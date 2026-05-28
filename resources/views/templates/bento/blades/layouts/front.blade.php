@php
    $locale = app()->getLocale();
    $languages = config('languages');
    $currentLang = $languages[$locale] ?? ['name' => $locale, 'flag' => 'us'];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ config('languages.' . app()->getLocale() . '.rtl') ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    @php
        $siteName = getSetting('name', config('app.name'));
        $favicon = asset('assets/images/' . getSetting('favicon', 'favicon.png'));
        $seoImage = asset('assets/images/' . getSetting('seo_image', 'seo-banner.jpg'));

        // Page title
        $title = request()->routeIs('home')
            ? $siteName . ' - ' . __('The Financial Ecosystem')
            : __($page_title ?? $siteName) . ' - ' . $siteName;

        // SEO meta
        $description = __(
            $page_description ??
                getSetting(
                    'seo_description',
                    'Invest in stocks, ETFs, and cryptocurrencies on a secure, data-driven financial platform built for portfolio growth and wealth management.',
                ),
        );
        $keywords = __(
            $page_keywords ??
                getSetting(
                    'seo_keywords',
                    'investment platform, online investing, stocks, etf, crypto, portfolio management, wealth management, finance',
                ),
        );

        // Social meta
        $socialTitle = $title;
        $socialDescription = $description;

        // URL meta
        $canonical = url()->current();

        // Theme color
        $themeColorDark = '#0f172a'; // slate-900 (Brand Primary)
        $brandAccent = '#8b5cf6'; // Violet 500 (Brand Accent)

        // Indexing
        $indexing = (bool) config('site.search_engine_indexing', true);
        $robots = $indexing
            ? 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1'
            : 'noindex,nofollow';

        // Optional: If you have a twitter handle (e.g. @yourbrand)
        $twitterUrl = $social_links['twitter'] ?? ($social_links['x'] ?? null);
        $twitterSite = $twitterUrl ? '@' . basename(parse_url($twitterUrl, PHP_URL_PATH)) : null;

    @endphp

    {{-- Title --}}
    <title>{{ $title }}</title>

    {{-- Basic SEO --}}
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="robots" content="{{ $robots }}">
    <meta name="googlebot" content="{{ $robots }}">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <link rel="canonical" href="{{ $canonical }}">

    {{-- Favicons --}}
    <link rel="icon" type="image/png" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <link rel="mask-icon" href="{{ asset('assets/images/safari-pinned-tab.svg') }}" color="{{ $brandAccent }}">

    {{-- Theme Color (Dark Mode Only) --}}
    <meta name="theme-color" content="{{ $themeColorDark }}">
    {{-- <meta name="color-scheme" content="dark text-only"> --}}

    {{-- Open Graph (Facebook / WhatsApp / LinkedIn) --}}
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $socialTitle }}">
    <meta property="og:description" content="{{ $socialDescription }}">
    <meta property="og:type" content="{{ request()->routeIs('home') ? 'website' : 'article' }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:locale" content="{{ $locale }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:image:secure_url" content="{{ $seoImage }}">
    <meta property="og:image:alt" content="{{ $socialTitle }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $socialTitle }}">
    <meta name="twitter:description" content="{{ $socialDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    @if ($twitterSite)
        <meta name="twitter:site" content="{{ $twitterSite }}">
    @endif

    {{-- Optional: Author / Publisher (set these if you want) --}}
    <meta name="author" content="{{ $siteName }} Team">
    <meta name="publisher" content="{{ $siteName }} Team">


    {{-- Google Fonts: preconnect first, then non-blocking load --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400&family=Montserrat:wght@700&family=Playfair+Display:wght@400&family=Raleway:wght@600&family=Roboto:wght@400&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400&family=Montserrat:wght@700&family=Playfair+Display:wght@400&family=Raleway:wght@600&family=Roboto:wght@400&display=swap"></noscript>

    @if (config('site.use_vite'))
        @vite(['resources/views/templates/bento/css/app.css'])
    @else
        <link rel="stylesheet"
            href="{{ asset('assets/templates/bento/css/main.css' . '?v=' . filemtime(public_path('assets/templates/bento/css/main.css'))) }}">
    @endif

    <style>
        {{-- Custom Keyframe Animations --}} @keyframes ticker-reverse {
            0% {
                transform: translateX(-50%);
            }

            100% {
                transform: translateX(0);
            }
        }

        @keyframes ticker {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .ticker-wrap {
            animation: ticker 30s linear infinite;
        }

        .ticker-wrap-reverse {
            animation: ticker-reverse 30s linear infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-delayed {
            animation: float 8s ease-in-out 2s infinite;
        }

        .animate-float-slow {
            animation: float 10s ease-in-out 1s infinite;
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.1);
            }
        }

        .animate-pulse-glow {
            animation: pulse-glow 4s ease-in-out infinite;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }

            to {
                transform: translateY(0);
            }
        }

        .typing-cursor::after {
            content: '|';
            animation: blink 1s step-end infinite;
            margin-left: 4px;
            color: var(--accent-primary, #3b82f6);
        }

        @keyframes blink {
            50% {
                opacity: 0;
            }
        }

        .navbar-fixed {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(5, 5, 5, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            animation: slideDown 0.4s ease forwards;
            height: 4rem;
            z-index: 100;
        }

        .navbar-fixed .max-w-7xl {
            height: 4rem;
        }

        {{-- Custom Mouse Cursor --}} #custom-cursor-dot,
        #custom-cursor-ring {
            pointer-events: none;
            position: fixed;
            top: 0;
            left: 0;
            border-radius: 50%;
            z-index: 9999;
            transition: opacity 0.3s ease, width 0.3s ease, height 0.3s ease, border 0.3s ease, background 0.3s ease;
            transform: translate(-50%, -50%);
            display: none;
        }

        #custom-cursor-dot {
            width: 8px;
            height: 8px;
            background-color: var(--accent-primary, #3b82f6);
            box-shadow: 0 0 15px var(--accent-primary, #3b82f6);
            transition: transform 0.1s ease-out;
        }

        #custom-cursor-ring {
            width: 40px;
            height: 40px;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            background-color: transparent;
        }

        #custom-cursor-label {
            position: fixed;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 10000;
            color: white;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.5);
            transition: all 0.3s ease;
            display: none;
        }

        .cursor-hover #custom-cursor-ring {
            width: 80px;
            height: 80px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(2px);
        }

        .cursor-hover #custom-cursor-dot {
            opacity: 0;
        }

        .cursor-has-label #custom-cursor-label {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        {{-- Micro-interactions --}} .btn-hover-effect {
            transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.2s ease;
        }

        .btn-hover-effect:hover {
            transform: scale(1.03);
        }

        .btn-hover-effect:active {
            transform: scale(0.98);
        }

        .card-hover-effect {
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .card-hover-effect:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        .input-focus-effect {
            transition: all 0.2s ease;
        }

        .input-focus-effect:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.5);
        }

        @media (max-width: 1024px) {

            #custom-cursor-dot,
            #custom-cursor-ring,
            #custom-cursor-label {
                display: none !important;
            }
        }
    </style>
    @stack('styles')
    {!! getSetting('header_scripts') !!}
