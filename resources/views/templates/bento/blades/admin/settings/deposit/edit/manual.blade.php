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

        .input-group {
            position: relative;
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
                            {{ __('Edit Manual Gateway') }}
                        </h2>
                        <p class="text-slate-500 text-xs font-medium tracking-wide uppercase">
                            {{ $gateway->name }} — {{ str_replace('_', ' ', strtoupper($gateway->type)) }}
                        </p>
                    </div>
                </div>

                <form id="edit-gateway-form" action="{{ route('admin.settings.deposit.update', $gateway->id) }}"
                    method="POST" class="space-y-12 pb-24" enctype="multipart/form-data">
                    @csrf

                    {{-- Base Configuration --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Basic Information') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Gateway Name') }}
                                </label>
                                <input type="text" name="name" value="{{ $gateway->name }}" required
                                    class="flat-input">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Gateway Logo') }}
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="preview-logo">
                                        <img src="{{ asset('assets/images/deposit-methods/' . $gateway->logo) }}"
                                            alt="{{ $gateway->name }}">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <input type="file" name="logo"
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

                    @php
                        $payment_information = json_decode($gateway->getRawOriginal('payment_information'), true);
                    @endphp

                    {{-- Dynamic Payment Information based on Type --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Payment Details') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-y-10">
                            @if ($gateway->type === 'crypto')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                                    <div class="flex flex-col gap-3">
                                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                            {{ __('Wallet Address') }}
                                        </label>
                                        <input type="text" name="payment_information[wallet_address]"
                                            value="{{ $payment_information['wallet_address'] ?? '' }}" required
                                            class="flat-input">
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                            {{ __('Network') }}
                                        </label>
                                        <input type="text" name="payment_information[network]"
                                            value="{{ $payment_information['network'] ?? '' }}" required
                                            class="flat-input">
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                            {{ __('Symbol / Currency') }}
                                        </label>
                                        <select name="payment_information[currency]" id="currency-select" class="w-full"
                                            required>
                                            <option value="">{{ __('Select Currency') }}</option>
                                            @foreach ($fiat_crypto_currencies as $code => $name)
                                                <option value="{{ strtoupper($code) }}"
                                                    {{ isset($payment_information['currency']) && strtoupper($payment_information['currency']) === strtoupper($code) ? 'selected' : '' }}>
                                                    {{ strtoupper($code) }}{{ $name ? ' - ' . $name : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @elseif($gateway->type === 'bank_transfer')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                                    <div class="flex flex-col gap-3">
                                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                            {{ __('Bank Name') }}
                                        </label>
                                        <input type="text" name="payment_information[bank_name]"
                                            value="{{ $payment_information['bank_name'] ?? '' }}" required
                                            class="flat-input">
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                            {{ __('Account Holder') }}
                                        </label>
                                        <input type="text" name="payment_information[account_holder]"
                                            value="{{ $payment_information['account_holder'] ?? '' }}" required
                                            class="flat-input">
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                            {{ __('Account Number') }}
                                        </label>
                                        <input type="text" name="payment_information[account_number]"
                                            value="{{ $payment_information['account_number'] ?? '' }}" required
                                            class="flat-input">
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                            {{ __('Routing Number') }}
                                        </label>
                                        <input type="text" name="payment_information[routing_number]"
                                            value="{{ $payment_information['routing_number'] ?? '' }}" class="flat-input">
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                            {{ __('SWIFT / BIC Code') }}
                                        </label>
                                        <input type="text" name="payment_information[swift]"
                                            value="{{ $payment_information['swift'] ?? '' }}" class="flat-input">
                                    </div>
                                </div>
                            @elseif($gateway->type === 'digital_wallet')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                                    <div class="flex flex-col gap-3">
                                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                            {{ __('Wallet Email / ID') }}
                                        </label>
                                        <input type="text" name="payment_information[email]"
                                            value="{{ $payment_information['email'] ?? ($payment_information['username'] ?? '') }}"
                                            required class="flat-input">
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                            {{ __('Short Tag (Optional)') }}
                                        </label>
                                        <input type="text" name="payment_information[tag]"
                                            value="{{ $payment_information['tag'] ?? '' }}" class="flat-input">
                                    </div>
                                </div>
                            @endif

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Instructions for User') }}
                                </label>
                                <textarea name="payment_information[instructions]" class="flat-input">{{ $payment_information['instructions'] ?? '' }}</textarea>
                                <span class="text-[10px] text-slate-600 font-medium">
                                    {{ __('These instructions will be displayed to the user on the deposit page.') }}
                                </span>
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
                            <span class="btn-text">{{ __('Save Changes') }}</span>
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

            $('#edit-gateway-form').on('submit', function(e) {
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

                        // Reset button UI
                        $btn.prop('disabled', false).removeClass(
                            'opacity-70 cursor-not-allowed');
                        $btnText.text(originalText);
                        $submitIcon.removeClass('hidden');
                        $loadingIcon.addClass('hidden');
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
