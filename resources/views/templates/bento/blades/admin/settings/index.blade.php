@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div
        class="bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col lg:flex-row backdrop-blur-xl min-h-[calc(100vh-160px)]">
        {{-- Settings Sidebar --}}
        <div
            class="w-full lg:w-80 bg-secondary/60 shrink-0 border-b lg:border-b-0 lg:border-r border-white/5 flex flex-col relative group">
            <div
                class="absolute inset-0 bg-gradient-to-b from-accent-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
            </div>
            <div class="relative z-10 flex-1 py-8 px-6 lg:px-8 overflow-y-auto custom-scrollbar">
                @include("templates.$template.blades.admin.settings.partials.sidebar")
            </div>
        </div>

        {{-- Settings Content --}}
        <div class="flex-1 p-6 md:p-10 lg:p-12 space-y-8 overflow-y-auto custom-scrollbar">

            {{-- Features Guarantee --}}

            {{-- Features Guarantee --}}
            <div
                class="bg-secondary relative border border-accent-primary/20 rounded-2xl p-6 overflow-hidden flex items-start gap-4 shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 to-transparent pointer-events-none z-0">
                </div>
                <div
                    class="w-10 h-10 rounded-xl bg-accent-primary/10 border border-accent-primary/20 flex items-center justify-center text-accent-primary shrink-0 relative z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="relative z-10 flex-1">
                    <h4 class="text-sm font-bold text-accent-primary mb-1">{{ __('Features Guarantee') }}</h4>
                    <p class="text-sm text-slate-400 leading-relaxed mb-3">
                        If you notice any missing features, you can contact us to request them. General scope features are
                        added to the software core at no additional cost. Urgent or custom features will be charged for
                        customization.
                    </p>
                    <a href="https://lozand.com/contact" target="_blank"
                        class="text-xs font-bold text-accent-primary hover:text-white transition-colors flex items-center gap-1.5 group/link">
                        {{ __('Contact Support') }}
                        <svg class="w-3.5 h-3.5 transform group-hover/link:translate-x-0.5 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Disclaimer --}}
            <div
                class="bg-secondary relative border border-amber-500/20 rounded-2xl p-6 overflow-hidden flex items-start gap-4 shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent pointer-events-none z-0">
                </div>
                <div
                    class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-500 shrink-0 relative z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="relative z-10 flex-1">
                    <h4 class="text-sm font-bold text-amber-400 mb-1">{{ __('Important Notice') }}</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        The solutions listed below are not ready-made plugins. Each system is developed exclusively on a
                        custom
                        basis and built to match your specific requirements after payment confirmation. As these are bespoke
                        developments, live demos are not available.
                    </p>
                </div>
            </div>


            {{-- Premium Bento Design Solutions Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 items-start">
                {{-- Polymarket Bot Development (Full Row) --}}
                <div
                    class="bg-secondary relative border border-white/5 rounded-2xl overflow-hidden hover:border-emerald-500/30 transition-all duration-500 flex flex-col md:flex-row items-center md:col-span-2 xl:col-span-3 group shadow-xl">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 via-transparent to-transparent pointer-events-none z-0">
                    </div>

                    {{-- Logo, Title & Description (Stacked) --}}
                    <div class="relative z-10 p-6 flex flex-col justify-center gap-4 flex-[1.5]">
                        <div class="flex items-center gap-6">
                            <div
                                class="shrink-0 flex items-center justify-center p-2 bg-white/5 rounded-xl border border-white/5 group-hover:border-emerald-500/30 transition-all duration-500">
                                <img loading="lazy" decoding="async" src="{{ asset('assets/images/polymarket.png') }}" alt="Polymarket"
                                    class="h-8 w-auto object-contain">
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Polymarket Bot') }}</h3>
                            </div>
                        </div>
                        <p
                            class="text-[10px] text-slate-400 font-medium leading-relaxed max-w-md opacity-80 uppercase tracking-wider">
                            {{ __('Custom polymarket bot development.') }}
                        </p>
                    </div>

                    {{-- Price & Action --}}
                    <div
                        class="relative z-10 p-6 flex flex-col sm:flex-row items-center gap-6 border-t md:border-t-0 md:border-l border-white/5 bg-white/[0.01] w-full md:w-auto">
                        <div class="text-center md:text-right">
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">
                                {{ __('Starting Price') }}</p>
                            <p class="text-2xl font-bold text-emerald-400">$16,000</p>
                        </div>
                        <a href="https://lozand.com/custom-engine-development/polymarket-bot" target="_blank"
                            class="h-12 px-8 rounded-xl bg-white/5 border border-white/10 hover:bg-emerald-500 hover:text-white text-white transition-all font-bold text-[10px] uppercase tracking-widest flex items-center gap-3">
                            {{ __('Buy Now') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
                {{-- Crypto Trading Engine --}}
                <div
                    class="bg-secondary relative border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors flex flex-col group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent pointer-events-none z-0">
                    </div>
                    <div
                        class="absolute top-0 right-0 -mt-10 -mr-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none z-0">
                    </div>

                    {{-- Header --}}
                    <div class="relative z-10 p-6 border-b border-white/5 flex flex-col items-start gap-4">
                        <div class="w-full flex items-start justify-between gap-3">
                            <h3 class="text-xl font-bold text-white leading-tight">
                                {{ __('Crypto Trading Engine') }}
                            </h3>
                        </div>

                    </div>

                    {{-- Metrics --}}
                    <div class="relative z-10 p-6 grid grid-cols-2 gap-4 bg-white/[0.01]">
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">
                                {{ __('Starting Price') }}</p>
                            <p class="text-lg font-bold text-indigo-400">$120,000</p>
                        </div>
                    </div>

                    {{-- Features --}}
                    <div class="relative z-10 p-6 border-t border-white/5 flex-1 flex flex-col justify-start">

                        <ul class="space-y-3 mt-auto">
                            <li class="flex items-center justify-between text-sm">
                                <span
                                    class="text-slate-400 font-bold tracking-tight">{{ __('Aggregated Liquidity Pools') }}</span>
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </li>
                            <li class="flex items-center justify-between text-sm">
                                <span
                                    class="text-slate-400 font-bold tracking-tight">{{ __('Advanced Futures & Options') }}</span>
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </li>
                            <li class="flex items-center justify-between text-sm">
                                <span
                                    class="text-slate-400 font-bold tracking-tight">{{ __('Ultra-low Latency Matching') }}</span>
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </li>
                        </ul>
                    </div>

                    {{-- Actions --}}
                    <div
                        class="relative z-10 p-4 border-t border-white/5 bg-secondary flex justify-between gap-3 mt-auto transition-colors group-hover:bg-white/[0.02]">
                        <a href="https://lozand.com/custom-engine-development/crypto-trading-engine" target="_blank"
                            class="flex-1 py-2 text-sm text-white font-medium bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 hover:border-indigo-500/30 hover:text-indigo-400 transition-all flex justify-center items-center gap-2">
                            {{ __('Buy') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Global Forex Infrastructure --}}
                <div
                    class="bg-secondary relative border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors flex flex-col group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent pointer-events-none z-0">
                    </div>

                    {{-- Header --}}
                    <div class="relative z-10 p-6 border-b border-white/5 flex flex-col items-start gap-4">
                        <div class="w-full flex items-start justify-between gap-3">
                            <h3 class="text-xl font-bold text-white leading-tight">
                                {{ __('Forex Engine') }}
                            </h3>
                        </div>
                    </div>

                    {{-- Metrics --}}
                    <div class="relative z-10 p-6 grid grid-cols-2 gap-4 bg-white/[0.01]">

                        <div>
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">
                                {{ __('Starting Price') }}</p>
                            <p class="text-lg font-bold text-emerald-400">$95,000</p>
                        </div>
                    </div>

                    {{-- Features --}}
                    <div class="relative z-10 p-6 border-t border-white/5 flex-1 flex flex-col justify-start">

                        <ul class="space-y-3 mt-auto">
                            <li class="flex items-center justify-between text-sm">
                                <span
                                    class="text-slate-400 font-bold tracking-tight">{{ __('Lp Bridge & Connectivity') }}</span>
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </li>
                            <li class="flex items-center justify-between text-sm">
                                <span
                                    class="text-slate-400 font-bold tracking-tight">{{ __('Deep MT4/MT5 Integration') }}</span>
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </li>
                            <li class="flex items-center justify-between text-sm">
                                <span
                                    class="text-slate-400 font-bold tracking-tight">{{ __('Institutional Risk Manager') }}</span>
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </li>
                        </ul>
                    </div>

                    {{-- Actions --}}
                    <div
                        class="relative z-10 p-4 border-t border-white/5 bg-secondary flex justify-between gap-3 mt-auto transition-colors group-hover:bg-white/[0.02]">
                        <a href="https://lozand.com/custom-engine-development/forex-engine" target="_blank"
                            class="flex-1 py-2 text-sm text-white font-medium bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 hover:border-emerald-500/30 hover:text-emerald-400 transition-all flex justify-center items-center gap-2">
                            {{ __('Buy') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Stock & ETF Engine --}}
                <div
                    class="bg-secondary relative border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors flex flex-col group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-transparent pointer-events-none z-0">
                    </div>

                    {{-- Header --}}
                    <div class="relative z-10 p-6 border-b border-white/5 flex flex-col items-start gap-4">
                        <div class="w-full flex items-start justify-between gap-3">
                            <h3 class="text-xl font-bold text-white leading-tight">
                                {{ __('Stock & ETF Engine') }}
                            </h3>

                        </div>
                    </div>

                    {{-- Metrics --}}
                    <div class="relative z-10 p-6 grid grid-cols-2 gap-4 bg-white/[0.01]">

                        <div>
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">
                                {{ __('Starting Price') }}</p>
                            <p class="text-lg font-bold text-violet-400">$150,000</p>
                        </div>
                    </div>

                    {{-- Features --}}
                    <div class="relative z-10 p-6 border-t border-white/5 flex-1 flex flex-col justify-start">

                        <ul class="space-y-3 mt-auto">
                            <li class="flex items-center justify-between text-sm">
                                <span
                                    class="text-slate-400 font-bold tracking-tight">{{ __('Global Order Routing (OMS)') }}</span>
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </li>
                            <li class="flex items-center justify-between text-sm">
                                <span
                                    class="text-slate-400 font-bold tracking-tight">{{ __('Real-time Inventory Sync') }}</span>
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </li>
                            <li class="flex items-center justify-between text-sm">
                                <span
                                    class="text-slate-400 font-bold tracking-tight">{{ __('Brokerage Integration') }}</span>
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </li>
                        </ul>
                    </div>

                    {{-- Actions --}}
                    <div
                        class="relative z-10 p-4 border-t border-white/5 bg-secondary flex justify-between gap-3 mt-auto transition-colors group-hover:bg-white/[0.02]">
                        <a href="https://lozand.com/custom-engine-development/stock" target="_blank"
                            class="flex-1 py-2 text-sm text-white font-medium bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 hover:border-violet-500/30 hover:text-violet-400 transition-all flex justify-center items-center gap-2">
                            {{ __('Buy') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Custom Mobile Solutions --}}
                <div
                    class="bg-secondary relative border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors flex flex-col group md:col-span-2 xl:col-span-1">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent pointer-events-none z-0">
                    </div>

                    {{-- Header --}}
                    <div class="relative z-10 p-6 border-b border-white/5 flex flex-col items-start gap-4">
                        <div class="w-full flex items-start justify-between gap-3">
                            <h3 class="text-xl font-bold text-white leading-tight">
                                {{ __('Custom App Development') }}
                            </h3>

                        </div>
                    </div>

                    {{-- Metrics --}}
                    <div class="relative z-10 p-6 grid grid-cols-2 gap-4 bg-white/[0.01]">

                        <div>
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">
                                {{ __('Starting Price') }}</p>
                            <p class="text-lg font-bold text-amber-400">$25,000</p>
                        </div>
                    </div>

                    {{-- Features --}}
                    <div class="relative z-10 p-6 border-t border-white/5 flex-1 flex flex-col justify-start">

                        <ul class="space-y-3 mt-auto">
                            <li class="flex items-center justify-between text-sm">
                                <span
                                    class="text-slate-400 font-bold tracking-tight">{{ __('Enterprise & SaaS Native Apps') }}</span>
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </li>
                            <li class="flex items-center justify-between text-sm">
                                <span
                                    class="text-slate-400 font-bold tracking-tight">{{ __('Custom Mobile Solutions') }}</span>
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </li>
                            <li class="flex items-center justify-between text-sm">
                                <span
                                    class="text-slate-400 font-bold tracking-tight">{{ __('Bespoke UX/UI Orchestration') }}</span>
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </li>
                        </ul>
                    </div>

                    {{-- Actions --}}
                    <div
                        class="relative z-10 p-4 border-t border-white/5 bg-secondary flex justify-between gap-3 mt-auto transition-colors group-hover:bg-white/[0.02]">
                        <a href="https://lozand.com/custom-engine-development/custom-app" target="_blank"
                            class="flex-1 py-2 text-sm text-white font-medium bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 hover:border-amber-500/30 hover:text-amber-400 transition-all flex justify-center items-center gap-2">
                            {{ __('Buy') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Token and Smart Contract Development (Wide Tile - spans 2 columns) --}}
                <div
                    class="bg-secondary border border-white/5 rounded-[2.5rem] p-8 relative overflow-hidden group hover:border-pink-500/40 transition-all duration-500 shadow-xl flex flex-col md:col-span-2 xl:col-span-2 xl:row-span-1">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-pink-500/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                    </div>

                    <div class="relative z-10 flex flex-col md:flex-row gap-8 flex-1">
                        <div class="flex-1">
                            <div
                                class="w-16 h-16 rounded-2xl bg-pink-500/10 border border-pink-500/20 flex items-center justify-center text-pink-400 mb-8 shadow-2xl group-hover:bg-pink-500 group-hover:text-white transition-all duration-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3 tracking-tight">
                                {{ __('Blockchain & Token Engineering') }}</h3>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.25em] mb-6">
                                {{ __('Web3 Infrastructure') }}</p>

                            <p
                                class="text-[11px] text-slate-400 font-medium leading-relaxed max-w-sm opacity-80 mb-6 uppercase tracking-wider">
                                {{ __('Deployment of audited, secure, and gas-efficient blockchain protocols for institutional asset tokenization.') }}
                            </p>
                        </div>

                        <div class="flex-1">
                            <ul class="space-y-4 pt-4 md:pt-16">
                                <li class="flex items-center gap-4 group/li">
                                    <div
                                        class="w-2 h-2 rounded-full bg-pink-500 group-hover/li:scale-150 transition-transform">
                                    </div>
                                    <span
                                        class="text-xs text-slate-400 font-bold">{{ __('Custom ERC-20 / BEP-20 / Solana Tokens') }}</span>
                                </li>
                                <li class="flex items-center gap-4 group/li">
                                    <div
                                        class="w-2 h-2 rounded-full bg-pink-500 group-hover/li:scale-150 transition-transform">
                                    </div>
                                    <span
                                        class="text-xs text-slate-400 font-bold">{{ __('Secure Vault & Staking Contracts') }}</span>
                                </li>
                                <li class="flex items-center gap-4 group/li">
                                    <div
                                        class="w-2 h-2 rounded-full bg-pink-500 group-hover/li:scale-150 transition-transform">
                                    </div>
                                    <span
                                        class="text-xs text-slate-400 font-bold">{{ __('L2 Scaling & Bridge Implementation') }}</span>
                                </li>
                                <li class="flex items-center gap-4 group/li">
                                    <div
                                        class="w-2 h-2 rounded-full bg-pink-500 group-hover/li:scale-150 transition-transform">
                                    </div>
                                    <span
                                        class="text-xs text-slate-400 font-bold">{{ __('Automated Market Maker (AMM) Logic') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="relative z-10 pt-8 border-t border-white/5 flex items-center justify-between">
                        <div>
                            <span
                                class="text-[9px] text-slate-500 uppercase font-bold tracking-widest">{{ __('Starting At') }}</span>
                            <div class="text-2xl font-bold text-white">$40,000</div>
                        </div>
                        <a href="https://lozand.com/custom-engine-development/blockchain" target="_blank"
                            class="h-12 px-8 rounded-xl bg-white/5 border border-white/10 hover:bg-pink-500 hover:text-white text-white transition-all font-bold text-[10px] uppercase tracking-widest flex items-center gap-2">
                            {{ __('Buy') }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
