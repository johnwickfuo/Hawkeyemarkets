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
            size: {{ $orientation ?? 'portrait' }};
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
            margin-bottom: 20px;
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

        .stats-box {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8fafc;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .stats-box .pass {
            color: #059669;
            margin-right: 20px;
        }

        .stats-box .fail {
            color: #dc2626;
        }

        h2 {
            font-size: 14px;
            color: #1e293b;
            margin-top: 30px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 20px;
            table-layout: fixed;
            /* Crucial for forcing line breaks in cells */
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
            word-wrap: break-word;
            /* Wrap long strings */
            word-break: break-all;
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
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-pass,
        .status-ok {
            color: #059669;
            background: #ecfdf5;
        }

        .status-fail,
        .status-high {
            color: #dc2626;
            background: #fef2f2;
        }

        .status-warn {
            color: #d97706;
            background: #fffbeb;
        }

        .code-block {
            font-family: 'Courier New', Courier, monospace;
            color: #334155;
            word-wrap: break-word;
            word-break: break-all;
            white-space: normal;
        }

        .warning-box {
            background-color: #fffbeb;
            border-left: 3px solid #f59e0b;
            padding: 10px 15px;
            margin-bottom: 10px;
            font-size: 11px;
            color: #92400e;
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
                {{ __('Scan Duration') }}: {{ number_format($elapsed, 3) }}s
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="stats-box">
        <span class="pass">{{ __('PASS') }}: {{ $okCount }}</span>
        @if ($badCount > 0)
            <span class="fail">{{ __('FAIL') }}: {{ $badCount }}</span>
        @endif
    </div>

    <h2>{{ __('System Checks') }}</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 10%" class="text-center">{{ __('Status') }}</th>
                <th style="width: 25%">{{ __('Check') }}</th>
                <th style="width: 65%">{{ __('Details') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($checks as $c)
                <tr>
                    <td class="text-center">
                        @if ($c['ok'])
                            <span class="status-badge status-pass">{{ __('PASS') }}</span>
                        @else
                            <span class="status-badge status-fail">{{ __('FAIL') }}</span>
                        @endif
                    </td>
                    <td><strong>{{ $c['label'] }}</strong></td>
                    <td class="code-block">{{ $c['details'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>{{ __('Warnings') }}</h2>
    @if (empty($warnings))
        <div
            style="padding: 10px; background-color: #ecfdf5; color: #059669; font-size: 11px; border-radius: 4px; border-left: 3px solid #10b981;">
            {{ __('No configuration warnings detected.') }}
        </div>
    @else
        @foreach ($warnings as $w)
            <div class="warning-box">{{ $w }}</div>
        @endforeach
    @endif

    <h2>{{ __('Security Findings (Scan)') }}</h2>
    @if (count($findings) === 1 && $findings[0]['severity'] === 'OK')
        <div
            style="padding: 10px; background-color: #ecfdf5; color: #059669; font-size: 11px; border-radius: 4px; border-left: 3px solid #10b981;">
            {{ __('No security pattern matches found in scanned directories.') }}
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 10%" class="text-center">{{ __('Severity') }}</th>
                    <th style="width: 25%">{{ __('Finding') }}</th>
                    <th style="width: 40%">{{ __('File') }}</th>
                    <th style="width: 25%">{{ __('Note') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($findings as $f)
                    @php
                        $sevCls = 'status-warn';
                        $sev = strtoupper($f['severity'] ?? '');
                        if ($sev === 'HIGH') {
                            $sevCls = 'status-fail';
                        }
                        if ($sev === 'OK') {
                            $sevCls = 'status-pass';
                        }
                    @endphp
                    <tr>
                        <td class="text-center">
                            <span class="status-badge {{ $sevCls }}">{{ $f['severity'] ?? 'INFO' }}</span>
                        </td>
                        <td><strong>{{ $f['name'] ?? '' }}</strong></td>
                        <td class="code-block">{{ $f['file'] ?? '' }}</td>
                        <td>{{ $f['note'] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        &copy; {{ date('Y') }} {{ getSetting('site_name') }}. {{ __('Admin Panel') }}<br>
        {{ url('/') }}
    </div>
</body>

</html>
