@extends('templates.bento.blades.layouts.user')

@section('content')
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- 1. PORTFOLIO DNA HEADER (Analytics) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            {{-- Main Summary Card --}}
            <div
                class="lg:col-span-2 bg-gradient-to-br from-[#131722] to-[#0f1219] border border-white/5 rounded-3xl p-8 relative overflow-hidden shadow-2xl">
                <div
                    class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')] opacity-20">
                </div>
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-accent-primary/5 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between md:items-end gap-6 p-8 pb-0">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-2 h-2 rounded-full bg-accent-primary animate-pulse"></span>
                            <h2 class="text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Portfolio DNA') }}</h2>
                        </div>
                        <div class="space-y-1">
                            <div class="text-4xl md:text-5xl font-mono font-bold text-white tracking-tighter">
                                {{ getSetting('currency_symbol') }}{{ number_format($etf_analytics['total_cost_basis'] + $etf_analytics['total_pnl'], 2) }}
                            </div>
                            <div class="text-sm text-text-secondary font-medium">{{ __('Total Assets Under Management') }}
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-8">
                        <div>
                            <div class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-1">
                                {{ __('Net Return') }}</div>
                            <div
                                class="text-2xl font-mono font-bold {{ $etf_analytics['total_pnl'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $etf_analytics['total_pnl'] >= 0 ? '+' : '' }}{{ getSetting('currency_symbol') }}{{ number_format($etf_analytics['total_pnl'], 2) }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-1">
                                {{ __('Yield') }}</div>
                            <div
                                class="text-2xl font-mono font-bold {{ $etf_analytics['total_pnl_percent'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $etf_analytics['total_pnl_percent'] }}%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-white/5 mx-8 my-6 relative z-10"></div>

                {{-- Expanded Performance Stats --}}
                <div class="px-8 pb-8 relative z-10">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach (['1m', '6m', '1y', 'ytd'] as $period)
                            <div>
                                <div class="text-[10px] text-text-secondary uppercase font-bold tracking-wider mb-1">
                                    {{ strtoupper($period) }} {{ __('Return') }}</div>
                                <div class="flex flex-col">
                                    <span
                                        class="font-mono font-bold text-sm {{ ($portfolio_returns[$period] ?? 0) >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                        {{ ($portfolio_returns[$period] ?? 0) >= 0 ? '+' : '' }}{{ $portfolio_returns[$period] ?? 0 }}%
                                    </span>
                                    <span class="text-[10px] text-text-secondary mt-0.5">
                                        {{ __('Net Flow') }}:
                                        {{ ($history_flows[$period] ?? 0) >= 0 ? '+' : '' }}{{ getSetting('currency_symbol') }}{{ number_format($history_flows[$period] ?? 0, 0) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Quick Stats / Allocation --}}
            <div class="bg-secondary-dark/60 border border-white/5 rounded-3xl p-8 flex flex-col justify-center gap-6">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-text-secondary font-bold">{{ __('Active Positions') }}</span>
                    <span class="text-2xl font-mono font-bold text-white">{{ $etf_analytics['positions_count'] }}</span>
                </div>
                <div class="h-px bg-white/5"></div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-text-secondary font-bold">{{ __('Best Performer') }}</span>
                    @if ($etf_analytics['top_gainer'])
                        <span
                            class="text-emerald-400 font-bold font-mono">{{ $etf_analytics['top_gainer']['ticker'] }}</span>
                    @else
                        <span class="text-text-secondary text-sm italic">{{ __('N/A') }}</span>
                    @endif
                </div>
                <div class="h-px bg-white/5"></div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-text-secondary font-bold">{{ __('Total Trades') }}</span>
                    <span class="text-white font-bold font-mono">{{ $etf_analytics['total_trades'] }}</span>
                </div>
            </div>
        </div>

        {{-- 2. NAVIGATION (Tabs) --}}
        <div class="border-b border-white/5" x-data="{ tab: 'marketplace' }">
            <div class="-mb-px flex items-center gap-4 sm:gap-8 overflow-x-auto no-scrollbar mask-linear-fade">
                @foreach (['marketplace' => 'Fund Marketplace', 'portfolio' => 'My Allocations', 'history' => 'Transaction Ledger'] as $key => $label)
                    <button onclick="switchTab('{{ $key }}')" id="tab-btn-{{ $key }}"
                        class="group relative pb-4 px-1 sm:px-2 cursor-pointer transition-all whitespace-nowrap flex-shrink-0">
                        <span
                            class="text-sm sm:text-base font-bold {{ $key === 'marketplace' ? 'text-white' : 'text-text-secondary group-hover:text-white' }} transition-colors">
                            {{ __($label) }}
                        </span>
                        <span id="tab-indicator-{{ $key }}"
                            class="absolute bottom-0 left-0 w-full h-0.5 {{ $key === 'marketplace' ? 'bg-accent-primary shadow-[0_0_10px_rgba(var(--color-accent-primary),0.5)]' : 'bg-transparent' }} transition-all"></span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- 3. VIEWS CONTAINER --}}
        <div id="view-container" class="min-h-[500px]">

            {{-- VIEW: MARKETPLACE (Fund Grid) --}}
            <div id="view-marketplace" class="space-y-6 animate-fade-in">
                {{-- Search & Filter --}}
                <div class="flex flex-col md:flex-row gap-4 justify-between items-center py-2">
                    <div class="relative group w-full md:w-96">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-text-secondary group-focus-within:text-accent-primary transition-colors"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="11" cy="11" r="8" stroke-width="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                        <input type="text" id="etf-search" placeholder="{{ __('Search funds...') }}"
                            onkeyup="filterEtfs()"
                            class="w-full bg-[#0f1219] border border-white/10 rounded-xl py-3 pl-12 pr-4 text-sm text-white placeholder-text-secondary focus:border-accent-primary focus:ring-0 transition-all">
                    </div>
                </div>

                {{-- Grid Layout for Funds --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="etf-list">
                    @forelse ($marketEtfs as $index => $etf)
                        <div class="group etf-item {{ $index >= 12 ? 'hidden' : '' }}"
                            data-name="{{ strtolower($etf['name']) }}" data-ticker="{{ strtolower($etf['ticker']) }}"
                            data-issuer="{{ strtolower($etf['issuer'] ?? '') }}">

                            <div
                                class="h-full bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-3xl p-6 hover:border-accent-primary/40 hover:bg-white/[0.02] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden flex flex-col justify-between">

                                {{-- Background Decor --}}
                                <div
                                    class="absolute -right-10 -top-10 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:bg-accent-primary/10 transition-colors">
                                </div>

                                <div>
                                    <div class="flex justify-between items-start mb-6 relative z-10">
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-white p-2 shadow-lg flex items-center justify-center">
                                            <img loading="lazy" decoding="async" src="{{ $etf['public_png_logo_url'] ?? '' }}" alt="{{ $etf['ticker'] }}"
                                                class="w-full h-full object-contain">
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span
                                                class="px-2 py-1 rounded bg-white/5 border border-white/5 text-[10px] uppercase font-bold text-text-secondary mb-1">
                                                {{ $etf['asset_class'] ?? 'ETF' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-6 relative z-10">
                                        <h3
                                            class="text-lg font-bold text-white leading-tight group-hover:text-accent-primary transition-colors line-clamp-2 min-h-[3rem]">
                                            {{ $etf['name'] }}
                                        </h3>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span
                                                class="text-xs font-bold text-text-secondary font-mono">{{ $etf['ticker'] }}</span>
                                            <span class="text-xs text-text-secondary">•</span>
                                            <span
                                                class="text-xs text-text-secondary truncate max-w-[120px]">{{ $etf['issuer'] ?? 'Issuer' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative z-10">
                                    <div class="flex items-end justify-between mb-4">
                                        <div>
                                            <div class="text-[10px] text-text-secondary uppercase font-bold">
                                                {{ __('NAV') }}</div>
                                            <div class="text-xl font-mono font-bold text-white">
                                                ${{ number_format($etf['current_price'], 2) }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @php $isPos = ($etf['change_1d_percentage'] ?? 0) >= 0; @endphp
                                            <div class="text-[10px] text-text-secondary uppercase font-bold">
                                                {{ __('1D') }}</div>
                                            <div
                                                class="text-sm font-mono font-bold {{ $isPos ? 'text-emerald-400' : 'text-rose-400' }}">
                                                {{ $isPos ? '▲' : '▼' }}
                                                {{ round(abs($etf['change_1d_percentage'] ?? 0), 2) }}%
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('user.capital-instruments.etfs.buy', $etf['ticker']) }}"
                                        class="w-full bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-xl py-3 font-bold text-sm flex items-center justify-center gap-2 transition-all hover:border-white/20">
                                        <span>{{ __('View Fact Sheet') }}</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 flex justify-center">
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-text-secondary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-white font-bold">{{ __('Funds Not Available') }}</h3>
                                <p class="text-text-secondary text-sm mt-1">{{ $message ?? __('No funds found.') }}
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if (count($marketEtfs) >= 12)
                    <div class="text-center pt-8 pb-4" id="load-more-container">
                        <button onclick="loadMoreEtfs()"
                            class="px-8 py-3 rounded-xl bg-white/5 border border-white/10 text-sm font-bold text-text-secondary hover:text-white hover:bg-white/10 transition-colors">
                            {{ __('Load More Funds') }}
                        </button>
                        <div class="text-[10px] text-text-secondary mt-3">
                            {{ __('Showing') }} <span id="visible-count">12</span> {{ __('of') }}
                            {{ count($marketEtfs) }} {{ __('Funds') }}
                        </div>
                    </div>
                @endif
            </div>

            {{-- VIEW: PORTFOLIO (Holdings) --}}
            <div id="view-portfolio" class="hidden space-y-6">
                @forelse ($holdings as $holding)
                    <div
                        class="bg-secondary-dark/60 border border-white/5 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-6 hover:border-white/10 transition-colors">
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <div
                                class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 font-bold font-mono text-xs border border-blue-500/20">
                                {{ $holding->ticker }}
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-lg">{{ $holding->ticker }} {{ __('ETF') }}
                                </h4>
                                {{-- Ideally fetch name if available --}}
                                <div class="flex items-center gap-2 text-xs text-text-secondary">
                                    <span>{{ number_format($holding->shares, 4) }} {{ __('Units') }}</span>
                                    <span>•</span>
                                    <span>{{ __('Avg') }} ${{ number_format($holding->average_price, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-8 w-full md:w-auto justify-between md:justify-end">
                            <div class="text-right">
                                <div class="text-[10px] text-text-secondary uppercase font-bold">
                                    {{ __('Market Value') }}
                                </div>
                                <div class="font-mono font-bold text-white text-lg">
                                    {{-- Need current price for market value, approximating with Average Price * PnL% logic or 0 if specific price logic missing here, simplified for now --}}
                                    ${{ number_format($holding->shares * $holding->average_price + $holding->pnl, 2) }}
                                </div>
                            </div>

                            <div class="text-right min-w-[100px]">
                                <div class="text-[10px] text-text-secondary uppercase font-bold">
                                    {{ __('Total Return') }}
                                </div>
                                <div class="flex flex-col items-end">
                                    <span
                                        class="font-mono font-bold {{ $holding->pnl >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                        {{ $holding->pnl >= 0 ? '+' : '' }}${{ number_format($holding->pnl, 2) }}
                                    </span>
                                    <span
                                        class="text-xs font-bold {{ $holding->pnl >= 0 ? 'text-emerald-400' : 'text-rose-400' }} opacity-80">
                                        {{ $holding->pnl_percent }}%
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="w-full md:w-auto border-t md:border-t-0 md:border-l border-white/5 pt-4 md:pt-0 md:pl-6 flex justify-end">
                            <button
                                onclick='openSellModal("{{ $holding->ticker }}", "{{ $holding->shares }}", "{{ $holding->average_price * $holding->shares + $holding->pnl > 0 ? ($holding->average_price * $holding->shares + $holding->pnl) / $holding->shares : 0 }}")'
                                class="px-6 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-white text-sm font-bold transition-colors border border-white/10">
                                {{ __('Sell') }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-secondary-dark/30 rounded-3xl border border-white/5">
                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-text-secondary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-white font-bold text-lg">{{ __('Your Portfolio is Empty') }}</h3>
                        <p class="text-text-secondary text-sm max-w-sm mx-auto mt-2">
                            {{ __('Start building your wealth by allocating capital to funds in the Marketplace.') }}
                        </p>
                        <button onclick="switchTab('marketplace')"
                            class="mt-6 px-6 py-2.5 rounded-xl bg-accent-primary hover:bg-accent-primary/90 text-white font-bold transition-all shadow-lg shadow-accent-primary/20">
                            {{ __('Browse Funds') }}
                        </button>
                    </div>
                @endforelse
            </div>

            {{-- VIEW: HISTORY (Ledger) --}}
            <div id="view-history" class="hidden">
                <div class="overflow-x-auto rounded-2xl border border-white/5">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 text-xs text-text-secondary uppercase font-bold tracking-wider">
                                <th class="p-4 rounded-tl-2xl">{{ __('Date') }}</th>
                                <th class="p-4">{{ __('Fund') }}</th>
                                <th class="p-4 text-center">{{ __('Type') }}</th>
                                <th class="p-4 text-right">{{ __('Units') }}</th>
                                <th class="p-4 text-right">{{ __('Price') }}</th>
                                <th class="p-4 text-right rounded-tr-2xl">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($holding_histories as $history)
                                <tr class="hover:bg-white/[0.02] transition-colors">
                                    <td class="p-4 text-sm text-text-secondary font-mono">
                                        {{ $history->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="p-4 text-sm font-bold text-white">{{ $history->ticker }}</td>
                                    <td class="p-4 text-center">
                                        <span
                                            class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $history->transaction_type == 'buy' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                            {{ ucfirst($history->transaction_type) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right text-sm text-white font-mono">
                                        {{ number_format($history->shares, 4) }}</td>
                                    <td class="p-4 text-right text-sm text-text-secondary font-mono">
                                        ${{ number_format($history->price_at_action, 2) }}</td>
                                    <td class="p-4 text-right text-sm font-bold text-white font-mono">
                                        {{ getSetting('currency_symbol') }}{{ number_format($history->amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-12 text-center text-text-secondary italic">
                                        {{ __('No transaction history found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($holding_histories->hasPages())
                    <div class="py-4 border-t border-white/5 ajax-pagination mt-4">
                        {{ $holding_histories->links('templates.bento.blades.partials.pagination') }}
                    </div>
                @endif
            </div>

        </div>
    </div>


    {{-- SELL MODAL (Reused logic style) --}}
    <div id="sell-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity opacity-0" id="sell-modal-backdrop">
        </div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-3xl bg-[#0f1219] border border-white/10 text-left shadow-2xl transition-all w-full max-w-lg opacity-0 scale-95"
                    id="sell-modal-panel">
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-white">{{ __('Sell ETF') }}</h3>
                            <button type="button" onclick="closeSellModal()"
                                class="text-text-secondary hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form id="sell-form" action="" method="POST" class="ajax-form" data-action="reload">
                            @csrf
                            <input type="hidden" name="ticker" id="modal-ticker-input">

                            <div class="bg-white/5 rounded-xl p-4 mb-6 flex justify-between items-center">
                                <div>
                                    <div class="text-xs text-text-secondary uppercase font-bold">
                                        {{ __('Available Units') }}</div>
                                    <div class="text-xl font-mono font-bold text-white" id="modal-available-shares">
                                        0.0000
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-text-secondary uppercase font-bold">
                                        {{ __('Est. Price') }}
                                    </div>
                                    <div class="text-lg font-mono font-bold text-white" id="modal-current-price">$0.00
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label
                                    class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">{{ __('Units to Sell') }}</label>
                                <div class="relative">
                                    <input type="number" step="any" name="shares" id="sell-shares" required
                                        class="w-full bg-secondary-dark border border-white/10 rounded-xl py-4 pl-4 pr-20 text-white placeholder-text-secondary focus:border-accent-primary focus:ring-0 transition-all font-mono font-bold text-lg"
                                        placeholder="0.00">
                                    <button type="button" id="btn-sell-max"
                                        class="absolute right-3 top-3 bottom-3 px-3 bg-white/10 hover:bg-white/20 text-xs font-bold text-white rounded-lg transition-colors uppercase">
                                        {{ __('Max') }}
                                    </button>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-accent-primary hover:bg-accent-primary/90 text-white rounded-xl py-4 font-bold text-lg shadow-lg hover:shadow-accent-primary/25 transition-all text-center">
                                {{ __('Confirm Sale') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const totalItems = {{ count($marketEtfs) }};
        let visibleItems = 12;

        function switchTab(tab) {
            const tabs = ['marketplace', 'portfolio', 'history'];
            tabs.forEach(t => {
                const view = document.getElementById('view-' + t);
                const btn = document.getElementById('tab-btn-' + t);
                const indicator = document.getElementById('tab-indicator-' + t);
                const label = btn.querySelector('span');

                if (t === tab) {
                    view.classList.remove('hidden');
                    indicator.classList.remove('bg-transparent');
                    indicator.classList.add('bg-accent-primary',
                        'shadow-[0_0_10px_rgba(var(--color-accent-primary),0.5)]');
                    label.classList.remove('text-text-secondary');
                    label.classList.add('text-white');
                } else {
                    view.classList.add('hidden');
                    indicator.classList.remove('bg-accent-primary',
                        'shadow-[0_0_10px_rgba(var(--color-accent-primary),0.5)]');
                    indicator.classList.add('bg-transparent');
                    label.classList.add('text-text-secondary');
                    label.classList.remove('text-white');
                }
            });
        }

        function loadMoreEtfs() {
            const hiddenItems = document.querySelectorAll('.etf-item.hidden');
            let count = 0;
            hiddenItems.forEach(item => {
                if (count < 12) {
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

        function filterEtfs() {
            const input = document.getElementById('etf-search');
            const filter = input.value.toLowerCase();
            const items = document.getElementsByClassName('etf-item');

            if (filter === '') {
                Array.from(items).forEach((item, index) => {
                    if (index < visibleItems) item.classList.remove('hidden');
                    else item.classList.add('hidden');
                });
                if (visibleItems < totalItems) document.getElementById('load-more-container').style.display = 'block';
                return;
            }

            document.getElementById('load-more-container').style.display = 'none';
            Array.from(items).forEach(item => {
                const name = item.getAttribute('data-name');
                const ticker = item.getAttribute('data-ticker');
                const issuer = item.getAttribute('data-issuer');

                if (name.includes(filter) || ticker.includes(filter) || issuer.includes(filter)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        // Sell Modal
        const sellModal = document.getElementById('sell-modal');
        const sellModalBackdrop = document.getElementById('sell-modal-backdrop');
        const sellModalPanel = document.getElementById('sell-modal-panel');
        const modalTickerInput = document.getElementById('modal-ticker-input');
        const modalAvailableShares = document.getElementById('modal-available-shares');
        const modalCurrentPrice = document.getElementById('modal-current-price');
        const sellForm = document.getElementById('sell-form');
        const sellSharesInput = document.getElementById('sell-shares');
        const btnSellMax = document.getElementById('btn-sell-max');

        function openSellModal(ticker, shares, currentPrice) {
            modalTickerInput.value = ticker;
            modalAvailableShares.innerText = parseFloat(shares).toFixed(4);
            // Since we don't have real-time price in the table loop efficiently without N+1 or bulk fetch,
            // we passed an estimated approximation or 0. For accurate pricing we might need a fetch or
            // rely on the backend validation/preview.
            // For now, let's display what we passed directly.
            modalCurrentPrice.innerText = '$' + parseFloat(currentPrice).toFixed(2);

            let actionUrl = "{{ route('user.capital-instruments.etfs.sell', ':ticker') }}";
            actionUrl = actionUrl.replace(':ticker', ticker);
            sellForm.action = actionUrl;
            sellSharesInput.value = '';

            sellModal.classList.remove('hidden');
            setTimeout(() => {
                sellModalBackdrop.classList.remove('opacity-0');
                sellModalPanel.classList.remove('opacity-0', 'scale-95');
            }, 10);

            btnSellMax.onclick = function() {
                sellSharesInput.value = shares;
            };
        }

        function closeSellModal() {
            sellModalBackdrop.classList.add('opacity-0');
            sellModalPanel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                sellModal.classList.add('hidden');
            }, 300);
        }
    </script>
@endsection
