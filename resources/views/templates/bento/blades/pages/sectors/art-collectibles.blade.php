@extends('templates.bento.blades.layouts.front')

@section('title', __($page_title))

@section('content')
    <div class="container mx-auto px-4 py-12 relative z-10">

        {{-- Section 1: The Core (Identity & Stats) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            {{-- Context Card --}}
            <div class="lg:col-span-2 relative group">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-rose-500/10 to-amber-500/10 rounded-3xl blur-xl opacity-30 group-hover:opacity-50 transition-opacity">
                </div>
                <div
                    class="relative h-full bg-[#1e293b]/60 backdrop-blur-xl border border-white/10 rounded-3xl p-8 flex flex-col justify-center overflow-hidden">
                    {{-- Illustrative Layer (Watch Movement) --}}
                    <div
                        class="absolute -right-20 -bottom-20 w-[450px] h-[450px] opacity-10 group-hover:opacity-30 transition-all duration-1000 pointer-events-none grayscale group-hover:grayscale-0">
                        <img src="{{ asset('assets/images/sectors/art.png') }}" class="w-full h-full object-cover">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 mb-6">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            <span
                                class="text-xs font-bold text-rose-500 tracking-wider uppercase">{{ __('Rarity & Scarcity') }}</span>
                        </div>
                        <h2
                            class="text-3xl md:text-5xl font-black text-white mb-6 uppercase tracking-tighter leading-none italic">
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
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-4">{{ __('Valuation Risk') }}
                    </h3>
                    <div class="flex items-end justify-between mb-2">
                        <span
                            class="text-2xl font-black text-white uppercase italic tracking-tighter">{{ __($sector_data['risk_level']) }}</span>
                        <span
                            class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ __($sector_data['risk_profile']) }}</span>
                    </div>
                    <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-rose-500 to-amber-500 rounded-full"
                            style="width: {{ $sector_data['risk_level'] == 'high' ? '100%' : ($sector_data['risk_level'] == 'medium' ? '66%' : '33%') }}">
                        </div>
                    </div>
                </div>

                {{-- Volatility --}}
                <div
                    class="bg-[#1e293b]/60 backdrop-blur-xl border border-white/10 rounded-3xl p-6 relative overflow-hidden group">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-2">
                        {{ __('Market Volatility') }}
                    </h3>
                    <div class="text-2xl font-black text-white uppercase italic tracking-tighter mb-4 relative z-10">
                        {{ __($sector_data['volatility']) }}</div>

                    {{-- Data Stream Visualization --}}
                    <div class="flex items-center gap-1.5 h-10 opacity-30">
                        @for ($i = 0; $i < 15; $i++)
                            @php $h = [10, 30, 20, 50, 40, 20, 30, 40, 50, 60, 40, 30, 20, 10, 30][$i % 15]; @endphp
                            <div class="flex-1 bg-rose-500/50 rounded-full animate-pulse shadow-[0_0_10px_rgba(244,63,94,0.2)]"
                                style="height: {{ $h }}%; animation-duration: {{ 1000 + $i * 100 }}ms"></div>
                        @endfor
                    </div>
                </div>

                {{-- Goal --}}
                <div class="bg-[#1e293b]/60 backdrop-blur-xl border border-white/10 rounded-3xl p-6">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-2">
                        {{ __('Long-term Objective') }}</h3>
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500">
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

        {{-- Section 2: Collection Flow --}}
        <div class="mb-20">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12">
                <div class="max-w-2xl">
                    <span
                        class="text-rose-500 font-black tracking-[0.3em] uppercase text-[10px] mb-4 block">{{ __('Asset Curation') }}</span>
                    <h2
                        class="text-4xl md:text-6xl font-black text-white mb-6 uppercase tracking-tighter leading-none italic">
                        {{ __('The Collectibles Logic') }}</h2>
                    <p class="text-slate-400 font-medium leading-relaxed italic">
                        {{ __('Rarity-driven assets offer a low-correlation hedge against traditional market movements, growing in value as global demand for uniqueness increases.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @php
                    $steps = [
                        [
                            'icon' =>
                                '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m16 10-4 4-4-4"/></svg>',
                            'title' => 'Curation',
                            'desc' => 'Identifying historically significant and rare assets.',
                        ],
                        [
                            'icon' =>
                                '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
                            'title' => 'Authentication',
                            'desc' => 'Rigorous verification of provenance and condition.',
                        ],
                        [
                            'icon' =>
                                '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>',
                            'title' => 'Vaulting',
                            'desc' => 'Storage in climate-controlled, insured facilities.',
                        ],
                        [
                            'icon' =>
                                '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12V4h12l4 4v12h-8"/><path d="M21 15 12 24 3 15"/><path d="M12 24v-9"/></svg>',
                            'title' => 'Appreciation',
                            'desc' => 'Long-term value growth via supply scarcity.',
                        ],
                    ];
                @endphp

                @foreach ($steps as $index => $step)
                    <div class="relative group">
                        <div
                            class="relative h-[250px] bg-[#1e293b] border border-white/5 rounded-[2.5rem] p-8 overflow-hidden hover:border-rose-500/30 transition-all group">
                            <div class="relative z-10 flex flex-col h-full justify-between">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 flex items-center justify-center group-hover:bg-rose-500 group-hover:text-black transition-all">
                                    {!! $step['icon'] !!}
                                </div>
                                <div>
                                    <h4
                                        class="text-xl font-black text-white mb-2 uppercase tracking-tighter italic leading-none">
                                        {{ __($step['title']) }}</h4>
                                    <p class="text-xs text-slate-500 font-medium italic leading-relaxed">
                                        {{ __($step['desc']) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Section 3: Mechanics & Yield (Split Layout) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-20">
            {{-- Earnings Engine --}}
            <div
                class="bg-[#1e293b]/40 backdrop-blur-xl border border-white/5 rounded-[3rem] p-10 relative overflow-hidden group">
                <div
                    class="absolute -right-20 -top-20 w-80 h-80 bg-rose-500/5 rounded-full blur-3xl group-hover:bg-rose-500/10 transition-all">
                </div>

                <div class="relative z-10">
                    <h3
                        class="text-2xl font-black text-white mb-10 flex items-center gap-4 uppercase tracking-tighter italic">
                        <div class="w-10 h-10 rounded-xl bg-rose-500 flex items-center justify-center text-black">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 5v14" />
                                <path d="m19 12-7 7-5-5" />
                            </svg>
                        </div>
                        {{ __('Value Engine') }}
                    </h3>
                    <div class="grid gap-4">
                        @foreach ($sector_data['earnings_generated_from'] as $key => $desc)
                            <div
                                class="p-6 rounded-2xl bg-[#0f172a] border border-white/5 hover:border-rose-500/20 transition-all group/item">
                                <h4
                                    class="font-black text-white text-xs mb-2 uppercase tracking-widest text-rose-500/70 group-hover/item:text-rose-500">
                                    {{ __(str_replace('_', ' ', $key)) }}
                                </h4>
                                <p class="text-sm text-slate-400 font-medium italic leading-relaxed">{{ __($desc) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Mechanics --}}
            <div
                class="bg-[#1e293b]/40 backdrop-blur-xl border border-white/5 rounded-[3rem] p-10 relative overflow-hidden group">
                <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-rose-500/5 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <h3
                        class="text-2xl font-black text-white mb-10 flex items-center gap-4 uppercase tracking-tighter italic">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                <path d="M12 17h.01" />
                            </svg>
                        </div>
                        {{ __('Gallery Mechanics') }}
                    </h3>
                    <div class="space-y-4">
                        @foreach ($sector_data['how_it_works'] as $index => $item)
                            <div
                                class="flex items-start gap-6 p-6 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.05] transition-all">
                                <div
                                    class="mt-1 w-8 h-8 rounded-lg bg-slate-900 border border-white/5 flex items-center justify-center text-[10px] font-black text-rose-500 flex-shrink-0">
                                    0{{ $index + 1 }}
                                </div>
                                <span
                                    class="text-sm text-slate-400 font-medium italic leading-relaxed">{{ __($item) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Suitability --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div
                class="p-10 rounded-[2.5rem] bg-emerald-500/[0.03] border border-emerald-500/10 group hover:border-emerald-500/30 transition-all">
                <h4 class="font-black text-white mb-6 flex items-center gap-4 uppercase tracking-tighter italic">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-500 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M20 6 9 17 4 12" />
                        </svg>
                    </div>
                    {{ __('Ideal For') }}
                </h4>
                <p class="text-lg text-slate-400 font-medium italic leading-relaxed">{{ __($sector_data['ideal_for']) }}
                </p>
            </div>
            <div
                class="p-10 rounded-[2.5rem] bg-orange-500/[0.03] border border-orange-500/10 group hover:border-orange-500/30 transition-all">
                <h4 class="font-black text-white mb-6 flex items-center gap-4 uppercase tracking-tighter italic">
                    <div class="w-8 h-8 rounded-lg bg-orange-500/20 text-orange-500 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                    </div>
                    {{ __('Not Ideal For') }}
                </h4>
                <p class="text-lg text-slate-400 font-medium italic leading-relaxed">{{ __($sector_data['not_ideal']) }}
                </p>
            </div>
        </div>

        {{-- Final CTA --}}
        <div class="mt-20 text-center py-20 bg-rose-600 rounded-[3rem] relative overflow-hidden group shadow-2xl">
            <div class="absolute inset-0 opacity-[0.05] pointer-events-none group-hover:scale-110 transition-transform duration-[4s]"
                style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 30px 30px;"></div>

            <div class="relative z-10 container mx-auto px-4">
                <h2 class="text-5xl md:text-8xl font-black text-white mb-8 uppercase tracking-tighter leading-none italic">
                    {{ __('Own') }} <br> {{ __('Elegance') }}
                </h2>
                <a href="{{ route('user.register') }}"
                    class="inline-flex items-center gap-6 px-14 py-6 bg-white text-black font-black uppercase text-xs tracking-widest rounded-2xl hover:scale-105 transition-all shadow-2xl group/cta">
                    {{ __('Acquire Assets') }}
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

    <style>
        body {
            background-color: #0f172a;
        }
    </style>
@endsection
