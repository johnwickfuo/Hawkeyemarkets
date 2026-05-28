@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div
        class="bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col lg:flex-row backdrop-blur-xl min-h-[calc(100vh-160px)]">
        {{-- Settings Sidebar (Global Navigator) --}}
        <div
            class="w-full lg:w-80 bg-secondary/60 shrink-0 border-b lg:border-b-0 lg:border-r border-white/5 flex flex-col relative group">
            <div
                class="absolute inset-0 bg-gradient-to-b from-accent-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
            </div>
            <div class="relative z-10 flex-1 py-8 px-6 lg:px-8 overflow-y-auto custom-scrollbar" id="sideBarSelector">
                @include("templates.$template.blades.admin.settings.partials.sidebar")
            </div>
        </div>

        {{-- Settings Content Area --}}
        <div class="flex-1 flex flex-col pt-10 px-6 md:px-10 lg:px-12 overflow-y-auto custom-scrollbar"
            id="contentScrollContainer">
            <div class="max-w-4xl">
                <div class="flex flex-col gap-2 mb-10 border-b border-white/5 pb-8">
                    <h2 class="text-3xl font-bold text-white tracking-tight leading-none">
                        {{ __('Core Platform Settings') }}
                    </h2>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">
                        {{ __('Manage branding, regional preferences, and financial rules') }}</p>
                </div>

                <form id="core-settings-form" action="{{ route('admin.settings.core.update') }}" method="POST"
                    enctype="multipart/form-data" class="space-y-12 pb-24">
                    @csrf

                    {{-- General Site Configuration --}}
                    <section class="space-y-8">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-8 h-8 rounded-lg bg-accent-primary/10 flex items-center justify-center text-accent-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wider">{{ __('Identity & Locale') }}
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Site Name') }}</label>
                                <input type="text" name="site_name" value="{{ getSetting('name') }}" required
                                    class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Support Email') }}</label>
                                <input type="email" name="support_email" value="{{ getSetting('email') }}" required
                                    class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                            </div>

                            <div class="flex flex-col gap-3 md:col-span-2">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Application Timezone') }}</label>
                                <div class="relative">
                                    <select name="app_timezone" id="timezone-select" class="w-full">
                                        @foreach ($timezones as $tz)
                                            <option value="{{ $tz['tzCode'] }}" data-utc="{{ $tz['utc'] }}"
                                                data-name="{{ $tz['name'] }}"
                                                {{ getSetting('app_timezone', config('app.timezone', 'UTC')) == $tz['tzCode'] ? 'selected' : '' }}>
                                                {{ $tz['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-[10px] text-slate-500 font-bold px-2 italic">
                                    {{ __('Search by city, country or offset (e.g. London, -05:00)') }}</p>
                            </div>
                        </div>
                    </section>

                    {{-- Branding & Visual Assets --}}
                    <section class="space-y-8 pt-8 border-t border-white/5">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-8 h-8 rounded-lg bg-accent-primary/10 flex items-center justify-center text-accent-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wider">{{ __('Visual Identity') }}
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            {{-- Square Logo --}}
                            <div class="flex flex-col gap-4">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Logo (Square)') }}</label>
                                <div
                                    class="flex items-center gap-6 p-6 rounded-3xl bg-white/5 border border-white/10 group/item">
                                    <div
                                        class="w-20 h-20 rounded-2xl bg-secondary/80 border border-white/10 p-3 flex items-center justify-center overflow-hidden shrink-0">
                                        <img loading="lazy" decoding="async" src="{{ asset('assets/images/' . getSetting('logo_square')) }}"
                                            id="preview-logo-square"
                                            class="max-w-full max-h-full object-contain transition-transform group-hover/item:scale-110">
                                    </div>
                                    <div class="flex-1 space-y-3">
                                        <p class="text-[10px] text-slate-500 font-bold leading-tight">
                                            {{ __('Best for avatars and icons. 512x512 recommended.') }}</p>
                                        <input type="file" name="logo_square" class="hidden image-input"
                                            data-preview="#preview-logo-square">
                                        <button type="button"
                                            class="trigger-input px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-[10px] font-bold text-white uppercase tracking-widest transition-all">{{ __('Update') }}</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Rectangle Logo --}}
                            <div class="flex flex-col gap-4">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Logo (Full/Rectangle)') }}</label>
                                <div
                                    class="flex flex-col gap-4 p-6 rounded-3xl bg-white/5 border border-white/10 group/item">
                                    <div
                                        class="h-20 rounded-2xl bg-secondary/80 border border-white/10 p-4 flex items-center justify-center overflow-hidden">
                                        <img loading="lazy" decoding="async" src="{{ asset('assets/images/' . getSetting('logo_rectangle')) }}"
                                            id="preview-logo-rectangle"
                                            class="max-h-full object-contain transition-transform group-hover/item:scale-110">
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-[10px] text-slate-500 font-bold">{{ __('Main brand logo layer.') }}
                                        </p>
                                        <input type="file" name="logo_rectangle" class="hidden image-input"
                                            data-preview="#preview-logo-rectangle">
                                        <button type="button"
                                            class="trigger-input px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-[10px] font-bold text-white uppercase tracking-widest transition-all">{{ __('Update') }}</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Favicon --}}
                            <div class="flex flex-col gap-4">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Favicon') }}</label>
                                <div
                                    class="flex items-center gap-6 p-6 rounded-3xl bg-white/5 border border-white/10 group/item">
                                    <div
                                        class="w-14 h-14 rounded-xl bg-secondary/80 border border-white/10 p-2 flex items-center justify-center overflow-hidden shrink-0">
                                        <img loading="lazy" decoding="async" src="{{ asset('assets/images/' . getSetting('favicon')) }}"
                                            id="preview-favicon"
                                            class="max-w-full max-h-full object-contain transition-transform group-hover/item:scale-110">
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <input type="file" name="favicon" class="hidden image-input"
                                            data-preview="#preview-favicon">
                                        <button type="button"
                                            class="trigger-input px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-[10px] font-bold text-white uppercase tracking-widest transition-all">{{ __('Update Favicon') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Financial Configuration --}}
                    <section class="space-y-8 pt-8 border-t border-white/5">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-8 h-8 rounded-lg bg-accent-primary/10 flex items-center justify-center text-accent-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wider">{{ __('Financial Rules') }}
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="flex flex-col gap-3 md:col-span-2">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Primary Platform Currency') }}</label>
                                <div class="relative">
                                    <select id="currency-select" class="w-full">
                                        @foreach ($currencies as $code => $curr)
                                            <option value="{{ $code }}" data-symbol="{{ $curr['symbol'] }}"
                                                data-name="{{ $curr['name'] }}" data-code="{{ $code }}"
                                                @if ($code == getSetting('currency')) selected @endif>
                                                {{ $code }} - {{ $curr['name'] }} | {{ getSetting('currency') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Hidden inputs to maintain compatibility with existing controller update method --}}
                                <input type="hidden" name="currency_name" id="hidden-currency-name"
                                    value="{{ getSetting('currency') }}">
                                <input type="hidden" name="currency_symbol" id="hidden-currency-symbol"
                                    value="{{ getSetting('currency_symbol') }}">
                                <p class="text-[10px] text-slate-500 font-bold px-2 italic">
                                    {{ __('Search by code (USD) or country name (United States)') }}</p>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Symbol Position') }}</label>
                                <select name="currency_position"
                                    class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-sm font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none appearance-none">
                                    <option value="before"
                                        {{ getSetting('currency_symbol_position') == 'before' ? 'selected' : '' }}
                                        class="bg-secondary-dark">{{ __('Before Amount ($100)') }}</option>
                                    <option value="after"
                                        {{ getSetting('currency_symbol_position') == 'after' ? 'selected' : '' }}
                                        class="bg-secondary-dark">{{ __('After Amount (100$)') }}</option>
                                </select>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Decimal Places') }}</label>
                                <input type="number" name="decimal_places" value="{{ getSetting('decimal_places') }}"
                                    min="0" max="8" required
                                    class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                            </div>
                        </div>
                    </section>

                    {{-- Office Locations --}}
                    <section
                        class="flex flex-col gap-8 p-10 rounded-[2.5rem] bg-white/[0.03] border border-white/10 backdrop-blur-md relative overflow-hidden mt-10">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-accent-primary/10 blur-[100px] -mr-32 -mt-32">
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-accent-primary/20 flex items-center justify-center border border-accent-primary/30">
                                    <svg class="w-6 h-6 text-accent-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-white uppercase tracking-wider">
                                    {{ __('Office Locations') }}</h3>
                            </div>
                            <button type="button" id="add-office-btn"
                                class="flex items-center justify-center gap-2 px-6 py-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-[10px] font-bold text-white uppercase tracking-widest transition-all duration-300">
                                <svg class="w-4 h-4 text-accent-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Add New Office') }}
                            </button>
                        </div>

                        <div id="offices-container" class="flex flex-col gap-6 relative">
                            @php
                                $offices = json_decode(getSetting('offices', '[]'), true) ?: [];
                            @endphp

                            @forelse($offices as $index => $office)
                                <div
                                    class="office-item group/office relative grid grid-cols-1 md:grid-cols-2 gap-6 p-8 rounded-3xl bg-white/5 border border-white/10 transition-all duration-500 hover:bg-white/[0.07]">
                                    <button type="button"
                                        class="remove-office-btn absolute -top-3 -right-3 w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center opacity-0 group-hover/office:opacity-100 transition-all duration-300 hover:scale-110 shadow-lg shadow-red-500/20 z-10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>

                                    <div class="flex flex-col gap-3 md:col-span-2">
                                        <label
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Office Name') }}</label>
                                        <input type="text" name="offices[{{ $index }}][name]"
                                            value="{{ $office['name'] ?? '' }}" placeholder="e.g. Headquarters" required
                                            class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                                    </div>

                                    <div class="flex flex-col gap-3 md:col-span-2">
                                        <label
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Address') }}</label>
                                        <textarea name="offices[{{ $index }}][address]" rows="2" placeholder="Street, City, Country" required
                                            class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-sm font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none resize-none">{{ $office['address'] ?? '' }}</textarea>
                                    </div>

                                    <div class="flex flex-col gap-3">
                                        <label
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Contact Email') }}</label>
                                        <input type="email" name="offices[{{ $index }}][email]"
                                            value="{{ $office['email'] ?? '' }}" placeholder="office@example.com"
                                            class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                                    </div>

                                    <div class="flex flex-col gap-3">
                                        <label
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Contact Phone') }}</label>
                                        <input type="text" name="offices[{{ $index }}][phone]"
                                            value="{{ $office['phone'] ?? '' }}" placeholder="+1 234 567 890"
                                            class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                                    </div>
                                </div>
                            @empty
                                <div id="empty-offices-msg"
                                    class="flex flex-col items-center justify-center p-12 rounded-3xl border-2 border-dashed border-white/5 bg-white/[0.02]">
                                    <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 font-bold text-sm text-center italic">
                                        {{ __('No offices configured. Click "Add New Office" to start.') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    {{-- Sticky Submit Bar --}}
                    <div class="pt-10">
                        <button type="submit" id="submit-btn"
                            class="w-full md:w-auto px-12 py-5 bg-accent-primary text-white text-xs font-bold uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-accent-primary/30 hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center gap-4">
                            <svg class="w-5 h-5 submit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <svg class="w-5 h-5 hidden loading-icon animate-spin" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span class="btn-text">{{ __('Synchronize Platform Settings') }}</span>
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
             * Custom matcher for Select2 to search across multiple data attributes.
             */
            function customMatcher(params, data) {
                // If there are no search terms, return all of the data
                if ($.trim(params.term) === '') {
                    return data;
                }

                // Do not display the item if there is no 'text' property
                if (typeof data.text === 'undefined') {
                    return null;
                }

                const term = params.term.toLowerCase();
                const $el = $(data.element);

                // Check if the text contains the term
                if (data.text.toLowerCase().indexOf(term) > -1) {
                    return data;
                }

                // Check data attributes for the term
                for (const key in $el.data()) {
                    const value = $el.data(key);
                    if (typeof value === 'string' && value.toLowerCase().indexOf(term) > -1) {
                        return data;
                    }
                }

                // If nothing else matches, return null
                return null;
            }

            /**
             * 0. Initialize Select2 for Timezone
             */
            function formatTz(tz) {
                if (!tz.id) return tz.text;
                const $el = $(tz.element);
                const utc = $el.data('utc');
                const name = $el.data('name');

                return $(`
                    <div class="flex items-center justify-between gap-4 py-1">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-white leading-tight">${tz.text}</span>
                            <span class="text-[10px] text-slate-500 font-bold">${name}</span>
                        </div>
                        <div class="shrink-0">
                            <span class="text-[9px] bg-accent-primary/10 text-accent-primary px-2 py-1 rounded-lg font-bold border border-accent-primary/20">GMT ${utc}</span>
                        </div>
                    </div>
                `);
            }

            $('#timezone-select').select2({
                templateResult: formatTz,
                templateSelection: formatTz,
                matcher: customMatcher,
                width: '100%',
                dropdownParent: $('#timezone-select').parent(), // Ensure dropdown is within the form
            });

            /**
             * 0.1 Initialize Select2 for Currency
             */
            function formatCurrency(state) {
                if (!state.id) return state.text;
                const $el = $(state.element);
                const symbol = $el.data('symbol');
                const name = $el.data('name');

                return $(`
                    <div class="flex items-center justify-between gap-4 py-1">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-xs font-bold text-white border border-white/5">${symbol}</span>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-white leading-tight">${state.id}</span>
                                <span class="text-[10px] text-slate-500 font-bold">${name}</span>
                            </div>
                        </div>
                    </div>
                `);
            }

            $('#currency-select').select2({
                templateResult: formatCurrency,
                templateSelection: formatCurrency,
                matcher: customMatcher,
                width: '100%',
                dropdownParent: $('#currency-select').parent(), // Ensure dropdown is within the form
            });

            // Sync hidden inputs for currency
            $('#currency-select').on('change', function() {
                const $opt = $(this).find(':selected');
                $('#hidden-currency-name').val($opt.data('code'));
                $('#hidden-currency-symbol').val($opt.data('symbol'));
            });
            // Initial sync
            if ($('#currency-select').val()) {
                const $opt = $('#currency-select').find(':selected');
                $('#hidden-currency-name').val($opt.data('code'));
                $('#hidden-currency-symbol').val($opt.data('symbol'));
            }

            /**
             * 0.2 Offices Repeater Logic
             */
            let officeIndex = {{ count($offices) }};
            const $officesContainer = $('#offices-container');
            const $emptyOfficesMsg = $('#empty-offices-msg');

            $('#add-office-btn').on('click', function() {
                $emptyOfficesMsg.hide();

                const html = `
                    <div class="office-item group/office relative grid grid-cols-1 md:grid-cols-2 gap-6 p-8 rounded-3xl bg-white/5 border border-white/10 transition-all duration-500 hover:bg-white/[0.07] animate__animated animate__fadeInUp animate__faster">
                        <button type="button" class="remove-office-btn absolute -top-3 -right-3 w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg shadow-red-500/20 z-10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="flex flex-col gap-3 md:col-span-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Office Name') }}</label>
                            <input type="text" name="offices[${officeIndex}][name]" placeholder="e.g. Headquarters" required
                                class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                        </div>

                        <div class="flex flex-col gap-3 md:col-span-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Address') }}</label>
                            <textarea name="offices[${officeIndex}][address]" rows="2" placeholder="Street, City, Country" required
                                class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none resize-none"></textarea>
                        </div>

                        <div class="flex flex-col gap-3">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Contact Email') }}</label>
                            <input type="email" name="offices[${officeIndex}][email]" placeholder="office@example.com"
                                class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                        </div>

                        <div class="flex flex-col gap-3">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Contact Phone') }}</label>
                            <input type="text" name="offices[${officeIndex}][phone]" placeholder="+1 234 567 890"
                                class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                        </div>
                    </div>
                `;

                $officesContainer.append(html);
                officeIndex++;
            });

            $(document).on('click', '.remove-office-btn', function() {
                const $item = $(this).closest('.office-item');
                $item.addClass('animate__fadeOutDown');
                setTimeout(() => {
                    $item.remove();
                    if ($officesContainer.find('.office-item').length === 0) {
                        $emptyOfficesMsg.fadeIn();
                    }
                }, 300);
            });

            /**
             * 1. Trigger hidden file inputs
             */
            $('.trigger-input').click(function() {
                $(this).siblings('input[type="file"]').click();
            });

            /**
             * 2. Live Image Previews
             */
            $('.image-input').change(function() {
                const input = this;
                const preview = $(this).data('preview');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $(preview).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });

            /**
             * 3. AJAX Submission
             */
            $('#core-settings-form').on('submit', function(e) {
                e.preventDefault();

                const $form = $(this);
                const $btn = $('#submit-btn');
                const $btnText = $btn.find('.btn-text');
                const $submitIcon = $btn.find('.submit-icon');
                const $loadingIcon = $btn.find('.loading-icon');
                const originalText = $btnText.text();

                // Validation
                const formData = new FormData(this);

                // UI State: Loading
                $btn.prop('disabled', true).addClass('opacity-70 cursor-not-allowed');
                $btnText.text('{{ __('Processing...') }}');
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

    <style>
        /* Premium custom scrollbar for bento content */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(var(--accent-primary-rgb), 0.1);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(var(--accent-primary-rgb), 0.3);
        }

        /* Input specific focus styles */
        select option {
            background-color: #1a1a2e;
            color: #fff;
            padding: 10px;
        }

        /* Select2 Bento Styling - FIXED DISTORTION */
        .select2-container--default .select2-selection--single {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 1.25rem !important;
            height: 60px !important;
            /* Fixed height to match inputs */
            display: flex !important;
            align-items: center !important;
            transition: all 0.3s ease !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: rgba(var(--accent-primary-rgb), 0.5) !important;
            box-shadow: 0 0 0 4px rgba(var(--accent-primary-rgb), 0.1) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white !important;
            padding-left: 20px !important;
            padding-right: 40px !important;
            width: 100% !important;
            line-height: normal !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 15px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: rgba(255, 255, 255, 0.4) transparent transparent transparent !important;
            border-width: 5px 4px 0 4px !important;
        }

        .select2-container--open .select2-dropdown {
            background: #111122 !important;
            /* Slightly darker for contrast */
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 1.25rem !important;
            margin-top: 5px !important;
            overflow: hidden !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6) !important;
            backdrop-filter: blur(25px) !important;
            z-index: 9999 !important;
        }

        .select2-search--dropdown {
            padding: 15px !important;
            background: rgba(255, 255, 255, 0.02) !important;
        }

        .select2-search--dropdown .select2-search__field {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 0.75rem !important;
            color: white !important;
            padding: 10px 15px !important;
            outline: none !important;
            font-size: 13px !important;
        }

        .select2-results__option {
            padding: 12px 20px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background: rgba(var(--accent-primary-rgb), 0.15) !important;
        }

        .select2-results__option[aria-selected=true] {
            background: rgba(var(--accent-primary-rgb), 0.3) !important;
        }

        .select2-results__options::-webkit-scrollbar {
            width: 4px;
        }

        .select2-results__options::-webkit-scrollbar-thumb {
            background: rgba(var(--accent-primary-rgb), 0.2);
            border-radius: 10px;
        }
    </style>
@endpush
