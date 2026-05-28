@extends('templates.bento.mail.layout')

@section('content')
    @php
        $isRtl = config('languages.' . app()->getLocale() . '.rtl', false);
    @endphp

    <p
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.6em; margin-top: 16px; text-align: {{ $isRtl ? 'right' : 'left' }}; color: #cbd5e1;">
        {{ __('Hi :name,', ['name' => $user->first_name]) }}
    </p>



    {{-- Referral Details Box --}}
    <div
        style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 24px; margin-bottom: 32px;">
        {!! $content !!}
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
