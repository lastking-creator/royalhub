<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CBO Impact & Financial Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; margin: 40px; color: #1e293b; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 25px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary-box { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .card { border: 1px solid #e2e8f0; padding: 12px; border-radius: 6px; width: 30%; text-align: center; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
            Print / Save as PDF
        </button>
    </div>

    <div class="header">
        <h1 style="margin:0; font-size: 22px;">CBO Official Impact & Financial Report</h1>
        <p style="color: #64748b; margin-top: 5px;">Period: {{ $startDate }} to {{ $endDate }}</p>
    </div>

    <h3>Income / Donations</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount (KSh)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donations as $d)
                <tr>
                    <td>{{ $d->created_at->format('Y-m-d') }}</td>
                    <td>KSh {{ number_format($d->amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="2">No income records found for this period.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3>Expenses</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount (KSh)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $e)
                <tr>
                    <td>{{ $e->created_at->format('Y-m-d') }}</td>
                    <td>KSh {{ number_format($e->amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="2">No expense records found for this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>