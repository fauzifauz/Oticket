<!DOCTYPE html>
<html>
<head>
    <title>New User Registration</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #4f46e5;">New User Registration Alert</h2>
    <p>A new account has been registered and is currently <strong>awaiting approval</strong>.</p>
    
    <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; border-left: 4px solid #4f46e5;">
        <p style="margin: 0;"><strong>Name:</strong> {{ $user->name }}</p>
        <p style="margin: 5px 0;"><strong>Email:</strong> {{ $user->email }}</p>
        <p style="margin: 5px 0;"><strong>Requested Role:</strong> <span style="text-transform: uppercase; font-weight: bold;">{{ ucfirst($user->role) }}</span></p>
    </div>

    <p style="margin-top: 20px;">
        <a href="{{ route('admin.users.index') }}?type={{ $user->role === 'user' ? 'employee' : 'staff' }}" 
           style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
            Review Account
        </a>
    </p>

    <p style="font-size: 12px; color: #6b7280; margin-top: 30px;">
        This is an automated notification from OTICKET System.
    </p>
</body>
</html>
