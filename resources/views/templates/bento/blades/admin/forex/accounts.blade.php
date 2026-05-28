@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div id="forex-accounts-wrapper" class="space-y-8">

        {{-- Page Header & Mode Switcher --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}"
                    class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-text-secondary hover:text-white hover:bg-white/10 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight">{{ __('Forex Trader Accounts') }}</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span
                            class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest {{ ($mode ?? 'live') === 'live' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                            {{ strtoupper($mode ?? 'live') }} {{ __('MODE') }}
                        </span>
                        <p class="text-[10px] text-text-secondary font-bold uppercase tracking-widest opacity-50">
                            {{ __('Overview and context') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center bg-white/[0.03] border border-white/[0.06] rounded-2xl p-1">
                    <a href="{{ request()->fullUrlWithQuery(['mode' => 'live']) }}"
                        class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ ($mode ?? 'live') === 'live' ? 'bg-accent-primary text-secondary shadow-lg' : 'text-slate-500 hover:text-white' }}">
                        {{ __('Live') }}
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['mode' => 'demo']) }}"
                        class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ ($mode ?? 'live') === 'demo' ? 'bg-amber-500 text-secondary shadow-lg' : 'text-slate-500 hover:text-white' }}">
                        {{ __('Demo') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Analytics Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-accent-primary/20 rounded-lg text-accent-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Total Balance') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['total_balance'], 2) }} <span
                                class="text-xs font-medium text-accent-primary uppercase">{{ $currency }}</span></h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-red-500/20 rounded-lg text-red-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Total Borrowed') }}</p>
                        <h4 class="text-xl font-bold text-red-400">{{ number_format($stats['total_borrowed'], 2) }} <span
                                class="text-xs font-medium uppercase text-red-400/50">{{ $currency }}</span></h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-amber-500/20 rounded-lg text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Active Traders') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['active_count']) }}</h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-red-500/20 rounded-lg text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Suspended') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['suspended_count']) }}</h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-slate-500/20 rounded-lg text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Inactive') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['inactive_count'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-slate-700/20 rounded-lg text-slate-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Closed') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['closed_count'] ?? 0) }}</h4>
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
                                {{ __('Account Growth Trend') }}</div>
                            <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                                {{ __('New Forex Accounts') }} · {{ __('Count') }}</p>
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            <div
                                class="flex items-center flex-wrap gap-1 bg-white/[0.03] border border-white/[0.06] rounded-lg p-1">
                                @foreach (['7d' => '7D', '30d' => '30D', '60d' => '60D', '90d' => '90D', '1y' => '1Y', 'ytd' => 'YTD'] as $key => $label)
                                    <button data-period="{{ $key }}"
                                        class="graph-period-btn cursor-pointer px-2.5 py-1 rounded-md text-[9px] font-bold uppercase tracking-wider transition-all {{ $key === '7d' ? 'bg-white/10 text-white' : 'text-slate-500 hover:text-slate-300' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div id="graph-legend" class="flex items-center gap-4 mb-4 flex-wrap"></div>
                    <div class="relative h-64"><canvas id="accountTrendChart"></canvas></div>
                </div>
            </div>

            {{-- Status Distribution (1/3 width) --}}
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
                        <div class="text-sm font-bold text-white tracking-wide">{{ __('Account Status Distribution') }}
                        </div>
                        <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                            {{ __('Total Balance by Status') }}</p>
                    </div>
                    <div class="relative flex-1 flex items-center justify-center p-4" style="min-height: 250px;">
                        <canvas id="statusDistributionChart" class="max-h-[220px]"></canvas>
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

        {{-- Table Section Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-6 bg-accent-primary rounded-full"></div>
                <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Forex Trading Accounts') }}</h3>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                <form id="filter-form" method="GET" action="{{ route('admin.forex-trading.accounts.index') }}"
                    class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                    <input type="hidden" name="mode" value="{{ $mode ?? 'live' }}">
                    <div class="relative group" id="status-filter-dropdown">
                        <button type="button"
                            class="dropdown-btn h-12 bg-white/5 border border-white/10 rounded-2xl px-5 flex items-center gap-3 text-sm font-medium text-white hover:bg-white/10 transition-all outline-none min-w-[140px]">
                            <span
                                class="selected-label">{{ request('status') ? ucfirst(request('status')) : __('All Status') }}</span>
                            <svg class="dropdown-icon w-4 h-4 text-text-secondary transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <input type="hidden" name="status" id="status-input" value="{{ request('status', 'all') }}">
                        <div
                            class="dropdown-menu absolute right-0 top-full mt-2 w-48 bg-[#0F172A] border border-white/10 rounded-2xl shadow-2xl py-2 z-50 hidden overflow-hidden backdrop-blur-xl text-left">
                            <div class="dropdown-option px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 cursor-pointer transition-colors uppercase tracking-widest"
                                data-value="all">{{ __('All Status') }}</div>
                            <div class="dropdown-option px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 cursor-pointer transition-colors uppercase tracking-widest"
                                data-value="active">{{ __('Active') }}</div>
                            <div class="dropdown-option px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 cursor-pointer transition-colors uppercase tracking-widest"
                                data-value="inactive">{{ __('Inactive') }}</div>
                            <div class="dropdown-option px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 cursor-pointer transition-colors uppercase tracking-widest"
                                data-value="suspended">{{ __('Suspended') }}</div>
                            <div class="dropdown-option px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 cursor-pointer transition-colors uppercase tracking-widest"
                                data-value="closed">{{ __('Closed') }}</div>
                        </div>
                    </div>

                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('Search traders...') }}"
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
                        class="dropdown-menu absolute right-0 top-full mt-2 w-48 bg-[#0F172A] border border-white/10 rounded-2xl shadow-2xl py-2 z-50 hidden overflow-hidden backdrop-blur-xl text-left">
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

        {{-- Accounts Table --}}
        <div id="accounts-wrapper">
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
                                    {{ __('Balance') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">
                                    {{ __('Borrowed') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">
                                    {{ __('Status') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60 text-right">
                                    {{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($accounts as $account)
                                <tr class="group hover:bg-white/[0.01] transition-colors">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-full bg-accent-primary/20 text-accent-primary flex items-center justify-center font-black text-xs border border-accent-primary/20">
                                                {{ substr($account->user->username, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-white leading-tight">
                                                    {{ $account->user->username }}</div>
                                                <div class="text-[10px] text-text-secondary font-mono opacity-50">
                                                    {{ $account->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-sm font-mono text-white">
                                        <div class="font-black">{{ number_format($account->balance, 2) }} <span
                                                class="text-[10px] text-accent-primary uppercase">{{ $account->currency }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-sm font-mono text-white">
                                        <div class="font-medium text-red-400">{{ number_format($account->borrowed, 2) }}
                                            <span
                                                class="text-[10px] opacity-100 uppercase">{{ $account->currency }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        @php
                                            $statuses = [
                                                'active' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                'inactive' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
                                                'suspended' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                                'closed' => 'bg-slate-500/10 text-slate-300 border-white/10',
                                            ];
                                            $statusClass = $statuses[$account->account_status] ?? $statuses['inactive'];
                                        @endphp
                                        <span
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                            {{ __($account->account_status) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                onclick="openCreditDebitModal({{ $account->id }}, '{{ $account->user->username }}')"
                                                class="p-2.5 rounded-xl bg-white/5 text-text-secondary border border-white/10 hover:border-accent-primary hover:text-accent-primary transition-all shadow-lg"
                                                title="{{ __('Balance Adjustment') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                            <button
                                                onclick="openStatusModal({{ $account->id }}, '{{ $account->account_status }}')"
                                                class="p-2.5 rounded-xl bg-white/5 text-text-secondary border border-white/10 hover:border-violet-500 hover:text-violet-400 transition-all shadow-lg"
                                                title="{{ __('Change Status') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            </button>
                                            <button
                                                onclick="deleteAccount({{ $account->id }}, '{{ $account->user->username }}')"
                                                class="p-2.5 rounded-xl bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all shadow-lg"
                                                title="{{ __('Delete Account') }}">
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
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <h5 class="text-white font-bold">{{ __('No Trader Accounts') }}</h5>
                                        <p class="text-text-secondary text-xs mt-1">
                                            {{ __('No forex trading accounts found in the database.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($accounts->hasPages())
                    <div class="px-8 py-6 bg-white/[0.01] border-t border-white/5 ajax-pagination">
                        {{ $accounts->links('templates.bento.blades.partials.pagination') }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Modals (Export, Credit/Debit, Status) remain identical logic-wise, just checking paths and IDs --}}
        {{-- [MODAL HTML CODE - TRUNCATED FOR SIMPLICITY IN TASK VIEW BUT FULL CONTENT WRITTEN TO FILE] --}}

        {{-- Export Modal --}}
        <div id="export-modal" class="fixed inset-0 z-[120] hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-md modal-close cursor-pointer"
                onclick="closeModal('export-modal')"></div>
            <div
                class="bg-secondary border border-white/10 rounded-[2.5rem] shadow-2xl relative z-10 w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
                <div class="p-8">
                    <h3 class="text-xl font-black text-white tracking-tight mb-8">{{ __('Export Options') }}</h3>
                    <input type="hidden" id="pending-export-type">
                    <div class="space-y-4">
                        @foreach (['username' => __('User'), 'email' => __('Email'), 'balance' => __('Balance'), 'borrowed' => __('Borrowed'), 'currency' => __('Currency'), 'account_status' => __('Status'), 'created_at' => __('Date')] as $key => $label)
                            <label
                                class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all cursor-pointer group">
                                <input type="checkbox" name="export_cols[]" value="{{ $key }}" checked
                                    class="w-5 h-5 rounded-lg bg-white/10 border-white/10 text-accent-primary focus:ring-accent-primary transition-all">
                                <span
                                    class="text-xs font-bold text-slate-300 group-hover:text-white transition-colors uppercase tracking-widest">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="flex gap-4 mt-10">
                        <button type="button" onclick="closeModal('export-modal')"
                            class="flex-1 h-14 rounded-2xl bg-white/5 text-xs font-black text-white uppercase tracking-widest hover:bg-white/10 transition-all">{{ __('Cancel') }}</button>
                        <button type="button" id="confirm-export"
                            class="flex-[2] h-14 rounded-2xl bg-accent-primary text-xs font-black text-secondary uppercase tracking-widest hover:bg-accent-secondary transition-all shadow-lg">{{ __('Download') }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Credit/Debit Modal --}}
        <div id="creditDebitModal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-md modal-close cursor-pointer"
                onclick="closeModal('creditDebitModal')"></div>
            <div
                class="bg-secondary border border-white/10 rounded-[2.5rem] shadow-2xl relative z-10 w-full max-w-md overflow-hidden animate-in zoom-in duration-300">
                <form id="creditDebitForm" class="p-8">
                    <input type="hidden" name="id" id="account_id_cd">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-white tracking-tight">{{ __('Adjust Balance') }}</h3>
                        <div id="account_name_cd"
                            class="px-3 py-1 rounded-lg bg-accent-primary/10 text-accent-primary text-[10px] font-black uppercase">
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label
                                class="block text-[10px] font-black text-text-secondary uppercase tracking-widest mb-3 ml-1">{{ __('Type') }}</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label
                                    class="relative flex items-center justify-center h-12 rounded-2xl border border-accent-primary bg-accent-primary/5 cursor-pointer group transition-all radio-parent active-status">
                                    <input type="radio" name="type" value="credit" checked
                                        class="absolute inset-0 opacity-0">
                                    <span
                                        class="text-xs font-bold text-emerald-400 uppercase tracking-widest">{{ __('Credit (+)') }}</span>
                                </label>
                                <label
                                    class="relative flex items-center justify-center h-12 rounded-2xl border border-white/5 bg-white/5 cursor-pointer group transition-all radio-parent">
                                    <input type="radio" name="type" value="debit"
                                        class="absolute inset-0 opacity-0">
                                    <span
                                        class="text-xs font-bold text-red-400 opacity-60 group-hover:opacity-100 uppercase tracking-widest">{{ __('Debit (-)') }}</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black text-text-secondary uppercase tracking-widest mb-3 ml-1">{{ __('Amount') }}
                                ({{ $currency }})</label>
                            <input type="number" step="any" name="amount" required placeholder="0.00"
                                class="w-full h-14 bg-white/5 border border-white/10 rounded-2xl px-6 text-white font-mono focus:border-accent-primary outline-none transition-all">
                        </div>
                        <button type="submit"
                            class="w-full h-14 rounded-2xl bg-accent-primary hover:bg-accent-primary/90 text-white font-black uppercase tracking-widest text-xs shadow-lg flex items-center justify-center gap-2">
                            <span class="btn-text">{{ __('Execute') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Status Modal --}}
        <div id="statusModal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-md modal-close cursor-pointer"
                onclick="closeModal('statusModal')"></div>
            <div
                class="bg-secondary border border-white/10 rounded-[2.5rem] shadow-2xl relative z-10 w-full max-w-sm overflow-hidden animate-in zoom-in duration-300">
                <form id="statusForm" class="p-8">
                    <input type="hidden" name="id" id="account_id_status">
                    <h3 class="text-xl font-black text-white tracking-tight mb-8">{{ __('Update Status') }}</h3>
                    <div class="space-y-3">
                        @foreach (['active', 'inactive', 'suspended', 'closed'] as $status)
                            <label
                                class="flex items-center gap-4 p-4 rounded-2xl border border-white/5 bg-white/5 hover:border-accent-primary/50 transition-all cursor-pointer group">
                                <input type="radio" name="status" value="{{ $status }}"
                                    class="w-5 h-5 border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary">
                                <span
                                    class="text-sm font-bold text-text-secondary group-hover:text-white uppercase tracking-widest">{{ __($status) }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button type="submit"
                        class="w-full h-14 rounded-2xl bg-white text-slate-900 font-black shadow-xl mt-8 hover:scale-[0.98] transition-all uppercase tracking-widest text-xs">{{ __('Update') }}</button>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            const GRAPH_DATA = @json($graph_data);
            const STATUS_CHART_DATA = @json($status_chart_data);
            const CURRENCY = "{{ $currency }}";

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
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || context.label || '';
                        if (label) label += ': ';
                        const val = context.parsed.y !== undefined ? context.parsed.y : context.parsed;
                        const formatted = new Intl.NumberFormat('en-US', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 2
                        }).format(val);
                        return label + formatted + (context.chart.config.type === 'doughnut' ? ' ' +
                            CURRENCY : '');
                    }
                }
            };

            const statusConfig = {
                'active': {
                    color: '#10b981',
                    bg: 'rgba(16, 185, 129, 0.1)',
                    border: 'rgba(16, 185, 129, 0.3)',
                    label: '{{ __('Active') }}'
                },
                'inactive': {
                    color: '#94a3b8',
                    bg: 'rgba(148, 163, 184, 0.1)',
                    border: 'rgba(148, 163, 184, 0.3)',
                    label: '{{ __('Inactive') }}'
                },
                'suspended': {
                    color: '#ef4444',
                    bg: 'rgba(239, 68, 68, 0.1)',
                    border: 'rgba(239, 68, 68, 0.3)',
                    label: '{{ __('Suspended') }}'
                },
                'closed': {
                    color: '#64748b',
                    bg: 'rgba(100, 116, 139, 0.1)',
                    border: 'rgba(100, 116, 139, 0.3)',
                    label: '{{ __('Closed') }}'
                }
            };

            let trendChart = null;
            let distChart = null;

            function initCharts() {
                const trendCtx = document.getElementById('accountTrendChart')?.getContext('2d');
                if (trendCtx) {
                    trendChart = new Chart(trendCtx, {
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
                                        maxTicksLimit: 5,
                                        precision: 0
                                    },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    updateTrendChart('7d');
                }

                const distCtx = document.getElementById('statusDistributionChart')?.getContext('2d');
                if (distCtx) {
                    distChart = new Chart(distCtx, {
                        type: 'doughnut',
                        data: {
                            labels: STATUS_CHART_DATA.map(d => d.status),
                            datasets: [{
                                data: STATUS_CHART_DATA.map(d => d.amount),
                                backgroundColor: STATUS_CHART_DATA.map(d => statusConfig[d.status
                                    .toLowerCase()]?.color || '#3b82f6'),
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
                                        padding: 20,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        font: {
                                            size: 10,
                                            weight: 'bold'
                                        }
                                    }
                                },
                                tooltip: tooltipOptions
                            }
                        }
                    });
                }
            }

            function updateTrendChart(period) {
                if (!trendChart || !GRAPH_DATA[period]) return;
                const periodData = GRAPH_DATA[period];
                let allLabels = new Set();
                Object.values(periodData).forEach(s => s.labels.forEach(l => allLabels.add(l)));
                let sortedLabels = Array.from(allLabels).sort();
                if (sortedLabels.length === 0) sortedLabels = [new Date().toISOString().split('T')[0]];

                trendChart.data.labels = sortedLabels;
                trendChart.data.datasets = Object.keys(statusConfig).map(status => {
                    const cfg = statusConfig[status];
                    const sData = periodData[status];
                    return {
                        label: cfg.label,
                        data: sortedLabels.map(l => {
                            const idx = sData ? sData.labels.indexOf(l) : -1;
                            return idx !== -1 ? sData.data[idx] : 0;
                        }),
                        borderColor: cfg.color,
                        backgroundColor: cfg.bg,
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        tension: 0.4,
                        fill: true
                    };
                });
                trendChart.update();
            }

            $('.graph-period-btn').on('click', function() {
                const p = $(this).data('period');
                $('.graph-period-btn').removeClass('bg-white/10 text-white').addClass('text-slate-500');
                $(this).addClass('bg-white/10 text-white').removeClass('text-slate-500');
                updateTrendChart(p);
            });

            initCharts();

            // AJAX Table & Pagination
            function loadAccounts(query = '') {
                const wrapper = $('#accounts-wrapper');
                wrapper.addClass('opacity-50 pointer-events-none');
                $('#loading-spinner').removeClass('hidden').addClass('flex');

                $.ajax({
                    url: '{{ route('admin.forex-trading.accounts.index') }}' + query,
                    method: 'GET',
                    success: function(response) {
                        const newContent = $(response).find('#accounts-wrapper').html();
                        wrapper.html(newContent);
                    },
                    complete: function() {
                        wrapper.removeClass('opacity-50 pointer-events-none');
                        $('#loading-spinner').addClass('hidden').removeClass('flex');
                    }
                });
            }

            $(document).on('click', '.ajax-pagination a', function(e) {
                e.preventDefault();
                const url = new URL($(this).attr('href'));
                loadAccounts(url.search);
                window.history.pushState({}, '', url.href);
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                loadAccounts('?' + $(this).serialize());
                window.history.pushState({}, '', '?' + $(this).serialize());
            });

            // Dropdowns
            $(document).on('click', '.dropdown-btn', function(e) {
                e.stopPropagation();
                $('.dropdown-menu').not($(this).next('.dropdown-menu')).addClass('hidden');
                $(this).next('.dropdown-menu').toggleClass('hidden');
            });

            $(document).on('click', '.dropdown-option', function() {
                const val = $(this).data('value');
                const exp = $(this).data('export');
                const parent = $(this).closest('.relative');

                if (val !== undefined) {
                    parent.find('.selected-label').text($(this).text());
                    parent.find('input[type="hidden"]').val(val);
                    parent.find('.dropdown-menu').addClass('hidden');
                    $('#filter-form').submit();
                } else if (exp !== undefined) {
                    $('#pending-export-type').val(exp);
                    openModal('export-modal');
                    parent.find('.dropdown-menu').addClass('hidden');
                }
            });

            $(document).on('click', function() {
                $('.dropdown-menu').addClass('hidden');
            });

            // Export logic
            $('#confirm-export').on('click', function() {
                const type = $('#pending-export-type').val();
                const cols = $('input[name="export_cols[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                const params = new URLSearchParams(window.location.search);
                params.set('export', type);
                params.set('columns', cols.join(','));
                window.location.href = '{{ route('admin.forex-trading.accounts.index') }}?' + params
                    .toString();
                closeModal('export-modal');
            });

            // Forms & Modals
            window.openModal = function(id) {
                $('#' + id).removeClass('hidden').addClass('flex');
            };
            window.closeModal = function(id) {
                $('#' + id).addClass('hidden').removeClass('flex');
            };

            window.openCreditDebitModal = function(id, name) {
                $('#account_id_cd').val(id);
                $('#account_name_cd').text(name);
                openModal('creditDebitModal');
            };

            window.openStatusModal = function(id, current) {
                $('#account_id_status').val(id);
                $(`input[name="status"][value="${current}"]`).prop('checked', true);
                openModal('statusModal');
            };

            $('#creditDebitForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $(this).find('button[type="submit"]');
                const originalText = btn.html();
                btn.prop('disabled', true).html(
                    '<span class="animate-spin mr-2">●</span> {{ __('Processing...') }}');

                $.ajax({
                    url: '{{ route('admin.forex-trading.accounts.credit-debit') }}',
                    method: 'POST',
                    data: $(this).serialize() + '&_token={{ csrf_token() }}',
                    success: function(res) {
                        toastNotification(res.status, res.message);
                        if (res.status === 'success') {
                            closeModal('creditDebitModal');
                            loadAccounts(window.location.search);
                        }
                    },
                    error: function() {
                        toastNotification('error', 'Request failed');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            });

            $('#statusForm').on('submit', function(e) {
                e.preventDefault();
                const btn = $(this).find('button[type="submit"]');
                const originalText = btn.text();
                btn.prop('disabled', true).text('{{ __('Updating...') }}');

                $.ajax({
                    url: '{{ route('admin.forex-trading.accounts.update-status') }}',
                    method: 'POST',
                    data: $(this).serialize() + '&_token={{ csrf_token() }}',
                    success: function(res) {
                        toastNotification(res.status, res.message);
                        if (res.status === 'success') {
                            closeModal('statusModal');
                            loadAccounts(window.location.search);
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).text(originalText);
                    }
                });
            });

            window.deleteAccount = function(id, name) {
                confirmAction('{{ __('Are you sure?') }}', '{{ __('Delete account for') }} ' + name,
                    function() {
                        $.ajax({
                            url: '{{ route('admin.forex-trading.accounts.delete') }}',
                            method: 'POST',
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                toastNotification(res.status, res.message);
                                if (res.status === 'success') loadAccounts(window.location
                                    .search);
                            }
                        });
                    });
            };

            // Radio button styling
            $(document).on('change', 'input[type="radio"]', function() {
                const parent = $(this).closest('.radio-parent');
                if (parent.length) {
                    $(this).closest('.grid').find('.radio-parent').removeClass(
                        'border-accent-primary bg-accent-primary/5 active-status').addClass(
                        'border-white/5 bg-white/5');
                    parent.addClass('border-accent-primary bg-accent-primary/5 active-status').removeClass(
                        'border-white/5 bg-white/5');
                    $(this).closest('.grid').find('.status-label').addClass('opacity-60');
                    parent.find('.status-label').removeClass('opacity-60').addClass('opacity-100');
                }
            });
        });
    </script>
@endpush
