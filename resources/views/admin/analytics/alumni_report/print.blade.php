<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Alumni Report') }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
        .header { margin-bottom: 12px; }
        .title { font-size: 16px; font-weight: 700; margin: 0 0 4px 0; }
        .meta { font-size: 12px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #222; padding: 6px 6px; vertical-align: top; }
        th { font-weight: 700; text-align: left; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ __('KSU Alumni Report') }}</div>
        <div class="meta">
            <div>{{ __('Generated') }}: {{ now()->format('Y-m-d H:i') }}</div>
            <div>
                {{ __('Filters') }}:
                {{ __('Year') }}: {{ $filters['graduation_year'] ?: __('All') }},
                {{ __('College') }}: {{ $filters['college_name'] ?: __('All') }},
                {{ __('Program') }}: {{ $filters['program_name'] ?: __('All') }}
            </div>
        </div>
        <div class="no-print" style="margin-top:10px;">
            <button onclick="window.print()">{{ __('Print') }}</button>
        </div>
    </div>

    <table>
        <thead>
        <tr>
            @foreach($columns as $col)
                <th>{{ __($columnLabels[$col] ?? $col) }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @forelse($rows as $r)
            <tr>
                @foreach($columns as $col)
                    <td>
                        @php $v = data_get($r, $col); @endphp
                        {{ is_array($v) ? json_encode($v) : $v }}
                    </td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) }}">{{ __('No records found for selected filters.') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
