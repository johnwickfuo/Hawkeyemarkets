@extends('templates.bento.mail.layout')

@section('content')
    @php
        $isRtl = config('languages.' . app()->getLocale() . '.rtl', false);
    @endphp
    <h1
        style="box-sizing: border-box; font-family: 'Space Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #ffffff; font-size: 28px; font-weight: bold; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; letter-spacing: -0.02em;">
        {{ $subject }}
    </h1>

    <p
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.6em; margin-top: 16px; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #cbd5e1;">
        {{ __('Hello :name,', ['name' => $kyc_record->user->first_name]) }}
    </p>

    <p
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.6em; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #cbd5e1; margin-bottom: 24px;">
        @if ($kyc_record->status == 'approved')
            {{ __('Congratulations! Your identity has been verified successfully. You now have full access to all account features.') }}
        @elseif($kyc_record->status == 'rejected')
            {{ __('Unfortunately, your identity verification request has been rejected.') }}
        @else
            {{ __('Your KYC documents have been received and are currently being reviewed by our compliance team.') }}
        @endif
    </p>

    {{-- KYC Details Box --}}
    <div
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 24px; margin-bottom: 32px;">

        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Document Type') }}</td>
                <td
                    style="padding-bottom: 12px; color: #f8fafc; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }}; text-transform: capitalize;">
                    {{ str_replace('_', ' ', $kyc_record->document_type) }}</td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Submission Date') }}</td>
                <td
                    style="padding-bottom: 12px; color: #f8fafc; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ $kyc_record->created_at->format('M d, Y H:i A') }}</td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Status') }}</td>
                <td
                    style="padding-bottom: 12px; color: #f8fafc; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }}; text-transform: capitalize;">
                    <span
                        style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; 
                        background-color: {{ $kyc_record->status === 'approved' ? 'rgba(16, 185, 129, 0.2)' : ($kyc_record->status === 'pending' ? 'rgba(245, 158, 11, 0.2)' : 'rgba(239, 68, 68, 0.2)') }};
                        color: {{ $kyc_record->status === 'approved' ? '#10b981' : ($kyc_record->status === 'pending' ? '#f59e0b' : '#ef4444') }};">
                        {{ __(ucfirst($kyc_record->status)) }}
                    </span>
                </td>
            </tr>

            @if ($kyc_record->status == 'rejected' && $kyc_record->rejection_reason)
                <tr>
                    <td
                        style="padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.1); color: #ef4444; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                        {{ __('Reason') }}</td>
                    <td
                        style="padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.1); color: #fca5a5; font-size: 14px; font-weight: 500; text-align: {{ $isRtl ? 'left' : 'right' }};">
                        {{ $kyc_record->rejection_reason }}</td>
                </tr>
            @endif
        </table>

    </div>

    <div
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; text-align: {{ $isRtl ? 'right' : 'left' }}; margin: 32px 0;">
        <a href="{{ route('user.kyc') }}"
            style="box-sizing: border-box; font-family: 'Space Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; display: inline-block; padding: 14px 32px; background-color: #8b5cf6; color: #ffffff; text-decoration: none; border-radius: 12px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.3);">
            {{ $kyc_record->status == 'rejected' ? __('Resubmit Documents') : __('View Status') }}
        </a>
    </div>

    <div
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; margin-top: 40px; padding-top: 32px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
        <p
            style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 14px; line-height: 1.5em; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #94a3b8;">
            {{ __('Regards,') }}<br>
            <span style="color: #f8fafc; font-weight: 600;">{{ getSetting('name') }}</span>
        </p>
    </div>
@endsection
