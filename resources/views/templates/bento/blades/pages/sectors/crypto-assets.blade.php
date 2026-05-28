@extends('templates.bento.blades.layouts.front')
@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            {{-- Sector Context & Identity --}}
            <div
                class="lg:col-span-2 p-10 rounded-[2.5rem] bg-gradient-to-br from-indigo-900/40 to-blue-900/10 border border-white/10 backdrop-blur-xl relative overflow-hidden group">
                <div
                    class="absolute -top-24 -right-24 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl group-hover:bg-cyan-500/20 transition-all">
                </div>

                <div class="relative z-10">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 mb-6 font-bold text-xs text-cyan-400 uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                        {{ __('Digital Economy Sector') }}
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-white mb-6 uppercase tracking-tighter leading-none">
                        {{ __($page_title) }}
                    </h1>
                    <p class="text-xl text-text-secondary leading-relaxed mb-8 max-w-2xl font-light">
                        {{ __($sector_data['context']) }}
                    </p>
                    <div class="flex flex-wrap gap-4">
                        @foreach ($sector_data['psychology'] as $item)
                            <span
                                class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-xs text-white/60 font-semibold italic">
                                #{{ __($item) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Risk & Pulse Meters --}}
            <div class="space-y-8">
                <div class="p-8 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-md">
                    <div class="flex justify-between items-end mb-4">
                        <div class="text-[10px] text-white/40 uppercase tracking-[0.2em] font-bold">
                            {{ __('Market Volatility') }}</div>
                        <div class="text-2xl font-black text-white italic uppercase">{{ __($sector_data['volatility']) }}
                        </div>
                    </div>
                    <div class="h-2 bg-white/5 w-full rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-cyan-400 to-indigo-500 shadow-[0_0_15px_rgba(34,211,238,0.5)]"
                            style="width: 85%"></div>
                    </div>
                </div>

                <div class="p-8 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-md">
                    <div class="flex justify-between items-end mb-4">
                        <div class="text-[10px] text-white/40 uppercase tracking-[0.2em] font-bold">
                            {{ __('Risk Assessment') }}</div>
                        <div class="text-2xl font-black text-white italic uppercase">{{ __($sector_data['risk_level']) }}
                        </div>
                    </div>
                    <div class="h-2 bg-white/5 w-full rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-rose-500 shadow-[0_0_15px_rgba(244,63,94,0.5)]"
                            style="width: 92%"></div>
                    </div>
                </div>

                <div
                    class="p-8 rounded-[2rem] bg-indigo-600 shadow-[0_20px_40px_rgba(79,70,229,0.3)] flex items-center justify-between group cursor-pointer overflow-hidden relative">
                    <div class="relative z-10">
                        <div class="text-[10px] text-white/60 uppercase tracking-[0.2em] font-bold mb-1">
                            {{ __('Trading Goal') }}</div>
                        <div class="text-2xl font-black text-white uppercase italic leading-none">
                            {{ str_replace('_', ' ', __($sector_data['investment_goal'])) }}</div>
                    </div>
                    <div
                        class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-white group-hover:bg-white group-hover:text-indigo-600 transition-all relative z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </div>
                    <div
                        class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/5 rounded-full blur-2xl group-hover:scale-150 transition-transform">
                    </div>
                </div>
            </div>
        </div>

        {{-- Operational Logic (Educational Flow) --}}
        <div class="mb-12">
            <div
                class="p-12 rounded-[3rem] bg-white/[0.02] border border-white/10 backdrop-blur-3xl overflow-hidden relative">
                <div class="absolute top-0 right-0 p-8 text-[8px] text-white/10 uppercase font-mono tracking-widest">
                    {{ __('Instructional Framework // Protocol v1.4') }}</div>

                <h2 class="text-3xl font-black text-white mb-12 uppercase tracking-tight flex items-center gap-4">
                    <span class="w-12 h-[2px] bg-cyan-400"></span>
                    {{ __('Operational Logic') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 relative">
                    {{-- Guide Connectors (Desktop) --}}
                    <div
                        class="hidden md:block absolute top-6 left-12 right-12 h-[1px] bg-white/10 border-t border-dashed border-white/20">
                    </div>

                    @foreach ($sector_data['how_it_works'] as $index => $log)
                        <div class="relative z-10 group">
                            <div
                                class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-white font-black text-xl mb-6 group-hover:bg-cyan-500 group-hover:text-black transition-all duration-500">
                                {{ sprintf('%02d', $index + 1) }}
                            </div>
                            <p class="text-sm text-text-secondary leading-relaxed font-semibold">
                                {{ __($log) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Earnings & Suitability --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            {{-- Yield Mechanisms --}}
            <div class="lg:col-span-2 p-10 rounded-[2.5rem] bg-white/5 border border-white/10 backdrop-blur-md">
                <h3 class="text-2xl font-black text-white mb-10 uppercase tracking-tight flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="text-indigo-400">
                        <path d="M12 2v20" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                    {{ __('Yield Mechanisms') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($sector_data['earnings_generated_from'] as $label => $detail)
                        <div
                            class="p-6 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/[0.08] transition-all group">
                            <div
                                class="text-sm font-black text-white uppercase mb-2 tracking-wide group-hover:text-cyan-400">
                                {{ __(str_replace('_', ' ', $label)) }}
                            </div>
                            <div class="text-xs text-text-secondary leading-relaxed">
                                {{ __($detail) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Suitability --}}
            <div class="space-y-8">
                <div class="p-8 rounded-[2rem] bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-md">
                    <div class="flex items-center gap-3 text-emerald-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest">{{ __('Ideal For') }}</span>
                    </div>
                    <p class="text-sm text-text-secondary leading-relaxed italic">
                        "{{ __($sector_data['ideal_for']) }}"
                    </p>
                </div>

                <div class="p-8 rounded-[2rem] bg-rose-500/10 border border-rose-500/20 backdrop-blur-md">
                    <div class="flex items-center gap-3 text-rose-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-widest">{{ __('Not Ideal For') }}</span>
                    </div>
                    <p class="text-sm text-text-secondary leading-relaxed italic">
                        "{{ __($sector_data['not_ideal']) }}"
                    </p>
                </div>
            </div>
        </div>

        {{-- Market Tickers (Secondary Priority) --}}
        <div class="p-10 rounded-[3rem] bg-white/[0.02] border border-white/10 backdrop-blur-2xl">
            <div class="flex flex-col md:flex-row items-end justify-between mb-10 gap-6">
                <div>
                    <h2 class="text-3xl font-black text-white uppercase tracking-tight">{{ __('Market Tickers') }}</h2>
                    <p class="text-sm text-white/40 mt-2 uppercase tracking-widest">
                        {{ __('High-liquidity perpetual futures indices') }}</p>
                </div>
                <div class="flex items-center gap-3 px-4 py-2 rounded-xl bg-white/5 border border-white/10">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span
                        class="text-[10px] font-bold text-white/60 uppercase tracking-widest">{{ __('Live Feed') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach (array_slice($futures, 0, 20) as $asset)
                    <div
                        class="p-5 rounded-2xl bg-white/5 border border-white/5 hover:border-cyan-500/30 transition-all group overflow-hidden relative">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-white/5 p-1.5 flex items-center justify-center border border-white/10 group-hover:bg-white group-hover:invert transition-all duration-500">
                                <img loading="lazy" decoding="async" src="{{ 'https://raw.githubusercontent.com/spothq/cryptocurrency-icons/refs/heads/master/svg/color/' . $asset['logo'] }}"
                                    alt="{{ $asset['ticker'] }}" class="w-full h-full object-contain"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ $asset['ticker'] }}&background=6366f1&color=fff'">
                            </div>
                            <div
                                class="text-[9px] font-black text-cyan-400 border border-cyan-400/20 px-2 py-0.5 rounded-full uppercase tracking-tighter">
                                {{ __('Perp') }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="text-sm font-bold text-white tracking-widest uppercase">{{ $asset['ticker'] }}
                            </div>
                            <div class="text-[9px] text-white/30 truncate uppercase">{{ $asset['quote'] }}</div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="font-bold text-white text-xs font-mono tracking-tighter">
                                ${{ number_format($asset['current_price'], 4) }}</div>
                            @php $change = $asset['change_1d_percentage'] ?? 0; @endphp
                            <div
                                class="text-[10px] font-black {{ $change >= 0 ? 'text-emerald-400' : 'text-rose-400' }} flex items-center gap-1 ticker-glow-val">
                                {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 2) }}%
                            </div>
                        </div>

                        <a href="{{ auth()->check() ? route('user.trading.futures', $asset['ticker']) : route('user.register') }}"
                            class="absolute inset-0 z-10"></a>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('user.register') }}"
                    class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-white text-black font-black uppercase text-sm tracking-widest hover:bg-cyan-400 hover:text-black transition-all">
                    {{ __('Open Trading Account') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <style>
        .flash-ticker {
            animation: tickerFlash 0.6s ease-out;
        }

        @keyframes tickerFlash {
            0% {
                transform: scale(1);
                filter: brightness(1);
            }

            50% {
                transform: scale(1.15);
                filter: brightness(2.5) contrast(1.2);
            }

            100% {
                transform: scale(1);
                filter: brightness(1);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function triggerRandomFlashes() {
                const elements = document.querySelectorAll('.ticker-glow-val');
                if (elements.length === 0) return;

                // Randomly select 3 indices
                const indices = [];
                while (indices.length < 3 && indices.length < elements.length) {
                    const r = Math.floor(Math.random() * elements.length);
                    if (!indices.includes(r)) indices.push(r);
                }

                // Add flash class
                indices.forEach(idx => {
                    elements[idx].classList.add('flash-ticker');
                    // Remove after animation completes
                    setTimeout(() => {
                        elements[idx].classList.remove('flash-ticker');
                    }, 600);
                });

                // Schedule next flash at random interval (800ms to 3000ms)
                const nextInterval = Math.floor(Math.random() * (3000 - 800 + 1)) + 800;
                setTimeout(triggerRandomFlashes, nextInterval);
            }

            // Start the sequence after a small delay
            setTimeout(triggerRandomFlashes, 2000);
        });
    </script>
@endsection
