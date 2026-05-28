@extends('templates.bento.blades.layouts.auth')

@section('content')
    <div class="space-y-6">
        @if (!session('register_info'))
            {{-- Header --}}
            <div>
                <h3 class="text-2xl font-display font-bold text-white tracking-tight">
                    {{ __('Create Account') }}
                </h3>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-6 h-[1px] bg-accent-primary"></span>
                    <p class="text-accent-primary font-mono text-xs uppercase tracking-wide">
                        {{ __('Join the future of wealth management') }}
                    </p>
                </div>
            </div>

            {{-- Registration Options Selection --}}
            <div id="auth-selection" class="space-y-3" @if ($only_email) style="display: none;" @endif>
                @foreach ($login_methods as $method => $data)
                    @if ($method === 'email')
                        <button type="button" onclick="toggleEmailForm()"
                            class="flex items-center justify-center gap-3 w-full bg-slate-800/50 text-white border border-white/10 font-semibold py-3 px-4 rounded-xl shadow-sm transition-all duration-300 transform hover:-translate-y-0.5 hover:bg-slate-800/80 cursor-pointer">
                            {!! $data['icon'] !!}
                            <span>{{ __('Continue with Email') }}</span>
                        </button>
                    @else
                        <a href="{{ route('user.login.social', ['provider' => $method]) }}"
                            class="flex items-center justify-center gap-3 w-full {{ $method === 'google' ? 'bg-white text-slate-900 border-slate-200 hover:bg-slate-50' : 'bg-slate-800/50 text-white border-white/10 hover:bg-slate-800/80' }} font-semibold py-3 px-4 rounded-xl shadow-sm transition-all duration-300 transform hover:-translate-y-0.5 border cursor-pointer">
                            {!! $data['icon'] !!}
                            <span>{{ __('Continue with ' . $data['name']) }}</span>
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Email Registration Form Container --}}
            <div id="email-register-container" @if (!$only_email) style="display: none;" @endif
                class="space-y-6">
                @if (!$only_email)
                    <button type="button" onclick="toggleSelection()"
                        class="text-accent-primary hover:text-accent-glow text-sm flex items-center gap-2 transition-colors cursor-pointer group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        {{ __('Back to options') }}
                    </button>
                @endif

                @php
                    $email_verification = getSetting('email_verification');
                @endphp
                <form action="{{ route('user.register-validate') }}" method="POST"
                    data-action="{{ $email_verification == 'disabled' ? 'redirect' : 'reload' }}"
                    @if ($email_verification == 'disabled') data-redirect="{{ route('user.dashboard') }}" @endif
                    class="ajax-form space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- First Name --}}
                        <div class="space-y-1.5">
                            <label for="first_name"
                                class="block text-xs font-mono font-medium text-text-secondary uppercase tracking-wider">{{ __('First Name') }}</label>
                            <input type="text" name="first_name" id="first_name"
                                class="w-full bg-primary-dark/50 border border-white/5 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all font-sans text-base"
                                placeholder="{{ __('John') }}" required>
                        </div>

                        {{-- Last Name --}}
                        <div class="space-y-1.5">
                            <label for="last_name"
                                class="block text-xs font-mono font-medium text-text-secondary uppercase tracking-wider">{{ __('Last Name') }}</label>
                            <input type="text" name="last_name" id="last_name"
                                class="w-full bg-primary-dark/50 border border-white/5 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all font-sans text-base"
                                placeholder="{{ __('Doe') }}" required>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label for="email"
                            class="block text-xs font-mono font-medium text-text-secondary uppercase tracking-wider">{{ __('Email Address') }}</label>
                        <input type="email" name="email" id="email"
                            class="w-full bg-primary-dark/50 border border-white/5 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all font-sans text-base"
                            placeholder="{{ __('john@example.com') }}" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Password --}}
                        <div class="space-y-1.5">
                            <label for="password"
                                class="block text-xs font-mono font-medium text-text-secondary uppercase tracking-wider">{{ __('Password') }}</label>
                            <input type="password" name="password" id="password"
                                class="w-full bg-primary-dark/50 border border-white/5 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all font-sans text-base"
                                placeholder="••••••••" required>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="space-y-1.5">
                            <label for="password_confirmation"
                                class="block text-xs font-mono font-medium text-text-secondary uppercase tracking-wider">{{ __('Confirm Password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full bg-primary-dark/50 border border-white/5 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all font-sans text-base"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    {{-- Referral Code --}}
                    <div class="space-y-1.5">
                        <label for="referral_code"
                            class="block text-xs font-mono font-medium text-text-secondary uppercase tracking-wider">
                            {{ __('Referral Code') }} <span
                                class="text-white/30 font-sans normal-case">({{ __('Optional') }})</span>
                        </label>
                        <input type="text" name="referral_code" id="referral_code"
                            @if (session()->has('referrer_code')) readonly value="{{ session()->get('referrer_code') }}" @endif
                            class="w-full bg-primary-dark/50 border border-white/5 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all font-sans text-base"
                            placeholder="GH7T6HY">
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
                        class="w-full bg-gradient-to-r from-accent-primary to-accent-glow hover:from-accent-glow hover:to-accent-primary text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-accent-primary/20 transition-all duration-300 transform hover:-translate-y-0.5 relative overflow-hidden group cursor-pointer">
                        <span class="relative z-10">{{ __('Create Account') }}</span>
                        <div
                            class="absolute inset-0 h-full w-full bg-white/20 group-hover:bg-transparent transition-colors">
                        </div>
                    </button>
                </form>
            </div>
        @else
            {{-- Email Verification Step --}}
            <div class="space-y-8 relative">

                {{-- Start Over Button --}}
                <form action="{{ route('user.register-cancel') }}" method="POST"
                    class="ajax-form absolute -top-12 right-0 z-20" data-action="redirect"
                    data-redirect="{{ route('user.register') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 text-text-secondary text-xs font-bold uppercase tracking-wider hover:text-white transition-colors bg-white/5 hover:bg-white/10 px-3 py-2 rounded-lg cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        {{ __('Start Over') }}
                    </button>
                </form>

                <div class="text-center">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-accent-primary/20 to-accent-secondary/20 text-accent-primary mb-6 border border-white/5 shadow-inner">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-display font-bold text-white mb-2">{{ __('Email Verification') }}</h3>
                    <p class="text-text-secondary text-sm">
                        {{ __('Code sent to') }} <span
                            class="text-white font-mono bg-white/10 px-2 py-0.5 rounded">{{ session('register_info.email') }}</span>
                    </p>
                </div>

                <form action="{{ route('user.email-verification') }}" method="POST" data-action="redirect"
                    data-redirect="{{ route('user.dashboard') }}" class="ajax-form space-y-6">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('register_info.email') }}">

                    <div class="space-y-2 text-center">
                        <label for="otp_code" class="sr-only">{{ __('OTP Code') }}</label>
                        <div class="relative max-w-xs mx-auto">
                            <input type="text" name="otp_code" id="otp_code"
                                class="w-full bg-primary-dark/50 border border-white/10 rounded-2xl px-4 py-4 text-white placeholder-white/10 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all text-center tracking-[1em] text-3xl font-mono font-bold"
                                placeholder="••••••" required maxlength="6">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-accent-primary to-accent-glow hover:from-accent-glow hover:to-accent-primary text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-accent-primary/20 transition-all duration-300 transform hover:-translate-y-0.5 relative overflow-hidden group cursor-pointer">
                        <span class="relative z-10">{{ __('Verify Email') }}</span>
                        <div
                            class="absolute inset-0 h-full w-full bg-white/20 group-hover:bg-transparent transition-colors">
                        </div>
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-text-secondary text-sm mb-2">{{ __('Didn\'t receive the code?') }}
                        <span id="countdown-timer" class="text-text-secondary text-sm ml-1 hidden">
                            ({{ __('Wait') }} <span id="timer-seconds" class="font-mono">60</span>s)
                        </span>
                    </p>
                    <form action="{{ route('user.resend-verification') }}" method="POST"
                        class="ajax-form inline-block">
                        @csrf
                        <button type="submit" id="resend-btn"
                            class="text-accent-primary font-bold text-sm hover:underline disabled:opacity-50 disabled:cursor-not-allowed bg-transparent border-0 p-0 cursor-pointer">
                            {{ __('Resend Code') }}
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <div class="text-center pt-4 border-t border-white/5 mt-6">
            <p class="text-text-secondary text-sm">
                {{ __('Already have an account?') }}
                <a href="{{ route('user.login') }}"
                    class="text-white hover:text-accent-primary font-bold transition-colors ml-1">
                    {{ __('Sign In') }}
                </a>
            </p>
        </div>
    </div>
@endsection


@section('scripts')
    @if (getSetting('google_recaptcha') == 'enabled')
        {!! NoCaptcha::renderJs() !!}
    @endif

    <script>
        $(document).ready(function() {
            let resendBtn = $('#resend-btn');
            let countdownTimer = $('#countdown-timer');
            let timerSeconds = $('#timer-seconds');
            let timeLeft = 60;
            let timerInterval;

            // Check if there's a stored timestamp for throttle OR backend says throttle is active
            let storedThrottle = localStorage.getItem('resend_throttle_expiry');
            let backendThrottle = @json($throttle ?? false);

            if (backendThrottle) {
                if (!storedThrottle || storedThrottle < new Date().getTime()) {
                    let expiry = new Date().getTime() + (60 * 1000);
                    localStorage.setItem('resend_throttle_expiry', expiry);
                    storedThrottle = expiry;
                }
            }

            if (storedThrottle) {
                let now = new Date().getTime();
                let distance = storedThrottle - now;

                if (distance > 0) {
                    timeLeft = Math.floor(distance / 1000);
                    startTimer();
                } else {
                    localStorage.removeItem('resend_throttle_expiry');
                }
            }

            function startTimer() {
                resendBtn.prop('disabled', true);
                countdownTimer.removeClass('hidden');

                // Clear any existing interval
                if (timerInterval) clearInterval(timerInterval);

                timerInterval = setInterval(function() {
                    timeLeft--;
                    timerSeconds.text(timeLeft);

                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        resendBtn.prop('disabled', false);
                        countdownTimer.addClass('hidden');
                        timeLeft = 60; // Reset for next time
                        localStorage.removeItem('resend_throttle_expiry');
                    }
                }, 1000);
            }


            // Re-binding logic specifically for this button's form
            $('form[action="{{ route('user.resend-verification') }}"]').on('submit', function() {
                // Set local storage for persistence across reloads
                let expiry = new Date().getTime() + (60 * 1000);
                localStorage.setItem('resend_throttle_expiry', expiry);
                startTimer();
            });

        });

        function toggleEmailForm() {
            document.getElementById('auth-selection').style.display = 'none';
            document.getElementById('email-register-container').style.display = 'block';
        }

        function toggleSelection() {
            document.getElementById('auth-selection').style.display = 'block';
            document.getElementById('email-register-container').style.display = 'none';
        }
    </script>
@endsection
