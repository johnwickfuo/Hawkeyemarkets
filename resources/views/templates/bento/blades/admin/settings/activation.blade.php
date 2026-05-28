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
                    <div class="flex flex-col gap-1">
                        <h2 class="text-3xl font-light text-white tracking-tight leading-none">
                            {{ __('Activation Settings') }}
                        </h2>
                        <p class="text-slate-500 text-xs font-medium tracking-wide uppercase">
                            {{ __('Manage product activation and market data API keys') }}
                        </p>
                    </div>
                </div>

                <form id="settings-form" action="{{ route('admin.settings.activation.update') }}" method="POST"
                    class="space-y-12 pb-24">
                    @csrf

                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('License & API Keys') }}</h3>
                        </div>

                        <div class="flex flex-col gap-8">

                            {{-- License Status Display --}}
                            @if (!$license_information)
                                {{-- Unlicensed / Error State --}}
                                <div
                                    class="p-8 rounded-[2.5rem] bg-red-500/5 border border-red-500/10 flex flex-col items-center text-center gap-6 animate-in fade-in zoom-in duration-700">
                                    <div
                                        class="w-20 h-20 rounded-[2rem] bg-red-500/10 flex items-center justify-center text-red-500 shadow-2xl shadow-red-500/20">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <h3 class="text-xl font-black text-white uppercase tracking-tighter">
                                            {{ __('Unlicensed Software') }}</h3>
                                        <p class="text-[11px] text-slate-400 font-medium leading-relaxed max-w-xs">
                                            {{ __('Your installation is not yet activated. Please enter a valid license key below to unlock all features.') }}
                                        </p>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 px-4 py-2 rounded-full bg-red-500/20 text-red-500 text-[9px] font-black uppercase tracking-widest">
                                        <span class="relative flex h-2 w-2">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                        </span>
                                        {{ __('Action Required') }}
                                    </div>

                                    <div class="mt-4">
                                        <a href="https://lozand.com" target="_blank"
                                            class="px-8 py-3.5 bg-accent-primary text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-white hover:text-black active:scale-95 transition-all duration-300 flex items-center gap-3 shadow-xl shadow-accent-primary/20">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                            {{ __('Purchase License') }}
                                        </a>
                                    </div>
                                </div>
                            @else
                                {{-- Licensed / Active State --}}
                                <div
                                    class="p-8 rounded-[2.5rem] bg-emerald-500/5 border border-emerald-500/10 flex flex-col gap-8 animate-in fade-in slide-in-from-top-4 duration-700">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            </div>
                                            <div class="flex flex-col">
                                                <h3
                                                    class="text-lg font-black text-white leading-tight uppercase tracking-tight">
                                                    {{ __('System Activated') }}</h3>
                                                <div
                                                    class="flex items-center gap-2 text-[10px] text-emerald-500 font-black uppercase tracking-widest">
                                                    {{ __('Genuine Software Verified') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="px-4 py-2 rounded-xl bg-white/5 border border-white/5 text-[9px] font-bold text-white uppercase tracking-widest">
                                            v{{ config('site.version') }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-2">
                                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 flex flex-col gap-1">
                                            <span
                                                class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('License Holder') }}</span>
                                            <span
                                                class="text-xs font-bold text-white truncate">{{ sandBoxCredentials($license_information['name'] ?? 'N/A') }}</span>
                                            <span
                                                class="text-[10px] text-slate-500 font-medium truncate">{{ sandBoxCredentials($license_information['email'] ?? 'N/A') }}</span>
                                        </div>
                                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 flex flex-col gap-1">
                                            <span
                                                class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Registered Domain') }}</span>
                                            <span
                                                class="text-xs font-bold text-white truncate">{{ $license_information['domain'] ?? request()->getHost() }}</span>
                                        </div>
                                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 flex flex-col gap-1">
                                            <span
                                                class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('Activated On') }}</span>
                                            <span
                                                class="text-xs font-bold text-white">{{ $license_information['date'] ? date('M d, Y', strtotime($license_information['date'])) : 'N/A' }}</span>
                                        </div>

                                        <div
                                            class="p-4 rounded-2xl bg-white/5 border border-white/10 flex flex-col gap-1 col-span-full">
                                            <span
                                                class="text-[9px] font-black text-slate-500 uppercase tracking-widest">{{ __('License Key') }}</span>
                                            <span
                                                class="text-xs font-bold text-white tracking-widest">{{ sandBoxCredentials($license_information['license_key'] ?? 'N/A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            <div class="@if ($license_information) hidden @endif">



                                <div class="flex flex-col gap-3 ">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Target Domain') }}
                                    </label>
                                    <div class="relative group/domain">
                                        <input type="text" value="{{ request()->getHost() }}" readonly
                                            class="flat-input opacity-60 cursor-not-allowed bg-white/5 border-dashed"
                                            placeholder="Domain">
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="text-[10px] text-slate-500 font-medium leading-relaxed italic">
                                        {{ __('Your license will be permanently locked to this domain upon activation.') }}
                                    </div>
                                </div>
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('License Key') }}
                                </label>
                                <input type="text" name="product_key"
                                    value="{{ sandBoxCredentials(safeDecrypt(config('site.product_key'))) }}"
                                    class="flat-input" placeholder="Enter License Key">
                                <div class="text-[10px] text-slate-400 font-medium leading-relaxed">
                                    {{ __('Purchase license key from') }}
                                    <a href="https://lozand.com" target="_blank"
                                        class="text-accent-primary hover:text-accent-primary/80 transition-colors">{{ __('lozand.com') }}</a>.
                                    {{ __('This is same the license key issued when you purchased this product.') }}
                                </div>

                            </div>
                        </div>



                        {{-- Twelve Data API Setup --}}
                        <div
                            class="mb-12 p-8 rounded-[2rem] bg-indigo-500/5 border border-indigo-500/10 flex flex-col gap-8 mt-12">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <h4 class="text-base font-bold text-white tracking-wide uppercase">
                                        {{ __('Twelve Data API') }}</h4>
                                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-widest">
                                        {{ __('Free API for stocks, ETFs & forex data') }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                @php
                                    $steps = [
                                        [
                                            'icon' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1',
                                            'text' => __('Go to twelvedata.com and create a free account'),
                                        ],
                                        [
                                            'icon' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
                                            'text' => __('Copy your API key from the dashboard'),
                                        ],
                                        [
                                            'icon' => 'M13 7l5 5m0 0l-5 5m5-5H6',
                                            'text' => __('Paste it in the API Key field below and save'),
                                        ],
                                        [
                                            'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                                            'text' => __('Free tier: 800 API credits/day — sufficient for normal platform usage'),
                                        ],
                                    ];
                                @endphp

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($steps as $index => $step)
                                        <div
                                            class="flex items-start gap-3 p-4 rounded-xl bg-white/5 border border-white/5 transition-colors hover:border-white/10 group/step">
                                            <div
                                                class="flex-shrink-0 w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400 font-black text-xs group-hover/step:bg-indigo-500/20 transition-all">
                                                {{ $index + 1 }}
                                            </div>
                                            <p
                                                class="text-[11px] text-slate-400 font-medium leading-relaxed group-hover/step:text-slate-300 transition-colors">
                                                {!! $step['text'] !!}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>

                                <div
                                    class="mt-4 p-5 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative group/ip">
                                    <div
                                        class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-3xl transition-all group-hover/ip:scale-150">
                                    </div>
                                    <div class="flex flex-col gap-1 relative z-10">
                                        <span
                                            class="text-[9px] font-black text-indigo-400 uppercase tracking-widest">{{ __('Your Server IP') }}</span>
                                        <h5 class="text-xl font-black text-white tracking-widest">
                                            {{ sandBoxCredentials($server_ip) ?? 'N/A' }}</h5>
                                    </div>
                                    <div class="flex flex-col items-center md:items-end gap-3 relative z-10">
                                        <p
                                            class="text-[10px] text-slate-400 font-medium text-center md:text-right max-w-[200px]">
                                            {{ __('Your server public IP address (for reference)') }}
                                        </p>
                                        <button type="button"
                                            onclick="copyToClipboard('{{ sandBoxCredentials($server_ip) }}')"
                                            class="px-5 py-2.5 bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-white hover:text-indigo-600 transition-all flex items-center gap-2 shadow-lg shadow-indigo-500/20 active:scale-95">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                            </svg>
                                            {{ __('Copy IP') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 mt-4">
                                    <label class="text-[15px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Twelve Data API Key') }}
                                    </label>
                                    <input type="text" name="twelve_data_key"
                                        value="{{ sandBoxCredentials(env('TWELVE_DATA_API_KEY')) }}"
                                        class="flat-input" placeholder="Enter Twelve Data API Key">
                                    <div class="text-[10px] text-slate-400 font-medium leading-relaxed">
                                        {{ __('Get your free API Key from') }}
                                        <a href="https://twelvedata.com" target="_blank"
                                            class="text-accent-primary hover:text-accent-primary/80 transition-colors">twelvedata.com</a>.
                                        {{ __('Used for stocks, ETFs and forex market data. Crypto data (futures & margin) uses the free Binance public API — no key needed.') }}
                                    </div>
                                </div>
                            </div>
                        </div>





                    </div>
            </div>

            <div class="pt-0">
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

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#settings-form').on('submit', function(e) {
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

                        // reload
                        setTimeout(() => {
                            location.reload();
                        }, 1000);

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

        function copyToClipboard(text) {
            if (!text) return;
            const tempInput = document.createElement('input');
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            toastNotification('{{ __('Copied to clipboard!') }}', 'success');
        }
    </script>
@endsection
