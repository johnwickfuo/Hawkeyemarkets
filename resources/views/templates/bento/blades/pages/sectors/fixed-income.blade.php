@extends('templates.bento.blades.layouts.front')

@section('content')
    <div class="container mx-auto px-4 py-12">
        {{-- Hero Sector Identity --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <div
                class="lg:col-span-2 p-10 rounded-[3rem] bg-[#0f172a] border border-slate-800 relative overflow-hidden group min-h-[400px] flex flex-col justify-end">
                <div
                    class="absolute top-0 right-0 w-full h-full bg-[radial-gradient(circle_at_top_right,rgba(16,185,129,0.1),transparent_70%)]">
                </div>
                <div class="absolute top-10 right-10 opacity-10 group-hover:opacity-20 transition-opacity duration-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 24 24" fill="none"
                        stroke="#10b981" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <span
                            class="px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-[0.2em]">
                            {{ __('Sovereign Grade') }}
                        </span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black text-white mb-6 uppercase tracking-tighter leading-none">
                        {{ __('Fixed Income') }}<span class="text-emerald-500 italic">.</span>
                    </h1>
                    <p class="text-lg text-slate-400 max-w-2xl leading-relaxed font-medium">
                        {{ __($sector_data['context']) }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                {{-- Capital Preservation --}}
                <div class="p-8 rounded-[2.5rem] bg-slate-900 border border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <span
                                class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Capital Preservation') }}</span>
                            <div
                                class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-white mb-2 uppercase">{{ __('Guaranteed') }}</div>
                        <div class="text-[10px] text-emerald-400 font-bold uppercase tracking-widest">
                            {{ __('Principal Secured') }}</div>
                    </div>
                    <div class="mt-4 flex gap-1">
                        @for ($i = 0; $i < 5; $i++)
                            <div class="h-1 flex-1 bg-emerald-500 rounded-full"></div>
                        @endfor
                    </div>
                </div>

                {{-- Reliability Index --}}
                <div class="p-8 rounded-[2.5rem] bg-slate-900 border border-slate-800">
                    <div class="flex items-center justify-between mb-6">
                        <span
                            class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Reliability Index') }}</span>
                        <span
                            class="px-3 py-1 rounded-md bg-emerald-500/10 text-emerald-400 text-[9px] font-bold uppercase">{{ __('Consolidated') }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="relative w-16 h-16">
                            <svg class="w-full h-full" viewBox="0 0 36 36">
                                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none" stroke="#1e293b" stroke-width="3" />
                                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none" stroke="#10b981" stroke-width="3" stroke-dasharray="95, 100" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center text-xs font-black text-white">95%
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-black text-white uppercase">{{ __($sector_data['risk_level']) }}
                                {{ __('Risk') }}</div>
                            <div class="text-[10px] text-slate-500 uppercase tracking-widest mt-0.5">
                                {{ __('Ultra-Low Volatility') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Accrual Mechanism --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-12">
            <div
                class="lg:col-span-1 p-10 rounded-[3rem] bg-emerald-500 text-slate-950 flex flex-col justify-between overflow-hidden relative">
                <div class="relative z-10">
                    <h2 class="text-3xl font-black uppercase leading-none mb-6 tracking-tighter">
                        {{ __('Income Logic') }}
                    </h2>
                    <p class="text-sm font-bold leading-relaxed opacity-80">
                        {{ __('Predictable interest accrual through sovereign-backed debt instruments.') }}
                    </p>
                </div>
                <div class="mt-12 relative z-10">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] border-b-2 border-slate-900/20 pb-1">
                        {{ __('Protocol: STEADY_v1.0') }}
                    </span>
                </div>
                {{-- Decorative circles --}}
                <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/20 rounded-full blur-2xl"></div>
            </div>

            <div class="lg:col-span-3 p-10 rounded-[3rem] bg-slate-900/50 border border-slate-800 backdrop-blur-xl group">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    @php
                        $steps = [
                            ['title' => 'Allocation', 'desc' => 'Funds deployed into high-grade bonds.'],
                            ['title' => 'Accrual', 'desc' => 'Interest generated at fixed, reliable rates.'],
                            ['title' => 'Settlement', 'desc' => 'Automated credit of yields to your wallet.'],
                            ['title' => 'Maturity', 'desc' => 'Full principal return upon plan completion.'],
                        ];
                    @endphp

                    @foreach ($steps as $index => $step)
                        <div class="relative">
                            <div
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-slate-800 border border-slate-700 text-xs font-black text-emerald-400 mb-6 group-hover:bg-emerald-500 group-hover:text-slate-950 transition-all duration-500">
                                0{{ $index + 1 }}
                            </div>
                            <h3 class="text-sm font-black text-white uppercase mb-3 tracking-widest">
                                {{ __($step['title']) }}</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">{{ __($step['desc']) }}</p>
                            @if ($index < 3)
                                <div class="hidden md:block absolute top-5 -right-4 text-slate-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Yield Sources & Suitability --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <div class="p-10 rounded-[3rem] bg-slate-900/50 border border-slate-800">
                <div class="flex items-center gap-4 mb-10">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v18h18" />
                            <path d="m19 9-5 5-4-4-3 3" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white uppercase tracking-tight">{{ __('Accrual Engines') }}
                        </h2>
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-1">
                            {{ __('Primary yield generation nodes') }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach ($sector_data['earnings_generated_from'] as $title => $description)
                        <div
                            class="group p-5 rounded-2xl bg-white/5 border border-white/5 hover:border-emerald-500/30 transition-all cursor-default">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="mt-1 w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]">
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-slate-200 uppercase tracking-widest mb-1">
                                            {{ __($title) }}</h4>
                                        <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                                            {{ __($description) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                <div
                    class="p-10 rounded-[3rem] bg-slate-900 border border-slate-800 flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/5 blur-3xl"></div>
                    <div class="flex items-center gap-3 text-emerald-500 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest">{{ __('Matched Profile') }}</span>
                    </div>
                    <p class="text-base text-slate-300 leading-relaxed font-medium">
                        {{ __($sector_data['ideal_for']) }}
                    </p>
                </div>

                <div
                    class="p-10 rounded-[3rem] bg-slate-950/40 border border-slate-800 border-dashed flex flex-col justify-center">
                    <div class="flex items-center gap-3 text-slate-600 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest">{{ __('Incompatible With') }}</span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed italic">
                        "{{ __($sector_data['not_ideal']) }}"
                    </p>
                </div>
            </div>
        </div>

        {{-- Call to Action --}}
        <div class="p-12 rounded-[4rem] bg-emerald-500 text-slate-950 text-center relative overflow-hidden group">
            <div class="relative z-10">
                <div class="flex justify-center mb-6">
                    <div class="p-3 bg-white/20 rounded-full backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-4xl md:text-5xl font-black mb-4 uppercase tracking-tighter">
                    {{ __('Secure Your Capital') }}</h2>
                <p class="text-slate-950/70 mb-10 max-w-xl mx-auto text-sm font-bold leading-relaxed">
                    {{ __('Initialize your conservative allocation strategy and start earning predictable, sovereign-backed yields today.') }}
                </p>
                <a href="{{ route('user.register') }}"
                    class="inline-flex items-center gap-3 px-10 py-5 rounded-2xl bg-slate-950 text-white font-black uppercase text-sm tracking-[0.2em] hover:bg-white hover:text-slate-950 transition-all transform hover:scale-105">
                    {{ __('Begin Allocation') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </a>
            </div>
            {{-- Abstract geometric pattern --}}
            <div
                class="absolute inset-0 opacity-10 pointer-events-none group-hover:scale-110 transition-transform duration-[3s]">
                <div class="absolute top-0 left-0 w-full h-full"
                    style="background-image: linear-gradient(30deg, #000 12%, transparent 12.5%, transparent 87%, #000 87.5%, #000), linear-gradient(150deg, #000 12%, transparent 12.5%, transparent 87%, #000 87.5%, #000), linear-gradient(30deg, #000 12%, transparent 12.5%, transparent 87%, #000 87.5%, #000), linear-gradient(150deg, #000 12%, transparent 12.5%, transparent 87%, #000 87.5%, #000), linear-gradient(60deg, #444 25%, transparent 25.5%, transparent 75%, #444 75%, #444), linear-gradient(60deg, #444 25%, transparent 25.5%, transparent 75%, #444 75%, #444); background-size: 40px 70px; background-position: 0 0, 0 0, 20px 35px, 20px 35px, 0 0, 20px 35px;">
                </div>
            </div>
        </div>
    </div>
@endsection
