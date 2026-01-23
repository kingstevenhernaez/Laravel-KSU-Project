<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Alumni Report</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        h2 { margin: 0 0 6px 0; }
        .meta { margin: 0 0 16px 0; color: #333; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; }
        th { background: #f5f5f5; text-align: left; }
        .text-end { text-align: right; }
        @media print { .no-print { display:none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:12px;">
        <button onclick="window.print()">Print</button>
    </div>

    <h2>KSU Alumni Report</h2>
    <div class="meta">
        @if(!empty($filters['graduation_year'])) Graduation Year: <strong>{{ $filters['graduation_year'] }}</strong> &nbsp; @endif
        @if(!empty($filters['program_name'])) Program: <strong>{{ $filters['program_name'] }}</strong> &nbsp; @endif
        <div>Total Rows: <strong>{{ $rows->count() }}</strong></div>
    </div>

    <table>
        <thead>
            <tr>
                @foreach($columns as $c)
                    <th>{{ $labels[$c] ?? $c }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($columns as $c)
                        <td>{{ $row->{$c} }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
