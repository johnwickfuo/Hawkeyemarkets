@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div id="bonds-content" class="space-y-8">
        {{-- Header & Stats --}}
        <div id="stats-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
                        {{ __('Total Bond Purchases') }}</div>
                </div>
            </div>

            {{-- Total Invested --}}
            <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                </div>
                <div class="relative z-10">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="text-3xl font-black text-white leading-none">
                        {{ getSetting('currency_symbol') }}{{ number_format($stats->total_invested, 2) }}</div>
                    <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                        {{ __('Total Principal') }}</div>
                </div>
            </div>

            {{-- Active Count --}}
            <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                </div>
                <div class="relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <div class="text-3xl font-black text-white leading-none">{{ number_format($stats->active_count) }}</div>
                    <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                        {{ __('Active (Locked)') }}</div>
                </div>
            </div>

            {{-- Expected Interest --}}
            <div class="bg-secondary border border-white/5 rounded-3xl p-6 relative overflow-hidden group">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                </div>
                <div class="relative z-10">
                    <div
                        class="w-12 h-12 rounded-2xl bg-violet-500/10 flex items-center justify-center text-violet-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="text-3xl font-black text-white leading-none">
                        {{ getSetting('currency_symbol') }}{{ number_format($stats->total_expected_interest, 2) }}</div>
                    <div class="text-[10px] text-text-secondary font-bold uppercase tracking-widest mt-2 opacity-60">
                        {{ __('Unpaid Interest') }}</div>
                </div>
            </div>
        </div>

        {{-- Main Table & Filters --}}
        <div id="bonds-wrapper"
            class="bg-secondary border border-white/5 rounded-[2rem] overflow-hidden shadow-2xl relative">
            {{-- Loading Spinner Overlay --}}
            <div id="loading-spinner"
                class="hidden absolute inset-0 bg-secondary/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-opacity duration-300">
                <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin">
                </div>
                <p class="mt-3 text-text-secondary text-xs font-bold uppercase tracking-widest">
                    {{ __('Syncing Bond Data...') }}</p>
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
                            <h4 class="text-white font-bold tracking-tight">{{ __('Bond Inventory') }}</h4>
                            <p
                                class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mt-0.5 opacity-50">
                                {{ __('List of all active and matured bond holdings') }}</p>
                        </div>
                    </div>

                    <form id="filter-form" method="GET" action="{{ route('admin.bonds.index') }}"
                        class="flex-1 max-w-2xl flex flex-wrap gap-3 lg:justify-end">
                        <div class="relative flex-1 group min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="{{ __('Search by cusip, name, username...') }}"
                                class="w-full h-12 bg-white/5 border border-white/10 rounded-2xl px-5 text-base font-medium text-white focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all outline-none placeholder:text-text-secondary/30">
                            <button type="submit"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-2 hover:bg-white/10 rounded-xl text-text-secondary transition-colors group-hover:text-accent-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="relative custom-dropdown" id="status-dropdown">
                            <input type="hidden" name="status" value="{{ request('status') }}" class="filter-input">
                            <button type="button"
                                class="bg-[#1E293B] border border-white/10 rounded-2xl h-12 px-6 text-xs text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[160px] dropdown-btn cursor-pointer font-bold uppercase tracking-widest">
                                <span class="selected-label">
                                    @if (request('status') == 'active')
                                        {{ __('Active') }}
                                    @elseif(request('status') == 'matured')
                                        {{ __('Matured') }}
                                    @else
                                        {{ __('All Status') }}
                                    @endif
                                </span>
                                <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <div
                                class="absolute top-full right-0 mt-2 w-full bg-[#0F172A] border border-white/10 rounded-xl shadow-2xl z-[80] py-2 hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200">
                                <div class="dropdown-option px-5 py-3 text-xs text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors font-bold uppercase tracking-widest"
                                    data-value="">{{ __('All Status') }}</div>
                                <div class="dropdown-option px-5 py-3 text-xs text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors font-bold uppercase tracking-widest"
                                    data-value="active">{{ __('Active') }}</div>
                                <div class="dropdown-option px-5 py-3 text-xs text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors font-bold uppercase tracking-widest"
                                    data-value="matured">{{ __('Matured') }}</div>
                            </div>
                        </div>

                        @if (request('search') || request('status'))
                            <button type="button" data-url="{{ route('admin.bonds.index') }}"
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
                                <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <div
                                class="absolute top-full right-0 mt-2 w-full lg:w-48 bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-[80] py-1 hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200">
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-export="csv">{{ __('CSV') }}</div>
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-export="sql">{{ __('SQL') }}</div>
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-export="pdf">{{ __('PDF') }}</div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto" id="table-content">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/[0.02]">
                            <th class="px-8 py-6 text-left shrink-0"><span
                                    class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('User') }}</span>
                            </th>
                            <th class="px-8 py-6 text-left"><span
                                    class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Bond') }}</span>
                            </th>
                            <th class="px-8 py-6 text-right"><span
                                    class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Principal') }}</span>
                            </th>
                            <th class="px-8 py-6 text-right"><span
                                    class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Coupon / Int.') }}</span>
                            </th>
                            <th class="px-8 py-6 text-right"><span
                                    class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Maturity') }}</span>
                            </th>
                            <th class="px-8 py-6 text-right"><span
                                    class="text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">{{ __('Status') }}</span>
                            </th>
                            <th class="px-8 py-6 text-right"><span
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
                                    <div
                                        class="text-sm font-black text-white leading-tight uppercase group-hover:text-accent-primary transition-colors">
                                        {{ $holding->cusip }}</div>
                                    <p
                                        class="text-[10px] text-text-secondary font-bold uppercase tracking-tighter opacity-50 leading-tight mt-0.5 truncate max-w-[150px]">
                                        {{ $holding->bond_name }}</p>
                                </td>
                                <td class="px-8 py-5 text-right font-mono text-sm">
                                    <div class="text-white font-black">
                                        {{ getSetting('currency_symbol') }}{{ number_format($holding->amount, 2) }}</div>
                                </td>
                                <td class="px-8 py-5 text-right font-mono text-sm">
                                    <div class="text-white font-black">{{ number_format($holding->coupon, 2) }}%</div>
                                    <div class="text-[10px] text-emerald-400 font-bold opacity-50">
                                        +{{ getSetting('currency_symbol') }}{{ number_format($holding->interest_amount, 2) }}
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right font-mono text-sm">
                                    <div class="text-white font-black">{{ $holding->maturity_date->format('Y-m-d') }}
                                    </div>
                                    <div class="text-[10px] text-text-secondary opacity-50 uppercase">
                                        {{ $holding->maturity_date->diffForHumans() }}</div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $holding->status == 'active' ? 'bg-amber-500/10 text-amber-500' : 'bg-emerald-500/10 text-emerald-500' }}">
                                        {{ $holding->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <button type="button"
                                        class="open-confirm-modal p-2.5 rounded-xl bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all cursor-pointer shadow-lg shadow-red-500/5"
                                        data-id="{{ $holding->id }}" data-action="delete"
                                        data-name="{{ $holding->cusip }} ({{ $holding->user->username }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-8 py-20 text-center">
                                    <div
                                        class="w-16 h-16 rounded-3xl bg-white/[0.02] border border-white/5 flex items-center justify-center text-text-secondary/20 mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                    </div>
                                    <h5 class="text-white font-bold">{{ __('No Bond Holdings') }}</h5>
                                    <p class="text-text-secondary text-xs mt-1">
                                        {{ __('There are currently no bond holdings matching your criteria.') }}</p>
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
    </div>

    {{-- Delete Confirmation Modal --}}
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
                <h3 class="text-2xl font-black text-white mb-2" id="confirm-title">{{ __('Remove Bond?') }}</h3>
                <p class="text-text-secondary text-sm leading-relaxed" id="confirm-message">
                    {{ __('Are you sure you want to delete this bond entry? This action cannot be undone.') }}
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
                                        'cusip' => ['label' => 'CUSIP', 'default' => true],
                                        'bond_name' => ['label' => 'Bond Name', 'default' => true],
                                        'amount' => ['label' => 'Principal', 'default' => true],
                                        'coupon' => ['label' => 'Coupon', 'default' => true],
                                        'interest_amount' => ['label' => 'Interest', 'default' => true],
                                        'maturity_date' => ['label' => 'Maturity', 'default' => true],
                                        'status' => ['label' => 'Status', 'default' => true],
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

            // Filter Dropdown Handler
            $(document).on('click', '#status-dropdown .dropdown-option[data-value]', function() {
                const val = $(this).data('value');
                const label = $(this).text();
                const $dropdown = $(this).closest('.custom-dropdown');

                $dropdown.find('.filter-input').val(val);
                $dropdown.find('.selected-label').text(label);
                $dropdown.find('.dropdown-menu').addClass('hidden');
                $dropdown.find('.dropdown-icon').removeClass('rotate-180');

                loadTable();
            });

            $(document).on('click', '.modal-close', function() {
                $(this).closest('.fixed.inset-0').addClass('hidden').removeClass('flex');
            });

            $(document).on('click', '#execute-export', function() {
                const columns = [];
                $('input[name="export_cols[]"]:checked').each(function() {
                    columns.push($(this).val());
                });

                if (columns.length === 0) {
                    if (typeof toastNotification === 'function') {
                        toastNotification('{{ __('Please select at least one column.') }}', 'error');
                    } else {
                        alert('{{ __('Please select at least one column.') }}');
                    }
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

            $(document).on('click', '#confirm-action-btn', function() {
                if (!currentId) return;

                const btn = $(this);
                const btnText = btn.find('.btn-text');
                const spinner = btn.find('.loading-spinner');

                btn.prop('disabled', true).addClass('opacity-50');
                btnText.text('{{ __('Deleting...') }}');
                spinner.removeClass('hidden');

                $.ajax({
                    url: "{{ route('admin.bonds.delete') }}",
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
                $('#bonds-wrapper').css('opacity', '0.5');

                $.ajax({
                    url: requestUrl,
                    type: 'GET',
                    success: function(data) {
                        $('#bonds-content').html($(data).find('#bonds-content').html());
                        window.history.pushState({}, '', requestUrl);
                    },
                    error: function() {
                        if (typeof toastNotification === 'function') {
                            toastNotification('{{ __('Error loading content.') }}', 'error');
                        }
                    },
                    complete: function() {
                        $('#loading-spinner').addClass('hidden').removeClass('flex');
                        $('#bonds-wrapper').css('opacity', '1');
                    }
                });
            }

            // AJAX search & filter handling
            $(document).on('submit', '#filter-form', function(e) {
                e.preventDefault();
                loadTable();
            });

            $(document).on('change', '.filter-select', function() {
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
