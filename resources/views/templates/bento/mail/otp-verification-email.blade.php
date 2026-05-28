@extends('templates.bento.mail.layout')

@section('content')
    @php
        $isRtl = config('languages.' . app()->getLocale() . '.rtl', false);
    @endphp
    <h1
        style="box-sizing: border-box; font-family: 'Space Grotesk', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #ffffff; font-size: 28px; font-weight: bold; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; margin-bottom: 24px;">
        {{ $subject }}
    </h1>

    <p
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.6em; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #94a3b8; margin-bottom: 32px;">
        {!! $body_message !!}
    </p>

    <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation"
        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 30px auto; padding: 0; text-align: center; width: 100%;">
        <tr>
            <td align="center"
                style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                    <tr>
                        <td align="center"
                            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                            <div
                                style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 32px; display: inline-block; min-width: 200px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                <span
                                    style="font-family: 'JetBrains Mono', 'Courier New', Courier, monospace; font-size: 40px; font-weight: bold; color: #8b5cf6; letter-spacing: 12px; display: block; text-align: center;">{{ $otp_code }}</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 40px; padding-top: 24px;">

        <p
            style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 14px; line-height: 1.5em; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #64748b; margin-bottom: 24px;">
            {{ __('This code will expire in 15 minutes.') }}
        </p>

        <p
            style="box-sizing: border-box; font-family: 'JetBrains Mono', 'Courier New', Courier, monospace; position: relative; font-size: 12px; line-height: 1.5em; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #94a3b8; border-{{ $isRtl ? 'right' : 'left' }}: 3px solid #8b5cf6; padding-{{ $isRtl ? 'right' : 'left' }}: 16px; background: rgba(139, 92, 246, 0.05); padding: 16px; border-radius: {{ $isRtl ? '8px 0 0 8px' : '0 8px 8px 0' }}; margin-bottom: 8px;">
            {{ __('IP Address: :ip', ['ip' => $ip]) }}
        </p>

        <p
            style="box-sizing: border-box; font-family: 'JetBrains Mono', 'Courier New', Courier, monospace; position: relative; font-size: 11px; line-height: 1.5em; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #64748b; border-{{ $isRtl ? 'right' : 'left' }}: 3px solid #475569; padding-{{ $isRtl ? 'right' : 'left' }}: 16px; background: rgba(71, 85, 105, 0.05); padding: 16px; border-radius: {{ $isRtl ? '8px 0 0 8px' : '0 8px 8px 0' }}; margin-bottom: 8px; word-break: break-all;">
            {{ __('Browser: :browser', ['browser' => $user_agent ?? __('Unknown')]) }}
        </p>

        <p
            style="box-sizing: border-box; font-family: 'JetBrains Mono', 'Courier New', Courier, monospace; position: relative; font-size: 11px; line-height: 1.5em; margin-top: 0; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #64748b; border-{{ $isRtl ? 'right' : 'left' }}: 3px solid #475569; padding-{{ $isRtl ? 'right' : 'left' }}: 16px; background: rgba(71, 85, 105, 0.05); padding: 16px; border-radius: {{ $isRtl ? '8px 0 0 8px' : '0 8px 8px 0' }}; margin-bottom: 24px;">
            {{ __('Location: :location', ['location' => $location ?? __('Unknown Location')]) }}
        </p>

        <h3
            style="font-family: 'Space Grotesk', sans-serif; color: #ffffff; font-size: 18px; margin-bottom: 12px; text-align: {{ $isRtl ? 'right' : 'left' }};">
            {{ __("Didn't Request This?") }}</h3>
        <p
            style="color: #94a3b8; font-family: 'Inter', sans-serif; font-size: 14px; line-height: 1.5em; margin-top: 0; margin-bottom: 12px; text-align: {{ $isRtl ? 'right' : 'left' }};">
            {{ __('If you did not request this code, please take action immediately:') }}
        </p>

        <ul
            style="color: #94a3b8; font-family: 'Inter', sans-serif; font-size: 14px; line-height: 1.6em; margin-top: 0; padding-{{ $isRtl ? 'right' : 'left' }}: 20px; list-style-type: disc; text-align: {{ $isRtl ? 'right' : 'left' }};">
            <li style="margin-bottom: 4px;">{{ __('Do not share this code with anyone') }}</li>
            <li style="margin-bottom: 4px;">{{ __('Change your password') }}</li>
            <li>{{ __('Contact our support team') }}</li>
        </ul>

        <p
            style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 14px; line-height: 1.5em; margin-top: 24px; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #64748b;">
            {{ __('If you have any questions, our support team is always here to help.') }}
        </p>
    </div>
@endsection
