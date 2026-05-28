@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div class="space-y-8">

        {{-- Message  if module is disabled --}}
        @if (!moduleEnabled('investment_module'))
            <div
                class="relative z-10 p-8 flex flex-col items-center justify-center text-center h-[300px] bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden backdrop-blur-xl">
                <div
                    class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-500" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">
                    {{ __('Investment module disabled') }}</h4>
                <p class="text-[10px] text-slate-500 max-w-[200px] mb-6 font-medium leading-relaxed italic">
                    {{ __('Investment module is disabled. Please enable the investment module in settings to initialize.') }}
                </p>
                <a href="{{ route('admin.settings.modules.index') }}"
                    class="px-5 py-2 rounded-xl bg-white/5 border border-white/10 text-[9px] font-black text-white uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all active:scale-95">
                    {{ __('Settings') }}
                </a>
            </div>
        @endif

        @if (moduleEnabled('investment_module'))
            {{-- Page Header --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <a href="{{ route('admin.investments.plans.index') }}"
                        class="inline-flex items-center gap-2 text-sm text-text-secondary hover:text-white transition-colors mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18">
                            </path>
                        </svg>
                        {{ __('Back to Plans') }}
                    </a>
                    <h1 class="text-2xl font-bold text-white">{{ __('Create Investment Plan') }}</h1>
                    <p class="text-sm text-text-secondary mt-1">
                        {{ __('Configure a new investment opportunity for your users.') }}
                    </p>
                </div>
                <button type="button"
                    class="btn-save-plan hidden md:flex items-center gap-2 bg-accent-primary text-white px-6 py-2.5 rounded-xl font-bold hover:bg-accent-primary/90 transition-all shadow-lg shadow-accent-primary/20 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Save Plan') }}
                </button>
            </div>

            <form id="createPlanForm" action="{{ route('admin.investments.plans.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Left Column: Core Details --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Basic Info Card --}}
                        <div class="bg-secondary border border-white/5 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                                <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                {{ __('Basic Information') }}
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-text-secondary mb-2">{{ __('Plan Name') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all placeholder:text-text-secondary/50"
                                        placeholder="{{ __('e.g., Global Tech Giants ETF') }}">
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-text-secondary mb-2">{{ __('Description') }}
                                        <span class="text-red-500">*</span></label>
                                    <textarea id="description" name="description" rows="3" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all placeholder:text-text-secondary/50"
                                        placeholder="{{ __('Briefly describe the investment strategy and focus.') }}"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Financials Card --}}
                        <div class="bg-secondary border border-white/5 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                {{ __('Financial Details') }}
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="min_investment"
                                        class="block text-sm font-medium text-text-secondary mb-2">{{ __('Minimum Investment') }}
                                        ({{ getSetting('currency') }}) <span class="text-red-500">*</span></label>
                                    <input type="number" id="min_investment" name="min_investment" step="any" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all"
                                        placeholder="100.00">
                                </div>
                                <div>
                                    <label for="max_investment"
                                        class="block text-sm font-medium text-text-secondary mb-2">{{ __('Maximum Investment') }}
                                        ({{ getSetting('currency') }}) <span class="text-red-500">*</span></label>
                                    <input type="number" id="max_investment" name="max_investment" step="any" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all"
                                        placeholder="10000.00">
                                </div>
                            </div>

                            <div class="mb-4">
                                <div>
                                    <label for="return_percent"
                                        class="block text-sm font-medium text-text-secondary mb-2">{{ __('Return Percentage') }}
                                        (%) <span class="text-red-500">*</span></label>
                                    <input type="number" id="return_percent" name="return_percent" step="any"
                                        required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-bold text-emerald-400"
                                        placeholder="5.5">
                                    <p class="mt-2 text-[11px] leading-relaxed text-text-secondary/80">
                                        {{ __('The total aggregate profit percentage the investor will earn over the entire duration of the plan, not the amount returned per interval.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Duration Card --}}
                        <div class="bg-secondary border border-white/5 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('Investment Duration') }}
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="duration"
                                        class="block text-sm font-medium text-text-secondary mb-2">{{ __('Duration Value') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="number" id="duration" name="duration" min="1" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all"
                                        placeholder="6">
                                </div>
                                <div>
                                    <label for="duration_type"
                                        class="block text-sm font-medium text-text-secondary mb-2">{{ __('Duration Type') }}
                                        <span class="text-red-500">*</span></label>
                                    <select id="duration_type" name="duration_type" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary transition-all appearance-none cursor-pointer">
                                        <option value="hours" class="bg-slate-800 text-white">{{ __('Hours') }}
                                        </option>
                                        <option value="days" class="bg-slate-800 text-white">{{ __('Days') }}
                                        </option>
                                        <option value="months" class="bg-slate-800 text-white" selected>
                                            {{ __('Months') }}
                                        </option>
                                        <option value="years" class="bg-slate-800 text-white">{{ __('Years') }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="return_interval"
                                        class="block text-sm font-medium text-text-secondary mb-2">{{ __('Return Interval') }}
                                        <span class="text-red-500">*</span></label>
                                    <select id="return_interval" name="return_interval" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary transition-all appearance-none cursor-pointer">
                                        <option value="hourly" class="bg-slate-800 text-white">{{ __('Hourly') }}
                                        </option>
                                        <option value="daily" class="bg-slate-800 text-white">{{ __('Daily') }}
                                        </option>
                                        <option value="weekly" class="bg-slate-800 text-white">{{ __('Weekly') }}
                                        </option>
                                        <option value="monthly" class="bg-slate-800 text-white" selected>
                                            {{ __('Monthly') }}
                                        </option>
                                        <option value="yearly" class="bg-slate-800 text-white">{{ __('Yearly') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <p class="mt-4 text-[11px] leading-relaxed text-text-secondary/80">
                                {{ __('The "Return Interval" dictates how frequently the "Return Percentage" is split up and paid out over the full "Duration". For example, a 10% total return paid Monthly over 10 months credits 1% per month.') }}
                            </p>
                        </div>

                    </div>

                    {{-- Right Column: Settings & Metadata --}}
                    <div class="space-y-6">

                        {{-- Targeting Card --}}
                        <div class="bg-secondary border border-white/5 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                    </path>
                                </svg>
                                {{ __('Targeting & Profile') }}
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="risk_profile"
                                        class="block text-sm font-medium text-text-secondary mb-2">{{ __('Risk Profile') }}
                                        <span class="text-red-500">*</span></label>
                                    <select id="risk_profile" name="risk_profile" required
                                        class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary transition-all appearance-none cursor-pointer">
                                        <option value="conservative" class="bg-slate-800 text-white">
                                            {{ __('Conservative') }}
                                        </option>
                                        <option value="balanced" class="bg-slate-800 text-white" selected>
                                            {{ __('Balanced') }}</option>
                                        <option value="growth" class="bg-slate-800 text-white">{{ __('Growth') }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label for="investment_goal"
                                        class="block text-sm font-medium text-text-secondary mb-2">{{ __('Investment Goal') }}
                                        <span class="text-red-500">*</span></label>
                                    <select id="investment_goal" name="investment_goal" required
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-primary transition-all appearance-none cursor-pointer">
                                        <option value="short_term" class="bg-slate-800 text-white">{{ __('Short Term') }}
                                        </option>
                                        <option value="medium_term" class="bg-slate-800 text-white" selected>
                                            {{ __('Medium Term') }}</option>
                                        <option value="long_term" class="bg-slate-800 text-white">{{ __('Long Term') }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-text-secondary mb-3">{{ __('Related Interests') }}
                                        <span
                                            class="text-xs text-slate-500 font-normal">({{ __('Select multiple') }})</span></label>
                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                        @php
                                            $interests = [
                                                'stocks_and_etfs' => 'Stocks & ETFs',
                                                'crypto_assets' => 'Crypto Assets',
                                                'real_estate' => 'Real Estate',
                                                'fixed_income' => 'Fixed Income',
                                                'cash_and_savings' => 'Cash & Savings',
                                                'commodities' => 'Commodities',
                                                'businesses_and_startups' => 'Startups',
                                                'art_and_collectibles' => 'Art',
                                                'gaming_and_esports' => 'Gaming',
                                            ];
                                        @endphp
                                        @foreach ($interests as $key => $label)
                                            <label
                                                class="flex items-center gap-3 p-3 bg-white/5 border border-white/5 rounded-xl cursor-pointer hover:bg-white/10 hover:border-white/20 transition-all group">
                                                <div class="relative flex items-center">
                                                    <input type="checkbox" name="interests[]"
                                                        value="{{ $key }}" class="peer sr-only">
                                                    <div
                                                        class="w-5 h-5 rounded border-2 border-slate-600 peer-checked:bg-accent-primary peer-checked:border-accent-primary transition-all flex items-center justify-center">
                                                        <svg class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <span
                                                    class="text-sm font-medium text-slate-300 group-hover:text-white transition-colors">{{ __($label) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Features & Toggles Card --}}
                        <div class="bg-secondary border border-white/5 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ __('Configuration Options') }}
                            </h3>

                            <div class="space-y-4">
                                {{-- Capital Returned Toggle --}}
                                <label
                                    class="flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition-colors">
                                    <div>
                                        <div class="text-sm font-bold text-white">{{ __('Capital Returned') }}</div>
                                        <div class="text-xs text-text-secondary mt-0.5">
                                            {{ __('Principal returned at the end of tenure.') }}</div>
                                    </div>
                                    <div class="relative">
                                        <input type="checkbox" name="capital_returned" value="1"
                                            class="peer sr-only" checked>
                                        <div
                                            class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500">
                                        </div>
                                    </div>
                                </label>

                                {{-- Compounding Toggle --}}
                                <label
                                    class="flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition-colors">
                                    <div>
                                        <div class="text-sm font-bold text-white">{{ __('Supports Compounding') }}</div>
                                        <div class="text-xs text-text-secondary mt-0.5">
                                            {{ __('Allows reinvesting earnings automatically.') }}</div>
                                    </div>
                                    <div class="relative">
                                        <input type="checkbox" name="compounding" value="1" class="peer sr-only"
                                            checked>
                                        <div
                                            class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500">
                                        </div>
                                    </div>
                                </label>

                                {{-- Featured Toggle --}}
                                <label
                                    class="flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition-colors">
                                    <div>
                                        <div class="text-sm font-bold text-amber-400">{{ __('Featured Plan') }}</div>
                                        <div class="text-xs text-text-secondary mt-0.5">
                                            {{ __('Highlight this plan on the frontend.') }}</div>
                                    </div>
                                    <div class="relative">
                                        <input type="checkbox" name="is_featured" value="1" class="peer sr-only">
                                        <div
                                            class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500">
                                        </div>
                                    </div>
                                </label>

                                {{-- Status Toggle --}}
                                <label
                                    class="flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition-colors">
                                    <div>
                                        <div class="text-sm font-bold text-white">{{ __('Plan Status') }}</div>
                                        <div class="text-xs text-text-secondary mt-0.5">
                                            {{ __('Enable or disable new investments.') }}</div>
                                    </div>
                                    <div class="relative">
                                        <input type="checkbox" name="is_enabled" value="1" class="peer sr-only"
                                            checked>
                                        <div
                                            class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent-primary">
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mobile Submit Button --}}
                <div class="mt-8 flex md:hidden">
                    <button type="button"
                        class="btn-save-plan w-full flex justify-center items-center gap-2 bg-accent-primary text-white px-6 py-3.5 rounded-xl font-bold hover:bg-accent-primary/90 transition-all shadow-lg shadow-accent-primary/20 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        {{ __('Save Plan') }}
                    </button>
                </div>
            </form>
        @endif
    </div>

    {{-- Error Banner Container (hidden by default) --}}
    <div id="error-banner" class="hidden mt-6 bg-red-500/10 border border-red-500/20 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="w-full">
                <h4 class="text-sm font-bold text-red-500">{{ __('Please fix the following errors:') }}</h4>
                <ul id="error-list" class="text-sm text-red-400 mt-2 list-disc list-inside space-y-1"></ul>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.btn-save-plan').on('click', function() {
            const originalText = $(this).html();
            const $btns = $('.btn-save-plan');

            $btns.html(
                '<svg class="w-5 h-5 animate-spin mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'
            );
            $btns.prop('disabled', true);

            const formData = new FormData($('#createPlanForm')[0]);
            // Handle un-checked checkboxes for toggles since unchecked inputs aren't sent
            ['capital_returned', 'compounding', 'is_featured', 'is_enabled'].forEach(field => {
                if (!$(`input[name="${field}"]`).is(':checked')) {
                    formData.append(field, '0');
                }
            });

            $('#error-banner').addClass('hidden');
            $('#error-list').empty();

            $.ajax({
                url: $('#createPlanForm').attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success || response.status === 'success') {
                        toastNotification(response.message, 'success');
                        setTimeout(() => {
                            window.location.href =
                                "{{ route('admin.investments.plans.index') }}";
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    $('.btn-save-plan').html(originalText).prop('disabled', false);
                    toastNotification(xhr.responseJSON?.message ||
                        '{{ __('An error occurred while creating the plan.') }}', 'error');
                }
            });
        });
    </script>
@endpush
