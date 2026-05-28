@extends('templates.bento.blades.layouts.auth')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h3 class="text-2xl font-display font-bold text-white tracking-tight">
                {{ __('Verify Identity') }}
            </h3>
            <p class="text-text-secondary text-sm mt-1 mb-6">{{ __('Enter the code sent to your email.') }}</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('user.login.otp.validate') }}" method="POST" class="ajax-form space-y-6" data-action="redirect">
            @csrf

            {{-- OTP Input --}}
            <div class="space-y-2 text-center">
                <label for="otp" class="sr-only">{{ __('Verification Code') }}</label>
                <div class="relative max-w-xs mx-auto">
                    <input type="text" name="otp_code" id="otp"
                        class="w-full bg-primary-dark/50 border border-white/10 rounded-2xl px-4 py-4 text-white placeholder-white/10 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all text-center tracking-[1em] text-3xl font-mono font-bold"
                        placeholder="••••••" maxlength="6" required autofocus autocomplete="one-time-code"
                        @if (session()->has('login_otp_code') && config('app.env') == 'sandbox') value="{{ session('login_otp_code') }}" @endif>
                </div>
                @error('otp_code')
                    <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                @enderror
                @if (session('success'))
                    <p class="text-emerald-400 text-xs mt-2">{{ session('success') }}</p>
                @endif
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full bg-gradient-to-r from-accent-primary to-accent-glow hover:from-accent-glow hover:to-accent-primary text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-accent-primary/20 transition-all duration-300 transform hover:-translate-y-0.5 relative overflow-hidden group cursor-pointer">
                <span class="relative z-10">{{ __('Verify & Login') }}</span>
                <div class="absolute inset-0 h-full w-full bg-white/20 group-hover:bg-transparent transition-colors"></div>
            </button>
        </form>

        {{-- Resend Code --}}
        <div class="text-center mt-6 space-y-4">
            <div class="flex items-center justify-center gap-2 text-sm text-text-secondary">
                <span>{{ __('Did not receive the code?') }}</span>
                <form action="{{ route('user.login.resend-otp') }}" method="POST" id="resend-form"
                    class="ajax-form inline-block">
                    @csrf
                    <button type="submit" id="resend-btn"
                        class="text-accent-primary font-bold hover:text-accent-glow hover:underline transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:no-underline bg-transparent border-0 p-0 cursor-pointer">
                        {{ __('Resend Code') }}
                    </button>
                </form>
                <span id="countdown-timer" class="text-text-secondary hidden">
                    ({{ __('Wait') }} <span id="timer-seconds" class="font-mono">60</span>s)
                </span>
            </div>
        </div>

        {{-- Logout --}}
        <div class="text-center pt-2">
            <form action="{{ route('user.logout') }}" method="POST" class="ajax-form" data-action="redirect"
                data-redirect="{{ route('home') }}">
                @csrf
                <button type="submit"
                    class="text-xs font-medium text-text-secondary hover:text-white transition-colors bg-transparent border-0 p-0 cursor-pointer">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>

    </div>
@endsection

@section('scripts')
    {{-- if google Captcha is enabled --}}
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
            let storedThrottle = localStorage.getItem('login_resend_throttle_expiry');
            let backendThrottle = @json($throttle ?? false);

            if (backendThrottle) {
                if (!storedThrottle || storedThrottle < new Date().getTime()) {
                    let expiry = new Date().getTime() + (60 * 1000);
                    localStorage.setItem('login_resend_throttle_expiry', expiry);
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
                    localStorage.removeItem('login_resend_throttle_expiry');
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
                        localStorage.removeItem('login_resend_throttle_expiry');
                    }
                }, 1000);
            }


            // Re-binding logic specifically for this button's form
            $('form[action="{{ route('user.login.resend-otp') }}"]').on('submit', function() {
                // Set local storage for persistence across reloads
                let expiry = new Date().getTime() + (60 * 1000);
                localStorage.setItem('login_resend_throttle_expiry', expiry);
                startTimer();
            });

        });
    </script>
@endsection
