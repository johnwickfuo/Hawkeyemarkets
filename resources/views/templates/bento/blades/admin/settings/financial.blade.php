@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Flat Design Adjustments */
        .settings-section {
            padding-bottom: 2rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .settings-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .input-group {
            position: relative;
        }

        .input-group .currency-symbol {
            position: absolute;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            font-weight: bold;
            pointer-events: none;
        }

        .input-group.has-symbol input {
            padding-left: 3rem;
        }

        /* Select2 Flat Styling */
        .select2-container--default .select2-selection--single {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 1rem !important;
            height: 56px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.3s ease !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: rgba(var(--accent-primary-rgb), 0.5) !important;
            background: rgba(255, 255, 255, 0.05) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white !important;
            padding-left: 1.5rem !important;
            padding-right: 2.5rem !important;
            width: 100% !important;
            font-weight: 700 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 1rem !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: rgba(255, 255, 255, 0.4) transparent transparent transparent !important;
            border-width: 5px 4px 0 4px !important;
        }

        .select2-container--open .select2-dropdown {
            background: #151525 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 1rem !important;
            margin-top: 8px !important;
            overflow: hidden !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5) !important;
            z-index: 9999 !important;
        }

        .select2-search--dropdown {
            padding: 1rem !important;
            background: transparent !important;
        }

        .select2-search--dropdown .select2-search__field {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 0.5rem !important;
            color: white !important;
            padding: 0.75rem 1rem !important;
            outline: none !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: rgba(var(--accent-primary-rgb), 0.5) !important;
        }

        .select2-results__option {
            padding: 0.75rem 1.5rem !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.02) !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background: rgba(var(--accent-primary-rgb), 0.1) !important;
        }

        .select2-results__option[aria-selected=true] {
            background: rgba(var(--accent-primary-rgb), 0.2) !important;
        }

        /* Modern Flat Input styling */
        .flat-input {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            color: white;
            font-weight: 700;
            transition: all 0.3s ease;
            outline: none;
            width: 100%;
        }

        .flat-input:focus {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(var(--accent-primary-rgb), 0.5);
        }

        .flat-select {
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            color: white;
            font-weight: 700;
            transition: all 0.3s ease;
            outline: none;
            width: 100%;
            appearance: none;
        }

        .flat-select:focus {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(var(--accent-primary-rgb), 0.5);
        }

        .flat-select option {
            background-color: #1a1a2e;
            color: #fff;
        }

        /* Module Disabled Overlay */
        .module-overlay-wrapper {
            position: relative;
        }

        .module-disabled-overlay {
            position: absolute;
            inset: -0.5rem -1rem;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
            pointer-events: auto;
            /* Ensure buttons are clickable */
        }

        .module-overlay-wrapper.is-disabled {
            opacity: 0.5;
            filter: grayscale(0.5);
        }

        .module-overlay-wrapper.is-disabled>div:not(.module-disabled-overlay) {
            pointer-events: none;
            /* Disable fields but not the overlay */
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col lg:flex-row min-h-[calc(100vh-160px)]">
        {{-- Settings Sidebar (Global Navigator) --}}
        <div class="w-full lg:w-80 shrink-0 border-b lg:border-b-0 lg:border-r border-white/5 flex flex-col pt-8 pr-8">
            <div id="sideBarSelector">
                @include("templates.$template.blades.admin.settings.partials.sidebar")
            </div>
        </div>

        {{-- Settings Content Area --}}
        <div class="flex-1 flex flex-col pt-8 lg:pl-16 overflow-y-auto custom-scrollbar" id="contentScrollContainer">
            <div class="max-w-3xl">
                <div class="flex flex-col gap-2 mb-12">
                    <h2 class="text-4xl font-light text-white tracking-tight leading-none">
                        {{ __('Financial Settings') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium tracking-wide">
                        {{ __('Configure platform currency, dynamic fees, and transaction limits.') }}
                    </p>
                </div>

                <form id="financial-settings-form" action="{{ route('admin.settings.financial.update') }}" method="POST"
                    class="space-y-12 pb-24">
                    @csrf

                    {{-- Local Currency Configuration --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Currency Preferences') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3 md:col-span-2">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Primary Platform Currency') }}</label>
                                <div class="relative">
                                    <select id="currency-select" class="w-full">
                                        @foreach ($currencies as $code => $curr)
                                            <option value="{{ $code }}" data-symbol="{{ $curr['symbol'] }}"
                                                data-name="{{ $curr['name'] }}" data-code="{{ $code }}"
                                                @if ($code == getSetting('currency')) selected @endif>
                                                {{ $code }} - {{ $curr['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Hidden inputs to submit --}}
                                <input type="hidden" name="currency_name" id="hidden-currency-name"
                                    value="{{ getSetting('currency') }}">
                                <input type="hidden" name="currency_symbol" id="hidden-currency-symbol"
                                    value="{{ getSetting('currency_symbol') }}">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Symbol Position') }}</label>
                                <select name="currency_position" class="flat-select">
                                    <option value="before"
                                        {{ getSetting('currency_symbol_position') == 'before' ? 'selected' : '' }}>
                                        {{ __('Before Amount ($100)') }}
                                    </option>
                                    <option value="after"
                                        {{ getSetting('currency_symbol_position') == 'after' ? 'selected' : '' }}>
                                        {{ __('After Amount (100$)') }}
                                    </option>
                                </select>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Decimal Format') }}</label>
                                <input type="number" name="decimal_places" value="{{ getSetting('decimal_places') }}"
                                    min="0" max="8" required class="flat-input">
                            </div>
                        </div>
                    </div>

                    {{-- Deposits Configuration --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Deposits') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Minimum Deposit') }}</label>
                                <div class="input-group has-symbol">
                                    <span
                                        class="currency-symbol dynamic-currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                    <input type="number" name="min_deposit" value="{{ getSetting('min_deposit', 1) }}"
                                        step="0.01" min="0" required class="flat-input">
                                </div>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Maximum Deposit') }}</label>
                                <div class="input-group has-symbol">
                                    <span
                                        class="currency-symbol dynamic-currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                    <input type="number" name="max_deposit" value="{{ getSetting('max_deposit', 6000) }}"
                                        step="0.01" min="0" required class="flat-input">
                                </div>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Deposit Fee (%)') }}</label>
                                <div class="input-group">
                                    <input type="number" name="deposit_fee" value="{{ getSetting('deposit_fee', 5) }}"
                                        step="0.01" min="0" required class="flat-input">
                                </div>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Invoice Expiry (Hrs)') }}</label>
                                <div class="input-group">
                                    <input type="number" name="deposit_expires_at"
                                        value="{{ getSetting('deposit_expires_at', 10) }}" step="1" min="1"
                                        required class="flat-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Withdrawals Configuration --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Withdrawals') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Minimum Withdrawal') }}</label>
                                <div class="input-group has-symbol">
                                    <span
                                        class="currency-symbol dynamic-currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                    <input type="number" name="min_withdrawal"
                                        value="{{ getSetting('min_withdrawal', 1) }}" step="0.01" min="0"
                                        required class="flat-input">
                                </div>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Maximum Withdrawal') }}</label>
                                <div class="input-group has-symbol">
                                    <span
                                        class="currency-symbol dynamic-currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                    <input type="number" name="max_withdrawal"
                                        value="{{ getSetting('max_withdrawal', 6000) }}" step="0.01" min="0"
                                        required class="flat-input">
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 md:col-span-2 max-w-sm">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Withdrawal Fee (%)') }}</label>
                                <div class="input-group">
                                    <input type="number" name="withdrawal_fee"
                                        value="{{ getSetting('withdrawal_fee', 5) }}" step="0.01" min="0"
                                        required class="flat-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Trading Assets Configuration --}}
                    <div class="module-overlay-wrapper @if (!moduleEnabled('stock_module')) is-disabled @endif">
                        @if (!moduleEnabled('stock_module'))
                            <div class="module-disabled-overlay">
                                <a href="{{ route('admin.settings.modules.index') }}"
                                    class="px-8 py-4 bg-accent-primary hover:bg-accent-secondary border border-white/20 rounded-2xl text-white shadow-[0_0_30px_rgba(var(--accent-primary-rgb),0.3)] hover:scale-105 transition-all flex items-center gap-4 active:scale-95 group/btn">
                                    <div class="flex flex-col items-start gap-1">
                                        <span
                                            class="text-xs font-black uppercase tracking-[0.2em] leading-none">{{ __('Stocks Module Required') }}</span>
                                        <span
                                            class="text-[10px] font-medium text-white/70 leading-none lowercase">{{ __('Click to enable this feature') }}</span>
                                    </div>
                                    <svg class="w-5 h-5 opacity-70 group-hover/btn:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        @endif

                        <div class="settings-section">
                            <div class="mb-8 border-b border-white/5 pb-4">
                                <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Stocks Trading') }}</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Minimum Purchase') }}</label>
                                    <div class="input-group has-symbol">
                                        <span
                                            class="currency-symbol dynamic-currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                        <input type="number" name="min_stock_purchase"
                                            value="{{ getSetting('min_stock_purchase', 250) }}" step="0.01"
                                            min="0" required class="flat-input">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Maximum Purchase') }}</label>
                                    <div class="input-group has-symbol">
                                        <span
                                            class="currency-symbol dynamic-currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                        <input type="number" name="max_stock_purchase"
                                            value="{{ getSetting('max_stock_purchase', 10000) }}" step="0.01"
                                            min="0" required class="flat-input">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Purchase Fee (%)') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="stock_purchase_fee_percent"
                                            value="{{ getSetting('stock_purchase_fee_percent', 1.5) }}" step="0.01"
                                            min="0" required class="flat-input">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Sale Fee (%)') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="stock_sale_fee_percent"
                                            value="{{ getSetting('stock_sale_fee_percent', 1.5) }}" step="0.01"
                                            min="0" required class="flat-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ETFs Trading --}}
                    <div class="module-overlay-wrapper @if (!moduleEnabled('etf_module')) is-disabled @endif">
                        @if (!moduleEnabled('etf_module'))
                            <div class="module-disabled-overlay">
                                <a href="{{ route('admin.settings.modules.index') }}"
                                    class="px-8 py-4 bg-accent-primary hover:bg-accent-secondary border border-white/20 rounded-2xl text-white shadow-[0_0_30px_rgba(var(--accent-primary-rgb),0.3)] hover:scale-105 transition-all flex items-center gap-4 active:scale-95 group/btn">
                                    <div class="flex flex-col items-start gap-1">
                                        <span
                                            class="text-xs font-black uppercase tracking-[0.2em] leading-none">{{ __('ETFs Module Required') }}</span>
                                        <span
                                            class="text-[10px] font-medium text-white/70 leading-none lowercase">{{ __('Click to enable this feature') }}</span>
                                    </div>
                                    <svg class="w-5 h-5 opacity-70 group-hover/btn:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        @endif

                        <div class="settings-section">
                            <div class="mb-8 border-b border-white/5 pb-4">
                                <h3 class="text-xl font-medium text-white tracking-wide">{{ __('ETFs Trading') }}</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Minimum Purchase') }}</label>
                                    <div class="input-group has-symbol">
                                        <span
                                            class="currency-symbol dynamic-currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                        <input type="number" name="min_etf_purchase"
                                            value="{{ getSetting('min_etf_purchase', 250) }}" step="0.01"
                                            min="0" required class="flat-input">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Maximum Purchase') }}</label>
                                    <div class="input-group has-symbol">
                                        <span
                                            class="currency-symbol dynamic-currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                        <input type="number" name="max_etf_purchase"
                                            value="{{ getSetting('max_etf_purchase', 10000) }}" step="0.01"
                                            min="0" required class="flat-input">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Purchase Fee (%)') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="etf_purchase_fee_percent"
                                            value="{{ getSetting('etf_purchase_fee_percent', 1.5) }}" step="0.01"
                                            min="0" required class="flat-input">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Sale Fee (%)') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="etf_sale_fee_percent"
                                            value="{{ getSetting('etf_sale_fee_percent', 1.5) }}" step="0.01"
                                            min="0" required class="flat-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bonds Trading --}}
                    <div class="module-overlay-wrapper @if (!moduleEnabled('bonds_module')) is-disabled @endif">
                        @if (!moduleEnabled('bonds_module'))
                            <div class="module-disabled-overlay">
                                <a href="{{ route('admin.settings.modules.index') }}"
                                    class="px-8 py-4 bg-accent-primary hover:bg-accent-secondary border border-white/20 rounded-2xl text-white shadow-[0_0_30px_rgba(var(--accent-primary-rgb),0.3)] hover:scale-105 transition-all flex items-center gap-4 active:scale-95 group/btn">
                                    <div class="flex flex-col items-start gap-1">
                                        <span
                                            class="text-xs font-black uppercase tracking-[0.2em] leading-none">{{ __('Bonds Module Required') }}</span>
                                        <span
                                            class="text-[10px] font-medium text-white/70 leading-none lowercase">{{ __('Click to enable this feature') }}</span>
                                    </div>
                                    <svg class="w-5 h-5 opacity-70 group-hover/btn:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        @endif

                        <div class="settings-section">
                            <div class="mb-8 border-b border-white/5 pb-4">
                                <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Bonds Trading') }}</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Minimum Purchase') }}</label>
                                    <div class="input-group has-symbol">
                                        <span
                                            class="currency-symbol dynamic-currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                        <input type="number" name="min_bond_purchase"
                                            value="{{ getSetting('min_bond_purchase', 250) }}" step="0.01"
                                            min="0" required class="flat-input">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Maximum Purchase') }}</label>
                                    <div class="input-group has-symbol">
                                        <span
                                            class="currency-symbol dynamic-currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                        <input type="number" name="max_bond_purchase"
                                            value="{{ getSetting('max_bond_purchase', 10000) }}" step="0.01"
                                            min="0" required class="flat-input">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Purchase Fee (%)') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="bond_purchase_fee_percent"
                                            value="{{ getSetting('bond_purchase_fee_percent', 1.5) }}" step="0.01"
                                            min="0" required class="flat-input">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Sale Fee (%)') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="bond_sale_fee_percent"
                                            value="{{ getSetting('bond_sale_fee_percent', 1.5) }}" step="0.01"
                                            min="0" required class="flat-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-8 mt-12">
                        <button type="submit" id="submit-btn"
                            class="px-10 py-4 bg-accent-primary text-white text-xs font-bold uppercase tracking-[0.15em] rounded-xl hover:bg-accent-secondary active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 w-max">
                            <svg class="w-4 h-4 submit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <svg class="w-4 h-4 hidden loading-icon animate-spin" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span class="btn-text">{{ __('Save Changes') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            /**
             * Select2 Initialization
             */
            function customMatcher(params, data) {
                if ($.trim(params.term) === '') {
                    return data;
                }
                if (typeof data.text === 'undefined') {
                    return null;
                }

                const term = params.term.toLowerCase();
                const $el = $(data.element);

                if (data.text.toLowerCase().indexOf(term) > -1) {
                    return data;
                }

                for (const key in $el.data()) {
                    const value = $el.data(key);
                    if (typeof value === 'string' && value.toLowerCase().indexOf(term) > -1) {
                        return data;
                    }
                }
                return null;
            }

            function formatCurrency(state) {
                if (!state.id) return state.text;
                const $el = $(state.element);
                const symbol = $el.data('symbol');
                const name = $el.data('name');

                return $(`
                    <div class="flex items-center gap-4 py-1">
                        <span class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-xs font-bold text-white border border-white/10 shrink-0">${symbol}</span>
                        <div class="flex flex-col overflow-hidden">
                            <span class="text-xs font-bold text-white truncate">${state.id} - ${name}</span>
                        </div>
                    </div>
                `);
            }

            $('#currency-select').select2({
                templateResult: formatCurrency,
                templateSelection: formatCurrency,
                matcher: customMatcher,
                width: '100%',
                dropdownParent: $('#currency-select').parent(),
            });

            // Update hidden inputs & dynamic symbols on change
            $('#currency-select').on('change', function() {
                const $opt = $(this).find(':selected');
                const newCode = $opt.data('code');
                const newSymbol = $opt.data('symbol');

                $('#hidden-currency-name').val(newCode);
                $('#hidden-currency-symbol').val(newSymbol);

                // Update dynamic symbols across the page
                $('.dynamic-currency-symbol').text(newSymbol);
            });

            // Initial sync
            if ($('#currency-select').val()) {
                const $opt = $('#currency-select').find(':selected');
                $('#hidden-currency-name').val($opt.data('code'));
                $('#hidden-currency-symbol').val($opt.data('symbol'));
            }

            /**
             * Form Submission
             */
            $('#financial-settings-form').on('submit', function(e) {
                e.preventDefault();

                const $form = $(this);
                const $btn = $('#submit-btn');
                const $btnText = $btn.find('.btn-text');
                const $submitIcon = $btn.find('.submit-icon');
                const $loadingIcon = $btn.find('.loading-icon');
                const originalText = $btnText.text();

                const formData = new FormData(this);

                // UI State: Loading
                $btn.prop('disabled', true).addClass('opacity-70 cursor-not-allowed');
                $btnText.text('{{ __('Saving...') }}');
                $submitIcon.addClass('hidden');
                $loadingIcon.removeClass('hidden');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastNotification(response.message, 'success');
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON.message ||
                            '{{ __('Something went wrong. Please check your inputs.') }}';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join(' | ');
                        }
                        toastNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        // Reset UI State
                        $btn.prop('disabled', false).removeClass(
                            'opacity-70 cursor-not-allowed');
                        $btnText.text(originalText);
                        $submitIcon.removeClass('hidden');
                        $loadingIcon.addClass('hidden');
                    }
                });
            });
        });
    </script>
@endpush
