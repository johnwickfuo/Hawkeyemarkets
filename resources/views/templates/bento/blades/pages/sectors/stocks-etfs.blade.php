@extends('templates.bento.blades.layouts.front')

@section('title', __($page_title))

@section('content')
    <div class="container mx-auto px-4 py-12 relative z-10">

        {{-- Section 1: The Core (Identity & Stats) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            {{-- Context Card --}}
            <div class="lg:col-span-2 relative group">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-accent-primary/20 to-accent-secondary/20 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity">
                </div>
                <div
                    class="relative h-full bg-[#0f1115]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-8 flex flex-col justify-center overflow-hidden">
                    <div
                        class="absolute top-0 right-0 p-8 text-[12rem] font-bold text-white/[0.02] leading-none pointer-events-none select-none">
                        {{ __('STK') }}
                    </div>
                    <div class="relative z-10">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-6">
                            <span class="w-2 h-2 rounded-full bg-accent-primary animate-pulse"></span>
                            <span
                                class="text-xs font-bold text-accent-primary tracking-wider uppercase">{{ __('Sector Analysis') }}</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">
                            {{ __($sector_data['context']) }}
                        </h2>
                        <div class="flex flex-wrap gap-4">
                            @foreach ($sector_data['psychology'] as $item)
                                <div
                                    class="px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-sm text-text-secondary">
                                    {{ __($item) }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pulse Stats --}}
            <div class="space-y-6">
                {{-- Risk Level --}}
                <div class="bg-[#0f1115]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                    <h3 class="text-sm font-bold text-text-secondary uppercase tracking-wider mb-4">{{ __('Risk Profile') }}
                    </h3>
                    <div class="flex items-end justify-between mb-2">
                        <span class="text-2xl font-bold text-white capitalize">{{ __($sector_data['risk_level']) }}</span>
                        <span class="text-xs text-text-secondary capitalize">{{ __($sector_data['risk_profile']) }}</span>
                    </div>
                    <div class="h-2 bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-500 via-yellow-500 to-red-500 rounded-full"
                            style="width: {{ $sector_data['risk_level'] == 'high' ? '100%' : ($sector_data['risk_level'] == 'medium' ? '66%' : '33%') }}">
                        </div>
                    </div>
                </div>

                {{-- Volatility --}}
                <div
                    class="bg-[#0f1115]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 relative overflow-hidden">
                    <h3 class="text-sm font-bold text-text-secondary uppercase tracking-wider mb-2">{{ __('Volatility') }}
                    </h3>
                    <div class="text-2xl font-bold text-white capitalize mb-4 relative z-10">
                        {{ __($sector_data['volatility']) }}</div>

                    {{-- Wave Visualization --}}
                    <div class="flex items-center gap-1 h-8 opacity-50">
                        @for ($i = 0; $i < 20; $i++)
                            <div class="flex-1 bg-accent-primary/50 rounded-full animate-pulse"
                                style="height: {{ rand(20, 100) }}%; animation-duration: {{ rand(500, 1500) }}ms"></div>
                        @endfor
                    </div>
                </div>

                {{-- Goal --}}
                <div class="bg-[#0f1115]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                    <h3 class="text-sm font-bold text-text-secondary uppercase tracking-wider mb-2">
                        {{ __('Investment Goal') }}</h3>
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-full bg-accent-secondary/20 text-accent-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <span
                            class="text-xl font-bold text-white capitalize">{{ str_replace('_', ' ', __($sector_data['investment_goal'])) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Capital Instrument (Trading Mechanism) --}}
        <div class="mb-16">
            <div class="text-center mb-10">
                <span
                    class="text-accent-primary font-bold tracking-wider uppercase text-sm mb-2 block">{{ __('Capital Instrument') }}</span>
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">{{ __('Direct Market Access') }}</h2>
                <p class="text-text-secondary max-w-2xl mx-auto">
                    {{ __('Utilize our advanced trading interface to buy and sell stocks and ETFs directly. Your gateway to the global financial markets starts here.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @php
                    $steps = [
                        [
                            'icon' =>
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>',
                            'title' => 'Create Account',
                            'desc' => 'Register and verify your identity.',
                        ],
                        [
                            'icon' =>
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>',
                            'title' => 'Deposit Funds',
                            'desc' => 'Fund your Capital Instrument wallet.',
                        ],
                        [
                            'icon' =>
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
                            'title' => 'Select Asset',
                            'desc' => 'Analyze and choose stocks or ETFs.',
                        ],
                        [
                            'icon' =>
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>',
                            'title' => 'Trade & Earn',
                            'desc' => 'Execute trades and track profit.',
                        ],
                    ];
                @endphp

                @foreach ($steps as $index => $step)
                    <div class="relative group">
                        <div
                            class="bg-[#0f1115] border border-white/10 rounded-2xl p-6 h-full hover:border-accent-primary/50 transition-colors">
                            <div
                                class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-accent-primary text-white flex items-center justify-center font-bold text-sm shadow-lg shadow-accent-primary/25">
                                {{ $index + 1 }}
                            </div>
                            <div
                                class="mb-4 text-accent-primary opacity-80 group-hover:scale-110 transition-transform duration-300">
                                {!! $step['icon'] !!}
                            </div>
                            <h4 class="text-lg font-bold text-white mb-2">{{ __($step['title']) }}</h4>
                            <p class="text-sm text-text-secondary">{{ __($step['desc']) }}</p>
                        </div>
                        @if (!$loop->last)
                            <div class="hidden md:block absolute top-1/2 -right-4 w-8 h-0.5 bg-white/10 z-0"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Section 3: Market Scanner (Logic Check) --}}
        @if (count($stocks) > 0 || count($etfs) > 0)
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-white">{{ __('Market Scanner') }}</h2>
                        <p class="text-text-secondary">{{ __('Real-time availability of Capital Instruments.') }}</p>
                    </div>
                    <a href="{{ auth()->check() ? route('user.capital-instruments.stocks') : route('user.register') }}"
                        class="btn-hover-effect px-4 py-1.5 md:px-6 md:py-2 rounded-full bg-white/5 border border-white/10 text-white font-medium hover:bg-white/10 transition-colors text-sm md:text-base">
                        {{ __('View All Assets') }}
                    </a>
                </div>

                {{-- Stocks --}}
                @if (count($stocks) > 0)
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-accent-primary rounded-full"></span>
                            {{ __('Stocks') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach (array_slice($stocks, 0, 8) as $stock)
                                <div
                                    class="bg-[#0f1115] border border-white/10 rounded-xl p-4 flex items-center gap-4 hover:shadow-lg hover:shadow-accent-primary/10 transition-all group">
                                    <div class="w-12 h-12 rounded-full bg-white/5 p-2 flex-shrink-0">
                                        {{-- Fallback for logo --}}
                                        @if (isset($stock['public_png_logo_url']) && $stock['public_png_logo_url'])
                                            <img loading="lazy" decoding="async" src="{{ $stock['public_png_logo_url'] }}" alt="{{ $stock['ticker'] }}"
                                                class="w-full h-full object-contain">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center font-bold text-xs text-text-secondary">
                                                {{ substr($stock['ticker'], 0, 2) }}</div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-0.5">
                                            <span class="font-bold text-white truncate">{{ $stock['ticker'] }}</span>
                                            <span
                                                class="text-[10px] px-1.5 py-0.5 rounded bg-accent-primary/10 text-accent-primary border border-accent-primary/20">{{ __('Stock') }}</span>
                                        </div>
                                        <p class="text-xs text-text-secondary truncate">{{ $stock['name'] }}</p>
                                        <div class="mt-1 flex items-center gap-1.5">
                                            <span
                                                class="text-xs font-mono font-bold text-white">{{ number_format($stock['current_price'], 2) }}</span>
                                            @if (isset($stock['change_1d_percentage']))
                                                <span
                                                    class="text-[10px] font-bold {{ $stock['change_1d_percentage'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                                    {{ $stock['change_1d_percentage'] >= 0 ? '+' : '' }}{{ number_format($stock['change_1d_percentage'], 2) }}%
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ auth()->check() ? route('user.capital-instruments.stocks.buy', $stock['ticker']) : route('user.register') }}"
                                        class="p-2 rounded-lg bg-white/5 {{ ($stock['change_1d_percentage'] ?? 0) >= 0 ? 'text-emerald-400 hover:bg-emerald-400' : 'text-rose-400 hover:bg-rose-400' }} hover:text-white transition-colors">
                                        @if (($stock['change_1d_percentage'] ?? 0) >= 0)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                                                <polyline points="17 6 23 6 23 12" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6" />
                                                <polyline points="17 18 23 18 23 12" />
                                            </svg>
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ETFs --}}
                @if (count($etfs) > 0)
                    <div>
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-accent-secondary rounded-full"></span>
                            {{ __('ETFs') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach (array_slice($etfs, 0, 8) as $etf)
                                <div
                                    class="bg-[#0f1115] border border-white/10 rounded-xl p-4 flex items-center gap-4 hover:shadow-lg hover:shadow-accent-secondary/10 transition-all group">
                                    <div class="w-12 h-12 rounded-full bg-white/5 p-2 flex-shrink-0">
                                        @if (isset($etf['public_png_logo_url']) && $etf['public_png_logo_url'])
                                            <img loading="lazy" decoding="async" src="{{ $etf['public_png_logo_url'] }}" alt="{{ $etf['ticker'] }}"
                                                class="w-full h-full object-contain">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center font-bold text-xs text-text-secondary">
                                                {{ substr($etf['ticker'], 0, 2) }}</div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-0.5">
                                            <span class="font-bold text-white truncate">{{ $etf['ticker'] }}</span>
                                            <span
                                                class="text-[10px] px-1.5 py-0.5 rounded bg-accent-secondary/10 text-accent-secondary border border-accent-secondary/20">{{ __('ETF') }}</span>
                                        </div>
                                        <p class="text-xs text-text-secondary truncate">{{ $etf['name'] }}</p>
                                        <div class="mt-1 flex items-center gap-1.5">
                                            <span
                                                class="text-xs font-mono font-bold text-white">{{ number_format($etf['current_price'], 2) }}</span>
                                            @if (isset($etf['change_1d_percentage']))
                                                <span
                                                    class="text-[10px] font-bold {{ $etf['change_1d_percentage'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                                    {{ $etf['change_1d_percentage'] >= 0 ? '+' : '' }}{{ number_format($etf['change_1d_percentage'], 2) }}%
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ auth()->check() ? route('user.capital-instruments.etfs.buy', $etf['ticker']) : route('user.register') }}"
                                        class="p-2 rounded-lg bg-white/5 {{ ($etf['change_1d_percentage'] ?? 0) >= 0 ? 'text-emerald-400 hover:bg-emerald-400' : 'text-rose-400 hover:bg-rose-400' }} hover:text-white transition-colors">
                                        @if (($etf['change_1d_percentage'] ?? 0) >= 0)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                                                <polyline points="17 6 23 6 23 12" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6" />
                                                <polyline points="17 18 23 18 23 12" />
                                            </svg>
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif


        {{-- Section 4: Mechanics & Earnings (Bento Grid) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
            {{-- Earnings Engine --}}
            <div class="bg-[#0f1115] border border-white/10 rounded-3xl p-8">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="text-accent-primary">
                        <path d="M12 2v20" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                    {{ __('Earnings Engine') }}
                </h3>
                <div class="grid gap-4">
                    @foreach ($sector_data['earnings_generated_from'] as $key => $desc)
                        <div
                            class="p-4 rounded-xl bg-white/5 border border-white/5 hover:border-white/10 transition-colors">
                            <h4 class="font-bold text-white text-sm mb-1 capitalize">{{ __(str_replace('_', ' ', $key)) }}
                            </h4>
                            <p class="text-xs text-text-secondary">{{ __($desc) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- How It Works --}}
            <div class="bg-[#0f1115] border border-white/10 rounded-3xl p-8">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="text-accent-primary">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                    {{ __('Mechanics') }}
                </h3>
                <ul class="space-y-4">
                    @foreach ($sector_data['how_it_works'] as $item)
                        <li class="flex items-start gap-4 p-4 rounded-xl bg-white/[0.02] border border-white/5">
                            <div
                                class="mt-1 w-2 h-2 rounded-full bg-accent-primary flex-shrink-0 shadow-[0_0_10px_rgba(59,130,246,0.5)]">
                            </div>
                            <span class="text-sm text-text-secondary leading-relaxed">{{ __($item) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Suitability --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 rounded-2xl bg-green-500/5 border border-green-500/10">
                <h4 class="font-bold text-white mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="text-green-500">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    {{ __('Ideal For') }}
                </h4>
                <p class="text-sm text-text-secondary">{{ __($sector_data['ideal_for']) }}</p>
            </div>
            <div class="p-6 rounded-2xl bg-orange-500/5 border border-orange-500/10">
                <h4 class="font-bold text-white mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="text-orange-500">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                    {{ __('Not Ideal For') }}
                </h4>
                <p class="text-sm text-text-secondary">{{ __($sector_data['not_ideal']) }}</p>
            </div>
        </div>

    </div>
@endsection
