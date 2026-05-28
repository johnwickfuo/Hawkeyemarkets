@extends('templates.' . config('site.template') . '.blades.layouts.user')

@section('content')
    <div class="space-y-12 animate-fade-up">

        <!-- Header & Introduction -->
        <div class="space-y-6 max-w-4xl">
            <a href="{{ route('user.dashboard') }}"
                class="inline-flex items-center gap-2 text-text-secondary hover:text-white transition-colors text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
                {{ __('Back to Dashboard') }}
            </a>

            <div class="space-y-4">
                <div class="flex items-center gap-4 flex-wrap">
                    <h1 class="text-4xl md:text-6xl font-heading font-bold text-white">
                        {{ $page_title }}
                    </h1>
                    <div class="flex items-center gap-2 flex-wrap">
                        @if (isset($sector_data['risk_profile']))
                            <span
                                class="px-4 py-1.5 rounded-full text-sm font-mono font-bold
                                {{ $sector_data['risk_profile'] == 'growth' ? 'bg-error/10 text-error border border-error/20' : '' }}
                                {{ $sector_data['risk_profile'] == 'balanced' ? 'bg-warning/10 text-warning border border-warning/20' : '' }}
                                {{ $sector_data['risk_profile'] == 'conservative' ? 'bg-success/10 text-success border border-success/20' : '' }}
                            ">
                                {{ __($sector_data['risk_profile']) }}
                            </span>
                        @endif

                        @if (isset($sector_data['risk_level']))
                            <div
                                class="flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-bold border
                                {{ $sector_data['risk_level'] == 'high' ? 'bg-error/5 text-error border-error/20' : '' }}
                                {{ $sector_data['risk_level'] == 'medium' ? 'bg-warning/5 text-warning border-warning/20' : '' }}
                                {{ $sector_data['risk_level'] == 'low' ? 'bg-success/5 text-success border-success/20' : '' }}
                            ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="opacity-70">
                                    <path
                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                    <line x1="12" y1="9" x2="12" y2="13" />
                                    <line x1="12" y1="17" x2="12.01" y2="17" />
                                </svg>
                                {{ __('Risk') }}: {{ __($sector_data['risk_level']) }}
                            </div>
                        @endif

                        @if (isset($sector_data['volatility']))
                            <div
                                class="flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-bold border
                                {{ $sector_data['volatility'] == 'high' ? 'bg-error/5 text-error border-error/20' : '' }}
                                {{ $sector_data['volatility'] == 'medium' ? 'bg-warning/5 text-warning border-warning/20' : '' }}
                                {{ $sector_data['volatility'] == 'low' ? 'bg-success/5 text-success border-success/20' : '' }}
                            ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="opacity-70">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                                </svg>
                                {{ __('Volatility') }}: {{ __($sector_data['volatility']) }}
                            </div>
                        @endif


                        @if (isset($sector_data['investment_goal']))
                            <div
                                class="flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-bold bg-white/5 text-text-secondary border border-white/10 uppercase tracking-tight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="opacity-70">
                                    <circle cx="12" cy="12" r="10" />
                                    <circle cx="12" cy="12" r="6" />
                                    <circle cx="12" cy="12" r="2" />
                                </svg>
                                {{ __('Goal') }}: {{ __($sector_data['investment_goal']) }}
                            </div>
                        @endif
                    </div>
                </div>
                <p class="text-xl text-text-secondary font-light leading-relaxed max-w-3xl">
                    {{ __($sector_data['context']) }}
                </p>
            </div>
        </div>

        <!-- Primary Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Volume -->
            <div
                class="p-6 rounded-2xl bg-secondary-dark border border-white/5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                        <line x1="12" y1="22.08" x2="12" y2="12" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-text-secondary uppercase tracking-widest mb-2">{{ __('Total Volume') }}
                </p>
                <p class="font-mono text-3xl font-bold text-white">
                    {{ getSetting('currency_symbol', '$') }}{{ number_format($sector_data['metrics']['total_invested'], 2) }}
                </p>
                <div class="mt-4 h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-white/20 w-3/4"></div>
                </div>
            </div>

            <!-- Earnings -->
            <div
                class="p-6 rounded-2xl bg-secondary-dark border border-white/5 relative overflow-hidden group hover:border-success/30 transition-colors">
                <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-text-secondary uppercase tracking-widest mb-2">{{ __('Earnings Paid') }}
                </p>
                <p class="font-mono text-3xl font-bold text-success">
                    +{{ getSetting('currency_symbol', '$') }}{{ number_format($sector_data['metrics']['earnings_generated'], 2) }}
                </p>
                <div class="mt-4 h-1 w-full bg-success/10 rounded-full overflow-hidden">
                    <div class="h-full bg-success w-1/2"></div>
                </div>
            </div>

            <!-- Investors -->
            <div
                class="p-6 rounded-2xl bg-secondary-dark border border-white/5 relative overflow-hidden group hover:border-accent-primary/30 transition-colors">
                <div
                    class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity text-accent-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-text-secondary uppercase tracking-widest mb-2">
                    {{ __('Active Investors') }}</p>
                <p class="font-mono text-3xl font-bold text-accent-primary">
                    {{ $sector_data['metrics']['active_investors'] }}</p>
                <div class="mt-4 flex -space-x-2">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="w-6 h-6 rounded-full bg-white/10 border-2 border-secondary-dark"></div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column: Story Flow -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Step 1: How It Works -->
                <div class="space-y-4">
                    <h2 class="font-heading text-2xl font-bold text-white flex items-center gap-3">
                        <span class="text-accent-primary">01.</span> {{ __('How It Works') }}
                    </h2>
                    <div class="p-8 rounded-2xl bg-secondary-dark border border-white/5">
                        <div class="grid sm:grid-cols-2 gap-8 relative">
                            @foreach ($sector_data['how_it_works'] as $index => $step)
                                <div class="relative z-10">
                                    <div
                                        class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center font-mono font-bold text-white mb-4 group-hover:bg-accent-primary group-hover:border-accent-primary transition-colors">
                                        {{ $index + 1 }}
                                    </div>
                                    <p class="text-text-secondary leading-relaxed">{{ __($step) }}</p>
                                </div>
                            @endforeach
                            <!-- Decoration Line -->
                            <div class="absolute top-5 left-5 right-5 h-[1px] bg-white/5 -z-0 hidden md:block"></div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Transparency (Earnings) -->
                <div class="space-y-4">
                    <h2 class="font-heading text-2xl font-bold text-white flex items-center gap-3">
                        <span class="text-accent-secondary">02.</span> {{ __('Source of Returns') }}
                    </h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        @foreach ($sector_data['earnings_generated_from'] as $source => $description)
                            <div
                                class="p-5 rounded-xl bg-secondary-dark border border-white/5 hover:border-accent-secondary/30 transition-all cursor-default group">
                                <p
                                    class="font-bold text-white mb-2 capitalize group-hover:text-accent-secondary transition-colors">
                                    {{ __($source) }}</p>
                                <p class="text-sm text-text-secondary">{{ __($description) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Step 3: Validation (Why/Suitability) -->
                <div class="space-y-4">
                    <h2 class="font-heading text-2xl font-bold text-white flex items-center gap-3">
                        <span class="text-success">03.</span> {{ __('Investor Fit') }}
                    </h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Why -->
                        <div class="p-6 rounded-xl bg-secondary-dark border border-white/5">
                            <h3 class="font-bold text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-accent-success" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('Why Invest?') }}
                            </h3>
                            <ul class="space-y-3">
                                @foreach ($sector_data['psychology'] as $point)
                                    <li class="flex items-start gap-3 text-sm text-text-secondary">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white/20 mt-1.5"></span>
                                        {{ __($point) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Who -->
                        <div class="p-6 rounded-xl bg-secondary-dark border border-white/5">
                            <h3 class="font-bold text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('Who is this for?') }}
                            </h3>
                            <div class="space-y-4">
                                <div class="bg-white/5 p-3 rounded-lg">
                                    <p class="text-xs font-bold text-accent-primary uppercase tracking-wider mb-1">
                                        {{ __('Ideal For') }}</p>
                                    <p class="text-sm text-white">{{ __($sector_data['ideal_for']) }}</p>
                                </div>
                                <div class="p-2">
                                    <p class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-1">
                                        {{ __('Not For') }}</p>
                                    <p class="text-sm text-text-secondary">{{ __($sector_data['not_ideal']) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Investment Action -->
            <div class="space-y-6">
                <div class="sticky top-24 space-y-6">
                    <div class="flex items-center justify-between border-b border-white/5 pb-4">
                        <h2 class="font-heading text-xl font-bold text-white">{{ __('Recommended Plans') }}</h2>
                        <a href="{{ route('user.investments.new', ['sector' => $sector]) }}"
                            class="text-xs font-bold text-accent-primary hover:text-white transition-colors flex items-center gap-1">
                            {{ __('View All') }} <span
                                class="bg-white/10 px-1.5 py-0.5 rounded text-[10px]">{{ $recommended_plans->count() }}</span>
                        </a>
                    </div>

                    @forelse($recommended_plans->take(3) as $plan)
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
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
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
                                        @if ($plan->relevance_rank === 1)
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-accent-primary/20 text-accent-primary border border-accent-primary/20 uppercase tracking-wide">
                                                {{ __('Top Pick') }}
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
                                        @endif
                                    </div>
                                </div>

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
                                            {{ $plan->duration }} <span
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
                    @empty
                        <div
                            class="text-center py-12 rounded-2xl bg-secondary-dark/50 border border-white/5 border-dashed">
                            <svg class="w-12 h-12 text-white/10 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                </path>
                            </svg>
                            <p class="text-text-secondary mb-2">{{ __('No plans currently open.') }}</p>
                            <p class="text-xs text-text-secondary/50">{{ __('Check back later for new opportunities.') }}
                            </p>
                        </div>
                    @endforelse

                    <div class="text-center pt-4">
                        <a href="{{ route('user.investments.new', ['sector' => $sector]) }}"
                            class="inline-flex items-center gap-2 text-sm text-text-secondary hover:text-white transition-colors group">
                            {{ __('View Full Catalog') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="group-hover:translate-x-1 transition-transform">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Soft CTA: Premium Section -->
    <div class="pt-24 pb-12">
        <div
            class="relative bg-gradient-to-br from-secondary-dark to-[#05070a] border border-white/10 rounded-[3rem] px-10 py-16 md:px-20 md:py-24 text-center space-y-10 overflow-hidden shadow-2xl">
            {{-- Massive Background Title for impact --}}
            <div
                class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden h-full">
                <span
                    class="text-[15vw] font-black text-white/[0.02] whitespace-nowrap leading-none uppercase tracking-tighter transform -rotate-12 translate-y-10">
                    {{ __('Marketplace') }}
                </span>
            </div>

            {{-- Glowing Orbs --}}
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-accent-primary/10 rounded-full blur-[100px]"></div>
            <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-accent-secondary/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 space-y-4">
                <h3 class="text-4xl md:text-6xl font-heading font-black text-white tracking-tighter">
                    {{ __('Not sure yet?') }}
                </h3>
                <p class="text-lg md:text-2xl text-text-secondary font-medium max-w-2xl mx-auto leading-relaxed">
                    {{ __('Discover more opportunities across all sectors.') }}
                </p>
            </div>

            <div class="relative z-10 pt-4">
                <a href="{{ route('user.investments.new') }}"
                    class="inline-flex items-center gap-3 px-10 py-5 rounded-2xl bg-white text-black font-black text-lg hover:bg-accent-primary hover:text-white transition-all duration-500 shadow-[0_20px_50px_-10px_rgba(255,255,255,0.15)] hover:shadow-[0_20px_60px_-10px_rgba(var(--color-accent-primary),0.4)] hover:-translate-y-2 group">
                    {{ __('Explore all investment plans') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                        stroke-linejoin="round" class="group-hover:translate-x-2 transition-transform duration-500">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </a>
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
        });
    </script>
@endsection
