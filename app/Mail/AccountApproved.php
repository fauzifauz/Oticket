<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountApproved extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ACCOUNT APPROVED: Welcome to OTICKET System',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.users.approved',
        );
    }
}
