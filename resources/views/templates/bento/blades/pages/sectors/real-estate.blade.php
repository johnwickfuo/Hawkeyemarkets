@extends('templates.bento.blades.layouts.front')

@section('content')
    <div class="container mx-auto px-4 py-12">
        {{-- Hero Sector Identity --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <div
                class="lg:col-span-2 p-10 rounded-[3rem] bg-[#1a1614] border border-[#3d322c] relative overflow-hidden group min-h-[400px] flex flex-col justify-end">
                <div
                    class="absolute top-0 right-0 w-full h-full bg-[radial-gradient(circle_at_top_right,rgba(184,134,11,0.15),transparent_70%)]">
                </div>
                <div class="absolute top-10 right-10 opacity-10 group-hover:opacity-20 transition-opacity duration-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 24 24" fill="none"
                        stroke="#b8860b" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18" />
                        <path d="M3 7v1a3 3 0 0 0 6 0V7l3 2 3-2v1a3 3 0 0 0 6 0V7" />
                        <path d="M9 17h1" />
                        <path d="M14 17h1" />
                        <path d="M9 13h1" />
                        <path d="M14 13h1" />
                        <path d="M18 21V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v17" />
                    </svg>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <span
                            class="px-4 py-1.5 rounded-full bg-[#b8860b]/20 border border-[#b8860b]/30 text-[#e6b800] text-[10px] font-black uppercase tracking-[0.2em]">
                            {{ __('Asset Class') }}
                        </span>
                        <span class="w-2 h-2 rounded-full bg-[#b8860b] animate-pulse"></span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black text-white mb-6 uppercase tracking-tighter leading-none">
                        {{ __('Real Estate') }}<span class="text-[#b8860b] italic">.</span>
                    </h1>
                    <p class="text-lg text-stone-400 max-w-2xl leading-relaxed font-medium">
                        {{ __($sector_data['context']) }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                {{-- Stability Meter --}}
                <div class="p-8 rounded-[2.5rem] bg-stone-900/50 border border-stone-800 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <span
                                class="text-[10px] font-black text-stone-500 uppercase tracking-widest">{{ __('Stability Factor') }}</span>
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 20V10" />
                                    <path d="M18 20V4" />
                                    <path d="M6 20v-4" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-white mb-2 uppercase">{{ __('High') }}</div>
                        <div class="h-1.5 w-full bg-stone-800 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <p class="text-[10px] text-stone-500 uppercase tracking-tighter mt-4 leading-relaxed">
                        {{ __('Low market volatility typical of physical assets.') }}</p>
                </div>

                {{-- Risk Assessment --}}
                <div class="p-8 rounded-[2.5rem] bg-stone-900/50 border border-stone-800">
                    <div class="flex items-center justify-between mb-6">
                        <span
                            class="text-[10px] font-black text-stone-500 uppercase tracking-widest">{{ __('Risk Index') }}</span>
                        <span
                            class="px-3 py-1 rounded-md bg-stone-800 text-stone-400 text-[9px] font-bold uppercase">{{ __('Conservative') }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="relative w-16 h-16">
                            <svg class="w-full h-full" viewBox="0 0 36 36">
                                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none" stroke="#2a2a2a" stroke-width="3" />
                                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none" stroke="#b8860b" stroke-width="3" stroke-dasharray="40, 100" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center text-xs font-black text-white">
                                {{ __('Mid') }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-black text-white uppercase">{{ __($sector_data['risk_level']) }}</div>
                            <div class="text-[10px] text-stone-500 uppercase tracking-widest mt-0.5">
                                {{ __('Balanced Growth') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Operational Logic --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-12">
            <div class="lg:col-span-1 p-10 rounded-[3rem] bg-[#b8860b] text-stone-950 flex flex-col justify-between">
                <div>
                    <h2 class="text-3xl font-black uppercase leading-none mb-6 tracking-tighter">
                        {{ __('Rental Logic') }}
                    </h2>
                    <p class="text-sm font-bold leading-relaxed opacity-80">
                        {{ __('How our institutional-grade property management generates consistent yields.') }}
                    </p>
                </div>
                <div class="mt-12">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] border-b-2 border-stone-900/20 pb-1">
                        {{ __('Version: ARCH_v2.1') }}
                    </span>
                </div>
            </div>

            <div
                class="lg:col-span-3 p-10 rounded-[3rem] bg-stone-900/30 border border-stone-800 backdrop-blur-xl relative overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10">
                    @php
                        $steps = [
                            ['title' => 'Acquisition', 'desc' => 'Strategic purchase of high-yield properties.'],
                            ['title' => 'Management', 'desc' => 'Professional oversight of tenants and maintenance.'],
                            ['title' => 'Distribution', 'desc' => 'Scheduled payout of rental dividends.'],
                            ['title' => 'Exit', 'desc' => 'Capital gains from strategic asset liquidation.'],
                        ];
                    @endphp

                    @foreach ($steps as $index => $step)
                        <div class="relative group">
                            <div
                                class="text-4xl font-black text-stone-800 mb-6 group-hover:text-[#b8860b] transition-colors">
                                0{{ $index + 1 }}</div>
                            <h3 class="text-sm font-black text-white uppercase mb-3 tracking-widest">
                                {{ __($step['title']) }}</h3>
                            <p class="text-xs text-stone-500 leading-relaxed">{{ __($step['desc']) }}</p>
                            @if ($index < 3)
                                <div class="hidden md:block absolute top-6 -right-4 text-stone-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                        <path d="m12 5 7 7-7 7" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-[#b8860b]/5 blur-[100px] rounded-full"></div>
            </div>
        </div>

        {{-- Yield Mechanisms & Suitability --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <div class="p-10 rounded-[3rem] bg-stone-900/50 border border-stone-800">
                <div class="flex items-center gap-4 mb-10">
                    <div
                        class="w-12 h-12 rounded-2xl bg-[#b8860b]/10 flex items-center justify-center border border-[#b8860b]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="#b8860b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2" />
                            <path d="M3 9h18" />
                            <path d="M9 21V9" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white uppercase tracking-tight">{{ __('Yield Generators') }}
                        </h2>
                        <p class="text-[10px] text-stone-500 uppercase tracking-widest mt-1">
                            {{ __('Primary sources of performance') }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach ($sector_data['earnings_generated_from'] as $title => $description)
                        <div
                            class="group p-5 rounded-2xl bg-white/5 border border-white/5 hover:bg-[#b8860b]/5 hover:border-[#b8860b]/20 transition-all">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-xs font-black text-stone-200 uppercase tracking-widest mb-1">
                                        {{ __($title) }}</h4>
                                    <p class="text-[11px] text-stone-500 leading-relaxed font-medium">
                                        {{ __($description) }}</p>
                                </div>
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="#b8860b" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M7 7h10v10" />
                                        <path d="M7 17 17 7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                <div class="p-10 rounded-[3rem] bg-[#1a1614] border border-[#3d322c] flex flex-col justify-center">
                    <div class="flex items-center gap-3 text-[#b8860b] mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest">{{ __('Ideal For') }}</span>
                    </div>
                    <p class="text-base text-stone-300 leading-relaxed">
                        {{ __($sector_data['ideal_for']) }}
                    </p>
                </div>

                <div
                    class="p-10 rounded-[3rem] bg-stone-950/20 border border-stone-800 border-dashed flex flex-col justify-center">
                    <div class="flex items-center gap-3 text-stone-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest">{{ __('Not Ideal For') }}</span>
                    </div>
                    <p class="text-sm text-stone-500 leading-relaxed italic">
                        "{{ __($sector_data['not_ideal']) }}"
                    </p>
                </div>
            </div>
        </div>

        {{-- Call to Action --}}
        <div
            class="p-12 rounded-[4rem] bg-[radial-gradient(ellipse_at_center,rgba(184,134,11,0.2),transparent_70%)] border border-[#b8860b]/20 text-center relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-4 uppercase tracking-tighter">
                    {{ __('Establish Your Estate') }}</h2>
                <p class="text-stone-400 mb-10 max-w-xl mx-auto text-sm leading-relaxed">
                    {{ __('Participate in institutional-grade real estate portfolios with fractionalized ease and automated yield management.') }}
                </p>
                <a href="{{ route('user.register') }}"
                    class="inline-flex items-center gap-3 px-10 py-5 rounded-2xl bg-white text-black font-black uppercase text-sm tracking-[0.2em] hover:bg-[#b8860b] hover:text-white transition-all transform hover:scale-105">
                    {{ __('Begin Allocation') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </a>
            </div>
            {{-- Background architectural grid --}}
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
                style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 30px 30px;"></div>
        </div>
    </div>
@endsection
