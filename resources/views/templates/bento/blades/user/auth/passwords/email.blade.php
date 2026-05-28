@extends('templates.bento.blades.layouts.auth')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h3 class="text-2xl font-display font-bold text-white tracking-tight">
                {{ __('Reset Password') }}
            </h3>
            <p class="text-text-secondary text-sm mt-1 mb-6">
                {{ __('Enter your email address and we will send you an OTP to reset your password.') }}
            </p>
        </div>

        {{-- Form --}}
        <form action="{{ route('user.forgot-password.send') }}" method="POST" class="ajax-form space-y-6"
            data-action="redirect">
            @csrf

            {{-- Email --}}
            <div class="space-y-2">
                <label for="email"
                    class="block text-xs font-mono font-medium text-text-secondary uppercase tracking-wider">
                    {{ __('Email Address') }}
                </label>
                <input type="email" name="email" id="email"
                    class="w-full bg-primary-dark/50 border border-white/10 rounded-2xl px-4 py-3.5 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all font-sans text-base"
                    placeholder="{{ __('Enter your email address') }}" required autofocus>
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Google Captcha --}}
            @if (getSetting('google_recaptcha') == 'enabled')
                <div class="pt-2">
                    {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
                    @error('g-recaptcha-response')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full bg-gradient-to-r from-accent-primary to-accent-glow hover:from-accent-glow hover:to-accent-primary text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-accent-primary/20 transition-all duration-300 transform hover:-translate-y-0.5 relative overflow-hidden group cursor-pointer">
                <span class="relative z-10">{{ __('Send Reset Code') }}</span>
                <div class="absolute inset-0 h-full w-full bg-white/20 group-hover:bg-transparent transition-colors"></div>
            </button>
        </form>

        {{-- Back to Login --}}
        <div class="text-center">
            <a href="{{ route('user.login') }}"
                class="text-xs font-medium text-text-secondary hover:text-white transition-colors">
                {{ __('Back to Login') }}
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    @if (getSetting('google_recaptcha') == 'enabled')
        {!! NoCaptcha::renderJs() !!}
    @endif
@endsection
