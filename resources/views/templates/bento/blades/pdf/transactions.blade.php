<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Transaction History') }}</title>
    <style>
        @page {
            margin: 40px;
            background-color: #ffffff;
        }

        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            color: #0f172a;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
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
            padding: 12px;
            background-color: #f8fafc;
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .amount-credit {
            color: #059669;
            font-weight: 700;
        }

        .amount-debit {
            color: #dc2626;
            font-weight: 700;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-completed {
            color: #059669;
            background: #ecfdf5;
        }

        .status-pending {
            color: #d97706;
            background: #fffbeb;
        }

        .status-failed {
            color: #dc2626;
            background: #fef2f2;
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
            {{-- Ideally use absolute path for PDF generation or base64 --}}
            <img src="{{ public_path('assets/images/' . getSetting('logo_rectangle')) }}"
                alt="{{ getSetting('site_name') }}" class="logo-img">
        </div>
        <div class="report-info">
            <span class="report-label">{{ __('Transaction History') }}</span>
            <div class="report-meta">
                {{ __('Generated on') }}: {{ now()->format('M d, Y H:i A') }}<br>
                {{ __('Account') }}: {{ $user->first_name }} {{ $user->last_name }}
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Reference') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Type') }}</th>
                <th class="text-right">{{ __('Amount') }}</th>
                <th class="text-right">{{ __('Balance') }}</th>
                <th class="text-center">{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
                <tr>
                    <td>
                        {{ $tx->created_at->format('M d, Y') }}<br>
                        <span style="color: #94a3b8; font-size: 10px;">{{ $tx->created_at->format('H:i') }}</span>
                    </td>
                    <td style="font-family: monospace; color: #64748b;">{{ $tx->reference }}</td>
                    <td>{{ $tx->description }}</td>
                    <td>
                        <span
                            style="font-weight: 600; font-size: 11px; {{ $tx->type == 'credit' ? 'color:#059669' : 'color:#dc2626' }}">
                            {{ ucfirst($tx->type) }}
                        </span>
                    </td>
                    <td class="text-right">
                        <span class="{{ $tx->type == 'credit' ? 'amount-credit' : 'amount-debit' }}">
                            {{ $tx->type == 'credit' ? '+' : '-' }}{{ number_format($tx->amount, getSetting('decimal_places', 2)) }}
                        </span>
                        <br>
                        <span style="color: #94a3b8; font-size: 10px;">{{ $tx->currency }}</span>
                    </td>
                    <td class="text-right" style="font-family: monospace;">
                        {{ number_format($tx->new_balance, getSetting('decimal_places', 2)) }}
                    </td>
                    <td class="text-center">
                        <span class="status-badge status-{{ $tx->status }}">{{ ucfirst($tx->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 30px; color: #94a3b8;">
                        {{ __('No transactions found for the selected period.') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} {{ getSetting('site_name') }}. {{ __('All rights reserved.') }}<br>
        {{ url('/') }}
    </div>
</body>

</html>
