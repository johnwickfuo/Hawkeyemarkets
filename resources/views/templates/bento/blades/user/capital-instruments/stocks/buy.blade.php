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
        {{-- Breadcrumb / Back --}}
        <div>
            <a href="{{ route('user.capital-instruments.stocks') }}"
                class="inline-flex items-center gap-2 text-text-secondary hover:text-white transition-colors text-sm font-bold">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                {{ __('Back to Stocks') }}
            </a>
        </div>

        @if (!empty($marketStock))
            {{-- Header Card --}}
            <div
                class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-3xl p-6 md:p-8 relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/10 to-transparent">
                </div>

                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative z-10">
                    <div class="flex items-center gap-5">
                        <div
                            class="w-20 h-20 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center p-2 shadow-xl">
                            <img loading="lazy" decoding="async" src="{{ $marketStock['public_png_logo_url'] ?? '' }}" alt="{{ $marketStock['name'] }}"
                                class="w-full h-full object-contain rounded-lg">
                        </div>
                        <div>
                            <h1 class="font-heading font-bold text-2xl md:text-3xl text-white tracking-tight">
                                {{ $marketStock['name'] }}</h1>
                            <div class="flex flex-wrap items-center gap-y-2 gap-x-3 mt-2">
                                <span
                                    class="text-sm font-bold text-text-secondary bg-white/5 px-2 py-0.5 rounded border border-white/5">{{ $marketStock['ticker'] }}</span>
                                <span class="text-sm font-medium text-text-secondary">{{ $marketStock['sector'] }}</span>
                                @if (isset($marketStock['cik']))
                                    <div class="hidden sm:block h-4 w-[1px] bg-white/10 mx-1"></div>
                                    <a href="https://www.sec.gov/cgi-bin/browse-edgar?action=getcompany&CIK={{ $marketStock['cik'] }}&owner=include&count=40&hidefilings=0"
                                        target="_blank"
                                        class="flex items-center gap-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 active:bg-blue-700 transition-all px-3 py-1.5 rounded-lg shadow-lg shadow-blue-900/20 hover:shadow-blue-900/40 border border-blue-500/50 group/sec w-full sm:w-auto justify-center sm:justify-start"
                                        title="{{ __('Verify SEC Filing') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

                    <div class="text-left md:text-right">
                        <div class="text-sm font-bold text-text-secondary uppercase tracking-wider mb-1">
                            {{ __('Current Price') }}</div>
                        <div class="font-mono font-bold text-3xl md:text-4xl text-white tracking-tight">
                            ${{ number_format($marketStock['current_price'], 2) }}</div>

                        @php $isPos = ($marketStock['change_1d'] ?? 0) >= 0; @endphp
                        <div class="flex items-center md:justify-end gap-2 mt-1">
                            <span class="text-sm font-bold {{ $isPos ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $isPos ? '+' : '' }}{{ number_format($marketStock['change_1d'] ?? 0, 2) }}
                                ({{ $marketStock['change_1d_percentage'] ?? '0%' }})
                            </span>
                            <span class="text-xs text-text-secondary">{{ __('Today') }}</span>
                        </div>

                        {{-- show the current price in wesbite currency if its not usd --}}
                        @if ($website_currency != $market_currency)
                            <div class="text-sm font-bold text-text-secondary uppercase tracking-wider mb-1">
                                {{ getSetting('currency_symbol', getSetting('currency', 'USD')) }}{{ number_format($marketStock['current_price'] * $rate, getSetting('decimal_places', 2)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Left Column: Stats & Info --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Price Performance (New) --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- 1 Day --}}
                        <div
                            class="bg-secondary-dark/40 border border-white/5 rounded-2xl p-4 flex flex-col justify-center">
                            <div class="text-xs text-text-secondary font-bold uppercase mb-1">{{ __('1 Day Change') }}
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span
                                    class="font-mono font-bold text-lg {{ ($marketStock['change_1d'] ?? 0) >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ ($marketStock['change_1d'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($marketStock['change_1d'] ?? 0, 2) }}
                                </span>
                                <span
                                    class="text-xs font-bold {{ ($marketStock['change_1d_percentage'] ?? 0) >= 0 ? 'text-emerald-400' : 'text-rose-400' }} bg-white/5 px-1.5 py-0.5 rounded">
                                    {{ ($marketStock['change_1d_percentage'] ?? 0) >= 0 ? '+' : '' }}{{ round($marketStock['change_1d_percentage'] ?? 0, 2) }}%
                                </span>
                            </div>
                        </div>
                        {{-- 50 Day --}}
                        <div
                            class="bg-secondary-dark/40 border border-white/5 rounded-2xl p-4 flex flex-col justify-center">
                            <div class="text-xs text-text-secondary font-bold uppercase mb-1">{{ __('50 Day Change') }}
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span
                                    class="font-mono font-bold text-lg {{ ($marketStock['change_50_day'] ?? 0) >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ ($marketStock['change_50_day'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($marketStock['change_50_day'] ?? 0, 2) }}
                                </span>
                                <span
                                    class="text-xs font-bold {{ ($marketStock['change_50_day_percentage'] ?? 0) >= 0 ? 'text-emerald-400' : 'text-rose-400' }} bg-white/5 px-1.5 py-0.5 rounded">
                                    {{ ($marketStock['change_50_day_percentage'] ?? 0) >= 0 ? '+' : '' }}{{ number_format(($marketStock['change_50_day_percentage'] ?? 0) * 100, 2) }}%
                                </span>
                            </div>
                        </div>
                        {{-- 200 Day --}}
                        <div
                            class="bg-secondary-dark/40 border border-white/5 rounded-2xl p-4 flex flex-col justify-center">
                            <div class="text-xs text-text-secondary font-bold uppercase mb-1">{{ __('200 Day Change') }}
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span
                                    class="font-mono font-bold text-lg {{ ($marketStock['change_200_day'] ?? 0) >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                    {{ ($marketStock['change_200_day'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($marketStock['change_200_day'] ?? 0, 2) }}
                                </span>
                                <span
                                    class="text-xs font-bold {{ ($marketStock['change_200_day_percentage'] ?? 0) >= 0 ? 'text-emerald-400' : 'text-rose-400' }} bg-white/5 px-1.5 py-0.5 rounded">
                                    {{ ($marketStock['change_200_day_percentage'] ?? 0) >= 0 ? '+' : '' }}{{ number_format(($marketStock['change_200_day_percentage'] ?? 0) * 100, 2) }}%
                                </span>
                            </div>
                        </div>
                    </div>


                    {{-- Market Stats Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        {{-- Market Cap --}}
                        <div class="bg-secondary-dark/40 border border-white/5 rounded-2xl p-4">
                            <div class="text-xs text-text-secondary font-bold uppercase mb-1">{{ __('Market Cap') }}</div>
                            <div class="font-mono font-bold text-white text-lg truncate">
                                ${{ formatNumberAbbreviated($marketStock['market_cap'] ?? 0) }}</div>
                        </div>
                        {{-- Volume --}}
                        <div class="bg-secondary-dark/40 border border-white/5 rounded-2xl p-4">
                            <div class="text-xs text-text-secondary font-bold uppercase mb-1">{{ __('Volume') }}</div>
                            <div class="font-mono font-bold text-white text-lg truncate">
                                {{ formatNumberAbbreviated($marketStock['volume'] ?? 0) }}</div>
                        </div>
                        {{-- P/E Ratio --}}
                        <div class="bg-secondary-dark/40 border border-white/5 rounded-2xl p-4">
                            <div class="text-xs text-text-secondary font-bold uppercase mb-1">{{ __('P/E Ratio') }}</div>
                            <div class="font-mono font-bold text-white text-lg">
                                {{ number_format($marketStock['p_e_ratio'] ?? 0, 2) }}
                            </div>
                        </div>
                        {{-- Dividend Yield --}}
                        <div class="bg-secondary-dark/40 border border-white/5 rounded-2xl p-4">
                            <div class="text-xs text-text-secondary font-bold uppercase mb-1">{{ __('Div Yield') }}</div>
                            <div class="font-mono font-bold text-white text-lg">
                                {{ $marketStock['dividend_yield'] ?? 0 }}%
                            </div>
                        </div>

                        {{-- Open --}}
                        <div class="bg-secondary-dark/40 border border-white/5 rounded-2xl p-4">
                            <div class="text-xs text-text-secondary font-bold uppercase mb-1">{{ __('Open') }}</div>
                            <div class="font-mono font-bold text-white text-lg">
                                ${{ number_format($marketStock['open'] ?? 0, 2) }}</div>
                        </div>
                        {{-- Prev Close --}}
                        <div class="bg-secondary-dark/40 border border-white/5 rounded-2xl p-4">
                            <div class="text-xs text-text-secondary font-bold uppercase mb-1">{{ __('Prev Close') }}</div>
                            <div class="font-mono font-bold text-text-secondary text-lg">
                                ${{ number_format($marketStock['previous_close'] ?? 0, 2) }}</div>
                        </div>
                        {{-- High --}}
                        <div class="bg-secondary-dark/40 border border-white/5 rounded-2xl p-4">
                            <div class="text-xs text-text-secondary font-bold uppercase mb-1">{{ __('High (Day)') }}</div>
                            <div class="font-mono font-bold text-emerald-400 text-lg">
                                ${{ number_format($marketStock['high'] ?? 0, 2) }}</div>
                        </div>
                        {{-- Low --}}
                        <div class="bg-secondary-dark/40 border border-white/5 rounded-2xl p-4">
                            <div class="text-xs text-text-secondary font-bold uppercase mb-1">{{ __('Low (Day)') }}</div>
                            <div class="font-mono font-bold text-rose-400 text-lg">
                                ${{ number_format($marketStock['low'] ?? 0, 2) }}</div>
                        </div>
                    </div>

                    {{-- About --}}
                    <div class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-3xl p-6 md:p-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-white">{{ __('About') }} {{ $marketStock['name'] }}</h3>
                            @if (isset($marketStock['last_updated']))
                                <div class="text-[10px] text-text-secondary bg-white/5 px-2 py-1 rounded-lg">
                                    {{ __('Updated') }}:
                                    {{ \Carbon\Carbon::createFromTimestamp($marketStock['last_updated'])->diffForHumans() }}
                                </div>
                            @endif
                        </div>

                        <p class="text-text-secondary leading-relaxed bg-white/5 rounded-xl p-4 border border-white/5">
                            {{ $marketStock['description'] ?? __('No description available.') }}
                        </p>

                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                class="flex items-center justify-between p-3 rounded-lg bg-white/[0.02] border border-white/5">
                                <span class="text-sm text-text-secondary">{{ __('CEO') }}</span>
                                <span class="text-sm font-bold text-white">{{ $marketStock['ceo'] ?? 'N/A' }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-3 rounded-lg bg-white/[0.02] border border-white/5">
                                <span class="text-sm text-text-secondary">{{ __('Chairman') }}</span>
                                <span class="text-sm font-bold text-white">{{ $marketStock['chairman'] ?? 'N/A' }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-3 rounded-lg bg-white/[0.02] border border-white/5">
                                <span class="text-sm text-text-secondary">{{ __('Founded') }}</span>
                                <span class="text-sm font-bold text-white">{{ $marketStock['founded'] ?? 'N/A' }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-3 rounded-lg bg-white/[0.02] border border-white/5">
                                <span class="text-sm text-text-secondary">{{ __('Sector') }}</span>
                                <span class="text-sm font-bold text-white">{{ $marketStock['sector'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Column: Buy Form --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-6">
                        <div
                            class="bg-secondary-dark/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-2xl relative overflow-hidden group">
                            {{-- Glow Effect --}}
                            <div
                                class="absolute -top-10 -right-10 w-32 h-32 bg-accent-primary/20 blur-3xl rounded-full group-hover:bg-accent-primary/30 transition-colors">
                            </div>

                            <h3 class="text-xl font-bold text-white mb-6 relative z-10">{{ __('Buy Stock') }}</h3>

                            <form
                                action="{{ route('user.capital-instruments.stocks.buy-validate', $marketStock['ticker']) }}"
                                method="POST" class="space-y-6 relative z-10 ajax-form" data-action="redirect"
                                data-redirect="{{ route('user.capital-instruments.stocks') }}">
                                @csrf

                                <input type="hidden" name="ticker" value="{{ $marketStock['ticker'] }}">

                                <div class="space-y-4">
                                    <div>
                                        <div class="flex justify-between items-center">
                                            <label for="amount"
                                                class="text-sm font-bold text-text-secondary uppercase tracking-wider">
                                                {{ __('Amount') }} ({{ getSetting('currency', 'USD') }})
                                            </label>
                                            <div class="text-xs text-text-secondary">
                                                {{ __('Available Balance') }}: <span
                                                    class="font-bold text-white">{{ getSetting('currency_symbol', '$') }}{{ number_format(auth()->user()->balance, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="relative mt-2">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <span
                                                    class="text-text-secondary font-bold">{{ getSetting('currency_symbol', '$') }}</span>
                                            </div>
                                            <input type="number" step="any"
                                                min="{{ getSetting('min_stock_purchase') }}"
                                                max="{{ getSetting('max_stock_purchase') }}" name="amount"
                                                id="amount" placeholder="0.00" required
                                                class="w-full bg-[#0f1219] border border-white/10 rounded-xl py-4 pl-8 pr-4 text-xl font-mono font-bold text-white placeholder-text-secondary focus:border-accent-primary focus:ring-0 transition-all">
                                        </div>
                                    </div>

                                    {{-- Limits & Fees --}}
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="bg-white/5 border border-white/5 rounded-xl p-3">
                                            <div class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                                {{ __('Minimum Purchase') }}</div>
                                            <div class="font-mono font-bold text-white text-sm">
                                                {{ getSetting('currency_symbol', '$') }}{{ number_format(getSetting('min_stock_purchase'), getSetting('decimal_places')) }}
                                            </div>
                                        </div>
                                        <div class="bg-white/5 border border-white/5 rounded-xl p-3">
                                            <div class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                                {{ __('Maximum Purchase') }}</div>
                                            <div class="font-mono font-bold text-white text-sm">
                                                {{ getSetting('currency_symbol', '$') }}{{ number_format(getSetting('max_stock_purchase'), getSetting('decimal_places')) }}
                                            </div>
                                        </div>
                                        <div
                                            class="col-span-2 bg-white/5 border border-white/5 rounded-xl p-3 flex justify-between items-center">
                                            <div class="text-[10px] uppercase font-bold text-text-secondary">
                                                {{ __('Transaction Fee') }}</div>
                                            <div class="font-mono font-bold text-white text-sm">
                                                {{ getSetting('stock_purchase_fee_percent') }}%
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Live Conversion Info --}}
                                    <div id="conversion-info"
                                        class="hidden space-y-3 bg-white/5 border border-white/5 rounded-xl p-4">
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-text-secondary">{{ __('USD Equivalent') }}</span>
                                            <span class="font-mono font-bold text-white" id="usd-equivalent">$0.00</span>
                                        </div>
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-text-secondary">{{ __('Estimated Shares') }}</span>
                                            <span class="font-mono font-bold text-accent-primary"
                                                id="estimated-shares">0.0000</span>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const amountInput = document.getElementById('amount');
                                        const infoBox = document.getElementById('conversion-info');
                                        const usdDisplay = document.getElementById('usd-equivalent');
                                        const sharesDisplay = document.getElementById('estimated-shares');

                                        const rate = {{ $rate }}; // Website Currency -> USD Rate
                                        const stockPrice = {{ $marketStock['current_price'] }};

                                        amountInput.addEventListener('input', function() {
                                            const val = parseFloat(this.value);

                                            if (val > 0) {
                                                infoBox.classList.remove('hidden');

                                                // Calculate USD
                                                const usdVal = val * rate;
                                                usdDisplay.textContent = '$' + usdVal.toLocaleString('en-US', {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                });

                                                // Calculate Shares
                                                const shares = usdVal / stockPrice;
                                                sharesDisplay.textContent = shares.toLocaleString('en-US', {
                                                    minimumFractionDigits: 4,
                                                    maximumFractionDigits: 4
                                                });
                                            } else {
                                                infoBox.classList.add('hidden');
                                            }
                                        });
                                    });
                                </script>

                                {{-- Estimated Shares (Dynamic calc usually requires JS, keeping simple for now) --}}
                                <div
                                    class="bg-white/5 border border-white/5 rounded-xl p-4 flex justify-between items-center">
                                    <span
                                        class="text-sm text-text-secondary font-medium">{{ __('Price per Share') }}</span>
                                    <span
                                        class="font-mono font-bold text-white">${{ number_format($marketStock['current_price'], 2) }}</span>
                                </div>

                                {{-- Submit Button --}}
                                <button type="submit"
                                    class="w-full bg-accent-primary hover:bg-accent-primary/90 text-white rounded-xl py-4 font-bold text-lg shadow-lg hover:shadow-accent-primary/25 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 group/btn cursor-pointer">
                                    <span>{{ __('Buy Now') }}</span>
                                    <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                        <path d="m12 5 7 7-7 7" />
                                    </svg>
                                </button>

                                <p class="text-xs text-text-secondary text-center leading-relaxed">
                                    {{ __('By proceeding, you agree to the Terms of Service for Capital Instruments trading.') }}
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div
                class="flex flex-col items-center justify-center py-20 bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-3xl text-center px-4">
                <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-text-secondary" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        <line x1="11" y1="8" x2="11" y2="8"></line>
                        <line x1="8" y1="11" x2="8" y2="11"></line>
                    </svg>
                </div>
                @if ($message)
                    <div
                        class="bg-rose-500/10 border border-rose-500/20 rounded-xl max-w-md w-full mb-8 relative overflow-hidden text-center p-6">
                        <div class="absolute inset-0 bg-gradient-to-br from-rose-500/5 to-transparent pointer-events-none">
                        </div>
                        <div class="relative z-10 flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-rose-500/10 flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-rose-500 w-5 h-5" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-2">{{ __('Unable to Load Stock') }}</h3>
                            <p class="text-rose-200/70 text-sm leading-relaxed">
                                {{ $message }}
                            </p>
                        </div>
                    </div>
                @else
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">{{ __('Stock not found') }}</h2>
                    <p class="text-text-secondary max-w-md mb-8 leading-relaxed">
                        {{ __('The stock you are looking for does not exist or has been removed.') }}
                    </p>
                @endif

                <a href="{{ route('user.capital-instruments.stocks') }}"
                    class="bg-accent-primary hover:bg-accent-primary/90 text-white rounded-xl px-8 py-3 font-bold transition-all shadow-lg hover:shadow-accent-primary/25">
                    {{ __('Back to Stocks') }}
                </a>
            </div>
        @endif
    </div>
@endsection
