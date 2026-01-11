<!DOCTYPE html>
<html>
<head>
    <title>Ticket Status Updated</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #4f46e5;">Ticket Progress Update</h2>
    <p>Dear {{ $ticket->user->name }},</p>
    <p>There has been an update regarding the status of your ticket.</p>
    
    <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; border-left: 4px solid #10b981;">
        <p style="margin: 0;"><strong>Ticket ID:</strong> #{{ $ticket->id }}</p>
        <p style="margin: 5px 0;"><strong>Subject:</strong> {{ $ticket->subject }}</p>
        <p style="margin: 5px 0;"><strong>Current Status:</strong> <span style="text-transform: uppercase; font-weight: bold; color: #059669;">{{ str_replace('_', ' ', strtoupper($ticket->status)) }}</span></p>
        <p style="margin: 5px 0;"><strong>Current Priority:</strong> <span style="text-transform: uppercase; font-weight: bold; color: #b45309;">{{ strtoupper($ticket->priority) }}</span></p>
        @if($ticket->support)
        <p style="margin: 5px 0;"><strong>Handled By:</strong> <span style="color: #4f46e5; font-weight: bold;">{{ $ticket->support->name }}</span> ({{ ucfirst($ticket->support->role) }})</p>
        @endif
    </div>

    <p style="margin-top: 20px;">
        <a href="{{ route('tickets.show', $ticket->id) }}" 
           style="background-color: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
            View Progress Details
        </a>
    </p>

    <p style="margin-top: 30px;">Thank you for your patience,<br><strong>OTICKET Support Team</strong></p>
</body>
</html>
