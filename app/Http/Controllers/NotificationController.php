<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getActiveNotifications()
    {
        $user = Auth::user();
        $notifications = collect([]);
        $stats = [];

        if ($user->role === 'admin') {
            $unassignedTicketsCount = Ticket::whereNull('support_id')->count();
            
            $pendingUsers = \App\Models\User::where('status', false)->where('role', '!=', 'admin')->latest()->get();
            
            $stats = [
                'total' => Ticket::count(),
                'unassigned' => $unassignedTicketsCount,
                'pending_users' => $pendingUsers->count(),
            ];

            $ticketNotifications = Ticket::with('user')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function($t) use ($user) {
                    $hasViewed = ActivityLog::where('user_id', $user->id)
                        ->where('action', 'VIEW')
                        ->where('description', 'like', '%' . $t->id . '%')
                        ->where('created_at', '>=', $t->updated_at)
                        ->exists();

                    return [
                        'id' => $t->id,
                        'is_new' => !$hasViewed,
                        'created_at' => $t->created_at->diffForHumans(),
                        'title' => ($t->support_id ? 'Ticket Assignment' : 'NEW UNASSIGNED Ticket'),
                        'message' => '#' . $t->id . ': ' . $t->subject . ' (by ' . $t->user->name . ')',
                        'color' => !$t->support_id ? 'rose' : 'indigo',
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                        'route' => route('admin.tickets.show', $t->id)
                    ];
                });

            $userNotifications = $pendingUsers->take(3)->map(function($u) use ($user) {
                return [
                    'id' => 'u' . $u->id,
                    'is_new' => true,
                    'created_at' => $u->created_at->diffForHumans(),
                    'title' => 'PENDING APPROVAL',
                    'message' => 'New User: ' . $u->name . ' (' . ucfirst($u->role) . ')',
                    'color' => 'amber',
                    'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
                    'route' => route('admin.users.index') . '?type=' . ($u->role === 'user' ? 'employee' : 'staff') . '&status=0&search=' . urlencode($u->email)
                ];
            });

            $notifications = $userNotifications->concat($ticketNotifications)->take(7);

            $finalCount = $unassignedTicketsCount + $pendingUsers->count();
        } elseif ($user->role === 'support') {
            $handlingCount = Ticket::where('support_id', $user->id)
                ->whereHas('ticketStatus', function($q) {
                    $q->whereNotIn('slug', ['resolved', 'closed']);
                })->count();

            $stats = [
                'handling' => $handlingCount,
            ];

            $notifications = Ticket::where('support_id', $user->id)
                ->with('user')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function($t) use ($user) {
                    $hasViewed = ActivityLog::where('user_id', $user->id)
                        ->where('action', 'VIEW')
                        ->where('description', 'like', '%' . $t->id . '%')
                        ->where('created_at', '>=', $t->updated_at)
                        ->exists();

                    return [
                        'id' => $t->id,
                        'is_new' => !$hasViewed,
                        'created_at' => $t->created_at->diffForHumans(),
                        'title' => 'Assigned To You',
                        'message' => 'Ticket #' . $t->id . ': ' . $t->subject,
                        'color' => in_array($t->status, ['resolved', 'closed']) ? 'emerald' : 'amber',
                        'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        'route' => route('support.tickets.show', $t->id)
                    ];
                });

            $finalCount = $handlingCount;
        } else {
            // Employee/User: Count tickets that have new updates NOT YET VIEWED
            $myTickets = Ticket::where('user_id', $user->id)->get();
            $unseenCount = 0;

            $notifications = $myTickets->sortByDesc('updated_at')->take(5)->map(function($t) use ($user, &$unseenCount) {
                $hasViewed = ActivityLog::where('user_id', $user->id)
                    ->where('action', 'VIEW')
                    ->where('description', 'like', '%' . $t->id . '%')
                    ->where('created_at', '>=', $t->updated_at)
                    ->exists();

                if (!$hasViewed) $unseenCount++;

                return [
                    'id' => $t->id,
                    'is_new' => !$hasViewed,
                    'created_at' => $t->updated_at->diffForHumans(),
                    'title' => 'Ticket ' . ucfirst($t->status),
                    'message' => '#' . $t->id . ': ' . $t->subject,
                    'color' => $t->status === 'resolved' ? 'emerald' : 'indigo',
                    'icon' => $t->status === 'resolved' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z',
                    'route' => route('tickets.show', $t->id)
                ];
            });

            $finalCount = $unseenCount;
        }

        return response()->json([
            'notifications' => $notifications,
            'stats' => $stats,
            'count' => $finalCount
        ]);

    }
}