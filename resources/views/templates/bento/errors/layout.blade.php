<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ config('languages.' . app()->getLocale() . '.rtl') ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ getSetting('name') ?? config('site.app_name') }}</title>
    <link rel="icon" type="image/png"
        href="{{ asset('assets/images/' . getSetting('favicon', config('site.favicon'))) }}">
    @if (config('site.user_vite'))
        @vite(['resources/views/templates/bento/css/app.css'])
    @else
        <link rel="stylesheet"
            href="{{ asset('assets/templates/bento/css/main.css' . '?v=' . filemtime(public_path('assets/templates/bento/css/main.css'))) }}">
    @endif
</head>

<body
    class="bg-primary-dark text-text-primary font-body antialiased selection:bg-accent-primary selection:text-white relative h-screen overflow-hidden">

    {{-- Global Abstract Background Elements (Matches Auth Layout) --}}
    <div class="fixed inset-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        {{-- Grid Pattern Overlay --}}
        <div
            class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTTAgNDBMMCAwTDQwIDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsIDI1NSwgMjU1LCAwLjAzKSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIiAvPjwvc3ZnPg==')] opacity-30">
        </div>

        {{-- Dynamic Orbs --}}
        <div
            class="absolute -top-[10%] -left-[10%] w-[70vw] h-[70vw] rounded-full bg-gradient-to-br from-accent-primary/20 via-accent-violet/10 to-transparent blur-[100px] mix-blend-screen animate-pulse-slow">
        </div>
        <div
            class="absolute top-[20%] -right-[10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-bl from-accent-secondary/10 via-accent-cyan/10 to-transparent blur-[90px] mix-blend-screen animate-float">
        </div>
        <div
            class="absolute -bottom-[20%] left-[20%] w-[70vw] h-[70vw] rounded-full bg-gradient-to-t from-accent-glow/10 via-accent-primary/5 to-transparent blur-[110px] mix-blend-screen">
        </div>
    </div>

    <div class="relative z-10 flex flex-col h-full">
        {{-- Language Switcher (Dropdown) --}}
        <div class="absolute top-6 right-6 z-50">
            <div class="relative group">
                <button
                    class="flex items-center gap-2 text-text-secondary hover:text-white transition-all bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/20 rounded-full px-4 py-2 backdrop-blur-sm cursor-pointer">
                    @php
                        $locale = app()->getLocale();
                        $languages = config('languages');
                        $currentLang = $languages[$locale] ?? ['name' => $locale, 'flag' => 'us'];
                    @endphp
                    <img loading="lazy" decoding="async" src="{{ asset('assets/flags/' . $currentLang['flag'] . '.svg') }}"
                        alt="{{ $currentLang['name'] }}" class="w-4 h-4 rounded-full object-cover">
                    <span class="text-sm font-medium hidden sm:inline-block">{{ $currentLang['name'] }}</span>
                    <svg class="w-3 h-3 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>

                {{-- Dropdown Menu --}}
                <div
                    class="absolute right-0 top-full mt-2 w-40 bg-secondary-dark/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right overflow-hidden">
                    <div class="p-1">
                        @foreach ($languages as $code => $lang)
                            <a href="{{ route('lang.switch', $code) }}"
                                class="flex items-center gap-3 px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ app()->getLocale() == $code ? 'bg-accent-primary/10 text-accent-primary' : '' }}">
                                <img loading="lazy" decoding="async" src="{{ asset('assets/flags/' . $lang['flag'] . '.svg') }}"
                                    alt="{{ $lang['name'] }}" class="w-4 h-4 rounded-full object-cover">
                                {{ $lang['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        {{-- Header / Logo --}}
        <header class="flex-none p-6 md:p-12 text-center">
            <a href="{{ route('home') }}" class="inline-block group">
                @php
                    $logo = getSetting('logo_rectangle');
                    $logoPath = public_path('assets/images/' . $logo);
                    $version = $logo && file_exists($logoPath) ? filemtime($logoPath) : time();
                    $logoUrl = $logo ? asset('assets/images/' . $logo) . '?v=' . $version : null;
                @endphp

                @if ($logoUrl)
                    <img loading="lazy" decoding="async" src="{{ $logoUrl }}" alt="{{ getSetting('name') }}"
                        class="h-12 md:h-16 w-auto mx-auto drop-shadow-lg transition-transform duration-300 group-hover:scale-105">
                @else
                    <h1
                        class="font-display text-4xl text-white tracking-tight drop-shadow-2xl group-hover:text-accent-primary transition-colors duration-300">
                        {{ getSetting('name', config('site.app_name')) }}
                    </h1>
                @endif
            </a>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 flex items-center justify-center p-4">
            <div class="max-w-3xl w-full text-center space-y-8 relative z-20">

                {{-- Error Code --}}
                <div class="relative inline-block group cursor-default">
                    <h1
                        class="font-display text-9xl md:text-[12rem] leading-none text-transparent bg-clip-text bg-gradient-to-b from-white to-white/10 select-none drop-shadow-2xl">
                        @yield('code')
                    </h1>
                    <div
                        class="absolute inset-0 bg-accent-primary/20 blur-[100px] rounded-full -z-10 opacity-50 group-hover:opacity-80 transition-opacity duration-700">
                    </div>
                </div>

                {{-- Message --}}
                <div class="space-y-4">
                    <h2 class="font-display text-2xl md:text-4xl text-white font-bold uppercase tracking-wide">
                        @yield('message')
                    </h2>
                    <p class="text-text-secondary text-lg max-w-lg mx-auto font-sans">
                        @yield('description', __('We couldn\'t find the page you were looking for. It might have been removed, renamed, or did not exist in the first place.'))
                    </p>
                </div>

                {{-- Actions --}}
                <div class="pt-8">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-accent-primary to-accent-glow hover:from-accent-glow hover:to-accent-primary text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-accent-primary/20 transition-all duration-300 transform hover:-translate-y-1 group relative overflow-hidden">
                        <span class="relative z-10 font-display tracking-wide">{{ __('Return Home') }}</span>
                        <div class="absolute inset-0 bg-white/20 group-hover:bg-transparent transition-colors"></div>
                        <svg class="w-5 h-5 relative z-10 group-hover:-translate-x-1 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                </div>

            </div>
        </main>

        {{-- Footer --}}
        <footer class="flex-none p-6 text-center">
            <p class="font-body text-text-secondary text-xs uppercase tracking-widest">
                © {{ date('Y') }} {{ getSetting('name') ?? config('site.app_name') }}.
                {{ __('All rights reserved.') }}
            </p>
        </footer>
    </div>
</body>

</html>
