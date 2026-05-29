<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ config('languages.' . app()->getLocale() . '.rtl') ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $page_title ?? 'Admin' }} | {{ getSetting('name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/' . getSetting('favicon')) }}">

    {{-- indexing --}}
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

    {{-- custom scrollbar design --}}
    <style>
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.2);
            border-radius: 10px;
            transition: background 0.3s ease;
            cursor: pointer;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.5);
        }

        /* Firefox support */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(139, 92, 246, 0.2) transparent;
        }

        /* Global Cursor Pointers */
        button,
        a,
        label,
        select,
        .cursor-pointer,
        .dropdown-btn,
        .dropdown-option,
        .modal-close {
            cursor: pointer !important;
        }
    </style>

    @stack('css')
</head>

<body
    class="bg-primary-dark text-text-primary font-body antialiased min-h-screen flex selection:bg-accent-primary selection:text-white overflow-hidden">

    {{-- Admin Preloader --}}
    @include('templates.bento.blades.partials.dashoard-preloader')

    <!-- Sidebar -->
    <aside
        class="hidden lg:flex flex-col w-72 bg-secondary-dark border-r border-white/5 h-screen sticky top-0 z-30 transition-all duration-300">
        <!-- Logo Area -->
        <div class="h-20 flex items-center px-8 border-b border-white/5 bg-secondary-dark/50 backdrop-blur-xl">
            <a href="{{ route('admin.dashboard') }}" class="group block w-full">
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
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-8 px-4 space-y-1 scrollbar-hide">

            <div class="mb-8">
                <p class="px-4 text-xs font-bold text-text-secondary uppercase tracking-widest mb-4 opacity-70">
                    {{ __('Admin Menu') }}
                </p>
                @foreach ($admin_menu_items as $item)
                    @if ($item->children->isNotEmpty())
                        {{-- Menu item with sub-menu --}}
                        @php
                            $isActive =
                                ($item->route_name && request()->routeIs($item->route_name)) ||
                                ($item->route_wildcard && request()->routeIs($item->route_wildcard)) ||
                                ($item->url && request()->url() == $item->url) ||
                                $item->children->contains(function ($child) {
                                    $cActive =
                                        ($child->route_name && request()->routeIs($child->route_name)) ||
                                        ($child->route_wildcard && request()->routeIs($child->route_wildcard)) ||
                                        ($child->route_name && request()->routeIs($child->route_name . '*'));

                                    if ($cActive && $child->params && is_array($child->params)) {
                                        foreach ($child->params as $k => $v) {
                                            if (request()->query($k) != $v) {
                                                return false;
                                            }
                                        }
                                    }
                                    return $cActive;
                                });
                        @endphp
                        <div class="mb-1 submenu-container relative"
                            data-expanded="{{ $isActive ? 'true' : 'false' }}">
                            <button
                                class="submenu-toggle cursor-pointer w-full flex items-center justify-between px-4 py-3.5 rounded-xl text-sm font-medium transition-all duration-300 group z-10 relative
                                {{ $isActive ? 'text-white bg-white/5 shadow-inner' : 'text-text-secondary hover:text-white hover:bg-white/5' }}">
                                <div class="flex items-center gap-3">
                                    {!! $item->icon !!}
                                    <span>{{ __($item->label) }}</span>
                                </div>
                                <svg class="submenu-arrow w-4 h-4 transition-transform duration-200 {{ $isActive ? 'rotate-180' : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            {{-- Connector Line --}}
                            <div
                                class="absolute left-[2.2rem] top-10 bottom-4 w-[1px] bg-white/10 submenu-line {{ $isActive ? '' : 'hidden' }}">
                            </div>

                            <div class="submenu-content pl-12 pr-2 space-y-1 mt-1 {{ $isActive ? '' : 'hidden' }}"
                                style="{{ $isActive ? 'display: block;' : 'display: none;' }}">
                                @foreach ($item->children as $child)
                                    @php
                                        $isChildActive =
                                            ($child->route_name && request()->routeIs($child->route_name)) ||
                                            ($child->route_name && request()->routeIs($child->route_name . '*'));

                                        if ($isChildActive && $child->params && is_array($child->params)) {
                                            foreach ($child->params as $key => $value) {
                                                if (request()->query($key) != $value) {
                                                    $isChildActive = false;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <a href="{{ $child->link }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 group/child relative
                                        {{ $isChildActive ? 'text-white font-medium bg-white/5' : 'text-text-secondary hover:text-white hover:bg-white/5' }}">

                                        {{-- Dot Indicator --}}
                                        <span
                                            class="w-1.5 h-1.5 rounded-full ring-2 ring-secondary-dark transition-all duration-300
                                            {{ $isChildActive ? 'bg-accent-primary scale-110 shadow-[0_0_8px_rgba(var(--color-accent-primary),0.6)]' : 'bg-white/20 group-hover/child:bg-white/50' }}"></span>

                                        <span>{{ __($child->label) }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Single menu item --}}
                        @php
                            $isSingleActive =
                                ($item->route_name && request()->routeIs($item->route_name)) ||
                                ($item->route_wildcard && request()->routeIs($item->route_wildcard)) ||
                                request()->url() == $item->url;
                            if ($isSingleActive && $item->params && is_array($item->params)) {
                                foreach ($item->params as $key => $value) {
                                    if (request()->query($key) != $value) {
                                        $isSingleActive = false;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <a href="{{ $item->link }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm font-medium transition-all duration-300 group [&>svg]:transition-transform [&>svg]:group-hover:scale-110 {{ $isSingleActive ? 'bg-accent-primary text-white shadow-lg shadow-accent-primary/20' : 'text-text-secondary hover:text-white hover:bg-white/5' }}">
                            {!! $item->icon !!}
                            <span>{{ __($item->label) }}</span>
                        </a>
                    @endif
                @endforeach
            </div>

        </nav>

        <!-- Admin Profile Footer -->
        <div class="p-4 border-t border-white/5 bg-secondary-dark/30">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5">

                @if (Auth::guard('admin')->user()->image)
                    <div
                        class="h-10 w-10 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white font-bold text-lg shadow-inner">
                        <img src="{{ asset('storage/profile/' . Auth::guard('admin')->user()->image) }}"
                            alt="{{ Auth::guard('admin')->user()->name }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div
                        class="h-10 w-10 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white font-bold text-lg shadow-inner">
                        {{ substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">
                        {{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-text-secondary truncate">
                        {{ Auth::guard('admin')->user()->email ?? 'admin@example.com' }}</p>
                </div>

                <a href="{{ route('admin.account.profile') }}"
                    class="p-2 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors group/settings"
                    title="{{ __('Account Settings') }}">
                    <svg class="w-5 h-5 transition-transform group-hover/settings:rotate-90" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden bg-primary-dark relative">

        @php
            $locale = app()->getLocale();
            $languages = config('languages');
            $currentLang = $languages[$locale] ?? ['name' => $locale, 'flag' => 'us'];
        @endphp

        <!-- Mobile Header -->
        <header
            class="lg:hidden h-16 flex items-center justify-between px-4 bg-secondary-dark border-b border-white/5 z-20 gap-3">
            <!-- Logo -->
            <a href="{{ route('admin.dashboard') }}" class="block shrink-0">
                @if (getSetting('logo_square'))
                    <img loading="lazy" decoding="async" src="{{ asset('assets/images/' . getSetting('logo_square')) }}"
                        alt="{{ getSetting('name') }}" class="h-8 w-auto">
                @else
                    <h1 class="font-heading text-xl font-bold text-white">
                        {{ substr(getSetting('name'), 0, 1) }}<span class="text-accent-primary">.</span>
                    </h1>
                @endif
            </a>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">

                <!-- Language Switcher -->
                <div class="relative">
                    <button onclick="toggleDropdown('mobile-header-lang-menu')"
                        class="flex items-center justify-center w-8 h-8 rounded-full bg-white/5 border border-white/10 hover:border-accent-primary/30 transition-colors cursor-pointer">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/flags/' . $currentLang['flag'] . '.svg') }}"
                            class="w-4 h-4 rounded-full object-cover">
                    </button>
                    <div id="mobile-header-lang-menu"
                        class="hidden absolute top-full right-0 mt-2 w-40 bg-secondary-dark/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl overflow-hidden z-50">
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

                <!-- Profile -->
                <div class="relative">
                    <button onclick="toggleDropdown('mobile-header-profile-menu')"
                        class="w-8 h-8 rounded-full overflow-hidden bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white font-bold text-xs shadow-inner ring-2 ring-transparent hover:ring-white/10 transition-all">
                        @if (Auth::guard('admin')->user()->image)
                            <img src="{{ asset('storage/profile/' . Auth::guard('admin')->user()->image) }}"
                                alt="{{ Auth::guard('admin')->user()->name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1) }}
                        @endif
                    </button>
                    <div id="mobile-header-profile-menu"
                        class="hidden absolute top-full right-0 mt-2 w-48 bg-secondary-dark/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl overflow-hidden z-50">
                        <div class="p-1">
                            <div class="px-3 py-2 border-b border-white/5 mb-1">
                                <p class="text-sm font-bold text-white truncate">
                                    {{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-text-secondary truncate">
                                    {{ Auth::guard('admin')->user()->email ?? 'admin@example.com' }}</p>
                            </div>

                            <a href="{{ route('admin.account.profile') }}"
                                class="flex items-center gap-3 px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                {{ __('My Profile') }}
                            </a>
                            <a href="{{ route('admin.account.security') }}"
                                class="flex items-center gap-3 px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/10 rounded-lg transition-colors mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2"
                                        ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                                {{ __('Security') }}
                            </a>

                            <form action="{{ route('admin.logout') }}" method="POST" class="ajax-form"
                                data-action="redirect" data-redirect="{{ route('admin.login') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-accent-error hover:bg-accent-error/10 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                        <polyline points="16 17 21 12 16 7" />
                                        <line x1="21" x2="9" y1="12" y2="12" />
                                    </svg>
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Hamburger -->
                <button id="mobile-menu-toggle"
                    class="p-1.5 text-text-secondary hover:text-white transition-colors bg-white/5 rounded-lg border border-white/10">
                    <svg class="icon" viewBox="0 0 32 32" width="20" height="20">
                        <rect x="6" y="9" width="20" height="2" rx="1" fill="currentColor" />
                        <rect x="10" y="15" width="16" height="2" rx="1" fill="currentColor" />
                        <rect x="6" y="21" width="12" height="2" rx="1" fill="currentColor" />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Top Bar (Desktop) -->
        <header
            class="hidden lg:flex h-20 items-center justify-between px-8 border-b border-white/5 bg-primary-dark/80 backdrop-blur-md z-20">
            <div>
                <h2 class="text-2xl font-bold text-white font-heading tracking-tight">
                    {{ $page_title ?? __('Dashboard') }}
                </h2>
                <p class="text-sm text-text-secondary mt-1">
                    {{ __('Management Console') }}
                </p>
            </div>

            <div class="flex items-center gap-6">
                <!-- Language Switcher -->
                <div class="relative group">
                    <button
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

                    <div
                        class="absolute right-0 top-full mt-2 w-40 bg-secondary-dark/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right overflow-hidden z-50">
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

                <!-- Admin Profile Dropdown -->
                <div class="relative group">
                    <button
                        class="flex items-center gap-3 pl-3 pr-2 py-1.5 rounded-full border border-white/10 hover:bg-white/5 bg-white/5 transition-all duration-300 group-hover:border-accent-primary/30">
                        <div class="flex flex-col items-end mr-1 hidden sm:flex">
                            <span
                                class="text-xs font-bold text-white leading-tight">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                            <span
                                class="text-[10px] text-text-secondary leading-tight">{{ __('Administrator') }}</span>
                        </div>
                        <div
                            class="h-8 w-8 rounded-full overflow-hidden bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white font-bold text-xs shadow-inner">
                            @if (Auth::guard('admin')->user()->image)
                                <img src="{{ asset('storage/profile/' . Auth::guard('admin')->user()->image) }}"
                                    alt="{{ Auth::guard('admin')->user()->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1) }}
                            @endif
                        </div>
                        <svg class="w-3 h-3 text-text-secondary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div
                        class="absolute right-0 top-full mt-2 w-48 bg-secondary-dark/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right overflow-hidden z-50">
                        <div class="p-1">
                            <div class="px-3 py-2 border-b border-white/5 mb-1">
                                <p class="text-sm font-bold text-white truncate">
                                    {{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-text-secondary truncate">
                                    {{ Auth::guard('admin')->user()->email ?? 'admin@example.com' }}</p>
                            </div>

                            <a href="{{ route('admin.account.profile') }}"
                                class="flex items-center gap-3 px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                {{ __('My Profile') }}
                            </a>
                            <a href="{{ route('admin.account.security') }}"
                                class="flex items-center gap-3 px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/10 rounded-lg transition-colors mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2"
                                        ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                                {{ __('Security') }}
                            </a>

                            <form action="{{ route('admin.logout') }}" method="POST" class="ajax-form"
                                data-action="redirect" data-redirect="{{ route('admin.login') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-3 py-2 text-sm text-accent-error hover:bg-accent-error/10 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                        <polyline points="16 17 21 12 16 7" />
                                        <line x1="21" x2="9" y1="12" y2="12" />
                                    </svg>
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- background elements --}}
        <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-violet-500/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Main Content Area -->
        <main
            class="flex-1 overflow-y-auto p-4 lg:p-8 scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">
            <div class="max-w-7xl mx-auto w-full min-h-full flex flex-col">
                <div class="space-y-8 flex-1 pb-10">
                    @yield('content')
                </div>

                <!-- Copyright Footer -->
                <div class="pt-8 border-t border-white/5 text-center mt-auto">
                    <p class="text-xs text-text-secondary opacity-60">
                        &copy; {{ date('Y') }} {{ getSetting('name') }}. {{ __('Admin Panel') }}
                    </p>
                </div>
            </div>
        </main>

    </div>

    <!-- Mobile Sidebar Overlay & Menu -->
    <div id="mobile-menu-overlay"
        class="fixed inset-0 bg-black/80 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity opacity-0"></div>

    <div id="mobile-sidebar"
        class="fixed inset-y-0 left-0 w-72 bg-secondary-dark z-50 transform -translate-x-full transition-transform duration-300 lg:hidden flex flex-col border-r border-white/10">
        <div class="h-16 flex items-center justify-between px-6 border-b border-white/5">
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
            <button id="mobile-menu-close" class="text-text-secondary hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            @foreach ($admin_menu_items as $item)
                @if ($item->children->isNotEmpty())
                    {{-- Mobile Menu item with sub-menu --}}
                    @php
                        $isActive =
                            request()->routeIs($item->route_name) ||
                            ($item->url && request()->url() == $item->url) ||
                            $item->children->contains(
                                fn($child) => ($child->route_name && request()->routeIs($child->route_name)) ||
                                    ($child->route_wildcard && request()->routeIs($child->route_wildcard)) ||
                                    ($child->route_name && request()->routeIs($child->route_name . '*')),
                            );
                    @endphp
                    <div class="mb-1 submenu-container relative" data-expanded="{{ $isActive ? 'true' : 'false' }}">
                        <button
                            class="submenu-toggle flex w-full items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-colors z-10 relative
                            {{ $isActive ? 'text-white bg-white/5 shadow-inner' : 'text-text-secondary hover:text-white hover:bg-white/5' }}">
                            <div class="flex items-center gap-3">
                                {!! $item->icon !!}
                                <span>{{ __($item->label) }}</span>
                            </div>
                            <svg class="submenu-arrow w-4 h-4 transition-transform duration-200 {{ $isActive ? 'rotate-180' : '' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        {{-- Connector Line --}}
                        <div
                            class="absolute left-[2.2rem] top-10 bottom-4 w-[1px] bg-white/10 submenu-line {{ $isActive ? '' : 'hidden' }}">
                        </div>

                        <div class="submenu-content pl-12 pr-2 space-y-1 mt-1 {{ $isActive ? '' : 'hidden' }}"
                            style="{{ $isActive ? 'display: block;' : 'display: none;' }}">
                            @foreach ($item->children as $child)
                                @php
                                    $isChildActive =
                                        ($child->route_name && request()->routeIs($child->route_name)) ||
                                        ($child->route_wildcard && request()->routeIs($child->route_wildcard)) ||
                                        ($child->route_name && request()->routeIs($child->route_name . '*'));
                                @endphp
                                <a href="{{ $child->link }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 group/child
                                    {{ $isChildActive ? 'text-white font-medium bg-white/5' : 'text-text-secondary hover:text-white hover:bg-white/5' }}">

                                    {{-- Dot Indicator --}}
                                    <span
                                        class="w-1.5 h-1.5 rounded-full ring-2 ring-secondary-dark transition-all duration-300
                                        {{ $isChildActive ? 'bg-accent-primary scale-110 shadow-[0_0_8px_rgba(var(--color-accent-primary),0.6)]' : 'bg-white/20 group-hover/child:bg-white/50' }}"></span>

                                    <span>{{ __($child->label) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- Mobile Single menu item --}}
                    <a href="{{ $item->link }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium mb-1 {{ ($item->route_name && request()->routeIs($item->route_name)) || request()->url() == $item->url ? 'bg-accent-primary text-white' : 'text-text-secondary hover:text-white hover:bg-white/5' }}">
                        {!! $item->icon !!}
                        <span>{{ __($item->label) }}</span>
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="p-4 border-t border-white/5">
            <form action="{{ route('admin.logout') }}" method="POST" class="ajax-form" data-action="redirect"
                data-redirect="{{ route('admin.login') }}">
                @csrf
                <button type="submit"
                    class="flex w-full items-center justify-center gap-2 px-4 py-3 rounded-xl bg-white/5 text-white font-medium hover:bg-accent-primary/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" x2="9" y1="12" y2="12" />
                    </svg>
                    <span>{{ __('Logout') }}</span>
                </button>
            </form>
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
        $(document).ready(function() {
            // Mobile Menu Toggle
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileOverlay = document.getElementById('mobile-menu-overlay');
            const mobileMenuClose = document.getElementById('mobile-menu-close');

            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    mobileOverlay.classList.remove('hidden', 'opacity-0');
                    mobileOverlay.classList.add('opacity-100');
                    mobileSidebar.classList.remove('-translate-x-full');
                });
            }

            function closeMobileMenu() {
                if (mobileOverlay) {
                    mobileOverlay.classList.remove('opacity-100');
                    mobileOverlay.classList.add('opacity-0');
                    setTimeout(() => {
                        mobileOverlay.classList.add('hidden');
                    }, 300);
                }
                if (mobileSidebar) {
                    mobileSidebar.classList.add('-translate-x-full');
                }
            }

            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', closeMobileMenu);
            }
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', closeMobileMenu);
            }

            // Sidebar Submenu Toggle
            $('.submenu-toggle').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                var $container = $this.closest('.submenu-container');
                var $content = $container.find('.submenu-content');
                var $arrow = $this.find('.submenu-arrow');
                var isExpanded = $container.attr('data-expanded') === 'true';

                if (isExpanded) {
                    $content.slideUp(200, function() {
                        $(this).addClass('hidden');
                    });
                    $container.find('.submenu-line').addClass('hidden');
                    $arrow.removeClass('rotate-180');
                    $container.attr('data-expanded', 'false');
                } else {
                    $content.removeClass('hidden').slideDown(200);
                    $container.find('.submenu-line').removeClass('hidden');
                    $arrow.addClass('rotate-180');
                    $container.attr('data-expanded', 'true');
                }
            });
        });

        function toggleDropdown(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.toggle('hidden');
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = ['mobile-header-lang-menu', 'mobile-header-profile-menu'];
            dropdowns.forEach(id => {
                const el = document.getElementById(id);
                const btn = el ? el.previousElementSibling : null;
                if (el && !el.classList.contains('hidden') && !el.contains(event.target) && !btn.contains(
                        event.target)) {
                    el.classList.add('hidden');
                }
            });
        });
    </script>

    {{-- Page Specific Scripts --}}
    @yield('scripts')

    @stack('scripts')

</body>

</html>
