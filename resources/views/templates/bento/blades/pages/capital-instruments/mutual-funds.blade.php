@extends('templates.bento.blades.layouts.front')

@section('title', __($page_title))

@section('content')
    <div class="container mx-auto px-4 py-12 relative z-10">

        {{-- Section 1: Fund Scanner Table --}}
        <div class="mb-20">
            <div class="flex flex-col md:flex-row items-center justify-between mb-10 gap-6">
                <div>
                    <h2 class="text-3xl font-black text-white mb-2 font-heading tracking-tight">{{ __('Portfolio Scanner') }}
                    </h2>
                    <p class="text-text-secondary">{{ __('Open-end professionally managed institutional funds.') }}
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
                    <input type="text" id="fund-search" placeholder="{{ __('Search fund or ticker...') }}"
                        onkeyup="filterFunds()"
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

            @if (count($mutual_funds) > 0)
                <div class="relative bg-[#0f1115]/40 backdrop-blur-md border border-white/5 rounded-[2rem] overflow-hidden">
                    <div class="max-h-[1000px] overflow-y-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse" id="fund-table">
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
                                            {{ __('NAV') }}
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
                                            {{ __('1D %') }}
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
                                        onclick="sortTable(3, 'number')">
                                        <div class="flex items-center gap-2">
                                            {{ __('Expense Ratio') }}
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
                                        onclick="sortTable(4, 'number')">
                                        <div class="flex items-center gap-2">
                                            {{ __('AUM') }}
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
                                        onclick="sortTable(5, 'number')">
                                        <div class="flex items-center gap-2">
                                            {{ __('YTD Return') }}
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-3 h-3 opacity-0 group-hover:opacity-50 transition-opacity"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
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
                            <tbody id="fund-list">
                                @foreach ($mutual_funds as $fund)
                                    <tr class="fund-item border-b border-white/5 hover:bg-white/[0.02] transition-colors group/row"
                                        data-name="{{ strtolower($fund['name'] ?? '') }}"
                                        data-ticker="{{ strtolower($fund['ticker'] ?? ($fund['symbol'] ?? '')) }}">

                                        {{-- Fund --}}
                                        <td class="p-6">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-accent-primary/5 flex items-center justify-center text-accent-primary group-hover/row:scale-110 transition-transform overflow-hidden p-1 border border-white/5">
                                                    @if (isset($fund['public_png_logo_url']) && $fund['public_png_logo_url'])
                                                        <img loading="lazy" decoding="async" src="{{ $fund['public_png_logo_url'] }}"
                                                            alt="{{ $fund['ticker'] ?? '' }}"
                                                            class="w-full h-full object-contain filter grayscale group-hover/row:grayscale-0 transition-all">
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2.5"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path
                                                                d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5">
                                                            </path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span
                                                        class="block text-sm font-bold text-white group-hover/row:text-accent-primary transition-colors">{{ $fund['ticker'] ?? ($fund['symbol'] ?? 'N/A') }}</span>
                                                    <span
                                                        class="text-[10px] text-text-secondary uppercase font-bold tracking-widest">
                                                        {{ \Illuminate\Support\Str::limit($fund['name'] ?? 'Untitled Fund', 30) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- NAV / Price --}}
                                        <td class="p-6"
                                            data-value="{{ $fund['current_nav'] ?? ($fund['price'] ?? ($fund['nav'] ?? 0)) }}">
                                            <span class="text-sm font-mono font-black text-white">
                                                ${{ number_format($fund['current_nav'] ?? ($fund['price'] ?? ($fund['nav'] ?? 0)), 2) }}
                                            </span>
                                        </td>

                                        {{-- 1D Change --}}
                                        @php
                                            $change =
                                                $fund['change_1d_percentage'] ?? ($fund['changesPercentage'] ?? 0);
                                        @endphp
                                        <td class="p-6" data-value="{{ $change }}">
                                            <div
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg {{ $change >= 0 ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                                    class="{{ $change >= 0 ? '' : 'rotate-180' }}">
                                                    <polyline points="18 15 12 9 6 15"></polyline>
                                                </svg>
                                                <span
                                                    class="text-[10px] font-black tracking-widest">{{ number_format($change, 2) }}%</span>
                                            </div>
                                        </td>

                                        {{-- Expense Ratio --}}
                                        <td class="p-6" data-value="{{ $fund['expense_ratio'] ?? 0 }}">
                                            <span class="text-sm font-mono font-black text-text-secondary">
                                                {{ number_format($fund['expense_ratio'] ?? 0, 2) }}%
                                            </span>
                                        </td>

                                        {{-- AUM --}}
                                        <td class="p-6" data-value="{{ $fund['assets_under_management'] ?? 0 }}">
                                            <span class="text-sm font-mono font-black text-white/60">
                                                @php
                                                    $aum = $fund['assets_under_management'] ?? 0;
                                                    if ($aum >= 1000000000000) {
                                                        $aum_formatted = number_format($aum / 1000000000000, 2) . 'T';
                                                    } elseif ($aum >= 1000000000) {
                                                        $aum_formatted = number_format($aum / 1000000000, 2) . 'B';
                                                    } elseif ($aum >= 1000000) {
                                                        $aum_formatted = number_format($aum / 1000000, 2) . 'M';
                                                    } else {
                                                        $aum_formatted = number_format($aum);
                                                    }
                                                @endphp
                                                ${{ $aum_formatted }}
                                            </span>
                                        </td>

                                        {{-- YTD Return --}}
                                        @php
                                            $ytd = $fund['ytd_return'] ?? 0;
                                        @endphp
                                        <td class="p-6" data-value="{{ $ytd }}">
                                            <span
                                                class="text-sm font-mono font-black {{ $ytd >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                                {{ $ytd >= 0 ? '+' : '' }}{{ number_format($ytd, 2) }}%
                                            </span>
                                        </td>

                                        {{-- CTA --}}
                                        <td class="p-6 text-right">
                                            <a href="{{ auth()->check() ? route('user.capital-instruments.mutual-funds.buy', $fund['ticker'] ?? ($fund['symbol'] ?? '')) : route('user.login') }}"
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
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">{{ __('Portfolios Offline') }}</h3>
                    <p class="text-text-secondary max-w-sm mx-auto">
                        {{ __('The mutual fund ledger is currently undergoing standard synchronization.') }}
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
                        {{ __('MF') }}
                    </div>
                    <div
                        class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')] opacity-10 pointer-events-none">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-6 backdrop-blur-md">
                            <span class="w-2 h-2 rounded-full bg-accent-primary animate-pulse"></span>
                            <span
                                class="text-xs font-bold text-accent-primary tracking-widest uppercase">{{ __('Managed Wealth') }}</span>
                        </div>
                        <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight font-heading">
                            {{ __('Institutional.') }} <br>
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">{{ __('Compound Alpha.') }}</span>
                        </h1>
                        <p class="text-text-secondary text-lg max-w-xl leading-relaxed">
                            {{ __('Leverage the expertise of world-class fund managers to navigate complex market cycles. Diversified, professionally orchestrated, and built for the long term.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div
                    class="bg-[#0f1115]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 group hover:border-accent-primary/50 transition-all">
                    <h3 class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-4 opacity-70">
                        {{ __('Management Tier') }}</h3>
                    <div class="flex items-end justify-between mb-3">
                        <span class="text-2xl font-black text-white font-heading">{{ __('Expert') }}</span>
                        <span class="text-xs text-accent-primary font-bold uppercase">{{ __('Active Strategy') }}</span>
                    </div>
                    <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                        <div
                            class="h-full bg-accent-primary rounded-full w-[85%] shadow-[0_0_15px_rgba(var(--color-accent-primary),0.3)]">
                        </div>
                    </div>
                </div>
                <div
                    class="bg-[#0f1115]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 group hover:border-accent-primary/50 transition-all">
                    <h3 class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 opacity-70">
                        {{ __('Historical Consistency') }}</h3>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-accent-primary/10 flex items-center justify-center text-accent-primary group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M22 17H2L11 2l9 15z"></path>
                                <path d="M11 2v15"></path>
                            </svg>
                        </div>
                        <div>
                            <span
                                class="block text-xl font-black text-white font-heading">{{ __('Stability Core') }}</span>
                            <span
                                class="text-[10px] text-text-secondary uppercase font-bold tracking-tight">{{ __('Equity Dispersion') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: fund Mechanics --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-20">
            <div class="relative group">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-accent-primary/20 to-transparent rounded-[2rem] blur opacity-0 group-hover:opacity-100 transition-opacity">
                </div>
                <div
                    class="relative bg-[#0B0F17]/60 backdrop-blur-xl border border-white/10 rounded-[2rem] p-10 overflow-hidden">
                    <h3 class="text-2xl font-black text-white mb-8 font-heading tracking-tight flex items-center gap-4">
                        <span class="w-1.5 h-8 bg-accent-primary rounded-full"></span>
                        {{ __('Stewardship Logic') }}
                    </h3>
                    <div class="space-y-6">
                        @php
                            $mechanics = [
                                'Professional Guardrails' =>
                                    'Each fund is governed by a dedicated team of analysts and managers, continuously optimizing for risk-adjusted returns.',
                                'Dynamic Allocation' =>
                                    'Capital is systematically rotated across sectors and asset classes to capitalize on shifting macro-economic trends.',
                                'Scale Efficiency' =>
                                    'Leverage the collective buying power of institutional pools to access instruments and pricing unavailable to individuals.',
                                'Systematic Reinvestment' =>
                                    'Automated distribution reinvestment protocols to maximize the power of longitudinal compounding.',
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
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <polyline points="17 11 19 13 23 9"></polyline>
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
                    <h3 class="text-2xl font-black text-white mb-4 font-heading tracking-tight">
                        {{ __('Passive Leverage') }}
                    </h3>
                    <p class="text-text-secondary leading-relaxed">
                        {{ __('Remove the psychological burden of daily market noise. Our mutual funds offer a "set it and forget it" approach to high-fidelity wealth building.') }}
                    </p>
                </div>
                <div
                    class="flex-1 bg-gradient-to-br from-accent-primary/10 to-transparent border border-accent-primary/10 rounded-[2rem] p-10 flex flex-col justify-center group hover:border-accent-primary/30 transition-all duration-500">
                    <h3 class="text-2xl font-black text-white mb-4 font-heading tracking-tight">
                        {{ __('Asset Orchestration') }}</h3>
                    <p class="text-text-secondary leading-relaxed">
                        {{ __('From emerging market debt to global technology leaders, our fund catalog provides the exact building blocks needed for a resilient portfolio.') }}
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
                    {{ __('compound Wealth') }}
                </span>
                <h2 class="text-4xl md:text-6xl font-black text-white mb-8 leading-tight font-heading">
                    {{ __('Architect Your') }} <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-accent-primary to-accent-secondary">{{ __('Managed Legacy.') }}</span>
                </h2>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="{{ route('user.register') }}"
                        class="w-full sm:w-auto px-10 py-5 bg-white text-primary-dark rounded-2xl font-black text-lg hover:scale-105 active:scale-95 transition-all outline-none">
                        {{ __('Open Account') }}
                    </a>
                    <a href="{{ route('contact') }}"
                        class="w-full sm:w-auto px-10 py-5 bg-white/5 border border-white/10 text-white rounded-2xl font-black text-lg hover:bg-white/10 active:scale-95 transition-all backdrop-blur-md outline-none">
                        {{ __('Speak with Advisor') }}
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

        function filterFunds() {
            const input = document.getElementById('fund-search');
            const filter = input.value.toLowerCase();
            const items = document.getElementsByClassName('fund-item');

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
            const table = document.getElementById('fund-table');
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
