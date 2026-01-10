<!DOCTYPE html>
<html>
<head>
    <title>Ticket Created Successfully</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #4f46e5;">Ticket Confirmation</h2>
    <p>Dear {{ $ticket->user->name }},</p>
    <p>Your ticket has been recorded in our system and will be processed according to our service standards.</p>
    
    <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; border-left: 4px solid #4f46e5;">
        <p style="margin: 0;"><strong>Ticket ID:</strong> #{{ $ticket->id }}</p>
        <p style="margin: 5px 0;"><strong>Subject:</strong> {{ $ticket->subject }}</p>
        <p style="margin: 5px 0;"><strong>Category:</strong> {{ $ticket->category->name }}</p>
        <p style="margin: 5px 0;"><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
    </div>

    <p style="margin-top: 20px;">
        <a href="{{ route('tickets.show', $ticket->id) }}" 
           style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
            View My Ticket
        </a>
    </p>

    <p style="margin-top: 30px;">Best regards,<br><strong>OTICKET Support Team</strong></p>
</body>
</html>
