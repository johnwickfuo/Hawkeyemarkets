<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title }}</title>
    <style>
        @page {
            margin: 30px;
            background-color: #ffffff;
            size: {{ $orientation ?? 'landscape' }};
        }

        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            color: #0f172a;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            font-size: 10px;
        }

        .header {
            margin-bottom: 40px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 20px;
        }

        .logo-img {
            height: 30px;
            max-width: 180px;
        }

        .report-info {
            float: right;
            text-align: right;
        }

        .report-label {
            display: block;
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
        }

        .report-meta {
            color: #64748b;
            font-size: 12px;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            text-align: left;
            padding: 8px 6px;
            background-color: #f8fafc;
            color: #64748b;
            font-size: 8px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 8px 6px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            color: #059669;
            background: #ecfdf5;
        }

        .status-completed {
            color: #2563eb;
            background: #eff6ff;
        }

        .status-suspended {
            color: #d97706;
            background: #fffbeb;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            color: #94a3b8;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div style="float: left;">
            <img src="{{ public_path('assets/images/' . getSetting('logo_rectangle')) }}"
                alt="{{ getSetting('site_name') }}" class="logo-img">
        </div>
        <div class="report-info">
            <span class="report-label">{{ $page_title }}</span>
            <div class="report-meta">
                {{ __('Generated on') }}: {{ now()->format('M d, Y H:i A') }}<br>
                {{ __('Total Earnings Records') }}: {{ $earnings->count() }}
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($columns as $key => $label)
                    <th
                        class="{{ in_array($key, ['amount']) ? 'text-right' : (in_array($key, ['investment_goal', 'risk_profile']) ? 'text-center' : '') }}">
                        {{ __($label) }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($earnings as $earning)
                <tr>
                    @foreach ($columns as $key => $label)
                        <td
                            class="{{ in_array($key, ['amount']) ? 'text-right' : (in_array($key, ['investment_goal', 'risk_profile']) ? 'text-center' : '') }}">
                            @if ($key == 'username')
                                <span
                                    style="font-weight: 600;">{{ $earning->user ? $earning->user->username : 'Deleted User' }}</span>
                            @elseif($key == 'plan_name')
                                {{ $earning->investment && $earning->investment->plan ? $earning->investment->plan->name : 'N/A' }}
                            @elseif($key == 'investment_goal')
                                {{ $earning->investment_goal ? ucfirst(str_replace('_', ' ', $earning->investment_goal)) : 'N/A' }}
                            @elseif($key == 'risk_profile')
                                {{ $earning->risk_profile ? ucfirst($earning->risk_profile) : 'N/A' }}
                            @elseif($key == 'amount')
                                <span style="font-family: monospace;">{{ showAmount($earning->amount) }}</span>
                            @elseif($key == 'created_at')
                                {{ $earning->created_at->format('M d, Y H:i:s') }}
                            @elseif($key == 'note')
                                {{ $earning->note ?: '-' }}
                            @else
                                {{ $earning->$key }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" class="text-center" style="padding: 30px; color: #94a3b8;">
                        {{ __('No earning records found matching your criteria.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} {{ getSetting('site_name') }}. {{ __('Admin Panel') }}<br>
        {{ url('/') }}
    </div>
</body>

</html>
