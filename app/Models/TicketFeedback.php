<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketFeedback extends Model
{
    protected $table = 'ticket_feedbacks';
    protected $fillable = ['ticket_id', 'rating', 'comment'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
