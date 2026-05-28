<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Receipt') }} - {{ $deposit->transaction_reference }}</title>
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
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            margin-bottom: 60px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 30px;
        }

        .logo-img {
            height: 30px;
            max-width: 180px;
        }

        .receipt-info {
            float: right;
            text-align: right;
        }

        .receipt-label {
            display: block;
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .reference-id {
            display: block;
            color: #64748b;
            font-size: 13px;
            margin-top: 5px;
            font-weight: 500;
        }

        /* Hero Summary */
        .summary-hero {
            margin-bottom: 50px;
        }

        .amount-display {
            margin-bottom: 10px;
        }

        .amount-main {
            font-size: 56px;
            font-weight: 800;
            letter-spacing: -2px;
            color: #0f172a;
            line-height: 1;
        }

        .currency-code {
            font-size: 20px;
            font-weight: 600;
            color: #8b5cf6;
            margin-left: 5px;
        }

        .payment-status {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 4px 12px;
            border-radius: 4px;
            margin-top: 15px;
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

        .status-partial_payment {
            color: #2563eb;
            background: #eff6ff;
        }

        /* Meta Grid */
        .meta-section {
            display: table;
            width: 100%;
            margin-bottom: 50px;
        }

        .meta-item {
            display: table-cell;
            width: 33.33%;
            padding-right: 20px;
        }

        .meta-label {
            display: block;
            color: #94a3b8;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .meta-value {
            display: block;
            color: #1e293b;
            font-size: 14px;
            font-weight: 600;
        }

        /* Detailed Breakdown */
        .breakdown-title {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .breakdown-row td {
            padding: 15px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .row-desc {
            color: #64748b;
            font-weight: 500;
        }

        .row-amt {
            text-align: right;
            color: #0f172a;
            font-weight: 700;
        }

        .total-row td {
            padding: 25px 0;
            border-bottom: none;
        }

        .total-label {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .total-amt-final {
            text-align: right;
            font-size: 24px;
            font-weight: 800;
            color: #8b5cf6;
        }

        /* Conversion Note */
        .conversion-box {
            background-color: #f8fafc;
            border-left: 4px solid #8b5cf6;
            padding: 20px;
            margin-bottom: 40px;
        }

        .conv-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .conv-value {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        /* Footer */
        .footer {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid #f1f5f9;
            color: #94a3b8;
            font-size: 11px;
            text-align: left;
        }

        .footer-legal {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .footer-stamp {
            font-weight: 600;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header clearfix">
            <div style="float: left;">
                <img src="{{ public_path('assets/images/' . getSetting('logo_rectangle')) }}"
                    alt="{{ getSetting('site_name') }}" class="logo-img">
            </div>
            <div class="receipt-info">
                <span class="receipt-label">{{ __('Payment Receipt') }}</span>
                <span class="reference-id">ID: {{ $deposit->transaction_reference }}</span>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="summary-hero">
            <div class="amount-display">
                <span
                    class="amount-main">{{ number_format($deposit->total_amount, getSetting('decimal_places', 2)) }}</span>
                <span class="currency-code">{{ getSetting('currency') }}</span>
            </div>
            <span class="payment-status status-{{ $deposit->status }}">
                {{ __($deposit->status) }}
            </span>
        </div>

        <!-- Meta Info -->
        <div class="meta-section">
            <div class="meta-item">
                <span class="meta-label">{{ __('Date of Issue') }}</span>
                <span class="meta-value">{{ $deposit->created_at->format('M d, Y') }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">{{ __('Account Holder') }}</span>
                <span class="meta-value">{{ $deposit->user->first_name }} {{ $deposit->user->last_name }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">{{ __('Payment Method') }}</span>
                <span class="meta-value">{{ __($deposit->paymentMethod->name) }}</span>
            </div>
        </div>

        <!-- Breakdown -->
        <h3 class="breakdown-title">{{ __('Transaction Details') }}</h3>
        <table class="breakdown-table">
            <tr class="breakdown-row">
                <td class="row-desc">{{ __('Base Deposit Amount') }}</td>
                <td class="row-amt">{{ number_format($deposit->amount, getSetting('decimal_places', 2)) }}
                    {{ getSetting('currency') }}</td>
            </tr>
            <tr class="breakdown-row">
                <td class="row-desc">{{ __('Processing & Service Fee') }}</td>
                <td class="row-amt">+ {{ number_format($deposit->fee_amount, getSetting('decimal_places', 2)) }}
                    {{ getSetting('currency') }}</td>
            </tr>
            <tr class="total-row">
                <td class="total-label">{{ __('Total Amount Credited') }}</td>
                <td class="total-amt-final">
                    {{ number_format($deposit->total_amount, getSetting('decimal_places', 2)) }}
                    {{ getSetting('currency') }}</td>
            </tr>
        </table>

        @if ($deposit->currency != getSetting('currency'))
            <div class="conversion-box">
                <span class="conv-label">{{ __('Cross-Currency Settlement') }}</span>
                <div class="conv-value">
                    {{ $deposit->converted_amount }} {{ $deposit->currency }}
                </div>
                <span class="conv-label" style="margin-top: 10px; font-weight: 500;">
                    {{ __('Calculated at rate') }}: 1 {{ getSetting('currency') }} = {{ $deposit->exchange_rate }}
                    {{ $deposit->currency }}
                </span>
            </div>
        @endif

        @if (!empty($deposit->transaction_hash))
            <div style="margin-top: -20px; font-size: 11px;">
                <span class="meta-label">{{ __('Network Transaction Hash') }}</span>
                <code style="color: #64748b; font-family: monospace;">{{ $deposit->transaction_hash }}</code>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-legal">
                {{ __('This receipt is an official confirmation of the transaction. Please keep it for your records. For any inquiries regarding this payment, please quote the Reference ID shown above.') }}
            </div>
            <div class="footer-stamp">
                {{ __('Generated on') }} {{ now()->format('Y-m-d H:i:s') }} • {{ getSetting('name') }}
            </div>
        </div>
    </div>
</body>

</html>
