@extends('templates.' . config('site.template') . '.blades.layouts.front')

@section('content')
    {{-- HERO SECTION --}}
    <section class="relative pt-32 pb-24 overflow-hidden bg-[#050505]">
        {{-- Atmospheric Background --}}
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[radial-gradient(circle_at_50%_0%,rgba(239,68,68,0.08),transparent_70%)]">
            </div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff02_1px,transparent_1px),linear-gradient(to_bottom,#ffffff02_1px,transparent_1px)] bg-[size:60px_60px]">
            </div>
            <div
                class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')] opacity-[0.03] pointer-events-none">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 mb-8 animate-fade-in-up">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                    <span
                        class="text-[10px] font-black text-red-500 uppercase tracking-widest">{{ __('Investment Transparency') }}</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white mb-8 leading-tight tracking-tighter">
                    {{ __('Risk') }} <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-red-600">{{ __('Assessment.') }}</span>
                </h1>
                <p class="text-xl text-text-secondary leading-relaxed mb-12 max-w-2xl mx-auto">
                    {{ __('Market participation involves inherent volatility. At :site_name, we believe informed investors are resilient investors. Carefully review our risk framework before engaging with capital instruments.', ['site_name' => getSetting('name')]) }}
                </p>
            </div>
        </div>
    </section>

    {{-- RISK CATEGORIES --}}
    <section class="py-24 relative overflow-hidden bg-[#050505] border-t border-white/5">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Category 1: Market Volatility --}}
                <div
                    class="p-10 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 hover:border-red-500/30 transition-all duration-500 group">
                    <div
                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-red-500 mb-8 group-hover:bg-red-500 group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">{{ __('Capital Volatility') }}</h3>
                    <p class="text-text-secondary text-sm leading-relaxed">
                        {{ __('Asset values can fluctuate rapidly. Capital at risk includes the potential loss of the entire principal amount invested through our platform.') }}
                    </p>
                </div>

                {{-- Category 2: Technical Risks --}}
                <div
                    class="p-10 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 hover:border-red-500/30 transition-all duration-500 group">
                    <div
                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-red-500 mb-8 group-hover:bg-red-500 group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">{{ __('Execution Lag') }}</h3>
                    <p class="text-text-secondary text-sm leading-relaxed">
                        {{ __('High-frequency market environments may experience latency. Price slippage during execution is a factor in high-volatility events.') }}
                    </p>
                </div>

                {{-- Category 3: Regulatory Risks --}}
                <div
                    class="p-10 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 hover:border-red-500/30 transition-all duration-500 group">
                    <div
                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-red-500 mb-8 group-hover:bg-red-500 group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.022.547l-2.387 2.387a2 2 0 102.828 2.828l2.387-2.387a2 2 0 00.547-1.022l.477-2.387a6 6 0 00-.517-3.86l-.158-.318a6 6 0 01-.517-3.86l.477-2.387a2 2 0 00-.547-1.022L2.05 6.05a2 2 0 10-2.828-2.828l2.387 2.387a2 2 0 001.022.547l2.387.477a6 6 0 003.86-.517l.318-.158a6 6 0 013.86-.517L17.95 8.79a2 2 0 001.022-.547l2.387-2.387a2 2 0 10-2.828-2.828l-2.387 2.387a2 2 0 00-.547 1.022l-.477 2.387a6 6 0 00.517 3.86l.158.318a6 6 0 01.517 3.86l-.477 2.387a2 2 0 00.547 1.022l2.387 2.387a2 2 0 102.828 2.828l-2.387-2.387z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">{{ __('Global Shifts') }}</h3>
                    <p class="text-text-secondary text-sm leading-relaxed">
                        {{ __('Shifting international regulations can impact instrument availability and capital liquidity. We continuously recalibrate to these changes.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- DETAILED CONTENT --}}
    <section class="py-24 relative overflow-hidden bg-black/20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="prose prose-invert prose-red max-w-none">
                    <div class="space-y-16">
                        {{-- Section 1 --}}
                        <div class="space-y-6">
                            <h2 class="text-3xl font-black text-white flex items-center gap-4">
                                <span class="w-2 h-8 bg-red-500 rounded-full"></span>
                                {{ __('1. General Investment Risk') }}
                            </h2>
                            <p class="text-text-secondary leading-relaxed">
                                {{ __('The value of investments and the income derived from them can fall as well as rise, and you may not get back the amount originally invested. Past performance is not a reliable indicator of future results.') }}
                            </p>
                        </div>

                        {{-- Section 2 --}}
                        <div class="space-y-6">
                            <h2 class="text-3xl font-black text-white flex items-center gap-4">
                                <span class="w-2 h-8 bg-red-500 rounded-full"></span>
                                {{ __('2. Liquidity Constraints') }}
                            </h2>
                            <p class="text-text-secondary leading-relaxed">
                                {{ __('Certain capital instruments, especially those in emerging markets or alternative assets, may suffer from low liquidity. This may impact your ability to exit positions at your desired price or timeframe.') }}
                            </p>
                        </div>

                        {{-- Section 3 --}}
                        <div class="space-y-6">
                            <h2 class="text-3xl font-black text-white flex items-center gap-4">
                                <span class="w-2 h-8 bg-red-500 rounded-full"></span>
                                {{ __('3. Counterparty Vulnerability') }}
                            </h2>
                            <p class="text-text-secondary leading-relaxed">
                                {{ __('While we utilize segregated institutional custodians, some instruments involve third-party counterparties. The failure of such parties could result in severe losses to your portfolio.') }}
                            </p>
                        </div>

                        {{-- Section 4 --}}
                        <div class="space-y-6">
                            <h2 class="text-3xl font-black text-white flex items-center gap-4">
                                <span class="w-2 h-8 bg-red-500 rounded-full"></span>
                                {{ __('4. No Financial Advice') }}
                            </h2>
                            <p
                                class="text-text-secondary leading-relaxed font-bold border-l-4 border-red-500/50 pl-6 italic">
                                {{ __(':site_name provides execution-only services and market data access. Nothing on this platform should be construed as financial, legal, or tax advice. Consult with a certified professional before committing capital.', ['site_name' => getSetting('name')]) }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-20 p-8 rounded-3xl bg-red-500/5 border border-red-500/10 text-center">
                        <p class="text-sm font-bold text-red-500 uppercase tracking-widest mb-2">
                            {{ __('Institutional Warning') }}
                        </p>
                        <p class="text-white font-black">
                            {{ __('Capital is definitively at risk.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 relative overflow-hidden bg-[#050505] border-t border-white/5">
        <div class="container mx-auto px-4 relative z-10 text-center">
            <div
                class="max-w-4xl mx-auto p-12 lg:p-20 rounded-[3rem] bg-gradient-to-b from-white/[0.05] to-transparent border border-white/5 relative overflow-hidden">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-8 leading-tight">
                    {{ __('Navigate with') }} <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-red-600">{{ __('Strategic Precision.') }}</span>
                </h2>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('user.register') }}"
                        class="px-10 py-5 bg-red-500 text-white rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-red-600 transition-all shadow-lg shadow-red-500/20 hover:-translate-y-1">
                        {{ __('Acknowledge & Register') }}
                    </a>
                    <a href="{{ route('contact') }}"
                        class="px-10 py-5 bg-white/5 border border-white/10 text-white rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-white/10 transition-all">
                        {{ __('Risk Advisory') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
