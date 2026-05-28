@php
    // Mock Data for List View Stocks Interface
    $portfolio = [
        'total_value' => 12450.5,
        'total_profit' => 1250.25,
        'profit_pct' => 11.2,
        'cash_balance' => 5000.0,
    ];

    // function to get ticker information
    $getTickerInformation = function ($ticker, $key) use ($marketStocks) {
        $all_tickers = $marketStocks;
        if (count($all_tickers) > 0) {
            foreach ($all_tickers as $stock) {
                if ($stock['ticker'] == $ticker) {
                    return $stock[$key];
                }
            }
        }
        return $key;
    };

@endphp

@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ANALYTICS HEADER --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            {{-- 1. Total Cost Basis (Invested) --}}
            <div
                class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div
                    class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:via-accent-primary/50 transition-all duration-500">
                </div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h2 class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                            {{ __('Invested Value') }}</h2>
                        <div class="text-2xl font-mono font-bold text-white tracking-tight">
                            {{ getSetting('currency_symbol') }}{{ number_format($stock_analytics['total_cost_basis'], 2) }}
                        </div>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-accent-primary shadow-[0_0_15px_rgba(var(--color-accent-primary),0.2)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- 2. Current PnL --}}
            <div
                class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div
                    class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:via-indigo-500/70 transition-all duration-500">
                </div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h2 class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                            {{ __('Unrealized P/L') }}</h2>
                        <div class="flex items-baseline gap-2">
                            <span
                                class="text-2xl font-mono font-bold {{ $stock_analytics['total_pnl'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }} tracking-tight">
                                {{ $stock_analytics['total_pnl'] >= 0 ? '+' : '' }}{{ getSetting('currency_symbol') }}{{ number_format($stock_analytics['total_pnl'], 2) }}
                            </span>
                        </div>
                        <span
                            class="text-xs font-bold {{ $stock_analytics['total_pnl_percent'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $stock_analytics['total_pnl_percent'] >= 0 ? '▲' : '▼' }}
                            {{ $stock_analytics['total_pnl_percent'] }}%
                        </span>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center {{ $stock_analytics['total_pnl'] >= 0 ? 'text-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.2)]' : 'text-rose-400 shadow-[0_0_15px_rgba(244,63,94,0.2)]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- 3. Top Performer --}}
            <div
                class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div
                    class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:via-emerald-500/70 transition-all duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-xs font-bold text-text-secondary uppercase tracking-wider">
                            {{ __('Best Performer') }}</h2>
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    @if ($stock_analytics['top_gainer'])
                        <div class="font-bold text-white text-lg">{{ $stock_analytics['top_gainer']['ticker'] }}</div>
                        <div class="text-emerald-400 text-sm font-mono font-bold">
                            +{{ getSetting('currency_symbol') }}{{ number_format($stock_analytics['top_gainer']['pnl'], 2) }}
                            <span class="text-xs opacity-80">(+{{ $stock_analytics['top_gainer']['pnl_percent'] }}%)</span>
                        </div>
                    @else
                        <div class="text-text-secondary text-sm italic">{{ __('No Data') }}</div>
                    @endif
                </div>
            </div>

            {{-- 4. Trade Volume --}}
            <div
                class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div
                    class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:via-blue-500/70 transition-all duration-500">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-xs font-bold text-text-secondary uppercase tracking-wider">{{ __('Total Volume') }}
                        </h2>
                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-lg font-mono font-bold text-white">
                        {{ getSetting('currency_symbol') }}{{ number_format($stock_analytics['buy_volume_usd'] + $stock_analytics['sell_volume_usd'], 2) }}
                    </div>
                    <div class="text-xs text-text-secondary mt-1">
                        {{ $stock_analytics['total_trades'] }} {{ __('Trades') }}
                        @if ($stock_analytics['most_traded_ticker'])
                            <span class="opacity-60">• {{ __('Most:') }}
                                {{ $stock_analytics['most_traded_ticker']['ticker'] }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Navigation Toggle --}}
        <div class="flex items-center gap-6 border-b border-white/5" x-data="{ tab: 'marketplace' }">
            <button onclick="switchTab('marketplace')" id="tab-btn-marketplace"
                class="group relative pb-4 px-2 cursor-pointer">
                <span
                    class="text-base font-bold text-white group-hover:text-accent-primary transition-colors">{{ __('Marketplace') }}</span>
                <span id="tab-indicator-marketplace"
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-accent-primary shadow-[0_0_10px_rgba(var(--color-accent-primary),0.5)] transition-all"></span>
            </button>
            <button onclick="switchTab('portfolio')" id="tab-btn-portfolio" class="group relative pb-4 px-2 cursor-pointer">
                <span
                    class="text-base font-bold text-text-secondary group-hover:text-white transition-colors">{{ __('Your Holdings') }}</span>
                <span id="tab-indicator-portfolio"
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-transparent transition-all"></span>
            </button>
            <button onclick="switchTab('history')" id="tab-btn-history" class="group relative pb-4 px-2 cursor-pointer">
                <span
                    class="text-base font-bold text-text-secondary group-hover:text-white transition-colors">{{ __('History') }}</span>
                <span id="tab-indicator-history"
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-transparent transition-all"></span>
            </button>
        </div>

        {{-- 3. Views --}}
        <div id="view-container">

            {{-- MARKETPLACE LIST VIEW --}}
            <div id="view-marketplace" class="space-y-4">
                {{-- Search Bar --}}
                <div class="relative group mb-6">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-text-secondary group-focus-within:text-accent-primary transition-colors"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </div>
                    <input type="text" id="stock-search"
                        placeholder="{{ __('Search companies, symbols, or sectors...') }}" onkeyup="filterStocks()"
                        class="w-full bg-[#0f1219] border border-white/10 rounded-xl py-3.5 pl-12 pr-4 text-base text-white placeholder-text-secondary focus:border-accent-primary focus:ring-0 transition-all">
                </div>


                {{-- List Header --}}
                <div
                    class="hidden md:grid grid-cols-12 gap-4 px-6 py-2 text-xs font-bold text-text-secondary uppercase tracking-wider">
                    <div class="col-span-5">{{ __('Company / Asset') }}</div>
                    <div class="col-span-3 text-right">{{ __('Price') }}</div>
                    <div class="col-span-2 text-right">{{ __('50d Change') }}</div>
                    <div class="col-span-2 text-right">{{ __('Action') }}</div>
                </div>


                {{-- Scrollable List --}}
                <div class="space-y-2" id="stock-list">
                    @forelse ($marketStocks as $index => $stock)
                        <div class="block group stock-item {{ $index >= 20 ? 'hidden' : '' }}"
                            data-name="{{ strtolower($stock['name']) }}"
                            data-symbol="{{ strtolower($stock['ticker']) }}"
                            data-sector="{{ strtolower(__($stock['sector'])) }}">
                            <div
                                class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-4 md:px-6 md:py-4 hover:border-accent-primary/40 hover:bg-white/[0.02] transition-all grid grid-cols-1 md:grid-cols-12 gap-4 items-center relative overflow-hidden">

                                {{-- Hover Gradient Line --}}
                                <div
                                    class="absolute left-0 inset-y-0 w-[2px] bg-accent-primary opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>

                                {{-- Company Info --}}
                                <div class="col-span-12 md:col-span-5 flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-2xl group-hover:scale-105 transition-transform">
                                        <img loading="lazy" decoding="async" src={{ $stock['public_png_logo_url'] }} alt="{{ $stock['name'] }}"
                                            class="w-full h-full object-contain">
                                    </div>
                                    <div>
                                        <div
                                            class="font-heading font-bold text-white text-base group-hover:text-accent-primary transition-colors">
                                            {{ $stock['name'] }}</div>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span
                                                class="text-xs font-bold text-text-secondary bg-white/5 px-1.5 py-0.5 rounded border border-white/5 w-14 text-center">{{ $stock['ticker'] }}</span>
                                            <span
                                                class="text-[10px] text-text-secondary font-medium hidden sm:inline-block">{{ __($stock['sector']) }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Price --}}
                                <div
                                    class="col-span-6 md:col-span-3 flex md:block items-center justify-between md:text-right">
                                    <span
                                        class="md:hidden text-xs text-text-secondary font-bold uppercase">{{ __('Price') }}</span>
                                    <span
                                        class="font-mono font-bold text-white text-lg">{{ number_format($stock['current_price'], 2) }}</span>
                                </div>

                                {{-- 50d Change --}}
                                <div
                                    class="col-span-6 md:col-span-2 flex md:block items-center justify-between md:text-right">
                                    <span
                                        class="md:hidden text-xs text-text-secondary font-bold uppercase">{{ __('50d Change') }}</span>

                                    @php $isPos = $stock['change_50_day_percentage'] >= 0; @endphp
                                    <div class="flex items-center justify-end gap-2">
                                        <span
                                            class="text-sm font-bold {{ $isPos ? 'text-emerald-400' : 'text-rose-400' }}">
                                            {{ $isPos ? '+' : '' }}{{ number_format($stock['change_50_day_percentage'], 2) }}%
                                        </span>
                                        {{-- Mini Trend Icon --}}
                                        @if ($isPos)
                                            <svg class="text-emerald-400 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                                                <polyline points="17 6 23 6 23 12" />
                                            </svg>
                                        @else
                                            <svg class="text-rose-400 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6" />
                                                <polyline points="17 18 23 18 23 12" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                {{-- Buy Action --}}
                                <div class="col-span-12 md:col-span-2 flex justify-end mt-4 md:mt-0">
                                    <a href="{{ route('user.capital-instruments.stocks.buy', $stock['ticker']) }}"
                                        class="w-full md:w-auto bg-accent-primary hover:bg-accent-primary/90 text-white rounded-lg px-4 py-2.5 font-bold text-sm flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-accent-primary/20 group/btn">
                                        <span>{{ __('Buy') }}</span>
                                        <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="m12 5 7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>

                            </div>
                        </div>
                    @empty
                        @if (isset($message))
                            <div class="col-span-12 flex justify-center py-12">
                                <div
                                    class="bg-rose-500/10 border border-rose-500/20 rounded-xl max-w-md w-full relative overflow-hidden text-center p-6">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-rose-500/5 to-transparent pointer-events-none">
                                    </div>
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div
                                            class="w-10 h-10 rounded-full bg-rose-500/10 flex items-center justify-center mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="text-rose-500 w-5 h-5"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12">
                                                </line>
                                                <line x1="12" y1="16" x2="12.01" y2="16">
                                                </line>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-white mb-2">{{ __('Unable to Load Stock') }}
                                        </h3>
                                        <p class="text-rose-200/70 text-sm leading-relaxed">
                                            {{ $message }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-span-12 text-center py-12 text-text-secondary">
                                {{ __('No stocks found.') }}
                            </div>
                        @endif
                    @endforelse
                </div>

                {{-- Load More Action --}}
                @if (count($marketStocks) >= 20)
                    <div class="text-center pt-6 pb-4" id="load-more-container">
                        <button onclick="loadMoreStocks()"
                            class="px-6 py-2 rounded-xl bg-white/5 border border-white/10 text-sm font-bold text-text-secondary hover:text-white hover:bg-white/10 transition-colors w-full md:w-auto">
                            {{ __('Load More Companies') }}
                        </button>
                        <div class="text-[10px] text-text-secondary mt-2">{{ __('Showing') }} <span
                                id="visible-count">20</span> {{ __('of') }}
                            {{ count($marketStocks) }} {{ __('Companies') }}</div>
                    </div>
                @endif
            </div>

            {{-- HOLDINGS LIST VIEW --}}
            <div id="view-portfolio" class="hidden space-y-4">
                <div
                    class="hidden md:grid grid-cols-12 gap-4 px-6 py-2 text-xs font-bold text-text-secondary uppercase tracking-wider">
                    <div class="col-span-5">{{ __('Asset') }}</div>
                    <div class="col-span-2 text-right">{{ __('Shares') }}</div>
                    <div class="col-span-3 text-right">{{ __('Value') }}</div>
                    <div class="col-span-2 text-right">{{ __('Action') }}</div>
                </div>


                <div class="space-y-2">
                    @forelse ($holdings as $holding)
                        <div
                            class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-4 md:px-6 md:py-4 hover:border-white/10 transition-all grid grid-cols-1 md:grid-cols-12 gap-4 items-center">

                            {{-- Asset --}}
                            <div class="col-span-12 md:col-span-5 flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-2xl">
                                    <img src="{{ $getTickerInformation($holding->ticker, 'public_png_logo_url') }}"
                                        alt="{{ $holding->name }}" class="w-full h-full object-contain rounded-lg">
                                </div>
                                <div>
                                    <div class="font-heading font-bold text-white text-base">
                                        {{ $getTickerInformation($holding->ticker, 'name') }}</div>
                                    <div
                                        class="text-xs font-bold text-text-secondary bg-white/5 px-1.5 py-0.5 rounded border border-white/5 w-max mt-0.5">
                                        {{ $holding->ticker }}</div>
                                </div>
                            </div>

                            {{-- Shares --}}
                            <div
                                class="col-span-12 md:col-span-2 flex md:block items-center justify-between md:text-right">
                                <span
                                    class="md:hidden text-xs text-text-secondary font-bold uppercase">{{ __('Shares') }}</span>
                                <span
                                    class="font-mono font-bold text-text-secondary text-lg">{{ number_format((float) $holding->shares, 4) }}</span>
                            </div>

                            {{-- Value --}}
                            <div
                                class="col-span-12 md:col-span-3 flex md:block items-center justify-between md:text-right">
                                <span
                                    class="md:hidden text-xs text-text-secondary font-bold uppercase">{{ __('Value') }}</span>
                                <div>

                                    <div class="font-mono font-bold text-white text-lg">
                                        ${{ number_format((float) $holding->shares * (float) $getTickerInformation($holding->ticker, 'current_price'), 2) }}
                                    </div>
                                    @php
                                        $pl = $holding->pnl;
                                        $pl_color = $pl >= 0 ? 'text-emerald-400' : 'text-rose-400';
                                        $pl_sign = $pl >= 0 ? '+' : '';
                                    @endphp
                                    <div class="text-xs font-bold {{ $pl_color }}">
                                        {{ $pl_sign }}${{ number_format($pl, 2) }}
                                        <span
                                            class="opacity-70">({{ $pl_sign }}{{ number_format($holding->pnl_percent, 2) }}%)</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Sell Action --}}
                            <div class="col-span-12 md:col-span-2 flex justify-end mt-4 md:mt-0">
                                <button
                                    onclick='openSellModal("{{ $holding->ticker }}", "{{ $holding->shares }}", "{{ $getTickerInformation($holding->ticker, 'public_png_logo_url') }}", "{{ $getTickerInformation($holding->ticker, 'current_price') }}")'
                                    class="w-full md:w-auto bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-lg px-4 py-2.5 font-bold text-sm flex items-center justify-center gap-2 transition-all group/btn cursor-pointer">
                                    <span>{{ __('Sell') }}</span>
                                    <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                        <path d="m12 5 7 7-7 7" />
                                    </svg>
                                </button>
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/10">
                                <svg class="w-8 h-8 text-white/20" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-white font-bold mb-1">{{ __('Your Portfolio is Empty') }}</h3>
                            <p class="text-text-secondary text-sm">
                                {{ __('Start building your wealth by purchasing stocks from the Marketplace.') }}</p>
                            <button onclick="switchTab('marketplace')"
                                class="mt-6 px-6 py-2.5 rounded-xl bg-accent-primary hover:bg-accent-primary/90 text-white font-bold transition-colors shadow-lg shadow-accent-primary/20">
                                {{ __('Browse Marketplace') }}
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- HISTORY LIST VIEW --}}
            <div id="view-history" class="hidden space-y-4 relative">
                {{-- Loading Spinner --}}
                <div id="history-loading-spinner"
                    class="hidden absolute inset-0 bg-[#0f1219]/80 z-50 flex flex-col items-center justify-center transition-opacity duration-300 rounded-2xl">
                    <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin">
                    </div>
                    <p class="mt-3 text-text-secondary font-medium animate-pulse">{{ __('Loading records...') }}</p>
                </div>

                {{-- Header --}}
                <div
                    class="hidden md:grid grid-cols-12 gap-4 px-6 py-2 text-xs font-bold text-text-secondary uppercase tracking-wider">
                    <div class="col-span-4">{{ __('Asset') }}</div>
                    <div class="col-span-3">{{ __('Date') }}</div>
                    <div class="col-span-2 text-right">{{ __('Shares') }}</div>
                    <div class="col-span-3 text-right">{{ __('Amount') }}</div>
                </div>

                <div id="history-table-content" class="space-y-4">
                    <div class="space-y-2">
                        @forelse ($holding_histories as $history)
                            <div
                                class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-4 md:px-6 md:py-4 grid grid-cols-1 md:grid-cols-12 gap-4 items-center hover:bg-white/[0.02] transition-colors relative group">
                                {{-- Asset --}}
                                <div class="col-span-12 md:col-span-4 flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center p-1.5">
                                        <img src="{{ $getTickerInformation($history->ticker, 'public_png_logo_url') }}"
                                            alt="{{ $history->ticker }}" class="w-full h-full object-contain rounded-lg">
                                    </div>
                                    <div>
                                        <div class="font-bold text-white text-sm">{{ $history->ticker }}</div>
                                        <div
                                            class="text-[10px] font-bold uppercase {{ $history->transaction_type == 'buy' ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' : 'text-rose-400 bg-rose-500/10 border-rose-500/20' }} px-1.5 py-0.5 rounded border w-max mt-0.5">
                                            {{ ucfirst(__($history->transaction_type)) }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Date --}}
                                <div class="col-span-12 md:col-span-3 flex md:block items-center justify-between">
                                    <span
                                        class="md:hidden text-xs text-text-secondary font-bold uppercase">{{ __('Date') }}</span>
                                    <span
                                        class="text-sm text-text-secondary font-medium">{{ $history->created_at->format('M d, Y H:i') }}</span>
                                </div>

                                {{-- Shares --}}
                                <div
                                    class="col-span-12 md:col-span-2 flex md:block items-center justify-between md:text-right">
                                    <span
                                        class="md:hidden text-xs text-text-secondary font-bold uppercase">{{ __('Shares') }}</span>
                                    <span
                                        class="font-mono font-bold text-white">{{ number_format((float) $history->shares, 4) }}</span>
                                </div>

                                {{-- Amount --}}
                                <div
                                    class="col-span-12 md:col-span-3 flex md:block items-center justify-between md:text-right pt-2 md:pt-0 border-t border-white/5 md:border-0 mt-2 md:mt-0">
                                    <span
                                        class="md:hidden text-xs text-text-secondary font-bold uppercase">{{ __('Amount') }}</span>
                                    <div>
                                        <span
                                            class="font-mono font-bold block {{ $history->transaction_type == 'buy' ? 'text-white' : 'text-emerald-400' }}">
                                            {{ getSetting('currency_symbol') }}{{ number_format((float) $history->amount, 2) }}
                                        </span>
                                        @if ($history->fee_amount > 0)
                                            <span class="text-xs text-text-secondary block opacity-70">
                                                {{ __('Fee') }}:
                                                {{ getSetting('currency_symbol') }}{{ number_format((float) $history->fee_amount, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div
                                    class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/10">
                                    <svg class="w-8 h-8 text-white/20" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-white font-bold mb-1">{{ __('No Transaction History') }}</h3>
                                <p class="text-text-secondary text-sm">
                                    {{ __('Your stock purchase and sale history will appear here.') }}</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($holding_histories->hasPages())
                        <div class="py-4 border-t border-white/5 ajax-pagination">
                            {{ $holding_histories->links('templates.bento.blades.partials.pagination') }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- SELL MODAL --}}
    <div id="sell-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity opacity-0" id="sell-modal-backdrop">
        </div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                {{-- Modal Panel --}}
                <div class="relative transform overflow-hidden rounded-2xl bg-[#0f1219] border border-white/10 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    id="sell-modal-panel">

                    <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                        <button type="button" onclick="closeSellModal()"
                            class="rounded-lg bg-white/5 p-2 text-text-secondary hover:text-white hover:bg-white/10 transition-colors focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center p-2 shadow-lg">
                                <img loading="lazy" decoding="async" id="modal-stock-logo" src="" alt="Stock Logo"
                                    class="w-full h-full object-contain rounded-lg">
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white mb-0.5">{{ __('Sell Stock') }}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="text-lg font-bold text-white tracking-tight"
                                        id="modal-ticker-display"></span>
                                    <span
                                        class="text-xs font-medium text-text-secondary bg-white/5 px-2 py-0.5 rounded border border-white/5"
                                        id="modal-current-price"></span>
                                </div>
                            </div>
                        </div>

                        <form id="sell-form" action="" method="POST" class="ajax-form" data-action="reload">
                            @csrf
                            <input type="hidden" name="ticker" id="modal-ticker-input">

                            <div class="space-y-5">
                                {{-- Available Shares Display --}}
                                <div
                                    class="bg-white/5 border border-white/5 rounded-xl p-4 flex justify-between items-center">
                                    <span class="text-sm text-text-secondary">{{ __('Available Shares') }}</span>
                                    <span class="font-mono font-bold text-white text-lg"
                                        id="modal-available-shares">0.0000</span>
                                </div>

                                {{-- Shares Input --}}
                                <div>
                                    <label for="sell-shares"
                                        class="block text-sm font-bold text-text-secondary uppercase tracking-wider mb-2">
                                        {{ __('Shares to Sell') }}
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="any" name="shares" id="sell-shares" required
                                            class="w-full bg-secondary-dark border border-white/10 rounded-xl py-3.5 pl-4 pr-20 text-white placeholder-text-secondary focus:border-accent-primary focus:ring-0 transition-all font-mono font-bold"
                                            placeholder="0.0000">
                                        <button type="button" id="btn-sell-max"
                                            class="absolute right-2 top-1.5 bottom-1.5 px-3 bg-white/10 hover:bg-white/20 text-xs font-bold text-white rounded-lg transition-colors uppercase">
                                            {{ __('Max') }}
                                        </button>
                                    </div>
                                </div>

                                {{-- Live Calculation Preview --}}
                                <div id="sell-calc-preview"
                                    class="hidden space-y-3 bg-white/5 border border-white/5 rounded-xl p-4">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-text-secondary">{{ __('Gross Value') }}</span>
                                        <span class="font-mono font-bold text-white" id="calc-gross">$0.00</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-text-secondary">{{ __('Fee') }} (<span
                                                id="calc-fee-percent">0</span>%)</span>
                                        <span class="font-mono font-bold text-rose-400" id="calc-fee">-$0.00</span>
                                    </div>
                                    <div class="h-px bg-white/10 my-1"></div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-text-secondary font-bold">{{ __('Net Proceeds') }}</span>
                                        <span class="font-mono font-bold text-emerald-400 text-base"
                                            id="calc-net">$0.00</span>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <button type="submit"
                                    class="w-full bg-accent-primary hover:bg-accent-primary/90 text-white rounded-xl py-3.5 font-bold text-lg shadow-lg hover:shadow-accent-primary/25 transition-all flex items-center justify-center gap-2 mt-4 cursor-pointer">
                                    {{ __('Confirm Sale') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ... (Existing variables) ...
        const totalItems = {{ count($marketStocks) }};
        let visibleItems = 20;
        const sellFeePercent = {{ getSetting('stock_sale_fee_percent', 0) }};

        // ... (Existing functions: loadMoreStocks, filterStocks, switchTab) ...
        function loadMoreStocks() {
            const hiddenItems = document.querySelectorAll('.stock-item.hidden');
            let count = 0;
            hiddenItems.forEach(item => {
                if (count < 20) {
                    item.classList.remove('hidden');
                    count++;
                }
            });
            visibleItems += count;
            document.getElementById('visible-count').innerText = Math.min(visibleItems, totalItems);
            if (visibleItems >= totalItems) {
                document.getElementById('load-more-container').style.display = 'none';
            }
        }

        function filterStocks() {
            const input = document.getElementById('stock-search');
            const filter = input.value.toLowerCase();
            const items = document.getElementsByClassName('stock-item');

            if (filter === '') {
                Array.from(items).forEach((item, index) => {
                    if (index < visibleItems) item.classList.remove('hidden');
                    else item.classList.add('hidden');
                });
                if (visibleItems < totalItems) document.getElementById('load-more-container').style.display = 'block';
                else document.getElementById('load-more-container').style.display = 'none';
                return;
            }
            document.getElementById('load-more-container').style.display = 'none';
            Array.from(items).forEach(item => {
                const name = item.getAttribute('data-name');
                const symbol = item.getAttribute('data-symbol');
                const sector = item.getAttribute('data-sector');
                if (name.indexOf(filter) > -1 || symbol.indexOf(filter) > -1 || sector.indexOf(filter) > -1) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        function switchTab(tab) {
            const tabs = ['marketplace', 'portfolio', 'history'];

            tabs.forEach(t => {
                const indicator = document.getElementById('tab-indicator-' + t);
                const btn = document.getElementById('tab-btn-' + t);
                const btnSpan = btn.querySelector('span');
                const view = document.getElementById('view-' + t);

                if (t === tab) {
                    // Active State
                    indicator.className =
                        'absolute bottom-0 left-0 w-full h-0.5 bg-accent-primary shadow-[0_0_10px_rgba(var(--color-accent-primary),0.5)] transition-all';
                    btnSpan.className = 'text-base font-bold text-white transition-colors';

                    if (view) {
                        view.classList.remove('hidden');
                        view.style.display = 'block';
                    }
                } else {
                    // Inactive State
                    indicator.className = 'absolute bottom-0 left-0 w-full h-0.5 bg-transparent transition-all';
                    btnSpan.className =
                        'text-base font-bold text-text-secondary group-hover:text-white transition-colors';

                    if (view) {
                        view.classList.add('hidden');
                        view.style.display = 'none';
                    }
                }
            });
        }

        // --- Sell Modal Logic ---
        const sellModal = document.getElementById('sell-modal');
        const sellModalBackdrop = document.getElementById('sell-modal-backdrop');
        const sellModalPanel = document.getElementById('sell-modal-panel');
        const modalTickerDisplay = document.getElementById('modal-ticker-display');
        const modalTickerInput = document.getElementById('modal-ticker-input');
        const modalAvailableShares = document.getElementById('modal-available-shares');
        const modalStockLogo = document.getElementById('modal-stock-logo');
        const modalCurrentPrice = document.getElementById('modal-current-price');

        const sellForm = document.getElementById('sell-form');
        const sellSharesInput = document.getElementById('sell-shares');
        const btnSellMax = document.getElementById('btn-sell-max');

        const calcPreview = document.getElementById('sell-calc-preview');
        const calcGross = document.getElementById('calc-gross');
        const calcFee = document.getElementById('calc-fee');
        const calcFeePercent = document.getElementById('calc-fee-percent');
        const calcNet = document.getElementById('calc-net');

        let currentStockPrice = 0;

        function openSellModal(ticker, shares, logo, price) {
            modalTickerDisplay.innerText = ticker;
            modalTickerInput.value = ticker;
            modalAvailableShares.innerText = parseFloat(shares).toFixed(4);
            modalStockLogo.src = logo;
            modalCurrentPrice.innerText = '$' + parseFloat(price).toFixed(2);
            currentStockPrice = parseFloat(price);

            sellSharesInput.value = '';
            calcPreview.classList.add('hidden');
            calcFeePercent.innerText = sellFeePercent;

            // Set Form Action Dynamically
            // Route: user.capital-instruments.stocks.sell with param ticker
            // We can construct it since we know the base structure or use a JS variable for base route?
            // Easiest is to replace a placeholder
            let actionUrl = "{{ route('user.capital-instruments.stocks.sell', ':ticker') }}";
            actionUrl = actionUrl.replace(':ticker', ticker);
            sellForm.action = actionUrl;

            sellModal.classList.remove('hidden');
            // Animation
            setTimeout(() => {
                sellModalBackdrop.classList.remove('opacity-0');
                sellModalPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            }, 10);

            // Max Button Logic
            btnSellMax.onclick = function() {
                sellSharesInput.value = shares;
                updateCalculation(shares);
            };
        }

        function closeSellModal() {
            sellModalBackdrop.classList.add('opacity-0');
            sellModalPanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => {
                sellModal.classList.add('hidden');
            }, 300);
        }

        // Live Calculation
        sellSharesInput.addEventListener('input', function() {
            updateCalculation(this.value);
        });

        function updateCalculation(shares) {
            const val = parseFloat(shares);
            if (val > 0) {
                calcPreview.classList.remove('hidden');

                const gross = val * currentStockPrice;
                const fee = gross * (sellFeePercent / 100);
                const net = gross - fee;

                calcGross.innerText = '$' + gross.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                calcFee.innerText = '-$' + fee.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                calcNet.innerText = '$' + net.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            } else {
                calcPreview.classList.add('hidden');
            }
        }
    </script>
@endsection

@section('scripts')
    {{-- jQuery (Required for AJAX) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // AJAX Pagination Logic for History Tab
            $(document).on('click', '.ajax-pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                // Show spinner
                $('#history-loading-spinner').removeClass('hidden');

                // Optional: Scroll to top of list
                $('html, body').animate({
                    scrollTop: $("#view-history").offset().top - 100
                }, 500);

                $.get(url, function(data) {
                    // Extract the new content
                    var newContent = $(data).find('#history-table-content').html();

                    // Update the DOM
                    $('#history-table-content').html(newContent);
                }).always(function() {
                    // Hide spinner
                    setTimeout(function() {
                        $('#history-loading-spinner').addClass('hidden');
                    }, 300);
                });
            });
        });
    </script>
@endsection
