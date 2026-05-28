@extends('templates.' . config('site.template') . '.blades.layouts.front')

@section('content')
    {{-- HERO SECTION --}}
    <section class="relative pt-32 pb-24 overflow-hidden bg-[#050505]">
        {{-- Atmospheric Background --}}
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[radial-gradient(circle_at_50%_0%,rgba(16,185,129,0.08),transparent_70%)]">
            </div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff02_1px,transparent_1px),linear-gradient(to_bottom,#ffffff02_1px,transparent_1px)] bg-[size:60px_60px]">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 mb-8 animate-fade-in-up">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span
                        class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">{{ __('Institutional Integrity') }}</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white mb-8 leading-tight tracking-tighter">
                    {{ __('Regulatory') }} <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-600">{{ __('Framework.') }}</span>
                </h1>
                <p class="text-xl text-text-secondary leading-relaxed mb-12 max-w-2xl mx-auto">
                    {{ __(':site_name operates at the intersection of technological innovation and rigorous financial oversight. Our infrastructure is engineered to exceed global compliance standards.', ['site_name' => getSetting('name')]) }}
                </p>
            </div>
        </div>
    </section>

    {{-- GOVERNANCE PILLARS --}}
    <section class="py-24 relative overflow-hidden bg-[#050505] border-t border-white/5">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Pillar 1: KYC/AML --}}
                <div
                    class="p-10 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 hover:border-emerald-500/30 transition-all duration-500 group">
                    <div
                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-emerald-500 mb-8 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">{{ __('Anti-Money Laundering') }}</h3>
                    <p class="text-text-secondary text-sm leading-relaxed">
                        {{ __('Our multi-tiered AML/CTF framework utilizes advanced on-chain surveillance and behavioral analytics to prevent illicit capital flows.') }}
                    </p>
                </div>

                {{-- Pillar 2: Data Protection --}}
                <div
                    class="p-10 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 hover:border-emerald-500/30 transition-all duration-500 group">
                    <div
                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-emerald-500 mb-8 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">{{ __('Data Sovereignty') }}</h3>
                    <p class="text-text-secondary text-sm leading-relaxed">
                        {{ __('End-to-end encryption for all sensitive user data, compliant with GDPR and international privacy standards for financial institutions.') }}
                    </p>
                </div>

                {{-- Pillar 3: Asset Protection --}}
                <div
                    class="p-10 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 hover:border-emerald-500/30 transition-all duration-500 group">
                    <div
                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-emerald-500 mb-8 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 01-6.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">{{ __('Asset Segregation') }}</h3>
                    <p class="text-text-secondary text-sm leading-relaxed">
                        {{ __('User funds are maintained in segregated accounts, isolated from operational capital, ensuring maximum security and liquidity.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- REGULATORY BODIES --}}
    @if ($regulatoryCompliance)
        <section class="py-24 relative overflow-hidden bg-[#050505]">
            <div
                class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-500/5 rounded-full blur-[120px] pointer-events-none">
            </div>

            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row gap-16 items-start">
                    <div class="lg:w-1/3">
                        <span
                            class="text-emerald-400 font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Global Oversight') }}</span>
                        <h2 class="text-4xl font-black text-white leading-tight mb-8">
                            {{ __('Supervisory') }} <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-600">{{ __('Bodies.') }}</span>
                        </h2>
                        <p class="text-text-secondary leading-relaxed mb-8">
                            {{ __('We maintain active dialogs and reporting channels with leading financial authorities to ensure a stable and transparent trading ecosystem.') }}
                        </p>
                    </div>

                    <div class="lg:w-2/3 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($regulatoryCompliance['regulators'] as $regulator)
                            <div
                                class="px-8 py-10 rounded-3xl bg-white/5 border border-white/10 flex flex-col gap-6 group hover:bg-white/10 transition-all border-l-4 border-l-emerald-500/40 hover:border-l-emerald-500 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.5)]">
                                    </div>
                                    <span
                                        class="text-[10px] font-black text-white/40 uppercase tracking-widest">{{ __('Supervised Entity') }}</span>
                                </div>
                                <h4 class="text-xl font-black text-white uppercase tracking-tight">{{ __($regulator) }}
                                </h4>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- CERTIFICATIONS & DOCUMENTS --}}
        <section class="py-24 relative overflow-hidden bg-black/40 border-y border-white/5">
            <div class="container mx-auto px-4">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span
                        class="text-emerald-400 font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Official Verification') }}</span>
                    <h2 class="text-4xl font-black text-white mb-6">{{ __('Licenses &') }}
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-600">{{ __('Certifications.') }}</span>
                    </h2>
                    <p class="text-text-secondary text-lg">
                        {{ __('Download and verify our official documentation as part of our commitment to radial transparency.') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($regulatoryCompliance['pdf_certificates'] as $pdf)
                        <div
                            class="p-8 rounded-[2.5rem] bg-secondary-dark/50 border border-white/5 backdrop-blur-xl relative overflow-hidden group">
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -translate-y-16 translate-x-16 blur-3xl group-hover:bg-emerald-500/10 transition-colors">
                            </div>

                            <div class="relative z-10">
                                <div
                                    class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-text-secondary group-hover:text-emerald-500 transition-all mb-8">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>

                                <h3 class="text-xl font-black text-white mb-2 leading-tight">{{ __($pdf['name']) }}</h3>
                                <div class="text-[10px] text-emerald-500 font-black uppercase tracking-widest mb-8">
                                    {{ __('SEC v4.2 PRO • Verified') }}
                                </div>

                                <div class="flex items-center gap-4">
                                    <a href="{{ asset('assets/pdf/' . $pdf['file']) }}"
                                        class="pdf-preview-link flex-1 px-6 py-3.5 bg-emerald-500 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-emerald-600 transition-all flex items-center justify-center gap-2">
                                        {{ __('View PDF') }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="{{ asset('assets/pdf/' . $pdf['file']) }}" download
                                        class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="py-24 relative overflow-hidden bg-[#050505]">
        <div class="container mx-auto px-4 relative z-10 text-center">
            <div
                class="max-w-4xl mx-auto p-12 lg:p-20 rounded-[3rem] bg-gradient-to-b from-white/[0.05] to-transparent border border-white/5 relative overflow-hidden">
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-1 bg-gradient-to-r from-transparent via-emerald-500/40 to-transparent">
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-white mb-8 leading-tight">
                    {{ __('Ready to trade with') }} <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-600">{{ __('Institutional Confidence?') }}</span>
                </h2>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('user.register') }}"
                        class="px-10 py-5 bg-emerald-500 text-white rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20 hover:-translate-y-1">
                        {{ __('Open Verified Account') }}
                    </a>
                    <a href="{{ route('contact') }}"
                        class="px-10 py-5 bg-white/5 border border-white/10 text-white rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-white/10 transition-all">
                        {{ __('Inquire Advisory') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    @include('templates.bento.blades.partials.pdf-modal')
@endsection
