<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Organization User Directory - OTicket</title>
    <style>
        @page { size: A4 landscape; margin: 1cm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 8px; color: #1e293b; line-height: 1.3; margin: 0; padding: 0; }
        
        /* Corporate Header */
        .header-wrapper { border-bottom: 2px solid #6366f1; padding-bottom: 15px; margin-bottom: 20px; }
        .branding { float: left; width: 40%; }
        .branding h1 { font-size: 24px; font-weight: 900; color: #0f172a; margin: 0; letter-spacing: -1px; }
        .branding span { font-size: 8px; color: #6366f1; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
        .meta { float: right; width: 60%; text-align: right; }
        .meta h2 { font-size: 14px; font-weight: 800; color: #1e1b4b; margin: 0; text-transform: uppercase; }
        .meta p { margin: 3px 0 0 0; color: #64748b; font-size: 7px; font-weight: 600; }
        .clearfix { clear: both; }

        /* Stats Dashboard */
        .dashboard { margin-bottom: 20px; background: #fdfdfd; border-radius: 8px; padding: 12px; border: 1px solid #e2e8f0; }
        .dashboard-table { width: 100%; border: none; }
        .stat-card { border: none; text-align: center; border-right: 1px solid #e2e8f0; }
        .stat-card:last-child { border-right: none; }
        .stat-label { display: block; font-size: 6px; color: #64748b; text-transform: uppercase; font-weight: 800; margin-bottom: 2px; }
        .stat-value { display: block; font-size: 14px; font-weight: 900; color: #0f172a; }

        /* Table Design */
        .content-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .content-table th { background-color: #0f172a; color: #ffffff; text-transform: uppercase; font-size: 7px; font-weight: 700; padding: 10px 6px; text-align: center; border: 1px solid #0f172a; }
        .content-table td { padding: 8px 6px; border: 1px solid #e2e8f0; vertical-align: middle; text-align: center; }
        .content-table tr:nth-child(even) { background-color: #f8fafc; }
        
        .text-left { text-align: left !important; }
        
        /* Badges */
        .badge { padding: 2px 4px; border-radius: 3px; font-size: 6.5px; font-weight: 800; text-transform: uppercase; display: inline-block; }
        .badge-admin { background-color: #fee2e2; color: #991b1b; }
        .badge-support { background-color: #fef3c7; color: #92400e; }
        .badge-user { background-color: #e0e7ff; color: #3730a3; }
        
        .status-pill { font-weight: 800; font-size: 7px; text-transform: uppercase; }
        .status-approved { color: #059669; }
        .status-pending { color: #94a3b8; }

        .name-bold { font-weight: 800; color: #0f172a; font-size: 9px; }
        .mono { font-family: monospace; font-size: 7.5px; color: #64748b; }

        /* Footer */
        .footer { position: fixed; bottom: -10px; width: 100%; border-top: 1px solid #e2e8f0; padding-top: 8px; }
        .footer-table { width: 100%; border: none; }
        .footer-td { border: none; padding: 0; color: #94a3b8; font-size: 7px; font-weight: 600; }
        .page-counter:before { content: "Page " counter(page); }

        .signature-box { float: right; width: 150px; text-align: center; border-top: 1px solid #0f172a; padding-top: 8px; margin-top: 20px; font-weight: bold; font-size: 7px; }
    </style>
</head>
<body>
    <div class="header-wrapper">
        <div class="branding">
            <h1>OTICKET</h1>
            <span>Information Technology Infrastructure</span>
        </div>
        <div class="meta">
            <h2>Organization User Directory Report</h2>
            <p>GENERATED ON: {{ date('d F Y | H:i') }} | EXPORTED BY: ADMINISTRATOR</p>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="dashboard">
        <table class="dashboard-table">
            <tr>
                <td class="stat-card">
                    <span class="stat-label">Total Organization</span>
                    <span class="stat-value">{{ count($users) }}</span>
                </td>
                <td class="stat-card">
                    <span class="stat-label">Administrative Staff</span>
                    <span class="stat-value">{{ $users->where('role', 'admin')->count() }}</span>
                </td>
                <td class="stat-card">
                    <span class="stat-label">Support</span>
                    <span class="stat-value">{{ $users->where('role', 'support')->count() }}</span>
                </td>
                <td class="stat-card">
                    <span class="stat-label">Authorized Users</span>
                    <span class="stat-value">{{ $users->where('role', 'user')->count() }}</span>
                </td>
                <td class="stat-card">
                    <span class="stat-label">Waitlist / Pending</span>
                    <span class="stat-value">{{ $users->where('status', false)->count() }}</span>
                </td>
            </tr>
        </table>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th width="30px">ID</th>
                <th class="text-left">Name</th>
                <th class="text-left">Email Address</th>
                <th width="60px">Role</th>
                <th width="40px">Tickets</th>
                <th width="60px">Status</th>
                <th>Department</th>
                <th width="80px">Phone</th>
                <th width="70px">Created</th>
                <th width="70px">Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="mono">{{ $user->id }}</td>
                <td class="text-left"><div class="name-bold">{{ $user->name }}</div></td>
                <td class="text-left" style="color: #64748b;">{{ $user->email }}</td>
                <td><span class="badge badge-{{ $user->role }}">{{ $user->role }}</span></td>
                <td style="font-weight: 800; color: #6366f1;">{{ $user->tickets_count }}</td>
                <td>
                    <span class="status-pill {{ $user->status ? 'status-approved' : 'status-pending' }}">
                        {{ $user->status ? 'APPROVED' : 'PENDING' }}
                    </span>
                </td>
                <td class="text-left" style="font-weight: 600;">{{ $user->department ?? '-' }}</td>
                <td style="color: #475569;">{{ $user->phone ?? '-' }}</td>
                <td style="color: #94a3b8;">{{ $user->created_at->format('d M Y') }}</td>
                <td style="color: #94a3b8;">{{ $user->updated_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-box" style="margin-top: 30px;">
        Authorized Signature
        <div style="font-weight: normal; font-size: 6px; margin-top: 3px; color: #94a3b8;">Digitally Generated by OTicket System</div>
    </div>
    <div class="clearfix"></div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-td">Internal Document | Confidential | {{ date('Y') }} OTicket Management</td>
                <td class="footer-td" style="text-align: right;"><span class="page-counter"></span></td>
            </tr>
        </table>
    </div>
</body>
</html>
