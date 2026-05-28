@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
@endpush

@section('panel')

    @push('css')
        <style>
            #pagination-wrapper nav {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            #pagination-wrapper nav svg {
                width: 1rem;
                height: 1rem;
            }
        </style>
    @endpush

@section('content')
    <div class="space-y-6">

        {{-- Message  if module is disabled --}}
        @if (!moduleEnabled('investment_module'))
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
                    {{ __('Investment module disabled') }}</h4>
                <p class="text-[10px] text-slate-500 max-w-[200px] mb-6 font-medium leading-relaxed italic">
                    {{ __('Investment module is disabled. Please enable the investment module in settings to initialize.') }}
                </p>
                <a href="{{ route('admin.settings.modules.index') }}"
                    class="px-5 py-2 rounded-xl bg-white/5 border border-white/10 text-[9px] font-black text-white uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all active:scale-95">
                    {{ __('Settings') }}
                </a>
            </div>
        @endif

        @if (moduleEnabled('investment_module'))

            {{-- Top Bar & Title --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-tight">{{ $page_title }}</h2>
                    <p class="text-sm text-text-secondary mt-1">{{ __('Track and manage individual investment returns.') }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button"
                        onclick="$('input[name=search], input[name=date]').val(''); $('#filter-form').submit();"
                        class="bg-white/5 border border-white/10 hover:bg-white/10 text-white px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 cursor-pointer shadow-sm">
                        <svg class="w-4 h-4 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        {{ __('Refresh Data') }}
                    </button>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4" id="stats-container">
                {{-- Total Distributed --}}
                <div
                    class="bg-secondary-dark border border-white/5 rounded-2xl p-6 relative overflow-hidden group hover:border-emerald-500/30 transition-all duration-300">
                    <div
                        class="absolute -right-6 -top-6 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-500">
                    </div>
                    <div class="relative z-10 flex items-start justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-text-secondary tracking-widest uppercase mb-2">
                                {{ __('Total Distributed') }}</p>
                            <h3 class="text-xl font-black text-white" id="stat-total-distributed">
                                {{ showAmount($stats['total_distributed']) }}</h3>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Today --}}
                <div
                    class="bg-secondary-dark border border-white/5 rounded-2xl p-6 relative overflow-hidden group hover:border-sky-500/30 transition-all duration-300">
                    <div
                        class="absolute -right-6 -top-6 w-32 h-32 bg-sky-500/10 rounded-full blur-3xl group-hover:bg-sky-500/20 transition-all duration-500">
                    </div>
                    <div class="relative z-10 flex items-start justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-text-secondary tracking-widest uppercase mb-2">
                                {{ __('Today') }}</p>
                            <h3 class="text-xl font-black text-white" id="stat-today">
                                {{ showAmount($stats['today']) }}</h3>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-500 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- This Week --}}
                <div
                    class="bg-secondary-dark border border-white/5 rounded-2xl p-6 relative overflow-hidden group hover:border-indigo-500/30 transition-all duration-300">
                    <div
                        class="absolute -right-6 -top-6 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all duration-500">
                    </div>
                    <div class="relative z-10 flex items-start justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-text-secondary tracking-widest uppercase mb-2">
                                {{ __('This Week') }}</p>
                            <h3 class="text-xl font-black text-white" id="stat-this-week">
                                {{ showAmount($stats['this_week']) }}</h3>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- This Month --}}
                <div
                    class="bg-secondary-dark border border-white/5 rounded-2xl p-6 relative overflow-hidden group hover:border-violet-500/30 transition-all duration-300">
                    <div
                        class="absolute -right-6 -top-6 w-32 h-32 bg-violet-500/10 rounded-full blur-3xl group-hover:bg-violet-500/20 transition-all duration-500">
                    </div>
                    <div class="relative z-10 flex items-start justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-text-secondary tracking-widest uppercase mb-2">
                                {{ __('This Month') }}</p>
                            <h3 class="text-xl font-black text-white" id="stat-this-month">
                                {{ showAmount($stats['this_month']) }}</h3>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center text-violet-500 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Unique Investors --}}
                <div
                    class="bg-secondary-dark border border-white/5 rounded-2xl p-6 relative overflow-hidden group hover:border-blue-500/30 transition-all duration-300">
                    <div
                        class="absolute -right-6 -top-6 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all duration-500">
                    </div>
                    <div class="relative z-10 flex items-start justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-text-secondary tracking-widest uppercase mb-2">
                                {{ __('Investors') }}</p>
                            <h3 class="text-xl font-black text-white" id="stat-unique-investors">
                                {{ $stats['unique_investors'] }}</h3>
                        </div>
                        <div
                            class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Graph and chart --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
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
                                    {{ __('Earnings Growth Trend') }}
                                </div>
                                <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                                    {{ __('Total Earnings Distributed') }} · {{ getSetting('currency') }}
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
                            <canvas id="earningsTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Plan Distribution (1/3 width) --}}
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
                            <div class="text-sm font-bold text-white tracking-wide">{{ __('Earnings by Plan') }}</div>
                            <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                                {{ __('Total Earnings Distributed') }}
                            </p>
                        </div>

                        <div class="relative flex-1" style="min-height: 250px;">
                            <canvas id="planDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Table Area --}}
            <div class="bg-secondary-dark border border-white/5 rounded-2xl overflow-hidden relative"
                id="table-container">

                {{-- Loading Overlay --}}
                <div id="table-loading"
                    class="absolute inset-0 bg-secondary-dark/80 backdrop-blur-sm z-50 hidden items-center justify-center">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="h-8 w-8 text-accent-primary animate-spin" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium text-text-secondary">{{ __('Loading data...') }}</span>
                    </div>
                </div>

                {{-- Table Controls --}}
                <div
                    class="p-6 border-b border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h3 class="text-lg font-bold text-white">{{ __('Earning History') }}</h3>

                    <form id="filter-form" action="{{ route('admin.investments.earnings') }}" method="GET"
                        class="flex flex-wrap gap-2 items-center w-full md:w-auto">

                        <div class="relative flex-grow md:flex-grow-0">
                            <input type="text" name="search" placeholder="{{ __('Search records...') }}"
                                value="{{ request('search') }}"
                                class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-white text-base placeholder-text-secondary focus:ring-1 focus:ring-accent-primary focus:border-accent-primary outline-none w-full lg:w-48">
                        </div>

                        <div class="relative">
                            <input type="text" name="date" id="date-range" placeholder="{{ __('Date Range') }}"
                                value="{{ request('date') }}"
                                class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-white text-base placeholder-text-secondary focus:ring-1 focus:ring-accent-primary focus:border-accent-primary outline-none w-full lg:w-56 cursor-pointer">
                        </div>

                        <div class="relative custom-dropdown" id="export-dropdown">
                            <button type="button"
                                class="bg-[#1E293B] border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[100px] dropdown-btn cursor-pointer">
                                <span class="selected-label">{{ __('Export') }}</span>
                                <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <div
                                class="absolute top-full right-0 mt-2 w-full lg:w-48 bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-[60] py-1 hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200">
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-export="csv">CSV</div>
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-export="sql">SQL</div>
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-export="pdf">PDF</div>
                            </div>
                        </div>

                        <button type="submit" id="filter-submit"
                            class="p-2 bg-accent-primary/10 text-accent-primary rounded-lg hover:bg-accent-primary/20 transition-all cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto overflow-y-clip" id="table-content">
                    @include('templates.bento.blades.admin.investments.partials.earnings-table')
                </div>

                {{-- Pagination --}}
                <div class="p-6 border-t border-white/5 ajax-pagination" id="pagination-wrapper">
                    {{ $earnings->links('templates.bento.blades.partials.pagination') }}
                </div>
            </div>
        @endif
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-primary-dark/80 backdrop-blur-sm transition-opacity"
            onclick="closeModal('deleteModal')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-[#0F172A] border relative border-white/10 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all duration-300 scale-95 opacity-0"
                id="deleteModal-content">
                <div class="p-6">
                    <div class="w-16 h-16 rounded-full bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white text-center mb-2">{{ __('Delete Earning Record') }}</h3>
                    <p class="text-sm text-text-secondary text-center mb-8">
                        {{ __('Are you sure you want to delete this earning record? This action cannot be undone and will permanently remove the record from the database.') }}
                    </p>
                    <input type="hidden" id="delete-earning-id">
                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeModal('deleteModal')"
                            class="flex-1 px-4 py-2 border border-white/10 rounded-xl text-white font-bold hover:bg-white/5 transition-colors modal-close">{{ __('Cancel') }}</button>
                        <button type="button" id="confirm-delete-btn"
                            class="flex-1 px-4 py-2 bg-red-500 text-white rounded-xl font-bold hover:bg-red-600 transition-colors cursor-pointer">{{ __('Delete Record') }}</button>
                    </div>
                </div>
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
                                    $columns = [
                                        'created_at' => ['label' => 'Date', 'default' => true],
                                        'username' => ['label' => 'User', 'default' => true],
                                        'plan_name' => ['label' => 'Plan', 'default' => true],
                                        'investment_goal' => ['label' => 'Goal', 'default' => true],
                                        'risk_profile' => ['label' => 'Risk', 'default' => true],
                                        'amount' => ['label' => 'Earning', 'default' => true],
                                        'note' => ['label' => 'Note', 'default' => true],
                                    ];
                                @endphp
                                @foreach ($columns as $key => $col)
                                    <label
                                        class="flex items-center gap-3 p-3 rounded-xl border border-white/5 bg-white/5 hover:border-accent-primary/50 transition-all cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="export_cols[]" value="{{ $key }}"
                                                {{ $col['default'] ? 'checked' : '' }}
                                                class="w-5 h-5 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary focus:ring-offset-0 cursor-pointer">
                                        </div>
                                        <span
                                            class="text-[11px] text-slate-400 group-hover:text-white transition-colors">{{ __($col['label']) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/5 flex gap-3">
                            <input type="hidden" id="pending-export-type" value="">
                            <button type="button"
                                class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all modal-close cursor-pointer">
                                {{ __('Cancel') }}
                            </button>
                            <button type="button" id="confirm-export"
                                class="flex-1 px-4 py-3 rounded-xl bg-accent-primary text-white font-bold hover:bg-accent-primary/90 shadow-lg shadow-accent-primary/20 transition-all cursor-pointer">
                                {{ __('Generate Export') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            // ── Data from PHP ────────────────────────────────────────────────────────
            const GRAPH_DATA = @json($graph_data);
            const PLAN_CHART_DATA = @json($plan_chart_data);

            // ── Shared Chart.js defaults ─────────────────────────────────────────────
            const CURRENCY = "{{ getSetting('currency') }}";
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
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: CURRENCY
                            }).format(context.parsed.y);
                        }
                        return label;
                    }
                }
            };

            // ── Earnings Trend Chart ───────────────────────────────────────────────
            const trendCtx = document.getElementById('earningsTrendChart').getContext('2d');
            const trendChart = new Chart(trendCtx, {
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
                        tooltip: tooltipOptions,
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255,255,255,0.03)'
                            },
                            ticks: {
                                maxTicksLimit: 8,
                                maxRotation: 0
                            },
                        },
                        y: {
                            grid: {
                                color: 'rgba(255,255,255,0.04)'
                            },
                            ticks: {
                                maxTicksLimit: 5
                            },
                            beginAtZero: true,
                        },
                    },
                },
            });

            // ── Plan Distribution Chart ──────────────────────────────────────────────
            const planCtx = document.getElementById('planDistributionChart').getContext('2d');
            const planChart = new Chart(planCtx, {
                type: 'bar',
                data: {
                    labels: PLAN_CHART_DATA.map(d => d.name),
                    datasets: [{
                        label: 'Total Earnings',
                        data: PLAN_CHART_DATA.map(d => d.earnings),
                        backgroundColor: 'rgba(52, 211, 153, 0.6)',
                        borderColor: '#34d399',
                        borderWidth: 1,
                        borderRadius: 4,
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
                        tooltip: {
                            ...tooltipOptions,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    const val = context.raw;
                                    label += new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: CURRENCY
                                    }).format(val);
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255,255,255,0.03)'
                            },
                            stacked: false
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            stacked: false
                        }
                    }
                }
            });

            // ── Trend Logic ─────────────────────────────────────────────────────────
            let activePeriod = '7d';
            const SERIES_META = {
                earnings: {
                    label: 'Earnings',
                    color: '#34d399'
                }
            };

            function updateTrendChart() {
                const periodData = GRAPH_DATA[activePeriod];
                if (!periodData || !periodData.earnings) return;

                const labels = periodData.earnings.labels;

                trendChart.data.labels = labels;
                trendChart.data.datasets = Object.entries(SERIES_META).map(([key, meta]) => ({
                    label: meta.label,
                    data: periodData[key].data,
                    borderColor: meta.color,
                    backgroundColor: meta.color + '18',
                    fill: true,
                    tension: 0.4,
                    pointRadius: labels.length <= 15 ? 3 : 0,
                    pointHoverRadius: 5,
                    borderWidth: 2,
                }));

                trendChart.update('none');

                // Render Legend
                const legendEl = document.getElementById('graph-legend');
                legendEl.innerHTML = '';
                Object.values(SERIES_META).forEach(meta => {
                    legendEl.insertAdjacentHTML('beforeend', `
                        <span class="flex items-center gap-1.5 text-[10px] text-slate-400">
                            <span style="width:8px;height:8px;border-radius:50%;background:${meta.color};display:inline-block;"></span>
                            ${meta.label}
                        </span>
                    `);
                });
            }

            $('.graph-period-btn').on('click', function() {
                activePeriod = $(this).data('period');
                $('.graph-period-btn').removeClass('bg-white/10 text-white').addClass('text-slate-500');
                $(this).removeClass('text-slate-500').addClass('bg-white/10 text-white');
                updateTrendChart();
            });

            updateTrendChart();

            // Dropdown Toggle
            $(document).on('click', '.dropdown-btn', function(e) {
                e.stopPropagation();
                const menu = $(this).siblings('.dropdown-menu');
                $('.dropdown-menu').not(menu).addClass('hidden');
                menu.toggleClass('hidden');
                $(this).find('.dropdown-icon').toggleClass('rotate-180');
            });

            // Close dropdowns on outside click
            $(document).on('click', function() {
                $('.dropdown-menu').addClass('hidden');
                $('.dropdown-icon').removeClass('rotate-180');
            });

            // Date Picker (Flatpickr)
            flatpickr("#date-range", {
                mode: "range",
                dateFormat: "Y-m-d",
                theme: "dark",
                background: "#0F172A",
            });

            // Delete Modal Logic
            window.openDeleteModal = function(id) {
                $('#delete-earning-id').val(id);
                const $modal = $('#deleteModal');
                const $content = $('#deleteModal-content');
                $modal.removeClass('hidden');
                setTimeout(() => {
                    $content.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
                }, 10);
            };

            window.closeModal = function(modalId) {
                const $modal = $('#' + modalId);
                const $content = $('#' + modalId + '-content');
                $content.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                setTimeout(() => {
                    $modal.addClass('hidden');
                }, 300);
            };

            $(document).on('click', '.modal-close', function(e) {
                if (e.target === this || $(this).hasClass('modal-close')) {
                    const modal = $(this).closest('.fixed.inset-0');
                    if (modal.length) {
                        if (modal.attr('id') === 'deleteModal') {
                            closeModal('deleteModal');
                        } else {
                            modal.addClass('hidden');
                        }
                    }
                }
            });

            $('#confirm-delete-btn').on('click', function() {
                const id = $('#delete-earning-id').val();
                const $btn = $(this);
                const originalText = $btn.text();

                $btn.html(
                    '<svg class="w-5 h-5 animate-spin mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'
                );
                $btn.prop('disabled', true);

                $.ajax({
                    url: `{{ route('admin.investments.earnings') }}/delete/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success || response.status === 'success') {
                            toastNotification(response.message, 'success');
                            closeModal('deleteModal');
                            loadTable();
                        } else {
                            toastNotification(response.message || 'Error deleting record.',
                                'error');
                            $btn.html(originalText).prop('disabled', false);
                        }
                    },
                    error: function() {
                        toastNotification('{{ __('An error occurred during deletion.') }}',
                            'error');
                        $btn.html(originalText).prop('disabled', false);
                    }
                });
            });

            // AJAX Table Loading
            function loadTable(url = null) {
                const requestUrl = url || $('#filter-form').attr('action');
                const formData = url ? null : $('#filter-form').serialize();

                $('#table-loading').removeClass('hidden').addClass('flex');

                $.ajax({
                    url: requestUrl,
                    type: 'GET',
                    data: formData,
                    success: function(response) {
                        $('#table-content').html(response.html);
                        $('#pagination-wrapper').html(response.pagination);

                        // Update Stats
                        if (response.stats) {
                            $('#stat-total-distributed').text('{{ getSetting('currency_symbol') }}' +
                                parseFloat(response.stats.total_distributed || 0).toFixed(
                                    {{ getSetting('decimal_places') }}));
                            $('#stat-today').text('{{ getSetting('currency_symbol') }}' + parseFloat(
                                response.stats.today || 0).toFixed(
                                {{ getSetting('decimal_places') }}));
                            $('#stat-this-week').text('{{ getSetting('currency_symbol') }}' +
                                parseFloat(response.stats.this_week || 0).toFixed(
                                    {{ getSetting('decimal_places') }}));
                            $('#stat-this-month').text('{{ getSetting('currency_symbol') }}' +
                                parseFloat(response.stats.this_month || 0).toFixed(
                                    {{ getSetting('decimal_places') }}));
                            $('#stat-unique-investors').text(response.stats.unique_investors);
                        }

                        $('#table-loading').addClass('hidden').removeClass('flex');

                        // Update URL silently
                        const newUrl = url || (requestUrl + '?' + formData);
                        window.history.pushState({}, '', newUrl);
                    },
                    error: function() {
                        toastNotification('{{ __('Failed to load data.') }}', 'error');
                        $('#table-loading').addClass('hidden').removeClass('flex');
                    }
                });
            }

            // AJAX Filtering
            $(document).on('submit', '#filter-form', function(e) {
                e.preventDefault();
                loadTable();
            });

            // AJAX Pagination
            $(document).on('click', '.ajax-pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                loadTable(url);
            });

            // Export Logic
            $(document).on('click', '#export-dropdown .dropdown-option', function() {
                const type = $(this).data('export');
                $('#pending-export-type').val(type);
                $('#export-modal').removeClass('hidden');
                $('.dropdown-menu').addClass('hidden');
                $('.dropdown-icon').removeClass('rotate-180');
            });

            $(document).on('click', '#confirm-export', function() {
                const type = $('#pending-export-type').val();
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

                const exportUrl = `${baseUrl}?${params}&export=${type}&columns=${columns.join(',')}`;
                window.location.href = exportUrl;

                $('#export-modal').addClass('hidden');
            });
        });
    </script>
@endpush
