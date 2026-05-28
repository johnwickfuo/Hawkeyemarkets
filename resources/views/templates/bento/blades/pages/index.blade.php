@extends('templates.bento.blades.layouts.front')

@section('title', getSetting('name') . ' | The Financial Ecosystem')

@php
    // Mock Data for Home Page
    $assets = [
        [
            'title' => __('Stocks'),
            'desc' => __('Ownership in high-growth companies.'),
            'module_loaded' => moduleEnabled('stock_module'),
            'icon' =>
                '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>',
        ],
        [
            'title' => __('ETFs'),
            'desc' => __('Diversified baskets for stable growth.'),
            'module_loaded' => moduleEnabled('etf_module'),
            'icon' =>
                '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>',
        ],
        [
            'title' => __('Crypto Futures'),
            'desc' => __('Perpetual contracts with up to 100x leverage.'),
            'module_loaded' => moduleEnabled('futures_module'),
            'icon' =>
                '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>',
        ],
        [
            'title' => __('Forex'),
            'desc' => __('Major, Minor, and Exotic pairs with tight spreads.'),
            'module_loaded' => moduleEnabled('forex_module'),
            'icon' =>
                '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>',
        ],
    ];

    $standoutFeatures = [
        [
            'id' => 'fixed-return',
            'title' => __('Fixed Return Investment'),
            'headline' => __('Guaranteed Returns, Institutional Stability.'),
            'description' => __(
                'Pick from any of our core investment plans. We allocate capital across diverse, low-volatility sectors to yield consistent, fixed returns.',
            ),
            'details' => [
                __('Diverse portfolio allocation'),
                __('Automated monthly yields'),
                __('Risk-mitigated strategies'),
                __('Insurance-backed capital'),
            ],
            'icon' =>
                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            'cta_label' => __('View Plans'),
            'cta_url' => '#invest',
            'image' => 'assets/templates/bento/images/features/fixed-return-investment.png',
        ],
        [
            'id' => 'crypto-futures',
            'title' => __('Crypto Trading'),
            'headline' => __('Next-Gen Perpetual Futures.'),
            'description' => __(
                'Trade the worlds most liquid crypto assets with up to 100x leverage. Professional charting tools and ultra-fast execution.',
            ),
            'details' => [
                __('100x Leverage available'),
                __('0.01% Taker fees'),
                __('Deep liquidity pools'),
                __('Advanced order types (TP/SL)'),
            ],
            'icon' =>
                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
            'image' => 'assets/templates/bento/images/features/crypto-trading.png',
        ],
        [
            'id' => 'forex',
            'title' => __('Forex Trading'),
            'headline' => __('The Global Currency Hub.'),
            'description' => __(
                'Access Major, Minor, and Exotic currency pairs with institution-grade spreads and lightning-fast execution speeds.',
            ),
            'details' => [
                __('Raw spreads starting at 0.0 pips'),
                __('50+ Currency pairs'),
                __('24/5 Live markets'),
                __('Tier-1 liquidity providers'),
            ],
            'icon' =>
                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>',
            'image' => 'assets/templates/bento/images/features/forex-trading.png',
        ],
        [
            'id' => 'stocks',
            'title' => __('Global Stocks'),
            'headline' => __('Own the Worlds Leaders.'),
            'description' => __(
                'Direct access to NYSE, NASDAQ, and LSE. Purchase fractional shares in high-growth companies like Apple, Tesla, and Amazon.',
            ),
            'details' => [
                __('Fractional shares (buy $1 info)'),
                __('Direct dividend distribution'),
                __('Extended trading hours'),
                __('Instant settlement'),
            ],
            'icon' =>
                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>',
            'image' => 'assets/templates/bento/images/features/global-stocks.png',
        ],
        [
            'id' => 'bonds',
            'title' => __('Sovereign Bonds'),
            'headline' => __('Safe Haven Assets.'),
            'description' => __(
                'Invest in government and corporate debt securities. Ideal for wealth preservation and steady long-term income.',
            ),
            'details' => [
                __('Secure sovereign debt'),
                __('Fixed periodic interest'),
                __('Liquidity on secondary markets'),
                __('Tax-efficient structures'),
            ],
            'icon' =>
                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>',
            'image' => 'assets/templates/bento/images/features/sovereign-bonds.png',
        ],
        [
            'id' => 'etfs',
            'title' => __('Diversified ETFs'),
            'headline' => __('The Power of Indexation.'),
            'description' => __(
                'Gain exposure to entire sectors or indices with a single trade. Low-cost, diversified, and highly liquid.',
            ),
            'details' => [
                __('Technology, Energy, & Finance ETFs'),
                __('Automatic rebalancing'),
                __('Broad market exposure'),
                __('Single-click diversification'),
            ],
            'icon' =>
                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>',
            'image' => 'assets/templates/bento/images/features/diversified-etfs.png',
        ],
        [
            'id' => 'margin',
            'title' => __('Margin Trading'),
            'headline' => __('Amplify Your Potential.'),
            'description' => __(
                'Maximize your buying power with flexible margin terms. Trade larger positions with a fraction of the capital.',
            ),
            'details' => [
                __('Cross and Isolated margin'),
                __('Low interest rates'),
                __('Real-time collateral monitoring'),
                __('Flexible liquidation buffers'),
            ],
            'icon' =>
                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            'image' => 'assets/templates/bento/images/features/margin-trading.png',
        ],
        [
            'id' => 'commodities',
            'title' => __('Hard Commodities'),
            'headline' => __('Tangible Wealth.'),
            'description' => __(
                'Trade Gold, Silver, Crude Oil, and Natural Gas. Protect your portfolio against inflation with essential global resources.',
            ),
            'details' => [
                __('Spot & Futures contracts'),
                __('Real-time global pricing'),
                __('Inflation hedge'),
                __('24-hour liquidity'),
            ],
            'icon' =>
                '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.99 7.99 0 0120 13c0 2.21-.895 4.21-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>',
            'image' => 'assets/templates/bento/images/features/hard-commodities.png',
        ],
    ];
@endphp

