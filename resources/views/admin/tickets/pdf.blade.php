<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Support Tickets Report</title>
    <style>
        {!! file_get_contents(resource_path('views/admin/tickets/pdf_styles.css')) !!}
    </style>
</head>
<body>
    <div class="header-container">
        <h1 class="header-title">OTicket System</h1>
        <p class="header-subtitle">Official Support Infrastructure &bull; General Asset Ledger Report</p>
        <p class="header-subtitle" style="margin-top: 15px;">GENERATED ON: {{ date('d M Y H:i:s') }}</p>
    </div>

    <div class="content-container">
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-label">Volume</div>
                <div class="summary-value">{{ $stats['total'] }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">SLA Compliance</div>
                <div class="summary-value" style="color: #2563eb;">{{ $stats['sla_compliance'] }}%</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Resolved</div>
                <div class="summary-value" style="color: #059669;">{{ $stats['resolved'] }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Avg Resolution</div>
                <div class="summary-value" style="color: #7c3aed;">{{ $stats['avg_resolution_time'] }}m</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Avg Response</div>
                <div class="summary-value" style="color: #db2777;">{{ $stats['avg_response_time'] }}m</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Open</div>
                <div class="summary-value" style="color: #ef4444;">{{ $stats['open'] }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Closed</div>
                <div class="summary-value" style="color: #64748b;">{{ $stats['closed'] }}</div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Satisfaction</div>
                <div class="summary-value" style="color: #f59e0b;">{{ number_format($stats['avg_rating'], 1) }}/5</div>
            </div>
        </div>

        <div class="grid-2">
            <div class="col">
                <h3 class="section-title">Category Breakdown</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th style="text-align: right;">Tickets</th>
                            <th style="text-align: right;">Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td style="font-weight: 800;">{{ strtoupper($category->name) }}</td>
                            <td style="text-align: right;">{{ $category->tickets_count }}</td>
                            <td style="text-align: right;">{{ $stats['total'] > 0 ? round(($category->tickets_count / $stats['total']) * 100) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col">
                <h3 class="section-title">Priority Distribution</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Priority</th>
                            <th style="text-align: right;">Count</th>
                            <th style="text-align: right;">Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($priorities as $priority => $count)
                        @php $prioClass = 'prio-' . strtolower($priority); @endphp
                        <tr>
                            <td><span class="badge {{ $prioClass }}">{{ strtoupper($priority) }}</span></td>
                            <td style="text-align: right; font-weight: 800;">{{ $count }}</td>
                            <td style="text-align: right;">{{ $stats['total'] > 0 ? round(($count / $stats['total']) * 100) : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <h3 class="section-title">Support Team Audit</h3>
        <table>
            <thead>
                <tr>
                    <th width="35%">Operative Name</th>
                    <th width="15%">Resolved</th>
                    <th width="25%">Quality Score</th>
                    <th width="25%">Efficiency</th>
                </tr>
            </thead>
            <tbody>
                @foreach($supportPerformance as $user)
                <tr>
                    <td>
                        <div style="font-weight: 800; color: #1e293b;">{{ $user->name }}</div>
                        <div style="font-size: 6px; color: #94a3b8;">STF-{{ $user->id }} • SUPPORT SPECIALIST</div>
                    </td>
                    <td style="font-weight: 800; font-size: 10px; color: #1e3a8a;">{{ $user->resolved_count }}</td>
                    <td>
                        <div style="margin-bottom: 2px;">
                            @for($i = 1; $i <= 5; $i++)
                                <span style="color: {{ $i <= round($user->avg_rating) ? '#f59e0b' : '#e2e8f0' }}; font-size: 8px;">★</span>
                            @endfor
                        </div>
                        <span style="font-size: 6px; font-weight: 800;">{{ number_format($user->avg_rating, 1) }} Quality Ind.</span>
                    </td>
                    <td>
                        <div class="performance-bar">
                            @php $eff = $stats['resolved'] > 0 ? ($user->resolved_count / $stats['resolved']) * 100 : 0; @endphp
                            <div class="performance-fill" style="width: {{ min(100, $eff * 2) }}%"></div>
                        </div>
                        <div style="font-size: 5px; color: #94a3b8; font-weight: 800;">RELATIVE OUTPUT</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="page-break"></div>
        <div class="header-container" style="padding: 15px 30px;">
            <h1 class="header-title" style="font-size: 14px;">Ticket Ledger Annex</h1>
        </div>

        <h3 class="section-title">Ticket Inventory Ledger</h3>
        <table>
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th width="15%">Subject</th>
                    <th width="12%">Reporter</th>
                    <th width="10%">Dept</th>
                    <th width="10%">Category</th>
                    <th width="8%">Priority</th>
                    <th width="10%">Status</th>
                    <th width="12%">Assignee</th>
                    <th width="15%">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                @php
                    $prioClass = 'prio-' . strtolower($ticket->priority);
                    $statusColors = [
                        'open' => '#2563eb',
                        'pending' => '#8b5cf6',
                        'in_progress' => '#f59e0b',
                        'in-progress' => '#f59e0b',
                        'resolved' => '#10b981',
                        'closed' => '#4b5563',
                    ];
                    $sColor = $statusColors[$ticket->status] ?? '#6b7280';
                @endphp
                <tr>
                    <td style="font-family: monospace; color: #64748b;">#{{ strtoupper(substr($ticket->uuid, 0, 8)) }}</td>
                    <td style="text-transform: uppercase;">{{ \Illuminate\Support\Str::limit($ticket->subject, 25) }}</td>
                    <td>{{ $ticket->user->name }}</td>
                    <td>{{ $ticket->user->department ?? 'N/A' }}</td>
                    <td style="color: #1e3a8a; font-weight: 800;">{{ strtoupper($ticket->category->name) }}</td>
                    <td><span class="badge {{ $prioClass }}">{{ $ticket->priority }}</span></td>
                    <td style="color: {{ $sColor }}; font-weight: 800;">{{ strtoupper(str_replace('_', ' ', $ticket->status)) }}</td>
                    <td>{{ $ticket->support ? $ticket->support->name : 'UNASSIGNED' }}</td>
                    <td style="color: #64748b;">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p class="footer-text">OFFICIAL GENERAL PERFORMANCE AUDIT &bull; OTICKET INFRASTRUCTURE PROTOCOL</p>
        <p class="footer-text">Organization Confidential &bull; &copy; {{ date('Y') }} OTicket System</p>
        <p class="verification-code">VERIFICATION: {{ strtoupper(md5(time())) }}</p>
    </div>
</body>
</html>
