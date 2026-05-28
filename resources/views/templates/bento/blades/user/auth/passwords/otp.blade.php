@extends('templates.bento.blades.layouts.auth')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h3 class="text-2xl font-display font-bold text-white tracking-tight">
                {{ __('OTP Verification') }}
            </h3>
            <p class="text-text-secondary text-sm mt-1 mb-6">
                {{ __('A 6-digit verification code has been sent to your email address.') }}
            </p>
        </div>

        {{-- Form --}}
        <form action="{{ route('user.forgot-password.otp.validate') }}" method="POST" class="ajax-form space-y-6"
            data-action="redirect">
            @csrf

            {{-- OTP Input --}}
            <div class="space-y-2 text-center">
                <label for="otp_code" class="sr-only">{{ __('Verification Code') }}</label>
                <div class="relative max-w-xs mx-auto">
                    <input type="text" name="otp_code" id="otp_code"
                        class="w-full bg-primary-dark/50 border border-white/10 rounded-2xl px-4 py-4 text-white placeholder-white/10 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all text-center tracking-[1em] text-3xl font-mono font-bold"
                        placeholder="••••••" maxlength="6" required autofocus autocomplete="one-time-code">
                </div>
                @error('otp_code')
                    <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
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
                <span class="relative z-10">{{ __('Verify & Proceed') }}</span>
                <div class="absolute inset-0 h-full w-full bg-white/20 group-hover:bg-transparent transition-colors"></div>
            </button>
        </form>

        {{-- Back --}}
        <div class="text-center pt-2">
            <a href="{{ route('user.forgot-password') }}"
                class="text-xs font-medium text-text-secondary hover:text-white transition-colors">
                {{ __('Back') }}
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    @if (getSetting('google_recaptcha') == 'enabled')
        {!! NoCaptcha::renderJs() !!}
    @endif
@endsection
