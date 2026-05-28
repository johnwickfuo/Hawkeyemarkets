@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div id="margin-positions-wrapper" class="space-y-8">

        {{-- Analytics Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-accent-primary/20 rounded-lg text-accent-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Total Open') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['total_positions']) }}</h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-emerald-500/20 rounded-lg text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Long Positions') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['long_count']) }}</h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-red-500/20 rounded-lg text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Short Positions') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['short_count']) }}</h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-blue-500/20 rounded-lg text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Total Margin') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['total_margin'], 2) }} <span
                                class="text-[10px] text-blue-400 uppercase">{{ $currency }}</span></h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div
                        class="p-3 {{ $stats['total_pnl'] >= 0 ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }} rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Unrealized PnL') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['total_pnl'], 2) }} <span
                                class="text-[10px] uppercase {{ $stats['total_pnl'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}">{{ $currency }}</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graph and Chart --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            {{-- Trend Graph (2/3 width) --}}
            <div
                class="lg:col-span-2 relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>
                <div
                    class="absolute top-0 left-0 -mt-10 -ml-10 w-48 h-48 bg-accent-primary/10 rounded-full blur-3xl pointer-events-none z-0">
                </div>

                <div class="relative z-10 p-6">
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                        <div>
                            <div id="graph-title" class="text-sm font-bold text-white tracking-wide">
                                {{ __('Position Growth Trend') }}
                            </div>
                            <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5"
                                id="graph-subtitle">
                                {{ __('Long vs Short Over Time') }} · {{ __('Open Interest Count') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            <div
                                class="flex items-center flex-wrap gap-1 bg-white/[0.03] border border-white/[0.06] rounded-lg p-1">
                                @foreach (['7d' => '7D', '30d' => '30D', '60d' => '60D', '90d' => '90D', '1y' => '1Y', 'ytd' => 'YTD'] as $key => $label)
                                    <button data-period="{{ $key }}"
                                        class="graph-period-btn cursor-pointer px-2.5 py-1 rounded-md text-[9px] font-bold uppercase tracking-wider transition-all
                                            {{ $key === '7d' ? 'bg-white/10 text-white' : 'text-slate-500 hover:text-slate-300' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div id="graph-legend" class="flex items-center gap-4 mb-4 flex-wrap"></div>

                    <div class="relative h-64">
                        <canvas id="positionTrendChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Side Distribution (1/3 width) --}}
            <div
                class="relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>
                <div
                    class="absolute bottom-0 right-0 -mb-10 -mr-10 w-40 h-40 bg-accent-primary/15 rounded-full blur-2xl pointer-events-none z-0">
                </div>

                <div class="relative z-10 p-6 h-full flex flex-col">
                    <div class="mb-5">
                        <div class="text-sm font-bold text-white tracking-wide">{{ __('Open Interest Distribution') }}
                        </div>
                        <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                            {{ __('Margin by Side') }}
                        </p>
                    </div>

                    <div class="relative flex-1 flex items-center justify-center p-4" style="min-height: 250px;">
                        <canvas id="sideDistributionChart" class="max-h-[220px]"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Loading Overlay --}}
        <div id="loading-spinner"
            class="fixed inset-0 z-[200] hidden items-center justify-center bg-slate-950/50 backdrop-blur-sm transition-all">
            <div class="flex flex-col items-center gap-4">
                <div class="relative w-16 h-16">
                    <div class="absolute inset-0 border-4 border-accent-primary/20 rounded-full"></div>
                    <div
                        class="absolute inset-0 border-4 border-accent-primary border-t-transparent rounded-full animate-spin">
                    </div>
                </div>
                <span
                    class="text-xs font-bold text-white uppercase tracking-widest animate-pulse">{{ __('Loading...') }}</span>
            </div>
        </div>

        {{-- Header & Stats --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}"
                    class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-text-secondary hover:text-white hover:bg-white/10 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight">{{ __('Open Positions') }}</h2>
                    <p class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-1 opacity-50">
                        {{ __('Monitor and manage active margin trading positions') }}</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                {{-- Search --}}
                <form id="filter-form" method="GET" action="{{ route('admin.margin-trading.positions.index') }}"
                    class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('Search ticker or user...') }}"
                            class="h-12 bg-white/5 border border-white/10 rounded-2xl px-5 pr-12 text-base font-medium text-white focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all outline-none placeholder:text-text-secondary/30 w-full sm:w-64">
                        <button type="submit"
                            class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-text-secondary group-hover:text-accent-primary transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Export Dropdown --}}
                <div class="relative group" id="export-dropdown">
                    <button type="button"
                        class="dropdown-btn h-12 bg-accent-primary/10 border border-accent-primary/20 rounded-2xl px-6 flex items-center gap-3 text-sm font-bold text-accent-primary hover:bg-accent-primary/20 transition-all outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        {{ __('Export') }}
                    </button>
                    <div
                        class="dropdown-menu absolute right-0 top-full mt-2 w-48 bg-[#0F172A] border border-white/10 rounded-2xl shadow-2xl py-2 z-50 hidden overflow-hidden backdrop-blur-xl">
                        <div class="dropdown-option px-4 py-3 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 cursor-pointer transition-colors flex items-center gap-3 uppercase tracking-widest"
                            data-export="csv">
                            <svg class="w-4 h-4 text-[#10B981]" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm1.8 14.5l-1.4 1.4L12 15.4l-2.4 2.5-1.4-1.4 2.4-2.5-2.4-2.5 1.4-1.4 2.4 2.5 2.4-2.5 1.4 1.4-2.4 2.5 2.4 2.5z" />
                            </svg>
                            {{ __('Export CSV') }}
                        </div>
                        <div class="dropdown-option px-4 py-3 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 cursor-pointer transition-colors flex items-center gap-3 uppercase tracking-widest"
                            data-export="pdf">
                            <svg class="w-4 h-4 text-[#EF4444]" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9v-2h2v2zm0-4h-2V7h2v5z" />
                            </svg>
                            {{ __('Export PDF') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Positions Table --}}
        <div id="positions-wrapper">
            <div
                class="bg-secondary border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl transition-opacity duration-300">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02]">
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">
                                    {{ __('Trader') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">
                                    {{ __('Asset / Leverage') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60 text-right">
                                    {{ __('Entry / Current') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60 text-right">
                                    {{ __('Margin / PnL') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60 text-right">
                                    {{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($positions as $position)
                                <tr class="group hover:bg-white/[0.01] transition-colors">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-full bg-accent-primary/20 text-accent-primary flex items-center justify-center font-black text-xs border border-accent-primary/20 shrink-0">
                                                {{ substr($position->user->username, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-white leading-tight block">
                                                    {{ $position->user->username }}</div>
                                                <div class="text-[10px] text-text-secondary font-mono opacity-50">
                                                    {{ $position->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="px-2 py-1 rounded bg-white/5 border border-white/10 text-[10px] font-black text-white uppercase tracking-tighter">
                                                {{ $position->ticker }}
                                            </div>
                                            <div class="flex flex-col">
                                                @if ($position->side == 'buy')
                                                    <span
                                                        class="text-[9px] font-black text-emerald-400 uppercase tracking-widest leading-none">{{ __('Long') }}</span>
                                                @else
                                                    <span
                                                        class="text-[9px] font-black text-red-400 uppercase tracking-widest leading-none">{{ __('Short') }}</span>
                                                @endif
                                                <span
                                                    class="text-[9px] font-bold text-text-secondary/50 uppercase">{{ $position->leverage }}x</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right font-mono">
                                        <div class="text-xs font-bold text-white leading-tight">
                                            {{ number_format($position->entry_price, 2) }}</div>
                                        <div class="text-[10px] text-accent-primary font-black mt-0.5">
                                            {{ number_format($position->current_price, 2) }}</div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="text-xs font-bold text-white font-mono">
                                            {{ number_format($position->margin, 2) }} <span
                                                class="text-[9px] opacity-40">{{ $currency }}</span></div>
                                        <div
                                            class="text-[10px] font-black mt-0.5 font-mono {{ $position->unrealized_pnl >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                            @if ($position->unrealized_pnl >= 0)
                                                +
                                            @endif{{ number_format($position->unrealized_pnl, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                onclick="closePosition({{ $position->id }}, '{{ $position->ticker }}')"
                                                class="p-2.5 rounded-xl bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all shadow-lg"
                                                title="{{ __('Force Close Position') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                            <button
                                                onclick="deletePosition({{ $position->id }}, '{{ $position->ticker }}')"
                                                class="p-2.5 rounded-xl bg-white/5 text-text-secondary border border-white/10 hover:border-red-500 hover:text-red-400 transition-all shadow-lg"
                                                title="{{ __('Delete Record') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div
                                            class="w-16 h-16 rounded-3xl bg-white/[0.02] border border-white/5 flex items-center justify-center text-text-secondary/20 mx-auto mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                        </div>
                                        <h5 class="text-white font-bold">{{ __('No Open Positions') }}</h5>
                                        <p class="text-text-secondary text-xs mt-1">
                                            {{ __('There are currently no active margin positions.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($positions->hasPages())
                    <div class="px-8 py-6 bg-white/[0.01] border-t border-white/5 ajax-pagination">
                        {{ $positions->links('templates.bento.blades.partials.pagination') }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Export Modal --}}
        <div id="export-modal" class="fixed inset-0 z-[120] hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-md modal-close cursor-pointer"
                onclick="closeModal('export-modal')"></div>
            <div
                class="bg-secondary border border-white/10 rounded-[2.5rem] shadow-2xl relative z-10 w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-white tracking-tight">{{ __('Export Positions') }}</h3>
                    </div>

                    <input type="hidden" id="pending-export-type">

                    <div class="space-y-6">
                        <div>
                            <label
                                class="block text-[10px] font-black text-text-secondary uppercase tracking-widest mb-4 ml-1">{{ __('Select Columns') }}</label>
                            <div class="grid grid-cols-1 gap-3">
                                @php
                                    $exportCols = [
                                        'username' => __('Trader Name'),
                                        'ticker' => __('Asset Ticker'),
                                        'side' => __('Position Side'),
                                        'size' => __('Position Size'),
                                        'entry_price' => __('Entry Price'),
                                        'current_price' => __('Market Price'),
                                        'margin' => __('Margin Used'),
                                        'leverage' => __('Leverage'),
                                        'unrealized_pnl' => __('Real-time PnL'),
                                        'created_at' => __('Open Date'),
                                    ];
                                @endphp
                                @foreach ($exportCols as $key => $label)
                                    <label
                                        class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 hover:border-white/10 transition-all cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="export_cols[]" value="{{ $key }}"
                                                checked
                                                class="w-5 h-5 rounded-lg bg-white/10 border-white/10 text-accent-primary focus:ring-accent-primary transition-all">
                                        </div>
                                        <span
                                            class="text-xs font-bold text-slate-300 group-hover:text-white transition-colors">
                                            {{ $label }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-10">
                        <button type="button" onclick="closeModal('export-modal')"
                            class="flex-1 h-14 rounded-2xl bg-white/5 text-xs font-black text-white uppercase tracking-widest hover:bg-white/10 transition-all">
                            {{ __('Cancel') }}
                        </button>
                        <button type="button" id="confirm-export"
                            class="flex-[2] h-14 rounded-2xl bg-accent-primary text-xs font-black text-secondary uppercase tracking-widest hover:bg-accent-secondary transition-all shadow-lg shadow-accent-primary/20">
                            {{ __('Download Report') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let trendChart = null;
        let distributionChart = null;

        function initCharts() {
            const trendCtx = document.getElementById('positionTrendChart');
            const distCtx = document.getElementById('sideDistributionChart');

            if (!trendCtx || !distCtx) return;

            // Destroy existing charts
            if (trendChart) trendChart.destroy();
            if (distributionChart) distributionChart.destroy();

            const graphData = @json($graph_data);
            const period = $('.graph-period-btn.bg-white\\/10').data('period') || '7d';
            const currentData = graphData[period];

            // Trend Chart
            trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: currentData.buy.labels,
                    datasets: [{
                            label: '{{ __('Longs') }}',
                            data: currentData.buy.data,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHitRadius: 20
                        },
                        {
                            label: '{{ __('Shorts') }}',
                            data: currentData.sell.data,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHitRadius: 20
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                },
                                maxRotation: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#fff',
                            bodyColor: '#94a3b8',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 4,
                            usePointStyle: true,
                            bodyFont: {
                                size: 11,
                                weight: 'bold'
                            }
                        }
                    }
                }
            });

            // Side Distribution
            const sideDistData = @json($side_chart_data);
            distributionChart = new Chart(distCtx, {
                type: 'doughnut',
                data: {
                    labels: sideDistData.map(d => d.status),
                    datasets: [{
                        data: sideDistData.map(d => d.amount),
                        backgroundColor: ['#10b981', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 15,
                        cutout: '75%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#64748b',
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                },
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    }
                }
            });
        }

        function loadPositions(url) {
            $('#loading-spinner').removeClass('hidden').addClass('flex');
            $('#margin-positions-wrapper').addClass('opacity-50 pointer-events-none');

            $.get(url, function(data) {
                const $html = $(data);
                $('#margin-positions-wrapper').html($html.find('#margin-positions-wrapper').html());

                // Re-initialize 
                initCharts();
                window.history.pushState({}, '', url);
            }).fail(function() {
                toastNotification("{{ __('Failed to load data.') }}", 'error');
            }).always(function() {
                $('#loading-spinner').addClass('hidden').removeClass('flex');
                $('#margin-positions-wrapper').removeClass('opacity-50 pointer-events-none');
            });
        }

        $(document).ready(function() {
            initCharts();

            // Period Switching
            $(document).on('click', '.graph-period-btn', function() {
                $('.graph-period-btn').removeClass('bg-white/10 text-white').addClass(
                    'text-slate-500 hover:text-slate-300');
                $(this).addClass('bg-white/10 text-white').removeClass(
                    'text-slate-500 hover:text-slate-300');

                const period = $(this).data('period');
                const graphData = @json($graph_data);
                const newData = graphData[period];

                if (trendChart && newData) {
                    trendChart.data.labels = newData.buy.labels;
                    trendChart.data.datasets[0].data = newData.buy.data;
                    trendChart.data.datasets[1].data = newData.sell.data;
                    trendChart.update();
                }
            });

            // Dropdowns
            $(document).on('click', '.dropdown-btn', function(e) {
                e.stopPropagation();
                const $menu = $(this).siblings('.dropdown-menu');
                $('.dropdown-menu').not($menu).addClass('hidden');
                $menu.toggleClass('hidden');
                $(this).find('.dropdown-icon').toggleClass('rotate-180');
            });

            $(document).on('click', function() {
                $('.dropdown-menu').addClass('hidden');
                $('.dropdown-icon').removeClass('rotate-180');
            });

            $(document).on('submit', '#filter-form', function(e) {
                e.preventDefault();
                const url = $(this).attr('action') + '?' + $(this).serialize();
                loadPositions(url);
            });

            // Pagination
            $(document).on('click', '.ajax-pagination a', function(e) {
                e.preventDefault();
                loadPositions($(this).attr('href'));
            });

            // Export
            $(document).on('click', '#export-dropdown .dropdown-option', function() {
                const type = $(this).data('export');
                $('#pending-export-type').val(type);
                $('#export-modal').removeClass('hidden').addClass('flex');
            });

            $(document).on('click', '#confirm-export', function() {
                const $btn = $(this);
                const originalHtml = $btn.html();
                const exportType = $('#pending-export-type').val();

                const selectedCols = [];
                $('input[name="export_cols[]"]:checked').each(function() {
                    selectedCols.push($(this).val());
                });

                if (selectedCols.length === 0) {
                    toastNotification("{{ __('Select at least one column.') }}", 'error');
                    return;
                }

                $btn.prop('disabled', true).addClass('opacity-50').html(`
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-secondary-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ __('Exporting...') }}</span>
                `);

                const baseUrl = $('#filter-form').attr('action');
                let params = new URLSearchParams(window.location.search);
                params.set('export', exportType);
                params.set('columns', selectedCols.join(','));

                setTimeout(() => {
                    closeModal('export-modal');
                    window.location.href = baseUrl + '?' + params.toString();
                    setTimeout(() => {
                        $btn.prop('disabled', false).removeClass('opacity-50').html(
                            originalHtml);
                    }, 3000);
                }, 500);
            });
        });

        window.closeModal = function(id) {
            $(`#${id}`).addClass('hidden').removeClass('flex');
        };

        window.closePosition = function(id, ticker) {
            confirmAction({
                title: '{{ __('Close Position?') }}',
                text: "{{ __('Are you sure you want to force close the position for') }} " + ticker +
                    "? {{ __('PnL will be settled and margin refunded.') }}",
                icon: 'warning',
                confirmText: '{{ __('Yes, Close Case') }}',
                isDanger: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ route('admin.margin-trading.positions.close') }}", {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    }).done(res => {
                        toastNotification(res.message, res.status);
                        if (res.status === 'success') loadPositions(window.location.href);
                    }).fail(() => toastNotification('{{ __('Request failed') }}', 'error'));
                }
            });
        }

        window.deletePosition = function(id, ticker) {
            confirmAction({
                title: '{{ __('Delete Record?') }}',
                text: "{{ __('This will only delete the database record for') }} " + ticker +
                    ". {{ __('No balance updates will occur.') }}",
                icon: 'warning',
                confirmText: '{{ __('Delete') }}',
                isDanger: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ route('admin.margin-trading.positions.delete') }}", {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    }).done(res => {
                        toastNotification(res.message, res.status);
                        if (res.status === 'success') loadPositions(window.location.href);
                    }).fail(() => toastNotification('{{ __('Request failed') }}', 'error'));
                }
            });
        }
    </script>
@endpush
