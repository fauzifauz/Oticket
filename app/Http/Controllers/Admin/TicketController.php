<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketAssigned;
use App\Mail\TicketStatusUpdated;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Ticket::with(['user', 'category', 'support']);
        $this->applyFilters($query, $request);

        $tickets = $query->latest()->paginate(10);
        $slaRules = \App\Models\SlaRule::all();
        $statuses = \App\Models\TicketStatus::orderBy('order')->withCount('tickets')->get();
        $totalTickets = Ticket::count();

        $priorityColors = $this->getPriorityColors();

        return view('admin.tickets.index', compact('tickets', 'slaRules', 'statuses', 'totalTickets', 'priorityColors'));
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

    protected function applyFilters($query, Request $request)
    {
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('category_name') && $request->category_name) {
            $names = explode(', ', $request->category_name);
            $query->whereHas('category', function($q) use ($names) {
                $q->whereIn('name', $names);
            });
        }

        if ($request->has('month') && $request->month) {
            $query->whereRaw("DATE_FORMAT(tickets.created_at, '%Y-%m') = ?", [$request->month]);
        }

        if ($request->has('year') && $request->year) {
            $query->whereRaw("YEAR(tickets.created_at) = ?", [$request->year]);
        }

        if ($request->has('status') && $request->status) {
            $query->ofStatus($request->status);
        }

        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                // If the search term is strictly numeric, we assume it might be an ID or part of a numeric subject
                if (is_numeric($request->search)) {
                    // Exact ID match OR
                    $q->where('id', $request->search)
                      // Continue to search other fields in case the number is in the subject/desc
                      ->orWhere('subject', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                } else {
                    // Standard string search
                    $q->where('subject', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%')
                      ->orWhere('uuid', 'like', '%' . $request->search . '%')
                      ->orWhereHas('user', function($uq) use ($request) {
                          $uq->where('name', 'like', '%' . $request->search . '%');
                      });
                }
            });
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->has('week_start') && $request->week_start) {
            $startDate = \Carbon\Carbon::parse($request->week_start);
            $endDate = $startDate->copy()->endOfWeek();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->has('overdue') && $request->overdue) {
            $query->whereDoesntHave('ticketStatus', function($q) {
                $q->whereIn('slug', ['closed', 'resolved']);
            })->where('sla_due_at', '<', now());
        }

        if ($request->has('assigned')) {
            if ($request->assigned === '0') {
                $query->whereNull('support_id');
            } elseif ($request->assigned === '1') {
                // If searching for specific support via dashboard
                if ($request->has('assigned_to') && $request->assigned_to) {
                    $query->whereHas('support', function($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->assigned_to . '%');
                    });
                } else {
                    $query->whereNotNull('support_id');
                }
            }
        }
        
        // Direct Support Search
        if ($request->has('assigned_to') && $request->assigned_to) {
            $query->whereHas('support', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->assigned_to . '%');
            });
        }

        if ($request->has('is_resolved') && $request->is_resolved) {
            $query->whereNotNull('resolved_at');
        }

        if ($request->has('is_unresolved') && $request->is_unresolved) {
            $query->whereNull('resolved_at');
        }

        if ($request->has('created_today') && $request->created_today) {
            $query->whereDate('created_at', today());
        }

        if ($request->has('department') && $request->department) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        if ($request->has('departments') && is_array($request->departments)) {
            $query->whereHas('user', function($q) use ($request) {
                $q->whereIn('department', $request->departments);
            });
        }

        // Temporal Filters
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
        if ($request->filled('month')) {
            $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$request->month]);
        }
        if ($request->filled('week')) {
            $date = \Carbon\Carbon::parse($request->week);
            $query->whereBetween('tickets.created_at', [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()]);
        }

        if ($request->has('rated') && $request->rated) {
            $query->whereHas('feedback');
        }

        if ($request->has('subject') && $request->subject) {
            $query->where('subject', $request->subject);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::with(['user', 'category', 'support', 'responses.user', 'attachments', 'feedback', 'ticketStatus'])
            ->findOrFail($id);
        
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'VIEW',
            'description' => 'Viewed ticket #' . $ticket->id,
        ]);

        $supportStaff = \App\Models\User::where('role', 'support')->where('status', true)->get();
        $statuses = \App\Models\TicketStatus::orderBy('order')->get();
        $slaRules = \App\Models\SlaRule::all();
        
        $priorityColors = $this->getPriorityColors();

        $similarTickets = $this->getSimilarTickets($ticket);

        return view('admin.tickets.show', compact('ticket', 'statuses', 'slaRules', 'supportStaff', 'priorityColors', 'similarTickets'));
    }

    protected function getSimilarTickets($ticket)
    {
        try {
            $scriptPath = base_path('app/AI/ticket_similarity.py');
            // Escape arguments safely
            $subject = escapeshellarg($ticket->subject);
            $description = escapeshellarg($ticket->description);
            
            // For Admin, we allow seeing global history for better oversight
            // Support will remain restricted to their own history via SupportController
            
            $pythonPath = base_path('venv/bin/python');
            if (!file_exists($pythonPath)) {
                $pythonPath = 'python3';
            }
            
            $command = "\"$pythonPath\" \"$scriptPath\" --subject=$subject --description=$description";
            
            $output = shell_exec($command);
            
            // Log output for debugging if needed
            // \Illuminate\Support\Facades\Log::info("AI Output: " . $output);
            
            $result = json_decode($output, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }
            
            // Check for error key
             if (isset($result['error'])) {
                \Illuminate\Support\Facades\Log::error("AI Script Error: " . $result['error']);
                return [];
            }

            return $result ?? [];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("AI Similarity Execution Failed: " . $e->getMessage());
            return [];
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        \Illuminate\Support\Facades\Log::info('Ticket update request', $request->all());
        $ticket = Ticket::findOrFail($id);
        
        // Ensure empty string support_id becomes null BEFORE validation
        if ($request->has('support_id') && $request->input('support_id') === '') {
            $request->merge(['support_id' => null]);
        }

        // Force lowercase priority to match validation rules
        if ($request->has('priority')) {
            $request->merge(['priority' => strtolower($request->priority)]);
        }
        
        $validated = $request->validate([
            'support_id' => 'nullable|exists:users,id', // Now we can safely check exists (null is skipped)
            'status' => 'required|exists:ticket_statuses,slug',
            'priority' => 'nullable|exists:sla_rules,priority',
        ]);

        $oldStatus = $ticket->status; 
        $oldSupport = $ticket->support_id;

        // Assign Support
        if (array_key_exists('support_id', $validated)) {
            $ticket->support_id = $validated['support_id'];
        }

        // Assign Status
        if (isset($validated['status'])) {
            $ticket->status = $validated['status'];
            if (in_array($validated['status'], ['resolved', 'closed'])) {
                if (!$ticket->resolved_at) {
                    $ticket->resolved_at = now();
                }
            } else {
                $ticket->resolved_at = null;
            }
        }

        // Assign Priority
        $priorityChanged = false;
        if (isset($validated['priority'])) {
            if ($ticket->priority !== $validated['priority']) {
                $ticket->priority = $validated['priority'];
                $priorityChanged = true;
            }
        }

        if ($priorityChanged) {
            $ticket->recalculateSla();
        }

        $ticket->save();

        // Log the updates
        $changes = [];
        if ($ticket->status != $oldStatus) $changes[] = "status to '{$ticket->status}'";
        if ($ticket->support_id != $oldSupport) $changes[] = "assignee to '" . ($ticket->support->name ?? 'Unassigned') . "'";
        if ($ticket->priority != $validated['priority']) $changes[] = "priority to '{$ticket->priority}'";
        
        if (!empty($changes)) {
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'UPDATE_TICKET',
                'description' => 'Updated ticket #' . $ticket->id . ' ' . implode(', ', $changes),
            ]);
        }

        // Send Email Notifications (Safe Mode)
        try {
            // Refresh logic if support changed
            if ($ticket->support_id && $ticket->support_id != $oldSupport) {
                // Manually load if not set
                if (!$ticket->relationLoaded('support')) {
                     $ticket->load('support');
                }
                
                if ($ticket->support) {
                     Mail::to($ticket->support)->send(new TicketAssigned($ticket));
                }
            }

            if ($oldStatus != $ticket->status || $priorityChanged) {
                Mail::to($ticket->user)->send(new TicketStatusUpdated($ticket));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Email notification failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Log deletion
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE',
            'description' => 'Deleted ticket #' . $ticket->id . ': ' . $ticket->subject,
        ]);

        $ticket->delete();

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    public function export(Request $request) {
        $query = \App\Models\Ticket::with(['user', 'category', 'support', 'ticketStatus']);
        $this->applyFilters($query, $request);
        $tickets = $query->latest()->get();
        
        $filename = "tickets_general_export_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Ticket UUID', 'Subject', 'User', 'Department', 'Phone', 'Category', 'Priority', 'Status', 'Assigned Support', 'Created At', 'Resolved At'];

        $callback = function() use($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->uuid,
                    $ticket->subject,
                    $ticket->user->name,
                    $ticket->user->department ?? 'N/A',
                    $ticket->user->phone ?? 'N/A',
                    $ticket->category->name,
                    $ticket->priority,
                    $ticket->status,
                    $ticket->support ? $ticket->support->name : 'Unassigned',
                    $ticket->created_at,
                    $ticket->resolved_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportMonthlyCsv(Request $request) {
        $monthStr = $request->month ?: date('Y-m');
        $startDate = \Carbon\Carbon::createFromFormat('Y-m', $monthStr)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $query = Ticket::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'category', 'support', 'ticketStatus']);
        
        $this->applyFilters($query, $request);
        $tickets = $query->latest()->get();
        
        $filename = "monthly_tickets_export_" . $monthStr . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Ticket UUID', 'Subject', 'User', 'Department', 'Phone', 'Category', 'Priority', 'Status', 'Assigned Support', 'Created At', 'Resolved At'];

        $callback = function() use($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->uuid,
                    $ticket->subject,
                    $ticket->user->name,
                    $ticket->user->department ?? 'N/A',
                    $ticket->user->phone ?? 'N/A',
                    $ticket->category->name,
                    $ticket->priority,
                    $ticket->status,
                    $ticket->support ? $ticket->support->name : 'Unassigned',
                    $ticket->created_at,
                    $ticket->resolved_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $query = \App\Models\Ticket::with(['user', 'category', 'support']);
        $this->applyFilters($query, $request);
        $tickets = $query->latest()->get();
        
        $stats = [
            'total' => $tickets->count(),
            'open' => $tickets->where('status', 'open')->count(),
            'in_progress' => $tickets->whereIn('status', ['in_progress', 'in-progress'])->count(),
            'resolved' => $tickets->where('status', 'resolved')->count(),
            'closed' => $tickets->where('status', 'closed')->count(),
            'avg_response_time' => 0,
            'avg_resolution_time' => 0,
            'avg_rating' => \App\Models\TicketFeedback::whereIn('ticket_id', $tickets->pluck('id'))->avg('rating') ?: 0
        ];

        // Use centralized calculations on the collection basis
        $stats['avg_response_time'] = \App\Models\Ticket::calculateAvgResponseTime($query);
        $stats['avg_resolution_time'] = \App\Models\Ticket::calculateAvgResolutionTime($query);
        $stats['sla_compliance'] = \App\Models\Ticket::calculateSlaCompliance($query);

        // Fetch Distribution Tables
        $categories = \App\Models\TicketCategory::withCount(['tickets' => function($q) use ($request) {
            $this->applyFilters($q, $request);
        }])->get();

        $priorities = $tickets->groupBy('priority')->map->count();

        $supportPerformance = \App\Models\User::where('role', 'support')
            ->where('status', true)
            ->withCount(['assignedTickets as resolved_count' => function($q) use ($request) {
                $q->whereRelation('ticketStatus', 'slug', 'resolved');
                $this->applyFilters($q, $request);
            }])
            ->get()
            ->map(function($user) use ($request) {
                $user->avg_rating = \App\Models\TicketFeedback::whereHas('ticket', function($q) use ($user, $request) {
                    $q->where('support_id', $user->id);
                    $this->applyFilters($q, $request);
                })->avg('rating') ?: 0;
                return $user;
            })
            ->sortByDesc('resolved_count');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.tickets.pdf', compact('tickets', 'stats', 'categories', 'priorities', 'supportPerformance'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('tickets_report_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportMonthlyPdf(Request $request)
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        if ($request->has('month') && $request->month) {
            try {
                $startDate = \Carbon\Carbon::createFromFormat('Y-m', $request->month)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
            } catch (\Exception $e) {
                // Keep default current month if format is invalid
            }
        }

        $allTickets = Ticket::whereBetween('created_at', [$startDate, $endDate])->with(['user', 'category', 'support', 'ticketStatus'])->latest()->get();
        $resolved = Ticket::whereRelation('ticketStatus', 'slug', 'resolved')
            ->whereBetween('resolved_at', [$startDate, $endDate])
            ->with(['user', 'category', 'support', 'ticketStatus'])
            ->latest('resolved_at')
            ->get();

        $stats = [
            'total_tickets' => $allTickets->count(),
            'resolved_tickets' => $resolved->count(),
            'closed_tickets' => $allTickets->where('status', 'closed')->count(),
            'avg_rating' => \App\Models\TicketFeedback::whereBetween('created_at', [$startDate, $endDate])->avg('rating') ?: 0,
            'sla_compliance' => 0,
            'avg_resolution_time' => 0,
            'avg_response_time' => 0,
        ];

        // Standardized Math
        // Standardized Math
        $query = Ticket::whereBetween('created_at', [$startDate, $endDate]);
        $stats['avg_response_time'] = Ticket::calculateAvgResponseTime($query);
        $stats['avg_resolution_time'] = Ticket::calculateAvgResolutionTime($query);
        $stats['sla_compliance'] = Ticket::calculateSlaCompliance($query);

        $categories = \App\Models\TicketCategory::withCount(['tickets' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        }])->get();

        $priorities = $allTickets->groupBy('priority')->map->count();

        $supportPerformance = \App\Models\User::where('role', 'support')
            ->withCount(['assignedTickets as resolved_count' => function($query) use ($startDate, $endDate) {
                $query->whereRelation('ticketStatus', 'slug', 'resolved')->whereBetween('resolved_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function($user) use ($startDate, $endDate) {
                $user->avg_rating = \App\Models\TicketFeedback::whereHas('ticket', function($q) use ($user, $startDate, $endDate) {
                    $q->where('support_id', $user->id)->whereBetween('resolved_at', [$startDate, $endDate]);
                })->avg('rating') ?: 0;
                return $user;
            });

        // Use ALL tickets for the layout, not just resolved ones
        $tickets = $allTickets;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.tickets.monthly_report', compact(
            'stats', 
            'supportPerformance', 
            'startDate', 
            'categories', 
            'priorities', 
            'tickets'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('monthly_performance_report_' . $startDate->format('Y-m') . '.pdf');
    }

    public function exportYearlyPdf(Request $request)
    {
        $year = $request->year ?: date('Y');
        $startDate = \Carbon\Carbon::createFromDate($year, 1, 1)->startOfYear();
        $endDate = $startDate->copy()->endOfYear();

        $allTickets = Ticket::whereBetween('created_at', [$startDate, $endDate])->with(['user', 'category', 'support', 'ticketStatus'])->latest()->get();
        $resolved = Ticket::whereRelation('ticketStatus', 'slug', 'resolved')
            ->whereBetween('resolved_at', [$startDate, $endDate])
            ->get();

        $stats = $this->calculateDetailedStats($startDate, $endDate, $allTickets, $resolved);

        $categories = \App\Models\TicketCategory::withCount(['tickets' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        }])->get();

        $priorities = $allTickets->groupBy('priority')->map->count();

        $supportPerformance = \App\Models\User::where('role', 'support')
            ->withCount(['assignedTickets as resolved_count' => function($query) use ($startDate, $endDate) {
                $query->whereRelation('ticketStatus', 'slug', 'resolved')->whereBetween('resolved_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function($user) use ($startDate, $endDate) {
                $user->avg_rating = \App\Models\TicketFeedback::whereHas('ticket', function($q) use ($user, $startDate, $endDate) {
                    $q->where('support_id', $user->id)->whereBetween('resolved_at', [$startDate, $endDate]);
                })->avg('rating') ?: 0;
                return $user;
            });

        $tickets = $allTickets;
        $isYearly = true;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.tickets.yearly_report', compact(
            'stats', 
            'supportPerformance', 
            'startDate', 
            'categories', 
            'priorities', 
            'tickets',
            'isYearly'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('yearly_performance_report_' . $year . '.pdf');
    }

    public function exportYearlyCsv(Request $request)
    {
        $year = $request->year ?: date('Y');
        $startDate = \Carbon\Carbon::createFromDate($year, 1, 1)->startOfYear();
        $endDate = $startDate->copy()->endOfYear();

        $tickets = Ticket::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'category', 'support', 'ticketStatus'])
            ->latest()
            ->get();
        
        $filename = "yearly_tickets_export_" . $year . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Ticket UUID', 'Subject', 'User', 'Department', 'Phone', 'Category', 'Priority', 'Status', 'Assigned Support', 'Created At', 'Resolved At'];

        $callback = function() use($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->uuid,
                    $ticket->subject,
                    $ticket->user->name,
                    $ticket->user->department ?? 'N/A',
                    $ticket->user->phone ?? 'N/A',
                    $ticket->category->name,
                    $ticket->priority,
                    $ticket->status,
                    $ticket->support ? $ticket->support->name : 'Unassigned',
                    $ticket->created_at,
                    $ticket->resolved_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function calculateDetailedStats($startDate, $endDate, $allTickets, $resolved)
    {
        $stats = [
            'total_tickets' => $allTickets->count(),
            'resolved_tickets' => $resolved->count(),
            'closed_tickets' => $allTickets->where('status', 'closed')->count(),
            'avg_rating' => \App\Models\TicketFeedback::whereBetween('created_at', [$startDate, $endDate])->avg('rating') ?: 0,
            'sla_compliance' => 0,
            'avg_resolution_time' => 0,
            'avg_response_time' => 0,
        ];

        // Standardized Math
        // Standardized Math
        $query = Ticket::whereBetween('created_at', [$startDate, $endDate]);
        $stats['avg_response_time'] = Ticket::calculateAvgResponseTime($query);
        $stats['avg_resolution_time'] = Ticket::calculateAvgResolutionTime($query);
        $stats['sla_compliance'] = Ticket::calculateSlaCompliance($query);

        return $stats;
    }
    public function storeResponse(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        \App\Models\TicketResponse::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);
        
        $ticket->touch(); 

        return redirect()->back()->with('success', 'Response posted successfully.');
    }
}
