@php
    $isRtl = config('languages.' . app()->getLocale() . '.rtl', false);
    $fontFamily = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
    $headingFamily =
        "'Space Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
    $isBan = ($action ?? 'ban') === 'ban';
@endphp

@extends('templates.bento.mail.layout')

@section('content')
    {{-- Icon Header --}}
    <div style="text-align: center; margin-bottom: 32px;">
        @if ($isBan)
            <div
                style="display: inline-flex; align-items: center; justify-content: center; width: 72px; height: 72px; border-radius: 50%; background-color: rgba(239, 68, 68, 0.15); border: 2px solid rgba(239, 68, 68, 0.3);">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                </svg>
            </div>
        @else
            <div
                style="display: inline-flex; align-items: center; justify-content: center; width: 72px; height: 72px; border-radius: 50%; background-color: rgba(16, 185, 129, 0.15); border: 2px solid rgba(16, 185, 129, 0.3);">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
        @endif
    </div>

    {{-- Heading --}}
    <h1
        style="box-sizing: border-box; font-family: {{ $headingFamily }}; color: #ffffff; font-size: 26px; font-weight: 700; margin-top: 0; margin-bottom: 8px; text-align: {{ $isRtl ? 'right' : 'left' }}; letter-spacing: -0.02em;">
        @if ($isBan)
            {{ __('Your Account Has Been Suspended') }}
        @else
            {{ __('Your Account Has Been Reactivated') }}
        @endif
    </h1>

    {{-- Greeting --}}
    <p
        style="box-sizing: border-box; font-family: {{ $fontFamily }}; font-size: 16px; line-height: 1.6em; margin-top: 0; margin-bottom: 20px; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #cbd5e1;">
        {{ __('Hello :name,', ['name' => $user->first_name]) }}
    </p>

    {{-- Intro paragraph --}}
    <p
        style="box-sizing: border-box; font-family: {{ $fontFamily }}; font-size: 16px; line-height: 1.6em; margin-top: 0; margin-bottom: 28px; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #cbd5e1;">
        @if ($isBan)
            {{ __('We are writing to inform you that your account on :site has been suspended by an administrator. Access to your account and all related services has been restricted.', ['site' => getSetting('name')]) }}
        @else
            {{ __('We are pleased to inform you that your account on :site has been reactivated by an administrator. You now have full access to all services.', ['site' => getSetting('name')]) }}
        @endif
    </p>

    {{-- Account Details Card --}}
    <div
        style="background-color: {{ $isBan ? 'rgba(239, 68, 68, 0.05)' : 'rgba(16, 185, 129, 0.05)' }}; border: 1px solid {{ $isBan ? 'rgba(239, 68, 68, 0.2)' : 'rgba(16, 185, 129, 0.2)' }}; border-radius: 12px; padding: 24px; margin-bottom: 28px;">
        <p
            style="font-family: {{ $headingFamily }}; font-size: 13px; font-weight: 600; color: {{ $isBan ? '#ef4444' : '#10b981' }}; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 0; margin-bottom: 16px; text-align: {{ $isRtl ? 'right' : 'left' }};">
            {{ __('Account Details') }}
        </p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-family: {{ $fontFamily }}; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Full Name') }}</td>
                <td
                    style="padding-bottom: 12px; color: #f8fafc; font-family: {{ $fontFamily }}; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ $user->first_name }} {{ $user->last_name }}</td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 12px; color: #94a3b8; font-family: {{ $fontFamily }}; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Username') }}</td>
                <td
                    style="padding-bottom: 12px; color: #f8fafc; font-family: {{ $fontFamily }}; font-size: 14px; font-weight: 600; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    {{ $user->username }}</td>
            </tr>
            <tr>
                <td
                    style="padding-bottom: 0; color: #94a3b8; font-family: {{ $fontFamily }}; font-size: 14px; text-align: {{ $isRtl ? 'right' : 'left' }};">
                    {{ __('Status') }}</td>
                <td style="padding-bottom: 0; text-align: {{ $isRtl ? 'left' : 'right' }};">
                    <span
                        style="display: inline-block; padding: 4px 12px; border-radius: 20px; font-family: {{ $fontFamily }}; font-size: 12px; font-weight: 600; letter-spacing: 0.02em;
                        background-color: {{ $isBan ? 'rgba(239, 68, 68, 0.2)' : 'rgba(16, 185, 129, 0.2)' }};
                        color: {{ $isBan ? '#ef4444' : '#10b981' }};">
                        {{ $isBan ? __('Suspended') : __('Active') }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    {{-- What This Means --}}
    <div
        style="background-color: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 24px; margin-bottom: 28px;">
        <p
            style="font-family: {{ $headingFamily }}; font-size: 13px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-top: 0; margin-bottom: 16px; text-align: {{ $isRtl ? 'right' : 'left' }};">
            {{ __('What This Means') }}
        </p>
        @if ($isBan)
            <ul
                style="margin: 0; padding-{{ $isRtl ? 'right' : 'left' }}: 20px; color: #cbd5e1; font-family: {{ $fontFamily }}; font-size: 14px; line-height: 1.8em;">
                <li>{{ __('You cannot log in to your account.') }}</li>
                <li>{{ __('All active sessions have been terminated.') }}</li>
                <li>{{ __('Scheduled or pending transactions may be paused.') }}</li>
            </ul>
        @else
            <ul
                style="margin: 0; padding-{{ $isRtl ? 'right' : 'left' }}: 20px; color: #cbd5e1; font-family: {{ $fontFamily }}; font-size: 14px; line-height: 1.8em;">
                <li>{{ __('You can now log in to your account normally.') }}</li>
                <li>{{ __('All account features have been fully restored.') }}</li>
                <li>{{ __('Any previously paused transactions may resume.') }}</li>
            </ul>
        @endif
    </div>

    {{-- Closing paragraph --}}
    <p
        style="font-family: {{ $fontFamily }}; font-size: 15px; line-height: 1.6em; margin-top: 0; margin-bottom: 24px; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #cbd5e1;">
        @if ($isBan)
            {{ __('If you believe this is a mistake or would like to appeal this decision, please contact our support team immediately.') }}
        @else
            {{ __('If you have any questions or need assistance, our support team is always here to help.') }}
        @endif
    </p>

    {{-- CTA Button --}}
    <div style="text-align: {{ $isRtl ? 'right' : 'left' }}; margin-bottom: 40px;">
        @if ($isBan)
            <a href="mailto:{{ getSetting('email') ?? config('mail.from.address') }}"
                style="display: inline-block; padding: 14px 32px; background-color: #8b5cf6; color: #ffffff; text-decoration: none; border-radius: 12px; font-family: {{ $headingFamily }}; font-weight: 700; font-size: 15px; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);">
                {{ __('Contact Support') }}
            </a>
        @else
            <a href="{{ route('user.dashboard') }}"
                style="display: inline-block; padding: 14px 32px; background-color: #8b5cf6; color: #ffffff; text-decoration: none; border-radius: 12px; font-family: {{ $headingFamily }}; font-weight: 700; font-size: 15px; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);">
                {{ __('Go to Dashboard') }}
            </a>
        @endif
    </div>

    {{-- Footer --}}
    <div style="margin-top: 40px; padding-top: 32px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
        <p
            style="font-family: {{ $fontFamily }}; font-size: 14px; line-height: 1.5em; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #94a3b8;">
            {{ __('Regards,') }}<br>
            <span style="color: #f8fafc; font-weight: 600;">{{ getSetting('name') }}</span>
        </p>
    </div>
@endsection