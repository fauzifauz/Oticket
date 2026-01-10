<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $totalResolvedQuery = Ticket::whereNotNull('resolved_at');
        $this->applyDateFilters($totalResolvedQuery, $request);
        $totalResolved = $totalResolvedQuery->count();

        $slaStats = [
            'compliance' => \App\Models\Ticket::calculateSlaCompliance($totalResolvedQuery),
            'total_with_sla' => (clone $totalResolvedQuery)->whereNotNull('sla_due_at')->count(),
            'compliant_count' => (clone $totalResolvedQuery)->whereNotNull('sla_due_at')
                ->where(function($q) {
                    $q->where(function($sq) {
                        $sq->whereNotNull('resolved_at')->whereRaw('resolved_at <= sla_due_at');
                    })->orWhere(function($sq) {
                        $sq->whereNull('resolved_at')->where('sla_due_at', '>=', now());
                    });
                })->count()
        ];
        $slaCompliance = $slaStats['compliance'];
        $resolvedWithinSla = $slaStats['compliant_count'];
        $totalWithSla = $slaStats['total_with_sla'];

        $avgResponseTime = \App\Models\Ticket::calculateAvgResponseTime($totalResolvedQuery);

        $catChartQuery = TicketCategory::leftJoin('tickets', 'ticket_categories.id', '=', 'tickets.category_id');
        $this->applyDateFilters($catChartQuery, $request);
        $ticketsByCategory = $catChartQuery->select('ticket_categories.name', DB::raw('count(tickets.id) as tickets_count'))
            ->groupBy('ticket_categories.id', 'ticket_categories.name')
            ->orderBy('tickets_count', 'desc')
            ->get()
            ->pluck('tickets_count', 'name');

        $impactQuery = Ticket::query();
        $this->applyDateFilters($impactQuery, $request);
        $topProblemsData = \App\Models\Ticket::getTopIssuesByImpact($impactQuery);
        
        $topProblemsLabels = $topProblemsData->pluck('subject')->toArray();
        $topProblemsValues = $topProblemsData->pluck('total_count')->toArray();

        $trendQuery = Ticket::query();
        if ($request->filled('year')) {
            $trendQuery->whereYear('created_at', $request->year)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, count(*) as count")
                ->groupBy('month')->orderBy('month');
        } elseif ($request->filled('month')) {
            $trendQuery->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$request->month])
                ->selectRaw("DATE(created_at) as date, count(*) as count")
                ->groupBy('date')->orderBy('date');
            $monthlyTrend = $trendQuery->get()->pluck('count', 'date');
        } else {
            $trendQuery->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, count(*) as count")
                ->groupBy('month')->orderBy('month');
        }
        
        if (!isset($monthlyTrend)) {
            $monthlyTrend = $trendQuery->get()->pluck('count', 'month');
        }

        $totalResolved = (clone $totalResolvedQuery)->whereNotNull('resolved_at')->count();

        $ratingQuery = \App\Models\TicketFeedback::whereHas('ticket', function($q) use ($request) {
            $q->whereNotNull('support_id');
            $this->applyDateFilters($q, $request);
        });
        $avgRating = $ratingQuery->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 1) : 0;

        $supportPerformance = \App\Models\User::where('role', 'support')
            ->where('status', true)
            ->withCount(['assignedTickets as resolved_count' => function($query) use ($request) {
                $query->whereNotNull('resolved_at');
                $this->applyDateFilters($query, $request);
            }])
            ->get()
            ->map(function($user) use ($request) {
                $avgResRating = \App\Models\TicketFeedback::whereHas('ticket', function($q) use ($user, $request) {
                    $q->where('support_id', $user->id);
                    $this->applyDateFilters($q, $request);
                })->avg('rating');
                $user->avg_rating = $avgResRating ? round($avgResRating, 1) : 0;
                return $user;
            })
            ->sortByDesc('avg_rating');

        return view('admin.analytics.index', compact(
            'slaCompliance', 
            'avgResponseTime', 
            'ticketsByCategory', 
            'topProblemsLabels',
            'topProblemsValues', 
            'monthlyTrend',
            'totalResolved',
            'resolvedWithinSla',
            'totalWithSla',
            'avgRating',
            'supportPerformance'
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
}
