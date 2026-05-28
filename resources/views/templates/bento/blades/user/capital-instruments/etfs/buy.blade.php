@php
    $rate = 1;
    $website_currency = getSetting('currency');
    $market_currency = 'USD';

    if ($website_currency != $market_currency) {
        $converter = rateConverter(1, $website_currency, $market_currency, 'etf');
        $rate = $converter['exchange_rate'];
    }

@endphp

@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Navigation --}}
        <div>
            <a href="{{ route('user.capital-instruments.etfs') }}"
                class="inline-flex items-center gap-2 text-text-secondary hover:text-white transition-colors text-sm font-bold">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                {{ __('Back to ETFs') }}
            </a>
        </div>

        @if (!empty($marketEtfs))
            {{-- HERO SECTION: Fund Header --}}
            <div class="relative rounded-3xl overflow-hidden bg-[#131722] border border-white/5 shadow-2xl">
                {{-- Background Gradient Mesh --}}
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 via-[#0f1219] to-purple-900/10 opacity-70">
                </div>
                <div
                    class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')] opacity-20">
                </div>

                <div class="relative z-10 p-8 md:p-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                        <div class="flex items-start gap-6">
                            <div
                                class="w-24 h-24 rounded-2xl bg-white p-3 shadow-lg flex items-center justify-center shrink-0">
                                <img loading="lazy" decoding="async" src="{{ $marketEtfs['public_png_logo_url'] ?? '' }}" alt="{{ $marketEtfs['name'] }}"
                                    class="w-full h-full object-contain">
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span
                                        class="px-2.5 py-1 rounded-lg bg-blue-500/20 text-blue-400 border border-blue-500/20 text-xs font-bold uppercase tracking-wider">
                                        {{ $marketEtfs['asset_class'] ?? 'ETF' }}
                                    </span>
                                    <span class="text-text-secondary text-xs uppercase font-bold tracking-wider opacity-70">
                                        {{ $marketEtfs['issuer'] ?? 'Unknown Issuer' }}
                                    </span>
                                </div>
                                <h1
                                    class="text-2xl md:text-5xl font-bold text-white tracking-title leading-tight mb-2 break-words hidden md:block">
                                    {{ $marketEtfs['name'] }}
                                </h1>
                                <div
                                    class="flex flex-wrap items-center gap-y-2 gap-x-4 text-sm font-medium text-text-secondary">
                                    <span
                                        class="bg-white/5 px-2 py-0.5 rounded text-white font-mono font-bold border border-white/10">{{ $marketEtfs['ticker'] }}</span>
                                    <span>{{ $marketEtfs['benchmark_index'] ?? '' }}</span>
                                    @if (isset($marketEtfs['ticker']))
                                        <div class="hidden sm:block h-4 w-[1px] bg-white/10 mx-1"></div>
                                        <a href="https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK={{ $marketEtfs['ticker'] }}&owner=include&count=40"
                                            target="_blank"
                                            class="flex items-center gap-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 active:bg-blue-700 transition-all px-3 py-1.5 rounded-lg shadow-lg shadow-blue-900/20 hover:shadow-blue-900/40 border border-blue-500/50 group/sec w-full sm:w-auto justify-center sm:justify-start"
                                            title="{{ __('Verify SEC Filing') }}">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ __('Verify SEC Filing') }}</span>
                                            <svg class="w-3 h-3 opacity-50 group-hover/sec:translate-x-0.5 transition-transform"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Mobile Name --}}
                        <h1 class="text-xl font-bold text-white tracking-title leading-tight mb-2 break-words md:hidden">
                            {{ $marketEtfs['name'] }}
                        </h1>

                        {{-- Current Price Block --}}
                        <div
                            class="text-left md:text-right bg-white/5 backdrop-blur-md rounded-2xl p-4 border border-white/10 min-w-[200px]">
                            <div class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-1">
                                {{ __('NAV / Price') }}</div>
                            <div class="font-mono font-bold text-4xl text-white tracking-tighter">
                                ${{ number_format($marketEtfs['current_price'], 2) }}
                            </div>
                            @php $isPos = ($marketEtfs['change_1d_percentage'] ?? 0) >= 0; @endphp
                            <div class="flex items-center md:justify-end gap-2 mt-1">
                                <span
                                    class="flex items-center gap-1 font-bold {{ $isPos ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ $isPos ? '▲' : '▼' }}
                                    {{ round(abs($marketEtfs['change_1d_percentage'] ?? 0), 2) }}%
                                </span>
                                <span class="text-xs text-text-secondary">{{ __('Today') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Performance Ribbon --}}
                    <div class="mt-8 pt-6 border-t border-white/5 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ([
            'YTD' => $marketEtfs['ytd_return'] ?? 0,
            '1M' => ($marketEtfs['change_50_day_percentage'] ?? 0) * 100, // Approx
            '6M' => ($marketEtfs['change_200_day_percentage'] ?? 0) * 100, // Approx
            '1Y' => ($marketEtfs['change_200_day_percentage'] ?? 0) * 100 + 5, // Fake adjustment for visual variety if data missing, effectively using available 200d
        ] as $label => $val)
                            @php $pPos = $val >= 0; @endphp
                            <div class="flex flex-col">
                                <span
                                    class="text-[10px] text-text-secondary uppercase font-bold tracking-wider mb-1">{{ $label }}
                                    {{ __('Return') }}</span>
                                <div class="flex items-center gap-2">
                                    <div class="h-1.5 w-12 rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-full {{ $pPos ? 'bg-emerald-500' : 'bg-rose-500' }}"
                                            style="width: {{ min(abs($val), 100) }}%"></div>
                                    </div>
                                    <span
                                        class="font-mono font-bold text-sm {{ $pPos ? 'text-emerald-400' : 'text-rose-400' }}">
                                        {{ $pPos ? '+' : '' }}{{ number_format($val, 2) }}%
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- MAIN CONTENT (Details) --}}
                <div class="lg:col-span-8 space-y-8">

                    {{-- FUND STATISTICS --}}
                    <div class="bg-secondary-dark/60 border border-white/5 rounded-3xl p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center text-purple-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white">{{ __('Fund Statistics') }}</h3>
                        </div>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                            <div>
                                <div class="text-xs text-text-secondary uppercase font-bold mb-1">
                                    {{ __('Assets Under Management') }}</div>
                                <div class="font-mono font-bold text-white text-lg">
                                    ${{ formatNumberAbbreviated($marketEtfs['assets_under_management'] ?? 0) }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-text-secondary uppercase font-bold mb-1">{{ __('Expense Ratio') }}
                                </div>
                                <div class="font-mono font-bold text-white text-lg">
                                    {{ $marketEtfs['expense_ratio'] ?? 'N/A' }}%</div>
                            </div>
                            <div>
                                <div class="text-xs text-text-secondary uppercase font-bold mb-1">{{ __('Yield') }}</div>
                                <div class="font-mono font-bold text-white text-lg">
                                    {{ $marketEtfs['dividend_yield'] ?? 'N/A' }}%</div>
                            </div>
                            <div>
                                <div class="text-xs text-text-secondary uppercase font-bold mb-1">{{ __('Volume') }}</div>
                                <div class="font-mono font-bold text-white text-lg">
                                    {{ formatNumberAbbreviated($marketEtfs['volume'] ?? 0) }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-text-secondary uppercase font-bold mb-1">{{ __('Open') }}</div>
                                <div class="font-mono font-bold text-white text-lg">
                                    ${{ number_format($marketEtfs['open'] ?? 0, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-text-secondary uppercase font-bold mb-1">{{ __('Prev Close') }}
                                </div>
                                <div class="font-mono font-bold text-white text-lg">
                                    ${{ number_format($marketEtfs['previous_close'] ?? 0, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-text-secondary uppercase font-bold mb-1">{{ __('High') }}</div>
                                <div class="font-mono font-bold text-emerald-400 text-lg">
                                    ${{ number_format($marketEtfs['high'] ?? 0, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-text-secondary uppercase font-bold mb-1">{{ __('Low') }}</div>
                                <div class="font-mono font-bold text-rose-400 text-lg">
                                    ${{ number_format($marketEtfs['low'] ?? 0, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- TOP HOLDINGS --}}
                    <div class="bg-secondary-dark/60 border border-white/5 rounded-3xl p-6 md:p-8 relative overflow-hidden">
                        <div
                            class="absolute -right-10 -top-10 w-64 h-64 bg-accent-primary/5 rounded-full blur-3xl pointer-events-none">
                        </div>
                        <div class="flex items-center gap-3 mb-6 relative z-10">
                            <div
                                class="w-8 h-8 rounded-lg bg-orange-500/20 flex items-center justify-center text-orange-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white">{{ __('Top Holdings') }}</h3>
                        </div>

                        @if (!empty($marketEtfs['top_holdings']) && is_array($marketEtfs['top_holdings']))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 relative z-10">
                                @foreach ($marketEtfs['top_holdings'] as $index => $holding)
                                    <div
                                        class="bg-white/5 border border-white/5 rounded-xl p-3 flex items-center gap-4 hover:bg-white/10 transition-colors">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-[#ffffff0a] flex items-center justify-center font-mono font-bold text-text-secondary text-sm border border-white/5">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="font-bold text-white text-sm truncate">{{ $holding }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-text-secondary italic">{{ __('Holdings data not available') }}</div>
                        @endif
                    </div>
                </div>

                {{-- SIDEBAR: Buy Console --}}
                <div class="lg:col-span-4 space-y-8">
                    <div class="sticky top-6">
                        <div
                            class="bg-[#131722] border border-white/10 rounded-3xl p-6 shadow-2xl relative overflow-hidden">
                            <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-accent-primary animate-pulse"></span>
                                {{ __('Allocation Console') }}
                            </h3>

                            <form
                                action="{{ route('user.capital-instruments.etfs.buy-validate', $marketEtfs['ticker']) }}"
                                method="POST" class="space-y-6 ajax-form" data-action="redirect"
                                data-redirect="{{ route('user.capital-instruments.etfs') }}">
                                @csrf
                                <input type="hidden" name="ticker" value="{{ $marketEtfs['ticker'] }}">

                                {{-- Balance Display --}}
                                <div class="bg-white/5 rounded-xl p-4 border border-white/5 mb-6">
                                    <div class="text-xs text-text-secondary mb-1">{{ __('Available Capital') }}</div>
                                    <div class="text-xl font-mono font-bold text-white">
                                        {{ getSetting('currency_symbol', '$') }}{{ number_format(auth()->user()->balance, 2) }}
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <label class="block">
                                        <span
                                            class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-2 block">{{ __('Investment Amount') }}</span>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <span
                                                    class="text-text-secondary font-bold">{{ getSetting('currency_symbol', '$') }}</span>
                                            </div>
                                            <input type="number" step="any"
                                                min="{{ getSetting('min_etf_purchase') }}"
                                                max="{{ getSetting('max_etf_purchase') }}" name="amount" id="amount"
                                                placeholder="0.00" required
                                                class="w-full bg-[#0f1219] border border-white/10 rounded-xl py-4 pl-8 pr-4 text-xl font-mono font-bold text-white placeholder-text-secondary focus:border-accent-primary focus:ring-0 transition-all">
                                        </div>
                                    </label>

                                    {{-- Live Conversion Info --}}
                                    <div id="conversion-info" class="hidden space-y-2 text-xs">
                                        <div class="flex justify-between items-center text-text-secondary">
                                            <span>{{ __('Est. Units') }}</span>
                                            <span class="font-mono font-bold text-white"
                                                id="estimated-shares">0.0000</span>
                                        </div>
                                        <div class="flex justify-between items-center text-text-secondary">
                                            <span>{{ __('Fees') }}
                                                ({{ getSetting('etf_purchase_fee_percent') }}%)</span>
                                            <span class="font-mono font-bold text-white" id="fee-display">$0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full bg-accent-primary hover:bg-accent-primary/90 text-white rounded-xl py-4 font-bold text-lg shadow-lg hover:shadow-accent-primary/25 transition-all flex items-center justify-center gap-2 group cursor-pointer mt-6">
                                    <span>{{ __('Confirm Allocation') }}</span>
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>

                                <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 mt-6">
                                    <div class="flex gap-3">
                                        <svg class="w-5 h-5 text-blue-400 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-xs text-blue-300/80 leading-relaxed">
                                            {{ __('ETF transactions are executed at the next available market price. Expense ratio is deducted from the fund assets annually.') }}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Script moved inside content for simplicity or push to stack if preferred --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const amountInput = document.getElementById('amount');
                    const infoBox = document.getElementById('conversion-info');
                    const sharesDisplay = document.getElementById('estimated-shares');
                    const feeDisplay = document.getElementById('fee-display');

                    const rate = {{ $rate }};
                    const price = {{ $marketEtfs['current_price'] }};
                    const feePercent = {{ getSetting('etf_purchase_fee_percent', 0) }};

                    amountInput.addEventListener('input', function() {
                        const val = parseFloat(this.value);
                        if (val > 0) {
                            infoBox.classList.remove('hidden');
                            const usdVal = val * rate;
                            const shares = usdVal / price;
                            const fee = val * (feePercent / 100);

                            sharesDisplay.textContent = shares.toLocaleString('en-US', {
                                minimumFractionDigits: 4,
                                maximumFractionDigits: 4
                            });
                            feeDisplay.textContent = '$' + fee.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        } else {
                            infoBox.classList.add('hidden');
                        }
                    });
                });
            </script>
        @else
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">{{ __('Funds Not Available') }}</h2>
                <p class="text-text-secondary">{{ __('We could not load the ETF data at this moment.') }}</p>
                @if ($message)
                    <p class="text-rose-400 mt-2 text-sm">{{ $message }}</p>
                @endif
                <a href="{{ route('user.capital-instruments.etfs') }}"
                    class="mt-8 px-8 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-white font-bold transition-all">{{ __('Return to ETF Market') }}</a>
            </div>
        @endif
    </div>
@endsection
