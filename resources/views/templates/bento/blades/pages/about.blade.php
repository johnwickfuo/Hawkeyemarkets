@extends('templates.bento.blades.layouts.front')

@section('content')
    {{-- About Us Content --}}
    <div class="relative bg-[#050505] pt-12 pb-24 overflow-hidden">
        {{-- Background Accents --}}
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-accent-primary/[0.03] rounded-full blur-[120px] -z-10">
        </div>
        <div class="absolute bottom-1/2 left-0 w-[500px] h-[500px] bg-purple-500/[0.02] rounded-full blur-[100px] -z-10">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            {{-- Mission & Vision Section --}}
            <div class="grid lg:grid-cols-2 gap-16 items-center mb-32">
                <div class="relative group">
                    <div
                        class="absolute -inset-4 bg-gradient-to-r from-accent-primary/20 to-accent-secondary/20 rounded-[2.5rem] blur-2xl opacity-50 group-hover:opacity-100 transition-opacity duration-700">
                    </div>
                    <div
                        class="relative rounded-[2rem] overflow-hidden border border-white/10 bg-[#0B0F17]/40 backdrop-blur-xl p-8 lg:p-12">
                        <span
                            class="text-accent-primary font-bold uppercase tracking-[0.3em] text-[10px] block mb-6">{{ __('Our Narrative') }}</span>
                        <h2 class="text-3xl md:text-5xl font-black text-white leading-tight mb-8">
                            {{ __('Redefining the') }} <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">{{ __('Architecture of Wealth.') }}</span>
                        </h2>
                        <div class="space-y-6 text-text-secondary text-lg leading-relaxed">
                            <p>
                                {{ __('Founded on the principle of institutional parity, :site_name was built to dissolve the barriers between elite financial strategies and the global retail investor. We believe that sophisticated capital management should not be a privilege of the few, but a standard for all.', ['site_name' => getSetting('name')]) }}
                            </p>
                            <p>
                                {{ __('Our journey began in 2018 with a single vision: to create a transparent, high-performance ecosystem that combines the stability of traditional assets with the velocity of digital markets.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div
                            class="p-6 rounded-2xl bg-white/[0.02] border border-white/5 hover:border-accent-primary/20 transition-all duration-300">
                            <div
                                class="w-12 h-12 rounded-xl bg-accent-primary/10 flex items-center justify-center text-accent-primary mb-6">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04Cover1.016l.6 6a11.955 11.955 0 005.618 8.435L12 21.5l1.382-.52a11.955 11.955 0 005.618-8.435l.6-6z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-white font-bold mb-2">{{ __('Uncompromising Security') }}</h4>
                            <p class="text-text-secondary text-sm">
                                {{ __('Tier-1 custodian integration and multi-layer encryption protocols.') }}</p>
                        </div>
                        <div
                            class="p-6 rounded-2xl bg-white/[0.02] border border-white/5 hover:border-purple-500/20 transition-all duration-300">
                            <div
                                class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 mb-6">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-white font-bold mb-2">{{ __('Total Transparency') }}</h4>
                            <p class="text-text-secondary text-sm">
                                {{ __('Real-time auditing and full visibility into asset allocation and performance.') }}
                            </p>
                        </div>
                        <div
                            class="p-6 rounded-2xl bg-white/[0.02] border border-white/5 hover:border-emerald-500/20 transition-all duration-300">
                            <div
                                class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 mb-6">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h4 class="text-white font-bold mb-2">{{ __('Algorithmic Precision') }}</h4>
                            <p class="text-text-secondary text-sm">
                                {{ __('High-frequency matching engines and proprietary risk models.') }}</p>
                        </div>
                        <div
                            class="p-6 rounded-2xl bg-white/[0.02] border border-white/5 hover:border-amber-500/20 transition-all duration-300">
                            <div
                                class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500 mb-6">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-white font-bold mb-2">{{ __('Global Connectivity') }}</h4>
                            <p class="text-text-secondary text-sm">
                                {{ __('Unified access to Major, Minor, and Exotic markets worldwide.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Metrics Grid (Bento Style) --}}
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-12 gap-6 mb-32">
                <div
                    class="md:col-span-4 lg:col-span-4 p-8 rounded-[2rem] bg-[#0B0F17]/40 border border-white/5 flex flex-col justify-between group overflow-hidden">
                    <div
                        class="absolute -top-12 -right-12 w-32 h-32 bg-accent-primary/20 rounded-full blur-3xl group-hover:bg-accent-primary/30 transition-all">
                    </div>
                    <div>
                        <span
                            class="text-accent-primary font-bold uppercase tracking-widest text-[10px] block mb-4">{{ __('Asset Reach') }}</span>
                        <div class="text-5xl font-black text-white mb-2">5,000+</div>
                        <p class="text-text-secondary text-sm">
                            {{ __('Verified financial instruments across all global sectors.') }}</p>
                    </div>
                </div>
                <div
                    class="md:col-span-2 lg:col-span-4 p-8 rounded-[2rem] bg-gradient-to-br from-accent-primary/10 to-transparent border border-white/5 flex flex-col justify-between">
                    <div>
                        <span
                            class="text-white/60 font-bold uppercase tracking-widest text-[10px] block mb-4">{{ __('Compliance') }}</span>
                        <div class="text-5xl font-black text-white mb-2">{{ __('SEC') }}</div>
                        <p class="text-text-secondary text-sm">
                            {{ __('v4.2 Certified platform with institutional-grade auditing.') }}</p>
                    </div>
                </div>
                <div
                    class="md:col-span-2 lg:col-span-4 p-8 rounded-[2rem] bg-[#0B0F17]/40 border border-white/5 flex flex-col justify-between">
                    <div>
                        <span
                            class="text-emerald-500 font-bold uppercase tracking-widest text-[10px] block mb-4">{{ __('Global Scale') }}</span>
                        <div class="text-5xl font-black text-white mb-2">150+</div>
                        <p class="text-text-secondary text-sm">
                            {{ __('Countries supported with localized infrastructure.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Team Section --}}
            <div id="team" class="mb-32">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span
                        class="text-accent-primary font-bold uppercase tracking-[0.3em] text-[10px] block mb-4">{{ __('Architects of Finance') }}</span>
                    <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-6">
                        {{ __('The Minds Behind') }} <br>
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">{{ __('Pro-Level Systems.') }}</span>
                    </h2>
                    <p class="text-text-secondary text-lg">
                        {{ __('Our leadership combines decades of experience in traditional hedge funds, blockchain security, and quantitative research.') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    {{-- CEO: Large Card --}}
                    <div
                        class="md:col-span-4 lg:col-span-3 lg:row-span-2 group relative rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 overflow-hidden transition-all duration-700 hover:border-accent-primary/30 shadow-2xl">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[#0B0F17] via-transparent to-transparent z-10 opacity-80 group-hover:opacity-60 transition-opacity">
                        </div>
                        <img loading="lazy" decoding="async" src="{{ asset('assets/images/team/ceo.png') }}" alt="CEO"
                            class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                        <div class="absolute bottom-0 left-0 w-full p-8 lg:p-12 z-20">
                            <div class="flex items-center gap-4 mb-4">
                                <span
                                    class="px-3 py-1 rounded-full bg-accent-primary text-white text-[10px] font-black uppercase tracking-widest">{{ __('Founder & CEO') }}</span>
                                <div class="w-2 h-2 rounded-full bg-accent-primary animate-pulse"></div>
                            </div>
                            <h3 class="text-3xl lg:text-4xl font-black text-white mb-4">{{ __('Marcus Sterling') }}</h3>
                            <p
                                class="text-text-secondary text-sm lg:text-base leading-relaxed max-w-md opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-4 group-hover:translate-y-0">
                                {{ __('Former head of Systematic Trading at a Tier-1 investment bank, Marcus founded :site_name to bridge the gap between retail investors and institutional-grade strategies.', ['site_name' => getSetting('name')]) }}
                            </p>
                        </div>
                    </div>

                    {{-- CTO Card --}}
                    <div
                        class="md:col-span-2 lg:col-span-3 group relative rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 overflow-hidden transition-all duration-700 hover:border-purple-500/30">
                        <div class="flex flex-col lg:flex-row h-full">
                            <div class="w-full lg:w-1/2 overflow-hidden">
                                <img loading="lazy" decoding="async" src="{{ asset('assets/images/team/cto.png') }}" alt="CTO"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 aspect-square lg:aspect-auto">
                            </div>
                            <div class="w-full lg:w-1/2 p-8 flex flex-col justify-center">
                                <div class="mb-4">
                                    <span
                                        class="text-purple-400 text-[10px] font-black uppercase tracking-widest">{{ __('Chief Tech Officer') }}</span>
                                </div>
                                <h3 class="text-2xl font-black text-white mb-2">{{ __('Elena Vance') }}</h3>
                                <p class="text-text-secondary text-xs leading-relaxed">
                                    {{ __('Cybersecurity architect with over 15 years specializing in distributed ledger technology and high-performance matching engines.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Head of Quant --}}
                    <div
                        class="md:col-span-2 lg:col-span-3 group relative rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 overflow-hidden transition-all duration-700 hover:border-emerald-500/30">
                        <div class="flex flex-col lg:flex-row h-full">
                            <div class="w-full lg:w-1/2 overflow-hidden order-1 lg:order-2">
                                <img loading="lazy" decoding="async" src="{{ asset('assets/images/team/asset_head.png') }}" alt="Head of strategy"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 aspect-square lg:aspect-auto">
                            </div>
                            <div class="w-full lg:w-1/2 p-8 flex flex-col justify-center order-2 lg:order-1">
                                <div class="mb-4">
                                    <span
                                        class="text-emerald-400 text-[10px] font-black uppercase tracking-widest">{{ __('Head of Strategy') }}</span>
                                </div>
                                <h3 class="text-2xl font-black text-white mb-2">{{ __('Dr. Julian Thorne') }}</h3>
                                <p class="text-text-secondary text-xs leading-relaxed">
                                    {{ __('PhD in Quantitative Finance, Dr. Thorne oversees our algorithmic model development and institutional risk management protocols.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Compliance Card (Small Bento) --}}
                    <div
                        class="md:col-span-4 lg:col-span-6 p-8 lg:p-12 rounded-[2.5rem] bg-gradient-to-r from-accent-primary/10 to-transparent border border-white/5 group relative overflow-hidden">
                        <div class="grid lg:grid-cols-12 gap-8 items-center relative z-10">
                            <div class="lg:col-span-2">
                                <div class="w-24 h-24 rounded-full border-2 border-accent-primary/30 p-1 overflow-hidden">
                                    <img loading="lazy" decoding="async" src="{{ asset('assets/images/team/compliance_head.png') }}"
                                        class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-700">
                                </div>
                            </div>
                            <div class="lg:col-span-6">
                                <span
                                    class="text-accent-primary text-[10px] font-black uppercase tracking-widest block mb-2">{{ __('Global Operations') }}</span>
                                <h3 class="text-2xl font-black text-white mb-2">{{ __('Sarah Jenkins') }}</h3>
                                <p class="text-text-secondary text-sm">
                                    {{ __('Leading our legal and regulatory frameworks, Sarah ensures every aspect of the :site_name ecosystem complies with international financial standards and SEC guidelines.', ['site_name' => getSetting('name')]) }}
                                </p>
                            </div>
                            <div class="lg:col-span-4 flex justify-end gap-3">
                                <div class="flex flex-col items-end">
                                    <span class="text-white font-bold text-sm">{{ __('Verified Governance') }}</span>
                                    <span
                                        class="text-accent-primary text-[10px] font-black uppercase tracking-widest">{{ __('SEC v4.2 PRO') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Global Section --}}
            <div class="relative py-24 border-y border-white/5 mb-32">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <span
                            class="text-accent-primary font-bold uppercase tracking-widest text-[10px] block mb-4">{{ __('Global Intelligence') }}</span>
                        <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-8">
                            {{ __('A Borderless') }} <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">{{ __('Financial Network.') }}</span>
                        </h2>
                        <p class="text-text-secondary text-lg leading-relaxed mb-10">
                            {{ __('From our primary headquarters in Frankfurt to our satellite nodes in Singapore and New York, :site_name maintains a 24/7 global presence. Our infrastructure is designed for zero-latency execution across every time zone.', ['site_name' => getSetting('name')]) }}
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 bg-white/5">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-xs font-bold text-white uppercase">{{ __('Frankfurt') }}</span>
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 bg-white/5">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-xs font-bold text-white uppercase">{{ __('Singapore') }}</span>
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 bg-white/5">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-xs font-bold text-white uppercase">{{ __('New York') }}</span>
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 bg-white/5">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-xs font-bold text-white uppercase">{{ __('London') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative h-[400px] lg:h-[600px] pointer-events-none">
                        <div class="absolute inset-0 scale-[1.2] lg:scale-[1.5]">
                            @include('templates.bento.blades.partials.globe-wireframe')
                        </div>
                    </div>
                </div>
            </div>

            {{-- CTA Section --}}
            <div class="relative group">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-accent-primary to-accent-secondary rounded-[3rem] blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200">
                </div>
                <div
                    class="relative bg-[#0B0F17] rounded-[2.5rem] p-12 lg:p-24 text-center overflow-hidden border border-white/5">
                    <div
                        class="absolute top-0 left-0 w-full h-full bg-[url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')] opacity-20 pointer-events-none">
                    </div>
                    <div class="relative z-10 max-w-2xl mx-auto">
                        <h2 class="text-4xl md:text-6xl font-black text-white mb-8 leading-tight">
                            {{ __('Join the') }} <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-accent-primary to-accent-secondary">{{ __('Financial Elite.') }}</span>
                        </h2>
                        <p class="text-text-secondary text-lg mb-12">
                            {{ __('Experience the next generation of wealth management. Secure, transparent, and built for everyone.') }}
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                            <a href="{{ route('user.register') }}"
                                class="w-full sm:w-auto px-10 py-5 bg-white text-primary-dark rounded-full font-bold text-lg hover:bg-gray-100 transition-all shadow-xl hover:-translate-y-1">
                                {{ __('Start Today') }}
                            </a>
                            <a href="{{ route('contact') }}"
                                class="w-full sm:w-auto px-10 py-5 bg-white/5 border border-white/10 text-white rounded-full font-bold text-lg hover:bg-white/10 transition-all backdrop-blur-md hover:-translate-y-1">
                                {{ __('Contact Advisory') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
