@extends('templates.bento.blades.layouts.auth')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h3 class="text-2xl font-display font-bold text-white tracking-tight">
                {{ __('Welcome Back') }}
            </h3>
            <p class="text-text-secondary text-sm mt-1 mb-6">
                {{ __('Sign in to access your dashboard.') }}</p>
        </div>

        {{-- Login Options Selection --}}
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

        {{-- Email Login Form Container --}}
        <div id="email-login-container" @if (!$only_email) style="display: none;" @endif class="space-y-6">
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

            <form action="{{ route('user.login.validate') }}" method="POST" class="ajax-form space-y-4"
                data-action="redirect">
                @csrf

                {{-- Email --}}
                <div class="space-y-1.5">
                    <label for="email"
                        class="block text-xs font-mono font-medium text-text-secondary uppercase tracking-wider">{{ __('Email') }}</label>
                    <input type="email" name="email" id="email"
                        class="w-full bg-primary-dark/50 border border-white/5 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all font-sans text-base"
                        placeholder="{{ __('john@example.com') }}" value="{{ old('email') }}" required autofocus>
                </div>

                {{-- Password --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="password"
                            class="block text-xs font-mono font-medium text-text-secondary uppercase tracking-wider">{{ __('Password') }}</label>
                        <a href="{{ route('user.forgot-password') }}"
                            class="text-xs text-accent-primary hover:text-accent-glow transition-colors font-medium">
                            {{ __('Forgot password?') }}
                        </a>
                    </div>
                    <input type="password" name="password" id="password"
                        class="w-full bg-primary-dark/50 border border-white/5 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all font-sans text-base"
                        placeholder="••••••••" required>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 bg-primary-dark border-white/10 rounded text-accent-primary focus:ring-accent-primary focus:ring-offset-0 transition-colors">
                    <label for="remember" class="ml-2 block text-sm text-text-secondary">
                        {{ __('Remember me') }}
                    </label>
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
                    <span class="relative z-10">{{ __('Sign In') }}</span>
                    <div class="absolute inset-0 h-full w-full bg-white/20 group-hover:bg-transparent transition-colors">
                    </div>
                </button>
            </form>
        </div>

        {{-- Register Link --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-text-secondary">
                {{ __("Don't have an account?") }}
                <a href="{{ route('user.register') }}"
                    class="font-bold text-white hover:text-accent-primary transition-colors ml-1">
                    {{ __('Sign Up') }}
                </a>
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- if google Captcha is enabled --}}
    @if (getSetting('google_recaptcha') == 'enabled')
        {!! NoCaptcha::renderJs() !!}
    @endif

    <script>
        function toggleEmailForm() {
            document.getElementById('auth-selection').style.display = 'none';
            document.getElementById('email-login-container').style.display = 'block';
        }

        function toggleSelection() {
            document.getElementById('auth-selection').style.display = 'block';
            document.getElementById('email-login-container').style.display = 'none';
        }
    </script>
@endsection
