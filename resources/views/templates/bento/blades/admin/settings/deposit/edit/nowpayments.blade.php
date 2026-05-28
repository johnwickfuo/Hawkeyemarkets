@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
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

        .currency-row {
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.25rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            user-select: none;
        }

        .currency-row:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .password-toggle:hover {
            color: white;
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
                            {{ __('Edit NowPayments') }}
                        </h2>
                        <p class="text-slate-500 text-xs font-medium tracking-wide uppercase">
                            {{ __('Automatic Crypto Gateway') }}
                        </p>
                    </div>
                </div>

                <form id="nowpayments-form" action="{{ route('admin.settings.deposit.nowpayment.update') }}" method="POST"
                    class="space-y-12 pb-24" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $gateway->id }}">

                    {{-- API Configuration --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('API Configuration') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('API Key') }}
                                </label>
                                <div class="relative">
                                    <input type="password" name="nowpayment_api_key"
                                        value="{{ sandBoxCredentials(safeDecrypt(config('site.nowpayment_api_key'))) }}"
                                        placeholder="Enter API Key" class="flat-input pr-12">
                                    <button type="button" class="password-toggle">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="text-[10px] text-slate-600 font-medium italic">
                                    {{ __('Leave blank to keep current encrypted key.') }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Secret Key') }}
                                </label>
                                <div class="relative">
                                    <input type="password" name="nowpayment_secret_key"
                                        value="{{ sandBoxCredentials(safeDecrypt(config('site.nowpayment_secret_key'))) }}"
                                        placeholder="Enter Secret Key" class="flat-input pr-12">
                                    <button type="button" class="password-toggle">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="text-[10px] text-slate-600 font-medium italic">
                                    {{ __('Used for IPN verification.') }}
                                </span>
                            </div>

                        </div>
                    </div>

                    {{-- Gateway Information --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Gateway Information') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Display Name') }}
                                </label>
                                <input type="text" name="name" value="{{ $gateway->name }}" required
                                    class="flat-input">
                                <span class="text-[10px] text-slate-600 font-medium italic">
                                    {{ __('You can set a custom display name if you don\'t want to use the default.') }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Logo') }}
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
                                            {{ __('Download logos from') }}
                                            <a href="https://worldvectorlogo.com/" target="_blank"
                                                class="text-accent-primary hover:underline">worldvectorlogo.com</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Currency Management --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4 flex items-center justify-between">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Currency Management') }}</h3>
                            <div class="relative w-48">
                                <input type="text" id="currency-search" placeholder="Search..."
                                    class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-[10px] text-white outline-none focus:border-accent-primary/50 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3 max-h-[500px] overflow-y-auto custom-scrollbar pr-2"
                            id="currency-list">
                            @php
                                $payment_info = $gateway->payment_information;
                            @endphp

                            @foreach ($payment_info as $index => $currency)
                                <div class="currency-row" data-code="{{ $currency['code'] }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center p-1.5">
                                            <img loading="lazy" decoding="async" src="https://nowpayments.io/images/coins/{{ strtolower($currency['code']) }}.svg"
                                                loading="lazy" decoding="async" class="w-full h-full object-contain">
                                        </div>
                                        <div class="flex flex-col">
                                            <span
                                                class="text-xs font-bold text-white">{{ strtoupper($currency['code']) }}</span>
                                            <span
                                                class="text-[9px] text-slate-500 font-medium uppercase tracking-tighter">{{ $currency['network'] ?? 'Native' }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <input type="hidden" name="currencies[{{ $index }}][code]"
                                            value="{{ $currency['code'] }}">

                                        <label class="switch">
                                            <input type="checkbox" name="currencies[{{ $index }}][status]"
                                                value="enabled"
                                                {{ ($currency['status'] ?? 'disabled') === 'enabled' ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
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
                            <span class="btn-text">{{ __('Save Settings') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Password Visibility Toggle
            $('.password-toggle').on('click', function() {
                const $input = $(this).siblings('input');
                const type = $input.attr('type') === 'password' ? 'text' : 'password';
                $input.attr('type', type);

                // Update Icon
                if (type === 'text') {
                    $(this).html(`
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    `);
                } else {
                    $(this).html(`
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    `);
                }
            });

            // Search functionality
            $('#currency-search').on('input', function() {
                const term = $(this).val().toLowerCase();
                $('.currency-row').each(function() {
                    const code = $(this).data('code').toLowerCase();
                    $(this).toggle(code.includes(term));
                });
            });

            // Row click toggle
            $('.currency-row').on('click', function(e) {
                // Prevent toggle if clicking directly on the switch/input to avoid double toggle
                if (!$(e.target).closest('.switch').length) {
                    const $checkbox = $(this).find('input[type="checkbox"]');
                    $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
                }
            });

            // Form submission
            $('#nowpayments-form').on('submit', function(e) {
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

                        // If logo was changed, update preview
                        if (response.logo_url) {
                            $('.preview-logo img').attr('src', response.logo_url);
                        }
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
