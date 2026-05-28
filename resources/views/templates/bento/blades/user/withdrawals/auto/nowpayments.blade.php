@extends('templates.bento.blades.layouts.user')

@section('content')
    @php
        $currencies = json_decode($method->payment_information, true);
        $enabledCurrencies = array_filter($currencies, function ($c) {
            return ($c['status'] ?? 'disabled') === 'enabled';
        });
    @endphp

    <div class="flex flex-col h-full space-y-8 animate-fade-up">
        <!-- Header & Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <h2 class="text-3xl font-bold text-white font-heading tracking-tight flex items-center gap-3">
                    <span
                        class="w-2 h-8 bg-accent-primary rounded-sm shadow-[0_0_15px_rgba(var(--color-accent-primary),0.5)]"></span>
                    {{ $page_title }}
                </h2>
                <p class="text-text-secondary mt-2 pl-5 border-l border-white/5 font-light">
                    {{ __('Select your preferred cryptocurrency to complete the withdrawal of') }}
                    <span
                        class="text-white font-bold">{{ getSetting('currency_symbol') }}{{ number_format($withdrawal_request['amount'], getSetting('decimal_places')) }}</span>
                </p>
            </div>

            <div class="lg:col-span-2 grid grid-cols-2 lg:grid-cols-3 gap-3">
                <div
                    class="bg-secondary-dark/40 backdrop-blur-md border border-white/10 rounded-xl p-4 flex flex-col justify-between relative overflow-hidden group">
                    <span
                        class="text-[10px] text-text-secondary uppercase tracking-widest font-bold z-10">{{ __('Withdrawal Amount') }}</span>
                    <div class="flex items-baseline gap-1 mt-1 z-10">
                        <span
                            class="text-xl font-mono font-bold text-white tracking-tight">{{ number_format($withdrawal_request['amount'], getSetting('decimal_places')) }}</span>
                        <span class="text-[10px] text-accent-primary font-bold ml-1">{{ getSetting('currency') }}</span>
                    </div>
                </div>
                <div
                    class="bg-secondary-dark/40 backdrop-blur-md border border-white/10 rounded-xl p-4 flex flex-col justify-between relative overflow-hidden group">
                    <span
                        class="text-[10px] text-text-secondary uppercase tracking-widest font-bold z-10">{{ __('Gateway') }}</span>
                    <div class="flex items-baseline gap-1 mt-1 z-10">
                        <span class="text-xl font-bold text-white tracking-tight">{{ $method->name }}</span>
                    </div>
                </div>
                <div
                    class="hidden lg:flex bg-secondary-dark/40 backdrop-blur-md border border-white/10 rounded-xl p-4 flex-col justify-between relative overflow-hidden group">
                    <span
                        class="text-[10px] text-text-secondary uppercase tracking-widest font-bold z-10">{{ __('Status') }}</span>
                    <div class="flex items-center gap-2 mt-1 z-10">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-sm font-bold text-white">{{ __('Ready') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="relative max-w-md group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors">
                <svg class="w-5 h-5 text-white/30 group-focus-within:text-accent-primary transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" id="currency-search"
                class="block w-full pl-11 pr-4 py-3.5 bg-secondary-dark/30 border border-white/5 rounded-xl text-base text-white placeholder-white/20 focus:border-accent-primary/50 focus:ring-0 transition-colors"
                placeholder="{{ __('Search currency (e.g. Bitcoin, ETH, USDT)...') }}">
        </div>

        <!-- Currencies Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="currencies-grid">
            @foreach ($enabledCurrencies as $currency)
                <div class="currency-card group relative bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-5 hover:border-accent-primary/40 transition-all duration-300 cursor-pointer overflow-hidden ring-1 ring-white/5 hover:ring-accent-primary/20 hover:shadow-[0_0_30px_-10px_rgba(var(--color-accent-primary),0.3)] hover:-translate-y-1"
                    data-name="{{ strtolower($currency['name']) }}" data-code="{{ strtolower($currency['code']) }}"
                    onclick="selectCurrency({{ json_encode($currency) }})">

                    <div class="flex flex-col items-center text-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 p-2 group-hover:bg-accent-primary/10 group-hover:border-accent-primary/20 transition-all duration-300">
                            <img loading="lazy" decoding="async" src="https://nowpayments.io{{ $currency['logo_url'] }}" alt="{{ $currency['name'] }}"
                                class="w-full h-full object-contain filter drop-shadow group-hover:scale-110 transition-transform">
                        </div>
                        <div>
                            <h3
                                class="font-bold text-white text-sm tracking-tight group-hover:text-accent-primary transition-colors">
                                {{ $currency['name'] }}</h3>
                            <p class="text-[10px] text-text-secondary font-mono mt-0.5">{{ strtoupper($currency['code']) }}
                                ({{ strtoupper($currency['network']) }})
                            </p>
                        </div>
                    </div>

                    <!-- Subtle Edge Highlight -->
                    <div
                        class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/10 to-transparent group-hover:via-accent-primary/50">
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results -->
        <div id="no-results" class="hidden py-24 flex flex-col items-center justify-center text-center opacity-60">
            <svg class="w-16 h-16 text-text-secondary mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h3 class="text-white font-bold text-xl">{{ __('No Currency Found') }}</h3>
            <p class="text-text-secondary">{{ __('Try searching for a different token or abbreviation.') }}</p>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirm-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/90 backdrop-blur-md transition-opacity duration-300 opacity-0 modal-backdrop">
        </div>

        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div
                class="bg-[#0f1115] border border-white/10 rounded-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-300 modal-content relative overflow-y-auto max-h-[90vh] shadow-2xl scrollbar-thin scrollbar-thumb-white/10">
                <div
                    class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-accent-primary to-transparent">
                </div>

                <button onclick="closeModal()"
                    class="absolute top-5 right-5 text-text-secondary hover:text-white transition-colors z-20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <div class="p-8">
                    <div class="text-center mb-6">
                        <div id="modal-crypto-logo"
                            class="w-16 h-16 mx-auto bg-white/5 border border-white/10 rounded-2xl p-3 mb-4 flex items-center justify-center shadow-inner">
                        </div>
                        <h3 class="text-2xl font-bold text-white uppercase">{{ __('Confirm Transaction') }}</h3>
                        <p class="text-xs text-text-secondary tracking-widest uppercase font-bold mt-1 opacity-60">
                            {{ __('Review Details') }}</p>
                    </div>

                    <div class="space-y-4">
                        @php
                            $amount = $withdrawal_request['amount'];
                            $feePercent = getSetting('withdrawal_fee');
                            $feeAmount = ($amount * $feePercent) / 100;
                            $amountPayable = $amount - $feeAmount;
                        @endphp
                        <div class="bg-white/[0.02] border border-white/5 rounded-xl p-4 space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-text-secondary">{{ __('Withdrawal Amount') }}</span>
                                <span
                                    class="text-white font-mono font-bold">{{ getSetting('currency_symbol') }}{{ number_format($amount, getSetting('decimal_places')) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-text-secondary">{{ __('Processing Fee') }}
                                    ({{ $feePercent }}%)</span>
                                <span
                                    class="text-accent-primary font-mono font-bold">-{{ getSetting('currency_symbol') }}{{ number_format($feeAmount, getSetting('decimal_places')) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-base border-t border-white/5 pt-3">
                                <span class="text-white font-bold">{{ __('Total to Receive') }}</span>
                                <span
                                    class="text-accent-primary font-mono font-bold underline decoration-accent-primary/30 underline-offset-4">
                                    {{ getSetting('currency_symbol') }}{{ number_format($amountPayable, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-t border-white/5 pt-3">
                                <span class="text-text-secondary">{{ __('Selected Currency') }}</span>
                                <span id="modal-crypto-name" class="text-white font-bold"></span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-t border-white/5 pt-3">
                                <span class="text-text-secondary">{{ __('Network') }}</span>
                                <span id="modal-crypto-network"
                                    class="text-white uppercase text-[10px] font-bold px-2 py-0.5 bg-white/10 rounded tracking-wider"></span>
                            </div>
                        </div>

                        <form id="nowpayments-form" action="{{ route('user.withdrawals.nowpayments.withdraw') }}"
                            method="POST" class="ajax-form" data-action="redirect">
                            @csrf
                            <input type="hidden" name="withdrawal_currency" id="selected-currency-code">
                            {{-- amount --}}
                            <input type="hidden" name="amount" id="amount" value="{{ $amount }}">



                            <!-- Wallet Address Input -->
                            <div class="space-y-2 mb-6">
                                <label
                                    class="block text-[10px] text-text-secondary uppercase font-bold tracking-widest pl-1">
                                    {{ __('Destination Address') }}
                                </label>
                                <div class="relative group">
                                    <input type="text" name="wallet_address" required
                                        class="w-full bg-white/[0.03] border border-white/10 rounded-xl py-4 px-4 text-white focus:border-accent-primary/50 transition-all outline-none placeholder:text-white/10 text-base font-mono"
                                        placeholder="{{ __('PASTE YOUR Payout ADDRESS HERE') }}">
                                </div>
                                <p class="text-[9px] text-text-secondary pl-1 leading-relaxed opacity-60">
                                    {{ __(':site_name is not responsible for funds sent to an incorrect address. Please double check.', ['site_name' => getSetting('name')]) }}
                                </p>
                            </div>

                            <button type="submit"
                                class="group relative w-full py-4 bg-accent-primary hover:bg-accent-primary-hover text-white font-bold rounded-xl transition-all shadow-[0_0_20px_rgba(var(--color-accent-primary),0.3)] hover:shadow-[0_0_30px_rgba(var(--color-accent-primary),0.5)] active:scale-[0.98] cursor-pointer">
                                <span class="flex items-center justify-center gap-3">
                                    {{ __('Proceed with Withdrawal') }}
                                    <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </span>
                            </button>
                        </form>

                        <p class="text-[10px] text-text-secondary text-center opacity-60">
                            {{ __('Final exchange rate will be calculated at the time of processing.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const $modal = $('#confirm-modal');
        const $backdrop = $modal.find('.modal-backdrop');
        const $content = $modal.find('.modal-content');

        function selectCurrency(currency) {
            // Populate Modal
            $('#selected-currency-code').val(currency.code);
            $('#modal-crypto-name').text(currency.name + ' (' + currency.code.toUpperCase() + ')');
            $('#modal-crypto-network').text(currency.network);
            $('#modal-crypto-logo').html(
                `<img src="https://nowpayments.io${currency.logo_url}" class="w-full h-full object-contain filter drop-shadow">`
            );

            // Show Modal
            $modal.removeClass('hidden');
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
            }, 300);
        }

        $(document).ready(function() {
            // Search Functionality
            $('#currency-search').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                let visibleCount = 0;

                $('.currency-card').each(function() {
                    const name = $(this).data('name');
                    const code = $(this).data('code');
                    const match = name.includes(value) || code.includes(value);

                    $(this).toggle(match);
                    if (match) visibleCount++;
                });

                if (visibleCount === 0) {
                    $('#no-results').removeClass('hidden');
                } else {
                    $('#no-results').addClass('hidden');
                }
            });

            // Close modal on backdrop click
            $('.modal-backdrop').click(closeModal);
        });
    </script>
@endsection
