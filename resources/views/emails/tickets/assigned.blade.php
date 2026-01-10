<!DOCTYPE html>
<html>
<head>
    <title>New Ticket Assigned</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #4f46e5;">New Assignment Alert</h2>
    <p>Hello Support Team,</p>
    <p>A new ticket has been assigned to you for handling.</p>
    
    <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; border-left: 4px solid #4f46e5;">
        <p style="margin: 0;"><strong>Ticket ID:</strong> #{{ $ticket->id }}</p>
        <p style="margin: 5px 0;"><strong>Subject:</strong> {{ $ticket->subject }}</p>
        <p style="margin: 5px 0;"><strong>Priority:</strong> <span style="text-transform: uppercase; font-weight: bold;">{{ ucfirst($ticket->priority) }}</span></p>
    </div>

    <p style="margin-top: 20px;">
        @php
            $route = ($ticket->support && $ticket->support->role === 'admin') 
                ? route('admin.tickets.show', $ticket->id) 
                : route('support.tickets.show', $ticket->id);
        @endphp
        <a href="{{ $route }}" 
           style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
            Handle Ticket
        </a>
    </p>

    <p style="font-size: 12px; color: #6b7280; margin-top: 30px;">
        This is an automated notification from OTICKET System.
    </p>
</body>
</html>
