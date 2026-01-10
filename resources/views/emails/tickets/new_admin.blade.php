<!DOCTYPE html>
<html>
<head>
    <title>New Ticket Submitted</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #4f46e5;">New Ticket Alert</h2>
    <p>A new ticket has been submitted by <strong>{{ $ticket->user->name }}</strong>.</p>
    
    <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; border-left: 4px solid #4f46e5;">
        <p style="margin: 0;"><strong>Ticket ID:</strong> #{{ $ticket->id }}</p>
        <p style="margin: 5px 0;"><strong>Subject:</strong> {{ $ticket->subject }}</p>
        <p style="margin: 5px 0;"><strong>Category:</strong> {{ $ticket->category->name }}</p>
        <p style="margin: 5px 0;"><strong>Priority:</strong> <span style="text-transform: uppercase; font-weight: bold;">{{ $ticket->priority }}</span></p>
        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 15px 0;">
        <p><strong>Description:</strong></p>
        <p>{{ $ticket->description }}</p>
    </div>

    <p style="margin-top: 20px;">
        <a href="{{ route('admin.tickets.show', $ticket->id) }}" 
           style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
            View Ticket Details
        </a>
    </p>

    <p style="font-size: 12px; color: #6b7280; margin-top: 30px;">
        This is an automated notification from OTICKET System.
    </p>
</body>
</html>
