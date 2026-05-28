@extends('templates.bento.mail.layout')

@section('content')
    @php
        $isRtl = config('languages.' . app()->getLocale() . '.rtl', false);
    @endphp
    <h1
        style="box-sizing: border-box; font-family: 'Space Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #ffffff; font-size: 28px; font-weight: bold; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; letter-spacing: -0.02em;">
        {{ __($custom_subject) }}
    </h1>

    <p
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.6em; margin-top: 16px; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #cbd5e1;">
        {{ __('Hello :name,', ['name' => $deposit->user->first_name]) }}
    </p>

    <p
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.6em; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #cbd5e1; margin-bottom: 24px;">
        {{ __($custom_message) }}
    </p>

    {{-- Deposit Details Box --}}
    <div
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 24px; margin-bottom: 32px;">
        <h3
            style="margin-top: 0; margin-bottom: 16px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; font-weight: 600; text-align: {{ $isRtl ? 'right' : 'left' }};">
            {{ __('Detailed Deposit Information') }}
        </h3>

        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Amount') }}</td>
                <td
                    style="padding-bottom: 12px; color: #ffffff; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ number_format($deposit->amount, getSetting('decimal_places')) }} {{ getSetting('currency') }}
                </td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Total Payable') }}</td>
                <td
                    style="padding-bottom: 12px; color: #ffffff; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ $deposit->converted_amount }} {{ $deposit->currency }}
                </td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Transaction ID') }}</td>
                <td
                    style="padding-bottom: 12px; color: #ffffff; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }}; font-family: monospace;">
                    {{ $deposit->transaction_reference }}
                </td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Payment Method') }}</td>
                <td
                    style="padding-bottom: 12px; color: #ffffff; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ $deposit->paymentMethod->name }}
                </td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 0px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Status') }}</td>
                <td style="padding-bottom: 0px; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    <span
                        style="background-color: rgba(234, 179, 8, 0.2); color: #facc15; font-size: 12px; padding: 4px 8px; border-radius: 4px; font-weight: 600; text-transform: uppercase;">
                        {{ __($deposit->status) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    {{-- Action Button --}}
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <a href="{{ route('user.deposits.index') }}"
                    style="box-sizing: border-box; font-family: 'Space Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; border-radius: 12px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #6366f1; border-bottom: 8px solid #6366f1; border-left: 18px solid #6366f1; border-right: 18px solid #6366f1; border-top: 8px solid #6366f1; font-weight: bold; width: 100%;">
                    {{ __('View Deposit History') }}
                </a>
            </td>
        </tr>
    </table>

    <p
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 14px; line-height: 1.6em; margin-top: 32px; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #94a3b8;">
        {{ __('If you did not initiate this request, we strongly recommend securing your account immediately and contacting our support team.') }}
    </p>

    <p
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 14px; line-height: 1.6em; margin-top: 16px; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #94a3b8;">
        {{ __('Need help? Our support team is available 24/7.') }}
    </p>

    <p
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.6em; margin-top: 32px; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #cbd5e1;">
        {{ __('Regards,') }}<br>
        {{ getSetting('name') }}
    </p>
@endsection
