@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div class="space-y-8">
        {{-- Message  if module is disabled --}}
        @if (!moduleEnabled('etf_module'))
            <div
                class="relative z-10 p-8 flex flex-col items-center justify-center text-center h-[300px] bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden backdrop-blur-xl">
                <div
                    class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-500" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">
                    {{ __('ETFs module disabled') }}</h4>
                <p class="text-[10px] text-slate-500 max-w-[200px] mb-6 font-medium leading-relaxed italic">
                    {{ __('ETFs module is disabled. Please enable the ETFs module in settings to initialize.') }}
                </p>
                <a href="{{ route('admin.settings.modules.index') }}"
                    class="px-5 py-2 rounded-xl bg-white/5 border border-white/10 text-[9px] font-black text-white uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all active:scale-95">
                    {{ __('Settings') }}
                </a>
            </div>
        @endif

        @if (moduleEnabled('etf_module'))
            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6" id="stats-container">
                {{-- Total Buy Volume --}}
                <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-black text-white leading-none" id="stat-total-buy">
                            {{ showAmount($stats['total_buy'] * $exchange_rate) }}</div>
                        <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                            {{ __('Total Buy Volume') }}</div>
                    </div>
                </div>

                {{-- Total Sell Volume --}}
                <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-rose-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 8v8m-4-5l4-1 4 1m-8 8v-8m4-5l4-1 4 1" />
                            </svg>
                        </div>
                        <div class="text-2xl font-black text-white leading-none" id="stat-total-sell">
                            {{ showAmount($stats['total_sell'] * $exchange_rate) }}</div>
                        <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                            {{ __('Total Sell Volume') }}</div>
                    </div>
                </div>

                {{-- Total Trades --}}
                <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-black text-white leading-none" id="stat-total-trades">
                            {{ number_format($stats['total_trades']) }}</div>
                        <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                            {{ __('Total Trades Executed') }}</div>
                    </div>
                </div>

                {{-- Trades Today --}}
                <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-black text-white leading-none" id="stat-trades-today">
                            {{ number_format($stats['trades_today']) }}</div>
                        <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                            {{ __('Trades Today') }}</div>
                    </div>
                </div>

                {{-- Unique Tickers --}}
                <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-violet-500/10 flex items-center justify-center text-violet-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                        </div>
                        <div class="text-2xl font-black text-white leading-none" id="stat-unique-tickers">
                            {{ number_format($stats['unique_tickers']) }}</div>
                        <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                            {{ __('Unique Assets Traded') }}</div>
                    </div>
                </div>
            </div>

            {{-- Graph and chart --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Volume Trend (2/3 width) --}}
                <div
                    class="lg:col-span-2 relative bg-secondary border border-white/5 rounded-3xl overflow-hidden hover:border-white/10 transition-colors">
                    <div
                        class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                    </div>
                    <div
                        class="absolute top-0 left-0 -mt-10 -ml-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none z-0">
                    </div>

                    <div class="relative z-10 p-6">
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                            <div>
                                <div class="text-sm font-bold text-white tracking-wide">{{ __('Trade Volume Trend') }}
                                </div>
                                <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                                    {{ __('Daily buy/sell execution volume') }} · {{ getSetting('currency') }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <div
                                    class="flex items-center gap-1 bg-white/[0.03] border border-white/[0.06] rounded-lg p-1">
                                    @foreach (['7d' => '7D', '30d' => '30D', '1y' => '1Y', 'ytd' => 'YTD'] as $key => $label)
                                        <button data-period="{{ $key }}"
                                            class="graph-period-btn cursor-pointer px-2.5 py-1 rounded-md text-[9px] font-bold uppercase tracking-wider transition-all {{ $key === '7d' ? 'bg-white/10 text-white' : 'text-slate-500 hover:text-slate-300' }}">
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div id="graph-legend" class="flex items-center gap-4 mb-4 flex-wrap"></div>

                        <div class="relative h-64">
                            <canvas id="volumeTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Ticker Distribution (1/3 width) --}}
                <div
                    class="relative bg-secondary border border-white/5 rounded-3xl overflow-hidden hover:border-white/10 transition-colors">
                    <div
                        class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                    </div>
                    <div
                        class="absolute bottom-0 right-0 -mb-10 -mr-10 w-40 h-40 bg-violet-500/15 rounded-full blur-2xl pointer-events-none z-0">
                    </div>

                    <div class="relative z-10 p-6 h-full flex flex-col">
                        <div class="mb-5">
                            <div class="text-sm font-bold text-white tracking-wide">{{ __('Asset Activity') }}</div>
                            <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                                {{ __('Top 10 most traded tickers') }}</p>
                        </div>

                        <div class="relative flex-1" style="min-height: 250px;">
                            <canvas id="tickerChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Ledger & Filters --}}
            <div id="history-wrapper"
                class="bg-secondary border border-white/5 rounded-[2rem] overflow-hidden shadow-2xl relative">
                {{-- Loading Spinner Overlay --}}
                <div id="loading-spinner"
                    class="hidden absolute inset-0 bg-secondary/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-opacity duration-300">
                    <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin">
                    </div>
                    <p class="mt-3 text-text-secondary text-xs font-bold uppercase tracking-widest">
                        {{ __('Retrieving ETF Ledger Logs...') }}</p>
                </div>

                {{-- Filters & Search Header --}}
                <div class="bg-secondary/30 border-b border-white/5 p-4 lg:p-6 relative">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-text-secondary border border-white/10 shadow-inner">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-bold tracking-tight">{{ __('Order History') }}</h4>
                                <p
                                    class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mt-0.5 opacity-50">
                                    {{ __('Complete log of all ETF purchase and liquidation events') }}</p>
                            </div>
                        </div>

                        <form id="filter-form" method="GET" action="{{ route('admin.etfs.history') }}"
                            class="flex-1 max-w-4xl flex flex-wrap gap-3 lg:justify-end">
                            @if (request('search') || request('type'))
                                <button type="button" data-url="{{ route('admin.etfs.history') }}"
                                    class="ajax-clear h-12 px-6 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 text-text-secondary hover:text-white flex items-center gap-2 transition-all font-bold text-xs uppercase tracking-widest shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    {{ __('Clear') }}
                                </button>
                            @endif
                            <div class="relative custom-dropdown" id="type-dropdown">
                                <input type="hidden" name="type" value="{{ request('type') }}">
                                <button type="button"
                                    class="h-12 bg-white/5 border border-white/10 rounded-2xl px-5 text-sm font-medium text-white hover:bg-white/10 transition-all flex items-center justify-between gap-3 min-w-[200px] dropdown-btn cursor-pointer">
                                    <span class="selected-label">
                                        @if (request('type') == 'buy')
                                            {{ __('Buy Orders') }}
                                        @elseif(request('type') == 'sell')
                                            {{ __('Sell Orders') }}
                                        @else
                                            {{ __('All Transactions') }}
                                        @endif
                                    </span>
                                    <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7">
                                        </path>
                                    </svg>
                                </button>
                                <div
                                    class="absolute top-full right-0 mt-2 w-full bg-[#0F172A] border border-white/10 rounded-2xl shadow-2xl z-[80] py-2 hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200 overflow-hidden">
                                    <div class="dropdown-option px-5 py-3 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                        data-value="">{{ __('All Transactions') }}</div>
                                    <div class="dropdown-option px-5 py-3 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                        data-value="buy">{{ __('Buy Orders') }}</div>
                                    <div class="dropdown-option px-5 py-3 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                        data-value="sell">{{ __('Sell Orders') }}</div>
                                </div>
                            </div>

                            <div class="relative flex-1 group min-w-[200px]">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="{{ __('Search ticker or user...') }}"
                                    class="w-full h-12 bg-white/5 border border-white/10 rounded-2xl px-5 text-sm font-medium text-white focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all outline-none placeholder:text-text-secondary/30">
                                <button type="submit"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-2 hover:bg-white/10 rounded-xl text-text-secondary transition-colors group-hover:text-accent-primary focus:outline-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="relative custom-dropdown" id="export-dropdown">
                                <button type="button"
                                    class="bg-[#1E293B] border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[120px] dropdown-btn cursor-pointer">
                                    <span class="selected-label">{{ __('Export') }}</span>
                                    <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7">
                                        </path>
                                    </svg>
                                </button>
                                <div
                                    class="absolute top-full right-0 mt-2 w-full lg:w-48 bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-[80] py-1 hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200">
                                    <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                        data-export="csv">CSV</div>
                                    <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                        data-export="sql">SQL</div>
                                    <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                        data-export="pdf">PDF</div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="table-content" class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02]">
                                <th class="px-8 py-6 text-left">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('User') }}</span>
                                </th>
                                <th class="px-8 py-6 text-left">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Asset') }}</span>
                                </th>
                                <th class="px-8 py-6 text-center">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Type') }}</span>
                                </th>
                                <th class="px-8 py-6 text-right">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Shares') }}</span>
                                </th>
                                <th class="px-8 py-6 text-right">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Price') }}</span>
                                </th>
                                <th class="px-8 py-6 text-right">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Total') }}</span>
                                </th>
                                <th class="px-8 py-6 text-right">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Timestamp') }}</span>
                                </th>
                                <th class="px-8 py-6 text-right border-l border-white/5">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Action') }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($history as $item)
                                <tr class="group hover:bg-white/[0.01] transition-colors relative">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            @if ($item->user->photo)
                                                <img src="{{ asset('storage/profile/' . $item->user->photo) }}"
                                                    class="w-8 h-8 rounded-full border border-white/10">
                                            @else
                                                <div
                                                    class="w-8 h-8 rounded-full bg-white/5 text-text-secondary flex items-center justify-center font-bold text-[10px] uppercase border border-white/10">
                                                    {{ substr($item->user->first_name, 0, 1) }}{{ substr($item->user->last_name, 0, 1) }}
                                                </div>
                                            @endif
                                            <a href="{{ route('admin.users.detail', $item->user->id) }}"
                                                class="text-xs font-bold text-white hover:text-accent-primary transition-colors block">
                                                {{ $item->user->username }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center overflow-hidden shrink-0 group-hover:border-accent-primary transition-colors">
                                                <img src="https://financialmodelingprep.com/image-stock/{{ $item->ticker }}.png"
                                                    class="w-5 h-5 object-contain opacity-80 group-hover:opacity-100"
                                                    onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ $item->ticker }}&background=0F172A&color=fff&bold=true';">
                                            </div>
                                            <div
                                                class="px-2 py-1 rounded bg-white/5 border border-white/10 text-[10px] font-black text-white leading-none group-hover:text-accent-primary transition-colors">
                                                {{ $item->ticker }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        @if ($item->transaction_type == 'buy')
                                            <span
                                                class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">
                                                {{ __('BUY') }}
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 rounded-full bg-red-500/10 text-red-400 text-[10px] font-black uppercase tracking-widest border border-red-500/20">
                                                {{ __('SELL') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-right font-mono text-xs">
                                        <div class="text-white font-bold">{{ number_format($item->shares, 2) }}</div>
                                    </td>
                                    <td class="px-8 py-5 text-right font-mono text-xs">
                                        <div class="text-white font-bold">
                                            {{ showAmount($item->price_at_action * $exchange_rate) }}</div>
                                    </td>
                                    <td class="px-8 py-5 text-right font-mono text-xs">
                                        <div class="text-white font-black">
                                            {{ showAmount($item->amount * $exchange_rate) }}
                                        </div>
                                        @if ($item->fee_amount > 0)
                                            <div class="text-[9px] text-red-400/50 font-bold uppercase">
                                                {{ __('Fee:') }}
                                                {{ showAmount($item->fee_amount * $exchange_rate) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="text-xs text-white font-medium">
                                            {{ $item->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-[10px] text-text-secondary opacity-50">
                                            {{ $item->created_at->format('H:i:s') }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right border-l border-white/5">
                                        <button type="button"
                                            class="open-delete-modal p-2 rounded-lg bg-red-500/5 text-red-400/50 hover:bg-red-500 hover:text-white transition-all cursor-pointer"
                                            data-id="{{ $item->id }}" title="{{ __('Delete Log') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-8 py-20 text-center">
                                        <div
                                            class="w-16 h-16 rounded-3xl bg-white/[0.02] border border-white/5 flex items-center justify-center text-text-secondary/20 mx-auto mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h5 class="text-white font-bold">{{ __('No History Found') }}</h5>
                                        <p class="text-text-secondary text-xs mt-1">
                                            {{ __('No ETF transactions have been recorded yet.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="pagination-wrapper" class="ajax-pagination">
                    @if ($history->hasPages())
                        <div class="px-8 py-6 bg-white/[0.01] border-t border-white/5 ajax-pagination">
                            {{ $history->links('templates.bento.blades.partials.pagination') }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Delete Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md modal-close"></div>
        <div
            class="bg-secondary border border-white/10 rounded-3xl relative z-10 w-full max-w-sm overflow-hidden animate-in zoom-in duration-300">
            <div class="p-8 text-center pt-10">
                <div
                    class="w-16 h-16 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center mx-auto mb-4 border border-red-500/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">{{ __('Delete Order Log?') }}</h3>
                <p class="text-text-secondary text-xs leading-relaxed">
                    {{ __('Are you sure you want to delete this historical record? This will not affect the user\'s current holdings.') }}
                </p>
            </div>
            <div class="flex gap-4 p-8 pt-0">
                <button type="button"
                    class="flex-1 h-12 rounded-xl border border-white/10 text-white font-bold hover:bg-white/5 transition-all modal-close cursor-pointer text-xs">{{ __('Cancel') }}</button>
                <button type="button" id="confirm-delete-btn"
                    class="flex-1 h-12 rounded-xl bg-red-600 hover:bg-red-700 text-white font-black transition-all cursor-pointer text-xs uppercase tracking-widest flex items-center justify-center gap-2">
                    <span class="btn-text">{{ __('Delete') }}</span>
                    <svg class="loading-spinner hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Export Modal --}}
    <div id="export-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity modal-close cursor-pointer">
            </div>
            <div
                class="relative w-full max-w-md transform rounded-3xl bg-secondary border border-white/10 p-8 text-left shadow-2xl transition-all">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none rounded-3xl">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Export Settings') }}</h3>
                            <p class="text-xs text-slate-500 mt-1 uppercase tracking-widest font-mono">
                                {{ __('Select columns to include') }}</p>
                        </div>
                        <button type="button"
                            class="text-slate-500 hover:text-white transition-colors modal-close cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 font-mono ml-1">
                                {{ __('Select Columns') }}
                            </p>
                            <div class="grid grid-cols-2 gap-3" id="export-columns-container">
                                @php
                                    $export_columns = [
                                        'username' => ['label' => 'User', 'default' => true],
                                        'ticker' => ['label' => 'Ticker', 'default' => true],
                                        'type' => ['label' => 'Type', 'default' => true],
                                        'shares' => ['label' => 'Shares', 'default' => true],
                                        'price' => ['label' => 'Price', 'default' => true],
                                        'amount' => ['label' => 'Total', 'default' => true],
                                        'fee' => ['label' => 'Fee', 'default' => true],
                                        'created_at' => ['label' => 'Date', 'default' => true],
                                    ];
                                @endphp
                                @foreach ($export_columns as $key => $col)
                                    <label
                                        class="flex items-center gap-3 p-3 rounded-xl border border-white/5 bg-white/5 hover:border-accent-primary/50 transition-all cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="export_cols[]" value="{{ $key }}"
                                                {{ $col['default'] ? 'checked' : '' }}
                                                class="w-5 h-5 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary focus:ring-offset-0 cursor-pointer">
                                        </div>
                                        <span
                                            class="text-sm font-medium text-slate-300 group-hover:text-white transition-colors">
                                            {{ __($col['label']) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="button" id="execute-export"
                            class="w-full h-12 bg-accent-primary hover:bg-accent-primary/90 text-white rounded-2xl font-bold text-sm uppercase tracking-widest transition-all shadow-lg shadow-accent-primary/20 flex items-center justify-center gap-2 group cursor-pointer">
                            <span>{{ __('Download Report') }}</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        (function() {
            'use strict';

            const GRAPH_DATA = @json($graph_data);
            const TICKER_DATA = @json($ticker_data);
            const CURRENCY_SYMBOL = "{{ getSetting('currency_symbol') }}";
            const DECIMAL_PLACES = {{ getSetting('decimal_places') }};
            const EXCHANGE_RATE = {{ $exchange_rate }};

            Chart.defaults.color = 'rgba(148,163,184,0.7)';
            Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
            Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui';
            Chart.defaults.font.size = 10;

            const tooltipOptions = {
                backgroundColor: 'rgba(15,23,42,0.95)',
                borderColor: 'rgba(255,255,255,0.08)',
                borderWidth: 1,
                padding: 10,
                titleFont: {
                    size: 10,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 10
                },
            };

            // ── Volume Trend Chart ───────────────────────────────────────────────────
            const volumeCtx = document.getElementById('volumeTrendChart').getContext('2d');
            const volumeChart = new Chart(volumeCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: []
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: tooltipOptions
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255,255,255,0.03)'
                            },
                            ticks: {
                                maxTicksLimit: 8,
                                maxRotation: 0
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(255,255,255,0.04)'
                            },
                            ticks: {
                                maxTicksLimit: 5
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            // ── Ticker Distribution Chart (Horizontal Bar) ─────────────────────────
            const tickerCtx = document.getElementById('tickerChart').getContext('2d');
            const tickerChart = new Chart(tickerCtx, {
                type: 'bar',
                data: {
                    labels: TICKER_DATA.map(d => d.ticker),
                    datasets: [{
                        label: 'Total Orders',
                        data: TICKER_DATA.map(d => d.count),
                        backgroundColor: 'rgba(99, 102, 241, 0.4)',
                        borderColor: '#6366f1',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: tooltipOptions
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255,255,255,0.03)'
                            },
                            ticks: {
                                precision: 0
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            let activePeriod = '7d';

            function updateVolumeChart() {
                const periodData = GRAPH_DATA[activePeriod];
                if (!periodData) return;

                const labels = periodData.buy.labels;
                volumeChart.data.labels = labels;
                volumeChart.data.datasets = [{
                        label: 'Buy Volume',
                        data: periodData.buy.data,
                        borderColor: '#10b881',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: labels.length <= 15 ? 3 : 0,
                        borderWidth: 2
                    },
                    {
                        label: 'Sell Volume',
                        data: periodData.sell.data,
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244, 63, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: labels.length <= 15 ? 3 : 0,
                        borderWidth: 2
                    }
                ];
                volumeChart.update('active');

                const legendEl = document.getElementById('graph-legend');
                legendEl.innerHTML = `
                    <span class="flex items-center gap-1.5 text-[10px] text-slate-400">
                        <span class="w-2 h-2 rounded-full bg-[#10b881]"></span> Buy Volume
                    </span>
                    <span class="flex items-center gap-1.5 text-[10px] text-slate-400">
                        <span class="w-2 h-2 rounded-full bg-[#f43f5e]"></span> Sell Volume
                    </span>
                `;
            }

            $('.graph-period-btn').on('click', function() {
                activePeriod = $(this).data('period');
                $('.graph-period-btn').removeClass('bg-white/10 text-white').addClass('text-slate-500');
                $(this).removeClass('text-slate-500').addClass('bg-white/10 text-white');
                updateVolumeChart();
            });

            updateVolumeChart();

            // ── AJAX Table Loading ──────────────────────────────────────────────
            function loadTable(url = null) {
                const form = $('#filter-form');
                const baseUrl = form.attr('action');
                const formData = form.serialize();
                const requestUrl = url || baseUrl + (formData ? '?' + formData : '');

                $('#loading-spinner').removeClass('hidden').addClass('flex');
                $('#history-wrapper').css('opacity', '0.5');

                $.ajax({
                    url: requestUrl,
                    type: 'GET',
                    success: function(data) {
                        $('#history-wrapper').html($(data).find('#history-wrapper').html());
                        window.history.pushState({}, '', requestUrl);
                    },
                    error: function() {
                        if (typeof toastNotification === 'function') {
                            toastNotification('{{ __('Error loading content.') }}', 'error');
                        }
                    },
                    complete: function() {
                        $('#loading-spinner').addClass('hidden').removeClass('flex');
                        $('#history-wrapper').css('opacity', '1');
                    }
                });
            }

            // ── Event Handlers ─────────────────────────────────────────────────

            // Search/Filter
            $(document).on('submit', '#filter-form', function(e) {
                e.preventDefault();
                loadTable();
            });

            // AJAX Pagination & Filters
            $(document).on('click', '.ajax-pagination a, .ajax-clear', function(e) {
                e.preventDefault();
                const url = $(this).attr('href') || $(this).data('url');
                if (url) loadTable(url);
            });

            // Type Filter Logic
            $(document).on('click', '#type-dropdown .dropdown-option', function() {
                const val = $(this).data('value');
                const label = $(this).text();
                const $dropdown = $(this).closest('.custom-dropdown');

                $dropdown.find('input[name="type"]').val(val);
                $dropdown.find('.selected-label').text(label);
                $dropdown.find('.dropdown-menu').addClass('hidden');
                $dropdown.find('.dropdown-icon').removeClass('rotate-180');

                loadTable();
            });

            // Dropdown & Export
            $(document).on('click', '.custom-dropdown .dropdown-btn', function(e) {
                e.stopPropagation();
                const $dropdown = $(this).closest('.custom-dropdown');
                $('.custom-dropdown').not($dropdown).find('.dropdown-menu').addClass('hidden');
                $dropdown.find('.dropdown-menu').toggleClass('hidden');
                $(this).find('.dropdown-icon').toggleClass('rotate-180');
            });

            $(document).on('click', function() {
                $('.dropdown-menu').addClass('hidden');
                $('.dropdown-icon').removeClass('rotate-180');
            });

            let currentExportType = '';
            $(document).on('click', '.dropdown-option[data-export]', function() {
                currentExportType = $(this).data('export');
                $(this).closest('.dropdown-menu').addClass('hidden');
                $('#export-modal').removeClass('hidden');
            });

            $(document).on('click', '.modal-close', function() {
                $(this).closest('.fixed.inset-0').addClass('hidden');
            });

            $(document).on('click', '#execute-export', function() {
                const columns = [];
                $('input[name="export_cols[]"]:checked').each(function() {
                    columns.push($(this).val());
                });

                if (columns.length === 0) {
                    toastNotification('{{ __('Please select at least one column.') }}', 'error');
                    return;
                }

                const params = new URLSearchParams($('#filter-form').serialize());
                params.set('export', currentExportType);
                params.set('columns', columns.join(','));
                $('#export-modal').addClass('hidden');
                window.location.href = $('#filter-form').attr('action') + '?' + params.toString();
            });

            // Delete Logic
            let deleteId = null;
            $(document).on('click', '.open-delete-modal', function() {
                deleteId = $(this).data('id');
                $('#delete-modal').removeClass('hidden').addClass('flex');
            });

            $(document).on('click', '#confirm-delete-btn', function() {
                if (!deleteId) return;
                const btn = $(this);
                const btnText = btn.find('.btn-text');
                const spinner = btn.find('.loading-spinner');

                btn.prop('disabled', true).addClass('opacity-50');
                btnText.text('{{ __('Deleting...') }}');
                spinner.removeClass('hidden');

                $.ajax({
                    url: "{{ route('admin.etfs.history.delete') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: deleteId
                    },
                    success: function(response) {
                        if (response.success || response.status === 'success') {
                            $('#delete-modal').addClass('hidden').removeClass('flex');
                            loadTable(window.location.href);
                            if (typeof toastNotification === 'function') toastNotification(response
                                .message, 'success');
                        } else {
                            if (typeof toastNotification === 'function') toastNotification(response
                                .message, 'error');
                        }
                    },
                    error: function(xhr) {
                        if (typeof toastNotification === 'function') toastNotification(xhr
                            .responseJSON?.message || 'Error occurred.', 'error');
                    },
                    complete: function() {
                        btn.prop('disabled', false).removeClass('opacity-50');
                        btnText.text('{{ __('Delete') }}');
                        spinner.addClass('hidden');
                    }
                });
            });
        })();
    </script>
@endpush
