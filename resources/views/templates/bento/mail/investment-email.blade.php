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
        {{ __('Hello :name,', ['name' => $investment->user->first_name]) }}
    </p>

    <p
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.6em; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #cbd5e1; margin-bottom: 24px;">
        {{ __($custom_message) }}
    </p>

    {{-- Investment Details Box --}}
    <div
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 24px; margin-bottom: 32px;">

        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Plan Name') }}</td>
                <td
                    style="padding-bottom: 12px; color: #f8fafc; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ __($investment->plan->name) }}</td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Status') }}</td>
                <td
                    style="padding-bottom: 12px; color: #f8fafc; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }}; text-transform: capitalize;">
                    <span
                        style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; 
                        background-color: {{ $investment->status == 'active' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)' }};
                        color: {{ $investment->status == 'active' ? '#10b981' : '#ef4444' }};">
                        {{ __($investment->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Invested Amount') }}</td>
                <td
                    style="padding-bottom: 12px; color: #f8fafc; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ getSetting('currency_symbol', '$') }}{{ number_format($investment->capital_invested, getSetting('decimal_places', 2)) }}
                </td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('ROI Rate') }}</td>
                <td
                    style="padding-bottom: 12px; color: #f8fafc; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ $investment->plan->return_percent }}% </td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Duration') }}</td>
                <td
                    style="padding-bottom: 12px; color: #f8fafc; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ $investment->plan->duration }} {{ __($investment->plan->duration_type) }}</td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('ROI Payout Interval') }}</td>
                <td
                    style="padding-bottom: 12px; color: #f8fafc; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ __($investment->plan->return_interval) }}</td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Next Payout') }}</td>
                <td
                    style="padding-bottom: 12px; color: #f8fafc; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ \Carbon\Carbon::createFromTimestamp($investment->next_roi_at)->format('M d, Y H:i A') }}</td>
            </tr>
            <tr>
                <td
                    style="padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.1); color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Activation Date') }}</td>
                <td
                    style="padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.1); color: #f8fafc; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ $investment->created_at->format('M d, Y H:i A') }}</td>
            </tr>
        </table>

    </div>

    <div
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; text-align: {{ $isRtl ? 'right' : 'left' }}; margin: 32px 0;">
        <a href="{{ route('user.investments.index') }}"
            style="box-sizing: border-box; font-family: 'Space Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; display: inline-block; padding: 14px 32px; background-color: #8b5cf6; color: #ffffff; text-decoration: none; border-radius: 12px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.3);">
            {{ __('View Investment') }}
        </a>
    </div>

    <p
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 14px; line-height: 1.5em; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #94a3b8;">
        {{ __('You can monitor your investment growth and manage auto-reinvestment settings from your dashboard.') }}
    </p>

    <div
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; margin-top: 40px; padding-top: 32px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
        <p
            style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 14px; line-height: 1.5em; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #94a3b8;">
            {{ __('Regards,') }}<br>
            <span style="color: #f8fafc; font-weight: 600;">{{ getSetting('name') }}</span>
        </p>
    </div>
@endsection