@section('content')
    {{-- Global Noise/Grain Overlay --}}
    <div
        class="fixed inset-0 pointer-events-none z-[9999] opacity-[0.035] bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')] mix-blend-overlay">
    </div>

    {{-- HERO SECTION --}}
    <section id="home" class="relative min-h-[90vh] flex items-center justify-center overflow-hidden">
        {{-- Animated Background Elements --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none hidden lg:block">
            @include('templates.bento.blades.partials.light-stream-effect')
        </div>
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            {{-- Background Decorative Mockups --}}
            @if (!empty($mockups) && count($mockups) >= 2)
                @php
                    $bgMockups = collect($mockups)->random(2)->all();
                @endphp
                {{-- Middle Left Mockup --}}
                <div
                    class="absolute top-1/2 -left-[450px] -translate-y-1/2 w-[600px] opacity-[0.05] pointer-events-none transform -rotate-12 blur-[2px] hidden lg:block">
                    <img src="{{ $bgMockups[0] }}" class="w-full h-auto rounded-3xl border border-white/20 shadow-2xl">
                </div>
                {{-- Middle Right Mockup --}}
                <div
                    class="absolute top-1/2 -right-[450px] -translate-y-1/2 w-[600px] opacity-[0.05] pointer-events-none transform rotate-12 blur-[2px] hidden lg:block">
                    <img src="{{ $bgMockups[1] }}" class="w-full h-auto rounded-3xl border border-white/20 shadow-2xl">
                </div>
            @endif

            {{-- Large Texture Text --}}
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full text-center z-0 opacity-[0.03] select-none pointer-events-none">
                <span
                    class="text-[20vw] lg:text-[15vw] font-bold text-white tracking-widest leading-none block font-heading">{{ \Str::limit(getSetting('name'), 6) }}</span>
            </div>

            {{-- Web3 Background Glows --}}
            <div
                class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-purple-600/20 rounded-full blur-[100px] animate-deep-pulse">
            </div>
            <div class="absolute top-[20%] right-[-5%] w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[120px] animate-deep-pulse"
                style="animation-delay: 2s;"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-[600px] h-[600px] bg-accent-primary/10 rounded-full blur-[120px] animate-deep-pulse"
                style="animation-delay: 4s;"></div>
            <div class="absolute bottom-[10%] right-[10%] w-64 h-64 bg-emerald-500/10 rounded-full blur-[80px] animate-deep-pulse"
                style="animation-delay: 1s;"></div>

            {{-- Floating Orbs (Foreground) --}}
            <div
                class="absolute top-[15%] left-[10%] w-32 h-32 bg-accent-primary/20 rounded-full blur-[60px] animate-pulse-glow">
            </div>
            <div class="absolute bottom-[20%] right-[10%] w-40 h-40 bg-accent-secondary/20 rounded-full blur-[80px] animate-pulse-glow"
                style="animation-delay: 1s;"></div>

            {{-- Floating Icons/Badges --}}
            <div
                class="absolute top-[10%] right-[5%] lg:top-[20%] lg:right-[15%] p-2 lg:p-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md animate-float scale-75 lg:scale-100 z-0">
                <div class="flex items-center gap-2">
                    <div
                        class="w-6 h-6 lg:w-8 lg:h-8 rounded-full bg-orange-500/20 flex items-center justify-center text-orange-500">
                        <img loading="lazy" decoding="async" src="{{ 'https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/' . $btc['logo'] }}"
                            alt="BTC" class="w-4 h-4 lg:w-5 lg:h-5">
                    </div>
                    <div>
                        <div class="text-[8px] lg:text-[10px] text-text-secondary">BTC/USDT</div>
                        @php
                            $btc_change_percent = $btc['change_1d_percentage'] ?? 0;
                        @endphp
                        <div
                            class="text-[10px] lg:text-xs font-bold {{ $btc_change_percent > 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $btc_change_percent > 0 ? '+' : '' }}{{ number_format($btc_change_percent, 2) }}%
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="absolute bottom-[20%] left-[5%] lg:bottom-[30%] lg:left-[8%] p-2 lg:p-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md animate-float-delayed scale-75 lg:scale-100 z-0">
                <div class="flex items-center gap-2">
                    <div
                        class="w-6 h-6 lg:w-8 lg:h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-500">
                        <img src="{{ $aapl['public_png_logo_url'] ?? '' }}" alt="AAPL" class="w-4 h-4 lg:w-5 lg:h-5">
                    </div>
                    <div>
                        <div class="text-[8px] lg:text-[10px] text-text-secondary">AAPL</div>
                        @php
                            $aapl_change_percent = $aapl['change_1d_percentage'] ?? 0;
                        @endphp
                        <div
                            class="text-[10px] lg:text-xs font-bold {{ $aapl_change_percent > 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $aapl_change_percent > 0 ? '+' : '' }}{{ number_format($aapl_change_percent, 2) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10 pt-36">
            <div class="text-center max-w-4xl mx-auto mb-12">
                <a href="#invest"
                    class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-white/5 border border-white/10 text-xs font-bold text-accent-primary mb-8 hover:bg-white/10 transition-colors group cursor-pointer">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-accent-primary"></span>
                    </span>
                    {{ __('The All-In-One Financial Ecosystem') }}
                    <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <h1 class="text-5xl md:text-7xl font-bold text-white tracking-tight leading-tight mb-6">
                    {{ __('One Platform.') }} <br>
                    <span
                        class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-accent-primary via-white to-accent-secondary typing-cursor"
                        id="typing-text">{{ __('Every Market') }}</span>
                </h1>

                <p class="text-xl text-text-secondary max-w-2xl mx-auto leading-relaxed mb-10">
                    {{ __('From safe government bonds to high-leverage crypto futures. Master the markets with institutional-grade tools and securities.') }}
                </p>

                {{-- Stats Grid --}}
                <div
                    class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 border-y border-white/5 py-6 mb-12 bg-white/[0.02]">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-white">150+</div>
                        <div class="text-xs text-text-secondary uppercase tracking-wider">{{ __('Countries') }}</div>
                    </div>
                    <div class="text-center border-l border-white/5">
                        <div class="text-2xl font-bold text-white">$10B+</div>
                        <div class="text-xs text-text-secondary uppercase tracking-wider">{{ __('Volume') }}</div>
                    </div>
                    <div class="text-center border-l border-white/5">
                        <div class="text-2xl font-bold text-white">0%</div>
                        <div class="text-xs text-text-secondary uppercase tracking-wider">{{ __('Commission') }}</div>
                    </div>
                    <div class="text-center border-l border-white/5">
                        <div class="text-2xl font-bold text-white">24/7</div>
                        <div class="text-xs text-text-secondary uppercase tracking-wider">{{ __('Support') }}</div>
                    </div>
                </div>
            </div>

            {{-- Dual CTA --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-20 animate-fade-in-up">
                @if (moduleEnabled('investment_module'))
                    <a href="{{ route('user.register') }}" data-cursor-label="{{ __('Join Now') }}"
                        class="btn-hover-effect w-full sm:w-auto px-8 py-4 bg-white text-primary-dark rounded-full font-bold text-lg hover:bg-gray-100 transition-all shadow-xl shadow-white/5 hover:shadow-white/20 hover:-translate-y-1 flex items-center justify-center gap-2">
                        <span>{{ __('Start Investing') }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6">
                            </path>
                        </svg>
                    </a>
                @endif

                @php
                    $trading_modules = [];
                    moduleEnabled('futures_module') ? ($trading_modules[] = 'futures') : null;
                    moduleEnabled('margin_module') ? ($trading_modules[] = 'margin') : null;
                    moduleEnabled('forex_module') ? ($trading_modules[] = 'forex') : null;
                @endphp

                @if (!empty($trading_modules))
                    <a href="{{ route('trading.' . $trading_modules[0]) }}" data-cursor-label="{{ __('Try Terminal') }}"
                        class="btn-hover-effect w-full sm:w-auto px-8 py-4 bg-white/5 border border-white/10 text-white rounded-full font-bold text-lg hover:bg-white/10 transition-all backdrop-blur-md flex items-center justify-center gap-2 group hover:-translate-y-1">
                        <span>{{ __('Pro Terminal') }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </a>
                @endif
            </div>

            {{-- Mockup Visual --}}
            <div class="relative max-w-5xl mx-auto perspective-1000 group">
                <div
                    class="absolute inset-0 bg-accent-primary/20 blur-3xl -z-10 group-hover:bg-accent-primary/30 transition-all duration-700">
                </div>
                <div
                    class="w-full rounded-2xl border border-white/20 bg-[#0B0F1A] shadow-2xl transform rotateX-12 scale-95 group-hover:rotateX-0 group-hover:scale-100 transition-all duration-1000 ease-out overflow-hidden">
                    {{-- Browser/Mac Top Bar --}}
                    <div class="h-10 bg-white/5 border-b border-white/10 px-4 flex items-center justify-between">
                        <div class="flex gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></div>
                        </div>
                        <div class="hidden sm:flex bg-white/5 px-6 py-1 rounded-full border border-white/5">
                            <span
                                class="text-[10px] text-text-secondary font-mono tracking-widest opacity-50 uppercase">{{ url('terminal') }}</span>
                        </div>
                        <div class="flex gap-2">
                            <div class="w-3 h-3 text-white/20">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div id="mockup-slider" class="flex transition-transform duration-1000 cubic-bezier(0.4, 0, 0.2, 1)">
                        @if (!empty($mockups))
                            @foreach ($mockups as $mockup)
                                <img src="{{ $mockup }}" class="w-full h-auto shrink-0" alt="Dashboard Interface">
                            @endforeach
                        @else
                            <img loading="lazy" decoding="async" src="{{ asset('assets/templates/bento/images/dashboard-mockup-dark.png') }}"
                                class="w-full h-auto shrink-0" alt="Dashboard Interface"
                                onerror="this.src='https://placehold.co/1200x800/1e293b/FFFFFF?text=Lozand+Dashboard+Interface'">
                        @endif
                    </div>
                </div>

                {{-- Floating Badges --}}
                <div
                    class="absolute -right-4 top-1/4 lg:-right-8 lg:top-1/3 bg-secondary-dark border border-white/10 p-3 lg:p-4 rounded-xl shadow-xl flex items-center gap-3 animate-float scale-90 lg:scale-100 z-20">
                    <div
                        class="w-8 h-8 lg:w-10 lg:h-10 rounded-full bg-green-500/20 flex items-center justify-center text-green-500">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="hidden sm:block">
                        <div class="text-xs text-text-secondary">{{ __('Securities Verified') }}</div>
                        <div class="text-sm font-bold text-white">{{ __('SEC Filed') }}</div>
                    </div>
                </div>

                <div
                    class="absolute -left-4 bottom-1/4 lg:-left-8 lg:bottom-1/3 bg-secondary-dark border border-white/10 p-3 lg:p-4 rounded-xl shadow-xl flex items-center gap-3 animate-float-delayed scale-90 lg:scale-100 z-20">
                    <div
                        class="w-8 h-8 lg:w-10 lg:h-10 rounded-full bg-accent-primary/20 flex items-center justify-center text-accent-primary">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="hidden sm:block">
                        <div class="text-xs text-text-secondary">{{ __('Total Assets') }}</div>
                        <div class="text-sm font-bold text-white">$2.4B+</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MARKET TICKER --}}
    @if (!empty($marketStats) && count($marketStats) > 0)
        <div class="bg-black/20 border-y border-white/5 backdrop-blur-sm relative overflow-hidden h-14 flex items-center">
            <div class="ticker-wrap-reverse flex items-center gap-8 whitespace-nowrap min-w-full">
                @for ($i = 0; $i < 4; $i++)
                    @foreach ($marketStats as $stat)
                        @php
                            $changePct = (float) ($stat['change_1d_percentage'] ?? 0);
                            $isUp = $changePct >= 0;
                            $changeColor = $isUp ? 'text-green-400' : 'text-red-400';
                        @endphp
                        <div class="flex items-center gap-3">
                            @if (!empty($stat['public_png_logo_url']))
                                <img loading="lazy" decoding="async" src="{{ $stat['public_png_logo_url'] }}"
                                    class="w-5 h-5 rounded-full object-contain bg-white/10 p-0.5"
                                    onerror="this.style.display='none'">
                            @endif
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-white">{{ $stat['ticker'] ?? '???' }}</span>
                                <span
                                    class="text-sm {{ $changeColor }}">${{ number_format($stat['current_price'] ?? 0, 2) }}</span>
                                <span
                                    class="text-xs {{ $changeColor }} bg-white/5 px-1.5 py-0.5 rounded">{{ $isUp ? '+' : '' }}{{ number_format($changePct, 2) }}%</span>
                            </div>
                        </div>
                        <div class="w-px h-4 bg-white/10 mx-2"></div>
                    @endforeach
                @endfor
            </div>
        </div>
    @endif

    <div class="bg-black/40 border-b border-white/5 backdrop-blur-md relative overflow-hidden flex items-center">
        <div class="tradingview-widget-container" id="crypto-ticker-container">
            <div class="tradingview-widget-container__widget"></div>
        </div>
    </div>


    {{-- Feature Explorer Section --}}
    <section id="features" class="py-24 relative overflow-hidden bg-[#050505]">
        {{-- Dynamic Background Elements --}}
        <div class="absolute inset-0 z-0 pointer-events-none">
            {{-- Animated Grid --}}
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:40px_40px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)]">
            </div>

            {{-- Floating Animated Orbs --}}
            <div
                class="absolute top-[10%] left-[5%] w-96 h-96 bg-accent-primary/10 rounded-full blur-[120px] animate-pulse-glow">
            </div>
            <div class="absolute bottom-[10%] right-[5%] w-[500px] h-[500px] bg-accent-secondary/5 rounded-full blur-[150px] animate-pulse-glow"
                style="animation-delay: 2s;"></div>

            {{-- Scrolling Data Streams --}}
            <div class="absolute inset-0 opacity-20">
                <div class="data-stream-container w-full h-full relative">
                    @for ($i = 0; $i < 20; $i++)
                        <div class="data-column absolute h-32 w-[1px] bg-gradient-to-b from-transparent via-accent-primary to-transparent animate-data-flow shadow-[0_0_12px_#3b82f6]"
                            style="animation-delay: {{ $i * 0.4 }}s; animation-duration: {{ 3 + ($i % 5) }}s; left: {{ $i * 5 }}%;">
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Moving Light Beam --}}
            <div
                class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-accent-primary/40 to-transparent animate-scan-line">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span
                    class="text-accent-primary font-bold uppercase tracking-widest text-sm block mb-2">{{ __('Advanced Capabilities') }}</span>
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">{{ __('Why and how we stand out') }}</h2>
                <p class="text-text-secondary text-lg">
                    {{ __('Access a universe of financial opportunities through a single, secure gateway designed for both retail and institutional traders.') }}
                </p>
            </div>

            <div class="flex flex-col lg:flex-row gap-12" id="feature-explorer">
                {{-- Left: Feature Titles (30%) --}}
                <div class="w-full lg:w-[35%] flex flex-col gap-3 sticky top-24 self-start z-30" role="tablist">
                    @foreach ($standoutFeatures as $index => $feature)
                        <button
                            class="feature-tab w-full flex items-center gap-4 p-4 lg:p-5 rounded-2xl border transition-all duration-300 group text-left {{ $index === 0 ? 'active border-accent-primary bg-white/10' : 'border-white/5 bg-white/5 hover:bg-white/10 hover:border-white/10' }}"
                            data-feature-id="{{ $feature['id'] }}" id="tab-{{ $feature['id'] }}" role="tab"
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-controls="panel-{{ $feature['id'] }}" tabindex="0">
                            <div
                                class="feature-icon-box w-10 h-10 lg:w-12 lg:h-12 rounded-xl flex items-center justify-center transition-all duration-300 {{ $index === 0 ? 'bg-accent-primary text-white shadow-lg shadow-accent-primary/20' : 'bg-white/5 text-text-secondary group-hover:text-white group-hover:bg-white/10' }}">
                                {!! $feature['icon'] !!}
                            </div>
                            <div>
                                <h3
                                    class="font-bold {{ $index === 0 ? 'text-white' : 'text-text-secondary group-hover:text-white' }} transition-colors">
                                    {{ $feature['title'] }}</h3>
                                <p
                                    class="text-[10px] uppercase tracking-wider text-accent-primary font-bold opacity-0 lg:group-[.active]:opacity-100 transition-opacity hidden lg:block">
                                    {{ __('Active Now') }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>

                {{-- Right: Feature Details (70%) --}}
                <div class="w-full lg:w-[65%] flex flex-col gap-8 lg:block relative lg:min-h-[450px]">
                    @foreach ($standoutFeatures as $index => $feature)
                        <div class="feature-panel bg-secondary-dark/50 border border-white/10 rounded-[2.5rem] p-8 lg:p-12 backdrop-blur-xl relative lg:absolute lg:inset-0 transition-all duration-500 flex flex-col lg:flex-row gap-12 scroll-mt-32 {{ $index === 0 ? 'opacity-100 scale-100 translate-x-0 z-20' : 'lg:opacity-0 lg:scale-95 lg:translate-x-12 lg:z-10 lg:pointer-events-none' }}"
                            id="panel-{{ $feature['id'] }}" role="tabpanel" aria-labelledby="tab-{{ $feature['id'] }}">
                            <div class="flex-1">
                                <h2 class="text-accent-primary font-bold uppercase tracking-widest text-sm mb-4">
                                    {{ $feature['title'] }}</h2>
                                <h3 class="text-3xl lg:text-4xl font-bold text-white mb-6 leading-tight">
                                    {{ $feature['headline'] }}</h3>
                                <p class="text-text-secondary text-lg mb-8 leading-relaxed">{{ $feature['description'] }}
                                </p>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
                                    @foreach ($feature['details'] as $detail)
                                        <div class="flex items-center gap-3 text-white/80">
                                            <div
                                                class="w-5 h-5 rounded-full bg-accent-primary/20 flex items-center justify-center text-accent-primary">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium">{{ $detail }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex items-center gap-4">
                                    <a href="{{ $feature['cta_url'] ?? route('user.register') }}"
                                        class="px-8 py-3.5 bg-accent-primary text-white rounded-xl font-bold hover:bg-accent-primary/90 transition-all shadow-lg shadow-accent-primary/20 hover:-translate-y-1">
                                        {{ $feature['cta_label'] ?? __('Start Trading') }}
                                    </a>
                                    <button
                                        class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="hidden lg:flex flex-1 items-center justify-center">
                                <div
                                    class="relative w-full aspect-square rounded-3xl overflow-hidden border border-white/10 group/media">
                                    <img loading="lazy" decoding="async" src="{{ asset($feature['image']) }}" alt="{{ $feature['title'] }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover/media:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    <div
                                        class="absolute bottom-6 left-6 right-6 p-4 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-accent-primary/20 flex items-center justify-center text-accent-primary animate-pulse">
                                                {!! $feature['icon'] !!}
                                            </div>
                                            <div>
                                                <div class="text-[10px] uppercase tracking-widest text-text-secondary">
                                                    {{ __('Live Terminal') }}</div>
                                                <div class="text-sm font-bold text-white">{{ __('Real-time Data') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>




    {{-- MARKET MOMENTUM (SCREENER) --}}
    <section id="momentum" class="py-24 relative overflow-hidden bg-[#050505]">
        {{-- Background Accents --}}
        <div class="absolute top-0 right-10 w-[500px] h-[500px] bg-accent-primary/[0.03] rounded-full blur-[120px] -z-10">
        </div>
        <div
            class="absolute bottom-10 left-10 w-[400px] h-[400px] bg-purple-500/[0.02] rounded-full blur-[100px] -z-10 animate-pulse-glow">
        </div>

        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row justify-between items-end gap-8 mb-16">
                <div class="max-w-2xl">
                    <span
                        class="text-accent-primary font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Real-Time Velocity') }}</span>
                    <h2 class="text-4xl md:text-5xl font-black text-white leading-tight">
                        {{ __('Market') }} <br>
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">{{ __('Momentum Track.') }}</span>
                    </h2>
                </div>
                <p class="text-text-secondary text-lg max-w-sm lg:text-right">
                    {{ __('Identify high-volatility opportunities across 5,000+ assets with our live institutional screener.') }}
                </p>
            </div>

            <div
                class="p-8 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 relative overflow-hidden group h-[600px] flex flex-col">
                <div class="flex-1 w-full rounded-2xl overflow-hidden bg-white/[0.02] border border-white/5">
                    {{-- TradingView Screener Widget --}}
                    <div class="tradingview-widget-container h-full">
                        <div class="tradingview-widget-container__widget h-full" id="tv-screener"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (moduleEnabled('investment_module'))
        {{-- INVESTMENT PLANS --}}
        <section id="invest" class="py-24 relative overflow-hidden">
            {{-- Decorative background --}}
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-accent-primary/[0.02] -z-10 blur-[120px]">
            </div>
            <div
                class="absolute top-1/2 left-0 w-64 h-64 bg-accent-primary/5 rounded-full blur-[80px] -z-10 animate-deep-pulse">
            </div>
            <div
                class="absolute bottom-0 right-0 w-96 h-96 bg-accent-secondary/5 rounded-full blur-[100px] -z-10 animate-pulse-glow">
            </div>

            {{-- Subtle Grid Overlay --}}
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-[0.02] -z-10">
            </div>

            <div class="container mx-auto px-4 relative">
                <div class="flex flex-col md:flex-row items-end justify-between mb-16 gap-6">
                    <div class="max-w-2xl">
                        <span
                            class="text-accent-primary font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Institutional Portfolios') }}</span>
                        <h2 class="text-4xl md:text-6xl font-black text-white leading-none tracking-tight">
                            {{ __('Managed') }} <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">{{ __('Intelligence.') }}</span>
                        </h2>
                    </div>
                    <div class="md:text-right">
                        <p class="text-text-secondary max-w-sm ml-auto text-sm lg:text-base leading-relaxed">
                            {{ __('Access professionally managed investment strategies. We handle the complexity, you secure the returns.') }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-6 lg:grid-cols-12 gap-6">
                    @foreach ($investmentPlans->take(6) as $index => $plan)
                        @php
                            $isFeatured = $plan->is_featured;
                            $risk = strtolower($plan->risk_profile);
                            $colorClass = match ($risk) {
                                'growth', 'high', 'aggressive' => 'accent-primary',
                                'balanced', 'medium' => 'amber-500',
                                default => 'emerald-500',
                            };
                            // Grid layout logic for Bento feel
                            $gridSpan = match ($index) {
                                0 => 'md:col-span-6 lg:col-span-8', // Large Main Card
                                1 => 'md:col-span-3 lg:col-span-4', // Medium Side Card
                                2 => 'md:col-span-3 lg:col-span-4', // Small Card
                                3 => 'md:col-span-3 lg:col-span-4', // Small Card
                                4 => 'md:col-span-3 lg:col-span-4', // Small Card
                                default => 'md:col-span-6 lg:col-span-12', // Wide Footer Card
                            };
                        @endphp

                        <div data-plan-id="{{ $plan->id }}" data-cursor-label="View Strategy"
                            class="group relative bg-[#0B0F17]/50 border border-white/5 rounded-[2rem] overflow-hidden transition-all duration-500 hover:border-{{ $colorClass }}/30 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.5)] {{ $gridSpan }} flex flex-col p-8 lg:p-10">

                            {{-- Background Accent Glow --}}
                            <div
                                class="absolute -top-24 -right-24 w-64 h-64 bg-{{ $colorClass }}/5 rounded-full blur-[80px] group-hover:bg-{{ $colorClass }}/10 transition-colors">
                            </div>

                            <div class="relative z-10 flex flex-col h-full">
                                {{-- Header Section --}}
                                <div class="flex justify-between items-start mb-8">
                                    <div class="flex gap-4 items-center">
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center group-hover:scale-110 group-hover:bg-white/10 group-hover:border-white/20 transition-all duration-500">
                                            {{-- Dynamic Icon based on Risk --}}
                                            @if ($risk == 'growth' || $risk == 'high' || $risk == 'aggressive')
                                                <svg class="w-7 h-7 text-accent-primary" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                </svg>
                                            @elseif($risk == 'balanced' || $risk == 'medium')
                                                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                                                    </path>
                                                </svg>
                                            @else
                                                <svg class="w-7 h-7 text-emerald-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h3
                                                class="text-xl lg:text-2xl font-bold text-white group-hover:text-accent-primary transition-colors">
                                                {{ __($plan->name) }}</h3>
                                            <div class="flex gap-2 mt-1">
                                                <span
                                                    class="text-[9px] font-bold uppercase tracking-widest px-2 py-0.5 rounded border border-{{ $colorClass }}/20 bg-{{ $colorClass }}/5 text-{{ $colorClass }}">{{ __($risk) }}</span>
                                                @if ($plan->compounding)
                                                    <span
                                                        class="text-[9px] font-bold uppercase tracking-widest px-2 py-0.5 rounded border border-white/5 bg-white/5 text-text-secondary">{{ __('Compounding') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-3xl font-black text-white">{{ $plan->return_percent }}%</div>
                                        <div class="text-[10px] font-bold text-text-secondary uppercase tracking-tighter">
                                            {{ __('ROI / ') . __($plan->duration) }}{{ __($plan->duration_type) }}</div>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <p
                                    class="text-text-secondary text-sm lg:text-base leading-relaxed mb-10 {{ $index === 0 ? 'max-w-xl' : 'line-clamp-2' }}">
                                    {{ __($plan->description) }}
                                </p>

                                {{-- Metrics Hub --}}
                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-auto">
                                    <div
                                        class="bg-white/5 border border-white/5 rounded-2xl p-4 flex flex-col justify-center">
                                        <span
                                            class="text-[9px] font-bold text-text-secondary uppercase tracking-widest mb-1">{{ __('Duration') }}</span>
                                        <span class="text-white font-bold">{{ $plan->duration }}
                                            {{ __($plan->duration_type) }}</span>
                                    </div>
                                    <div
                                        class="bg-white/5 border border-white/5 rounded-2xl p-4 flex flex-col justify-center">
                                        <span
                                            class="text-[9px] font-bold text-text-secondary uppercase tracking-widest mb-1">{{ __('Return') }}</span>
                                        <span
                                            class="text-white font-bold">{{ __(ucfirst($plan->return_interval)) }}</span>
                                    </div>
                                    <div
                                        class="bg-white/5 border border-white/5 rounded-2xl p-4 flex flex-col justify-center">
                                        <span
                                            class="text-[9px] font-bold text-text-secondary uppercase tracking-widest mb-1">{{ __('Min Entry') }}</span>
                                        <span
                                            class="text-emerald-400 font-bold font-mono">{{ getSetting('currency_symbol', '$') }}{{ number_format($plan->min_investment) }}</span>
                                    </div>
                                    <div
                                        class="bg-white/5 border border-white/5 rounded-2xl p-4 flex flex-col justify-between items-start">
                                        <span
                                            class="text-[9px] font-bold text-text-secondary uppercase tracking-widest">{{ __('Capital') }}</span>
                                        <div class="flex items-center gap-1.5">
                                            <div
                                                class="w-1.5 h-1.5 rounded-full {{ $plan->capital_returned ? 'bg-emerald-400 animate-pulse' : 'bg-white/20' }}">
                                            </div>
                                            <span
                                                class="text-[10px] font-bold text-white uppercase">{{ $plan->capital_returned ? __('Returned') : __('Not Returned') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Hover Action --}}
                                <div
                                    class="mt-8 flex items-center justify-between opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-4 group-hover:translate-y-0">
                                    <a href="{{ route('user.register') }}" class="flex items-center gap-2 group/btn">
                                        <span class="text-sm font-bold text-white">{{ __('Start Investment') }}</span>
                                        <svg class="w-4 h-4 text-accent-primary group-hover/btn:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                    <span
                                        class="text-[10px] font-mono text-text-secondary/40">{{ __('SEC v4.2 COMPLIANT') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Secondary CTA --}}
                <div class="mt-16 text-center">
                    <a href="{{ route('user.register') }}" class="inline-flex items-center gap-4 group">
                        <span
                            class="text-text-secondary font-bold hover:text-white transition-colors">{{ __('Explore all professional portfolios') }}</span>
                        <div
                            class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-accent-primary group-hover:bg-accent-primary transition-all">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
        </section>
        {{-- ROI CALCULATOR --}}
        <section id="calculator" class="py-24 relative overflow-hidden">
            {{-- Decorative Elements --}}
            <div
                class="absolute top-1/2 left-0 -translate-y-1/2 w-[600px] h-[600px] bg-accent-primary/5 rounded-full blur-[120px] pointer-events-none">
            </div>
            <div
                class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-accent-secondary/[0.03] rounded-full blur-[120px] pointer-events-none">
            </div>

            <div class="container mx-auto px-4">
                <div
                    class="bg-[#0B0F17]/60 border border-white/5 rounded-[2.5rem] md:rounded-[3rem] p-6 md:p-12 lg:p-16 backdrop-blur-xl relative overflow-hidden">
                    {{-- Background Grid --}}
                    <div
                        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-[0.03] pointer-events-none">
                    </div>

                    <div class="grid lg:grid-cols-2 gap-16 items-center relative z-10">
                        {{-- Left: Text Content --}}
                        <div>
                            <span
                                class="text-accent-primary font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Precision Forecasting') }}</span>
                            <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-6">
                                {{ __('Project Your') }} <br>
                                <span
                                    class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">{{ __('Financial Growth.') }}</span>
                            </h2>
                            <p class="text-text-secondary text-lg mb-10 max-w-md">
                                {{ __('Calculate your potential earnings in seconds. Select a strategy and visualize your institutional-grade returns.') }}
                            </p>

                            <div class="space-y-6">
                                <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/5 border border-white/5">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-accent-primary/20 flex items-center justify-center text-accent-primary">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white mb-1">{{ __('Compound Optimized') }}</h4>
                                        <p class="text-[11px] text-text-secondary">
                                            {{ __('Our calculator accounts for strategy-specific compounding intervals.') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/5 border border-white/5">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white mb-1">{{ __('Capital Security') }}</h4>
                                        <p class="text-[11px] text-text-secondary">
                                            {{ __('All projections are based on current verified asset performance.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right: Calculator UI --}}
                        <div
                            class="bg-secondary-dark/80 rounded-[2rem] md:rounded-[2.5rem] border border-white/10 p-6 md:p-8 lg:p-10 shadow-2xl relative overflow-hidden group/calc">
                            {{-- Top Accent --}}
                            <div
                                class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-primary to-accent-secondary opacity-50">
                            </div>

                            <div class="space-y-8">
                                {{-- Select Plan --}}
                                <div>
                                    <label
                                        class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-3">{{ __('Select Strategy') }}</label>
                                    <div class="relative group/dropdown" id="roi-plan-dropdown">
                                        <button type="button" id="roi-dropdown-trigger"
                                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 md:px-6 py-3 md:py-4 text-sm md:text-base text-white font-bold flex items-center justify-between hover:border-accent-primary/50 transition-all focus:ring-1 focus:ring-accent-primary outline-none cursor-pointer">
                                            <span id="selected-plan-name">{{ __('Select a Plan') }}</span>
                                            <svg class="w-4 h-4 text-text-secondary transition-transform duration-300"
                                                id="dropdown-arrow" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>

                                        <div id="roi-dropdown-menu"
                                            class="absolute top-full left-0 w-full mt-2 bg-[#0B0F17] border border-white/10 rounded-2xl shadow-2xl opacity-0 invisible translate-y-2 transition-all duration-300 z-50 overflow-hidden backdrop-blur-xl">
                                            <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                                @foreach ($investmentPlans as $plan)
                                                    <div class="roi-option group/opt px-6 py-4 text-white font-bold hover:bg-accent-primary/10 cursor-pointer transition-all border-b border-white/[0.03] last:border-0"
                                                        data-id="{{ $plan->id }}"
                                                        data-min="{{ $plan->min_investment }}"
                                                        data-max="{{ $plan->max_investment }}"
                                                        data-percent="{{ $plan->return_percent }}"
                                                        data-duration="{{ $plan->duration }}"
                                                        data-duration-type="{{ $plan->duration_type }}"
                                                        data-interval="{{ $plan->return_interval }}"
                                                        data-capital="{{ $plan->capital_returned ? 1 : 0 }}"
                                                        data-name="{{ $plan->name }} ({{ $plan->return_percent }}%)">
                                                        <div class="flex justify-between items-center">
                                                            <div class="flex flex-col">
                                                                <span class="text-sm">{{ __($plan->name) }}</span>
                                                                <span
                                                                    class="text-[9px] text-text-secondary uppercase tracking-widest font-medium">{{ __(ucfirst($plan->return_interval)) }}</span>
                                                            </div>
                                                            <div class="text-right">
                                                                <div class="text-sm text-accent-primary">
                                                                    {{ $plan->return_percent }}%</div>
                                                                <div class="text-[8px] text-text-secondary uppercase">
                                                                    {{ __('ROI') }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <input type="hidden" id="roi-plan-id" value="">
                                    </div>
                                </div>

                                {{-- Amount Input --}}
                                <div>
                                    <div class="flex justify-between items-end mb-3">
                                        <label
                                            class="text-[10px] font-bold text-text-secondary uppercase tracking-widest">{{ __('Investment Amount') }}</label>
                                        <div class="text-[9px] md:text-[10px] font-mono text-accent-primary font-bold text-right"
                                            id="min-max-label">
                                            <span class="block sm:inline">{{ __('Min:') }} <span
                                                    id="min-amount-display">0</span></span>
                                            <span class="hidden sm:inline mx-1">•</span>
                                            <span class="block sm:inline">{{ __('Max:') }} <span
                                                    id="max-amount-display">0</span></span>
                                        </div>
                                    </div>
                                    <div class="relative mb-6">
                                        <div
                                            class="absolute left-6 top-1/2 -translate-y-1/2 text-text-secondary font-mono">
                                            {{ getSetting('currency_symbol', '$') }}</div>
                                        <input type="number" id="roi-amount-input"
                                            class="w-full bg-white/5 border border-white/10 rounded-2xl pl-10 md:pl-12 pr-6 py-4 md:py-5 text-xl md:text-2xl font-black text-white focus:border-accent-primary outline-none transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                    </div>
                                    <input type="range" id="roi-amount-slider"
                                        class="w-full h-1.5 bg-white/5 rounded-lg appearance-none cursor-pointer accent-accent-primary">
                                </div>

                                {{-- Results Grid --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="p-6 rounded-3xl bg-white/[0.03] border border-white/5">
                                        <div
                                            class="text-[9px] font-bold text-text-secondary uppercase tracking-[0.2em] mb-2">
                                            {{ __('Total Profit') }}</div>
                                        <div class="text-2xl font-black text-emerald-400 font-mono"
                                            id="total-profit-display">
                                            {{ getSetting('currency_symbol', '$') }}0.00</div>
                                    </div>
                                    <div class="p-6 rounded-3xl bg-white/[0.03] border border-white/5">
                                        <div
                                            class="text-[9px] font-bold text-text-secondary uppercase tracking-[0.2em] mb-2">
                                            {{ __('Net Payout') }}</div>
                                        <div class="text-2xl font-black text-white font-mono" id="net-payout-display">
                                            {{ getSetting('currency_symbol', '$') }}0.00</div>
                                    </div>
                                </div>

                                {{-- Timeline Info --}}
                                <div
                                    class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-3 px-6 py-4 rounded-2xl bg-accent-primary/5 border border-accent-primary/10">
                                    <div class="w-full sm:flex-1">
                                        <div
                                            class="text-[8px] font-black text-accent-primary uppercase tracking-[0.25em] mb-1 italic">
                                            {{ __('Simulation Maturity') }}</div>
                                        <div class="text-white text-xs font-bold" id="maturity-date-display">-</div>
                                    </div>
                                    <div
                                        class="w-full sm:text-right border-t border-accent-primary/10 sm:border-0 pt-3 sm:pt-0">
                                        <div
                                            class="text-[8px] font-black text-accent-primary uppercase tracking-[0.25em] mb-1 italic">
                                            {{ __('Duration') }}</div>
                                        <div class="text-white text-xs font-bold" id="duration-display">-</div>
                                    </div>
                                </div>

                                <button
                                    class="w-full bg-gradient-to-r from-accent-primary to-accent-secondary py-5 rounded-2xl text-white font-black uppercase tracking-widest text-sm hover:shadow-[0_0_30px_rgba(59,130,246,0.3)] transition-all transform hover:-translate-y-1 active:scale-95">
                                    {{ __('Commit to Strategy') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @endif


    {{-- PRO TERMINAL MOCKUP --}}
    <section id="trade" class="py-24 relative overflow-hidden">
        {{-- Atmospheric Background --}}
        <div
            class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[120%] h-full bg-accent-secondary/[0.01] -z-10 blur-[150px] animate-pulse-glow">
        </div>
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
            style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
        </div>

        <div class="container mx-auto px-4">
            <div
                class="bg-secondary-dark/30 border border-white/5 rounded-[3rem] p-8 lg:p-16 backdrop-blur-xl relative group">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
                </div>

                <div class="grid lg:grid-cols-2 gap-16 items-center relative z-10">
                    <div>
                        <span
                            class="text-accent-secondary font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Advanced Trading') }}</span>
                        <h2 class="text-4xl md:text-6xl font-black text-white leading-tight mb-8">
                            {{ __('The World is') }} <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-accent-primary to-accent-secondary">{{ __('Your Terminal.') }}</span>
                        </h2>
                        <p class="text-lg text-text-secondary mb-10 leading-relaxed max-w-md">
                            {{ __('Direct access to high-liquidity markets. Trade Stocks, Forex, and Crypto Futures with up to 100x leverage on our institutional matching engine.') }}
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4">
                            @if (moduleEnabled('futures_module'))
                                <a href="{{ route('trading.futures') }}"
                                    class="px-8 py-4 bg-accent-primary text-white rounded-2xl font-bold flex items-center justify-center gap-3 hover:bg-accent-primary/90 transition-all shadow-lg shadow-accent-primary/20">
                                    {{ __('Launch Terminal - Futures') }}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </a>
                            @endif
                            @if (moduleEnabled('forex_module'))
                                <a href="{{ route('trading.forex') }}"
                                    class="px-8 py-4 bg-white/5 border border-white/10 text-white rounded-2xl font-bold flex items-center justify-center gap-3 hover:bg-white/10 transition-all">
                                    {{ __('Launch Terminal - Forex') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="relative">
                        {{-- Terminal UI Element --}}
                        <div
                            class="relative rounded-2xl border border-white/10 bg-[#0B0F19] p-2 shadow-2xl group/terminal overflow-hidden transition-all duration-700 hover:scale-[1.02]">
                            {{-- Browser Top Bar --}}
                            <div
                                class="flex items-center justify-between px-4 py-3 border-b border-white/5 bg-white/5 rounded-t-xl">
                                <div class="flex items-center gap-3">
                                    <div class="flex gap-1.5">
                                        <div class="w-3 h-3 rounded-full bg-rose-500/30 border border-rose-500/20"></div>
                                        <div class="w-3 h-3 rounded-full bg-amber-500/30 border border-amber-500/20"></div>
                                        <div class="w-3 h-3 rounded-full bg-emerald-500/30 border border-emerald-500/20">
                                        </div>
                                    </div>
                                    <span
                                        class="text-[10px] font-mono text-text-secondary opacity-50 tracking-widest uppercase">{{ getSetting('name') }}-Terminal-v2.exe</span>
                                </div>
                                <div class="text-[10px] font-bold text-emerald-400 flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                    {{ __('LIVE NODE') }}
                                </div>
                            </div>

                            {{-- Visual Trading Content --}}
                            <div class="aspect-video bg-[#05070A] relative overflow-hidden p-6 flex flex-col">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-xs text-text-secondary uppercase tracking-widest font-bold mb-1">
                                            BTC / USDT</div>
                                        <div class="text-3xl font-black text-white font-mono tracking-tighter">
                                            @php
                                                $btc_current_price = $btc['current_price'] ?? 0;
                                                $btc_change = $btc['change_1d'] ?? 0;
                                                $btc_change_percent = $btc['change_1d_percent'] ?? 0;

                                            @endphp
                                            {{ number_format($btc_current_price, 2) }}
                                        </div>
                                        <div
                                            class="text-sm font-mono {{ $btc_change_percent >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                            {{ $btc_change_percent >= 0 ? '+' : '' }}{{ number_format($btc_change_percent, 2) }}%
                                            (${{ $btc_change >= 0 ? '+' : '' }}{{ number_format($btc_change, 2) }})
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <div
                                            class="px-3 py-1 bg-white/5 rounded-lg text-[10px] border border-white/10 font-bold text-white">
                                            1H</div>
                                        <div
                                            class="px-3 py-1 bg-accent-primary/20 rounded-lg text-[10px] border border-accent-primary/20 font-bold text-accent-primary">
                                            1D</div>
                                    </div>
                                </div>

                                {{-- Fake Chart Lines --}}
                                <div
                                    class="mt-auto h-32 flex items-end gap-[2px] opacity-40 group-hover/terminal:opacity-60 transition-opacity">
                                    @foreach (range(1, 40) as $i)
                                        <div class="flex-1 bg-gradient-to-t {{ $i % 3 == 0 ? 'from-rose-500/50 to-rose-400' : 'from-emerald-500/50 to-emerald-400' }} rounded-t-sm"
                                            style="height: {{ rand(20, 90) }}%"></div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Live Technical Analysis Widget (acting as depth/sentiment) --}}
                            <div class="h-[120px] bg-white/[0.02] border-t border-white/5 relative overflow-hidden">
                                <div class="tradingview-widget-container h-full">
                                    <div class="tradingview-widget-container__widget h-full" id="tv-technical-analysis">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Floating Badges --}}
                        <div
                            class="absolute -top-6 -right-6 lg:-right-12 bg-secondary-dark border border-white/10 p-4 rounded-2xl shadow-2xl animate-float z-20">
                            <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mb-1">
                                {{ __('Trading Vol') }}</div>
                            @php
                                function shortNumber($num)
                                {
                                    $num = (float) $num;

                                    if ($num >= 1_000_000_000_000) {
                                        return round($num / 1_000_000_000_000, 1) . 'T';
                                    }

                                    if ($num >= 1_000_000_000) {
                                        return round($num / 1_000_000_000, 1) . 'B';
                                    }

                                    if ($num >= 1_000_000) {
                                        return round($num / 1_000_000, 1) . 'M';
                                    }

                                    if ($num >= 1_000) {
                                        return round($num / 1_000, 1) . 'K';
                                    }

                                    return number_format($num, 0);
                                }

                                $btc_volume = shortNumber($btc['quote_volume'] ?? 0);
                            @endphp
                            <div class="text-xl font-black text-white">${{ $btc_volume }}+</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MARKET SURVEILLANCE --}}
    <section id="surveillance" class="py-24 relative overflow-hidden bg-black/40">
        {{-- Tech Background Elements --}}
        <div
            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-accent-primary/30 to-transparent animate-scan-line">
        </div>
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff02_1px,transparent_1px),linear-gradient(to_bottom,#ffffff02_1px,transparent_1px)] bg-[size:50px_50px]">
        </div>

        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row justify-between items-end gap-8 mb-16">
                <div class="max-w-2xl">
                    <span
                        class="text-accent-primary font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Global Surveillance') }}</span>
                    <h2 class="text-4xl md:text-5xl font-black text-white leading-tight">
                        {{ __('Cross-Exchange') }} <br>
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">{{ __('Capital Flow.') }}</span>
                    </h2>
                </div>
                <div
                    class="flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 mb-4 lg:mb-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span
                        class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">{{ __('Live Terminal Sync') }}</span>
                </div>
            </div>

            <div
                class="p-8 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 relative overflow-hidden group min-h-[600px] flex flex-col">
                <div
                    class="flex-1 w-full rounded-2xl overflow-hidden bg-white/[0.02] border border-white/5 relative group-hover:border-accent-primary/20 transition-colors">
                    {{-- TradingView Market Overview Widget --}}
                    <div class="tradingview-widget-container h-full">
                        <div class="tradingview-widget-container__widget h-full" id="tv-market-overview"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MARKET ASSETS --}}
    <section id="markets" class="py-24 relative overflow-hidden">
        {{-- Ambient Orbs --}}
        <div
            class="absolute top-1/4 left-0 w-64 h-64 bg-accent-primary/5 rounded-full blur-[100px] -z-10 animate-pulse-glow">
        </div>
        <div class="absolute bottom-1/4 right-0 w-80 h-80 bg-accent-secondary/[0.03] rounded-full blur-[120px] -z-10 animate-pulse-glow"
            style="animation-delay: 2s;"></div>

        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span
                    class="text-accent-primary font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Global Coverage') }}</span>
                <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-6">{{ __('Diversify Across') }}
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-accent-primary to-accent-secondary">{{ __('Every Asset.') }}</span>
                </h2>
                <p class="text-text-secondary text-lg leading-relaxed">
                    {{ __('From high-yield corporate bonds to the world\'s most liquid stocks and crypto assets. Build your ultimate portfolio.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($assets as $asset)
                    @if ($asset['module_loaded'])
                        <div data-cursor-label="Explore Market"
                            class="group p-8 rounded-3xl bg-[#0B0F17]/40 border border-white/5 hover:border-accent-primary/50 transition-all duration-500 hover:-translate-y-2 flex flex-col h-full bg-gradient-to-b from-transparent to-white/[0.02]">
                            <div
                                class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-8 text-white group-hover:bg-accent-primary group-hover:text-white transition-all duration-500 rotate-3 group-hover:rotate-0 shadow-lg group-hover:shadow-accent-primary/20">
                                {!! $asset['icon'] !!}
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-4">{{ __($asset['title']) }}</h3>
                            <p class="text-text-secondary text-sm leading-relaxed mb-8 flex-grow">
                                {{ __($asset['desc']) }}
                            </p>

                            <div
                                class="pt-6 border-t border-white/5 flex justify-between items-center group-hover:border-accent-primary/20">
                                <span
                                    class="text-[10px] font-bold text-text-secondary uppercase tracking-widest">{{ __('Live Feed') }}</span>
                                <div class="flex gap-1">
                                    <div class="w-1 h-3 bg-accent-primary/30 rounded-full animate-pulse"></div>
                                    <div class="w-1 h-5 bg-accent-primary/50 rounded-full animate-pulse"
                                        style="animation-delay: 0.2s"></div>
                                    <div class="w-1 h-2 bg-accent-primary/70 rounded-full animate-pulse"
                                        style="animation-delay: 0.4s"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>



    {{-- REGULATORY COMPLIANCE --}}
    @if ($regulatoryCompliance)
        <section id="compliance" class="py-24 relative overflow-hidden bg-black/40">
            {{-- Background Glow --}}
            <div
                class="absolute top-1/2 left-0 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-500/5 rounded-full blur-[120px] pointer-events-none">
            </div>
            <div
                class="absolute top-0 right-0 w-full h-full bg-[radial-gradient(circle_at_bottom_right,rgba(16,185,129,0.03),transparent_60%)] -z-10">
            </div>

            <div class="container mx-auto px-4 relative">
                <div class="grid lg:grid-cols-2 gap-16 items-start">
                    <div>
                        <span
                            class="text-emerald-400 font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Global Standards') }}</span>
                        <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-8">
                            {{ __('Regulated,') }} <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-600">{{ __('Compliant, Trusted.') }}</span>
                        </h2>
                        <p class="text-lg text-text-secondary mb-10 leading-relaxed max-w-xl">
                            {{ __('We operate under some of the world\'s strictest financial regulations. Our commitment to transparency and legal compliance ensures your assets are always protected.') }}
                        </p>

                        <div class="space-y-6">
                            <h4 class="text-sm font-bold text-white uppercase tracking-widest">
                                {{ __('Primary Supervisory Bodies') }}</h4>
                            <div class="flex flex-wrap gap-4">
                                @foreach ($regulatoryCompliance['regulators'] as $regulator)
                                    <div
                                        class="px-6 py-4 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-4 group hover:bg-white/10 transition-all">
                                        <div
                                            class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                                        </div>
                                        <span class="text-sm font-bold text-white/90">{{ __($regulator) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="bg-secondary-dark/50 border border-white/5 rounded-[2.5rem] p-8 lg:p-10 backdrop-blur-xl">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-bold text-white">{{ __('Official Certification') }}</h3>
                            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>

                        <div class="grid gap-4">
                            @foreach ($regulatoryCompliance['pdf_certificates'] as $pdf)
                                <a href="{{ asset('assets/pdf/' . $pdf['file']) }}"
                                    class="pdf-preview-link flex items-center justify-between p-5 rounded-2xl bg-white/5 border border-white/5 hover:border-emerald-500/30 hover:bg-emerald-500/5 transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-text-secondary group-hover:text-emerald-500 transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div
                                                class="text-sm font-bold text-white leading-tight group-hover:text-emerald-400 transition-colors">
                                                {{ __($pdf['name']) }}</div>
                                            <div class="text-[10px] text-text-secondary uppercase tracking-widest mt-1">
                                                {{ __('PDF DOCUMENT • VERIFIED') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center bg-white/5 group-hover:bg-emerald-500 group-hover:border-emerald-500 transition-all opacity-100 translate-x-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-8 pt-8 border-t border-white/5 flex items-center gap-3 text-text-secondary">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-[10px] font-bold uppercase tracking-widest">
                                {{ __('End-to-End Encryption & SEC Compliant Infrastructure') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


    {{-- INVESTMENT SECTORS EXPLORER --}}
    @if (isset($sectors))
        <section id="sectors" class="py-24 relative overflow-hidden bg-[#050505]">
            {{-- Structural Accents --}}
            <div
                class="absolute top-0 right-0 w-[800px] h-[800px] bg-accent-primary/[0.03] rounded-full blur-[150px] -z-10 animate-pulse-glow">
            </div>
            <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-purple-500/[0.02] rounded-full blur-[130px] -z-10">
            </div>

            <div class="container mx-auto px-4 relative">
                <div class="flex flex-col lg:flex-row justify-between items-end gap-8 mb-16">
                    <div class="max-w-2xl">
                        <span
                            class="text-accent-primary font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Asset Architecture') }}</span>
                        <h2 class="text-4xl md:text-5xl font-black text-white leading-tight">
                            {{ __('Multi-Asset') }} <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">{{ __('Sector Integration.') }}</span>
                        </h2>
                    </div>
                    <p class="text-text-secondary text-lg max-w-sm lg:text-right">
                        {{ __('Explore specialized investment vehicles curated through institutional-grade research and risk management.') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($sectors as $key => $sector)
                        <div data-cursor-label="View Dynamics"
                            class="group relative p-8 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 hover:border-accent-primary/30 transition-all duration-500 flex flex-col h-full overflow-hidden">

                            {{-- Hover Background Effect --}}
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-accent-primary/[0.05] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                            </div>

                            <div class="relative z-10">
                                {{-- Header: Icon & Risk --}}
                                <div class="flex justify-between items-start mb-8">
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-white group-hover:bg-accent-primary group-hover:scale-110 transition-all duration-500">
                                        {{-- Use dynamic icons based on key --}}
                                        @switch($key)
                                            @case('stocks_and_etfs')
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2">
                                                    </path>
                                                </svg>
                                            @break

                                            @case('crypto_assets')
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                            @break

                                            @case('real_estate')
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                                    </path>
                                                </svg>
                                            @break

                                            @case('gaming_and_esports')
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4v-3a2 2 0 002-2V7a2 2 0 00-2-2H5z">
                                                    </path>
                                                </svg>
                                            @break

                                            @default
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                        @endswitch
                                    </div>
                                    <div
                                        class="px-3 py-1 rounded-full bg-white/5 border border-white/10 flex items-center gap-2">
                                        <div
                                            class="w-1.5 h-1.5 rounded-full {{ $sector['risk_level'] === 'high' ? 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]' : ($sector['risk_level'] === 'medium' ? 'bg-amber-500' : 'bg-emerald-500') }}">
                                        </div>
                                        <span
                                            class="text-[10px] font-bold text-white uppercase tracking-widest">{{ __($sector['risk_profile']) }}</span>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <h3
                                    class="text-2xl font-black text-white mb-3 group-hover:text-accent-primary transition-colors">
                                    {{ __($key) }}</h3>
                                <p class="text-text-secondary text-sm leading-relaxed mb-8 line-clamp-3">
                                    {{ __($sector['context']) }}</p>

                                {{-- Dynamics Grid --}}
                                <div class="grid grid-cols-2 gap-4 mb-8">
                                    <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5">
                                        <div
                                            class="text-[9px] font-bold text-text-secondary uppercase tracking-[0.2em] mb-1">
                                            {{ __('Volatility') }}</div>
                                        <div class="text-sm font-bold text-white capitalize">
                                            {{ __($sector['volatility']) }}</div>
                                    </div>
                                    <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5">
                                        <div
                                            class="text-[9px] font-bold text-text-secondary uppercase tracking-[0.2em] mb-1">
                                            {{ __('Yield Target') }}</div>
                                        <div class="text-sm font-bold text-white">
                                            {{ $sector['risk_level'] === 'high' ? __('Aggressive') : __('Sustainable') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Revenue Streams (Hover Reveal) --}}
                                <div class="space-y-3 opacity-60 group-hover:opacity-100 transition-all duration-500">
                                    <h4 class="text-[10px] font-black text-white uppercase tracking-widest">
                                        {{ __('Profit Channels') }}</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @php $i = 0; @endphp
                                        @foreach ($sector['earnings_generated_from'] as $subKey => $desc)
                                            @if ($i++ < 3)
                                                <span
                                                    class="text-[10px] px-2.5 py-1 rounded-lg bg-white/5 border border-white/5 text-text-secondary capitalize">{{ __($subKey) }}</span>
                                            @endif
                                        @endforeach
                                        @if (count($sector['earnings_generated_from']) > 3)
                                            <span
                                                class="text-[10px] px-2.5 py-1 rounded-lg bg-accent-primary/10 text-accent-primary font-bold">+{{ count($sector['earnings_generated_from']) - 3 }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Action Footer --}}
                            <div class="mt-8 pt-8 border-t border-white/5 flex items-center justify-between relative z-10">
                                <span
                                    class="text-[10px] font-bold text-text-secondary uppercase tracking-widest">{{ __('System Ready') }}</span>
                                <div
                                    class="flex items-center gap-2 text-accent-primary font-black text-xs uppercase tracking-widest group/btn cursor-pointer">
                                    {{ __('Analyze') }}
                                    <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- FEATURES --}}
    <section id="company" class="py-24 bg-secondary-dark border-t border-white/5 relative overflow-hidden">
        {{-- Background Accents --}}
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[radial-gradient(circle,rgba(59,130,246,0.02),transparent_70%)] -z-10">
        </div>

        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="card-hover-effect md:col-span-2 p-10 rounded-3xl bg-primary-dark border border-white/5 relative overflow-hidden group">
                    <div class="relative z-10">
                        <h3 class="text-3xl font-bold text-white mb-4">{{ __('Bank-Grade Security') }}</h3>
                        <p class="text-text-secondary max-w-lg">
                            {{ __('We use AES-256 encryption, cold storage for 95% of crypto assets, and multi-signature wallets.') }}
                        </p>
                    </div>
                    <svg class="absolute right-10 bottom-10 w-24 h-24 text-white/5 rotate-12" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <div
                    class="p-10 rounded-3xl bg-primary-dark border border-white/5 relative overflow-hidden hover:border-accent-secondary/30 transition-colors">
                    <h3 class="text-xl font-bold text-white mb-2">{{ __('Lightning Fast') }}</h3>
                    <p class="text-text-secondary text-sm">
                        {{ __('Low-latency matching engine ensures you get the price you see.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    {{-- MARKET DOMINANCE (HEATMAP) --}}
    <section id="dominance" class="py-24 relative overflow-hidden bg-[#0A0A0C]">
        {{-- Data Glows --}}
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-accent-primary/[0.01] rounded-full blur-[150px] -z-10 animate-deep-pulse">
        </div>

        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row justify-between items-end gap-8 mb-16">
                <div class="max-w-2xl">
                    <span
                        class="text-accent-primary font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Asset Architecture') }}</span>
                    <h2 class="text-4xl md:text-5xl font-black text-white leading-tight">
                        {{ __('Market Cap') }} <br>
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">{{ __('Heatmap & Analysis.') }}</span>
                    </h2>
                </div>
                <p class="text-text-secondary text-lg max-w-sm lg:text-right">
                    {{ __('Visualizing the global digital economy. Monitor sector dominance and capital rotation in real-time.') }}
                </p>
            </div>

            <div
                class="p-8 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 relative overflow-hidden group h-[500px] flex flex-col">
                <div class="flex-1 w-full rounded-2xl overflow-hidden bg-white/[0.02] border border-white/5">
                    {{-- TradingView Heatmap Widget --}}
                    <div class="tradingview-widget-container h-full">
                        <div class="tradingview-widget-container__widget h-full" id="tv-heatmap"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- OUR TEAMS SECTION --}}
    <section id="team" class="py-24 relative overflow-hidden bg-[#050505]">
        {{-- Structural Accents --}}
        <div
            class="absolute top-0 right-0 w-[800px] h-[800px] bg-accent-primary/[0.03] rounded-full blur-[150px] -z-10 animate-pulse-glow">
        </div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-purple-500/[0.02] rounded-full blur-[130px] -z-10">
        </div>

        <div class="container mx-auto px-4 relative">
            <div class="flex flex-col lg:flex-row justify-between items-end gap-8 mb-16">
                <div class="max-w-2xl">
                    <span
                        class="text-accent-primary font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Architects of Finance') }}</span>
                    <h2 class="text-4xl md:text-5xl font-black text-white leading-tight">
                        {{ __('The Minds Behind') }} <br>
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">{{ __('Pro-Level Systems.') }}</span>
                    </h2>
                </div>
                <p class="text-text-secondary text-lg max-w-sm lg:text-right">
                    {{ __('Our leadership combines decades of experience in traditional hedge funds, blockchain security, and quantitative research.') }}
                </p>
            </div>


            {{-- Team Bento Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-6">
                {{-- CEO: Large Card --}}
                @php
                    $ceo = $management_team->where('role', 'ceo')->first();
                @endphp
                @if ($ceo)
                    <div
                        class="md:col-span-4 lg:col-span-3 lg:row-span-2 group relative rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 overflow-hidden transition-all duration-700 hover:border-accent-primary/30 shadow-2xl">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[#0B0F17] via-transparent to-transparent z-10 opacity-80 group-hover:opacity-60 transition-opacity">
                        </div>
                        <img src="{{ asset('assets/images/team/' . $ceo->image) }}" alt="CEO"
                            class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">

                        <div class="absolute bottom-0 left-0 w-full p-8 lg:p-12 z-20">
                            <div class="flex items-center gap-4 mb-4">
                                <span
                                    class="px-3 py-1 rounded-full bg-accent-primary text-white text-[10px] font-black uppercase tracking-widest">{{ __('Founder & CEO') }}</span>
                                <div class="w-2 h-2 rounded-full bg-accent-primary animate-pulse"></div>
                            </div>
                            <h3 class="text-3xl lg:text-4xl font-black text-white mb-4">{{ __($ceo->name) }}</h3>
                            <p
                                class="text-text-secondary text-sm lg:text-base leading-relaxed max-w-md opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-4 group-hover:translate-y-0">
                                {{ __($ceo->description, ['site_name' => getSetting('name')]) }}
                            </p>
                        </div>
                    </div>
                @endif

                {{-- CTO Card --}}
                @php
                    $cto = $management_team->where('role', 'cto')->first();
                @endphp
                @if ($cto)
                    <div
                        class="md:col-span-2 lg:col-span-3 group relative rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 overflow-hidden transition-all duration-700 hover:border-purple-500/30">
                        <div class="flex flex-col lg:flex-row h-full">
                            <div class="w-full lg:w-1/2 overflow-hidden">
                                <img src="{{ asset('assets/images/team/' . $cto->image) }}" alt="CTO"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 aspect-square lg:aspect-auto">
                            </div>
                            <div class="w-full lg:w-1/2 p-8 flex flex-col justify-center">
                                <div class="mb-4">
                                    <span
                                        class="text-purple-400 text-[10px] font-black uppercase tracking-widest">{{ __('Chief Tech Officer') }}</span>
                                </div>
                                <h3 class="text-2xl font-black text-white mb-2">{{ __($cto->name) }}</h3>
                                <p class="text-text-secondary text-xs leading-relaxed">
                                    {{ __($cto->description, ['site_name' => getSetting('name')]) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Head of Quant --}}
                @php
                    $quant = $management_team->where('role', 'quant')->first();
                @endphp
                @if ($quant)
                    <div
                        class="md:col-span-2 lg:col-span-3 group relative rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 overflow-hidden transition-all duration-700 hover:border-emerald-500/30">
                        <div class="flex flex-col lg:flex-row h-full">
                            <div class="w-full lg:w-1/2 overflow-hidden order-1 lg:order-2">
                                <img src="{{ asset('assets/images/team/' . $quant->image) }}" alt="Head of strategy"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 aspect-square lg:aspect-auto">
                            </div>
                            <div class="w-full lg:w-1/2 p-8 flex flex-col justify-center order-2 lg:order-1">
                                <div class="mb-4">
                                    <span
                                        class="text-emerald-400 text-[10px] font-black uppercase tracking-widest">{{ __('Head of Strategy') }}</span>
                                </div>
                                <h3 class="text-2xl font-black text-white mb-2">{{ __($quant->name) }}</h3>
                                <p class="text-text-secondary text-xs leading-relaxed">
                                    {{ __($quant->description, ['site_name' => getSetting('name')]) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Compliance Card (Small Bento) --}}
                @php
                    $compliance = $management_team->where('role', 'coo')->first();
                @endphp
                @if ($compliance)
                    <div
                        class="md:col-span-4 lg:col-span-6 p-8 lg:p-12 rounded-[2.5rem] bg-gradient-to-r from-accent-primary/10 to-transparent border border-white/5 group relative overflow-hidden">
                        <div class="grid lg:grid-cols-12 gap-8 items-center relative z-10">
                            <div class="lg:col-span-2">
                                <div class="w-24 h-24 rounded-full border-2 border-accent-primary/30 p-1 overflow-hidden">
                                    <img src="{{ asset('assets/images/team/' . $compliance->image) }}"
                                        class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-700">
                                </div>
                            </div>
                            <div class="lg:col-span-6">
                                <span
                                    class="text-accent-primary text-[10px] font-black uppercase tracking-widest block mb-2">{{ __('Global Operations') }}</span>
                                <h3 class="text-2xl font-black text-white mb-2">{{ __($compliance->name) }}</h3>
                                <p class="text-text-secondary text-sm">
                                    {{ __($compliance->description, ['site_name' => getSetting('name')]) }}
                                </p>
                            </div>
                            <div class="lg:col-span-4 flex justify-end gap-3">
                                <div class="flex flex-col items-end">
                                    <span class="text-white font-bold text-sm">{{ __('Verified Governance') }}</span>
                                    <span
                                        class="text-accent-primary text-[10px] font-black uppercase tracking-widest">{{ __('SEC v4.2 PRO') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                {{-- lets list other team members --}}
                @php
                    $other_team = $management_team->where('role', 'others');
                @endphp
                @if ($other_team)
                    @foreach ($other_team as $team)
                        <div
                            class="md:col-span-2 lg:col-span-2 p-8 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 group relative overflow-hidden hover:border-accent-primary/30 transition-all duration-500">
                            <div class="relative z-10 flex flex-col items-center text-center">
                                <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-full border-2 border-accent-primary/30 p-1 overflow-hidden mb-6">
                                    <img src="{{ asset('assets/images/team/' . $team->image) }}"
                                        class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-700">
                                </div>
                                <div>
                                    <span
                                        class="text-accent-primary text-[10px] font-black uppercase tracking-widest block mb-2">{{ __('Management Team') }}</span>
                                    <h3 class="text-xl font-black text-white mb-2 leading-tight">{{ __($team->name) }}</h3>
                                    <p class="text-text-secondary text-xs leading-relaxed line-clamp-2">
                                        {{ __($team->description, ['site_name' => getSetting('name')]) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    {{-- CLIENT REVIEWS --}}
    <section id="reviews" class="py-24 relative overflow-hidden bg-[#050505]">
        {{-- Soft Glows --}}
        <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-accent-primary/[0.02] rounded-full blur-[130px] -z-10">
        </div>
        <div
            class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-emerald-500/[0.01] rounded-full blur-[100px] -z-10 animate-pulse-glow">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span
                    class="text-accent-primary font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Global Sentiments') }}</span>
                <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-6">
                    {{ __('Trusted by the') }} <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-accent-primary to-accent-secondary">{{ __('Financial Elite.') }}</span>
                </h2>
                <p class="text-text-secondary text-lg leading-relaxed">
                    {{ __('Direct feedback from institutional partners and private investors utilizing our liquidity and algorithmic ecosystems.') }}
                </p>
            </div>

            {{-- Reviews Scrollable Area --}}
            <div class="relative group/scroll">
                <div class="flex gap-8 overflow-x-auto pb-12 snap-x snap-mandatory no-scrollbar cursor-grab active:cursor-grabbing" id="reviews-scroll-container">
                    @foreach ($reviews as $review)
                        <div
                            class="min-w-[320px] md:min-w-[400px] snap-center group p-8 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 hover:border-accent-primary/30 transition-all duration-500 relative overflow-hidden flex flex-col justify-between">
                            <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.437.917-4 3.638-4 5.849h4v10h-9.996z" />
                                </svg>
                            </div>

                            <div class="relative z-10">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-14 h-14 rounded-2xl border border-white/10 p-1 overflow-hidden shrink-0">
                                        @if($review->image)
                                            <img src="{{ asset('assets/images/team/' . $review->image) }}" alt="{{ $review->name }}"
                                                class="w-full h-full object-cover rounded-xl grayscale group-hover:grayscale-0 transition-all duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-white/5 rounded-xl">
                                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold">{{ __($review->name) }}</h4>
                                        <p class="text-[10px] text-accent-primary font-black uppercase tracking-widest">
                                            {{ __($review->role) }}</p>
                                    </div>
                                </div>

                                <p class="text-text-secondary text-sm leading-relaxed mb-8 italic min-h-[80px]">
                                    "{{ __($review->review, ['site_name' => getSetting('name')]) }}"
                                </p>

                                <div class="flex gap-1">
                                    @for ($i = 0; $i < $review->rating; $i++)
                                        <svg class="w-4 h-4 text-accent-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Scroll Navigation --}}
                <div class="hidden md:flex justify-center gap-4 mt-8">
                    <button onclick="scrollReviews('left')" class="p-4 rounded-full border border-white/5 bg-white/5 hover:bg-accent-primary hover:border-accent-primary transition-all text-white group/btn">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button onclick="scrollReviews('right')" class="p-4 rounded-full border border-white/5 bg-white/5 hover:bg-accent-primary hover:border-accent-primary transition-all text-white group/btn">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    @include('templates.bento.blades.partials.pdf-modal')
@endsection

@push('styles')
    <style>
        .tradingview-widget-container {
            width: 100%;
        }

        .tradingview-widget-copyright {
            display: none !important;
        }

        /* Feature Explorer Styles */
        .feature-tab.active {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent-primary, #3b82f6);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.1);
            transform: translateX(8px);
        }

        @media (max-width: 1024px) {
            .feature-tab.active {
                transform: translateY(-4px);
            }

            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        }

        .feature-panel {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-tab {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-tab:active {
            transform: scale(0.95);
        }

        /* Dynamic Background Animations */
        @keyframes data-flow {
            0% {
                transform: translateY(-20rem);
                opacity: 0;
            }

            20% {
                opacity: 0.8;
            }

            80% {
                opacity: 0.8;
            }

            100% {
                transform: translateY(100vh);
                opacity: 0;
            }
        }

        .animate-data-flow {
            animation: data-flow linear infinite;
        }

        @keyframes scan-line {
            0% {
                transform: translateY(0);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(100vh);
                opacity: 0;
            }
        }

        .animate-scan-line {
            animation: scan-line 8s linear infinite;
        }

        .animate-pulse-glow {
            animation: pulse-glow 4s ease-in-out infinite;
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.1);
            }
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        {{-- Feature Explorer Tabs Logic --}}
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.feature-tab');
            const panels = document.querySelectorAll('.feature-panel');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetId = this.dataset.featureId;

                    // Update Tabs
                    if (window.innerWidth < 1024) {
                        // Mobile: Scroll to panel
                        const targetPanel = document.getElementById('panel-' + targetId);
                        if (targetPanel) {
                            const yOffset = -100; // Adjustment for sticky header
                            const y = targetPanel.getBoundingClientRect().top + window.pageYOffset +
                                yOffset;
                            window.scrollTo({
                                top: y,
                                behavior: 'smooth'
                            });
                        }

                        // Update active tab for visual feedback
                        tabs.forEach(t => {
                            t.classList.remove('active', 'border-accent-primary',
                                'bg-white/10');
                            const icon = t.querySelector('.feature-icon-box');
                            const title = t.querySelector('h3');
                            if (icon) icon.classList.remove('bg-accent-primary',
                                'text-white', 'shadow-lg', 'shadow-accent-primary/20');
                            if (icon) icon.classList.add('bg-white/5',
                                'text-text-secondary');
                            if (title) title.classList.remove('text-white');
                            if (title) title.classList.add('text-text-secondary');
                        });
                        this.classList.add('active', 'border-accent-primary', 'bg-white/10');
                        const activeIcon = this.querySelector('.feature-icon-box');
                        const activeTitle = this.querySelector('h3');
                        if (activeIcon) {
                            activeIcon.classList.remove('bg-white/5', 'text-text-secondary');
                            activeIcon.classList.add('bg-accent-primary', 'text-white', 'shadow-lg',
                                'shadow-accent-primary/20');
                        }
                        if (activeTitle) {
                            activeTitle.classList.remove('text-text-secondary');
                            activeTitle.classList.add('text-white');
                        }
                    } else {
                        // Desktop: Swap panels
                        tabs.forEach(t => {
                            t.classList.remove('active', 'border-accent-primary',
                                'bg-white/10');
                            const icon = t.querySelector('.feature-icon-box');
                            const title = t.querySelector('h3');
                            if (icon) icon.classList.remove('bg-accent-primary',
                                'text-white', 'shadow-lg', 'shadow-accent-primary/20');
                            if (icon) icon.classList.add('bg-white/5',
                                'text-text-secondary');
                            if (title) title.classList.remove('text-white');
                            if (title) title.classList.add('text-text-secondary');
                        });

                        this.classList.add('active', 'border-accent-primary', 'bg-white/10');
                        const activeIcon = this.querySelector('.feature-icon-box');
                        const activeTitle = this.querySelector('h3');
                        if (activeIcon) {
                            activeIcon.classList.remove('bg-white/5', 'text-text-secondary');
                            activeIcon.classList.add('bg-accent-primary', 'text-white', 'shadow-lg',
                                'shadow-accent-primary/20');
                        }
                        if (activeTitle) {
                            activeTitle.classList.remove('text-text-secondary');
                            activeTitle.classList.add('text-white');
                        }

                        // Update Panels
                        panels.forEach(panel => {
                            panel.classList.remove('opacity-100', 'scale-100',
                                'translate-x-0', 'z-20');
                            panel.classList.add('lg:opacity-0', 'lg:scale-95',
                                'lg:translate-x-12', 'lg:z-10', 'lg:pointer-events-none'
                            );
                        });

                        const activePanel = document.getElementById('panel-' + targetId);
                        activePanel.classList.remove('lg:opacity-0', 'lg:scale-95',
                            'lg:translate-x-12', 'lg:z-10', 'lg:pointer-events-none');
                        activePanel.classList.add('opacity-100', 'scale-100', 'translate-x-0',
                            'z-20');
                    }
                });

                // Keyboard Support
                tab.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            });
        });

        {{-- TradingView Ticker Dynamic Injection --}}
            (function() {
                const container = document.getElementById('crypto-ticker-container');
                if (container) {
                    const script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.src = 'https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js';
                    script.async = true;
                    script.textContent = JSON.stringify({
                        "symbols": [{
                                "proName": "BINANCE:BTCUSDT",
                                "title": "{{ __('Bitcoin') }}"
                            },
                            {
                                "proName": "BINANCE:ETHUSDT",
                                "title": "{{ __('Ethereum') }}"
                            },
                            {
                                "proName": "BINANCE:SOLUSDT",
                                "title": "{{ __('Solana') }}"
                            },
                            {
                                "proName": "BINANCE:XRPUSDT",
                                "title": "{{ __('XRP') }}"
                            },
                            {
                                "proName": "BINANCE:ADAUSDT",
                                "title": "{{ __('Cardano') }}"
                            },
                            {
                                "proName": "BINANCE:DOGEUSDT",
                                "title": "{{ __('Dogecoin') }}"
                            },
                            {
                                "proName": "BINANCE:BNBUSDT",
                                "title": "{{ __('BNB') }}"
                            }
                        ],
                        "showSymbolLogo": true,
                        "colorTheme": "dark",
                        "isTransparent": true,
                        "displayMode": "adaptive",
                        "locale": "{{ app()->getLocale() }}"
                    });
                    container.appendChild(script);
                }
            })();

        document.addEventListener('DOMContentLoaded', function() {
            const words = [
                "{{ __('Stocks.') }}",
                "{{ __('Crypto.') }}",
                "{{ __('ETFs.') }}",
                "{{ __('Bonds.') }}",
                "{{ __('Forex.') }}",
                "{{ __('Everything.') }}"
            ];
            const el = document.getElementById('typing-text');
            let wordIndex = 0;
            let charIndex = 0;
            let isDeleting = false;
            let typeSpeed = 100;

            function type() {
                const currentWord = words[wordIndex];
                if (isDeleting) {
                    el.textContent = currentWord.substring(0, charIndex - 1);
                    charIndex--;
                    typeSpeed = 50;
                } else {
                    el.textContent = currentWord.substring(0, charIndex + 1);
                    charIndex++;
                    typeSpeed = 150;
                }

                if (!isDeleting && charIndex === currentWord.length) {
                    isDeleting = true;
                    typeSpeed = 2000;
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    wordIndex = (wordIndex + 1) % words.length;
                    typeSpeed = 500;
                }
                setTimeout(type, typeSpeed);
            }

            if (el) {
                el.textContent = '';
                setTimeout(type, 1000);
            }
        });

        {{-- Mockup Slide Rotation --}}
        const mockupList = @json($mockups ?? []);
        const sliderEl = document.getElementById('mockup-slider');

        if (mockupList.length > 1 && sliderEl) {
            let currentIdx = 0;
            const isRtl = document.documentElement.getAttribute('dir') === 'rtl' || document.documentElement.lang ===
                'ar' || document.documentElement.lang === 'ur';

            setInterval(() => {
                currentIdx = (currentIdx + 1) % mockupList.length;
                const translateValue = isRtl ? (currentIdx * 100) : -(currentIdx * 100);
                sliderEl.style.transform = `translateX(${translateValue}%)`;
            }, 5000);
        }


        {{-- ROI Calculator Logic --}}
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.getElementById('roi-plan-dropdown');
            const trigger = document.getElementById('roi-dropdown-trigger');
            const menu = document.getElementById('roi-dropdown-menu');
            const arrow = document.getElementById('dropdown-arrow');
            const selectedNameDisplay = document.getElementById('selected-plan-name');
            const options = document.querySelectorAll('.roi-option');

            const amountInput = document.getElementById('roi-amount-input');
            const amountSlider = document.getElementById('roi-amount-slider');
            const minDisplay = document.getElementById('min-amount-display');
            const maxDisplay = document.getElementById('max-amount-display');
            const profitDisplay = document.getElementById('total-profit-display');
            const payoutDisplay = document.getElementById('net-payout-display');
            const maturityDisplay = document.getElementById('maturity-date-display');
            const durationDisplay = document.getElementById('duration-display');
            const currency = "{{ getSetting('currency_symbol', '$') }}";

            let currentPlanData = null;

            function toggleDropdown(show) {
                if (show) {
                    menu.classList.remove('invisible', 'opacity-0', 'translate-y-2');
                    menu.classList.add('visible', 'opacity-100', 'translate-y-0');
                    arrow.classList.add('rotate-180');
                } else {
                    menu.classList.add('invisible', 'opacity-0', 'translate-y-2');
                    menu.classList.remove('visible', 'opacity-100', 'translate-y-0');
                    arrow.classList.remove('rotate-180');
                }
            }

            if (trigger) {
                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isOpen = menu.classList.contains('visible');
                    toggleDropdown(!isOpen);
                });
            }

            document.addEventListener('click', (e) => {
                if (dropdown && !dropdown.contains(e.target)) toggleDropdown(false);
            });

            function updateCalculator() {
                if (!currentPlanData) return;

                const min = parseFloat(currentPlanData.min);
                const max = parseFloat(currentPlanData.max);
                const percent = parseFloat(currentPlanData.percent);
                const duration = parseInt(currentPlanData.duration);
                const durationType = currentPlanData.durationType || 'Days';
                const capitalReturned = parseInt(currentPlanData.capital) === 1;

                minDisplay.textContent = currency + min.toLocaleString();
                maxDisplay.textContent = currency + max.toLocaleString();

                amountSlider.min = min;
                amountSlider.max = max;

                let val = parseFloat(amountInput.value);
                if (isNaN(val)) val = min;

                // Sync slider
                amountSlider.value = val;

                // Calculate
                const profit = (val * percent) / 100;
                const payout = capitalReturned ? (val + profit) : profit;

                profitDisplay.textContent = currency + profit.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                payoutDisplay.textContent = currency + payout.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                durationDisplay.textContent = duration + ' ' + durationType;

                // Date
                const date = new Date();
                const dType = durationType.toLowerCase();
                if (dType.includes('hour')) {
                    date.setHours(date.getHours() + duration);
                } else if (dType.includes('day')) {
                    date.setDate(date.getDate() + duration);
                } else if (dType.includes('week')) {
                    date.setDate(date.getDate() + (duration * 7));
                } else if (dType.includes('month')) {
                    date.setMonth(date.getMonth() + duration);
                } else if (dType.includes('year')) {
                    date.setFullYear(date.getFullYear() + duration);
                }
                maturityDisplay.textContent = date.toLocaleDateString(undefined, {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            options.forEach(option => {
                option.addEventListener('click', () => {
                    currentPlanData = {
                        id: option.dataset.id,
                        min: option.dataset.min,
                        max: option.dataset.max,
                        percent: option.dataset.percent,
                        duration: option.dataset.duration,
                        durationType: option.dataset.durationType,
                        capital: option.dataset.capital,
                        name: option.dataset.name
                    };

                    selectedNameDisplay.textContent = currentPlanData.name;
                    amountInput.value = currentPlanData.min;

                    options.forEach(opt => opt.classList.remove('bg-accent-primary/10'));
                    option.classList.add('bg-accent-primary/10');

                    toggleDropdown(false);
                    updateCalculator();
                });
            });

            if (amountInput) amountInput.addEventListener('input', updateCalculator);
            if (amountSlider) {
                amountSlider.addEventListener('input', (e) => {
                    amountInput.value = e.target.value;
                    updateCalculator();
                });
            }

            // Initial selection
            if (options.length > 0) {
                options[0].click();
            }
        });
        {{-- Intelligence Hub Widget Injections --}}
            (function() {
                // Market Overview
                const overviewContainer = document.getElementById('tv-market-overview');
                if (overviewContainer) {
                    const script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.src = 'https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js';
                    script.async = true;
                    script.textContent = JSON.stringify({
                        "colorTheme": "dark",
                        "dateRange": "12M",
                        "showChart": true,
                        "locale": "{{ app()->getLocale() }}",
                        "largeChartUrl": "",
                        "isTransparent": true,
                        "showSymbolLogo": true,
                        "showFloatingTooltip": false,
                        "width": "100%",
                        "height": "100%",
                        "tabs": [{
                            "title": "{{ __('Crypto') }}",
                            "symbols": [{
                                    "s": "BINANCE:BTCUSDT"
                                },
                                {
                                    "s": "BINANCE:ETHUSDT"
                                },
                                {
                                    "s": "BINANCE:SOLUSDT"
                                },
                                {
                                    "s": "BINANCE:BNBUSDT"
                                },
                                {
                                    "s": "BINANCE:XRPUSDT"
                                },
                                {
                                    "s": "BINANCE:ADAUSDT"
                                }
                            ]
                        }]
                    });
                    overviewContainer.appendChild(script);
                }

                // Screener (Gainers/Losers)
                const screenerContainer = document.getElementById('tv-screener');
                if (screenerContainer) {
                    const script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.src = 'https://s3.tradingview.com/external-embedding/embed-widget-screener.js';
                    script.async = true;
                    script.textContent = JSON.stringify({
                        "width": "100%",
                        "height": "100%",
                        "defaultColumn": "overview",
                        "screener_type": "crypto_mkt",
                        "displayCurrency": "USD",
                        "colorTheme": "dark",
                        "locale": "{{ app()->getLocale() }}",
                        "isTransparent": true
                    });
                    screenerContainer.appendChild(script);
                }

                // Heatmap
                const heatmapContainer = document.getElementById('tv-heatmap');
                if (heatmapContainer) {
                    const script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.src = 'https://s3.tradingview.com/external-embedding/embed-widget-crypto-coins-heatmap.js';
                    script.async = true;
                    script.textContent = JSON.stringify({
                        "dataSource": "Crypto",
                        "hasTopBar": false,
                        "isTransparent": true,
                        "hasSymbolTooltip": true,
                        "displayMode": "Adaptive",
                        "colorTheme": "dark",
                        "symbolUrl": "",
                        "palette": ["#f44336", "#e91e63", "#9c27b0", "#673ab7", "#3f51b5", "#2196f3", "#03a9f4",
                            "#00bcd4", "#009688", "#4caf50", "#8bc34a", "#cddc39", "#ffeb3b", "#ffc107",
                            "#ff9800", "#ff5722", "#795548", "#9e9e9e", "#607d8b"
                        ],
                        "width": "100%",
                        "height": "100%"
                    });
                    heatmapContainer.appendChild(script);
                }

                // Technical Analysis (Terminal Depth / Sentiment)
                const taContainer = document.getElementById('tv-technical-analysis');
                if (taContainer) {
                    const script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.src = 'https://s3.tradingview.com/external-embedding/embed-widget-technical-analysis.js';
                    script.async = true;
                    script.textContent = JSON.stringify({
                        "interval": "1m",
                        "width": "100%",
                        "isTransparent": true,
                        "height": "100%",
                        "symbol": "BINANCE:BTCUSDT",
                        "showIntervalTabs": true,
                        "locale": "{{ app()->getLocale() }}",
                        "colorTheme": "dark"
                    });
                    taContainer.appendChild(script);
                }
            })();

        window.scrollReviews = function(direction) {
            const container = document.getElementById('reviews-scroll-container');
            if (container) {
                const scrollAmount = 432; // card width + gap
                if (direction === 'left') {
                    container.scrollBy({
                        left: -scrollAmount,
                        behavior: 'smooth'
                    });
                } else {
                    container.scrollBy({
                        left: scrollAmount,
                        behavior: 'smooth'
                    });
                }
            }
        };
    </script>
@endpush
