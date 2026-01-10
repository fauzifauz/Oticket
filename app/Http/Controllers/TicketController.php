<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketResponse;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index(Request $request)
    {
         $query = Ticket::where('user_id', auth()->id())->with('category');
         
         if ($request->has('status') && $request->status) {
             $query->ofStatus($request->status);
         }
         
         if ($request->has('resolved_today') && $request->resolved_today) {
             $query->whereDate('resolved_at', today());
         }

         if ($request->has('active') && $request->active) {
             $query->whereDoesntHave('ticketStatus', function($q) {
                 $q->whereIn('slug', ['resolved', 'closed']);
             });
         }
         
         $tickets = $query->latest()->paginate(10);
         
         // Fetch recent activities (incoming responses)
         $recentActivities = TicketResponse::whereHas('ticket', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->where('user_id', '!=', auth()->id())
            ->with(['ticket', 'user'])
            ->latest()
            ->take(5)
            ->get();
            
         // System Broadcast Content
         $broadcast = [
             'title' => \App\Models\CmsContent::where('key', 'announcement_title')->value('value') ?? 'System Broadcast',
             'message' => \App\Models\CmsContent::where('key', 'announcement_message')->value('value') ?? 'No announcements at this time.',
         ];
            
         return view('dashboard', compact('tickets', 'recentActivities', 'broadcast'));
    }

    public function create()
    {
        $categories = TicketCategory::all();
        return view('tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:ticket_categories,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $category = TicketCategory::findOrFail($validated['category_id']);
        $priority = $category->priority ?? 'normal'; // Default to normal if not set

        $sla = \App\Models\SlaRule::where('priority', $priority)->first();
        $slaDueAt = $sla ? now()->addMinutes($sla->resolution_time_minutes) : null;

        $ticket = Ticket::create([
            'uuid' => Str::uuid(),
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => $priority,
            'status_id' => \App\Models\TicketStatus::where('slug', 'open')->first()->id ?? 1, // Fallback to 1 if not found
            'sla_due_at' => $slaDueAt,
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE',
            'description' => 'Created ticket #' . $ticket->id . ': ' . $ticket->subject,
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();
            $path = $file->store('attachments/' . $ticket->id, 'public');

            \App\Models\TicketAttachment::create([
                'ticket_id' => $ticket->id,
                'file_path' => $path,
                'file_name' => $fileName,
            ]);
        }



        // Send Emails
        // 1. Notify the Employee (Creator)
        \Illuminate\Support\Facades\Mail::to(auth()->user())->send(new \App\Mail\TicketCreated($ticket));

        // 2. Notify All Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            \Illuminate\Support\Facades\Mail::to($admins)->send(new \App\Mail\NewTicketAdmin($ticket));
        }

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Ticket created successfully.');
    }

    public function show($id)
    {
        $ticket = Ticket::where('user_id', auth()->id())->with(['category', 'responses.user', 'attachments'])->findOrFail($id);
        
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'VIEW',
            'description' => 'Viewed ticket #' . $ticket->id,
        ]);

        return view('tickets.show', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($id);
        
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        TicketResponse::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);
        
        $ticket->touch(); // Update updated_at

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Response added.');
    }

    public function feedback(Request $request, $id)
    {
        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        \App\Models\TicketFeedback::create([
            'ticket_id' => $ticket->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }
}
