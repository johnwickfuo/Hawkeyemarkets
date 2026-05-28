@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="flex flex-col h-full space-y-8">
        <!-- Tech Header & Stats HUD -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Title Section -->
            <div class="lg:col-span-1 flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-white font-heading tracking-tight flex items-center gap-3">
                    <span
                        class="w-2 h-8 bg-accent-primary rounded-sm shadow-[0_0_15px_rgba(var(--color-accent-primary),0.5)]"></span>
                    {{ $page_title ?? __('New Withdrawal') }}
                </h2>
                <p class="text-text-secondary mt-2 pl-5 border-l border-white/5 font-light">
                    {{ __('Select a payment gateway to initiate a fund withdrawal.') }}
                </p>
            </div>

            <!-- HUD Stats -->
            <div class="lg:col-span-2 grid grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Balance Item -->
                <div
                    class="bg-accent-primary/10 backdrop-blur-md border border-accent-primary/20 rounded-xl p-4 flex flex-col justify-between relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-accent-primary/20 to-transparent opacity-100 transition-opacity duration-500">
                    </div>
                    <span
                        class="text-[10px] text-accent-primary uppercase tracking-widest font-black z-10">{{ __('Available') }}</span>
                    <div class="flex items-baseline gap-1 mt-1 z-10">
                        @if (getSetting('currency_symbol_position') == 'before')
                            <span class="text-sm text-accent-primary font-bold">{{ getSetting('currency_symbol') }}</span>
                        @endif
                        <span
                            class="text-xl font-mono font-bold text-white tracking-tight">{{ number_format(auth()->user()->balance, getSetting('decimal_paces', 2)) }}</span>
                        @if (getSetting('currency_symbol_position') == 'after')
                            <span class="text-sm text-accent-primary font-bold">{{ getSetting('currency_symbol') }}</span>
                        @endif
                    </div>
                </div>
                <!-- Stat Item -->
                <div
                    class="bg-secondary-dark/40 backdrop-blur-md border border-white/10 rounded-xl p-4 flex flex-col justify-between relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <span
                        class="text-[10px] text-text-secondary uppercase tracking-widest font-bold z-10">{{ __('Min Limit') }}</span>
                    <div class="flex items-baseline gap-1 mt-1 z-10">
                        @if (getSetting('currency_symbol_position') == 'before')
                            <span class="text-sm text-accent-primary font-bold">{{ getSetting('currency_symbol') }}</span>
                        @endif
                        <span
                            class="text-xl font-mono font-bold text-white tracking-tight">{{ number_format(getSetting('min_withdrawal'), getSetting('decimal_paces', 0)) }}</span>
                        @if (getSetting('currency_symbol_position') == 'after')
                            <span class="text-sm text-accent-primary font-bold">{{ getSetting('currency_symbol') }}</span>
                        @endif
                        <span class="text-[10px] text-text-secondary font-bold ml-1">{{ getSetting('currency') }}</span>
                    </div>
                </div>
                <!-- Stat Item -->
                <div
                    class="bg-secondary-dark/40 backdrop-blur-md border border-white/10 rounded-xl p-4 flex flex-col justify-between relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <span
                        class="text-[10px] text-text-secondary uppercase tracking-widest font-bold z-10">{{ __('Max Limit') }}</span>
                    <div class="flex items-baseline gap-1 mt-1 z-10">
                        @if (getSetting('currency_symbol_position') == 'before')
                            <span class="text-sm text-accent-primary font-bold">{{ getSetting('currency_symbol') }}</span>
                        @endif
                        <span
                            class="text-xl font-mono font-bold text-white tracking-tight">{{ number_format(getSetting('max_withdrawal'), getSetting('decimal_paces', 0)) }}</span>
                        @if (getSetting('currency_symbol_position') == 'after')
                            <span class="text-sm text-accent-primary font-bold">{{ getSetting('currency_symbol') }}</span>
                        @endif
                        <span class="text-[10px] text-text-secondary font-bold ml-1">{{ getSetting('currency') }}</span>
                    </div>
                </div>
                <!-- Stat Item -->
                <div
                    class="bg-secondary-dark/40 backdrop-blur-md border border-white/10 rounded-xl p-4 flex flex-col justify-between relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <span
                        class="text-[10px] text-text-secondary uppercase tracking-widest font-bold z-10">{{ __('Fee') }}</span>
                    <div class="flex items-baseline gap-1 mt-1 z-10">
                        <span
                            class="text-xl font-mono font-bold text-white tracking-tight">{{ getSetting('withdrawal_fee') }}</span>
                        <span class="text-[10px] text-accent-primary font-bold">%</span>
                    </div>
                </div>
                <!-- Stat Item -->
                <div
                    class="bg-secondary-dark/40 backdrop-blur-md border border-white/10 rounded-xl p-4 flex flex-col justify-between relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <span
                        class="text-[10px] text-text-secondary uppercase tracking-widest font-bold z-10">{{ __('Time') }}</span>
                    <div class="flex items-baseline gap-2 mt-1 z-10">
                        <div class="flex h-2 w-2 relative">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-primary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-accent-primary"></span>
                        </div>
                        <span class="text-sm font-bold text-white mt-0.5">{{ __('Instant') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Content -->
        <div class="flex flex-col gap-6">
            <!-- Search Bar -->
            <!-- Search Bar -->
            <div class="relative max-w-md group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors">
                    <svg class="w-5 h-5 text-white/30 group-focus-within:text-accent-primary transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="method-search"
                    class="block w-full pl-11 pr-4 py-3.5 bg-secondary-dark/30 border border-white/5 rounded-xl text-base text-white placeholder-white/20 focus:border-accent-primary/50 focus:ring-0 transition-colors"
                    placeholder="{{ __('Search method (e.g. Bitcoin, USDT, Chase)...') }}">
            </div>

            <!-- Methods Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4" id="methods-grid">
                @foreach ($withdrawal_methods as $method)
                    <div class="payment-method-card group relative bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-0 hover:border-accent-primary/40 transition-all duration-300 cursor-pointer overflow-hidden ring-1 ring-white/5 hover:ring-accent-primary/20 hover:shadow-[0_0_30px_-10px_rgba(var(--color-accent-primary),0.3)] hover:-translate-y-1"
                        data-name="{{ strtolower($method->name) }}" data-id="{{ $method->id }}"
                        data-class="{{ $method->class }}" data-logo="{{ $method->logo }}"
                        data-info="{{ $method->payment_information }}">

                        <!-- Top Decoration -->
                        <div
                            class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:via-accent-primary/70 transition-all duration-500">
                        </div>

                        <div class="p-6 flex flex-col gap-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between">
                                <div class="relative">
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 p-2.5 flex items-center justify-center group-hover:bg-accent-primary/10 group-hover:border-accent-primary/20 transition-all duration-300">
                                        @if ($method->logo)
                                            <img src="{{ asset('assets/images/withdrawal-methods/' . $method->logo) }}"
                                                alt="{{ $method->name }}"
                                                class="h-full w-full object-contain filter drop-shadow opacity-90 group-hover:opacity-100 transition-all">
                                        @else
                                            <span
                                                class="text-xl font-bold text-white">{{ substr($method->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <!-- Online Dot -->
                                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                                        <span
                                            class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 border-2 border-secondary-dark"></span>
                                    </span>
                                </div>

                                <div class="text-right">
                                    <span
                                        class="px-2.5 py-1 rounded-md bg-white/5 border border-white/5 text-[10px] font-bold text-text-secondary uppercase tracking-wider group-hover:text-white group-hover:bg-white/10 transition-colors">
                                        {{ __($method->type) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Info -->
                            <div>
                                <h3
                                    class="font-heading font-bold text-white text-lg tracking-tight group-hover:text-accent-primary transition-colors">
                                    {{ $method->name }}</h3>
                                <p class="text-xs text-text-secondary mt-1 line-clamp-2">
                                    {{ __('Processing') }}: <span
                                        class="text-white font-bold">{{ __($method->class) }}</span>
                                    •
                                    {{ __('Fee') }}:
                                    {{ getSetting('withdrawal_fee') }}%</p>
                            </div>

                            <!-- Action -->
                            <div
                                class="mt-auto pt-4 border-t border-white/5 flex items-center justify-between opacity-60 group-hover:opacity-100 transition-opacity">
                                <span class="text-xs font-mono text-accent-primary">{{ __('Rates: 1:1 USD') }}</span>
                                <svg class="w-5 h-5 text-white transform group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- No Results -->
            <div id="no-results" class="hidden py-32 flex flex-col items-center justify-center text-center opacity-60">
                <div class="relative mb-6">
                    <div class="absolute inset-0 bg-accent-primary/20 blur-xl rounded-full"></div>
                    <svg class="relative w-16 h-16 text-text-secondary" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-white font-bold text-xl mb-2">{{ __('No Assets Found') }}</h3>
                <p class="text-text-secondary">{{ __('We couldn\'t find any payment method matching your search.') }}</p>
            </div>
        </div>
    </div>

    <!-- Withdrawal Modals -->

    <!-- Manual Withdrawal Modal -->
    <div id="manual-withdrawal-modal"
        class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300 modal-backdrop">
        <div
            class="bg-secondary-dark w-full max-w-2xl rounded-[32px] border border-white/5 shadow-2xl relative overflow-y-auto max-h-[90vh] transform scale-95 transition-transform duration-300 scrollbar-thin scrollbar-thumb-white/10">
            <form action="{{ route('user.withdrawals.new-validate') }}" method="POST" class="ajax-form"
                data-action="redirect" data-redirect="{{ route('user.withdrawals.index') }}">
                @csrf
                <input type="hidden" name="withdrawal_method_id" id="manual-modal-method-id">

                <!-- Header Decor -->
                <div
                    class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-accent-primary to-transparent">
                </div>

                <!-- Step Indicator -->
                <div class="px-8 pt-8 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="manual-step-dot w-2 h-2 rounded-full bg-accent-primary shadow-[0_0_10px_rgba(var(--color-accent-primary),0.5)]"
                            data-step="1"></div>
                        <div class="manual-step-line w-12 h-[1px] bg-white/10" data-step="1"></div>
                        <div class="manual-step-dot w-2 h-2 rounded-full bg-white/10" data-step="2"></div>
                    </div>
                    <span
                        class="manual-step-label text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em]">{{ __('Step 1 of 2') }}</span>
                </div>

                <!-- Modal Content -->
                <div class="p-8 pt-6">
                    <!-- Close Button -->
                    <button type="button"
                        class="close-modal absolute top-6 right-6 text-text-secondary hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>

                    <div class="mb-8 flex items-start gap-4">
                        <div id="manual-modal-logo-container"
                            class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 p-3 flex items-center justify-center">
                            <!-- Logo injected here -->
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-2">{{ __('Manual Withdrawal') }}</h2>
                            <div class="flex items-center gap-2 group">
                                <div
                                    class="w-2 h-2 rounded-full bg-accent-primary group-hover:animate-pulse shadow-[0_0_8px_rgba(var(--color-accent-primary),0.4)]">
                                </div>
                                <p class="text-sm text-text-secondary font-medium tracking-wide">
                                    {{ __('Request to withdraw via') }} <span id="manual-modal-method-name"
                                        class="text-white font-bold">...</span>
                                    <span id="manual-modal-network-container" class="hidden">
                                        {{ __('on') }} <span id="manual-modal-method-network"
                                            class="text-white font-bold"></span> {{ __('network') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1: Amount -->
                    <div class="manual-modal-step space-y-6" data-step="1">
                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-text-secondary uppercase tracking-widest pl-1">
                                {{ __('Withdrawal Amount') }}
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute left-6 top-1/2 -translate-y-1/2 text-accent-primary group-focus-within:animate-pulse">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <input type="number" name="amount" id="withdrawal-amount" step="any"
                                    class="w-full bg-secondary-dark/50 border-2 border-white/5 rounded-2xl py-5 pl-16 pr-24 text-xl font-bold text-white placeholder:text-white/10 focus:border-accent-primary/50 focus:ring-0 transition-all outline-none"
                                    placeholder="0.00" required>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2">
                                    <span class="text-sm font-bold text-text-secondary uppercase tracking-wider">
                                        {{ getSetting('currency') }}
                                    </span>
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between p-4 rounded-xl bg-white/[0.02] border border-white/5">
                                <span class="text-xs text-text-secondary">{{ __('Available Balance') }}</span>
                                <span
                                    class="text-sm font-bold text-accent-primary">{{ number_format(Auth::user()->balance, 2) }}
                                    {{ getSetting('currency') }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5">
                                <span
                                    class="block text-[10px] text-text-secondary uppercase font-bold mb-1">{{ __('Fee') }}</span>
                                <span
                                    class="text-sm font-bold text-white">{{ number_format(getSetting('withdrawal_fee'), 2) }}%</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5">
                                <span
                                    class="block text-[10px] text-text-secondary uppercase font-bold mb-1">{{ __('Limits') }}</span>
                                <span
                                    class="text-sm font-bold text-white">{{ number_format(getSetting('min_withdrawal'), 2) }}
                                    -
                                    {{ number_format(getSetting('max_withdrawal'), 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Information Fields -->
                    <div class="manual-modal-step hidden space-y-6" data-step="2">
                        <div id="manual-dynamic-fields-container"
                            class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-thin">
                            <!-- Dynamic fields injected here -->
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex flex-col sm:flex-row items-center gap-4 mt-10" id="manual-modal-footer">
                        <button type="button" id="manual-prev-step"
                            class="hidden group relative w-full sm:w-auto min-w-[140px] py-4 bg-white/5 hover:bg-white/10 text-text-secondary hover:text-white font-bold rounded-xl transition-all border border-white/10 active:scale-[0.98] cursor-pointer">
                            <span class="flex items-center justify-center gap-3">
                                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                {{ __('Back') }}
                            </span>
                        </button>
                        <button type="button" id="manual-next-step"
                            class="group relative w-full sm:flex-1 py-4 bg-accent-primary hover:bg-accent-primary-hover text-white font-bold rounded-xl transition-all shadow-[0_0_20px_rgba(var(--color-accent-primary),0.3)] hover:shadow-[0_0_30px_rgba(var(--color-accent-primary),0.5)] active:scale-[0.98] cursor-pointer">
                            <span class="flex items-center justify-center gap-3">
                                {{ __('Continue to Payment') }}
                                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3">
                                    </path>
                                </svg>
                            </span>
                        </button>
                        <button type="submit" id="manual-submit-button"
                            class="hidden group relative w-full sm:flex-1 py-4 bg-accent-primary hover:bg-accent-primary-hover text-white font-bold rounded-xl transition-all shadow-[0_0_20px_rgba(var(--color-accent-primary),0.3)] hover:shadow-[0_0_30px_rgba(var(--color-accent-primary),0.5)] active:scale-[0.98] cursor-pointer">
                            <span class="flex items-center justify-center gap-3">
                                {{ __('Finalize Withdrawal') }}
                                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Automatic Withdrawal Modal -->
    <div id="automatic-withdrawal-modal"
        class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300 modal-backdrop">
        <div
            class="bg-secondary-dark w-full max-w-lg rounded-[32px] border border-white/5 shadow-2xl relative overflow-y-auto max-h-[90vh] transform scale-95 transition-transform duration-300 scrollbar-thin scrollbar-thumb-white/10">
            <form action="{{ route('user.withdrawals.new-validate') }}" method="POST" class="ajax-form"
                data-action="redirect">
                @csrf
                <input type="hidden" name="withdrawal_method_id" id="auto-modal-method-id">

                <!-- Header Decor -->
                <div
                    class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-accent-primary to-transparent">
                </div>

                <!-- Modal Content -->
                <div class="p-8">
                    <!-- Close Button -->
                    <button type="button"
                        class="close-modal absolute top-6 right-6 text-text-secondary hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>

                    <div class="mb-8 flex items-start gap-4">
                        <div id="auto-modal-logo-container"
                            class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 p-3 flex items-center justify-center">
                            <!-- Logo injected here -->
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-2">{{ __('Quick Withdrawal') }}</h2>
                            <div class="flex items-center gap-2 group">
                                <div
                                    class="w-2 h-2 rounded-full bg-accent-primary group-hover:animate-pulse shadow-[0_0_8px_rgba(var(--color-accent-primary),0.4)]">
                                </div>
                                <p class="text-sm text-text-secondary font-medium tracking-wide">
                                    {{ __('Request via') }} <span id="auto-modal-method-name"
                                        class="text-white font-bold">...</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-text-secondary uppercase tracking-widest pl-1">
                                {{ __('Amount to Withdraw') }}
                            </label>
                            <div class="relative group">
                                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-accent-primary">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0-2.08-.402-2.599-1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <input type="number" name="amount" step="any"
                                    class="w-full bg-secondary-dark/50 border-2 border-white/5 rounded-2xl py-5 pl-16 pr-24 text-xl font-bold text-white placeholder:text-white/10 focus:border-accent-primary/50 focus:ring-0 transition-all outline-none"
                                    placeholder="0.00" required>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2">
                                    <span class="text-sm font-bold text-text-secondary uppercase tracking-wider">
                                        {{ getSetting('currency') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5">
                                <span
                                    class="block text-[10px] text-text-secondary uppercase font-bold mb-1">{{ __('Balance') }}</span>
                                <span
                                    class="text-sm font-bold text-accent-primary">{{ number_format(Auth::user()->balance, 2) }}
                                    {{ getSetting('currency') }}</span>
                            </div>
                            <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5">
                                <span
                                    class="block text-[10px] text-text-secondary uppercase font-bold mb-1">{{ __('Fee') }}</span>
                                <span
                                    class="text-sm font-bold text-white">{{ number_format(getSetting('withdrawal_fee'), 2) }}%</span>
                            </div>
                        </div>

                        <div
                            class="p-4 rounded-xl bg-white/[0.02] border border-white/5 flex items-center justify-between">
                            <span
                                class="text-[10px] text-text-secondary uppercase font-bold">{{ __('Withdrawal Limits') }}</span>
                            <span class="text-sm font-bold text-white">
                                {{ number_format(getSetting('min_withdrawal'), 2) }} -
                                {{ number_format(getSetting('max_withdrawal'), 2) }} {{ getSetting('currency') }}
                            </span>
                        </div>

                        <button type="submit"
                            class="group relative w-full py-4 bg-accent-primary hover:bg-accent-primary-hover text-white font-bold rounded-xl transition-all shadow-[0_0_20px_rgba(var(--color-accent-primary),0.3)] hover:shadow-[0_0_30px_rgba(var(--color-accent-primary),0.5)] active:scale-[0.98] cursor-pointer">
                            <span class="flex items-center justify-center gap-3">
                                {{ __('Confirm Withdrawal') }}
                                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Search Functionality
            const $grid = $('#methods-grid');
            const $noResults = $('#no-results');
            const $manualModal = $('#manual-withdrawal-modal');
            const $autoModal = $('#automatic-withdrawal-modal');

            $('#method-search').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                let visibleCount = 0;

                $('.payment-method-card').each(function() {
                    const match = $(this).data('name').indexOf(value) > -1;
                    $(this).toggle(match);
                    if (match) visibleCount++;
                });

                $noResults.toggleClass('hidden', visibleCount > 0);
            });



            function openManualModal(method) {
                $manualModal.removeClass('hidden');
                // Trigger flow
                $manualModal[0].offsetHeight;
                $manualModal.removeClass('opacity-0');
                $manualModal.find('> div').removeClass('scale-95');

                switchManualStep(1);

                // Populate Data
                $('#manual-modal-method-id').val(method.id);
                $('#manual-modal-method-name').text(method.name);

                // Handle Network Display
                const $networkContainer = $('#manual-modal-network-container');
                const $networkSpan = $('#manual-modal-method-network');
                if (method.info && method.info.network) {
                    $networkSpan.text(method.info.network);
                    $networkContainer.removeClass('hidden');
                } else {
                    $networkContainer.addClass('hidden');
                }

                // Inject Logo
                const $logoContainer = $('#manual-modal-logo-container');
                $logoContainer.empty();
                if (method.logo) {
                    $logoContainer.append(
                        `<img src="/assets/images/withdrawal-methods/${method.logo}" alt="${method.name}" class="h-full w-full object-contain filter drop-shadow opacity-90">`
                    );
                } else {
                    $logoContainer.append(
                        `<span class="text-2xl font-bold text-white">${method.name.charAt(0)}</span>`);
                }

                // Build Dynamic Fields

                const fields = method.info && method.info.fields ? method.info.fields : {};
                const $container = $('#manual-dynamic-fields-container');
                $container.empty();

                // Display Instructions if they exist
                if (method.info && method.info.instructions) {
                    $container.append(`
                        <div class="p-4 rounded-xl bg-accent-primary/5 border border-accent-primary/10 mb-6">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-accent-primary shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-xs text-text-secondary leading-relaxed">${method.info.instructions}</p>
                            </div>
                        </div>
                    `);
                }

                Object.entries(fields).forEach(([name, validation]) => {
                    // Generate Label from Key (e.g., wallet_address -> Wallet Address)
                    const label = name.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');

                    // Detect Type
                    let type = 'text';
                    if (validation.includes('email')) type = 'email';
                    if (validation.includes('numeric') || validation.includes('integer')) type = 'number';

                    const fieldHtml = `
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-text-secondary uppercase tracking-widest pl-1">${label}</label>
                            <input type="${type}" name="${name}" required
                                class="w-full bg-white/[0.03] border border-white/5 rounded-xl py-3.5 px-4 text-white focus:border-accent-primary/50 transition-all outline-none placeholder:text-white/10"
                                placeholder="Enter ${label.toLowerCase()}">
                        </div>
                    `;
                    $container.append(fieldHtml);
                });
            }

            function openAutoModal(method) {
                $autoModal.removeClass('hidden');
                // Trigger flow
                $autoModal[0].offsetHeight;
                $autoModal.removeClass('opacity-0');
                $autoModal.find('> div').removeClass('scale-95');

                // Populate Data
                $('#auto-modal-method-id').val(method.id);
                $('#auto-modal-method-name').text(method.name);

                // Inject Logo
                const $logoContainer = $('#auto-modal-logo-container');
                $logoContainer.empty();
                if (method.logo) {
                    $logoContainer.append(
                        `<img src="/assets/images/withdrawal-methods/${method.logo}" alt="${method.name}" class="h-full w-full object-contain filter drop-shadow opacity-90">`
                    );
                } else {
                    $logoContainer.append(
                        `<span class="text-2xl font-bold text-white">${method.name.charAt(0)}</span>`);
                }
            }

            function switchManualStep(step) {
                $('.manual-modal-step').addClass('hidden');
                $(`.manual-modal-step[data-step="${step}"]`).removeClass('hidden');

                // Update Indicator
                $('.manual-step-dot').removeClass(
                        'bg-accent-primary bg-white/10 shadow-[0_0_10px_rgba(var(--color-accent-primary),0.5)]')
                    .addClass('bg-white/10');
                $('.manual-step-line').removeClass('bg-accent-primary').addClass('bg-white/10');

                $(`.manual-step-dot[data-step="${step}"]`).addClass(
                        'bg-accent-primary shadow-[0_0_10px_rgba(var(--color-accent-primary),0.5)]')
                    .removeClass('bg-white/10');

                if (step === 2) {
                    $('#manual-modal-footer').addClass('is-step-2');
                    $('.manual-step-dot[data-step="1"]').addClass('bg-accent-primary');
                    $('.manual-step-line[data-step="1"]').addClass('bg-accent-primary');
                    $('.manual-step-label').text(`{{ __('Step 2 of 2') }}`);
                    $('#manual-next-step').addClass('hidden');
                    $('#manual-submit-button').removeClass('hidden');
                    $('#manual-prev-step').removeClass('hidden');
                } else {
                    $('#manual-modal-footer').removeClass('is-step-2');
                    $('.manual-step-label').text(`{{ __('Step 1 of 2') }}`);
                    $('#manual-next-step').removeClass('hidden');
                    $('#manual-submit-button').addClass('hidden');
                    $('#manual-prev-step').addClass('hidden');
                }
            }

            $('#manual-next-step').click(function() {
                const amount = $manualModal.find('input[name="amount"]').val();
                if (!amount || amount <= 0) {
                    toastNotification('Please enter a valid amount', 'error');
                    return;
                }
                switchManualStep(2);
            });

            $('#manual-prev-step').click(function() {
                switchManualStep(1);
            });

            // Card Selection - Using Event Delegation for robustness
            $(document).on('click', '.payment-method-card', function() {
                const $card = $(this);
                let info = $card.data('info');

                // If it's a string, try parsing it (JQuery usually does this automatically but just in case)
                if (typeof info === 'string' && info.startsWith('{')) {
                    try {
                        info = JSON.parse(info);
                    } catch (e) {
                        console.warn('JSON parse failed for method info', e);
                    }
                }

                const method = {
                    id: $card.data('id'),
                    class: String($card.data('class')).toLowerCase(),
                    name: $card.find('h3').text().trim(),
                    logo: $card.data('logo'),
                    info: info || {}
                };

                if (method.class === 'manual') {
                    openManualModal(method);
                } else {
                    openAutoModal(method);
                }
            });

            function closeModal() {
                $manualModal.addClass('opacity-0');
                $manualModal.find('> div').addClass('scale-95');
                $autoModal.addClass('opacity-0');
                $autoModal.find('> div').addClass('scale-95');

                setTimeout(() => {
                    $manualModal.addClass('hidden');
                    $autoModal.addClass('hidden');
                    // Reset forms
                    $manualModal.find('form').trigger('reset');
                    $autoModal.find('form').trigger('reset');
                }, 300);
            }

            // Close Events
            $(document).on('click', '.close-modal', closeModal);
            $(document).on('click', '.modal-backdrop', function(e) {
                if (e.target === this) closeModal();
            });
        });
    </script>
@endsection
