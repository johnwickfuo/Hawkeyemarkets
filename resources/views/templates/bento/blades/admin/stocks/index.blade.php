@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div id="stocks-content" class="space-y-8">
        {{-- Message  if module is disabled --}}
        @if (!moduleEnabled('stock_module'))
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
                    {{ __('Stock module disabled') }}</h4>
                <p class="text-[10px] text-slate-500 max-w-[200px] mb-6 font-medium leading-relaxed italic">
                    {{ __('Stock module is disabled. Please enable the stock module in settings to initialize.') }}
                </p>
                <a href="{{ route('admin.settings.modules.index') }}"
                    class="px-5 py-2 rounded-xl bg-white/5 border border-white/10 text-[9px] font-black text-white uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all active:scale-95">
                    {{ __('Settings') }}
                </a>
            </div>
        @endif
        @if (moduleEnabled('stock_module'))
            {{-- Header & Stats --}}
            <div id="stats-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                {{-- Total Holdings --}}
                <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <div class="text-3xl font-black text-white leading-none">{{ number_format($stats->total_holdings) }}
                        </div>
                        <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                            {{ __('Unique Holdings') }}</div>
                    </div>
                </div>

                {{-- Total Shares --}}
                <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div class="text-3xl font-black text-white leading-none">
                            {{ number_format($stats->total_shares, 2) }}</div>
                        <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                            {{ __('Total Shares Managed') }}</div>
                    </div>
                </div>

                {{-- Total PnL --}}
                <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative z-10">
                        @php $totalPnl = $stats->total_pnl * $exchange_rate; @endphp
                        <div
                            class="w-12 h-12 rounded-2xl {{ $totalPnl >= 0 ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }} flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div
                            class="text-3xl font-black {{ $totalPnl >= 0 ? 'text-emerald-400' : 'text-red-400' }} leading-none">
                            {{ $totalPnl >= 0 ? '+' : '' }}{{ showAmount($totalPnl) }}
                        </div>
                        <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                            {{ __('Net Unrealized PnL') }}</div>
                    </div>
                </div>

                {{-- PnL Average --}}
                <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-violet-500/10 flex items-center justify-center text-violet-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-black text-white leading-none">
                            {{ number_format($stats->avg_pnl, 2) }}%
                        </div>
                        <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                            {{ __('Avg Profitability') }}</div>
                    </div>
                </div>

                {{-- Total Value --}}
                <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="text-3xl font-black text-white leading-none">
                            {{ showAmount($stats->total_value * $exchange_rate) }}
                        </div>
                        <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                            {{ __('Total Inventory Value') }}</div>
                    </div>
                </div>
            </div>

            {{-- Main Table & Filters --}}
            <div id="stocks-wrapper"
                class="bg-secondary border border-white/5 rounded-[2rem] overflow-hidden shadow-2xl relative">
                {{-- Loading Spinner Overlay --}}
                <div id="loading-spinner"
                    class="hidden absolute inset-0 bg-secondary/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-opacity duration-300">
                    <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin">
                    </div>
                    <p class="mt-3 text-text-secondary text-xs font-bold uppercase tracking-widest">
                        {{ __('Syncing Equity Data...') }}</p>
                </div>

                {{-- Filters & Search Header --}}
                <div class="bg-secondary/30 border-b border-white/5 p-4 lg:p-6 relative">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-text-secondary border border-white/10 shadow-inner">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-bold tracking-tight">{{ __('Equity Inventory') }}</h4>
                                <p
                                    class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mt-0.5 opacity-50">
                                    {{ __('List of all active asset holdings across the platform') }}</p>
                            </div>
                        </div>

                        <form id="filter-form" method="GET" action="{{ route('admin.stocks.index') }}"
                            class="flex-1 max-w-2xl flex flex-wrap gap-3 lg:justify-end">
                            <div class="relative flex-1 group min-w-[200px]">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="{{ __('Search by ticker, username or email...') }}"
                                    class="w-full h-12 bg-white/5 border border-white/10 rounded-2xl px-5 text-sm font-medium text-white focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all outline-none placeholder:text-text-secondary/30">
                                <button type="submit"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-2 hover:bg-white/10 rounded-xl text-text-secondary transition-colors group-hover:text-accent-primary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>

                            @if (request('search'))
                                <button type="button" data-url="{{ route('admin.stocks.index') }}"
                                    class="ajax-clear h-12 px-6 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 text-text-secondary hover:text-white flex items-center gap-2 transition-all font-bold text-xs uppercase tracking-widest shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    {{ __('Clear') }}
                                </button>
                            @endif

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

                <div class="overflow-x-auto" id="table-content">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02]">
                                <th class="px-8 py-6 text-left">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Shareholder') }}</span>
                                </th>
                                <th class="px-8 py-6 text-left">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Asset') }}</span>
                                </th>
                                <th class="px-8 py-6 text-right">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Volume') }}</span>
                                </th>
                                <th class="px-8 py-6 text-right">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Avg Entry') }}</span>
                                </th>
                                <th class="px-8 py-6 text-right">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Performance') }}</span>
                                </th>
                                <th class="px-8 py-6 text-right">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Action') }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($holdings as $holding)
                                <tr class="group hover:bg-white/[0.01] transition-colors relative">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="relative shrink-0">
                                                @if ($holding->user->photo)
                                                    <img src="{{ asset('storage/profile/' . $holding->user->photo) }}"
                                                        class="w-10 h-10 rounded-full border border-white/10">
                                                @else
                                                    <div
                                                        class="w-10 h-10 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-black text-xs uppercase border border-indigo-500/20">
                                                        {{ substr($holding->user->first_name, 0, 1) }}{{ substr($holding->user->last_name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div
                                                    class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-secondary border-2 border-primary flex items-center justify-center">
                                                    <div
                                                        class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]">
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.users.detail', $holding->user->id) }}"
                                                    class="text-sm font-bold text-white hover:text-accent-primary transition-colors block leading-tight">
                                                    {{ $holding->user->fullname }}
                                                </a>
                                                <p class="text-[10px] text-text-secondary font-mono opacity-50">
                                                    {{ $holding->user->username }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3 text-left">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10 shrink-0 overflow-hidden group-hover:border-accent-primary transition-colors">
                                                <img src="https://financialmodelingprep.com/image-stock/{{ $holding->ticker }}.png"
                                                    alt="{{ $holding->ticker }}"
                                                    class="w-7 h-7 object-contain opacity-90 group-hover:opacity-100 transition-opacity"
                                                    onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ $holding->ticker }}&background=0F172A&color=fff&bold=true';">
                                            </div>
                                            <div>
                                                <div
                                                    class="text-sm font-black text-white leading-tight uppercase group-hover:text-accent-primary transition-colors">
                                                    {{ $holding->ticker }}</div>
                                                <p
                                                    class="text-[10px] text-text-secondary font-bold uppercase tracking-tighter opacity-50 leading-tight mt-0.5">
                                                    {{ __('Stock Instrument') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right font-mono text-sm">
                                        <div class="text-white font-black">{{ $holding->shares }}</div>
                                        <div class="text-[9px] text-text-secondary font-bold uppercase opacity-40">
                                            {{ __('Shares') }}</div>
                                    </td>
                                    <td class="px-8 py-5 text-right font-mono text-sm">
                                        <div class="text-white font-black">
                                            {{ showAmount($holding->average_price * $exchange_rate) }}</div>
                                        <div class="text-[9px] text-text-secondary font-bold uppercase opacity-40">
                                            {{ __('Per Unit') }}</div>
                                    </td>
                                    <td class="px-8 py-5 text-right font-mono text-sm">
                                        <div
                                            class="font-black {{ $holding->pnl >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                            {{ $holding->pnl >= 0 ? '+' : '' }}{{ showAmount($holding->pnl * $exchange_rate) }}
                                        </div>
                                        <div
                                            class="text-[10px] font-bold {{ $holding->pnl_percent >= 0 ? 'text-emerald-400/50' : 'text-red-400/50' }}">
                                            {{ number_format($holding->pnl_percent, 2) }}%
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button"
                                                class="open-confirm-modal p-2.5 rounded-xl bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all cursor-pointer shadow-lg shadow-red-500/5"
                                                data-id="{{ $holding->id }}" data-action="delete"
                                                data-name="{{ $holding->ticker }} ({{ $holding->user->username }})"
                                                title="{{ __('Liquidate/Delete') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center">
                                        <div
                                            class="w-16 h-16 rounded-3xl bg-white/[0.02] border border-white/5 flex items-center justify-center text-text-secondary/20 mx-auto mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                </path>
                                            </svg>
                                        </div>
                                        <h5 class="text-white font-bold">{{ __('No Stock Holdings') }}</h5>
                                        <p class="text-text-secondary text-xs mt-1">
                                            {{ __('There are currently no assets allocated to users.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="pagination-wrapper" class="ajax-pagination">
                    @if ($holdings->hasPages())
                        <div class="px-8 py-6 bg-white/[0.01] border-t border-white/5 ajax-pagination">
                            {{ $holdings->links('templates.bento.blades.partials.pagination') }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Liquidate Confirmation Modal --}}
    <div id="confirm-modal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md modal-close"></div>
        <div
            class="bg-secondary border border-white/10 rounded-[2.5rem] shadow-2xl relative z-10 w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
            <div class="p-8 text-center pt-12">
                <div
                    class="w-20 h-20 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center mx-auto mb-6 border border-red-500/20 animate-pulse">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-white mb-2" id="confirm-title">{{ __('Liquidate Asset?') }}</h3>
                <p class="text-text-secondary text-sm leading-relaxed" id="confirm-message">
                    {{ __('Are you sure you want to remove this holding from the user\'s portfolio? This action cannot be undone.') }}
                </p>
                <div class="mt-4 p-3 bg-red-500/5 rounded-2xl border border-red-500/20 inline-block">
                    <span class="text-[10px] font-black text-red-400 uppercase tracking-[0.2em]" id="confirm-name"></span>
                </div>
            </div>
            <div class="flex gap-4 p-8 pt-0">
                <button type="button"
                    class="flex-1 h-14 rounded-2xl border border-white/10 text-white font-black hover:bg-white/5 transition-all modal-close cursor-pointer uppercase tracking-widest text-xs">{{ __('Cancel') }}</button>
                <button type="button" id="confirm-action-btn"
                    class="flex-1 h-14 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-black shadow-lg shadow-red-500/20 transition-all cursor-pointer uppercase tracking-widest text-xs flex items-center justify-center gap-2">
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
                                        'shares' => ['label' => 'Shares', 'default' => true],
                                        'average_price' => ['label' => 'Avg Price', 'default' => true],
                                        'pnl' => ['label' => 'PnL', 'default' => true],
                                        'pnl_percent' => ['label' => 'PnL %', 'default' => true],
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
    <script>
        $(document).ready(function() {
            // Custom Dropdown Toggle
            $(document).on('click', '.custom-dropdown .dropdown-btn', function(e) {
                e.stopPropagation();
                const $dropdown = $(this).closest('.custom-dropdown');
                $('.custom-dropdown').not($dropdown).find('.dropdown-menu').addClass('hidden');
                $('.custom-dropdown').not($dropdown).find('.dropdown-icon').removeClass('rotate-180');

                $dropdown.find('.dropdown-menu').toggleClass('hidden');
                $(this).find('.dropdown-icon').toggleClass('rotate-180');
            });

            $(document).on('click', function() {
                $('.dropdown-menu').addClass('hidden');
                $('.dropdown-icon').removeClass('rotate-180');
            });

            // Export Modal Trigger (Delegated)
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

                const form = $('#filter-form');
                const baseUrl = form.attr('action');
                const params = form.serialize();
                let paramsObj = new URLSearchParams(params);
                paramsObj.set('export', currentExportType);
                paramsObj.set('columns', columns.join(','));

                $('#export-modal').addClass('hidden');
                window.location.href = baseUrl + '?' + paramsObj.toString();
            });

            let currentId = null;

            window.confirmDelete = function(id) {
                currentId = id;
                $('#confirm-modal').removeClass('hidden').addClass('flex');
            };

            window.closeModal = function() {
                $('#confirm-modal').addClass('hidden').removeClass('flex');
                currentId = null;
            };

            // Existing delete modal handlers (delegated)
            $(document).on('click', '.open-confirm-modal', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                $('#confirm-name').text(name);
                window.confirmDelete(id);
            });

            $(document).on('click', '#confirm-modal .modal-close', function() {
                window.closeModal();
            });

            $(document).on('click', '#confirm-action-btn', function() {
                if (!currentId) return;

                const btn = $(this);
                const btnText = btn.find('.btn-text');
                const spinner = btn.find('.loading-spinner');

                btn.prop('disabled', true).addClass('opacity-50');
                btnText.text('{{ __('Deleting...') }}');
                spinner.removeClass('hidden');

                $.ajax({
                    url: "{{ route('admin.stocks.delete') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: currentId
                    },
                    success: function(response) {
                        if (response.success || response.status === 'success') {
                            $('#confirm-modal').addClass('hidden').removeClass('flex');
                            loadTable(window.location.href);
                            if (typeof toastNotification === 'function') {
                                toastNotification(response.message, 'success');
                            }
                        } else {
                            if (typeof toastNotification === 'function') {
                                toastNotification(response.message, 'error');
                            }
                        }
                    },
                    error: function(xhr) {
                        if (typeof toastNotification === 'function') {
                            toastNotification(xhr.responseJSON?.message ||
                                'An error occurred while processing your request.', 'error');
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).removeClass('opacity-50');
                        btnText.text('{{ __('Delete') }}');
                        spinner.addClass('hidden');
                    }
                });
            });
            // AJAX Table Loading
            function loadTable(url = null) {
                const form = $('#filter-form');
                const baseUrl = form.attr('action');
                const formData = form.serialize();
                const requestUrl = url || baseUrl + (formData ? '?' + formData : '');

                $('#loading-spinner').removeClass('hidden').addClass('flex');
                $('#stocks-wrapper').css('opacity', '0.5');

                $.ajax({
                    url: requestUrl,
                    type: 'GET',
                    success: function(data) {
                        $('#stocks-content').html($(data).find('#stocks-content').html());
                        window.history.pushState({}, '', requestUrl);
                    },
                    error: function() {
                        if (typeof toastNotification === 'function') {
                            toastNotification('{{ __('Error loading content.') }}', 'error');
                        }
                    },
                    complete: function() {
                        $('#loading-spinner').addClass('hidden').removeClass('flex');
                        $('#stocks-wrapper').css('opacity', '1');
                    }
                });
            }

            // AJAX search handling
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
        });
    </script>
@endpush