</head>

<body
    class="bg-[#050505] text-text-primary font-body antialiased min-h-screen flex flex-col overflow-x-hidden selection:bg-accent-primary selection:text-white">

    {{-- Dashboard Preloader --}}
    @include('templates.bento.blades.partials.dashoard-preloader')

    {{-- Custom Cursor --}}
    <div id="custom-cursor-dot"></div>
    <div id="custom-cursor-ring"></div>
    <div id="custom-cursor-label"></div>

    {{-- Navbar --}}
    <header class="absolute top-0 inset-x-0 z-50 bg-transparent border-b border-white/0 transition-all duration-300"
        id="navbar">
        <div class="max-w-7xl mx-auto px-4 lg:px-6 h-20 flex items-center justify-between">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2 group">
                @if (getSetting('logo_rectangle'))
                    <img loading="lazy" decoding="async" src="{{ asset('assets/images/' . getSetting('logo_rectangle')) }}"
                        alt="{{ getSetting('name') }}"
                        class="h-8 w-auto transition-transform duration-300 group-hover:scale-105">
                @else
                    <h1 class="font-heading text-2xl font-bold text-white tracking-tight">
                        {{ getSetting('name') }}<span class="text-accent-primary">.</span>
                    </h1>
                @endif
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden lg:flex items-center gap-8">
                @foreach (config('menus.header_nav') as $item)
                    @if ($item['is_active'])
                        @php
                            $href = '#';
                            $isActive = false;
                            $hasActiveChild = false;
                            $itemType = $item['type'] ?? 'link'; // link, dropdown, mega

                            if ($item['route_name'] && Route::has($item['route_name'])) {
                                $href = route($item['route_name']);
                                $isActive = request()->routeIs($item['route_name']);
                            } elseif ($item['link']) {
                                $href = $item['link'];
                            }

                            // Check active children for Dropdowns
                            if ($itemType === 'dropdown' && !empty($item['sub_menu'])) {
                                foreach ($item['sub_menu'] as $subItem) {
                                    if (
                                        $subItem['route_name'] &&
                                        Route::has($subItem['route_name']) &&
                                        request()->routeIs($subItem['route_name'])
                                    ) {
                                        $hasActiveChild = true;
                                        break;
                                    }
                                }
                            }

                            // Check active children for Mega Menu
                            if ($itemType === 'mega' && !empty($item['sections'])) {
                                foreach ($item['sections'] as $section) {
                                    foreach ($section['items'] as $subItem) {
                                        if (
                                            $subItem['route_name'] &&
                                            Route::has($subItem['route_name']) &&
                                            request()->routeIs($subItem['route_name'])
                                        ) {
                                            $hasActiveChild = true;
                                            break 2;
                                        }
                                    }
                                }
                            }

                            $parentActive = $isActive || $hasActiveChild;
                        @endphp

                        @if ($itemType === 'link')
                            <a href="{{ $href }}"
                                class="text-sm font-medium transition-colors {{ $parentActive ? 'text-white' : 'text-text-secondary hover:text-white' }}">{{ __($item['name']) }}</a>
                        @elseif ($itemType === 'dropdown')
                            <div class="relative group">
                                <button
                                    class="flex items-center gap-1 text-sm font-medium transition-colors {{ $parentActive ? 'text-white' : 'text-text-secondary group-hover:text-white' }}">
                                    {{ __($item['name']) }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 transition-transform group-hover:rotate-180">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                <div
                                    class="absolute top-full left-0 pt-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50">
                                    <div
                                        class="bg-[#0f1115] border border-white/10 rounded-xl p-2 min-w-[200px] shadow-xl backdrop-blur-xl">
                                        @foreach ($item['sub_menu'] as $subItem)
                                            @if ($subItem['is_active'])
                                                @php
                                                    $subHref = '#';
                                                    $subIsActive = false;
                                                    if ($subItem['route_name'] && Route::has($subItem['route_name'])) {
                                                        $subHref = route($subItem['route_name']);
                                                        $subIsActive = request()->routeIs($subItem['route_name']);
                                                    } elseif ($subItem['link']) {
                                                        $subHref = $subItem['link'];
                                                    }
                                                @endphp
                                                <a href="{{ $subHref }}"
                                                    class="block px-4 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 rounded-lg transition-colors {{ $subIsActive ? 'text-white bg-white/5' : '' }}">
                                                    {{ __($subItem['name']) }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @elseif ($itemType === 'mega')
                            <div class="relative group">
                                <button
                                    class="flex items-center gap-1 text-sm font-medium transition-colors {{ $parentActive ? 'text-white' : 'text-text-secondary group-hover:text-white' }}">
                                    {{ __($item['name']) }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-4 h-4 transition-transform group-hover:rotate-180">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                <div
                                    class="absolute top-full left-1/2 -translate-x-1/2 pt-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2 z-50 w-screen max-w-4xl px-4">
                                    <div
                                        class="bg-[#0f1115] border border-white/10 rounded-2xl p-6 shadow-2xl backdrop-blur-xl grid grid-cols-3 gap-8">
                                        @foreach ($item['sections'] as $section)
                                            @if ($section['is_active'] ?? true)
                                                <div>
                                                    <h4 class="text-white font-bold mb-4 border-b border-white/5 pb-2">
                                                        {{ __($section['name']) }}</h4>
                                                    <div class="space-y-2">
                                                        @foreach ($section['items'] as $subItem)
                                                            @if ($subItem['is_active'])
                                                                @php
                                                                    $subHref = '#';
                                                                    $subIsActive = false;
                                                                    if (
                                                                        $subItem['route_name'] &&
                                                                        Route::has($subItem['route_name'])
                                                                    ) {
                                                                        $subHref = route($subItem['route_name']);
                                                                        $subIsActive = request()->routeIs(
                                                                            $subItem['route_name'],
                                                                        );
                                                                    } elseif ($subItem['link']) {
                                                                        $subHref = $subItem['link'];
                                                                    }
                                                                @endphp
                                                                <a href="{{ $subHref }}"
                                                                    class="block px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 rounded-lg transition-colors {{ $subIsActive ? 'text-white bg-white/5' : '' }}">
                                                                    {{ __($subItem['name']) }}
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </nav>

            {{-- Actions --}}
            <div class="flex items-center gap-4">
                {{-- Language Switcher --}}
                <div class="relative items-center flex">
                    <button id="lang-switcher-btn"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg border border-white/10 hover:bg-white/5 bg-white/5 transition-all duration-300">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/flags/' . $currentLang['flag'] . '.svg') }}"
                            alt="{{ $currentLang['name'] }}" class="w-4 h-4 rounded-full object-cover">
                        <span
                            class="text-sm font-medium text-text-secondary group-hover:text-white transition-colors">{{ $currentLang['name'] }}</span>
                        <svg class="w-3 h-3 text-text-secondary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div id="lang-dropdown"
                        class="absolute right-0 top-full mt-2 w-40 bg-secondary-dark/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl opacity-0 invisible transition-all duration-200 transform origin-top-right overflow-hidden z-50">
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

                {{-- Mobile Actions (Hidden on Desktop) --}}
                <div class="flex items-center gap-4 lg:hidden">
                    <button onclick="toggleMobileMenu()"
                        class="p-2 text-text-secondary hover:text-white transition-colors">
                        <svg class="icon" viewBox="0 0 32 32" width="20" height="20">
                            <rect x="6" y="9" width="20" height="2" rx="1" fill="currentColor" />
                            <rect x="10" y="15" width="16" height="2" rx="1" fill="currentColor" />
                            <rect x="6" y="21" width="12" height="2" rx="1" fill="currentColor" />
                        </svg>
                    </button>
                </div>

                {{-- Desktop Actions --}}
                <div class="hidden lg:flex items-center gap-4">
                    @auth
                        <a href="{{ route('user.dashboard') }}"
                            class="btn-hover-effect px-5 py-2.5 rounded-full bg-gradient-to-r from-accent-primary to-accent-secondary text-white text-sm font-bold shadow-lg shadow-accent-primary/25 hover:shadow-accent-primary/40 hover:scale-105 transition-all">
                            {{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('user.login') }}"
                            class="btn-hover-effect text-sm font-medium text-text-secondary hover:text-white transition-colors">
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('user.register') }}"
                            class="btn-hover-effect px-5 py-2.5 rounded-full bg-gradient-to-r from-accent-primary to-accent-secondary text-white text-sm font-bold shadow-lg shadow-accent-primary/25 hover:shadow-accent-primary/40 hover:scale-105 transition-all">
                            {{ __('Open Account') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>

    </header>

    {{-- Mobile Menu Overlay --}}
    <div id="mobile-menu"
        class="fixed inset-0 z-[110] bg-primary-dark/95 backdrop-blur-xl transform translate-x-full transition-transform duration-300 lg:hidden flex flex-col">
        <div class="p-6 border-b border-white/5 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                @if (getSetting('logo_rectangle'))
                    <img loading="lazy" decoding="async" src="{{ asset('assets/images/' . getSetting('logo_rectangle')) }}"
                        alt="{{ getSetting('name') }}"
                        class="h-6 w-auto transition-transform duration-300 group-hover:scale-105">
                @else
                    <h1 class="font-heading text-xl font-bold text-white tracking-tight">
                        {{ getSetting('name') }}<span class="text-accent-primary">.</span>
                    </h1>
                @endif
            </a>
            <button onclick="toggleMobileMenu()" class="p-2 text-text-secondary hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 flex flex-col gap-6">
            {{-- Mobile Nav Links --}}
            <nav class="flex flex-col gap-4">
                @foreach (config('menus.header_nav') as $item)
                    @if ($item['is_active'])
                        @php
                            $href = '#';
                            $isActive = false;
                            $hasActiveChild = false;
                            $itemType = $item['type'] ?? 'link';

                            if ($item['route_name'] && Route::has($item['route_name'])) {
                                $href = route($item['route_name']);
                                $isActive = request()->routeIs($item['route_name']);
                            } elseif ($item['link']) {
                                $href = $item['link'];
                            }

                            // Check active children for Dropdowns
                            if ($itemType === 'dropdown' && !empty($item['sub_menu'])) {
                                foreach ($item['sub_menu'] as $subItem) {
                                    if (
                                        $subItem['route_name'] &&
                                        Route::has($subItem['route_name']) &&
                                        request()->routeIs($subItem['route_name'])
                                    ) {
                                        $hasActiveChild = true;
                                        break;
                                    }
                                }
                            }

                            // Check active children for Mega Menu
                            if ($itemType === 'mega' && !empty($item['sections'])) {
                                foreach ($item['sections'] as $section) {
                                    foreach ($section['items'] as $subItem) {
                                        if (
                                            $subItem['route_name'] &&
                                            Route::has($subItem['route_name']) &&
                                            request()->routeIs($subItem['route_name'])
                                        ) {
                                            $hasActiveChild = true;
                                            break 2;
                                        }
                                    }
                                }
                            }

                            $parentActive = $isActive || $hasActiveChild;
                        @endphp

                        @if ($itemType === 'link')
                            <a href="{{ $href }}" onclick="toggleMobileMenu()"
                                class="text-lg font-medium transition-colors {{ $parentActive ? 'text-white' : 'text-text-secondary hover:text-white' }}">{{ __($item['name']) }}</a>
                        @elseif ($itemType === 'dropdown')
                            <div class="flex flex-col gap-2">
                                <button
                                    class="flex items-center justify-between w-full text-lg font-medium transition-colors {{ $parentActive ? 'text-white' : 'text-text-secondary hover:text-white' }}"
                                    onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180')">
                                    {{ __($item['name']) }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-5 h-5 transition-transform duration-300">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>
                                <div
                                    class="flex flex-col gap-2 pl-4 py-2 border-l border-white/10 hidden transition-all duration-300">
                                    @foreach ($item['sub_menu'] as $subItem)
                                        @if ($subItem['is_active'])
                                            @php
                                                $subHref = '#';
                                                $subIsActive = false;
                                                if ($subItem['route_name'] && Route::has($subItem['route_name'])) {
                                                    $subHref = route($subItem['route_name']);
                                                    $subIsActive = request()->routeIs($subItem['route_name']);
                                                } elseif ($subItem['link']) {
                                                    $subHref = $subItem['link'];
                                                }
                                            @endphp
                                            <a href="{{ $subHref }}" onclick="toggleMobileMenu()"
                                                class="text-base font-medium transition-colors {{ $subIsActive ? 'text-white' : 'text-text-secondary hover:text-white' }}">
                                                {{ __($subItem['name']) }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @elseif ($itemType === 'mega')
                            <div class="flex flex-col gap-2">
                                <button
                                    class="flex items-center justify-between w-full text-lg font-medium transition-colors {{ $parentActive ? 'text-white' : 'text-text-secondary hover:text-white' }}"
                                    onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180')">
                                    {{ __($item['name']) }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="w-5 h-5 transition-transform duration-300">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>
                                <div
                                    class="flex flex-col gap-4 pl-4 py-2 border-l border-white/10 hidden transition-all duration-300">
                                    @foreach ($item['sections'] as $section)
                                        @if ($section['is_active'] ?? true)
                                            <div>
                                                <h5 class="text-sm font-bold text-white mb-2">
                                                    {{ __($section['name']) }}</h5>
                                                <div class="flex flex-col gap-2">
                                                    @foreach ($section['items'] as $subItem)
                                                        @if ($subItem['is_active'])
                                                            @php
                                                                $subHref = '#';
                                                                $subIsActive = false;
                                                                if (
                                                                    $subItem['route_name'] &&
                                                                    Route::has($subItem['route_name'])
                                                                ) {
                                                                    $subHref = route($subItem['route_name']);
                                                                    $subIsActive = request()->routeIs(
                                                                        $subItem['route_name'],
                                                                    );
                                                                } elseif ($subItem['link']) {
                                                                    $subHref = $subItem['link'];
                                                                }
                                                            @endphp
                                                            <a href="{{ $subHref }}"
                                                                onclick="toggleMobileMenu()"
                                                                class="text-base font-medium transition-colors {{ $subIsActive ? 'text-white' : 'text-text-secondary hover:text-white' }}">
                                                                {{ __($subItem['name']) }}
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </nav>

            <div class="border-t border-white/5 pt-6 flex flex-col gap-4">
                @auth
                    <a href="{{ route('user.dashboard') }}" onclick="toggleMobileMenu()"
                        class="w-full py-3 text-center rounded-xl bg-gradient-to-r from-accent-primary to-accent-secondary text-white font-bold hover:shadow-lg hover:shadow-accent-primary/25 transition-all">
                        {{ __('Dashboard') }}
                    </a>
                @else
                    <a href="{{ route('user.login') }}" onclick="toggleMobileMenu()"
                        class="w-full py-3 text-center rounded-xl bg-white/5 border border-white/10 text-white font-bold hover:bg-white/10 transition-colors">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('user.register') }}" onclick="toggleMobileMenu()"
                        class="w-full py-3 text-center rounded-xl bg-gradient-to-r from-accent-primary to-accent-secondary text-white font-bold hover:shadow-lg hover:shadow-accent-primary/25 transition-all">
                        {{ __('Open Account') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <main class="flex-1 relative">
        @if (!request()->routeIs('home') && !request()->routeIs('trading.*'))
            {{-- Global Mini Hero / Page Header --}}
            <section class="relative min-h-[45vh] flex items-center justify-center overflow-hidden bg-[#050505]">
                {{-- Dynamic Background --}}
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    {{-- Grid Pattern Overlay --}}
                    <div
                        class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')] opacity-20 mix-blend-soft-light">
                    </div>

                    {{-- Animated Orbs --}}
                    <div
                        class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[120px] animate-float-slow">
                    </div>
                    <div
                        class="absolute bottom-[-20%] right-[-10%] w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[120px] animate-float-delayed">
                    </div>
                    <div
                        class="absolute top-[30%] left-[50%] -translate-x-1/2 w-[800px] h-[400px] bg-accent-primary/10 rounded-full blur-[100px] animate-pulse-glow">
                    </div>

                    {{-- Scanline Effect --}}
                    <div
                        class="absolute inset-0 bg-[linear-gradient(to_bottom,transparent_50%,rgba(0,0,0,0.3)_50%)] bg-[length:100%_4px] opacity-10 pointer-events-none">
                    </div>

                    {{-- Globe Wireframe Decoration --}}
                    <div
                        class="absolute top-[-150px] left-[-150px] w-[500px] h-[500px] md:w-[800px] md:h-[800px] opacity-40 z-0 pointer-events-none">
                        @include('templates.bento.blades.partials.globe-wireframe')
                    </div>
                </div>

                <div class="container mx-auto px-4 relative z-10 flex flex-col items-center justify-center pt-20">
                    {{-- Glass Breadcrumb Pill --}}
                    <nav
                        class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-white/[0.03] backdrop-blur-md border border-white/10 mb-8 animate-slideDown shadow-2xl shadow-black/20 group hover:bg-white/[0.05] transition-colors">
                        <a href="{{ route('home') }}"
                            class="text-xs font-bold text-text-secondary uppercase tracking-wider hover:text-accent-primary transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <polyline points="9 22 9 12 15 12 15 22" />
                            </svg>
                            {{ __('Home') }}
                        </a>
                        <span class="text-white/20">/</span>
                        <span class="text-xs font-bold text-white uppercase tracking-wider text-shadow-sm">
                            @yield('page_title', __($page_title) ?? __('Page'))
                        </span>
                    </nav>

                    {{-- Main Title --}}
                    <h1
                        class="text-5xl md:text-7xl font-bold mb-6 font-heading tracking-tight text-center leading-tight">
                        <span
                            class="inline-block bg-clip-text text-transparent bg-gradient-to-b from-white via-white to-white/40 drop-shadow-sm">
                            @yield('page_title', __($page_title) ?? __('Current Page'))
                        </span>
                        <span class="text-accent-primary animate-pulse">.</span>
                    </h1>

                    {{-- Decorative Line --}}
                    <div
                        class="w-32 h-1 bg-gradient-to-r from-transparent via-accent-primary to-transparent rounded-full opacity-50 blur-[1px]">
                    </div>
                </div>

                {{-- Fade to Content --}}
                <div
                    class="absolute bottom-0 inset-x-0 h-32 bg-gradient-to-t from-[#0B0F1A] to-transparent pointer-events-none">
                </div>
                <div
                    class="absolute bottom-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent">
                </div>
            </section>
        @endif

        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-[#050505] pt-20 pb-10 border-t border-white/5">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-start gap-12 mb-16">
                {{-- Brand --}}
                <div class="max-w-xs">
                    <a href="/" class="flex items-center gap-2 mb-6">
                        @if (getSetting('logo_rectangle'))
                            <img loading="lazy" decoding="async" src="{{ asset('assets/images/' . getSetting('logo_rectangle')) }}"
                                alt="{{ getSetting('name') }}" class="h-8 w-auto">
                        @else
                            <h1 class="font-heading text-2xl font-bold text-white tracking-tight">
                                {{ getSetting('name') }}<span class="text-accent-primary">.</span>
                            </h1>
                        @endif
                    </a>
                    <p class="text-text-secondary text-sm leading-relaxed">
                        {{ __('The next generation of wealth management. Secure, transparent, and built for everyone.') }}
                    </p>
                </div>

                {{-- Links --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 md:gap-16">
                    @foreach (config('menus.footer_nav') as $column)
                        @if (!$column['is_active'])
                            @continue
                        @endif
                        <div class="@if (count($column['items']) > 5) col-span-2 @endif">
                            <h4 class="font-bold text-white mb-4">{{ __($column['name']) }}</h4>
                            <ul
                                class="@if (count($column['items']) > 5) grid grid-cols-2 gap-4 @else space-y-2 @endif text-sm text-text-secondary">
                                @foreach ($column['items'] as $item)
                                    @if (!$item['is_active'])
                                        @continue
                                    @endif
                                    @php
                                        $href = '#';
                                        if ($item['route_name'] && Route::has($item['route_name'])) {
                                            $href = route($item['route_name']);
                                        }
                                    @endphp
                                    <li>
                                        <a href="{{ $href }}"
                                            class="hover:text-white transition-colors">{{ __($item['name']) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-text-secondary opacity-60">
                    &copy; {{ date('Y') }} {{ getSetting('name') }}. {{ __('All rights reserved.') }}
                </p>
                <div class="flex items-center gap-6">
                    <a href="{{ Route::has('privacy-policy') ? route('privacy-policy') : '#' }}"
                        class="text-xs text-text-secondary hover:text-white transition-colors">{{ __('Privacy') }}</a>
                    <a href="{{ Route::has('terms-and-conditions') ? route('terms-and-conditions') : '#' }}"
                        class="text-xs text-text-secondary hover:text-white transition-colors">{{ __('Terms') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @if (config('site.use_vite'))
        @vite(['resources/views/templates/bento/js/app.js'])
    @else
        <script
            src="{{ asset('assets/templates/bento/js/main.js' . '?v=' . filemtime(public_path('assets/templates/bento/js/main.js'))) }}">
        </script>
    @endif

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (menu.classList.contains('translate-x-full')) {
                menu.classList.remove('translate-x-full');
                document.body.style.overflow = 'hidden';
            } else {
                menu.classList.add('translate-x-full');
                document.body.style.overflow = '';
            }
        };

        {{-- Language Switcher Toggle --}}
        $('#lang-switcher-btn').on('click', function(e) {
            e.stopPropagation();
            $('#lang-dropdown').toggleClass('opacity-100 visible').toggleClass('opacity-0 invisible');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#lang-dropdown').length) {
                $('#lang-dropdown').addClass('opacity-0 invisible').removeClass('opacity-100 visible');
            }
        });

        {{-- Custom Mouse Cursor Effect --}}
        const dot = document.getElementById('custom-cursor-dot');
        const ring = document.getElementById('custom-cursor-ring');
        const label = document.getElementById('custom-cursor-label');

        let mouseX = 0,
            mouseY = 0;
        let dotX = 0,
            dotY = 0;
        let ringX = 0,
            ringY = 0;

        if (window.innerWidth > 1024) {
            dot.style.display = 'block';
            ring.style.display = 'block';
            label.style.display = 'block';

            document.addEventListener('mousemove', function(e) {
                mouseX = e.clientX;
                mouseY = e.clientY;
            });

            function animateCursor() {
                dotX += (mouseX - dotX) * 0.2;
                dotY += (mouseY - dotY) * 0.2;
                ringX += (mouseX - ringX) * 0.15;
                ringY += (mouseY - ringY) * 0.15;

                dot.style.left = dotX + 'px';
                dot.style.top = dotY + 'px';
                ring.style.left = ringX + 'px';
                ring.style.top = ringY + 'px';
                label.style.left = ringX + 'px';
                label.style.top = ringY + 'px';

                requestAnimationFrame(animateCursor);
            }
            animateCursor();

            const interactables = 'a, button, [onclick], .card-hover-effect';
            $(document).on('mouseenter', interactables, function() {
                $('body').addClass('cursor-hover');
                const cursorLabel = $(this).data('cursor-label');
                if (cursorLabel) {
                    label.innerText = cursorLabel;
                    $('body').addClass('cursor-has-label');
                }
            }).on('mouseleave', interactables, function() {
                $('body').removeClass('cursor-hover cursor-has-label');
            });

            $(document).on('mousedown', function() {
                ring.style.transform = 'translate(-50%, -50%) scale(0.8)';
            }).on('mouseup', function() {
                ring.style.transform = 'translate(-50%, -50%) scale(1)';
            });
        }

        {{-- Navbar Scroll Effect --}}
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 100) {
                $('#navbar').addClass('navbar-fixed');
            } else {
                $('#navbar').removeClass('navbar-fixed');
            }
        });
    </script>

    @include('templates.bento.blades.partials.cookie-consent')
    @stack('scripts')

    {!! getSetting('livechat_scripts') !!}
    {!! getSetting('footer_scripts') !!}

</body>

</html>
