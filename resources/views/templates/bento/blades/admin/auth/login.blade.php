@extends('templates.bento.blades.admin.layouts.auth')

@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">{{ __('Welcome Back') }}</h2>
            <p class="text-slate-400 text-sm mt-1">{{ __('Please sign in to your administrator account.') }}</p>
        </div>

        <div id="auth-selection" class="space-y-4" @if ($show_email_only) style="display: none;" @endif>
            @foreach ($login_methods as $method => $data)
                @if ($data['status'] === 'enabled')
                    @if ($method === 'email')
                        {{-- Continue with Email --}}
                        <button type="button" onclick="toggleEmailForm()"
                            class="flex items-center justify-center gap-3 w-full bg-slate-800/50 hover:bg-slate-800/80 text-white font-semibold py-3 px-4 rounded-xl border border-white/10 transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer">
                            {!! $data['icon'] !!}
                            <span>{{ __('Continue with ' . $data['name']) }}</span>
                        </button>
                    @else
                        {{-- Social Logins --}}
                        <a href="{{ route('admin.login.google', $method) }}"
                            class="flex items-center justify-center gap-3 w-full {{ $method === 'google' ? 'bg-white text-slate-900 border-slate-200 hover:bg-slate-50' : 'bg-slate-800/50 text-white border-white/10 hover:bg-slate-800/80' }} font-semibold py-3 px-4 rounded-xl shadow-sm transition-all duration-300 transform hover:-translate-y-0.5 border cursor-pointer">
                            {!! $data['icon'] !!}
                            <span>{{ __('Continue with ' . $data['name']) }}</span>
                        </a>
                    @endif
                @endif
            @endforeach
        </div>

        <div id="email-login-container" @if (!$show_email_only) style="display: none;" @endif class="space-y-6">
            @if (!$show_email_only)
                <button type="button" onclick="toggleSelection()"
                    class="text-indigo-400 hover:text-indigo-300 text-sm flex items-center gap-2 transition-colors cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12" />
                        <polyline points="12 19 5 12 12 5" />
                    </svg>
                    {{ __('Back to options') }}
                </button>
            @endif

            <form action="{{ route('admin.login.validate') }}" method="POST" class="ajax-form space-y-5"
                data-action="redirect">
                @csrf

                {{-- Username or Email --}}
                <div class="space-y-2">
                    <label for="login"
                        class="block text-xs font-mono font-medium text-slate-400 uppercase tracking-wider">
                        {{ __('Username or Email') }}
                    </label>
                    <input type="text" name="login" id="login"
                        class="w-full bg-slate-800/50 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-sans text-base"
                        placeholder="{{ __('Enter your username or email') }}" required autofocus>
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password"
                            class="block text-xs font-mono font-medium text-slate-400 uppercase tracking-wider">
                            {{ __('Password') }}
                        </label>
                    </div>
                    <input type="password" name="password" id="password"
                        class="w-full bg-slate-800/50 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-sans text-base"
                        placeholder="••••••••" required>
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 bg-slate-800 border-white/10 rounded text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0 transition-colors">
                        <label for="remember" class="ml-2 block text-sm text-slate-400">
                            {{ __('Remember me') }}
                        </label>
                    </div>
                    <div>
                        <a href="{{ route('admin.forgot-password') }}"
                            class="text-sm text-indigo-500 hover:text-indigo-400 transition-colors font-medium">
                            {{ __('Forgot password?') }}
                        </a>
                    </div>
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
                    <span class="relative z-10">{{ __('Sign In to Admin') }}</span>
                    <div class="absolute inset-0 bg-white/10 group-hover:bg-transparent transition-colors"></div>
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
