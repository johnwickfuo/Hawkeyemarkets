@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="space-y-8">
        {{-- Header & Sorting --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">

            <div class="lg:col-span-1 flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-white font-heading tracking-tight flex items-center gap-3">
                    <span
                        class="w-2 h-8 bg-accent-primary rounded-sm shadow-[0_0_15px_rgba(var(--color-accent-primary),0.5)]"></span>
                    {{ __('Investment Marketplace') }}
                </h2>
                <p class="text-text-secondary mt-2 pl-5 border-l border-white/5 font-light">
                    {{ __('Discover opportunities tailored to your financial goals.') }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                {{-- Sort moved to sidebar --}}

                {{-- Mobile Filter Trigger --}}

            </div>
        </div>

        {{-- Mobile Filter Drawer (Fixed Left) --}}
        <div id="mobileFilterDrawer"
            class="fixed inset-y-0 left-0 z-[60] w-72 bg-[#0f1219] border-r border-white/10 shadow-2xl transform -translate-x-full transition-transform duration-300 lg:hidden overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-white text-lg">{{ __('Filters') }}</h3>
                    <button class="text-text-secondary hover:text-white cursor-pointer"
                        onclick="$('#mobileFilterDrawer').addClass('-translate-x-full')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Mobile Sort --}}
                <div class="mb-6">
                    <label
                        class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block">{{ __('Sort By') }}</label>
                    <div class="relative group/sort">
                        <select id="sortPlansMobile"
                            class="sort-plans w-full appearance-none bg-[#0f1219] border border-white/10 rounded-xl px-4 py-2.5 pr-10 text-base text-white focus:border-accent-primary focus:ring-0 cursor-pointer">
                            <option value="featured" class="bg-[#0f1219] text-white">{{ __('Featured') }}</option>
                            <option value="min_asc" class="bg-[#0f1219] text-white">{{ __('Min Investment (Low to High)') }}
                            </option>
                            <option value="min_desc" class="bg-[#0f1219] text-white">
                                {{ __('Min Investment (High to Low)') }}</option>
                            <option value="roi_desc" class="bg-[#0f1219] text-white">{{ __('Highest ROI') }}</option>
                            <option value="duration_asc" class="bg-[#0f1219] text-white">{{ __('Shortest Duration') }}
                            </option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Copied Filters --}}
                <div class="mb-6">
                    <label
                        class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block">{{ __('Risk Profile') }}</label>
                    <div class="space-y-2">
                        @php
                            $risks = $investment_plans->pluck('risk_profile')->unique();
                        @endphp
                        @foreach ($risks as $risk)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox"
                                        class="peer h-4 w-4 rounded border-white/20 bg-white/5 text-accent-primary focus:ring-accent-primary/50 transition-all filter-risk"
                                        value="{{ $risk }}">
                                </div>
                                <span
                                    class="text-sm text-text-secondary group-hover:text-white transition-colors capitalize">{{ __($risk) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label
                        class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block">{{ __('Return Interval') }}</label>
                    <div class="space-y-2">
                        @php
                            $intervals = $investment_plans->pluck('return_interval')->unique();
                        @endphp
                        @foreach ($intervals as $interval)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox"
                                        class="peer h-4 w-4 rounded border-white/20 bg-white/5 text-accent-primary focus:ring-accent-primary/50 transition-all filter-interval"
                                        value="{{ $interval }}">
                                </div>
                                <span
                                    class="text-sm text-text-secondary group-hover:text-white transition-colors capitalize">{{ __($interval) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label
                        class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block">{{ __('Interests') }}</label>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $allInterests = $investment_plans->pluck('interests')->flatten()->unique();
                        @endphp
                        @foreach ($allInterests as $interest)
                            <button
                                class="px-3 py-1 rounded-lg text-xs font-medium border border-white/10 bg-white/5 text-text-secondary hover:bg-white/10 hover:text-white hover:border-white/20 transition-all filter-interest cursor-pointer"
                                data-value="{{ $interest }}">
                                {{ __($interest) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <button
                    class="btn-reset-filters w-full py-2 rounded-lg text-xs font-medium text-text-secondary hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
                    {{ __('Reset Filters') }}
                </button>
            </div>
        </div>

        {{-- Mobile Overlay (Backdrop for drawer) --}}
        <div class="fixed inset-0 bg-black/50 z-[50] hidden lg:hidden" id="mobileDrawerBackdrop"
            onclick="$('#mobileFilterDrawer').addClass('-translate-x-full')"></div>
    </div>

    {{-- Recommended Plans --}}
    @if ($recommended_plans->isNotEmpty())
        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-4">
                <span
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-accent-primary/20 text-accent-primary ring-1 ring-accent-primary/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                </span>
                <h2 class="text-xl font-bold text-white">{{ __('Recommended For You') }}</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($recommended_plans as $plan)
                    <div class="plan-card group relative bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-0 hover:border-accent-primary/40 transition-all duration-300 overflow-hidden ring-1 ring-white/5 hover:ring-accent-primary/20 hover:shadow-[0_0_30px_-10px_rgba(var(--color-accent-primary),0.3)] hover:-translate-y-1 flex flex-col"
                        data-min="{{ $plan->min_investment }}" data-roi="{{ $plan->return_percent }}"
                        data-duration="{{ (int) $plan->duration }}" data-risk="{{ $plan->risk_profile }}"
                        data-interval="{{ $plan->return_interval }}"
                        data-interests='{{ json_encode($plan->interests ?? []) }}' data-featured="1">

                        {{-- Top Decoration --}}
                        <div
                            class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:via-accent-primary/70 transition-all duration-500">
                        </div>

                        <div class="p-6 flex flex-col h-full">
                            {{-- Header --}}
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex items-center gap-4">
                                    {{-- Icon Container --}}
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 p-2.5 flex items-center justify-center group-hover:bg-accent-primary/10 group-hover:border-accent-primary/20 transition-all duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="text-white group-hover:text-accent-primary transition-colors">
                                            <path d="M12 2v20M2 12h20M12 2l-5 5 5-5 5 5" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3
                                            class="font-heading font-bold text-sm lg:text-base text-white leading-tight mb-1 group-hover:text-accent-primary transition-colors">
                                            {{ __($plan->name) }}
                                        </h3>
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/5 border border-white/5 text-text-secondary uppercase tracking-wider group-hover:text-white group-hover:bg-white/10 transition-colors">
                                            {{ __(str_replace('_', ' ', $plan->risk_profile)) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="flex flex-col items-end gap-1">
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-accent-primary/20 text-accent-primary border border-accent-primary/20 uppercase tracking-wide">
                                        {{ __('Top Pick') }}
                                    </span>
                                    @if ($plan->capital_returned)
                                        <span class="text-[10px] text-emerald-400 flex items-center gap-1 font-bold">
                                            <span class="relative flex h-2 w-2">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                                            </span>
                                            {{ __('Capital Returned') }}
                                        </span>
                                    @endif
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold {{ $plan->compounding ? 'bg-blue-500/20 text-blue-400 border-blue-500/20' : 'bg-white/5 text-text-secondary border-white/5' }} border uppercase tracking-wide">
                                        {{ $plan->compounding ? __('Compounding') : __('Simple Interest') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Description --}}
                            <p class="text-text-secondary text-xs lg:text-sm leading-relaxed mb-6 line-clamp-2"
                                title="{{ __($plan->description) }}">
                                {{ __($plan->description) }}
                            </p>

                            {{-- Metrics --}}
                            <div
                                class="grid grid-cols-3 gap-4 py-4 border-t border-b border-white/5 mb-6 bg-white/[0.02] rounded-xl px-4">
                                <div class="text-center border-r border-white/5">
                                    <span
                                        class="block text-[10px] text-text-secondary uppercase tracking-wider font-bold mb-1">{{ __('ROI') }}</span>
                                    <span
                                        class="block text-base lg:text-lg font-mono font-bold text-accent-primary">{{ $plan->return_percent }}%</span>

                                </div>
                                <div class="text-center border-r border-white/5">
                                    <span
                                        class="block text-[10px] text-text-secondary uppercase tracking-wider font-bold mb-1">{{ __('Term') }}</span>
                                    <span class="block text-base lg:text-lg font-mono font-bold text-white">
                                        {{ $plan->duration }}
                                        <span
                                            class="text-[10px] text-text-secondary font-bold">{{ __($plan->duration_type) }}</span>
                                    </span>

                                </div>
                                <div class="text-center">
                                    <span
                                        class="block text-[10px] text-text-secondary uppercase tracking-wider font-bold mb-1">{{ __('Min') }}</span>
                                    <span
                                        class="block text-base lg:text-lg font-mono font-bold text-white">{{ getSetting('currency_symbol', '$') }}{{ number_format($plan->min_investment, getSetting('decimal_places', 2)) }}</span>
                                </div>
                            </div>

                            {{-- Action --}}
                            <div class="mt-auto">
                                <button type="button"
                                    class="invest-btn w-full py-3.5 rounded-xl bg-accent-primary hover:bg-accent-primary-hover text-white font-bold transition-all shadow-[0_0_20px_rgba(var(--color-accent-primary),0.3)] hover:shadow-[0_0_30px_rgba(var(--color-accent-primary),0.5)] active:scale-[0.98] cursor-pointer flex items-center justify-center gap-2 group/btn"
                                    data-id="{{ $plan->id }}" data-name="{{ __($plan->name) }}"
                                    data-min="{{ $plan->min_investment }}" data-max="{{ $plan->max_investment }}"
                                    data-roi="{{ $plan->return_percent }}"
                                    data-interval="{{ __('ROI Returned') }} {{ __($plan->return_interval) }}"
                                    data-duration="{{ $plan->duration }} {{ __($plan->duration_type) }}"
                                    data-description="{{ __($plan->description) }}"
                                    data-capital="{{ $plan->capital_returned ? __('Capital Returned') : __('Capital Withheld') }}"
                                    data-compounding="{{ $plan->compounding ? __('Compounding') : __('Simple Interest') }}">
                                    <span>{{ __('Quick Invest') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4 transition-transform group-hover/btn:translate-x-1"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Main Content: Sidebar Filters + Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        {{-- Filters Sidebar (Desktop) --}}
        <div class="hidden lg:block space-y-6">
            <div class="bg-[#0f1219] border border-white/5 rounded-2xl p-6 sticky top-24">
                <h3 class="font-bold text-white mb-4">{{ __('Filter & Sort') }}</h3>

                {{-- Sort --}}
                <div class="mb-6">
                    <label
                        class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block">{{ __('Sort By') }}</label>
                    <div class="relative group/sort">
                        <select id="sortPlansDesktop"
                            class="sort-plans w-full appearance-none bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 pr-10 text-base text-white focus:border-accent-primary focus:ring-0 cursor-pointer">
                            <option value="featured" class="bg-[#0f1219] text-white">{{ __('Featured') }}</option>
                            <option value="min_asc" class="bg-[#0f1219] text-white">
                                {{ __('Min Investment (Low to High)') }}</option>
                            <option value="min_desc" class="bg-[#0f1219] text-white">
                                {{ __('Min Investment (High to Low)') }}</option>
                            <option value="roi_desc" class="bg-[#0f1219] text-white">{{ __('Highest ROI') }}</option>
                            <option value="duration_asc" class="bg-[#0f1219] text-white">{{ __('Shortest Duration') }}
                            </option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Categories / Risk --}}
                <div class="mb-6">
                    <label
                        class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block">{{ __('Risk Profile') }}</label>
                    <div class="space-y-2">
                        @php
                            $risks = $investment_plans->pluck('risk_profile')->unique();
                        @endphp
                        @foreach ($risks as $risk)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox"
                                        class="peer h-4 w-4 rounded border-white/20 bg-white/5 text-accent-primary focus:ring-accent-primary/50 transition-all filter-risk"
                                        value="{{ $risk }}">
                                </div>
                                <span
                                    class="text-sm text-text-secondary group-hover:text-white transition-colors capitalize">{{ __($risk) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label
                        class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block">{{ __('Return Interval') }}</label>
                    <div class="space-y-2">
                        @php
                            $intervals = $investment_plans->pluck('return_interval')->unique();
                        @endphp
                        @foreach ($intervals as $interval)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox"
                                        class="peer h-4 w-4 rounded border-white/20 bg-white/5 text-accent-primary focus:ring-accent-primary/50 transition-all filter-interval"
                                        value="{{ $interval }}">
                                </div>
                                <span
                                    class="text-sm text-text-secondary group-hover:text-white transition-colors capitalize">{{ __($interval) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Interests --}}
                <div class="mb-6">
                    <label
                        class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block">{{ __('Interests') }}</label>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $allInterests = $investment_plans->pluck('interests')->flatten()->unique();
                        @endphp
                        @foreach ($allInterests as $interest)
                            <button
                                class="px-3 py-1 rounded-lg text-xs font-medium border border-white/10 bg-white/5 text-text-secondary hover:bg-white/10 hover:text-white hover:border-white/20 transition-all filter-interest cursor-pointer"
                                data-value="{{ $interest }}">
                                {{ __($interest) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <button id="resetFilters"
                    class="btn-reset-filters w-full py-2 rounded-lg text-xs font-medium text-text-secondary hover:text-white hover:bg-white/5 transition-colors cursor-pointer">
                    {{ __('Reset Filters') }}
                </button>
            </div>
        </div>

        {{-- Plans Grid --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Toggle Mobile Filters --}}
            <div class="flex items-center justify-between mb-6">
                <h2 id="exploreMarketplace" class="text-2xl font-bold text-white">{{ __('Explore Marketplace') }}</h2>
                <button
                    class="lg:hidden px-4 py-2.5 rounded-xl bg-[#0f1219] border border-white/10 text-white font-medium flex items-center gap-2 cursor-pointer hover:bg-white/5 transition-colors"
                    onclick="$('#mobileFilterDrawer').removeClass('-translate-x-full')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="4" y1="21" x2="4" y2="14" />
                        <line x1="4" y1="10" x2="4" y2="3" />
                        <line x1="12" y1="21" x2="12" y2="12" />
                        <line x1="12" y1="8" x2="12" y2="3" />
                        <line x1="20" y1="21" x2="20" y2="16" />
                        <line x1="20" y1="12" x2="20" y2="3" />
                        <line x1="1" y1="14" x2="7" y2="14" />
                        <line x1="9" y1="8" x2="15" y2="8" />
                        <line x1="17" y1="16" x2="23" y2="16" />
                    </svg>
                    {{ __('Sort & Filter') }}
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-5" id="plansContainer">
                @foreach ($investment_plans as $plan)
                    <div class="plan-card group relative bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-0 hover:border-accent-primary/40 transition-all duration-300 overflow-hidden ring-1 ring-white/5 hover:ring-accent-primary/20 hover:shadow-[0_0_30px_-10px_rgba(var(--color-accent-primary),0.3)] hover:-translate-y-1 flex flex-col"
                        data-min="{{ $plan->min_investment }}" data-roi="{{ $plan->return_percent }}"
                        data-duration="{{ (int) $plan->duration }}" data-risk="{{ $plan->risk_profile }}"
                        data-interval="{{ $plan->return_interval }}"
                        data-interests='{{ json_encode($plan->interests ?? []) }}'
                        data-featured="{{ $plan->is_featured ? 1 : 0 }}">

                        {{-- Top Decoration --}}
                        <div
                            class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:via-accent-primary/70 transition-all duration-500">
                        </div>

                        <div class="p-6 flex flex-col h-full">
                            {{-- Header --}}
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex items-center gap-4">
                                    {{-- Icon Container --}}
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 p-2.5 flex items-center justify-center group-hover:bg-accent-primary/10 group-hover:border-accent-primary/20 transition-all duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="text-white group-hover:text-accent-primary transition-colors">
                                            <path d="M12 2v20M2 12h20M12 2l-5 5 5-5 5 5" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3
                                            class="font-heading font-bold text-sm lg:text-base text-white leading-tight mb-1 group-hover:text-accent-primary transition-colors">
                                            {{ __($plan->name) }}
                                        </h3>
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/5 border border-white/5 text-text-secondary uppercase tracking-wider group-hover:text-white group-hover:bg-white/10 transition-colors">
                                            {{ __(str_replace('_', ' ', $plan->risk_profile)) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Status/Badges --}}
                                <div class="flex flex-col items-end gap-1">
                                    @if ($plan->is_featured && $recommended_plans->contains('id', $plan->id))
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-bold bg-accent-primary/20 text-accent-primary border border-accent-primary/20 uppercase tracking-wide">
                                            {{ __('Recommended') }}
                                        </span>
                                    @endif
                                    @if ($plan->capital_returned)
                                        <span class="text-[10px] text-emerald-400 flex items-center gap-1 font-bold">
                                            <span class="relative flex h-2 w-2">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                                            </span>
                                            {{ __('Capital Returned') }}
                                        </span>
                                    @else
                                        <span class="text-[10px] text-text-secondary">{{ __('Capital Included') }}</span>
                                    @endif
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold {{ $plan->compounding ? 'bg-blue-500/20 text-blue-400 border-blue-500/20' : 'bg-white/5 text-text-secondary border-white/5' }} border uppercase tracking-wide">
                                        {{ $plan->compounding ? __('Compounding') : __('Simple Interest') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Description --}}
                            <p class="text-text-secondary text-xs lg:text-sm leading-relaxed mb-6 line-clamp-2"
                                title="{{ __($plan->description) }}">
                                {{ __($plan->description) }}
                            </p>

                            {{-- Tags --}}
                            @if (!empty($plan->interests))
                                <div class="flex flex-wrap gap-2 mb-6">
                                    @foreach ($plan->interests as $tag)
                                        <span
                                            class="px-2.5 py-1 rounded-md bg-white/5 border border-white/5 text-[10px] font-bold text-text-secondary uppercase tracking-wider group-hover:text-white group-hover:bg-white/10 transition-colors">
                                            {{ __($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Metrics (Modified to match cleaner style) --}}
                            <div
                                class="grid grid-cols-3 gap-4 py-4 border-t border-b border-white/5 mb-6 bg-white/[0.02] rounded-xl px-4">
                                <div class="text-center border-r border-white/5">
                                    <span
                                        class="block text-[10px] text-text-secondary uppercase tracking-wider font-bold mb-1">{{ __('ROI') }}</span>
                                    <span
                                        class="block text-base lg:text-lg font-mono font-bold text-accent-primary">{{ $plan->return_percent }}%</span>

                                </div>
                                <div class="text-center border-r border-white/5">
                                    <span
                                        class="block text-[10px] text-text-secondary uppercase tracking-wider font-bold mb-1">{{ __('Term') }}</span>
                                    <span class="block text-base lg:text-lg font-mono font-bold text-white">
                                        {{ $plan->duration }}
                                        <span
                                            class="text-[10px] text-text-secondary font-bold">{{ __($plan->duration_type) }}</span>
                                    </span>

                                </div>
                                <div class="text-center">
                                    <span
                                        class="block text-[10px] text-text-secondary uppercase tracking-wider font-bold mb-1">{{ __('Min') }}</span>
                                    <span
                                        class="block text-base lg:text-lg font-mono font-bold text-white">{{ getSetting('currency_symbol', '$') }}{{ number_format($plan->min_investment, getSetting('decimal_places', 2)) }}</span>
                                </div>
                            </div>

                            {{-- Action --}}
                            <div class="mt-auto">
                                <button type="button"
                                    class="invest-btn w-full py-3.5 rounded-xl bg-white/5 border border-white/10 hover:bg-accent-primary hover:border-accent-primary text-white font-bold transition-all shadow-none hover:shadow-[0_0_30px_rgba(var(--color-accent-primary),0.5)] active:scale-[0.98] cursor-pointer flex items-center justify-center gap-2 group/btn"
                                    data-id="{{ $plan->id }}" data-name="{{ __($plan->name) }}"
                                    data-min="{{ $plan->min_investment }}" data-max="{{ $plan->max_investment }}"
                                    data-roi="{{ $plan->return_percent }}"
                                    data-interval="{{ __('ROI Returned') }} {{ __($plan->return_interval) }}"
                                    data-duration="{{ $plan->duration }} {{ __($plan->duration_type) }}"
                                    data-description="{{ __($plan->description) }}"
                                    data-capital="{{ $plan->capital_returned ? __('Capital Returned') : __('Capital Withheld') }}"
                                    data-compounding="{{ $plan->compounding ? __('Compounding') : __('Simple Interest') }}">
                                    <span>{{ __('Invest Now') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4 transition-transform group-hover/btn:translate-x-1"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Empty State (JS controlled) --}}
            <div id="noPlansFound" class="hidden text-center py-20">
                <div class="inline-flex p-4 rounded-full bg-white/5 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="text-text-secondary">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white">{{ __('No plans match your filters') }}</h3>
                <button onclick="$('#resetFilters').click()"
                    class="mt-2 text-accent-primary text-sm hover:underline">{{ __('Clear all filters') }}</button>
            </div>
        </div>
    </div>
    </div>

    {{-- Investment Modal  --}}
    <div id="investmentModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" id="modalBackdrop"></div>
        <div class="relative min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-lg bg-[#121620] border border-white/10 rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 relative"
                id="modalContent">

                {{-- Decorative Glow --}}
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-primary to-accent-secondary">
                </div>

                <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white mb-2" id="modalPlanName"></h3>
                        <div class="flex flex-wrap items-center gap-2">
                            <span id="modalPlanDuration"
                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/5 border border-white/10 text-text-secondary uppercase tracking-wider"></span>
                            <span id="modalProfitReturnInterval"
                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/5 border border-white/10 text-white uppercase tracking-wider"></span>
                            <span id="modalCompounding"
                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 border border-blue-500/20 text-blue-400 uppercase tracking-wider"></span>
                            <span id="modalCapital"
                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase tracking-wider"></span>
                        </div>
                    </div>
                    <button class="modal-close text-text-secondary hover:text-white p-2 self-start cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-8 py-4 bg-white/[0.02] border-b border-white/5">
                    <p class="text-xs text-text-secondary leading-relaxed italic" id="modalDescription"></p>
                </div>

                <div class="p-8 space-y-8">
                    <div class="flex items-center justify-between p-4 rounded-xl bg-white/5 border border-white/5">
                        <div class="text-center">
                            <span class="text-xs text-text-secondary uppercase">{{ __('ROI Rate') }}</span>
                            <span class="block text-xl font-bold text-accent-primary mt-1" id="modalRoi"></span>
                        </div>
                        <div class="h-8 w-px bg-white/10"></div>
                        <div class="text-center">
                            <span class="text-xs text-text-secondary uppercase">{{ __('Total Return') }}</span>
                            <span class="block text-xl font-bold text-white mt-1" id="modalEstimatedReturn">0.00</span>
                        </div>
                    </div>

                    <form action="{{ route('user.investments.new-validate') }}" method="POST"
                        class="ajax-form space-y-6" data-action="redirect"
                        data-redirect="{{ route('user.investments.index') }}">
                        @csrf
                        <input type="hidden" name="plan_id" id="modalPlanId">

                        <div class="space-y-4">
                            <div class="flex justify-between items-end">
                                <label
                                    class="text-sm text-text-secondary font-medium">{{ __('Investment Amount') }}</label>
                                <div class="text-right">
                                    <span
                                        class="text-[10px] text-text-secondary uppercase font-bold tracking-wider">{{ __('Available Balance') }}</span>
                                    <p class="text-sm font-mono font-bold text-white">
                                        {{ getSetting('currency_symbol', '$') }}{{ number_format(auth()->user()->balance, getSetting('decimal_places', 2)) }}
                                    </p>
                                </div>
                            </div>

                            <div class="relative group">
                                <div
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-text-secondary font-bold text-lg select-none">
                                    {{ getSetting('currency_symbol', '$') }}
                                </div>
                                <input type="number" name="amount" id="amountInput"
                                    class="w-full bg-[#0a0f1d] border border-white/10 rounded-2xl pl-10 pr-20 py-5 text-white text-2xl font-mono font-bold focus:border-accent-primary focus:ring-0 transition-all placeholder:text-white/10"
                                    placeholder="0.00" step="any">
                                <button type="button" id="maxAmountBtn"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-lg bg-accent-primary/20 text-accent-primary text-[10px] font-bold uppercase tracking-wider hover:bg-accent-primary hover:text-white transition-all cursor-pointer">
                                    {{ __('Max') }}
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 rounded-xl bg-white/5 border border-white/5">
                                    <span
                                        class="block text-[10px] text-text-secondary uppercase font-bold mb-1">{{ __('Minimum') }}</span>
                                    <span class="text-sm font-bold text-white" id="modalMinLimit">0.00</span>
                                </div>
                                <div class="p-4 rounded-xl bg-white/5 border border-white/5">
                                    <span
                                        class="block text-[10px] text-text-secondary uppercase font-bold mb-1">{{ __('Maximum') }}</span>
                                    <span class="text-sm font-bold text-white" id="modalMaxLimit">0.00</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-3 sm:py-5 rounded-xl bg-gradient-to-r from-accent-primary to-accent-secondary text-white text-sm sm:text-lg font-bold shadow-xl shadow-accent-primary/20 hover:scale-[1.01] active:scale-[0.99] transition-all cursor-pointer flex items-center justify-center gap-3 group">
                            <span>{{ __('Confirm & Start Investment') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="group-hover:translate-x-1 transition-transform">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            const currencySymbol = "{{ getSetting('currency_symbol', '$') }}";
            let currentRoi = 0;

            // --- FILTERING & SORTING LOGIC ---

            // State
            let activeRiskFilters = [];
            let activeInterestFilters = [];
            let activeIntervalFilters = [];

            // Mobile Drawer Logic
            function toggleDrawer(show) {
                if (show) {
                    $('#mobileFilterDrawer').removeClass('-translate-x-full');
                    $('#mobileDrawerBackdrop').removeClass('hidden');
                } else {
                    $('#mobileFilterDrawer').addClass('-translate-x-full');
                    $('#mobileDrawerBackdrop').addClass('hidden');
                }
            }

            // Attach toggle handlers
            $('button:contains("Sort & Filter")').attr('onclick', '').on('click', function() {
                toggleDrawer(true);
            });
            $('#mobileFilterDrawer button:has(svg)').on('click', function() {
                toggleDrawer(false);
            });
            $('#mobileDrawerBackdrop').attr('onclick', '').on('click', function() {
                toggleDrawer(false);
            });

            // Sort Handler (Desktop & Mobile)
            $('.sort-plans').on('change', function() {
                const sortType = $(this).val();

                // Sync values
                $('.sort-plans').val(sortType);

                const container = $('#plansContainer');
                const cards = container.children('.plan-card').get();

                cards.sort(function(a, b) {
                    const $a = $(a);
                    const $b = $(b);

                    if (sortType === 'min_asc') return $a.data('min') - $b.data('min');
                    if (sortType === 'min_desc') return $b.data('min') - $a.data('min');
                    if (sortType === 'roi_desc') return $b.data('roi') - $a.data('roi');
                    if (sortType === 'duration_asc') return $a.data('duration') - $b.data(
                        'duration');
                    // Default/Featured
                    return $b.data('featured') - $a.data('featured');
                });

                $.each(cards, function(idx, card) {
                    container.append(card);
                });
            });

            // Filter Handlers
            $('.filter-risk').on('change', function() {
                // Determine active risks from ALL checked boxes (desktop + mobile)
                // Filter unique values
                const allChecked = $('.filter-risk:checked').map(function() {
                    return $(this).val();
                }).get();
                activeRiskFilters = [...new Set(allChecked)];

                // Sync checkboxes
                const val = $(this).val();
                const isChecked = $(this).is(':checked');
                $(`.filter-risk[value="${val}"]`).prop('checked', isChecked);

                applyFilters();
            });

            $('.filter-interest').on('click', function() {
                const val = $(this).data('value');
                const isAdding = !activeInterestFilters.includes(val);
                const $buttons = $(`.filter-interest[data-value="${val}"]`);

                if (!isAdding) {
                    activeInterestFilters = activeInterestFilters.filter(i => i !== val);
                    $buttons.removeClass(
                        'bg-accent-primary text-white border-accent-primary active-filter pl-2 pr-2');
                    $buttons.find('.remove-icon').remove();
                } else {
                    activeInterestFilters.push(val);
                    $buttons.addClass(
                        'bg-accent-primary text-white border-accent-primary active-filter flex items-center gap-1 pl-2 pr-1'
                    );
                    // Avoid double icon append
                    $buttons.each(function() {
                        if ($(this).find('.remove-icon').length === 0) {
                            $(this).append(
                                `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="remove-icon text-red-500 bg-white/10 rounded-full p-0.5 ml-1 hover:bg-white/20 transition-colors"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`
                            );
                        }
                    });
                }
                applyFilters();
            });

            $('.filter-interval').on('change', function() {
                const allChecked = $('.filter-interval:checked').map(function() {
                    return $(this).val();
                }).get();
                activeIntervalFilters = [...new Set(allChecked)];

                // Sync checkboxes
                const val = $(this).val();
                const isChecked = $(this).is(':checked');
                $(`.filter-interval[value="${val}"]`).prop('checked', isChecked);

                applyFilters();
            });

            // Reset Handler
            $('.btn-reset-filters, #resetFilters').on('click', function() {
                $('.filter-risk').prop('checked', false);
                $('.filter-interval').prop('checked', false);
                $('.filter-interest').removeClass(
                    'bg-accent-primary text-white border-accent-primary active-filter flex items-center gap-1 pl-2 pr-1 pl-2 pr-2'
                );
                $('.filter-interest .remove-icon').remove();
                activeRiskFilters = [];
                activeInterestFilters = [];
                activeIntervalFilters = [];
                $('.sort-plans').val('featured').trigger('change');
                applyFilters();
            });

            function applyFilters() {
                let visibleCount = 0;
                $('#plansContainer .plan-card').each(function() {
                    const $card = $(this);
                    const cardRisk = $card.data('risk');
                    const cardInterval = $card.data('interval');
                    const cardInterests = $card.data('interests'); // array

                    // Risk Check
                    const passRisk = activeRiskFilters.length === 0 || activeRiskFilters.includes(cardRisk);

                    // Interval Check
                    const passInterval = activeIntervalFilters.length === 0 || activeIntervalFilters
                        .includes(cardInterval);

                    // Interest Check (OR logic: match any selected interest)
                    const passInterest = activeInterestFilters.length === 0 || activeInterestFilters.some(
                        i => cardInterests.includes(i));

                    if (passRisk && passInterval && passInterest) {
                        $card.show();
                        visibleCount++;
                    } else {
                        $card.hide();
                    }
                });

                if (visibleCount === 0) $('#noPlansFound').removeClass('hidden');
                else $('#noPlansFound').addClass('hidden');
            }


            // --- MODAL LOGIC ---

            $('.invest-btn').on('click', function() {
                const btn = $(this);

                $('#modalPlanId').val(btn.data('id'));
                $('#modalPlanName').text(btn.data('name'));
                $('#modalDescription').text(btn.data('description'));
                $('#modalPlanDuration').text(btn.data('duration'));
                $('#modalRoi').text(btn.data('roi') + '%');
                $('#modalProfitReturnInterval').text(btn.data('interval'));
                $('#modalCapital').text(btn.data('capital'));
                $('#modalCompounding').text(btn.data('compounding'));

                // Limits
                $('#modalMinLimit').text(currencySymbol + btn.data('min'));
                $('#modalMaxLimit').text(currencySymbol + btn.data('max'));
                $('#amountInput').attr('placeholder', 'Min: ' + btn.data('min'));

                // State
                currentRoi = parseFloat(btn.data('roi'));
                const userBalance = {{ auth()->user()->balance }};
                const planMax = parseFloat(btn.data('max'));

                // Max Button
                $('#maxAmountBtn').off('click').on('click', function() {
                    const maxPossible = Math.min(userBalance, planMax);
                    $('#amountInput').val(maxPossible).trigger('input');
                });

                $('#investmentModal').removeClass('hidden');
                setTimeout(() => {
                    $('#modalContent').removeClass('scale-95 opacity-0').addClass(
                        'scale-100 opacity-100');
                }, 10);
            });

            $('.modal-close, #modalBackdrop').on('click', function() {
                $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                setTimeout(() => {
                    $('#investmentModal').addClass('hidden');
                    $('#amountInput').val('');
                    $('#modalEstimatedReturn').text('0.00');
                }, 300);
            });

            $('#amountInput').on('input', function() {
                const amount = parseFloat($(this).val()) || 0;
                if (amount > 0) {
                    const profit = amount * (currentRoi / 100);
                    const total = amount + profit;
                    $('#modalEstimatedReturn').text(currencySymbol + total.toFixed(2));
                } else {
                    $('#modalEstimatedReturn').text('0.00');
                }
            });
            // Handle sector query parameter
            const urlParams = new URLSearchParams(window.location.search);
            const sector = urlParams.get('sector');
            if (sector) {
                const $targetButton = $(`.filter-interest[data-value="${sector}"]`);
                if ($targetButton.length > 0) {
                    // Apply filter
                    $targetButton.first().click();

                    // Scroll to marketplace
                    setTimeout(() => {
                        const element = document.getElementById('exploreMarketplace');
                        if (element) {
                            element.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }, 100);
                }
            }
        });
    </script>
@endsection
