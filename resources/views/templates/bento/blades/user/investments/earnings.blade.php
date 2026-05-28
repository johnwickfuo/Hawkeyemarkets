@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="space-y-8">
        {{-- Header & Smart Insights --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Header Card --}}
            <div
                class="lg:col-span-1 bg-secondary border border-white/5 rounded-2xl p-6 relative overflow-hidden flex flex-col justify-between">
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold text-white mb-2">{{ __('Investment Earnings') }}</h2>
                    <p class="text-text-secondary text-sm leading-relaxed">
                        {{ __('Detailed analytics of your portfolio performance over time.') }}
                    </p>
                </div>

                <div class="mt-8 relative z-10">
                    <p class="text-text-secondary text-xs uppercase tracking-wider font-semibold mb-1">
                        {{ __('Total Earned') }}</p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ number_format($analytics['total_earned'], getSetting('decimal_places', 2)) }} <span
                            class="text-lg font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                    </h3>
                </div>

                {{-- Decorative Background --}}
                <div
                    class="absolute top-0 right-0 -mt-8 -mr-8 w-40 h-40 bg-accent-primary/10 rounded-full blur-3xl pointer-events-none">
                </div>
                <div
                    class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl pointer-events-none">
                </div>
            </div>

            {{-- Smart Insights --}}
            {{-- Smart Insights (Swipeable) --}}
            {{-- Smart Insights (Swipeable Stack) --}}
            <div
                class="lg:col-span-2 bg-gradient-to-br from-secondary to-secondary/80 border border-white/5 rounded-2xl relative overflow-hidden flex flex-col h-[320px]">
                <div class="px-6 pt-6 mb-2 flex items-center justify-between z-10">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-accent-primary/20 rounded-lg text-accent-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-white">{{ __('Smart Insights') }}</h3>
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="swiper-button-prev-custom p-2 rounded-full bg-white/5 hover:bg-white/10 text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                        </button>
                        <button
                            class="swiper-button-next-custom p-2 rounded-full bg-white/5 hover:bg-white/10 text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Swiper Container --}}
                <div class="swiper mySwiper w-full h-full pb-10 px-4 sm:px-14">
                    <div class="swiper-wrapper">
                        @forelse($analytics['insights'] ?? [] as $index => $insight)
                            <div class="swiper-slide rounded-2xl p-6 flex flex-col justify-between relative overflow-hidden group shadow-2xl border border-white/10"
                                style="background: linear-gradient(135deg, {{ $index % 2 == 0 ? '#1e293b, #0f172a' : '#334155, #1e293b' }});">

                                {{-- Smart Illustration (Blended) --}}
                                <div
                                    class="absolute top-1/2 right-0 transform -translate-y-1/2 translate-x-1/4 opacity-10 pointer-events-none">
                                    @switch($insight['type'] ?? '')
                                        @case('trend_up')
                                            {{-- Trend Up Graph --}}
                                            <svg class="w-48 h-48 text-emerald-400" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="0.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                        @break

                                        @case('trend_down')
                                            {{-- Trend Down Graph --}}
                                            <svg class="w-48 h-48 text-red-400" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="0.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                            </svg>
                                        @break

                                        @case('source')
                                            {{-- Pie Chart / Source --}}
                                            <svg class="w-48 h-48 text-accent-primary" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="0.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                            </svg>
                                        @break

                                        @case('frequency')
                                            {{-- Calendar / Frequency --}}
                                            <svg class="w-48 h-48 text-blue-400" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="0.5">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                                <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01" />
                                            </svg>
                                        @break

                                        @case('best_day')
                                            {{-- Star / Trophy --}}
                                            <svg class="w-48 h-48 text-yellow-400" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="0.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        @break

                                        @default
                                            {{-- Default Abstract --}}
                                            <svg class="w-48 h-48 text-white/20" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="0.5">
                                                <circle cx="12" cy="12" r="10" />
                                                <path d="M12 6v6l4 2" />
                                            </svg>
                                    @endswitch
                                </div>

                                {{-- Abstract Decor (Blur) --}}
                                <div
                                    class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-accent-primary/10 rounded-full blur-3xl">
                                </div>
                                <div
                                    class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl">
                                </div>

                                <div class="relative z-10">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div
                                            class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/10">
                                            {{-- Icon matching type --}}
                                            @if (($insight['type'] ?? '') === 'trend_up')
                                                <svg class="w-5 h-5 text-emerald-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                </svg>
                                            @elseif(($insight['type'] ?? '') === 'trend_down')
                                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                                </svg>
                                            @elseif(($insight['type'] ?? '') === 'source')
                                                <svg class="w-5 h-5 text-accent-primary" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                                </svg>
                                            @elseif(($insight['type'] ?? '') === 'frequency')
                                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <rect x="3" y="4" width="18" height="18" rx="2"
                                                        ry="2" stroke-width="2" />
                                                    <line x1="16" y1="2" x2="16" y2="6"
                                                        stroke-width="2" />
                                                    <line x1="8" y1="2" x2="8" y2="6"
                                                        stroke-width="2" />
                                                    <line x1="3" y1="10" x2="21" y2="10"
                                                        stroke-width="2" />
                                                </svg>
                                            @elseif(($insight['type'] ?? '') === 'best_day')
                                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-accent-primary" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <span
                                            class="text-xs font-bold text-accent-primary uppercase tracking-widest">{{ __('Insight') }}
                                            #{{ $insight['index'] }}</span>
                                    </div>

                                    <p class="text-white font-medium text-xl md:text-2xl leading-snug drop-shadow-md">
                                        {{ $insight['text'] }}
                                    </p>
                                </div>

                                <div
                                    class="relative z-10 mt-auto pt-6 border-t border-white/5 flex justify-between items-center text-xs text-text-secondary">
                                    <span>{{ __('Generated just now') }}</span>
                                    <span class="font-mono opacity-50">AI-DRIVEN</span>
                                </div>
                            </div>
                            @empty
                                <div
                                    class="swiper-slide bg-secondary border border-white/10 rounded-2xl p-6 flex items-center justify-center">
                                    <p class="text-text-secondary">
                                        {{ __('Gathering data for insights... Check back later.') }}
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Custom CSS for Swiper Cards --}}
                    <style>
                        .swiper-slide {
                            display: flex;
                            flex-direction: column;
                            width: 320px;
                            /* Fixed width for cards effect look */
                            height: 200px;
                        }

                        /* On responsive, adjust */
                        @media (max-width: 640px) {
                            .swiper-slide {
                                width: 220px;
                                /* Further reduced width */
                                height: 240px;
                                /* Taller for better proportion */
                            }
                        }
                    </style>
                </div>
            </div>

            {{-- Metrics Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Today --}}
                <div class="bg-secondary border border-white/5 rounded-xl p-5">
                    <p class="text-text-secondary text-xs uppercase font-bold tracking-wider mb-2">{{ __('Earned Today') }}
                    </p>
                    <div class="flex items-end justify-between">
                        <h4 class="text-xl font-bold text-white">
                            {{ number_format($analytics['earned_today'], getSetting('decimal_places', 2)) }} <span
                                class="text-xs font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                        </h4>
                        <span class="text-xs text-text-secondary">{{ date('D, M d') }}</span>
                    </div>
                </div>

                {{-- Last 7 Days --}}
                <div class="bg-secondary border border-white/5 rounded-xl p-5">
                    <p class="text-text-secondary text-xs uppercase font-bold tracking-wider mb-2">{{ __('Last 7 Days') }}</p>
                    <div class="flex items-end justify-between">
                        <h4 class="text-xl font-bold text-white">
                            {{ number_format($analytics['earned_7d'], getSetting('decimal_places', 2)) }} <span
                                class="text-xs font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                        </h4>
                        <div
                            class="flex items-center gap-1 {{ $analytics['change_7d'] >= 0 ? 'text-emerald-400' : 'text-red-400' }} text-xs font-bold">
                            <span>{{ $analytics['change_7d'] > 0 ? '+' : '' }}{{ $analytics['change_7d'] }}%</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $analytics['change_7d'] >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Last 30 Days --}}
                <div class="bg-secondary border border-white/5 rounded-xl p-5">
                    <p class="text-text-secondary text-xs uppercase font-bold tracking-wider mb-2">{{ __('Last 30 Days') }}
                    </p>
                    <div class="flex items-end justify-between">
                        <h4 class="text-xl font-bold text-white">
                            {{ number_format($analytics['earned_30d'], getSetting('decimal_places', 2)) }} <span
                                class="text-xs font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                        </h4>
                        <div
                            class="flex items-center gap-1 {{ $analytics['change_30d'] >= 0 ? 'text-emerald-400' : 'text-red-400' }} text-xs font-bold">
                            <span>{{ $analytics['change_30d'] > 0 ? '+' : '' }}{{ $analytics['change_30d'] }}%</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $analytics['change_30d'] >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Avg Daily --}}
                <div class="bg-secondary border border-white/5 rounded-xl p-5">
                    <p class="text-text-secondary text-xs uppercase font-bold tracking-wider mb-2">
                        {{ __('Avg Daily Earnings') }}</p>
                    <div class="flex items-end justify-between">
                        <h4 class="text-xl font-bold text-white">
                            {{ number_format($analytics['avg_daily_30d'], getSetting('decimal_places', 2)) }} <span
                                class="text-xs font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                        </h4>
                        <span class="text-xs px-2 py-0.5 rounded bg-white/5 text-text-secondary">{{ __('30d Avg') }}</span>
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Trend Chart --}}
                <div class="lg:col-span-2 bg-secondary border border-white/5 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-white font-bold flex items-center gap-2">
                            <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                            {{ __('Performance Trend') }}
                        </h3>
                    </div>
                    <div id="earningsTrendChart" class="w-full h-[320px]"></div>
                </div>

                {{-- Breakdown Chart --}}
                <div class="lg:col-span-1 bg-secondary border border-white/5 rounded-2xl p-6 flex flex-col">
                    <h3 class="text-white font-bold mb-6 text-center">{{ __('Earnings by Interest') }}</h3>
                    <div class="flex-1 flex items-center justify-center relative min-h-[300px]">
                        <div id="breakdownChart" class="w-full h-[280px]"></div>
                    </div>
                </div>
            </div>

            {{-- History Table --}}
            <div class="bg-secondary border border-white/5 rounded-2xl overflow-hidden relative"
                id="earnings-history-wrapper">
                {{-- Loading Spinner Overlay --}}
                <div id="earnings-loading-spinner"
                    class="hidden absolute inset-0 bg-secondary/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-opacity duration-300">
                    <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin">
                    </div>
                    <p class="mt-3 text-text-secondary font-medium animate-pulse">{{ __('Loading records...') }}</p>
                </div>

                <div class="p-6 border-b border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('Recent Earnings') }}
                    </h3>
                    <div class="text-sm text-text-secondary">
                        {{ __('Showing recent earnings') }}
                    </div>
                </div>

                <div id="earnings-table-content">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/5">
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                        {{ __('Date') }}</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                        {{ __('How') }}</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                        {{ __('Plan / Source') }}</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                        {{ __('Details') }}</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-bold text-text-secondary uppercase tracking-wider">
                                        {{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($investment_earnings as $earning)
                                    <tr class="hover:bg-white/5 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="text-sm text-white font-medium block">{{ $earning->created_at->format('M d, Y') }}</span>
                                            <span
                                                class="text-xs text-text-secondary">{{ $earning->created_at->format('H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($earning->note)
                                                <div class="relative max-w-xs group cursor-help"
                                                    title="{{ $earning->note }}">
                                                    <div
                                                        class="bg-white/5 hover:bg-white/10 text-text-secondary text-sm px-4 py-3 rounded-2xl rounded-tl-sm transition-colors duration-300 relative z-10 border border-white/5">
                                                        <p class="font-medium italic leading-relaxed line-clamp-2">
                                                            "{{ $earning->note }}"</p>
                                                    </div>
                                                    {{-- Bubble Tail --}}
                                                    <div
                                                        class="absolute top-0 -left-1.5 w-3 h-3 bg-white/5 border-l border-t border-white/5 transform scale-x-50 rotate-45 rounded-sm z-0">
                                                    </div>
                                                </div>
                                            @else
                                                <span
                                                    class="text-text-secondary text-sm opacity-50">{{ __('No note') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="text-white font-medium block">{{ $earning->investment->plan->name ?? __('Unknown Plan') }}</span>
                                            <span
                                                class="text-xs text-text-secondary badge badge-outline mt-1 inline-block border border-white/10 px-2 py-0.5 rounded-full">
                                                {{ __($earning->investment->plan->return_interval ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1">
                                                <div class="flex items-center gap-2 text-xs text-text-secondary">
                                                    <span class="w-2 h-2 rounded-full bg-accent-primary"></span>
                                                    {{ __($earning->interest) }}
                                                </div>
                                                <div class="flex items-center gap-2 text-xs text-text-secondary">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                    {{ __($earning->risk_profile) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span
                                                class="text-emerald-400 font-bold text-base group-hover:text-emerald-300 transition-colors">+{{ number_format($earning->amount, getSetting('decimal_places', 2)) }}</span>
                                            <span
                                                class="text-xs text-text-secondary block">{{ getSetting('currency') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="p-4 bg-white/5 rounded-full mb-3">
                                                    <svg class="w-8 h-8 text-text-secondary opacity-50" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-text-secondary">{{ __('No earning records found.') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($investment_earnings->hasPages())
                        <div class="p-6 border-t border-white/5 ajax-pagination">
                            {{ $investment_earnings->links('templates.bento.blades.partials.pagination') }}
                        </div>
                    @endif
                </div>
            </div>
        @endsection

        @section('scripts')
            {{-- jQuery (Required for AJAX) --}}
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

            {{-- Swiper JS & CSS (CDN) --}}
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // AJAX Pagination Logic
                    $(document).on('click', '.ajax-pagination a', function(e) {
                        e.preventDefault();
                        var url = $(this).attr('href');

                        // Show spinner
                        $('#earnings-loading-spinner').removeClass('hidden');

                        // Optional: Scroll to table top smoothly
                        $('html, body').animate({
                            scrollTop: $("#earnings-history-wrapper").offset().top - 100
                        }, 500);

                        $.get(url, function(data) {
                            // Extract the new content for the table wrapper
                            var newContent = $(data).find('#earnings-table-content').html();

                            // Update the DOM
                            $('#earnings-table-content').html(newContent);
                        }).always(function() {
                            // Hide spinner with a small delay for smoothness
                            setTimeout(function() {
                                $('#earnings-loading-spinner').addClass('hidden');
                            }, 300);
                        });
                    });

                    // ... Swiper & Charts Init ...
                    // Swiper Init (Cards Effect)
                    if (document.querySelector('.mySwiper')) {
                        var swiper = new Swiper(".mySwiper", {
                            effect: "cards",
                            grabCursor: true,
                            loop: true,
                            initialSlide: 0,
                            centeredSlides: true,
                            slidesPerView: "auto",
                            autoplay: {
                                delay: 5000,
                                disableOnInteraction: false,
                            },
                            cardsEffect: {
                                perSlideOffset: 18,
                                /* Further increased for mobile visibility */
                                perSlideRotate: 6,
                                /* More rotation for modern "fanned" look */
                                slideShadows: true,
                            },
                            navigation: {
                                nextEl: ".swiper-button-next-custom",
                                prevEl: ".swiper-button-prev-custom",
                            },
                            pagination: {
                                enabled: false
                            }
                        });
                    }

                    // Trend Chart
                    const trendData = @json($analytics['trend_14d']);
                    const trendLabels = trendData.map(item => item.date);
                    const trendValues = trendData.map(item => parseFloat(item.total));

                    if (document.querySelector("#earningsTrendChart")) {
                        const trendOptions = {
                            chart: {
                                type: 'bar',
                                height: 320,
                                toolbar: {
                                    show: false
                                },
                                fontFamily: 'inherit',
                                background: 'transparent',
                                animations: {
                                    enabled: true,
                                    easing: 'easeinout',
                                    speed: 800,
                                }
                            },
                            series: [{
                                name: "{{ __('Earnings') }}",
                                data: trendValues
                            }],
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '12', // Tiny fixed width bars
                                    borderRadius: 6, // Fully rounded top
                                    borderRadiusApplication: 'end',
                                },
                            },
                            stroke: {
                                show: true,
                                width: 0,
                                colors: ['transparent']
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shadeIntensity: 1,
                                    opacityFrom: 0.45,
                                    opacityTo: 0.05,
                                    stops: [0, 90, 100],
                                    colorStops: [{
                                            offset: 0,
                                            color: '#8b5cf6',
                                            opacity: 0.4
                                        },
                                        {
                                            offset: 100,
                                            color: '#6366f1',
                                            opacity: 0.0
                                        }
                                    ]
                                }
                            },
                            xaxis: {
                                categories: trendLabels,
                                axisBorder: {
                                    show: false
                                },
                                axisTicks: {
                                    show: false
                                },
                                labels: {
                                    style: {
                                        colors: '#94a3b8'
                                    },
                                },
                                tooltip: {
                                    enabled: false
                                }
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        colors: '#94a3b8'
                                    },
                                    formatter: (val) => val.toFixed({{ getSetting('decimal_places', 2) }})
                                }
                            },
                            grid: {
                                borderColor: 'rgba(255, 255, 255, 0.05)',
                                strokeDashArray: 4,
                                padding: {
                                    top: 0,
                                    right: 0,
                                    bottom: 0,
                                    left: 10
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            tooltip: {
                                theme: 'dark',
                                y: {
                                    formatter: (val) => val + " {{ getSetting('currency') }}"
                                },
                                background: '#1e293b',
                                borderColor: '#334155',
                            },
                            colors: ['#8b5cf6'],
                        };
                        const trendChart = new ApexCharts(document.querySelector("#earningsTrendChart"), trendOptions);
                        trendChart.render();
                    }

                    // Breakdown Chart (Donut)
                    // Pre-translate labels server-side
                    const breakdownData = @json(
                        $analytics['by_interest']->map(function ($item) {
                            $item->label = __($item->interest);
                            return $item;
                        }));

                    if (document.querySelector("#breakdownChart") && breakdownData.length > 0) {
                        const bdLabels = breakdownData.map(item => item.label);
                        const bdValues = breakdownData.map(item => parseFloat(item.total));

                        const breakdownOptions = {
                            chart: {
                                type: 'donut',
                                height: 280,
                                background: 'transparent'
                            },
                            series: bdValues,
                            labels: bdLabels,
                            colors: ['#8b5cf6', '#6366f1', '#ec4899', '#10b981', '#f59e0b', '#3b82f6'],
                            legend: {
                                position: 'bottom',
                                labels: {
                                    colors: '#cbd5e1'
                                },
                                itemMargin: {
                                    horizontal: 10,
                                    vertical: 5
                                }
                            },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '75%',
                                        labels: {
                                            show: true,
                                            total: {
                                                show: true,
                                                label: "{{ __('Total') }}",
                                                color: '#94a3b8',
                                                formatter: function(w) {
                                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                                        .toFixed({{ getSetting('decimal_places', 2) }});
                                                }
                                            },
                                            value: {
                                                color: '#ffffff',
                                                fontSize: '20px',
                                                fontWeight: 700,
                                                formatter: function(val) {
                                                    return parseFloat(val).toFixed(
                                                        {{ getSetting('decimal_places', 2) }});
                                                }
                                            }
                                        }
                                    }
                                }
                            },
                            stroke: {
                                show: false
                            },
                            dataLabels: {
                                enabled: false
                            },
                            tooltip: {
                                theme: 'dark',
                                y: {
                                    formatter: (val) => val + " {{ getSetting('currency') }}"
                                }
                            }
                        };
                        const breakdownChart = new ApexCharts(document.querySelector("#breakdownChart"), breakdownOptions);
                        breakdownChart.render();
                    }
                });
            </script>
        @endsection
