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

        .type-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.5rem;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1rem;
        }

        .type-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .type-card.active {
            background: rgba(var(--accent-primary-rgb), 0.05);
            border-color: rgba(var(--accent-primary-rgb), 0.3);
            box-shadow: 0 10px 30px -10px rgba(var(--accent-primary-rgb), 0.2);
        }

        .type-card .icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            transition: all 0.3s ease;
        }

        .type-card.active .icon-wrapper {
            background: var(--accent-primary);
            color: white;
        }

        textarea.flat-input {
            min-height: 120px;
            resize: vertical;
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
            <div class="max-w-3xl">
                <div class="flex items-center gap-4 mb-12">
                    <a href="{{ route('admin.settings.deposit.index') }}"
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
                            {{ __('Add a new manual payment method') }}
                        </p>
                    </div>
                </div>

                <form id="create-gateway-form" action="{{ route('admin.settings.deposit.store') }}" method="POST"
                    class="space-y-12 pb-24" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" id="gateway-type" value="crypto">

                    {{-- Type Selection --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Select Gateway Type') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="type-card active" onclick="selectType('crypto')">
                                <div class="icon-wrapper">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span
                                    class="text-sm font-bold text-white uppercase tracking-widest">{{ __('Crypto') }}</span>
                            </div>

                            <div class="type-card" onclick="selectType('bank_transfer')">
                                <div class="icon-wrapper">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <span
                                    class="text-sm font-bold text-white uppercase tracking-widest">{{ __('Bank Transfer') }}</span>
                            </div>

                            <div class="type-card" onclick="selectType('digital_wallet')">
                                <div class="icon-wrapper">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <span
                                    class="text-sm font-bold text-white uppercase tracking-widest">{{ __('Digital Wallet') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Base Information --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Basic Information') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Gateway Name') }}
                                </label>
                                <input type="text" name="name" placeholder="e.g. Bitcoin, Chase Bank" required
                                    class="flat-input">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Gateway Logo') }}
                                </label>
                                <div class="flex flex-col gap-2">
                                    <input type="file" name="logo" required
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

                    {{-- Dynamic Information Sections --}}
                    <div id="fields-crypto" class="dynamic-section">
                        <div class="settings-section">
                            <div class="mb-8 border-b border-white/5 pb-4">
                                <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Crypto Details') }}</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                                <div class="flex flex-col gap-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Wallet Address') }}
                                    </label>
                                    <input type="text" name="payment_information[wallet_address]" required
                                        class="flat-input crypto-input">
                                </div>
                                <div class="flex flex-col gap-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Network') }}
                                    </label>
                                    <input type="text" name="payment_information[network]"
                                        placeholder="e.g. TRC20, ERC20" required class="flat-input crypto-input">
                                </div>
                                <div class="flex flex-col gap-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Symbol / Currency') }}
                                    </label>
                                    <select name="payment_information[currency]" id="currency-select"
                                        class="w-full crypto-input" required>
                                        <option value="">{{ __('Select Currency') }}</option>
                                        @foreach ($fiat_crypto_currencies as $code => $name)
                                            <option value="{{ strtoupper($code) }}">
                                                {{ strtoupper($code) }}{{ $name ? ' - ' . $name : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="fields-bank_transfer" class="dynamic-section hidden">
                        <div class="settings-section">
                            <div class="mb-8 border-b border-white/5 pb-4">
                                <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Bank Details') }}</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                                <div class="flex flex-col gap-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Bank Name') }}
                                    </label>
                                    <input type="text" name="payment_information[bank_name]" required
                                        class="flat-input bank-input">
                                </div>
                                <div class="flex flex-col gap-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Account Holder') }}
                                    </label>
                                    <input type="text" name="payment_information[account_holder]" required
                                        class="flat-input bank-input">
                                </div>
                                <div class="flex flex-col gap-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Account Number') }}
                                    </label>
                                    <input type="text" name="payment_information[account_number]" required
                                        class="flat-input bank-input">
                                </div>
                                <div class="flex flex-col gap-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Routing Number') }}
                                    </label>
                                    <input type="text" name="payment_information[routing_number]"
                                        class="flat-input bank-input">
                                </div>
                                <div class="flex flex-col gap-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('SWIFT / BIC Code') }}
                                    </label>
                                    <input type="text" name="payment_information[swift]"
                                        class="flat-input bank-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="fields-digital_wallet" class="dynamic-section hidden">
                        <div class="settings-section">
                            <div class="mb-8 border-b border-white/5 pb-4">
                                <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Wallet Details') }}</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                                <div class="flex flex-col gap-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Wallet Email / ID') }}
                                    </label>
                                    <input type="text" name="payment_information[email]" required
                                        class="flat-input wallet-input">
                                </div>
                                <div class="flex flex-col gap-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Short Tag (Optional)') }}
                                    </label>
                                    <input type="text" name="payment_information[tag]"
                                        class="flat-input wallet-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Instructions --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Final Instructions') }}</h3>
                        </div>
                        <div class="flex flex-col gap-3">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                {{ __('Instructions for User') }}
                            </label>
                            <textarea name="payment_information[instructions]" class="flat-input"></textarea>
                            <span class="text-[10px] text-slate-600 font-medium">
                                {{ __('These instructions will be displayed to the user on the deposit page.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Submit Trigger --}}
                    <div class="pt-10">
                        <button type="submit" id="submit-btn"
                            class="px-10 py-4 bg-accent-primary text-white text-xs font-bold uppercase tracking-[0.15em] rounded-xl hover:bg-accent-secondary active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 w-max">
                            <svg class="w-4 h-4 submit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4" />
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
        function selectType(type) {
            // Update hidden input
            document.getElementById('gateway-type').value = type;

            // Update Cards UI
            document.querySelectorAll('.type-card').forEach(card => card.classList.remove('active'));
            event.currentTarget.classList.add('active');

            // Toggle Sections
            document.querySelectorAll('.dynamic-section').forEach(section => {
                section.classList.add('hidden');
                // Disable inputs and select arrays in hidden sections to avoid validation/payload pollution
                $(section).find('input').prop('disabled', true);
                if (type !== 'crypto') {
                    if ($(section).find('select').data('select2')) {
                        $(section).find('select').prop('disabled', true);
                    }
                }
            });

            const activeSection = document.getElementById(`fields-${type}`);
            activeSection.classList.remove('hidden');
            $(activeSection).find('input').prop('disabled', false);

            if (type === 'crypto') {
                $(activeSection).find('select').prop('disabled', false);
            } else {
                $(document).find('#currency-select').prop('disabled', true);
            }

            // Add animation
            activeSection.classList.add('animate-in', 'fade-in', 'slide-in-from-bottom-2', 'duration-300');
        }

        $(document).ready(function() {
            if ($('#currency-select').length) {
                $('#currency-select').select2({
                    width: '100%',
                    dropdownParent: $('#currency-select').parent(),
                });
            }

            // Initial state: Only crypto inputs enabled
            $('.dynamic-section:not(#fields-crypto) input').prop('disabled', true);

            $('#create-gateway-form').on('submit', function(e) {
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
                                "{{ route('admin.settings.deposit.index') }}";
                        }, 1500);
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
