@extends('templates.bento.blades.admin.layouts.auth')

@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">{{ __('Reset Password') }}</h2>
            <p class="text-slate-400 text-sm mt-1">
                {{ __('Enter your email address and we will send you an OTP to reset your password.') }}</p>
        </div>

        <form action="{{ route('admin.forgot-password.send') }}" method="POST" class="ajax-form space-y-5"
            data-action="redirect">
            @csrf

            {{-- Email --}}
            <div class="space-y-2">
                <label for="email" class="block text-xs font-mono font-medium text-slate-400 uppercase tracking-wider">
                    {{ __('Email Address') }}
                </label>
                <input type="email" name="email" id="email"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-sans text-base"
                    placeholder="{{ __('Enter your admin email') }}" required autofocus>
            </div>

            {{-- Google Captcha --}}
            @if (getSetting('google_recaptcha') == 'enabled')
                <div class="pt-2">
                    {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
                    @error('g-recaptcha-response')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-500/20 transition-all duration-300 transform hover:-translate-y-0.5 relative overflow-hidden group cursor-pointer">
                <span class="relative z-10">{{ __('Send Reset Code') }}</span>
                <div class="absolute inset-0 bg-white/10 group-hover:bg-transparent transition-colors"></div>
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('admin.login') }}" class="text-sm text-slate-400 hover:text-white transition-colors">
                {{ __('Back to Login') }}
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- if google Captcha is enabled --}}
    @if (getSetting('google_recaptcha') == 'enabled')
        {!! NoCaptcha::renderJs() !!}
    @endif
@endsection
