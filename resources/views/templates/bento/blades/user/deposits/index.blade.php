@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="space-y-8">
        {{-- Creative Header --}}
        <div class="relative overflow-hidden rounded-2xl bg-secondary border border-white/5 p-6 md:p-8">
            {{-- Background decorative elements --}}
            <div
                class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-accent-primary/5 blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute bottom-0 left-0 -ml-16 -mb-16 w-40 h-40 rounded-full bg-purple-500/5 blur-2xl pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-start gap-5">
                    <div
                        class="p-3 bg-white/5 rounded-2xl border border-white/10 hidden md:flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-8 h-8 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">{{ __('Deposit History') }}
                        </h1>
                        <p class="text-text-secondary text-sm md:text-base mt-2 max-w-xl leading-relaxed">
                            {{ __('Track your financial growth. View detailed transaction history and manage your incoming funds securely.') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <a href="{{ route('user.deposits.new') }}"
                        class="group relative w-full md:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 bg-accent-primary hover:bg-accent-primary-hover text-white font-bold rounded-xl transition-all shadow-[0_4px_20px_rgba(var(--color-accent-primary),0.3)] hover:shadow-[0_6px_25px_rgba(var(--color-accent-primary),0.4)] active:scale-[0.98] overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
                        </div>
                        <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>{{ __('New Deposit') }}</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Analytics Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Deposited --}}
            <div class="bg-secondary border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <p class="text-text-secondary text-sm font-medium">{{ __('Total Deposited') }}</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-white">
                        {{ number_format($deposits_analytics['total']['total'], 2) }}
                        <span class="text-sm font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                    </h3>
                </div>
                <div class="mt-4 flex items-center gap-2 text-xs text-text-secondary">
                    <span class="px-2 py-1 rounded bg-white/5 border border-white/5">
                        {{ $deposits_analytics['total']['count'] }} {{ __('Transactions') }}
                    </span>
                </div>
            </div>

            {{-- Pending --}}
            <div class="bg-secondary border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-text-secondary text-sm font-medium">{{ __('Pending') }}</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-white">
                        {{ number_format($deposits_analytics['pending']['total'], 2) }}
                        <span class="text-sm font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                    </h3>
                </div>
                <div class="mt-4 flex items-center gap-2 text-xs text-text-secondary">
                    <span class="px-2 py-1 rounded bg-yellow-500/10 border border-yellow-500/10 text-yellow-500">
                        {{ $deposits_analytics['pending']['count'] }} {{ __('Pending') }}
                    </span>
                </div>
            </div>

            {{-- Completed --}}
            <div class="bg-secondary border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-text-secondary text-sm font-medium">{{ __('Completed') }}</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-white">
                        {{ number_format($deposits_analytics['completed']['total'], 2) }}
                        <span class="text-sm font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                    </h3>
                </div>
                <div class="mt-4 flex items-center gap-2 text-xs text-text-secondary">
                    <span class="px-2 py-1 rounded bg-emerald-500/10 border border-emerald-500/10 text-emerald-500">
                        {{ $deposits_analytics['completed']['count'] }} {{ __('Completed') }}
                    </span>
                    @if ($avg_processing_time)
                        <span class="text-[10px]">{{ __('Avg Time') }}: {{ round($avg_processing_time / 60) }}m</span>
                    @endif
                </div>
            </div>

            {{-- Failed/Partial --}}
            <div class="bg-secondary border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-text-secondary text-sm font-medium">{{ __('Failed / Partial') }}</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-white">
                        {{ number_format($deposits_analytics['failed']['total'] + $deposits_analytics['partial_payment']['total'], 2) }}
                        <span class="text-sm font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                    </h3>
                </div>
                <div class="mt-4 flex items-center gap-2 text-xs text-text-secondary">
                    <span class="px-2 py-1 rounded bg-red-500/10 border border-red-500/10 text-red-500">
                        {{ $deposits_analytics['failed']['count'] }} {{ __('Failed') }}
                    </span>
                    <span class="px-2 py-1 rounded bg-orange-500/10 border border-orange-500/10 text-orange-500">
                        {{ $deposits_analytics['partial_payment']['count'] }} {{ __('Partial') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Deep Dive Analysis --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Column 1: Processing Performance --}}
            <div
                class="bg-secondary border border-white/5 rounded-2xl p-6 flex flex-col justify-between relative overflow-hidden group">
                {{-- Background SVG --}}
                <div class="absolute -right-32 -bottom-32 opacity-5 rotate-6 pointer-events-none">
                    <svg class="w-64 h-64 text-accent-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="0.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>

                <div class="relative z-10">
                    <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        {{ __('Processing Speed') }}
                    </h3>

                    <div class="space-y-4">
                        {{-- Avg Time --}}
                        <div
                            class="flex items-center justify-between p-3 bg-white/5 rounded-xl backdrop-blur-sm border border-white/5 hover:bg-white/10 transition-colors">
                            <span class="text-text-secondary text-sm">{{ __('Average') }}</span>
                            <span class="text-white font-bold">
                                @if ($avg_processing_time)
                                    {{ $avg_processing_time < 60 ? round($avg_processing_time) . 's' : round($avg_processing_time / 60) . 'm' }}
                                @else
                                    --
                                @endif
                            </span>
                        </div>

                        {{-- Fastest --}}
                        <div
                            class="flex items-center justify-between p-3 bg-white/5 rounded-xl backdrop-blur-sm border border-white/5 hover:bg-white/10 transition-colors">
                            <span class="text-text-secondary text-sm">{{ __('Fastest') }}</span>
                            <div class="text-right">
                                <span class="block text-white font-bold">
                                    @if ($fastest_deposit)
                                        @php $f_seconds = $fastest_deposit->created_at->diffInSeconds($fastest_deposit->updated_at); @endphp
                                        {{ $f_seconds < 60 ? $f_seconds . 's' : round($f_seconds / 60) . 'm' }}
                                    @else
                                        --
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- Slowest --}}
                        <div
                            class="flex items-center justify-between p-3 bg-white/5 rounded-xl backdrop-blur-sm border border-white/5 hover:bg-white/10 transition-colors">
                            <span class="text-text-secondary text-sm">{{ __('Slowest') }}</span>
                            <div class="text-right">
                                <span class="block text-white font-bold">
                                    @if ($slowest_deposit)
                                        @php $s_seconds = $slowest_deposit->created_at->diffInSeconds($slowest_deposit->updated_at); @endphp
                                        {{ $s_seconds < 60 ? $s_seconds . 's' : round($s_seconds / 60) . 'm' }}
                                    @else
                                        --
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Column 2: Fees & Records --}}
            <div
                class="bg-secondary border border-white/5 rounded-2xl p-6 flex flex-col gap-4 relative overflow-hidden group">
                {{-- Background SVG --}}
                <div class="absolute -right-36 -top-36 opacity-5 -rotate-12 pointer-events-none">
                    <svg class="w-72 h-72 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="0.5">
                        <circle cx="12" cy="12" r="10" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                    </svg>
                </div>

                <div class="relative z-10">
                    {{-- Fees --}}
                    <div>
                        <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            {{ __('Network Fees') }}
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div
                                class="p-3 bg-white/5 rounded-xl backdrop-blur-sm border border-white/5 hover:bg-white/10 transition-colors">
                                <p class="text-xs text-text-secondary mb-1">{{ __('Total Paid') }}</p>
                                <p class="text-lg font-bold text-white">{{ number_format($fee_stats->total_fees, 2) }}
                                    {{ getSetting('currency') }}</p>
                            </div>
                            <div
                                class="p-3 bg-white/5 rounded-xl backdrop-blur-sm border border-white/5 hover:bg-white/10 transition-colors">
                                <p class="text-xs text-text-secondary mb-1">{{ __('Avg Rate') }}</p>
                                <p class="text-lg font-bold text-white">
                                    {{ number_format($fee_stats->avg_fee_percent, 2) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Records --}}
                    <div class="border-t border-white/5 pt-4 mt-auto">
                        <div class="grid grid-cols-1 gap-3">
                            @if ($largest_deposit)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="p-1.5 bg-accent-primary/10 text-accent-primary rounded-lg"><svg
                                                class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                            </svg></span>
                                        <span class="text-sm text-text-secondary">{{ __('Largest Deposit') }}</span>
                                    </div>
                                    <span
                                        class="text-white font-bold">{{ number_format($largest_deposit->amount, 2) }}</span>
                                </div>
                            @endif

                            @if ($highest_daily_total)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="p-1.5 bg-emerald-500/10 text-emerald-500 rounded-lg"><svg
                                                class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg></span>
                                        <span class="text-sm text-text-secondary">{{ __('Best Day') }}</span>
                                    </div>
                                    <span
                                        class="text-white font-bold">{{ number_format($highest_daily_total->total, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Column 3: Distribution --}}
            <div class="bg-secondary border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                {{-- Background SVG --}}
                <div class="absolute -right-32 -bottom-32 opacity-5 rotate-12 pointer-events-none">
                    <svg class="w-64 h-64 text-purple-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="0.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>

                <div class="relative z-10">
                    <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        {{ __('Distribution') }}
                    </h3>

                    {{-- By Method --}}
                    <div class="mb-6">
                        <p class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-3">
                            {{ __('By Method') }}
                        </p>
                        <div class="space-y-3" id="methods-container">
                            @foreach ($method_stats as $stat)
                                <div class="method-item {{ $loop->index > 2 ? 'hidden' : '' }}">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-white">
                                            {{ __($stat->paymentMethod->name) ?? __('Unknown') }}
                                        </span>
                                        <span class="text-text-secondary">{{ $stat->count }}
                                            ({{ number_format($stat->total, 2) }})
                                        </span>
                                    </div>
                                    <div class="w-full bg-white/5 rounded-full h-1.5 backdrop-blur-sm">
                                        @php $percent = $deposits_analytics['total']['total'] > 0 ? ($stat->total / $deposits_analytics['total']['total']) * 100 : 0; @endphp
                                        <div class="bg-purple-500 h-1.5 rounded-full"
                                            style="width: {{ $percent }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if (count($method_stats) > 3)
                            <button type="button" data-target="#methods-container"
                                class="toggle-load-more cursor-pointer mt-4 text-[10px] font-bold text-accent-primary uppercase tracking-widest hover:text-accent-primary-hover transition-colors flex items-center gap-1 group">
                                <span class="label">{{ __('Load More') }}</span>
                                <svg class="w-3 h-3 group-data-[expanded=true]:rotate-180 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                        @endif
                    </div>

                    {{-- By Currency --}}
                    <div>
                        <p class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-3">
                            {{ __('By Currency') }}
                        </p>
                        <div class="space-y-2" id="currencies-container">
                            @foreach ($currency_stats as $stat)
                                <div
                                    class="currency-item flex items-center justify-between p-2 hover:bg-white/5 rounded-lg transition-colors backdrop-blur-sm border border-transparent hover:border-white/5 {{ $loop->index > 2 ? 'hidden' : '' }}">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-white">{{ __($stat->currency) }}</span>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-white">{{ number_format($stat->total, 2) }}</p>
                                        <p class="text-[10px] text-text-secondary">{{ $stat->count }}
                                            {{ __('txns') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if (count($currency_stats) > 3)
                            <button type="button" data-target="#currencies-container"
                                class="toggle-load-more cursor-pointer mt-3 text-[10px] font-bold text-accent-primary uppercase tracking-widest hover:text-accent-primary-hover transition-colors flex items-center gap-1 group">
                                <span class="label">{{ __('Load More') }}</span>
                                <svg class="w-3 h-3 group-data-[expanded=true]:rotate-180 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Filters & Table --}}
        <div id="deposits-history-wrapper"
            class="bg-secondary border border-white/5 rounded-2xl overflow-hidden relative">
            {{-- Loading Spinner Overlay --}}
            <div id="deposits-loading-spinner"
                class="hidden absolute inset-0 bg-secondary/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-opacity duration-300">
                <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin">
                </div>
                <p class="mt-3 text-text-secondary font-medium animate-pulse">{{ __('Loading records...') }}</p>
            </div>

            <div
                class="p-6 border-b border-white/5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <h3 class="text-lg font-bold text-white">{{ __('Recent Deposits') }}</h3>

                <form method="GET" action="{{ route('user.deposits.index') }}" class="relative">
                    <input type="text" name="search" placeholder="{{ __('Search deposits...') }}"
                        value="{{ request('search') }}"
                        class="pl-10 pr-4 py-2 bg-white/5 border border-white/10 rounded-xl text-base text-white placeholder-text-secondary focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all w-full md:w-64">
                    <svg class="w-4 h-4 text-text-secondary absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </form>
            </div>

            <div id="deposits-table-content">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/5">
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                    {{ __('Transaction') }}</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                    {{ __('Amount') }}</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                    {{ __('Method') }}</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                    {{ __('Date') }}</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-text-secondary uppercase tracking-wider">
                                    {{ __('Status') }}</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($deposits as $deposit)
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-text-secondary group-hover:bg-accent-primary/20 group-hover:text-accent-primary transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <a href="{{ route('user.deposits.view', $deposit->transaction_reference) }}"
                                                    class="text-white font-medium text-sm hover:text-accent-primary transition-colors hover:underline font-mono">
                                                    {{ strlen($deposit->transaction_reference) > 6 ? substr($deposit->transaction_reference, 0, 3) . '...' . substr($deposit->transaction_reference, -3) : $deposit->transaction_reference }}
                                                </a>
                                                <p class="text-text-secondary text-xs"
                                                    title="{{ $deposit->transaction_hash }}">
                                                    {{ strlen($deposit->transaction_hash) > 6 ? substr($deposit->transaction_hash, 0, 3) . '...' . substr($deposit->transaction_hash, -3) : $deposit->transaction_hash }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-white font-bold">{{ number_format($deposit->amount, 2) }} <span
                                                class="text-xs text-text-secondary">{{ getSetting('currency') }}</span>
                                        </p>
                                        @if ($deposit->currency != getSetting('currency'))
                                            <p class="text-xs text-text-secondary">≈
                                                {{ number_format($deposit->converted_amount, 8) }}
                                                {{ $deposit->currency }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-xs font-medium text-white">
                                            {{ $deposit->paymentMethod->name ?? __('Unknown') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-white text-sm">{{ $deposit->created_at->format('M d, Y') }}</p>
                                        <p class="text-text-secondary text-xs">{{ $deposit->created_at->format('H:i A') }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                                'completed' =>
                                                    'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                                'failed' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                                'partial_payment' =>
                                                    'bg-orange-500/10 text-orange-500 border-orange-500/20',
                                            ];
                                            $class =
                                                $statusClasses[$deposit->status] ??
                                                'bg-white/10 text-white border-white/20';
                                        @endphp
                                        <span
                                            class="inline-block px-3 py-1 rounded-lg text-xs font-bold border {{ $class }}">
                                            {{ __($deposit->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('user.deposits.view', $deposit->transaction_reference) }}"
                                            class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-text-secondary hover:text-white transition-colors inline-block"
                                            title="{{ __('View Details') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            @if (request('search'))
                                                <div
                                                    class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mb-4">
                                                    <svg class="w-8 h-8 text-text-secondary" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-white font-medium text-lg">{{ __('No Results Found') }}</p>
                                                <p class="text-text-secondary text-sm mb-6">
                                                    {{ __('We couldn\'t find any deposits matching ":search"', ['search' => request('search')]) }}
                                                </p>
                                                <a href="{{ route('user.deposits.index') }}"
                                                    class="text-accent-primary hover:text-accent-primary-hover font-medium text-sm transition-colors">
                                                    {{ __('Clear Search') }}
                                                </a>
                                            @else
                                                <div
                                                    class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mb-4">
                                                    <svg class="w-8 h-8 text-text-secondary" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-white font-medium text-lg">{{ __('No deposits found') }}
                                                </p>
                                                <p class="text-text-secondary text-sm mb-6">
                                                    {{ __('You haven\'t made any deposits yet.') }}</p>
                                                <a href="{{ route('user.deposits.new') }}"
                                                    class="text-accent-primary hover:text-accent-primary-hover font-medium text-sm transition-colors">
                                                    {{ __('Make your first deposit') }} &rarr;
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($deposits->hasPages())
                    <div class="p-6 border-t border-white/5 ajax-pagination">
                        {{ $deposits->links('templates.bento.blades.partials.pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
{{-- See all returned data for this page from the index() function on DepositController --}}
@section('scripts')
    {{-- jQuery (Required for AJAX) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check for scroll params on normal load
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('page') || urlParams.has('search')) {
                const element = document.getElementById('deposits-history-wrapper');
                if (element) {
                    element.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }

            // AJAX Pagination Logic
            $(document).on('click', '.ajax-pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                // Show spinner
                $('#deposits-loading-spinner').removeClass('hidden');

                // Scroll to top of table
                $('html, body').animate({
                    scrollTop: $("#deposits-history-wrapper").offset().top - 100
                }, 500);

                $.get(url, function(data) {
                    // Extract content
                    var newContent = $(data).find('#deposits-table-content').html();

                    // Update DOM
                    $('#deposits-table-content').html(newContent);
                }).always(function() {
                    // Hide spinner
                    setTimeout(function() {
                        $('#deposits-loading-spinner').addClass('hidden');
                    }, 300);
                });
            });

            // Load More / Show Less Toggle for Distribution Stats
            $(document).on('click', '.toggle-load-more', function() {
                const $btn = $(this);
                const $container = $($btn.data('target'));
                const isExpanded = $btn.attr('data-expanded') === 'true';

                if (isExpanded) {
                    // Hide items beyond first 3
                    $container.children().each(function(index) {
                        if (index > 2) $(this).addClass('hidden');
                    });
                    $btn.attr('data-expanded', 'false');
                    $btn.removeAttr('data-expanded'); // Reset using group-data-[expanded]
                    $btn.find('.label').text("{{ __('Load More') }}");
                } else {
                    // Show all items
                    $container.children().removeClass('hidden');
                    $btn.attr('data-expanded', 'true');
                    $btn.find('.label').text("{{ __('Show Less') }}");
                }
            });
        });
    </script>
@endsection
