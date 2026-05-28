@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
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

        .preview-logo {
            width: 80px;
            height: 80px;
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.02);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 1rem;
        }

        .preview-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        textarea.flat-input {
            min-height: 120px;
            resize: vertical;
        }

        .flat-input option {
            background-color: #1a1a1a;
            color: white;
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
            color: white !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background: rgba(var(--accent-primary-rgb), 0.1) !important;
        }

        .select2-results__option[aria-selected=true] {
            background: rgba(var(--accent-primary-rgb), 0.2) !important;
        }

        .type-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 2rem;
            padding: 2rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .type-card:hover {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-4px);
        }

        .type-card.active {
            background: rgba(var(--accent-primary-rgb), 0.1);
            border-color: rgba(var(--accent-primary-rgb), 0.5);
            box-shadow: 0 10px 30px -10px rgba(var(--accent-primary-rgb), 0.3);
        }

        .type-card input[type="radio"] {
            display: none;
        }

        .type-card .check-mark {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            width: 24px;
            height: 24px;
            background: var(--accent-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.3s ease;
        }

        .type-card.active .check-mark {
            opacity: 1;
            transform: scale(1);
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col lg:flex-row min-h-[calc(100vh-160px)]">
        {{-- Settings Sidebar --}}
        <div class="w-full lg:w-80 shrink-0 border-b lg:border-b-0 lg:border-r border-white/5 flex flex-col pt-8 pr-8">
            <div id="sideBarSelector">
                @include("templates.$template.blades.admin.settings.partials.sidebar")
            </div>
        </div>

        {{-- Settings Content Area --}}
        <div class="flex-1 flex flex-col pt-8 lg:pl-16 overflow-y-auto custom-scrollbar" id="contentScrollContainer">
            <div class="max-w-4xl">
                <div class="flex items-center gap-4 mb-12">
                    <a href="{{ route('admin.settings.withdrawal.index') }}"
                        class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div class="flex flex-col gap-1">
                        <h2 class="text-3xl font-light text-white tracking-tight leading-none">
                            {{ __('Create Manual Gateway') }}
                        </h2>
                        <p class="text-slate-500 text-xs font-medium tracking-wide uppercase">
                            {{ __('Add a new manual withdrawal processing method') }}
                        </p>
                    </div>
                </div>

                <form id="create-gateway-form" action="{{ route('admin.settings.withdrawal.store') }}" method="POST"
                    class="space-y-12 pb-24" enctype="multipart/form-data">
                    @csrf

                    {{-- Step 1: Select Type --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Select Gateway Type') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <label class="type-card active" data-type="crypto">
                                <input type="radio" name="type" value="crypto" checked required>
                                <div class="check-mark">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-accent-primary/20 flex items-center justify-center text-accent-primary">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-white mb-1">{{ __('Cryptocurrency') }}</h4>
                                        <p class="text-xs text-slate-500 leading-relaxed">
                                            {{ __('Bitcoin, Ethereum, USDT and other digital assets.') }}</p>
                                    </div>
                                </div>
                            </label>

                            <label class="type-card" data-type="bank_transfer">
                                <input type="radio" name="type" value="bank_transfer">
                                <div class="check-mark">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-orange-500/20 flex items-center justify-center text-orange-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 14v20m4-20v20m4-20v20M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-white mb-1">{{ __('Bank Transfer') }}</h4>
                                        <p class="text-xs text-slate-500 leading-relaxed">
                                            {{ __('Traditional wire transfers, SEPA, SWIFT, etc.') }}</p>
                                    </div>
                                </div>
                            </label>

                            <label class="type-card" data-type="digital_wallet">
                                <input type="radio" name="type" value="digital_wallet">
                                <div class="check-mark">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-purple-500/20 flex items-center justify-center text-purple-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-white mb-1">{{ __('Digital Wallet') }}</h4>
                                        <p class="text-xs text-slate-500 leading-relaxed">
                                            {{ __('PayPal, Skrill, Neteller, and other E-wallets.') }}</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Step 2: Basic Config --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Basic Information') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Gateway Name') }}
                                </label>
                                <input type="text" name="name" required class="flat-input"
                                    placeholder="e.g. USDT (TRC20)">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Gateway Logo') }}
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="preview-logo" id="logo-preview-container">
                                        <svg class="w-8 h-8 text-white/10" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 112.828 2.828L16 16m-2-2l1.586-1.586a2 2 0 112.828 2.828L20 16M7 10h.01M6 21h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <input type="file" name="logo" id="logo-input" required
                                            class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-white/10 file:text-white hover:file:bg-white/20 transition-all">
                                        <span class="text-[10px] text-slate-500 font-medium">
                                            {{ __('Download high-quality logos from') }}
                                            <a href="https://worldvectorlogo.com/" target="_blank"
                                                class="text-accent-primary hover:underline">worldvectorlogo.com</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Dynamic Fields --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4 flex items-center justify-between">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Withdrawal Fields') }}</h3>
                            <button type="button" id="add-field-btn"
                                class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-lg transition-all flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Add Field') }}
                            </button>
                        </div>

                        <div id="fields-container" class="space-y-4 mb-8">
                            {{-- Initial field for demonstration --}}
                            <div
                                class="field-row grid grid-cols-1 md:grid-cols-12 gap-6 p-6 bg-white/2 rounded-3xl border border-white/5 relative group transition-all hover:bg-white/[0.03] items-end">
                                <div class="flex flex-col gap-2 md:col-span-3">
                                    <label
                                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Field Label') }}</label>
                                    <input type="text" name="payment_information[fields][0][label]"
                                        value="{{ __('Withdrawal Address') }}" required
                                        class="flat-input !py-2 !px-4 !text-xs field-label-input"
                                        placeholder="e.g. Wallet Address">
                                    <input type="hidden" name="payment_information[fields][0][name]"
                                        value="withdrawal_address" class="field-name-input">
                                </div>
                                <div class="flex flex-col gap-2 md:col-span-3">
                                    <label
                                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Input Type') }}</label>
                                    <select class="flat-input !py-2 !px-4 !text-xs field-type-select">
                                        <option value="string" selected>{{ __('Text / String') }}</option>
                                        <option value="numeric">{{ __('Number') }}</option>
                                        <option value="email">{{ __('Email Address') }}</option>
                                        <option value="url">{{ __('URL / Link') }}</option>
                                        <option value="date">{{ __('Date') }}</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-2 md:col-span-2">
                                    <label
                                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Requirement') }}</label>
                                    <select class="flat-input !py-2 !px-4 !text-xs field-req-select">
                                        <option value="required" selected>{{ __('Required') }}</option>
                                        <option value="nullable">{{ __('Optional') }}</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-2 md:col-span-2">
                                    <label
                                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Max Length') }}</label>
                                    <input type="number" value="255"
                                        class="flat-input !py-2 !px-4 !text-xs field-max-input" placeholder="255">
                                </div>
                                <div class="flex justify-end md:col-span-2">
                                    <button type="button"
                                        class="remove-field-btn w-10 h-10 flex items-center justify-center rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <input type="hidden" name="payment_information[fields][0][validation]"
                                    value="required|string|max:255" class="field-validation-input">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Symbol / Currency') }}
                                </label>
                                <select name="payment_information[currency]" id="currency-select" class="w-full"
                                    required>
                                    <option value="">{{ __('Select Currency') }}</option>
                                    @foreach ($fiat_crypto_currencies as $code => $name)
                                        <option value="{{ strtoupper($code) }}">
                                            {{ strtoupper($code) }}{{ $name ? ' - ' . $name : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex flex-col gap-3" id="network-container">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Network') }}
                                </label>
                                <input type="text" name="payment_information[network]" placeholder="e.g. ERC20, TRC20"
                                    class="flat-input">
                            </div>

                            <div class="flex flex-col gap-3 md:col-span-2">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Instructions for User') }}
                                </label>
                                <textarea name="payment_information[instructions]" class="flat-input"
                                    placeholder="{{ __('Provide detailed instructions on how the user should fill the withdrawal information...') }}"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Trigger --}}
                    <div class="pt-10">
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
                            <span class="btn-text">{{ __('Create Gateway') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($('#currency-select').length) {
                $('#currency-select').select2({
                    width: '100%',
                    dropdownParent: $('#currency-select').parent(),
                });
            }

            let fieldIndex = 1;

            // Type Switching
            $('.type-card').on('click', function() {
                $('.type-card').removeClass('active');
                $(this).addClass('active');
                $(this).find('input').prop('checked', true);

                const type = $(this).data('type');
                if (type === 'crypto') {
                    $('#network-container').fadeIn();
                } else {
                    $('#network-container').fadeOut();
                }
            });

            // Logo Preview
            $('#logo-input').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#logo-preview-container').html(
                            `<img src="${e.target.result}" alt="Preview">`);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Helper to update validation string
            function updateValidation($row) {
                const req = $row.find('.field-req-select').val();
                const type = $row.find('.field-type-select').val();
                const max = $row.find('.field-max-input').val() || '255';

                let rules = [req, type];
                if (max) rules.push(`max:${max}`);

                $row.find('.field-validation-input').val(rules.join('|'));
            }

            // Add Field Logic
            $('#add-field-btn').on('click', function() {
                const html = `
                    <div class="field-row grid grid-cols-1 md:grid-cols-12 gap-6 p-6 bg-white/2 rounded-3xl border border-white/5 relative group animate-in fade-in slide-in-from-top-2 duration-300 items-end">
                        {{-- Col: Label (col-span-3) --}}
                        <div class="flex flex-col gap-2 md:col-span-3">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Field Label') }}</label>
                            <input type="text" name="payment_information[fields][${fieldIndex}][label]" required class="flat-input !py-2 !px-4 !text-xs field-label-input" placeholder="e.g. Wallet Address">
                            <input type="hidden" name="payment_information[fields][${fieldIndex}][name]" class="field-name-input">
                        </div>

                        {{-- Col: Type (col-span-3) --}}
                        <div class="flex flex-col gap-2 md:col-span-3">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Input Type') }}</label>
                            <select class="flat-input !py-2 !px-4 !text-xs field-type-select">
                                <option value="string" selected>{{ __('Text / String') }}</option>
                                <option value="numeric">{{ __('Number') }}</option>
                                <option value="email">{{ __('Email Address') }}</option>
                                <option value="url">{{ __('URL / Link') }}</option>
                                <option value="date">{{ __('Date') }}</option>
                            </select>
                        </div>

                        {{-- Col: Requirement (col-span-2) --}}
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Requirement') }}</label>
                            <select class="flat-input !py-2 !px-4 !text-xs field-req-select">
                                <option value="required" selected>{{ __('Required') }}</option>
                                <option value="nullable">{{ __('Optional') }}</option>
                            </select>
                        </div>

                        {{-- Col: Max Length (col-span-2) --}}
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Max Length') }}</label>
                            <input type="number" value="255" class="flat-input !py-2 !px-4 !text-xs field-max-input" placeholder="255">
                        </div>

                        {{-- Col: Action (col-span-2) --}}
                        <div class="flex justify-end md:col-span-2">
                            <button type="button" class="remove-field-btn w-10 h-10 flex items-center justify-center rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Hidden Validation --}}
                        <input type="hidden" name="payment_information[fields][${fieldIndex}][validation]" value="required|string|max:255" class="field-validation-input">
                    </div>
                `;
                $('#fields-container').append(html);
                fieldIndex++;
            });

            // Remove Field Logic
            $(document).on('click', '.remove-field-btn', function() {
                $(this).closest('.field-row').fadeOut(300, function() {
                    $(this).remove();
                    $('.field-label-input').first().trigger('input'); // Refresh duplicate check
                });
            });

            // Auto-generate Field Name from Label
            $(document).on('input', '.field-label-input', function() {
                const $row = $(this).closest('.field-row');
                const label = $(this).val();
                const slug = label.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special chars
                    .replace(/\s+/g, '_') // Replace spaces with _
                    .replace(/-+/g, '_'); // Replace hyphens with _

                $row.find('.field-name-input').val(slug);

                // Check for duplicates
                let duplicates = false;
                $('.field-name-input').each(function() {
                    const currentName = $(this).val();
                    const $currentInput = $(this);
                    let count = 0;

                    $('.field-name-input').each(function() {
                        if ($(this).val() === currentName && currentName !== '') {
                            count++;
                        }
                    });

                    if (count > 1) {
                        duplicates = true;
                        $currentInput.closest('.field-row').addClass(
                            'border-red-500/50 bg-red-500/5 has-error');
                        $currentInput.addClass('!text-red-500');
                    } else {
                        $currentInput.closest('.field-row').removeClass(
                            'border-red-500/50 bg-red-500/5 has-error');
                        $currentInput.removeClass('!text-red-500');
                    }
                });

                if (duplicates) {
                    if (!$('#duplicate-warning').length) {
                        toastNotification(
                            '{{ __('Duplicate field detected! Please use unique labels.') }}', 'error');
                    }
                }
            });

            // Logic for Visual Validation Selectors
            $(document).on('change input', '.field-type-select, .field-req-select, .field-max-input', function() {
                const $row = $(this).closest('.field-row');
                updateValidation($row);
            });

            $('#create-gateway-form').on('submit', function(e) {
                e.preventDefault();

                // Final duplicate check
                if ($('.field-row.has-error').length > 0) {
                    toastNotification('{{ __('Please resolve duplicate fields before saving.') }}',
                        'error');
                    return false;
                }

                const $form = $(this);
                const $btn = $('#submit-btn');
                const $btnText = $btn.find('.btn-text');
                const $submitIcon = $btn.find('.submit-icon');
                const $loadingIcon = $btn.find('.loading-icon');
                const originalText = $btnText.text();

                const formData = new FormData(this);

                // UI State: Loading
                $btn.prop('disabled', true).addClass('opacity-70 cursor-not-allowed');
                $btnText.text('{{ __('Creating...') }}');
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
                        setTimeout(() => {
                            window.location.href =
                                "{{ route('admin.settings.withdrawal.index') }}";
                        }, 1300);
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON.message ||
                            '{{ __('Something went wrong. Please check your inputs.') }}';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join(' | ');
                        }
                        toastNotification(errorMessage, 'error');
                        // Reset button
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
@endsection
