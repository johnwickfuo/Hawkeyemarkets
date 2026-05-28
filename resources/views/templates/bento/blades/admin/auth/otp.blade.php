@extends('templates.bento.blades.admin.layouts.auth')

@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">{{ __('OTP Verification') }}</h2>
            <p class="text-slate-400 text-sm mt-1">
                {{ __('A 6-digit verification code has been sent to your email address.') }}</p>
        </div>

        <form action="{{ route('admin.login.otp.validate') }}" method="POST" class="ajax-form space-y-5"
            data-action="redirect">
            @csrf

            {{-- OTP Code --}}
            <div class="space-y-2">
                <label for="otp_code" class="block text-xs font-mono font-medium text-slate-400 uppercase tracking-wider">
                    {{ __('Verification Code') }}
                </label>
                <input type="text" name="otp_code" id="otp_code"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-sans text-base text-center tracking-[0.5em] font-bold"
                    placeholder="••••••" required autofocus maxlength="6"
                    @if (session('admin_login_otp_code') && config('app.env') == 'sandbox') value="{{ session('admin_login_otp_code') }}" @endif>

                @if (config('app.env') == 'sandbox')
                    <p class="text-xs text-indigo-400 mt-2">OTP Code automatically filled in sandbox mode.</p>
                @endif
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-500/20 transition-all duration-300 transform hover:-translate-y-0.5 relative overflow-hidden group cursor-pointer">
                <span class="relative z-10">{{ __('Verify & Login') }}</span>
                <div class="absolute inset-0 bg-white/10 group-hover:bg-transparent transition-colors"></div>
            </button>
        </form>

        {{-- Resend OTP --}}
        <div class="pt-4 text-center">
            <p class="text-sm text-slate-400">
                {{ __("Didn't receive the code?") }}
                <button type="button" id="resend-otp-btn"
                    class="font-bold text-white hover:text-indigo-400 transition-colors ml-1 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                    {{ $throttle ? 'disabled' : '' }}>
                    {{ __('Resend Code') }}
                </button>
            </p>
            <div id="resend-timer" class="text-xs text-indigo-400 mt-2 {{ $throttle ? '' : 'hidden' }}">
                {{ __('Resend available in') }} <span id="timer-seconds">60</span>s
            </div>
        </div>

        {{-- Logout --}}
        <div class="text-center pt-2">
            <form action="{{ route('admin.logout') }}" method="POST" class="ajax-form" data-action="redirect"
                data-redirect="{{ route('admin.login') }}">
                @csrf
                <button type="submit"
                    class="text-xs font-medium text-slate-400 hover:text-white transition-colors bg-transparent border-0 p-0 cursor-pointer">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let timerSeconds = 60;
            let timerInterval;

            const $resendBtn = $('#resend-otp-btn');
            const $resendTimer = $('#resend-timer');
            const $timerSeconds = $('#timer-seconds');

            @if ($throttle)
                startTimer();
            @endif

            function startTimer() {
                timerSeconds = 60;
                $resendBtn.prop('disabled', true);
                $resendTimer.removeClass('hidden');

                clearInterval(timerInterval);
                timerInterval = setInterval(() => {
                    timerSeconds--;
                    $timerSeconds.text(timerSeconds);

                    if (timerSeconds <= 0) {
                        clearInterval(timerInterval);
                        $resendBtn.prop('disabled', false);
                        $resendTimer.addClass('hidden');
                    }
                }, 1000);
            }

            $resendBtn.on('click', function() {
                if ($(this).prop('disabled')) return;

                const $btn = $(this);
                const originalHtml = $btn.html();

                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

                $.ajax({
                    url: "{{ route('admin.login.otp.resend') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            toastNotification(response.message, 'success');
                            startTimer();
                        } else {
                            toastNotification(response.message, 'error');
                            $btn.prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON ? xhr.responseJSON.message :
                            "{{ __('Something went wrong') }}";
                        toastNotification(message, 'error');
                        $btn.prop('disabled', false);
                    },
                    complete: function() {
                        $btn.html(originalHtml);
                    }
                });
            });
        });
    </script>
@endsection
