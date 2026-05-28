@extends('templates.bento.blades.layouts.front')

@section('title', __($page_title))

@section('content')
    <div class="container mx-auto px-4 py-12 relative z-10">

        {{-- Section 1: The Core (Identity & Stats) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            {{-- Context Card --}}
            <div class="lg:col-span-2 relative group">
                <div
                    class="absolute inset-0 bg-white/5 rounded-3xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity">
                </div>
                <div
                    class="relative h-full bg-[#1e293b]/60 backdrop-blur-xl border border-white/10 rounded-3xl p-8 flex flex-col justify-center overflow-hidden">
                    {{-- Illustrative Layer (Gold) - Subtle & Grayscale unless hovered --}}
                    <div class="absolute -right-20 -bottom-20 w-[400px] h-[400px] opacity-[0.03] group-hover:opacity-10 transition-all duration-1000 pointer-events-none grayscale group-hover:grayscale-0">
                         <img src="{{ asset('assets/images/sectors/gold.png') }}" class="w-full h-full object-contain">
                    </div>
                    
                    <div class="relative z-10">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-6">
                            <span class="w-2 h-2 rounded-full bg-slate-500 animate-pulse"></span>
                            <span
                                class="text-xs font-bold text-slate-400 tracking-wider uppercase">{{ __('Tangible Assets') }}</span>
                        </div>
                        <h2 class="text-3xl md:text-5xl font-black text-white mb-6 uppercase tracking-tighter leading-none italic">
                            {{ __($sector_data['context']) }}
                        </h2>
                        <div class="flex flex-wrap gap-4">
                            @foreach ($sector_data['psychology'] as $item)
                                <div
                                    class="px-5 py-2 rounded-xl bg-white/5 border border-white/10 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                    {{ __($item) }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pulse Stats --}}
            <div class="space-y-6">
                {{-- Risk Level --}}
                <div class="bg-[#1e293b]/60 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4">{{ __('Asset Risk') }}
                    </h3>
                    <div class="flex items-end justify-between mb-2">
                        <span class="text-2xl font-black text-white uppercase italic tracking-tighter">{{ __($sector_data['risk_level']) }}</span>
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ __($sector_data['risk_profile']) }}</span>
                    </div>
                    <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-white/10 rounded-full"
                            style="width: {{ $sector_data['risk_level'] == 'high' ? '100%' : ($sector_data['risk_level'] == 'medium' ? '66%' : '33%') }}">
                        </div>
                    </div>
                </div>

                {{-- Volatility --}}
                <div
                    class="bg-[#1e293b]/60 backdrop-blur-xl border border-white/10 rounded-3xl p-6 relative overflow-hidden group">
                    {{-- Illustrative Layer (Oil) --}}
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 opacity-[0.02] group-hover:opacity-[0.05] transition-opacity grayscale">
                        <img src="{{ asset('assets/images/sectors/oil.png') }}" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-2">{{ __('Market Volatility') }}
                    </h3>
                    <div class="text-2xl font-black text-white uppercase italic tracking-tighter mb-4 relative z-10">
                        {{ __($sector_data['volatility']) }}</div>

                    {{-- Wave Visualization --}}
                    <div class="flex items-center gap-1.5 h-10 opacity-20">
                        @for ($i = 0; $i < 15; $i++)
                            @php $h = [40, 70, 90, 60, 100, 30, 80, 50, 70, 90, 60, 40, 80, 100, 60][$i % 15]; @endphp
                            <div class="flex-1 bg-white/50 rounded-full animate-pulse"
                                style="height: {{ $h }}%; animation-duration: {{ 1000 + ($i * 100) }}ms"></div>
                        @endfor
                    </div>
                </div>

                {{-- Goal --}}
                <div class="bg-[#1e293b]/60 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-2">
                        {{ __('Portfolio Goal') }}</h3>
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-2xl bg-white/5 border border-white/10 text-white/50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 2v20" />
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                        </div>
                        <span
                            class="text-xl font-black text-white uppercase italic tracking-tighter">{{ str_replace('_', ' ', __($sector_data['investment_goal'])) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Supply Chain Logic --}}
        <div class="mb-20">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12">
                <div class="max-w-2xl">
                    <span
                        class="text-slate-500 font-black tracking-[0.3em] uppercase text-[10px] mb-4 block">{{ __('Supply Chain Logic') }}</span>
                    <h2 class="text-4xl md:text-6xl font-black text-white mb-6 uppercase tracking-tighter leading-none italic">{{ __('The Physical Journey') }}</h2>
                    <p class="text-slate-500 font-medium leading-relaxed italic">
                        {{ __('Unlike digital abstractions, commodities follow a rigorous physical process from extraction to global market integration.') }}
                    </p>
                </div>
                <div>
                     <a href="{{ route('user.register') }}" class="px-10 py-5 bg-white text-black font-black uppercase text-xs tracking-widest rounded-2xl hover:bg-slate-200 transition-all transform hover:scale-105 inline-block">
                        {{ __('Allocate Now') }}
                     </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @php
                    $steps = [
                        [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22s1-2 4-2 7 2 10 2 4-2 4-2V5s-1 2-4 2-7-2-10-2-4 2-4 2Z"/><path d="M2 5v17"/><path d="M12 5v17"/><path d="M22 5v17"/></svg>',
                            'title' => 'Global Sourcing',
                            'desc' => 'Securing raw material output from key production hubs.',
                            'image' => 'silver.png'
                        ],
                        [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="12" x="2" y="10" rx="2"/><rect width="8" height="20" x="14" y="2" rx="2"/></svg>',
                            'title' => 'Supply Dynamics',
                            'desc' => 'Analyzing scarcity, geopolitics, and logistics flow.',
                            'image' => 'oil.png'
                        ],
                        [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="m17 11-5-5-5 5"/><path d="m7 13 5 5 5-5"/></svg>',
                            'title' => 'Vaulting & Storage',
                            'desc' => 'Physical collateralization in secure, insured facilities.',
                            'image' => 'gold.png'
                        ],
                        [
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/></svg>',
                            'title' => 'Value Realization',
                            'desc' => 'Exit liquidity through global exchange integration.',
                            'image' => 'wheat.png'
                        ],
                    ];
                @endphp

                @foreach ($steps as $index => $step)
                    <div class="relative group">
                        <div
                            class="relative h-[280px] bg-[#1e293b] border border-white/5 rounded-[2.5rem] p-8 overflow-hidden hover:border-white/20 transition-all group">
                            {{-- Background Illustrative (Very Subtle) --}}
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-[0.08] transition-opacity duration-700 grayscale scale-110 group-hover:scale-100">
                                <img src="{{ asset('assets/images/sectors/' . $step['image']) }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-[#0f172a]/70"></div>
                            </div>

                            <div class="relative z-10 flex flex-col h-full justify-between">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 text-white/40 flex items-center justify-center group-hover:bg-white/10 group-hover:text-white transition-all">
                                    {!! $step['icon'] !!}
                                </div>
                                <div>
                                    <h4 class="text-xl font-black text-white mb-2 uppercase tracking-tighter italic leading-none group-hover:text-white transition-colors">{{ __($step['title']) }}</h4>
                                    <p class="text-xs text-slate-500 font-medium italic leading-relaxed">{{ __($step['desc']) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Section 3: Mechanics & Yield --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-20">
            {{-- Earnings Engine --}}
            <div class="bg-[#1e293b]/40 backdrop-blur-xl border border-white/5 rounded-[3rem] p-10 relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-white mb-10 flex items-center gap-4 uppercase tracking-tighter italic">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/50">
                             <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-5-5"/></svg>
                        </div>
                        {{ __('Earnings Engine') }}
                    </h3>
                    <div class="grid gap-4">
                        @foreach ($sector_data['earnings_generated_from'] as $key => $desc)
                            <div
                                class="p-6 rounded-2xl bg-[#0f172a] border border-white/5 hover:border-white/20 transition-all group/item">
                                <h4 class="font-black text-white text-xs mb-2 uppercase tracking-widest text-slate-500 group-hover/item:text-white">
                                    {{ __(str_replace('_', ' ', $key)) }}
                                </h4>
                                <p class="text-sm text-slate-500 font-medium italic leading-relaxed">{{ __($desc) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Mechanics --}}
            <div class="bg-[#1e293b]/40 backdrop-blur-xl border border-white/5 rounded-[3rem] p-10 relative overflow-hidden group">
                <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-white/5 rounded-full blur-3xl opacity-0"></div>
                
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-white mb-10 flex items-center gap-4 uppercase tracking-tighter italic">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-white">
                             <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                        </div>
                        {{ __('Mechanics') }}
                    </h3>
                    <div class="space-y-4">
                        @foreach ($sector_data['how_it_works'] as $index => $item)
                            <div class="flex items-start gap-6 p-6 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.05] transition-all">
                                <div
                                    class="mt-1 w-8 h-8 rounded-lg bg-slate-900 border border-white/5 flex items-center justify-center text-[10px] font-black text-slate-500 flex-shrink-0">
                                    0{{ $index + 1 }}
                                </div>
                                <span class="text-sm text-slate-500 font-medium italic leading-relaxed">{{ __($item) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Suitability --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="p-10 rounded-[2.5rem] bg-white/[0.02] border border-white/5 group hover:border-white/10 transition-all">
                <h4 class="font-black text-white mb-6 flex items-center gap-4 uppercase tracking-tighter italic">
                    <div class="w-8 h-8 rounded-lg bg-white/5 text-white/50 flex items-center justify-center text-xs">
                         <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17 4 12"/></svg>
                    </div>
                    {{ __('Ideal For') }}
                </h4>
                <p class="text-lg text-slate-500 font-medium italic leading-relaxed">{{ __($sector_data['ideal_for']) }}</p>
            </div>
            <div class="p-10 rounded-[2.5rem] bg-white/[0.02] border border-white/5 group hover:border-white/10 transition-all">
                <h4 class="font-black text-white mb-6 flex items-center gap-4 uppercase tracking-tighter italic">
                     <div class="w-8 h-8 rounded-lg bg-white/5 text-white/50 flex items-center justify-center text-xs">
                         <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    </div>
                    {{ __('Not Ideal For') }}
                </h4>
                <p class="text-lg text-slate-500 font-medium italic leading-relaxed">{{ __($sector_data['not_ideal']) }}</p>
            </div>
        </div>

        {{-- Final CTA --}}
        <div class="mt-20 text-center py-20 bg-white rounded-[3rem] relative overflow-hidden group shadow-2xl">
             {{-- Texture Layer --}}
             <div class="absolute inset-0 opacity-[0.05] pointer-events-none group-hover:scale-110 transition-transform duration-[4s]"
                style="background-image: radial-gradient(black 1px, transparent 1px); background-size: 30px 30px;"></div>
                
             <div class="relative z-10 container mx-auto px-4">
                <h2 class="text-5xl md:text-8xl font-black text-black mb-8 uppercase tracking-tighter leading-none italic">
                    {{ __('Own') }} <br> {{ __('Potential') }}
                </h2>
                <p class="text-black text-sm font-bold uppercase tracking-[0.2em] mb-12 opacity-60">
                    {{ __('Begin your allocation into hard assets today.') }}
                </p>
                <a href="{{ route('user.register') }}"
                    class="inline-flex items-center gap-6 px-14 py-6 bg-black text-white font-black uppercase text-xs tracking-widest rounded-2xl hover:scale-105 transition-all shadow-2xl group/cta">
                    {{ __('Start Allocation') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"
                        stroke-linejoin="round" class="group-hover/cta:translate-x-1 transition-transform">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </a>
             </div>
        </div>

    </div>

    {{-- Global Background Styling --}}
    <style>
        body { background-color: #0f172a; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
    </style>
@endsection
