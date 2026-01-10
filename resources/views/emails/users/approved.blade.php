<!DOCTYPE html>
<html>
<head>
    <title>Account Approved</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333 text-align: left;">
    <h2 style="color: #10b981;">Account Approved Successfully</h2>
    <p>Dear {{ $user->name }},</p>
    <p>We are pleased to inform you that your OTICKET account has been reviewed and **approved** by the administrator.</p>
    
    <div style="background-color: #f0fdf4; padding: 20px; border-radius: 8px; border-left: 4px solid #10b981;">
        <p style="margin: 0;"><strong>Account Status:</strong> <span style="color: #059669; font-weight: bold; text-transform: uppercase;">ACTIVE</span></p>
        <p style="margin: 5px 0;"><strong>Assigned Role:</strong> {{ ucfirst($user->role) }}</p>
    </div>

    <p style="margin-top: 20px;">You can now log in to the system using your registered credentials.</p>

    <p style="margin-top: 20px;">
        @if(in_array($user->role, ['admin', 'support']))
            <a href="{{ route('login') }}" 
               style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                Login to Dashboard
            </a>
        @else
            <a href="{{ route('employee.login') }}" 
               style="background-color: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                Login to Employee Portal
            </a>
        @endif
    </p>

    <p style="margin-top: 30px;">Welcome aboard,<br><strong>OTICKET Administration</strong></p>
</body>
</html>
