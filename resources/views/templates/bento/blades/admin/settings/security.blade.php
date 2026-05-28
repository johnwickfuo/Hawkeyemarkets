@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
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
                        {{ __('Security Settings') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium tracking-wide">
                        {{ __('Manage platform security, multi-factor authentication, and bot protection.') }}
                    </p>
                </div>

                <form id="security-settings-form" action="{{ route('admin.settings.security.update') }}" method="POST"
                    class="space-y-12 pb-24">
                    @csrf

                    {{-- Core Security Configuration --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">
                                {{ __('Authentication & Verification') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Email Verification') }}</label>
                                <select name="email_verification" class="flat-select">
                                    <option value="enabled"
                                        {{ getSetting('email_verification') == 'enabled' ? 'selected' : '' }}>
                                        {{ __('Enabled') }}</option>
                                    <option value="disabled"
                                        {{ getSetting('email_verification') == 'disabled' ? 'selected' : '' }}>
                                        {{ __('Disabled') }}</option>
                                </select>
                                <p class="text-[1px] text-slate-500 mt-1 italic font-medium">
                                    {{ __('Require users to verify their email after registration.') }}</p>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Login OTP') }}</label>
                                <select name="login_otp" class="flat-select">
                                    <option value="enabled" {{ getSetting('login_otp') == 'enabled' ? 'selected' : '' }}>
                                        {{ __('Enabled') }}</option>
                                    <option value="disabled" {{ getSetting('login_otp') == 'disabled' ? 'selected' : '' }}>
                                        {{ __('Disabled') }}</option>
                                </select>
                                <p class="text-[10px] text-slate-500 mt-1 italic font-medium">
                                    {{ __('Send a one-time password to email during login.') }}</p>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Strong Passwords') }}</label>
                                <select name="require_strong_password" class="flat-select">
                                    <option value="enabled"
                                        {{ getSetting('require_strong_password') == 'enabled' ? 'selected' : '' }}>
                                        {{ __('Required') }}</option>
                                    <option value="disabled"
                                        {{ getSetting('require_strong_password') == 'disabled' ? 'selected' : '' }}>
                                        {{ __('Optional') }}</option>
                                </select>
                                <p class="text-[10px] text-slate-500 mt-1 italic font-medium">
                                    {{ __('Enforce symbols, numbers, and mixed case in passwords.') }}</p>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Google reCAPTCHA') }}</label>
                                <select name="google_recaptcha" class="flat-select">
                                    <option value="enabled"
                                        {{ getSetting('google_recaptcha') == 'enabled' ? 'selected' : '' }}>
                                        {{ __('Enabled') }}</option>
                                    <option value="disabled"
                                        {{ getSetting('google_recaptcha') == 'disabled' ? 'selected' : '' }}>
                                        {{ __('Disabled') }}</option>
                                </select>
                                <p class="text-[10px] text-slate-500 mt-1 italic font-medium">
                                    {{ __('Protect registration and login with reCAPTCHA v2.') }}</p>
                            </div>
                        </div>
                    </div>


                    @if (moduleEnabled('kyc_module'))
                        {{-- KYC --}}
                        <div class="settings-section">
                            <div class="mb-8 border-b border-white/5 pb-4">
                                <h3 class="text-xl font-medium text-white tracking-wide">
                                    {{ __('Identity Verification (KYC)') }}</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                                @foreach ($kyc_settings as $setting)
                                    <div class="flex flex-col gap-3">
                                        <label
                                            class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Requir KYC for ' . $setting['name']) }}</label>
                                        <select name="kyc[{{ $setting['name'] }}]" class="flat-select">
                                            <option value="enabled"
                                                {{ ($setting['status'] ?? 'disabled') === 'enabled' ? 'selected' : '' }}>
                                                {{ __('Required') }}</option>
                                            <option value="disabled"
                                                {{ ($setting['status'] ?? 'disabled') === 'disabled' ? 'selected' : '' }}>
                                                {{ __('Disabled') }}</option>
                                        </select>
                                        <p class="text-[10px] text-slate-500 mt-1 italic font-medium">
                                            {{ __($setting['description']) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4 flex items-center justify-between">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('reCAPTCHA Credentials') }}</h3>
                            <a href="https://www.google.com/recaptcha/admin" target="_blank"
                                class="text-[10px] font-bold text-accent-primary uppercase tracking-widest hover:underline flex items-center gap-2">
                                <span>{{ __('Get API Keys') }}</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Site Key') }}</label>
                                <input type="text" name="nocaptcha_sitekey"
                                    value="{{ sandBoxCredentials(config('captcha.sitekey')) }}" class="flat-input"
                                    placeholder="6LdmH...">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Secret Key') }}</label>
                                <input type="text" name="nocaptcha_secret"
                                    value="{{ sandBoxCredentials(config('captcha.secret')) }}" class="flat-input"
                                    placeholder="KLFJJR">
                            </div>
                        </div>
                    </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-8 mt-12">
                <button type="submit" id="submit-btn"
                    class="px-10 py-4 bg-accent-primary text-white text-xs font-bold uppercase tracking-[0.15em] rounded-xl hover:bg-accent-secondary active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 w-max">
                    <svg class="w-4 h-4 submit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
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
    <script>
        $(document).ready(function() {
            /**
             * Form Submission
             */
            $('#security-settings-form').on('submit', function(e) {
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
