<!DOCTYPE html>
<html>
<head>
    <title>SLA Reminder</title>
</head>
<body>
    <h1 style="color: red;">[SLA ALERT] Ticket Nearing Deadline</h1>
    <p>The following ticket is reaching its SLA deadline in less than 1 hour.</p>
    <p><strong>Ticket ID:</strong> {{ $ticket->uuid }}</p>
    <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
    <p><strong>Due At:</strong> {{ $ticket->sla_due_at->format('Y-m-d H:i:s') }}</p>
    <p><a href="{{ route('support.tickets.show', $ticket->id) }}">Take Immediate Action</a></p>
</body>
</html>
