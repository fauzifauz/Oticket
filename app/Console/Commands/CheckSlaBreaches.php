<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckSlaBreaches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sla:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for SLA breaches and escalate tickets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $breachedTickets = \App\Models\Ticket::whereHas('ticketStatus', function($q) {
                $q->whereIn('slug', ['open', 'in_progress']);
            })
            ->where('sla_due_at', '<', now())
            ->where('priority', '!=', 'critical') 
            ->get();

        $nearingBreach = \App\Models\Ticket::whereHas('ticketStatus', function($q) {
                $q->whereIn('slug', ['open', 'in_progress']);
            })
            ->where('sla_due_at', '>', now())
            ->where('sla_due_at', '<', now()->addHour())
            ->get();

        foreach ($nearingBreach as $ticket) {
            if ($ticket->support) {
                \Illuminate\Support\Facades\Mail::to($ticket->support->email)->send(new \App\Mail\SlaReminder($ticket));
            }
        }

        foreach ($breachedTickets as $ticket) {
            $newPriority = match ($ticket->priority) {
                'low' => 'normal',
                'normal' => 'high',
                'high' => 'critical',
                default => $ticket->priority,
            };

            if ($newPriority !== $ticket->priority) {
                $ticket->update(['priority' => $newPriority]);
                
                \App\Models\ActivityLog::create([
                    'user_id' => 1,
                    'action' => 'sla_breach',
                    'description' => "Ticket #{$ticket->uuid} escalated to {$newPriority} due to SLA breach.",
                ]);

                $this->info("Escalated ticket #{$ticket->uuid}");
            }
        }

        $this->info('SLA check completed.');
    }
}
