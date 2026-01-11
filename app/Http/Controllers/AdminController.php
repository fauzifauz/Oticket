<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();
        $this->applyDateFilters($query, $request);
        
        $totalTickets = $query->count();
        
        // Dynamic Status Counts
        $statusCounts = \App\Models\TicketStatus::with(['tickets' => function($q) use ($request) {
            $this->applyDateFilters($q, $request);
        }])->orderBy('order')->get()
        ->map(function($status) {
            $status->tickets_count = $status->tickets->count();
            return $status;
        });

        // High Priority "Unresolved"
        $hpQuery = Ticket::whereNull('resolved_at')->where('priority', 'high');
        $this->applyDateFilters($hpQuery, $request);
        $highPriorityUnresolved = $hpQuery->count();

        $assignedQuery = Ticket::whereNotNull('support_id');
        $this->applyDateFilters($assignedQuery, $request);
        $assignedTickets = $assignedQuery->count();
        
        $overdueQuery = Ticket::whereNull('resolved_at')->where('sla_due_at', '<', now());
        $this->applyDateFilters($overdueQuery, $request);
        $overdueTickets = $overdueQuery->count();
        
        $logQuery = \App\Models\ActivityLog::where('action', 'DELETE');
        if ($request->filled('year')) $logQuery->whereYear('created_at', $request->year);
        if ($request->filled('month')) $logQuery->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$request->month]);
        if ($request->filled('week')) {
            $date = \Carbon\Carbon::parse($request->week);
            $logQuery->whereBetween('created_at', [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()]);
        }
        $deletedTickets = $logQuery->count();

        $activeSupport = User::where('role', 'support')->where('status', true)->count();
        
        // Avg Service Time (Resolution)
        $avgResolutionTimeMin = Ticket::calculateAvgResolutionTime($query);
        $avgServiceTime = $avgResolutionTimeMin > 60 
            ? round($avgResolutionTimeMin / 60, 1) . ' hrs' 
            : round($avgResolutionTimeMin, 1) . ' mins';

        // Avg Response Time
        $responseQuery = Ticket::whereNotNull('support_id')->whereHas('responses', function($q) {
            $q->whereColumn('user_id', '!=', 'tickets.user_id');
        });
        $this->applyDateFilters($responseQuery, $request);
        $avgResponseTime = $responseQuery->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, (
                SELECT MIN(created_at) 
                FROM ticket_responses 
                WHERE ticket_id = tickets.id 
                AND user_id != tickets.user_id
            ))) as avg_time')
            ->value('avg_time');
        $avgResponseTime = $avgResponseTime ? round($avgResponseTime, 1) . ' mins' : 'N/A';

        // Chart Data
        $statusChartQuery = Ticket::join('ticket_statuses', 'tickets.status_id', '=', 'ticket_statuses.id');
        $this->applyDateFilters($statusChartQuery, $request);
        $ticketsByStatus = $statusChartQuery->selectRaw('ticket_statuses.slug as status, count(*) as count')->groupBy('ticket_statuses.slug')->pluck('count', 'status');

        $prioChartQuery = Ticket::query();
        $this->applyDateFilters($prioChartQuery, $request);
        $ticketsByPriority = $prioChartQuery->selectRaw('priority, count(*) as count')->groupBy('priority')->pluck('count', 'priority');
        
        $catChartQuery = \App\Models\TicketCategory::leftJoin('tickets', 'ticket_categories.id', '=', 'tickets.category_id');
        if ($request->filled('year')) $catChartQuery->whereYear('tickets.created_at', $request->year);
        if ($request->filled('month')) $catChartQuery->whereRaw("DATE_FORMAT(tickets.created_at, '%Y-%m') = ?", [$request->month]);
        if ($request->filled('week')) {
            $date = \Carbon\Carbon::parse($request->week);
            $catChartQuery->whereBetween('tickets.created_at', [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()]);
        }
        $ticketsByCategory = $catChartQuery->select('ticket_categories.name', \DB::raw('count(tickets.id) as tickets_count'))
            ->groupBy('ticket_categories.name')
            ->orderBy('tickets_count', 'desc')
            ->get()
            ->pluck('tickets_count', 'name');
        
        // Ingress Trend
        $trendQuery = Ticket::query();
        if ($request->filled('year')) {
            $trendQuery->whereYear('created_at', $request->year)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as date, count(*) as count")
                ->groupBy('date')->orderBy('date');
        } elseif ($request->filled('month')) {
            $trendQuery->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$request->month])
                ->selectRaw("DATE(created_at) as date, count(*) as count")
                ->groupBy('date')->orderBy('date');
        } elseif ($request->filled('week')) {
            $date = \Carbon\Carbon::parse($request->week);
            $trendQuery->whereBetween('created_at', [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()])
                ->selectRaw("DATE(created_at) as date, count(*) as count")
                ->groupBy('date')->orderBy('date');
        } else {
            $trendQuery->where('created_at', '>=', now()->subWeeks(12))
                ->selectRaw('MIN(DATE(created_at)) as date, count(*) as count')
                ->groupByRaw('YEARWEEK(created_at, 1)')->orderByRaw('MIN(created_at)');
        }
        $ticketsPerDay = $trendQuery->pluck('count', 'date');

        $statusColors = \App\Models\TicketStatus::pluck('color', 'slug');
        $priorityColors = $this->getPriorityColors();

        $ratingQuery = \App\Models\TicketFeedback::whereHas('ticket', function($q) use ($request) {
            $q->whereNotNull('support_id');
            $this->applyDateFilters($q, $request);
        });
        $avgRating = $ratingQuery->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 1) : 0;

        // Python Data Mining Integration with Cache
        $cacheKey = 'mining_data_' . md5(serialize($request->only(['year', 'month', 'week'])));
        $miningData = Cache::remember($cacheKey, now()->addMinutes(10), function() use ($request) {
            try {
                $pythonPath = base_path('app/AI/venv/bin/python3');
                $scriptPath = base_path('app/AI/mining_service.py');
                
                $dbConfig = config('database.connections.mysql');
                $args = [
                    "--db_host=" . escapeshellarg($dbConfig['host']),
                    "--db_user=" . escapeshellarg($dbConfig['username']),
                    "--db_pass=" . escapeshellarg($dbConfig['password'] ?? ''),
                    "--db_name=" . escapeshellarg($dbConfig['database']),
                ];

                if ($request->filled('year')) $args[] = "--year=" . escapeshellarg($request->year);
                if ($request->filled('month')) $args[] = "--month=" . escapeshellarg($request->month);
                if ($request->filled('week')) $args[] = "--week=" . escapeshellarg($request->week);
                
                $argString = implode(' ', $args);
                $output = shell_exec("$pythonPath $scriptPath $argString 2>&1");
                
                if ($output) {
                    $jsonStart = strpos($output, '{');
                    if ($jsonStart !== false) {
                        $jsonOutput = substr($output, $jsonStart);
                        return json_decode($jsonOutput, true);
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Mining failed: ' . $e->getMessage());
            }
            return null;
        });

        return view('admin.dashboard', compact(
            'totalTickets', 'highPriorityUnresolved', 'statusCounts', 'activeSupport',
            'assignedTickets', 'overdueTickets', 'deletedTickets',
            'avgServiceTime', 'avgResponseTime', 'avgRating',
            'ticketsByStatus', 'ticketsByPriority', 'ticketsByCategory', 'ticketsPerDay',
            'statusColors', 'priorityColors', 'miningData'
        ));
    }

    private function applyDateFilters($query, Request $request)
    {
        if ($request->filled('year')) {
            $query->whereYear('tickets.created_at', $request->year);
        }
        if ($request->filled('month')) {
            $query->whereRaw("DATE_FORMAT(tickets.created_at, '%Y-%m') = ?", [$request->month]);
        }
        if ($request->filled('week')) {
            $date = \Carbon\Carbon::parse($request->week);
            $query->whereBetween('tickets.created_at', [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()]);
        }
    }

    private function getPriorityColors()
    {
        $content = \App\Models\CmsContent::where('key', 'sla_priority_colors')->first();
        if (!$content) {
            return [
                'critical' => 'rose',
                'high' => 'amber',
                'normal' => 'indigo',
                'low' => 'emerald',
            ];
        }
        return json_decode($content->value, true) ?: [];
    }
}
