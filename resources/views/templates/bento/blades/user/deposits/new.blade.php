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
                    {{ $page_title ?? __('New Deposit') }}
                </h2>
                <p class="text-text-secondary mt-2 pl-5 border-l border-white/5 font-light">
                    {{ __('Select a payment gateway to initiate a fund transfer.') }}
                </p>
            </div>

            <!-- HUD Stats -->
            <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-3">
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
                            class="text-xl font-mono font-bold text-white tracking-tight">{{ number_format(getSetting('min_deposit'), getSetting('decimal_paces', 0)) }}</span>
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
                            class="text-xl font-mono font-bold text-white tracking-tight">{{ number_format(getSetting('max_deposit'), getSetting('decimal_paces', 0)) }}</span>
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
                            class="text-xl font-mono font-bold text-white tracking-tight">{{ getSetting('deposit_fee') }}</span>
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
                @foreach ($payment_methods as $method)
                    <div class="payment-method-card group relative bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-0 hover:border-accent-primary/40 transition-all duration-300 cursor-pointer overflow-hidden ring-1 ring-white/5 hover:ring-accent-primary/20 hover:shadow-[0_0_30px_-10px_rgba(var(--color-accent-primary),0.3)] hover:-translate-y-1"
                        data-name="{{ strtolower($method->name) }}" data-id="{{ $method->id }}"
                        data-logo="{{ $method->logo }}">

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
                                            <img src="{{ asset('assets/images/deposit-methods/' . $method->logo) }}"
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
                                    {{ getSetting('deposit_fee') }}%</p>
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

    <!-- Tech Deposit Modal -->
    <div id="deposit-modal" class="fixed inset-0 z-50 hidden font-sans">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/90 backdrop-blur-md transition-opacity opacity-0 modal-backdrop"></div>

        <!-- Content -->
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div
                class="bg-[#0f1115] border border-white/10 rounded-2xl w-full max-w-lg transform scale-95 opacity-0 transition-all duration-300 modal-content relative overflow-hidden shadow-2xl">
                <!-- Glow Effect -->
                <div
                    class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-accent-primary to-transparent opacity-50">
                </div>

                <!-- Close -->
                <button
                    class="modal-close absolute top-5 right-5 text-text-secondary hover:text-white transition-colors z-20 cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <!-- Form -->
                <form action="{{ route('user.deposits.new-validate') }}" method="POST" class="ajax-form"
                    data-action="redirect">
                    @csrf
                    <input type="hidden" name="payment_method_id" id="modal-method-id">

                    <div class="relative z-10">
                        <!-- Header -->
                        <div class="p-8 pb-4 text-center">
                            <div class="w-16 h-16 mx-auto bg-white/5 border border-white/10 rounded-2xl p-3 mb-4 flex items-center justify-center shadow-inner"
                                id="modal-method-logo">
                                <!-- JS Injected -->
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-1"><span
                                    class="text-text-secondary text-lg font-normal">{{ __('Deposit via') }}</span> <span
                                    id="modal-method-name" class="text-accent-primary">Bitcoin</span></h3>
                            <p class="text-xs text-text-secondary uppercase tracking-widest font-bold opacity-60">
                                {{ __('Initiate Transaction') }}</p>
                        </div>

                        <!-- Input Area -->
                        <div class="px-8 py-6">
                            <label
                                class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-3 ml-1">{{ __('Amount to Deposit') }}</label>

                            <div class="relative group">
                                <input type="number" name="amount" step="any" required
                                    min="{{ getSetting('min_deposit') }}" max="{{ getSetting('max_deposit') }}"
                                    class="w-full bg-black/40 border border-white/10 rounded-xl py-4 pl-12 pr-20 text-2xl font-mono font-bold text-white placeholder-white/20 focus:outline-none focus:border-accent-primary/50 focus:ring-1 focus:ring-accent-primary/50 transition-all text-right group-hover:border-white/20"
                                    placeholder="0.00">

                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <span
                                        class="text-xl font-bold text-accent-primary">{{ getSetting('currency_symbol') }}</span>
                                </div>
                                <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none opacity-50">
                                    <span
                                        class="text-xs font-bold text-white bg-white/10 px-2 py-1 rounded">{{ getSetting('currency') }}</span>
                                </div>
                            </div>

                            <!-- Limits Bar -->
                            <div class="flex justify-between items-center mt-3 text-[10px] font-bold text-text-secondary">
                                <span class="bg-white/5 px-2 py-1 rounded border border-white/5">
                                    {{ __('MIN') }}:
                                    @if (getSetting('currency_symbol_position') == 'before')
                                        {{ getSetting('currency_symbol') }}
                                        @endif{{ number_format(getSetting('min_deposit')) }}@if (getSetting('currency_symbol_position') == 'after')
                                            {{ getSetting('currency_symbol') }}
                                        @endif
                                </span>
                                <span class="flex-1 mx-3 h-[1px] bg-white/5"></span>
                                <span class="bg-white/5 px-2 py-1 rounded border border-white/5">
                                    {{ __('MAX') }}:
                                    @if (getSetting('currency_symbol_position') == 'before')
                                        {{ getSetting('currency_symbol') }}
                                        @endif{{ number_format(getSetting('max_deposit')) }}@if (getSetting('currency_symbol_position') == 'after')
                                            {{ getSetting('currency_symbol') }}
                                        @endif
                                </span>
                            </div>
                        </div>

                        <!-- Summary info -->
                        <div class="bg-white/[0.02] border-t border-b border-white/5 px-8 py-4 space-y-2">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-text-secondary">{{ __('Network Fee') }}</span>
                                <span class="font-mono text-white">{{ getSetting('deposit_fee') }}%</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-text-secondary">{{ __('Estimated Arrival') }}</span>
                                <span class="flex items-center gap-1.5 text-emerald-400 font-bold text-xs">
                                    <span class="relative flex h-2 w-2">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                                    </span>
                                    {{ __('Instant') }}
                                </span>
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="p-8 pt-6">
                            <button type="submit"
                                class="group relative w-full py-4 bg-accent-primary hover:bg-accent-primary-hover text-white font-bold rounded-xl transition-all shadow-[0_0_20px_rgba(var(--color-accent-primary),0.3)] hover:shadow-[0_0_30px_rgba(var(--color-accent-primary),0.5)] active:scale-[0.98] cursor-pointer">
                                <span class="flex items-center justify-center gap-3">
                                    {{ __('Confirm Deposit') }}
                                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </span>
                            </button>
                            <p class="text-center text-[10px] text-text-secondary mt-4 opacity-70">
                                {{ __('By confirming, you agree to the Terms of Service. Secure encrypted connection.') }}
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Search Functionality
            $('#method-search').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                var visibleCount = 0;

                $('.payment-method-card').filter(function() {
                    var match = $(this).data('name').indexOf(value) > -1;
                    $(this).toggle(match);
                    if (match) visibleCount++;
                });

                if (visibleCount === 0) {
                    $('#no-results').removeClass('hidden');
                } else {
                    $('#no-results').addClass('hidden');
                }
            });

            // Modal Logic
            const $modal = $('#deposit-modal');
            const $backdrop = $modal.find('.modal-backdrop');
            const $content = $modal.find('.modal-content');

            function openModal(method) {
                // Populate Data
                $('#modal-method-id').val(method.id);
                $('#modal-method-name').text(method.name);

                if (method.logo) {
                    $('#modal-method-logo').html(
                        `<img src="${method.logo}" class="h-full w-full object-contain filter drop-shadow">`);
                } else {
                    $('#modal-method-logo').html(
                        `<span class="text-3xl font-bold text-white">${method.name.charAt(0)}</span>`);
                }

                // Show Modal
                $modal.removeClass('hidden');
                // Small delay for transition
                setTimeout(() => {
                    $backdrop.removeClass('opacity-0');
                    $content.removeClass('opacity-0 scale-95').addClass('scale-100');
                }, 10);
            }

            function closeModal() {
                $backdrop.addClass('opacity-0');
                $content.removeClass('scale-100').addClass('opacity-0 scale-95');

                setTimeout(() => {
                    $modal.addClass('hidden');
                    // Reset form (optional)
                    $modal.find('form')[0].reset();
                }, 300);
            }

            // Card Click Event
            $('.payment-method-card').click(function() {
                const data = {
                    id: $(this).data('id'),
                    name: $(this).find('h3').text(), // Get text directly for proper capitalization
                    logo: $(this).find('img').attr('src')
                };
                openModal(data);
            });

            // Close Events
            $('.modal-close, .modal-backdrop').click(closeModal);
        });
    </script>
@endsection
