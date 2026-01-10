<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketAssigned extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Ticket Assigned: ' . $this->ticket->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tickets.assigned',
        );
    }
}
