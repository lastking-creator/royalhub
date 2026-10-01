<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $event->title }} - Attendance Sheet</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; font-size: 13px; color: #1e293b; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Print Report</button>
    </div>

    <div class="header">
        <h1 style="margin:0;">{{ $event->title }}</h1>
        <p style="color: #64748b;">Type: {{ ucfirst($event->type) }} | Date: {{ \Carbon\Carbon::parse($event->start_time)->format('Y-m-d H:i') }} | Location: {{ $event->location ?? 'N/A' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Member Name</th>
                <th>Email</th>
                <th>RSVP Status</th>
                <th>Check-in Status</th>
                <th>Check-in Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $att)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $att->user->name }}</td>
                    <td>{{ $att->user->email }}</td>
                    <td>{{ ucfirst($att->rsvp_status) }}</td>
                    <td>{{ $att->checked_in ? 'Checked In' : 'Absent' }}</td>
                    <td>{{ $att->checked_in_at ? \Carbon\Carbon::parse($att->checked_in_at)->setTimezone('Africa/Nairobi')->format('h:i:s A') : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No records available.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>