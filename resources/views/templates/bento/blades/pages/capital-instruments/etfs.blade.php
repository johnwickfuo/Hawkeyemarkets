@extends('templates.bento.blades.layouts.front')

@section('title', __($page_title))

@section('content')
    <div class="container mx-auto px-4 py-12 relative z-10">

        {{-- Section 1: Fund Scanner Table --}}
        <div class="mb-20">
            <div class="flex flex-col md:flex-row items-center justify-between mb-10 gap-6">
                <div>
                    <h2 class="text-3xl font-black text-white mb-2 font-heading tracking-tight">{{ __('Fund Scanner') }}
                    </h2>
                    <p class="text-text-secondary">
                        {{ __('Exchange-traded funds and diversified institutional portfolios.') }}
                    </p>
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-text-secondary group-focus-within:text-accent-primary transition-colors"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="11" cy="11" r="8" stroke-width="2.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m21 21-4.3-4.3" />
                        </svg>
                    </div>
                    <input type="text" id="etf-search" placeholder="{{ __('Search fund or ticker...') }}"
                        onkeyup="filterEtfs()"
                        class="w-full md:w-80 bg-white/[0.03] border border-white/10 rounded-2xl py-3 pl-10 pr-4 text-sm text-white placeholder-text-secondary focus:border-accent-primary/50 focus:ring-0 transition-all outline-none">
                </div>
            </div>

            @if (isset($message) && $message)
                <div
                    class="mb-10 p-6 rounded-[2rem] bg-red-500/5 border border-red-500/20 backdrop-blur-md flex items-center gap-6 animate-pulse-slow">
                    <div class="w-12 h-12 rounded-2xl bg-red-500/10 flex items-center justify-center text-red-500 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-red-400 tracking-wide uppercase">{{ $message }}</p>
                </div>
            @endif

            @if (count($etfs) > 0)
                <div class="relative bg-[#0f1115]/40 backdrop-blur-md border border-white/5 rounded-[2rem] overflow-hidden">
                    <div class="max-h-[1000px] overflow-y-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse" id="etf-table">
                            <thead class="sticky top-0 z-20 bg-[#0f1115] border-b border-white/10">
                                <tr>
                                    <th class="p-6 text-[10px] font-black text-text-secondary uppercase tracking-[0.2em] cursor-pointer hover:text-white transition-colors group"
                                        onclick="sortTable(0)">
                                        <div class="flex items-center gap-2">
                                            {{ __('Fund / Ticker') }}
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-3 h-3 opacity-0 group-hover:opacity-50 transition-opacity"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m7 15 5 5 5-5" />
                                                <path d="m7 9 5-5 5 5" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th class="p-6 text-[10px] font-black text-text-secondary uppercase tracking-[0.2em] cursor-pointer hover:text-white transition-colors group"
                                        onclick="sortTable(1, 'number')">
                                        <div class="flex items-center gap-2">
                                            {{ __('Price') }}
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-3 h-3 opacity-0 group-hover:opacity-50 transition-opacity"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m7 15 5 5 5-5" />
                                                <path d="m7 9 5-5 5 5" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th class="p-6 text-[10px] font-black text-text-secondary uppercase tracking-[0.2em] cursor-pointer hover:text-white transition-colors group"
                                        onclick="sortTable(2, 'number')">
                                        <div class="flex items-center gap-2">
                                            {{ __('1D Change') }}
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-3 h-3 opacity-0 group-hover:opacity-50 transition-opacity"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m7 15 5 5 5-5" />
                                                <path d="m7 9 5-5 5 5" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th class="p-6 text-[10px] font-black text-text-secondary uppercase tracking-[0.2em] cursor-pointer hover:text-white transition-colors group hidden md:table-cell"
                                        onclick="sortTable(3, 'number')"></th>
                                    <div class="flex items-center gap-2">
                                        {{ __('Yield') }}
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-3 h-3 opacity-0 group-hover:opacity-50 transition-opacity"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m7 15 5 5 5-5" />
                                            <path d="m7 9 5-5 5 5" />
                                        </svg>
                                    </div>
                                    </th>
                                    <th
                                        class="p-6 text-[10px] font-black text-text-secondary uppercase tracking-[0.2em] text-right">
                                        {{ __('Action') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="etf-list">
                                @foreach ($etfs as $etf)
                                    <tr class="etf-item border-b border-white/5 hover:bg-white/[0.02] transition-colors group/row"
                                        data-name="{{ strtolower($etf['name']) }}"
                                        data-ticker="{{ strtolower($etf['ticker']) }}">

                                        {{-- Fund --}}
                                        <td class="p-6">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-accent-primary/5 flex items-center justify-center text-accent-primary group-hover/row:scale-110 transition-transform overflow-hidden p-1 border border-white/5">
                                                    @if (isset($etf['public_png_logo_url']) && $etf['public_png_logo_url'])
                                                        <img loading="lazy" decoding="async" src="{{ $etf['public_png_logo_url'] }}"
                                                            alt="{{ $etf['ticker'] }}"
                                                            class="w-full h-full object-contain filter grayscale group-hover/row:grayscale-0 transition-all">
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2.5"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                                            <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span
                                                        class="block text-sm font-bold text-white group-hover/row:text-accent-primary transition-colors">{{ $etf['ticker'] }}</span>
                                                    <span
                                                        class="text-[10px] text-text-secondary uppercase font-bold tracking-widest">
                                                        {{ \Illuminate\Support\Str::limit($etf['name'], 30) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Price --}}
                                        <td class="p-6" data-value="{{ $etf['current_price'] }}">
                                            <span class="text-sm font-mono font-black text-white">
                                                ${{ number_format($etf['current_price'], 2) }}
                                            </span>
                                        </td>

                                        {{-- 1D Change --}}
                                        <td class="p-6" data-value="{{ $etf['change_1d_percentage'] }}">
                                            <div
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg {{ $etf['change_1d_percentage'] >= 0 ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                                    class="{{ $etf['change_1d_percentage'] >= 0 ? '' : 'rotate-180' }}">
                                                    <polyline points="18 15 12 9 6 15"></polyline>
                                                </svg>
                                                <span
                                                    class="text-[10px] font-black tracking-widest">{{ number_format($etf['change_1d_percentage'], 2) }}%</span>
                                            </div>
                                        </td>

                                        {{-- Yield --}}
                                        <td class="p-6 hidden md:table-cell" data-value="{{ $etf['dividend_yield'] }}">
                                            <span class="text-sm font-mono font-bold text-accent-secondary">
                                                {{ number_format($etf['dividend_yield'], 2) }}%
                                            </span>
                                        </td>

                                        {{-- CTA --}}
                                        <td class="p-6 text-right">
                                            <a href="{{ auth()->check() ? route('user.capital-instruments.etfs.buy', $etf['ticker']) : route('user.login') }}"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest hover:bg-accent-primary hover:border-accent-primary transition-all group/btn">
                                                {{ __('Purchase') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                                    class="translate-x-0 group-hover/btn:translate-x-1 transition-transform">
                                                    <line x1="5" y1="12" x2="19" y2="12">
                                                    </line>
                                                    <polyline points="12 5 19 12 12 19"></polyline>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-20 bg-white/[0.02] border border-white/5 rounded-3xl">
                    <div
                        class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6 opacity-30">
                        <svg class="w-10 h-10 text-text-secondary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">{{ __('Funds Offline') }}</h3>
                    <p class="text-text-secondary max-w-sm mx-auto">
                        {{ __('The ETF index is currently being recalibrated. Please check back shortly.') }}
                    </p>
                </div>
            @endif
        </div>

        {{-- Section 2: Cinematic Analytics Header --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
            <div class="lg:col-span-2 relative group">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-accent-primary/20 to-accent-secondary/20 rounded-3xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity duration-700">
                </div>
                <div
                    class="relative h-full bg-[#0f1115]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-8 md:p-12 flex flex-col justify-center overflow-hidden">
                    <div
                        class="absolute top-0 right-0 p-8 text-[12rem] font-bold text-white/[0.02] leading-none pointer-events-none select-none font-heading">
                        {{ __('ETF') }}
                    </div>
                    <div
                        class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')] opacity-10 pointer-events-none">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-6 backdrop-blur-md">
                            <span class="w-2 h-2 rounded-full bg-accent-primary animate-pulse"></span>
                            <span
                                class="text-xs font-bold text-accent-primary tracking-widest uppercase">{{ __('Basket Exposure') }}</span>
                        </div>
                        <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight font-heading">
                            {{ __('Diversified Growth.') }} <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">{{ __('Simplified.') }}</span>
                        </h1>
                        <p class="text-text-secondary text-lg max-w-xl leading-relaxed">
                            {{ __('Access entire sectors, commodities, or global indices with a single trade. Institutional-grade portfolios, optimized for retail efficiency.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div
                    class="bg-[#0f1115]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 group hover:border-accent-primary/50 transition-all">
                    <h3 class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-4 opacity-70">
                        {{ __('Expense Efficiency') }}</h3>
                    <div class="flex items-end justify-between mb-3">
                        <span class="text-2xl font-black text-white font-heading">{{ __('Low-Cost') }}</span>
                        <span class="text-xs text-accent-primary font-bold uppercase">{{ __('0.05% Avg') }}</span>
                    </div>
                    <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                        <div
                            class="h-full bg-accent-primary rounded-full w-[95%] shadow-[0_0_15px_rgba(var(--color-accent-primary),0.3)]">
                        </div>
                    </div>
                </div>
                <div
                    class="bg-[#0f1115]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 group hover:border-accent-primary/50 transition-all">
                    <h3 class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 opacity-70">
                        {{ __('Risk Dispersion') }}</h3>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-accent-primary/10 flex items-center justify-center text-accent-primary group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xl font-black text-white font-heading">{{ __('High Matrix') }}</span>
                            <span
                                class="text-[10px] text-text-secondary uppercase font-bold tracking-tight">{{ __('Cross-Sector Yield') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: ETF Mechanics --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-20">
            <div class="relative group">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-accent-primary/20 to-transparent rounded-[2rem] blur opacity-0 group-hover:opacity-100 transition-opacity">
                </div>
                <div
                    class="relative bg-[#0B0F17]/60 backdrop-blur-xl border border-white/10 rounded-[2rem] p-10 overflow-hidden">
                    <h3 class="text-2xl font-black text-white mb-8 font-heading tracking-tight flex items-center gap-4">
                        <span class="w-1.5 h-8 bg-accent-primary rounded-full"></span>
                        {{ __('Alpha Architecture') }}
                    </h3>
                    <div class="space-y-6">
                        @php
                            $mechanics = [
                                'Index Precision' =>
                                    'Automated tracking of benchmark indices with minimal error, ensuring your returns mirror institutional performance.',
                                'Intraday Agility' =>
                                    'Trade funds like individual stocks through our high-speed execution engine, capturing micro-movements in real-time.',
                                'Dynamic Rebalancing' =>
                                    'Proprietary algorithms ensure underlying assets are perfectly weighted to match fund objectives continuously.',
                                'Tax Optimization' =>
                                    'Leverage the unique structure of ETFs to minimize capital gains exposure compared to mutual fund alternatives.',
                            ];
                        @endphp
                        @foreach ($mechanics as $title => $desc)
                            <div
                                class="flex gap-6 p-6 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-colors group/item">
                                <div
                                    class="w-10 h-10 rounded-xl bg-accent-primary/10 flex items-center justify-center text-accent-primary flex-shrink-0 group-hover/item:scale-110 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">{{ __($title) }}</h4>
                                    <p class="text-xs text-text-secondary leading-relaxed">{{ __($desc) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-8">
                <div
                    class="flex-1 bg-gradient-to-br from-indigo-500/10 to-transparent border border-indigo-500/10 rounded-[2rem] p-10 flex flex-col justify-center group hover:border-indigo-500/30 transition-all duration-500">
                    <h3 class="text-2xl font-black text-white mb-4 font-heading tracking-tight">{{ __('Instant Reach') }}
                    </h3>
                    <p class="text-text-secondary leading-relaxed">
                        {{ __('Eliminate the complexity of stock picking. Gain immediate exposure to the S&P 500, Nasdaq 100, or specialized tech sectors with a single click.') }}
                    </p>
                </div>
                <div
                    class="flex-1 bg-gradient-to-br from-accent-primary/10 to-transparent border border-accent-primary/10 rounded-[2rem] p-10 flex flex-col justify-center group hover:border-accent-primary/30 transition-all duration-500">
                    <h3 class="text-2xl font-black text-white mb-4 font-heading tracking-tight">
                        {{ __('Liquidity Nexus') }}</h3>
                    <p class="text-text-secondary leading-relaxed">
                        {{ __('Our deep liquidity pools ensure that even large-scale portfolio adjustments are executed with minimal slippage and maximum transparency.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Final CTA --}}
        <div class="relative rounded-[3rem] bg-[#030303] border border-white/5 p-12 md:p-20 text-center overflow-hidden">
            <div
                class="absolute inset-0 bg-gradient-to-br from-accent-primary/10 via-transparent to-accent-secondary/5 opacity-50">
            </div>
            <div class="relative z-10 max-w-2xl mx-auto">
                <span
                    class="inline-block px-4 py-1.5 rounded-full bg-accent-primary text-white text-[10px] font-black uppercase tracking-[0.2em] mb-8 animate-bounce">
                    {{ __('Deploy Capital') }}
                </span>
                <h2 class="text-4xl md:text-6xl font-black text-white mb-8 leading-tight font-heading">
                    {{ __('Ready to Own the') }} <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-accent-primary to-accent-secondary">{{ __('Global Economy?') }}</span>
                </h2>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="{{ route('user.register') }}"
                        class="w-full sm:w-auto px-10 py-5 bg-white text-primary-dark rounded-2xl font-black text-lg hover:scale-105 active:scale-95 transition-all outline-none">
                        {{ __('Access Funds') }}
                    </a>
                    <a href="{{ route('contact') }}"
                        class="w-full sm:w-auto px-10 py-5 bg-white/5 border border-white/10 text-white rounded-2xl font-black text-lg hover:bg-white/10 active:scale-95 transition-all backdrop-blur-md outline-none">
                        {{ __('Consult Strategist') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentSortColumn = -1;
        let isAscending = true;

        function filterEtfs() {
            const input = document.getElementById('etf-search');
            const filter = input.value.toLowerCase();
            const items = document.getElementsByClassName('etf-item');

            Array.from(items).forEach(item => {
                const name = item.getAttribute('data-name');
                const ticker = item.getAttribute('data-ticker');

                if (name.includes(filter) || ticker.includes(filter)) {
                    item.style.display = 'table-row';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function sortTable(columnIndex, type = 'string') {
            const table = document.getElementById('etf-table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            if (currentSortColumn === columnIndex) {
                isAscending = !isAscending;
            } else {
                currentSortColumn = columnIndex;
                isAscending = true;
            }

            rows.sort((a, b) => {
                let valA, valB;
                if (type === 'number') {
                    valA = parseFloat(a.children[columnIndex].getAttribute('data-value')) || 0;
                    valB = parseFloat(b.children[columnIndex].getAttribute('data-value')) || 0;
                } else {
                    valA = a.children[columnIndex].innerText.trim().toLowerCase();
                    valB = b.children[columnIndex].innerText.trim().toLowerCase();
                }
                if (valA < valB) return isAscending ? -1 : 1;
                if (valA > valB) return isAscending ? 1 : -1;
                return 0;
            });

            rows.forEach(row => tbody.appendChild(row));

            table.querySelectorAll('th').forEach((th, idx) => {
                if (idx === columnIndex) {
                    th.classList.add('text-accent-primary');
                    th.classList.remove('text-text-secondary');
                } else {
                    th.classList.remove('text-accent-primary');
                    th.classList.add('text-text-secondary');
                }
            });
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 195, 255, 0.3);
        }
    </style>
@endpush
