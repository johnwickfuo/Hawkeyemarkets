@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div class="space-y-8">
        {{-- Transactions Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-accent-primary/20 rounded-lg text-accent-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Total Transactions') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['total_count']) }}</h4>
                        <div class="mt-2 text-[9px] flex items-center gap-1.5 font-medium">
                            <span class="text-emerald-400 bg-emerald-400/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ number_format($stats['total_credit_count']) }} {{ __('Cr') }}
                            </span>
                            <span class="text-red-400 bg-red-400/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                    </path>
                                </svg>
                                {{ number_format($stats['total_debit_count']) }} {{ __('Dr') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-emerald-500/20 rounded-lg text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Total Volume') }}</p>
                        <h4 class="text-xl font-bold text-white">
                            {{ showAmount($stats['total_volume']) }}</h4>
                        <div class="mt-2 text-[9px] flex items-center gap-1.5 font-medium">
                            <span class="text-emerald-400 bg-emerald-400/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ showAmount($stats['total_credit_volume']) }}
                            </span>
                            <span class="text-red-400 bg-red-400/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                    </path>
                                </svg>
                                {{ showAmount($stats['total_debit_volume']) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-amber-500/20 rounded-lg text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Today Count') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['today_count']) }}</h4>
                        <div class="mt-2 text-[9px] flex items-center gap-1.5 font-medium">
                            <span class="text-emerald-400 bg-emerald-400/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ number_format($stats['today_credit_count']) }} {{ __('Cr') }}
                            </span>
                            <span class="text-red-400 bg-red-400/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                    </path>
                                </svg>
                                {{ number_format($stats['today_debit_count']) }} {{ __('Dr') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-indigo-500/20 rounded-lg text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Today Volume') }}</p>
                        <h4 class="text-xl font-bold text-white">
                            {{ showAmount($stats['today_volume']) }}</h4>
                        <div class="mt-2 text-[9px] flex items-center gap-1.5 font-medium">
                            <span class="text-emerald-400 bg-emerald-400/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ showAmount($stats['today_credit_volume']) }}
                            </span>
                            <span class="text-red-400 bg-red-400/10 px-1.5 py-0.5 rounded flex items-center gap-1">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4">
                                    </path>
                                </svg>
                                {{ showAmount($stats['today_debit_volume']) }}
                            </span>
                        </div>
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
                                {{ __('Transaction Volume Trend') }}
                            </div>
                            <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                                {{ __('Credits vs Debits') }} · {{ getSetting('currency') }}
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
                        <canvas id="transactionTrendChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Type Distribution (1/3 width) --}}
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
                        <div class="text-sm font-bold text-white tracking-wide">{{ __('Volume by Type') }}</div>
                        <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                            {{ __('Total volume distributed across transaction types.') }}
                        </p>
                    </div>

                    <div class="relative flex-1" style="min-height: 250px;">
                        <canvas id="typeDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Transactions Table Section --}}
        <div class="bg-secondary border border-white/5 rounded-2xl overflow-hidden relative" id="transactions-wrapper">
            {{-- Loading Spinner Overlay --}}
            <div id="loading-spinner"
                class="hidden absolute inset-0 bg-secondary/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-opacity duration-300">
                <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin">
                </div>
                <p class="mt-3 text-text-secondary font-medium">{{ __('Loading transactions...') }}</p>
            </div>

            {{-- Table Filter Header --}}
            <div class="p-6 border-b border-white/5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    {{ __('Transactions History') }}
                </h3>

                {{-- Filters --}}
                <form id="filter-form" action="{{ route('admin.transactions.index') }}" method="GET"
                    class="flex flex-wrap gap-2 items-center">
                    @if (request('user_id'))
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                    @endif
                    <div class="relative w-full lg:w-auto">
                        <input type="text" name="search" placeholder="{{ __('Search user, trx ref...') }}"
                            value="{{ request('search') }}"
                            class="bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white text-sm placeholder-text-secondary focus:ring-1 focus:ring-accent-primary focus:border-accent-primary outline-none w-full lg:w-64">
                        <button type="submit"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-text-secondary hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="relative custom-dropdown" id="type-filter-dropdown">
                        <input type="hidden" name="type" id="type-input" value="{{ request('type', 'all') }}">
                        <button type="button"
                            class="bg-[#1E293B] border border-white/10 rounded-lg px-4 py-2 text-sm text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[140px] dropdown-btn cursor-pointer">
                            <span class="selected-label">
                                @if (request('type') && request('type') !== 'all')
                                    {{ __(ucfirst(request('type'))) }}
                                @else
                                    {{ __('All Types') }}
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-text-secondary pointer-events-none transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <ul
                            class="dropdown-menu absolute z-50 mt-1 w-full bg-[#1E293B] border border-white/10 rounded-lg shadow-xl hidden opacity-0 transition-opacity duration-200 overflow-hidden text-sm">
                            <li class="dropdown-option px-4 py-2 hover:bg-white/5 text-white cursor-pointer transition-colors"
                                data-value="all">{{ __('All Types') }}</li>
                            @foreach ($types as $typeOption)
                                <li class="dropdown-option px-4 py-2 hover:bg-white/5 text-white cursor-pointer transition-colors"
                                    data-value="{{ $typeOption }}">{{ __(ucfirst($typeOption)) }}</li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Bulk Delete Button (Hidden by default) --}}
                    <button type="button" id="bulk-delete-btn" onclick="confirmBulkDelete()"
                        class="hidden bg-red-600/10 border border-red-500/20 text-red-500 hover:bg-red-600 hover:text-white rounded-lg px-4 py-2 text-sm font-bold transition-all items-center gap-2 cursor-pointer shadow-lg shadow-red-500/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        {{ __('Delete Selected') }} (<span id="selected-count">0</span>)
                    </button>

                    <div class="relative custom-dropdown" id="export-dropdown">
                        <button type="button"
                            class="bg-[#1E293B] border border-white/10 rounded-lg px-4 py-2 text-sm text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[140px] dropdown-btn cursor-pointer">
                            <span class="selected-label">{{ __('Export') }}</span>
                            <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div
                            class="dropdown-menu absolute right-0 mt-2 w-full lg:w-48 bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-[60] py-1 hidden opacity-0 transition-opacity duration-200">
                            <div class="dropdown-option px-4 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                data-export="csv">CSV</div>
                            <div class="dropdown-option px-4 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                data-export="sql">SQL</div>
                            <div class="dropdown-option px-4 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                data-export="pdf">PDF</div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table Wrapper --}}
            <div class="overflow-x-auto min-h-[300px]">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-white/[0.02]">
                            <th class="px-6 py-4 border-b border-white/5 w-10 text-center">
                                <input type="checkbox" id="select-all-checkbox"
                                    class="w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary focus:ring-offset-0 cursor-pointer">
                            </th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                {{ __('User / Reference') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                {{ __('Amount') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-center">
                                {{ __('Type') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-center">
                                {{ __('Status') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-center">
                                {{ __('Date') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-right">
                                {{ __('Balance') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-right">
                                {{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($transactions as $transaction)
                            <tr class="hover:bg-white/[0.01] transition-all group">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $transaction->id }}"
                                        class="row-checkbox w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary focus:ring-offset-0 cursor-pointer">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($transaction->user->photo)
                                            <div
                                                class="w-10 h-10 rounded-full border border-white/10 shadow-lg overflow-hidden shrink-0">
                                                <img src="{{ asset('storage/profile/' . $transaction->user->photo) }}"
                                                    alt="{{ $transaction->user->username }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-full bg-accent-primary/10 flex items-center justify-center text-accent-primary font-black text-xs shrink-0 border border-accent-primary/20">
                                                {{ substr($transaction->user->username, 0, 2) }}
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('admin.users.detail', $transaction->user->id) }}"
                                                class="text-sm text-white font-bold hover:text-accent-primary transition-colors block">{{ $transaction->user->first_name }}
                                                {{ $transaction->user->last_name }}</a>
                                            <span
                                                class="text-[10px] text-text-secondary uppercase tracking-widest font-mono">#{{ $transaction->reference }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div
                                        class="text-sm font-bold {{ $transaction->type === 'credit' ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $transaction->type === 'credit' ? '+' : '-' }}{{ showAmount($transaction->amount) }}
                                    </div>
                                    <div class="text-[10px] text-text-secondary truncate max-w-[150px]"
                                        title="{{ $transaction->description }}">{{ $transaction->description }}</div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div
                                        class="inline-flex px-2 py-1 rounded bg-white/5 border border-white/10 text-xs font-semibold text-white capitalize">
                                        {{ __($transaction->type) }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($transaction->status === 'completed' || $transaction->status === 'approved' || $transaction->status === 'success')
                                        <div
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                            <span
                                                class="text-[10px] font-black uppercase tracking-wider">{{ __($transaction->status) }}</span>
                                        </div>
                                    @elseif($transaction->status === 'pending')
                                        <div
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-amber-500/10 border border-amber-500/20 text-amber-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                            <span
                                                class="text-[10px] font-black uppercase tracking-wider">{{ __($transaction->status) }}</span>
                                        </div>
                                    @else
                                        <div
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-red-500/10 border border-red-500/20 text-red-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                            <span
                                                class="text-[10px] font-black uppercase tracking-wider">{{ __($transaction->status) }}</span>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="text-xs text-white font-medium">
                                        {{ $transaction->created_at->format('M d, Y') }}</div>
                                    <div class="text-[10px] text-text-secondary">
                                        {{ $transaction->created_at->format('h:i A') }}</div>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="text-sm font-bold text-white">
                                        {{ showAmount($transaction->new_balance) }}
                                    </div>
                                    <div class="text-[10px] text-text-secondary">{{ $transaction->currency }}</div>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" onclick="confirmDelete('{{ $transaction->id }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 border border-white/10 text-text-secondary hover:text-red-400 hover:bg-red-500/10 hover:border-red-500/20 transition-all cursor-pointer"
                                            title="{{ __('Delete Record') }}">
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
                                <td colspan="100%" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-text-secondary">
                                        <div
                                            class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium">{{ __('No transactions found') }}</p>
                                        <p class="text-xs opacity-70 mt-1">
                                            {{ __('Try adjusting your search or filters') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($transactions->hasPages())
                <div class="p-6 border-t border-white/5 bg-black/20 ajax-pagination">
                    {{ $transactions->withQueryString()->links('templates.bento.blades.partials.pagination') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-primary-dark/80 backdrop-blur-sm z-40 modal-overlay cursor-pointer"
            onclick="closeDeleteModal()"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div
                class="relative bg-secondary-dark border border-white/10 rounded-2xl shadow-2xl text-left overflow-hidden transform transition-all sm:max-w-md w-full z-50 animate-modal-enter">

                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeDeleteModal()"
                        class="text-text-secondary hover:text-white focus:outline-none transition-colors cursor-pointer">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-8 sm:flex sm:items-start text-center sm:text-left">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-500/20 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 sm:mt-0 sm:ml-4 flex-1">
                        <h3 class="text-lg font-bold text-white mb-2">{{ __('Delete Transaction Record') }}</h3>
                        <div class="mt-2">
                            <p class="text-sm text-text-secondary">
                                {{ __('Are you sure you want to delete this transaction record? This action cannot be undone.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-black/20 px-6 py-4 border-t border-white/5 flex flex-col sm:flex-row-reverse sm:gap-3">
                    <button type="button" id="confirmDeleteBtn"
                        class="w-full inline-flex justify-center items-center rounded-xl bg-red-600 hover:bg-red-500 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-red-600/20 transition-all focus:outline-none sm:w-auto relative overflow-hidden group">
                        <span class="btn-text">{{ __('Delete Record') }}</span>
                        <div class="btn-loader hidden absolute inset-0 flex items-center justify-center bg-red-600">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </button>
                    <button type="button" onclick="closeDeleteModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-xl bg-white/5 border border-white/10 px-4 py-2 text-sm font-bold text-white hover:bg-white/10 transition-colors focus:outline-none sm:mt-0 sm:w-auto">
                        {{ __('Cancel') }}
                    </button>
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
                                        'amount' => ['label' => 'Amount', 'default' => true],
                                        'currency' => ['label' => 'Currency', 'default' => true],
                                        'converted_amount' => ['label' => 'Converted', 'default' => false],
                                        'converted_currency' => ['label' => 'Conv. Cur', 'default' => false],
                                        'rate' => ['label' => 'Rate', 'default' => false],
                                        'new_balance' => ['label' => 'New Balance', 'default' => true],
                                        'type' => ['label' => 'Type', 'default' => true],
                                        'reference' => ['label' => 'Trx Ref', 'default' => true],
                                        'description' => ['label' => 'Description', 'default' => false],
                                        'status' => ['label' => 'Status', 'default' => true],
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
        <script>
            let currentDeleteId = null;
            let currentDeleteIds = [];

            // ── Bulk Selection Logic ─────────────────────────────────────────────
            function updateBulkDeleteUI() {
                const checkedCount = $('.row-checkbox:checked').length;
                $('#selected-count').text(checkedCount);
                if (checkedCount > 0) {
                    $('#bulk-delete-btn').removeClass('hidden').addClass('flex');
                } else {
                    $('#bulk-delete-btn').addClass('hidden').removeClass('flex');
                }
            }

            $(document).on('change', '#select-all-checkbox', function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
                updateBulkDeleteUI();
            });

            $(document).on('change', '.row-checkbox', function() {
                if ($('.row-checkbox').length === $('.row-checkbox:checked').length) {
                    $('#select-all-checkbox').prop('checked', true);
                } else {
                    $('#select-all-checkbox').prop('checked', false);
                }
                updateBulkDeleteUI();
            });

            // ── Custom Dropdowns functionality ──────────────────────────────────────
            $(document).on('click', '.dropdown-btn', function(e) {
                e.preventDefault();
                const dropdownMenu = $(this).next('.dropdown-menu');
                $('.dropdown-menu').not(dropdownMenu).addClass('hidden opacity-0');

                if (dropdownMenu.hasClass('hidden')) {
                    dropdownMenu.removeClass('hidden');
                    setTimeout(() => dropdownMenu.removeClass('opacity-0'), 10);
                } else {
                    dropdownMenu.addClass('opacity-0');
                    setTimeout(() => dropdownMenu.addClass('hidden'), 200);
                }
            });

            // Close dropdowns on outside click
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.custom-dropdown').length) {
                    $('.dropdown-menu').addClass('opacity-0');
                    setTimeout(() => $('.dropdown-menu').addClass('hidden'), 200);
                }
            });

            // Handle Dropdown Option Selection (Filter options)
            $(document).on('click', '.dropdown-option[data-value]', function() {
                const value = $(this).data('value');
                const label = $(this).text();
                const wrapper = $(this).closest('.custom-dropdown');

                wrapper.find('input[type="hidden"]').val(value);
                wrapper.find('.selected-label').text(label);

                // Try to load via AJAX filtering instead of full page submit
                $('#filter-form').submit();
            });

            // Handle Search Form Submit
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                const url = new URL($(this).attr('action'));
                const searchData = new FormData(this);

                for (let [key, value] of searchData.entries()) {
                    if (value && value !== 'all') {
                        url.searchParams.set(key, value);
                    } else if (value === 'all' && url.searchParams.has(key)) {
                        url.searchParams.delete(key);
                    }
                }

                // Reset page on search
                url.searchParams.set('page', 1);

                window.history.pushState({}, '', url);
                loadTable(url.toString());
            });

            // Handle Pagination Click
            $(document).on('click', '.ajax-pagination a', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                window.history.pushState({}, '', url);
                loadTable(url);
            });

            // Export Logic
            $(document).on('click', '#export-dropdown .dropdown-option', function() {
                const type = $(this).data('export');

                if (type === 'sql') {
                    const url = new URL(window.location.href);
                    url.searchParams.set('export', type);
                    window.location.href = url.toString();
                    return;
                }

                $('#pending-export-type').val(type);
                $('#export-modal').removeClass('hidden');
                $('.dropdown-menu').addClass('hidden');
                $('.dropdown-icon').removeClass('rotate-180');
            });

            $(document).on('click', '#confirm-export', function() {
                const type = $('#pending-export-type').val();
                if (!type) {
                    toastNotification('{{ __('Please select an export type.') }}', 'error');
                    return;
                }

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
                paramsObj.set('export', type);
                paramsObj.set('columns', columns.join(','));

                // Close modal implicitly via existing listeners or explicitly
                $('#export-modal').addClass('hidden');

                window.location.href = baseUrl + '?' + paramsObj.toString();
            });

            // Close Modal on Cancel button clicks (Catch-all for modals)
            $('.modal-close').on('click', function() {
                $(this).closest('.fixed.inset-0').addClass('hidden');
            });

            // Core Table Load Function
            function loadTable(url) {
                $('#loading-spinner').removeClass('hidden');
                $('#transactions-wrapper').addClass('opacity-70 pointer-events-none');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        const newTable = $(response).find('table');
                        const newPagination = $(response).find('.ajax-pagination');

                        $('table').replaceWith(newTable);

                        if (newPagination.length > 0) {
                            if ($('.ajax-pagination').length > 0) {
                                $('.ajax-pagination').replaceWith(newPagination);
                            } else {
                                $('#transactions-wrapper').append(newPagination);
                            }
                        } else {
                            $('.ajax-pagination').remove();
                        }
                    },
                    error: function() {
                        toastNotification("{{ __('Error loading content.') }}", 'error');
                    },
                    complete: function() {
                        setTimeout(() => {
                            $('#loading-spinner').addClass('hidden');
                            $('#transactions-wrapper').removeClass('opacity-70 pointer-events-none');
                        }, 300);
                    }
                });
            }

            // MODAL FUNCTIONS
            window.confirmDelete = function(id) {
                currentDeleteId = id;
                currentDeleteIds = []; // clear bulk array
                openDeleteModal();
            };

            window.confirmBulkDelete = function() {
                currentDeleteId = null; // clear single id
                currentDeleteIds = [];
                $('.row-checkbox:checked').each(function() {
                    currentDeleteIds.push($(this).val());
                });

                if (currentDeleteIds.length === 0) return;
                openDeleteModal();
            };

            function openDeleteModal() {
                const modal = $('#deleteModal');
                modal.removeClass('hidden');
                setTimeout(() => {
                    modal.find('.modal-overlay').addClass('opacity-100');
                    modal.find('.animate-modal-enter').addClass('scale-100 opacity-100').removeClass(
                        'scale-95 opacity-0');
                }, 10);
            }

            window.closeDeleteModal = function() {
                const modal = $('#deleteModal');

                modal.find('.modal-overlay').removeClass('opacity-100');
                modal.find('.animate-modal-enter').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');

                setTimeout(() => {
                    modal.addClass('hidden');
                    currentDeleteId = null;
                    currentDeleteIds = [];
                }, 200);
            };

            // Execute Delete Request (Handles Single and Bulk)
            $('#confirmDeleteBtn').on('click', function() {
                if (!currentDeleteId && currentDeleteIds.length === 0) return;

                const btn = $(this);
                btn.find('.btn-text').addClass('opacity-0');
                btn.find('.btn-loader').removeClass('hidden');
                btn.prop('disabled', true);

                let url, data;

                if (currentDeleteId) {
                    // Single Delete
                    url = "{{ route('admin.transactions.delete', ':id') }}".replace(':id', currentDeleteId);
                    data = {
                        _token: '{{ csrf_token() }}'
                    };
                } else {
                    // Bulk Delete
                    url = "{{ route('admin.transactions.bulk-delete') }}";
                    data = {
                        _token: '{{ csrf_token() }}',
                        ids: currentDeleteIds
                    };
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            toastNotification(response.message, 'success');
                            closeDeleteModal();

                            // Uncheck main checkbox and hide button
                            $('#select-all-checkbox').prop('checked', false);
                            updateBulkDeleteUI();

                            loadTable(window.location.href);
                        } else {
                            toastNotification(response.message || "Failed to delete records", 'error');
                        }
                    },
                    error: function(xhr) {
                        toastNotification(xhr.responseJSON?.message || "Failed to delete records.",
                        'error');
                    },
                    complete: function() {
                        setTimeout(() => {
                            btn.find('.btn-text').removeClass('opacity-0');
                            btn.find('.btn-loader').addClass('hidden');
                            btn.prop('disabled', false);
                        }, 300);
                    }
                });
            });

            // ── Data from PHP ────────────────────────────────────────────────────────
            const GRAPH_DATA = @json($graph_data);
            const TYPE_CHART_DATA = @json($type_chart_data);

            // ── Shared Chart.js defaults ─────────────────────────────────────────────
            const CURRENCY = "{{ getSetting('currency') }}";
            if (typeof Chart !== 'undefined') {
                Chart.defaults.color = 'rgba(148,163,184,0.7)';
                Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
                Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui';
                Chart.defaults.font.size = 10;
            }

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

            // ── Transaction Trend Chart ───────────────────────────────────────────────
            const trendCtx = document.getElementById('transactionTrendChart');
            let trendChart;
            if (trendCtx) {
                trendChart = new Chart(trendCtx.getContext('2d'), {
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
            }

            // ── Type Distribution Chart ──────────────────────────────────────────────
            const typeCtx = document.getElementById('typeDistributionChart');
            let typeChart;
            if (typeCtx) {
                typeChart = new Chart(typeCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: TYPE_CHART_DATA.map(d => d.name),
                        datasets: [{
                            label: 'Total Volume',
                            data: TYPE_CHART_DATA.map(d => d.total),
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
                                        if (context.parsed.x !== null) {
                                            label += new Intl.NumberFormat('en-US', {
                                                style: 'currency',
                                                currency: CURRENCY
                                            }).format(context.parsed.x);
                                        }
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
            }

            // ── Trend Logic ─────────────────────────────────────────────────────────
            let activePeriod = '7d';
            const SERIES_META = {
                credits: {
                    label: 'Credits',
                    color: '#34d399' // Emerald
                },
                debits: {
                    label: 'Debits',
                    color: '#f87171' // Red
                }
            };

            function updateTrendChart() {
                if (!trendChart) return;

                const periodData = GRAPH_DATA[activePeriod];
                if (!periodData || !periodData.credits || !periodData.debits) return;

                // Assuming both series have the same labels (dates)
                const labels = periodData.credits.labels;

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
                if (legendEl) {
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
            }

            $('.graph-period-btn').on('click', function() {
                activePeriod = $(this).data('period');
                $('.graph-period-btn').removeClass('bg-white/10 text-white').addClass('text-slate-500');
                $(this).removeClass('text-slate-500').addClass('bg-white/10 text-white');
                updateTrendChart();
            });

            if (trendChart) {
                updateTrendChart();
            }
        </script>
    @endpush
@endsection
