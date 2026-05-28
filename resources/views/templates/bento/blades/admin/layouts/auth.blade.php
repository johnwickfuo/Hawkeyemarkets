<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ config('languages.' . app()->getLocale() . '.rtl') ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page_title ?? __('Admin Auth') }} | {{ getSetting('name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/' . getSetting('favicon')) }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0f172a">
    <meta name="author" content="{{ getSetting('name') }} Team">
    <meta name="publisher" content="{{ getSetting('name') }} Team">

    @if (config('site.use_vite'))
        @vite(['resources/views/templates/bento/css/app.css'])
    @else
        <link rel="stylesheet"
            href="{{ asset('assets/templates/bento/css/main.css' . '?v=' . filemtime(public_path('assets/templates/bento/css/main.css'))) }}">
    @endif

    <style>
        .admin-auth-bg {
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent),
                radial-gradient(circle at bottom left, rgba(168, 85, 247, 0.15), transparent),
                #0f172a;
        }
    </style>
</head>

<body class="admin-auth-bg text-slate-200 font-sans antialiased h-screen flex items-center justify-center p-4">

    @include('templates.bento.blades.partials.dashoard-preloader')

    {{-- Language Switcher --}}
    <div class="absolute top-6 right-6 z-50">
        <div class="relative group" id="lang-switcher">
            <button id="lang-btn"
                class="flex items-center gap-2 text-slate-400 hover:text-white transition-all bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/20 rounded-full px-4 py-2 backdrop-blur-sm cursor-pointer"
                onclick="toggleLanguageMenu(event)">
                @php
                    $locale = app()->getLocale();
                    $languages = config('languages');
                    $currentLang = $languages[$locale] ?? ['name' => $locale, 'flag' => 'us'];
                @endphp
                <img loading="lazy" decoding="async" src="{{ asset('assets/flags/' . $currentLang['flag'] . '.svg') }}" alt="{{ $currentLang['name'] }}"
                    class="w-4 h-4 rounded-full object-cover">
                <span class="text-sm font-medium hidden sm:inline-block">{{ $currentLang['name'] }}</span>
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                    </path>
                </svg>
            </button>

            <div id="lang-menu"
                class="absolute right-0 top-full mt-2 w-40 bg-slate-900/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right overflow-hidden z-50">
                <div class="p-1">
                    @foreach ($languages as $code => $lang)
                        <a href="{{ route('lang.switch', $code) }}"
                            class="flex items-center gap-3 px-3 py-2 text-sm text-slate-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ app()->getLocale() == $code ? 'bg-indigo-500/10 text-indigo-500' : '' }}">
                            <img loading="lazy" decoding="async" src="{{ asset('assets/flags/' . $lang['flag'] . '.svg') }}" alt="{{ $lang['name'] }}"
                                class="w-4 h-4 rounded-full object-cover">
                            {{ $lang['name'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-md w-full">
        {{-- Brand Header --}}
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center p-3 bg-white/5 rounded-2xl border border-white/10 mb-4">
                @php
                    $logo = getSetting('logo_rectangle');
                @endphp
                @if ($logo)
                    <img loading="lazy" decoding="async" src="{{ asset('assets/images/' . $logo) }}" alt="{{ getSetting('name') }}"
                        class="h-10 w-auto">
                @else
                    <span class="text-2xl font-bold text-white tracking-tight">{{ getSetting('name') }}</span>
                @endif
            </div>
            <h1 class="text-xl font-semibold text-white">{{ __('Admin Control Panel') }}</h1>
            <p class="text-slate-400 text-sm mt-1">{{ __('Authorized access only') }}</p>
        </div>

        {{-- Main Card --}}
        <div class="relative group">
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000">
            </div>
            <div class="relative bg-slate-900/80 backdrop-blur-xl border border-white/10 p-8 rounded-2xl shadow-2xl">
                @yield('content')
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-8 text-center text-slate-500 text-xs">
            <p>&copy; {{ date('Y') }} {{ getSetting('name') }}. {{ __('All rights reserved.') }}</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (config('site.use_vite'))
        @vite(['resources/views/templates/bento/js/app.js'])
    @else
        <script
            src="{{ asset('assets/templates/bento/js/main.js' . '?v=' . filemtime(public_path('assets/templates/bento/js/main.js'))) }}">
        </script>
    @endif

    <script>
        function toggleLanguageMenu(event) {
            event.stopPropagation();
            const menu = document.getElementById('lang-menu');
            if (menu.classList.contains('invisible')) {
                menu.classList.remove('invisible', 'opacity-0');
            } else {
                menu.classList.add('invisible', 'opacity-0');
            }
        }

        document.addEventListener('click', function(event) {
            const menu = document.getElementById('lang-menu');
            const btn = document.getElementById('lang-btn');
            if (!menu.classList.contains('invisible') && !btn.contains(event.target) && !menu.contains(event
                    .target)) {
                menu.classList.add('invisible', 'opacity-0');
            }
        });
    </script>

    @include('templates.bento.blades.partials.notifications')
    @yield('scripts')
</body>

</html>
