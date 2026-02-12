<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketStatusUpdated;

class SupportController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Basic Stats
        $stats = [
            'assignedTickets' => Ticket::where('support_id', $user->id)->count(),
            'openTickets' => Ticket::where('support_id', $user->id)->ofStatus('open')->count(),
            'inProgressTickets' => Ticket::where('support_id', $user->id)->whereHas('ticketStatus', function($q) {
                $q->whereIn('slug', ['in_progress', 'in-progress']);
            })->count(),
            'resolvedTickets' => Ticket::where('support_id', $user->id)->whereHas('ticketStatus', function($q) {
                $q->whereIn('slug', ['resolved', 'closed']);
            })->count(),
        ];
        
        // SLA Warnings
        $slaWarnings = [
            'overdue' => Ticket::where('support_id', $user->id)
                ->whereDoesntHave('ticketStatus', function($q) {
                    $q->whereIn('slug', ['resolved', 'closed']);
                })
                ->where('sla_due_at', '<', now())
                ->count(),
            'dueSoon' => Ticket::where('support_id', $user->id)
                ->whereDoesntHave('ticketStatus', function($q) {
                    $q->whereIn('slug', ['resolved', 'closed']);
                })
                ->whereBetween('sla_due_at', [now(), now()->addHours(2)])
                ->count(),
        ];
        
        
        // Personal Performance Metrics
        $performance = [
            'myAvgResponseTime' => 0,
            'teamAvgResponseTime' => 0,
            'resolutionRate' => 0,
            'avgResolutionTime' => 0,
            'todayResolved' => 0,
        ];
        
        // Calculate MY Avg First Response Time (in minutes) - Each ticket weighted once
        $myAvgResponseMinutes = Ticket::where('support_id', $user->id)
            ->whereHas('responses', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, (
                SELECT MIN(created_at) 
                FROM ticket_responses 
                WHERE ticket_id = tickets.id 
                AND user_id = ?
            ))) as avg_time', [$user->id])
            ->value('avg_time');
        $performance['myAvgResponseTime'] = $myAvgResponseMinutes ? round($myAvgResponseMinutes, 1) : 0;
        
        // Calculate TEAM Avg First Response Time (in minutes) - System-wide, each ticket once
        $teamAvgResponseMinutes = Ticket::whereNotNull('support_id')
            ->whereHas('responses', function($q) {
                $q->whereColumn('user_id', '!=', 'tickets.user_id');
            })
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, (
                SELECT MIN(created_at) 
                FROM ticket_responses 
                WHERE ticket_id = tickets.id 
                AND user_id != tickets.user_id
            ))) as avg_time')
            ->value('avg_time');
        $performance['teamAvgResponseTime'] = $teamAvgResponseMinutes ? round($teamAvgResponseMinutes, 1) : 0;
        
        // Calculate Resolution Rate
        if ($stats['assignedTickets'] > 0) {
            $performance['resolutionRate'] = round(($stats['resolvedTickets'] / $stats['assignedTickets']) * 100, 1);
        }
        
        // Calculate Avg Resolution Time (in hours) - Optimized with raw SQL
        $avgResolutionHours = Ticket::where('support_id', $user->id)
            ->whereNotNull('resolved_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, resolved_at)) / 60 as avg_hours')
            ->value('avg_hours');
        $performance['avgResolutionTime'] = $avgResolutionHours ? round($avgResolutionHours, 1) : 0;
        
        // Today's Resolved Count
        $performance['todayResolved'] = Ticket::where('support_id', $user->id)
            ->whereHas('ticketStatus', function($q) {
                $q->whereIn('slug', ['resolved', 'closed']);
            })
            ->whereDate('resolved_at', today())
            ->count();
        
        // Rating Metrics
        $performance['avgRating'] = Ticket::where('support_id', $user->id)
            ->whereHas('feedback')
            ->with('feedback')
            ->get()
            ->avg('feedback.rating') ?: 0;
            
        $performance['totalRatings'] = Ticket::where('support_id', $user->id)
            ->whereHas('feedback')
            ->count();

        // Recent Feedbacks for Scrolling Ticker
        $recentFeedbacks = Ticket::where('support_id', $user->id)
            ->whereHas('feedback')
            ->with(['feedback', 'user'])
            ->latest('updated_at')
            ->take(10)
            ->get();
        
        $recentTickets = Ticket::where('support_id', $user->id)->latest()->take(5)->get();

        return view('support.dashboard', compact('stats', 'recentTickets', 'slaWarnings', 'performance', 'recentFeedbacks'));
    }

    public function tickets(Request $request)
    {
        $query = Ticket::where('support_id', auth()->id())->with(['user', 'category']);

        if ($request->has('status') && $request->status) {
            $query->ofStatus($request->status);
        }

        if ($request->has('is_resolved') && $request->is_resolved) {
            $query->whereNotNull('resolved_at');
        }

        if ($request->has('overdue') && $request->overdue) {
            $query->whereDoesntHave('ticketStatus', function($q) {
                $q->whereIn('slug', ['resolved', 'closed']);
            })->where('sla_due_at', '<', now());
        }

        if ($request->has('sla_warning') && $request->sla_warning) {
            $query->whereDoesntHave('ticketStatus', function($q) {
                $q->whereIn('slug', ['resolved', 'closed']);
            })->where('sla_due_at', '<', now()->addHours(2));
        }

        $tickets = $query->latest()->paginate(10);
        $statuses = \App\Models\TicketStatus::orderBy('order')->get();
        return view('support.tickets.index', compact('tickets', 'statuses'));
    }

    public function show($id)
    {
        $ticket = Ticket::where('support_id', auth()->id())->with(['user', 'category', 'responses.user', 'attachments', 'feedback'])->findOrFail($id);
        
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'VIEW',
            'description' => 'Viewed ticket #' . $ticket->id,
        ]);

        $statuses = \App\Models\TicketStatus::orderBy('order')->get();
        $slaRules = \App\Models\SlaRule::all();
        
        $similarTickets = $this->getSimilarTickets($ticket);

        return view('support.tickets.show', compact('ticket', 'statuses', 'slaRules', 'similarTickets'));
    }

    protected function getSimilarTickets($ticket)
    {
        try {
            $scriptPath = base_path('app/AI/ticket_similarity.py');
            // Escape arguments safely
            $subject = escapeshellarg($ticket->subject);
            $description = escapeshellarg($ticket->description);
            $supportId = auth()->id();
            
            // Construct the command
            // We use 2>&1 to capture stderr if needed, but json output is on stdout
            // Use venv python if available, otherwise fallback to system python
            $pythonPath = base_path('venv/bin/python');
            if (!file_exists($pythonPath)) {
                $pythonPath = 'python3';
            }
            
            $command = "\"$pythonPath\" \"$scriptPath\" --subject=$subject --description=$description --support_id=$supportId";
            
            $output = shell_exec($command);
            
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

    public function update(Request $request, $id)
    {
        $ticket = Ticket::where('support_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|exists:ticket_statuses,slug',
            'priority' => 'nullable|exists:sla_rules,priority',
            'message' => 'nullable|string',
        ]);

        $oldStatus = $ticket->status;
        $oldPriority = $ticket->priority;
        
        $ticket->status = $validated['status'];
        
        if (isset($validated['priority']) && $validated['priority'] !== $oldPriority) {
            $ticket->priority = $validated['priority'];
            $ticket->recalculateSla();
        }

        $ticket->save();

        $changes = [];
        if ($ticket->status != $oldStatus) $changes[] = "status from " . strtoupper($oldStatus) . " to " . strtoupper($validated['status']);
        if ($ticket->priority != $oldPriority) $changes[] = "priority from " . strtoupper($oldPriority) . " to " . strtoupper($ticket->priority);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_TICKET',
            'description' => 'Support updated ticket #' . $ticket->id . ': ' . implode(', ', $changes),
        ]);

        if (!empty($validated['message'])) {
            TicketResponse::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'message' => $validated['message'],
            ]);
        }
        
        // Handle logic for resolved_at if status becomes resolved
        if (in_array($validated['status'], ['resolved', 'closed'])) {
            if (!$ticket->resolved_at) {
                $ticket->update(['resolved_at' => now()]);
            }
        } else {
            // Reopened or moved to in_progress/pending
            $ticket->update(['resolved_at' => null]);
        }

        // Notify Employee if status or priority changed
        if ($oldStatus != $validated['status'] || $oldPriority != $ticket->priority) {
            try {
                Mail::to($ticket->user)->send(new TicketStatusUpdated($ticket));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Status update email failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('support.tickets.show', $ticket->id)->with('success', 'Ticket updated successfully.');
    }
}
