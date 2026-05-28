@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div id="kyc-submission-form" class="space-y-6 {{ isset($last_kyc) && $last_kyc ? 'hidden' : '' }}">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-0">
                <div>
                    <h1 class="text-2xl font-bold text-white font-heading">{{ __('Identity Verification') }}</h1>
                    <p class="text-text-secondary text-sm mt-1">
                        {{ __('Complete the steps below to verify your identity and unlock full account features.') }}</p>
                </div>
                <div
                    class="flex items-center justify-between md:block md:text-right bg-white/5 md:bg-transparent p-3 md:p-0 rounded-lg md:rounded-none border border-white/5 md:border-none">
                    <span class="text-xs font-bold text-accent-primary uppercase tracking-widest">{{ __('Status') }}</span>
                    <div class="flex items-center gap-2 mt-0 md:mt-1">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                        </span>
                        <span class="font-bold text-white">{{ __('Not Verified') }}</span>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="bg-secondary-dark rounded-xl border border-white/10 p-4">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-text-secondary uppercase tracking-widest" id="step-indicator">
                        {{ __('Step 1 of 6') }}
                    </span>
                    <span class="text-xs font-bold text-white uppercase tracking-widest" id="step-title">
                        {{ __('Personal Details') }}
                    </span>
                </div>
                <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                    <div id="progress-bar" class="h-full bg-accent-primary transition-all duration-500 ease-out w-[16%]">
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <!-- Form Container -->
            <form id="kyc-form" method="POST" enctype="multipart/form-data"
                class="bg-secondary-dark rounded-xl border border-white/10 overflow-hidden relative min-h-[400px]">
                @csrf
                <!-- Decorative Background -->
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-accent-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none">
                </div>

                <!-- Steps -->
                <div class="p-6 lg:p-8 relative z-10">

                    <!-- Step 1: Personal Details -->
                    <div class="kyc-step" data-step="1">
                        <h3 class="text-lg font-bold text-white mb-6">{{ __('Personal Details') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold text-text-secondary uppercase tracking-wider">{{ __('First Name') }}</label>
                                <input type="text" name="first_name" value="{{ Auth::user()->first_name }}"
                                    class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-colors"
                                    placeholder="John">
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold text-text-secondary uppercase tracking-wider">{{ __('Last Name') }}</label>
                                <input type="text" name="last_name" value="{{ Auth::user()->last_name }}"
                                    class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-colors"
                                    placeholder="Doe">
                            </div>
                            <div class="space-y-2">
                                <div class="space-y-2">
                                    <label
                                        class="text-xs font-bold text-text-secondary uppercase tracking-wider">{{ __('Date of Birth') }}</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <select name="dob_day" id="dob_day"
                                            class="bg-white/5 border border-white/10 rounded-lg px-2 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-colors appearance-none text-center cursor-pointer">
                                            <option value="" disabled selected>{{ __('Day') }}</option>
                                            @for ($i = 1; $i <= 31; $i++)
                                                <option value="{{ $i }}" class="bg-secondary-dark">
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                        <select name="dob_month" id="dob_month"
                                            class="bg-white/5 border border-white/10 rounded-lg px-2 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-colors appearance-none text-center cursor-pointer">
                                            <option value="" disabled selected>{{ __('Month') }}</option>
                                            @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $month)
                                                <option value="{{ $index + 1 }}" class="bg-secondary-dark">
                                                    {{ __($month) }}</option>
                                            @endforeach
                                        </select>
                                        <select name="dob_year" id="dob_year"
                                            class="bg-white/5 border border-white/10 rounded-lg px-2 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-colors appearance-none text-center cursor-pointer">
                                            <option value="" disabled selected>{{ __('Year') }}</option>
                                            @for ($i = date('Y') - 18; $i >= date('Y') - 100; $i--)
                                                <option value="{{ $i }}" class="bg-secondary-dark">
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-xs font-bold text-text-secondary uppercase tracking-wider">{{ __('Phone Number') }}</label>
                                    <div class="flex gap-2">
                                        <div class="relative w-1/3">
                                            <select name="phone_code"
                                                class="w-full bg-white/5 border border-white/10 rounded-lg pl-10 pr-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-colors appearance-none truncate cursor-pointer"
                                                id="country_code_select">
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country['dial_code'] }}"
                                                        data-flag="{{ strtolower($country['code']) }}"
                                                        class="bg-secondary-dark"
                                                        {{ $country['code'] == 'US' ? 'selected' : '' }}>
                                                        {{ $country['code'] }} ({{ $country['dial_code'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                                id="selected_flag_container">
                                                <img loading="lazy" decoding="async" src="{{ asset('assets/flags/us.svg') }}"
                                                    class="w-5 h-auto rounded-sm object-cover" id="selected_flag">
                                            </div>
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-white">
                                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <input type="tel" name="phone"
                                            class="w-2/3 bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-colors"
                                            placeholder="1234567890"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Document Upload -->
                    <div class="kyc-step hidden" data-step="2">
                        <h3 class="text-lg font-bold text-white mb-6">{{ __('Document Upload') }}</h3>

                        <div class="mb-6">
                            <label
                                class="text-xs font-bold text-text-secondary uppercase tracking-wider block mb-2">{{ __('Document Type') }}</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="document_type" value="passport" class="peer sr-only"
                                        checked>
                                    <div
                                        class="px-4 py-3 rounded-lg border border-white/10 bg-white/5 text-text-secondary peer-checked:bg-accent-primary peer-checked:text-white peer-checked:border-accent-primary transition-all text-center text-sm font-bold">
                                        {{ __('Passport') }}
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="document_type" value="id_card" class="peer sr-only">
                                    <div
                                        class="px-4 py-3 rounded-lg border border-white/10 bg-white/5 text-text-secondary peer-checked:bg-accent-primary peer-checked:text-white peer-checked:border-accent-primary transition-all text-center text-sm font-bold">
                                        {{ __('National ID') }}
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="document_type" value="drivers_license"
                                        class="peer sr-only">
                                    <div
                                        class="px-4 py-3 rounded-lg border border-white/10 bg-white/5 text-text-secondary peer-checked:bg-accent-primary peer-checked:text-white peer-checked:border-accent-primary transition-all text-center text-sm font-bold">
                                        {{ __('Driver\'s License') }}
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <span
                                    class="text-xs font-bold text-text-secondary uppercase tracking-wider block">{{ __('Front Side') }}</span>
                                <div class="relative group">
                                    <input type="file" name="doc_front" id="doc_front"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        accept="image/*">
                                    <div class="h-40 rounded-xl border-2 border-dashed border-white/10 bg-white/5 flex flex-col items-center justify-center transition-colors group-hover:border-accent-primary/50 group-hover:bg-white/10"
                                        id="preview_doc_front_container">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-text-secondary mb-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <span
                                            class="text-xs text-text-secondary font-medium">{{ __('Click to upload') }}</span>
                                    </div>
                                    <img loading="lazy" decoding="async" id="preview_doc_front"
                                        class="absolute inset-0 w-full h-full object-cover rounded-xl hidden pointer-events-none">
                                </div>
                            </div>
                            <div class="space-y-2" id="back_side_container">
                                <span
                                    class="text-xs font-bold text-text-secondary uppercase tracking-wider block">{{ __('Back Side') }}</span>
                                <div class="relative group">
                                    <input type="file" name="doc_back" id="doc_back"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        accept="image/*">
                                    <div class="h-40 rounded-xl border-2 border-dashed border-white/10 bg-white/5 flex flex-col items-center justify-center transition-colors group-hover:border-accent-primary/50 group-hover:bg-white/10"
                                        id="preview_doc_back_container">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-text-secondary mb-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <span
                                            class="text-xs text-text-secondary font-medium">{{ __('Click to upload') }}</span>
                                    </div>
                                    <img loading="lazy" decoding="async" id="preview_doc_back"
                                        class="absolute inset-0 w-full h-full object-cover rounded-xl hidden pointer-events-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Selfie Verification -->
                    <div class="kyc-step hidden" data-step="3">
                        <h3 class="text-lg font-bold text-white mb-2">{{ __('Selfie Verification') }}</h3>
                        <p class="text-sm text-text-secondary mb-6">
                            {{ __('Please take a selfie holding your ID document ensuring your face and details are clearly visible.') }}
                        </p>

                        <div class="flex flex-col items-center justify-center">
                            <!-- Hidden File Input for Form Submission Fallback -->
                            <input type="file" name="selfie" id="selfie_input" class="hidden" accept="image/*">

                            <div class="relative group w-full max-w-sm aspect-[3/4] rounded-2xl border-2 border-dashed border-white/10 bg-black/20 overflow-hidden flex flex-col items-center justify-center"
                                id="camera_container">

                                <!-- Placeholder / Initial State -->
                                <div id="camera_placeholder" class="text-center p-6 transition-all">
                                    <div
                                        class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-white font-bold mb-1">{{ __('Take a Selfie') }}</h4>
                                    <button type="button" onclick="startCamera()"
                                        class="mt-4 px-6 py-2 bg-accent-primary hover:bg-accent-primary-hover text-white rounded-full font-bold text-sm transition-colors cursor-pointer relative z-20">
                                        {{ __('Launch Camera') }}
                                    </button>
                                </div>

                                <!-- Video Element (Live Stream) -->
                                <video id="camera_video" class="absolute inset-0 w-full h-full object-cover hidden"
                                    autoplay playsinline muted></video>

                                <!-- Canvas Element (Capture) -->
                                <canvas id="camera_canvas" class="hidden"></canvas>

                                <!-- Captured Image Preview -->
                                <img loading="lazy" decoding="async" id="selfie_preview"
                                    class="absolute inset-0 w-full h-full object-cover hidden pointer-events-none">

                                <!-- Camera UI Overlay (Capture Button) -->
                                <div id="camera_ui"
                                    class="absolute bottom-4 left-0 right-0 flex justify-center hidden z-30">
                                    <button type="button" onclick="capturePhoto()"
                                        class="w-16 h-16 rounded-full border-4 border-white bg-white/20 hover:bg-white/40 transition-all flex items-center justify-center cursor-pointer">
                                        <div class="w-12 h-12 bg-white rounded-full"></div>
                                    </button>
                                </div>

                                <!-- Retake UI Overlay -->
                                <div id="retake_ui"
                                    class="absolute bottom-4 left-0 right-0 flex justify-center hidden z-30">
                                    <button type="button" onclick="retakePhoto()"
                                        class="px-6 py-2 bg-black/60 hover:bg-black/80 backdrop-blur-sm text-white rounded-full font-bold text-sm border border-white/20 transition-colors cursor-pointer">
                                        {{ __('Retake Photo') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Address Verification -->
                    <div class="kyc-step hidden" data-step="4">
                        <h3 class="text-lg font-bold text-white mb-6">{{ __('Address Verification') }}</h3>

                        <div class="space-y-4 mb-8">
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold text-text-secondary uppercase tracking-wider">{{ __('Address Line 1') }}</label>
                                <input type="text" name="address_line_1"
                                    class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-colors"
                                    placeholder="123 Main St">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label
                                        class="text-xs font-bold text-text-secondary uppercase tracking-wider">{{ __('City') }}</label>
                                    <input type="text" name="city"
                                        class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-colors"
                                        placeholder="New York">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-xs font-bold text-text-secondary uppercase tracking-wider">{{ __('Postcode / ZIP') }}</label>
                                    <input type="text" name="zip"
                                        class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-colors"
                                        placeholder="10001">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold text-text-secondary uppercase tracking-wider">{{ __('Country') }}</label>
                                <div class="relative">
                                    <select name="country" id="address_country"
                                        class="w-full bg-white/5 border border-white/10 rounded-lg pl-12 pr-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-colors appearance-none cursor-pointer"
                                        onchange="updateAddressFlag(this)">
                                        <option value="" class="bg-secondary-dark" data-flag="">
                                            {{ __('Select Country') }}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country['code'] }}"
                                                data-flag="{{ strtolower($country['code']) }}" class="bg-secondary-dark">
                                                {{ $country['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <img loading="lazy" decoding="async" src="{{ asset('assets/flags/us.svg') }}"
                                            class="w-6 h-auto rounded-sm object-cover hidden" id="address_flag_img">
                                        <div id="address_flag_placeholder" class="w-6 h-4 bg-white/10 rounded-sm"></div>
                                    </div>
                                    <div
                                        class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <span
                                class="text-xs font-bold text-text-secondary uppercase tracking-wider block">{{ __('Proof of Address') }}</span>
                            <p class="text-[10px] text-text-secondary mb-2">
                                {{ __('Utility bill, bank statement, or tax document dated within the last 3 months.') }}
                            </p>
                            <div class="relative group">
                                <input type="file" name="proof_address" id="proof_address"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    accept="image/*,.pdf">
                                <div class="h-24 rounded-xl border-2 border-dashed border-white/10 bg-white/5 flex items-center justify-center gap-3 transition-colors group-hover:border-accent-primary/50 group-hover:bg-white/10"
                                    id="preview_proof_address_container">
                                    <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-text-secondary"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <span
                                            class="block text-sm font-bold text-white">{{ __('Upload Document') }}</span>
                                        <span
                                            class="block text-xs text-text-secondary">{{ __('PDF, JPG, or PNG (Max 5MB)') }}</span>
                                    </div>
                                </div>
                                <div id="preview_proof_address_name"
                                    class="absolute inset-0 bg-secondary-dark/90 rounded-xl flex items-center justify-center hidden pointer-events-none border border-accent-primary/50">
                                    <span class="text-accent-primary font-bold text-sm flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                            <polyline points="22 4 12 14.01 9 11.01" />
                                        </svg>
                                        <span class="filename truncate max-w-[200px]"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: AML Screening -->
                    <div class="kyc-step hidden" data-step="5">
                        <h3 class="text-lg font-bold text-white mb-6">{{ __('AML Screening') }}</h3>

                        <div class="bg-white/5 rounded-xl p-6 border border-white/10 mb-6">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-accent-primary/20 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-accent-primary"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white text-sm mb-1">{{ __('Compliance Check') }}</h4>
                                    <p class="text-text-secondary text-xs leading-relaxed">
                                        {{ __('By proceeding, you agree to our automated anti-money laundering (AML) and counter-terrorist financing (CTF) screening checks. Your data will be processed securely.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <input type="checkbox" id="aml_consent" name="aml_consent" class="hidden"
                                onchange="toggleAmlCheckbox(this)">
                            <label for="aml_consent" id="aml_label"
                                class="flex items-start gap-4 p-5 rounded-xl bg-white/5 border border-white/10 cursor-pointer transition-all hover:bg-white/10 group">
                                <div id="aml_checkbox_box"
                                    class="w-6 h-6 rounded-md border-2 border-white/20 bg-black/20 flex items-center justify-center transition-all shrink-0 mt-0.5 relative text-transparent">
                                    <svg id="aml_checkmark" class="w-4 h-4 opacity-0 transition-opacity" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span id="aml_title"
                                        class="block text-sm font-bold text-white group-hover:text-accent-primary transition-colors">{{ __('I Consent to AML Checks') }}</span>
                                    <span
                                        class="block text-xs text-text-secondary mt-1">{{ __('I confirm that the information provided is accurate and I agree to the screening process.') }}</span>
                                </div>
                            </label>
                        </div>

                        <div id="aml-loading" class="hidden mt-8 text-center pb-4">
                            <div
                                class="w-12 h-12 border-4 border-white/10 border-t-accent-primary rounded-full animate-spin mx-auto mb-4">
                            </div>
                            <p class="text-sm font-bold text-white animate-pulse">{{ __('Running security checks...') }}
                            </p>
                            <p class="text-xs text-text-secondary mt-1">{{ __('This usually takes less than a minute.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Step 6: Review and Submit -->
                    <div class="kyc-step hidden" data-step="6">
                        <h3 class="text-lg font-bold text-white mb-6">{{ __('Review and Submit') }}</h3>

                        <div class="space-y-4">
                            <!-- Personal Info Summary -->
                            <div class="bg-white/5 rounded-xl p-5 border border-white/10">
                                <h4
                                    class="text-white font-bold mb-4 flex items-center gap-2 text-sm uppercase tracking-wider text-text-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <h4
                                        class="text-white font-bold mb-4 flex items-center gap-2 text-sm uppercase tracking-wider text-text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        {{ __('Personal Information') }}
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span
                                                class="text-xs text-text-secondary uppercase tracking-wider block mb-1">{{ __('Full Name') }}</span>
                                            <span class="font-medium text-white" id="review_fullname">--</span>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs text-text-secondary uppercase tracking-wider block mb-1">{{ __('Date of Birth') }}</span>
                                            <span class="font-medium text-white" id="review_dob">--</span>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs text-text-secondary uppercase tracking-wider block mb-1">{{ __('Phone Number') }}</span>
                                            <span class="font-medium text-white" id="review_phone">--</span>
                                        </div>
                                    </div>
                            </div>

                            <!-- Address Summary -->
                            <div class="bg-white/5 rounded-xl p-5 border border-white/10">
                                <h4
                                    class="text-white font-bold mb-4 flex items-center gap-2 text-sm uppercase tracking-wider text-text-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    {{ __('Residential Address') }}
                                </h4>
                                <p class="text-white text-sm leading-relaxed" id="review_address">--</p>
                            </div>

                            <!-- Documents Summary -->
                            <div class="bg-white/5 rounded-xl p-5 border border-white/10">
                                <h4
                                    class="text-white font-bold mb-4 flex items-center gap-2 text-sm uppercase tracking-wider text-text-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    {{ __('Documents') }}
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-black/20 rounded-lg p-3 flex items-center gap-3 border border-white/5">
                                        <div
                                            class="w-8 h-8 rounded bg-accent-primary/20 text-accent-primary flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2"
                                                    ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6">
                                                </line>
                                                <line x1="8" y1="2" x2="8" y2="6">
                                                </line>
                                                <line x1="3" y1="10" x2="21" y2="10">
                                                </line>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="text-xs text-text-secondary block">{{ __('ID Type') }}</span>
                                            <span class="text-white font-bold text-sm capitalize"
                                                id="review_doc_type_label">--</span>
                                        </div>
                                    </div>
                                    <div class="bg-black/20 rounded-lg p-3 flex items-center gap-3 border border-white/5">
                                        <div
                                            class="w-8 h-8 rounded bg-green-500/20 text-green-500 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        </div>
                                        <div>
                                            <span
                                                class="text-xs text-text-secondary block">{{ __('Verification') }}</span>
                                            <span class="text-white font-bold text-sm">{{ __('Ready to Upload') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-2 text-green-400 text-xs mt-4 justify-center bg-green-500/5 p-3 rounded-lg border border-green-500/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span class="font-bold">{{ __('AML Screening Passed') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Success State (Hidden initially) -->
                    <div class="kyc-step hidden text-center py-10" data-step="success">
                        <div class="w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-500" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">{{ __('Verification Pending') }}</h3>
                        <p class="text-text-secondary max-w-md mx-auto mb-8">
                            {{ __('Your documents have been successfully submitted and are currently under review. Using our automated systems, this process is usually instantaneous, but in some cases may take up to 24 hours.') }}
                        </p>
                        <a href="{{ route('user.dashboard') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="9" x="3" y="3" rx="1" />
                                <rect width="7" height="5" x="14" y="3" rx="1" />
                                <rect width="7" height="9" x="14" y="12" rx="1" />
                                <rect width="7" height="5" x="3" y="16" rx="1" />
                            </svg>
                            {{ __('Return to Dashboard') }}
                        </a>
                    </div>

                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-4 bg-black/20 border-t border-white/5 flex justify-between items-center"
                    id="form-actions">
                    <button type="button" id="btn-back"
                        class="hidden px-6 py-2.5 rounded-lg border border-white/10 text-text-secondary font-medium hover:text-white hover:bg-white/5 transition-all text-sm cursor-pointer">
                        {{ __('Back') }}
                    </button>
                    <div class="ml-auto">
                        <button type="button" id="btn-next"
                            class="px-8 py-2.5 rounded-lg bg-accent-primary hover:bg-accent-primary-hover text-white font-bold transition-all shadow-lg shadow-accent-primary/20 text-sm flex items-center gap-2 cursor-pointer">
                            <span>{{ __('Next Step') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                        <button type="submit" id="btn-submit"
                            class="hidden px-8 py-2.5 rounded-lg bg-green-600 hover:bg-green-500 text-white font-bold transition-all shadow-lg shadow-green-600/20 text-sm flex items-center gap-2 cursor-pointer">
                            <span>{{ __('Submit Verification') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if (isset($last_kyc) && $last_kyc)
            <div class="bg-secondary-dark rounded-xl border border-white/10 p-8 relative overflow-hidden">
                <!-- Decorative Background -->
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-accent-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none">
                </div>

                <div class="max-w-xl mx-auto">
                    @php
                        $statusColor = 'bg-yellow-500';
                        $statusTextColor = 'text-yellow-500';
                        $statusText = __('In Progress');

                        if ($last_kyc->status == 'approved') {
                            $statusColor = 'bg-green-500';
                            $statusTextColor = 'text-green-500';
                            $statusText = __('Verified');
                        } elseif ($last_kyc->status == 'rejected') {
                            $statusColor = 'bg-red-500';
                            $statusTextColor = 'text-red-500';
                            $statusText = __('Action Required');
                        }
                    @endphp

                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-white/5">
                        <div class="text-left">
                            <h2 class="text-xl font-bold text-white">{{ __('Verification Tracker') }}</h2>
                            <p class="text-text-secondary text-xs mt-1">
                                {{ __('Monitor the progress of your identity verification') }}</p>
                        </div>
                        <div
                            class="flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 border border-white/10 self-start sm:self-auto">
                            <span class="relative flex h-2.5 w-2.5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $statusColor }}"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $statusColor }}"></span>
                            </span>
                            <span
                                class="text-xs font-bold {{ $statusTextColor }} uppercase tracking-widest">{{ $statusText }}</span>
                        </div>
                    </div>

                    <div class="space-y-0">
                        <!-- Step 1: Submission Received -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-8 h-8 rounded-full bg-green-500/20 text-green-500 flex items-center justify-center border border-green-500/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                                <div class="w-0.5 h-full bg-green-500/30 my-2"></div>
                            </div>
                            <div class="pb-8 w-full">
                                <h3 class="text-white font-bold text-sm">{{ __('Submission Received') }}</h3>
                                <p class="text-text-secondary text-xs mt-1">{{ __('We have received your documents on') }}
                                    {{ $last_kyc->created_at->format('d M Y') }}</p>

                                <div
                                    class="mt-4 bg-white/5 rounded-lg p-4 border border-white/5 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                    <div>
                                        <div class="text-text-secondary mb-1">{{ __('Full Name') }}</div>
                                        <div class="text-white font-medium">{{ $last_kyc->user->first_name }}
                                            {{ $last_kyc->user->last_name }}</div>
                                    </div>
                                    <div>
                                        <div class="text-text-secondary mb-1">{{ __('Date of Birth') }}</div>
                                        <div class="text-white font-medium">
                                            {{ \Carbon\Carbon::parse($last_kyc->date_of_birth)->format('d M Y') }}</div>
                                    </div>
                                    <div>
                                        <div class="text-text-secondary mb-1">{{ __('Phone Number') }}</div>
                                        <div class="text-white font-medium">+{{ $last_kyc->phone_code }}
                                            {{ $last_kyc->phone }}</div>
                                    </div>
                                    <div>
                                        <div class="text-text-secondary mb-1">{{ __('Document Type') }}</div>
                                        <div class="text-white font-medium capitalize">
                                            {{ __(str_replace('_', ' ', $last_kyc->document_type)) }}</div>
                                    </div>
                                    <div class="md:col-span-2">
                                        <div class="text-text-secondary mb-1">{{ __('Residential Address') }}</div>
                                        <div class="text-white font-medium">
                                            {{ $last_kyc->address_line_1 }}, {{ $last_kyc->city }},
                                            {{ $last_kyc->zip }}, {{ $last_kyc->country }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Under Review -->
                        @php
                            $isReviewComplete = in_array($last_kyc->status, ['approved', 'rejected']);
                            $reviewColor = $isReviewComplete ? 'green' : 'accent-primary';
                            $reviewBg = $isReviewComplete ? 'bg-green-500/20' : 'bg-accent-primary/20';
                            $reviewBorder = $isReviewComplete ? 'border-green-500/50' : 'border-accent-primary/50';
                            $reviewText = $isReviewComplete ? 'text-green-500' : 'text-accent-primary';
                        @endphp
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-8 h-8 rounded-full {{ $reviewBg }} {{ $reviewText }} flex items-center justify-center border {{ $reviewBorder }}">
                                    @if ($isReviewComplete)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 animate-spin"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M12 6v6l4 2"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div
                                    class="w-0.5 h-full {{ $isReviewComplete ? 'bg-green-500/30' : 'bg-white/10' }} my-2">
                                </div>
                            </div>
                            <div class="pb-8 w-full">
                                <h3
                                    class="{{ $isReviewComplete ? 'text-white' : 'text-accent-primary' }} font-bold text-sm">
                                    {{ __('Compliance Review') }}</h3>
                                <p class="text-text-secondary text-xs mt-1">
                                    @if ($isReviewComplete)
                                        {{ __('Review completed successfully.') }}
                                    @else
                                        {{ __('Our compliance team is currently reviewing your documents.') }}
                                    @endif
                                </p>

                                <div class="mt-4 space-y-2">
                                    @foreach (['Identity Document Validity', 'Proof of Address Verification', 'Facial Biometric Match', 'Sanctions List Screening', 'Risk Assessment Score', 'AML/Background Checks'] as $check)
                                        <div
                                            class="flex items-center gap-3 text-xs {{ $isReviewComplete ? 'text-white' : 'text-text-secondary' }}">
                                            @if ($isReviewComplete)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            @else
                                                <div class="w-4 h-4 rounded-full border border-white/20"></div>
                                            @endif
                                            <span>{{ __($check) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Final Decision -->
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                @if ($last_kyc->status == 'approved')
                                    <div
                                        class="w-8 h-8 rounded-full bg-green-500/20 text-green-500 flex items-center justify-center border border-green-500/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                @elseif($last_kyc->status == 'rejected')
                                    <div
                                        class="w-8 h-8 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center border border-red-500/50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </div>
                                @else
                                    <div
                                        class="w-8 h-8 rounded-full bg-white/5 text-white/20 flex items-center justify-center border border-white/10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="pb-2">
                                <h3
                                    class="{{ $last_kyc->status == 'pending' ? 'text-text-secondary' : 'text-white' }} font-bold text-sm">
                                    {{ __('Final Decision') }}
                                </h3>

                                @if ($last_kyc->status == 'approved')
                                    <div class="mt-3">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                            {{ __('Approved') }}
                                        </span>
                                        <p class="text-text-secondary text-xs mt-3 leading-relaxed">
                                            {{ __('Congratulations! Your identity has been verified. You now have uncapped access to all investment features.') }}
                                        </p>
                                        <a href="{{ route('user.dashboard') }}"
                                            class="mt-4 inline-flex items-center gap-2 text-xs font-bold text-black bg-accent-primary hover:bg-white transition-colors px-4 py-2 rounded-lg">
                                            {{ __('Go to Dashboard') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14"></path>
                                                <path d="m12 5 7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @elseif($last_kyc->status == 'rejected')
                                    <div class="mt-3">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                            {{ __('Application Rejected') }}
                                        </span>

                                        @if ($last_kyc->rejection_reason)
                                            <div class="mt-3 bg-red-500/5 border border-red-500/10 rounded-lg p-3">
                                                <span
                                                    class="text-[10px] uppercase tracking-wider text-red-500/60 font-bold block mb-1">{{ __('Reason for Rejection') }}</span>
                                                <p class="text-red-300 text-xs">{{ $last_kyc->rejection_reason }}</p>
                                            </div>
                                        @endif

                                        <div class="mt-4">
                                            <button type="button"
                                                onclick="document.getElementById('kyc-submission-form').classList.remove('hidden'); this.closest('.bg-secondary-dark').classList.add('hidden');"
                                                class="inline-flex items-center gap-2 text-xs font-bold text-white bg-white/10 hover:bg-white/20 transition-colors px-4 py-2 rounded-lg border border-white/10 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                                    <path d="M3 3v5h5"></path>
                                                    <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16">
                                                    </path>
                                                    <path d="M16 16h5v5"></path>
                                                </svg>
                                                {{ __('Try Again') }}
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-3">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/5 text-text-secondary border border-white/10">
                                            <span class="w-1.5 h-1.5 rounded-full bg-text-secondary animate-pulse"></span>
                                            {{ __('Decision Pending') }}
                                        </span>
                                        <p class="text-text-secondary text-xs mt-3 leading-relaxed">
                                            {{ __('Our team is currently assessing your application. You will be notified via email once a decision is made.') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            let currentStep = 1;
            const totalSteps = 6;
            const steps = {
                1: "{{ __('Personal Details') }}",
                2: "{{ __('Document Upload') }}",
                3: "{{ __('Selfie Verification') }}",
                4: "{{ __('Address Verification') }}",
                5: "{{ __('AML Screening') }}",
                6: "{{ __('Review and Submit') }}"
            };

            // Update UI based on current step
            function updateStepUI() {
                // Update Progress Bar
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                $('#progress-bar').css('width', `${Math.max(5, progressPercentage)}%`);

                // Update Labels
                $('#step-indicator').text(
                    `{{ __('Step') }} ${currentStep} {{ __('of') }} ${totalSteps}`);
                $('#step-title').text(steps[currentStep]);

                // Show/Hide Steps
                $('.kyc-step').addClass('hidden');
                $(`.kyc-step[data-step="${currentStep}"]`).removeClass('hidden');

                // Button Logic
                if (currentStep === 1) {
                    $('#btn-back').addClass('hidden');
                } else {
                    $('#btn-back').removeClass('hidden');
                }

                if (currentStep === totalSteps) {
                    $('#btn-next').addClass('hidden');
                    $('#btn-submit').removeClass('hidden');
                } else {
                    $('#btn-next').removeClass('hidden');
                    $('#btn-submit').addClass('hidden');
                }

                // Hide actions on success
                if (currentStep === 'success') {
                    $('#form-actions').addClass('hidden');
                    $('#progress-bar').css('width', '100%');
                    $('#step-title').text("{{ __('Completed') }}");
                }
            }

            // Image Previews
            function handleImagePreview(inputSelector, imgSelector, containerSelector) {
                $(inputSelector).change(function() {
                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $(imgSelector).attr('src', e.target.result).removeClass('hidden');
                            if (containerSelector) {
                                $(containerSelector).addClass(
                                    'opacity-0'
                                ); // Hide the placeholder content visually but keep layout if needed, or simple hide
                            }
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

            handleImagePreview('#doc_front', '#preview_doc_front', '#preview_doc_front_container');
            handleImagePreview('#doc_back', '#preview_doc_back', '#preview_doc_back_container');
            handleImagePreview('#selfie', '#preview_selfie', '#selfie_placeholder');

            // File Upload logic for Proof of Address (shows filename instead of image preview if not image)
            $('#proof_address').change(function() {
                if (this.files && this.files[0]) {
                    $('#preview_proof_address_name').removeClass('hidden').find('.filename').text(this
                        .files[0].name);
                }
            });

            // Country Code Flag Updater
            $('#country_code_select').change(function() {
                var selectedOption = $(this).find('option:selected');
                var flagCode = selectedOption.data('flag');
                $('#selected_flag').attr('src', `{{ asset('assets/flags/') }}/${flagCode}.svg`);
            });

            // Date of Birth Validator
            function updateDays() {
                var month = $('#dob_month').val();
                var year = $('#dob_year').val();
                var daySelect = $('#dob_day');
                var currentDay = daySelect.val();

                if (month && year) {
                    // Calculate days in month (handling leap years)
                    var daysInMonth = new Date(year, month, 0).getDate();

                    // Specific check for February 29th and Leap Years (Standard logic might miss non-leap century years if not using Date object correctly, but `new Date(year, month, 0)` is robust)
                    // new Date(2023, 2, 0).getDate() -> 28 (Feb 2023)
                    // new Date(2024, 2, 0).getDate() -> 29 (Feb 2024)

                    // Enable/Disable options
                    daySelect.find('option').each(function() {
                        var dayVal = parseInt($(this).val());
                        if (dayVal > daysInMonth) {
                            $(this).prop('disabled', true).addClass('hidden');
                        } else {
                            $(this).prop('disabled', false).removeClass('hidden');
                        }
                    });

                    // If currently selected day is invalid, reset to empty or max day
                    if (currentDay > daysInMonth) {
                        daySelect.val('');
                    }
                }
            }

            $('#dob_month, #dob_year').change(updateDays);

            // Address Country Flag Updater
            window.updateAddressFlag = function(select) {
                var selectedOption = $(select).find('option:selected');
                var flagCode = selectedOption.data('flag');

                if (flagCode) {
                    $('#address_flag_img').attr('src', `{{ asset('assets/flags/') }}/${flagCode}.svg`)
                        .removeClass('hidden');
                    $('#address_flag_placeholder').addClass('hidden');
                } else {
                    $('#address_flag_img').addClass('hidden');
                    $('#address_flag_placeholder').removeClass('hidden');
                }
            };


            // Document Type Handler (Passport only needs front side)
            $('input[name="document_type"]').change(function() {
                if ($(this).val() === 'passport') {
                    $('#back_side_container').addClass('hidden');
                } else {
                    $('#back_side_container').removeClass('hidden');
                }
            });
            // Initialize visibility state
            $('input[name="document_type"]:checked').trigger('change');

            // Camera Logic
            let stream = null;

            window.startCamera = async function() {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: 'user'
                        },
                        audio: false
                    });
                    const video = document.getElementById('camera_video');
                    video.srcObject = stream;
                    video.classList.remove('hidden');
                    $('#camera_placeholder').addClass('hidden');
                    $('#camera_ui').removeClass('hidden');
                } catch (err) {
                    console.error("Error accessing camera: ", err);
                    window.toastNotification(
                        "{{ __('Could not access camera. Please allow permissions.') }}", 'error');
                }
            };

            window.capturePhoto = async function() {
                const video = document.getElementById('camera_video');
                const canvas = document.getElementById('camera_canvas');
                const context = canvas.getContext('2d');

                // Set canvas size to match video stream
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // Draw frame
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Stop Stream
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                // Show Preview
                const dataURL = canvas.toDataURL('image/png');
                $('#selfie_preview').attr('src', dataURL).removeClass('hidden');

                // Hide Video & Show Retake
                $(video).addClass('hidden');
                $('#camera_ui').addClass('hidden');
                $('#retake_ui').removeClass('hidden');

                // Convert to File and set to input synchronously-ish
                try {
                    const res = await fetch(dataURL);
                    const blob = await res.blob();
                    const file = new File([blob], "selfie.png", {
                        type: "image/png"
                    });
                    const container = new DataTransfer();
                    container.items.add(file);
                    document.getElementById('selfie_input').files = container.files;
                    console.log("Selfie file assigned:", document.getElementById('selfie_input').files
                        .length);
                } catch (e) {
                    console.error("Error creating selfie file:", e);
                    window.toastNotification("Error saving selfie. Please try again.", 'error');
                }
            };

            window.retakePhoto = function() {
                $('#selfie_preview').addClass('hidden');
                $('#retake_ui').addClass('hidden');
                window.startCamera();
            };

            // AML Checkbox Toggler (Manual JS for reliability)
            window.toggleAmlCheckbox = function(checkbox) {

                const label = $('#aml_label');
                const box = $('#aml_checkbox_box');
                const checkmark = $('#aml_checkmark');
                const title = $('#aml_title');

                if (checkbox.checked) {
                    // Active State
                    label.addClass('border-accent-primary bg-accent-primary/10');
                    label.removeClass('border-white/10 bg-white/5');

                    box.addClass('bg-accent-primary border-accent-primary text-white');
                    box.removeClass('bg-black/20 border-white/20 text-transparent');

                    checkmark.removeClass('opacity-0');
                } else {
                    // Inactive State
                    label.removeClass('border-accent-primary bg-accent-primary/10');
                    label.addClass('border-white/10 bg-white/5');
                }
            };
            // Step Validation Logic
            function validateStep(step) {
                let isValid = true;
                let errorMessage = '';

                switch (step) {
                    case 1: // Personal Details
                        if (!$('input[name="first_name"]').val()) {
                            isValid = false;
                            errorMessage = "{{ __('Please enter your first name.') }}";
                            break;
                        }
                        if (!$('input[name="last_name"]').val()) {
                            isValid = false;
                            errorMessage = "{{ __('Please enter your last name.') }}";
                            break;
                        }
                        if (!$('select[name="dob_day"]').val() || !$('select[name="dob_month"]').val() || !$(
                                'select[name="dob_year"]').val()) {
                            isValid = false;
                            errorMessage = "{{ __('Please enter your full date of birth.') }}";
                            break;
                        }
                        if (!$('input[name="phone"]').val()) {
                            isValid = false;
                            errorMessage = "{{ __('Please enter your phone number.') }}";
                            break;
                        }
                        break;

                    case 2: // Document Upload
                        if (!$('#doc_front').val()) {
                            isValid = false;
                            errorMessage = "{{ __('Please upload the front side of your document.') }}";
                            break;
                        }
                        // Check back side if not passport
                        if ($('input[name="document_type"]:checked').val() !== 'passport') {
                            if (!$('#doc_back').val()) {
                                isValid = false;
                                errorMessage = "{{ __('Please upload the back side of your document.') }}";
                                break;
                            }
                        }
                        break;

                    case 3: // Selfie
                        if (!$('#selfie_input').get(0).files.length) {
                            isValid = false;
                            errorMessage = "{{ __('Please take a selfie.') }}";
                            break;
                        }
                        break;

                    case 4: // Address
                        if (!$('input[name="address_line_1"]').val()) {
                            isValid = false;
                            errorMessage = "{{ __('Please enter your address.') }}";
                            break;
                        }
                        if (!$('input[name="city"]').val()) {
                            isValid = false;
                            errorMessage = "{{ __('Please enter your city.') }}";
                            break;
                        }
                        if (!$('input[name="zip"]').val()) {
                            isValid = false;
                            errorMessage = "{{ __('Please enter your zip code.') }}";
                            break;
                        }
                        if (!$('select[name="country"]').val()) {
                            isValid = false;
                            errorMessage = "{{ __('Please select your country.') }}";
                            break;
                        }
                        if (!$('#proof_address').val()) {
                            isValid = false;
                            errorMessage = "{{ __('Please upload proof of address.') }}";
                            break;
                        }
                        break;
                }

                if (!isValid) {
                    window.toastNotification(errorMessage, 'error');
                }
                return isValid;
            }

            // Navigation Handlers
            $('#btn-next').click(function() {
                // Validate current step before proceeding
                if (!validateStep(currentStep)) {
                    return;
                }

                // If step is valid, proceed with existing logic
                if (currentStep === 5) {
                    // AML Simulation
                    if (!$('#aml_consent').is(':checked')) {
                        window.toastNotification(
                            "{{ __('Please consent to the AML checks to proceed.') }}", 'error');
                        return;
                    }

                    // Simulate Loading
                    $('#btn-next').prop('disabled', true).addClass('opacity-50');
                    $('#aml-loading').removeClass('hidden');

                    setTimeout(function() {
                        $('#btn-next').prop('disabled', false).removeClass('opacity-50');
                        $('#aml-loading').addClass('hidden');

                        // Populate Review Data
                        // Personal
                        $('#review_fullname').text($('input[name="first_name"]').val() + ' ' + $(
                            'input[name="last_name"]').val());
                        $('#review_dob').text(
                            `${$('#dob_day').val()} ${$('#dob_month option:selected').text()} ${$('#dob_year').val()}`
                        );
                        $('#review_phone').text(
                            `+${$('#country_code_select').val()} ${$('input[name="phone"]').val()}`
                        );

                        // Address
                        const address =
                            `${$('input[name="address_line_1"]').val()}, ${$('input[name="city"]').val()}, ${$('input[name="zip"]').val()}, ${$('select[name="country"] option:selected').text()}`;
                        $('#review_address').text(address);

                        // Docs
                        var docType = $('input[name="document_type"]:checked').val();
                        $('#review_doc_type_label').text(docType.replace(/_/g, ' '));

                        currentStep++;
                        updateStepUI();
                    }, 2000);
                } else {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        updateStepUI();
                    }
                }
            });

            $('#btn-back').click(function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateStepUI();
                }
            });

            // Submission Handler
            // Submission Handler
            $('#kyc-form').submit(function(e) {
                e.preventDefault();

                $('#btn-submit').prop('disabled', true).addClass('opacity-50 cursor-not-allowed').html(
                    '<span class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full mr-2"></span> {{ __('Submitting...') }}'
                );

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('user.kyc.submit') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('.kyc-step').addClass('hidden');
                        $(`.kyc-step[data-step="success"]`).removeClass('hidden');
                        currentStep = 'success';
                        updateStepUI();
                        window.toastNotification(response.message, 'success');
                    },
                    error: function(xhr) {
                        $('#btn-submit').prop('disabled', false).removeClass(
                            'opacity-50 cursor-not-allowed').html(
                            `<span>{{ __('Submit Verification') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>`
                        );

                        var errors = xhr.responseJSON.errors;
                        var errorMessage =
                            "{{ __('Something went wrong. Please try again.') }}";

                        if (errors) {
                            errorMessage = Object.values(errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        window.toastNotification(errorMessage, 'error');
                    }
                });
            });

        });
    </script>
@endsection
