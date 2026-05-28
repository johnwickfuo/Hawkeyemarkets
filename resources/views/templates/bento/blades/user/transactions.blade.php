@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="space-y-8">
        {{-- Header & Money Flow Summary --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Col 1: Header/Net Flow Card --}}
            <div
                class="lg:col-span-1 bg-secondary border border-white/5 rounded-2xl p-5 relative overflow-hidden flex flex-col justify-between h-[196px]">
                <div class="relative z-10">
                    <h2 class="text-xl font-bold text-white mb-1">{{ __('Money Flow') }}</h2>
                </div>

                <div class="relative z-10">
                    <p class="text-text-secondary text-[10px] uppercase tracking-wider font-semibold mb-1">
                        {{ __('Net Cash Flow') }}</p>
                    <h3 class="text-2xl font-bold {{ $netFlow >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $netFlow >= 0 ? '+' : '' }}{{ number_format($netFlow, getSetting('decimal_places', 2)) }} <span
                            class="text-sm font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                    </h3>
                    <p class="text-[10px] text-text-secondary mt-1">
                        {{ __('Current Balance:') }} <span
                            class="text-white">{{ number_format($currentBalance, getSetting('decimal_places', 2)) }}
                            {{ getSetting('currency') }}</span>
                    </p>
                </div>

                {{-- Decorative Background --}}
                <div
                    class="absolute -right-[120px] -bottom-[120px] text-accent-primary/20 transform rotate-45 pointer-events-none z-0">
                    <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z">
                        </path>
                    </svg>
                </div>
                <div
                    class="absolute top-0 right-0 -mt-8 -mr-8 w-24 h-24 bg-accent-primary/20 rounded-full blur-2xl pointer-events-none z-0">
                </div>
                <div
                    class="absolute bottom-0 left-0 -mb-8 -ml-8 w-24 h-24 bg-purple-500/20 rounded-full blur-2xl pointer-events-none z-0">
                </div>
            </div>

            {{-- Col 2: Vertical Stack of Credits & Debits --}}
            <div class="lg:col-span-1 flex flex-col gap-4">
                {{-- Total Credits --}}
                <div
                    class="bg-secondary border border-white/5 rounded-xl p-4 flex items-center justify-between relative overflow-hidden h-[90px]">
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="p-3 bg-emerald-500/20 rounded-lg text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                                {{ __('Total Credits') }}
                            </p>
                            <h4 class="text-lg font-bold text-white">
                                {{ number_format($moneyFlow->total_credits, getSetting('decimal_places', 2)) }} <span
                                    class="text-xs font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                            </h4>
                        </div>
                    </div>
                    <div
                        class="absolute -right-[120px] -bottom-[120px] text-emerald-500/20 transform -rotate-45 pointer-events-none z-0">
                        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.58 5.59L20 12l-8-8-8 8z"></path>
                        </svg>
                    </div>
                    <div
                        class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none z-0">
                    </div>
                </div>

                {{-- Total Debits --}}
                <div
                    class="bg-secondary border border-white/5 rounded-xl p-4 flex items-center justify-between relative overflow-hidden h-[90px]">
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="p-3 bg-red-500/20 rounded-lg text-red-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                                {{ __('Total Debits') }}
                            </p>
                            <h4 class="text-lg font-bold text-white">
                                {{ number_format($moneyFlow->total_debits, getSetting('decimal_places', 2)) }} <span
                                    class="text-xs font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                            </h4>
                        </div>
                    </div>
                    <div
                        class="absolute -right-[120px] -bottom-[120px] text-red-500/20 transform rotate-45 pointer-events-none z-0">
                        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 12l-1.41-1.41L13 16.17V4h-2v12.17l-5.58-5.59L4 12l8 8 8-8z"></path>
                        </svg>
                    </div>
                    <div
                        class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-red-500/20 rounded-full blur-2xl pointer-events-none z-0">
                    </div>
                </div>
            </div>

            {{-- Col 3: Smart Insights (Swipeable Stack Style from Earnings) --}}
            <div
                class="lg:col-span-1 bg-gradient-to-br from-secondary to-secondary/80 border border-white/5 rounded-2xl relative overflow-hidden flex flex-col h-[196px]">
                <div class="px-5 pt-5 mb-1 flex items-center justify-between z-10">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-accent-primary/20 rounded-lg text-accent-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-white text-sm">{{ __('Smart Insights') }}</h3>
                    </div>
                    <div class="flex gap-1.5">
                        <button
                            class="swiper-button-prev-custom p-1.5 rounded-full bg-white/5 hover:bg-white/10 text-white transition-colors cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                        </button>
                        <button
                            class="swiper-button-next-custom p-1.5 rounded-full bg-white/5 hover:bg-white/10 text-white transition-colors cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Swiper Container --}}
                <div class="swiper mySwiper w-full h-full px-5 pb-4">
                    <div class="swiper-wrapper">
                        @php
                            $insights = [
                                [
                                    'type' => 'frequency',
                                    'title' => __('Analysis'),
                                    'content' => $activityInsight ?? 'No data available',
                                    'color' => 'accent-primary',
                                ],
                                [
                                    'type' => 'source',
                                    'title' => __('Pattern'),
                                    'content' => $ratioInsight ?? 'No data available',
                                    'color' => 'purple-400',
                                ],
                                [
                                    'type' => 'best_day',
                                    'title' => __('Health'),
                                    'content' => $operationalInsight ?? 'No data available',
                                    'color' => 'yellow-400',
                                ],
                                [
                                    'type' => 'trend_up',
                                    'title' => __('Trend'),
                                    'content' => $currencyInsight ?? 'No data available',
                                    'color' => 'blue-400',
                                ],
                                [
                                    'type' => 'trend_up',
                                    'title' => __('Summary'),
                                    'content' => $sizeInsight ?? 'No data available',
                                    'color' => 'emerald-400',
                                ],
                            ];
                        @endphp

                        @foreach ($insights as $index => $insight)
                            <div class="swiper-slide rounded-xl p-4 flex flex-col justify-between relative overflow-hidden group border border-white/5 shadow-inner"
                                style="background: linear-gradient(135deg, {{ $index % 2 == 0 ? '#1e293b, #0f172a' : '#0f172a, #1e293b' }});">

                                {{-- Blended Background Illustration --}}
                                <div class="absolute -right-4 top-1/2 -translate-y-1/2 opacity-10 pointer-events-none">
                                    @switch($insight['type'])
                                        @case('trend_up')
                                            <svg class="w-24 h-24 text-emerald-400" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                        @break

                                        @case('source')
                                            <svg class="w-24 h-24 text-accent-primary" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                            </svg>
                                        @break

                                        @case('frequency')
                                            <svg class="w-24 h-24 text-blue-400" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <path d="M16 2v4M8 2v4M3 10h18" />
                                            </svg>
                                        @break

                                        @case('best_day')
                                            <svg class="w-24 h-24 text-yellow-400" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        @break
                                    @endswitch
                                </div>

                                <div class="relative z-10">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-white/5 flex items-center justify-center backdrop-blur-sm border border-white/10">
                                            @if ($insight['type'] === 'trend_up')
                                                <svg class="w-3 h-3 text-emerald-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                </svg>
                                            @elseif($insight['type'] === 'source')
                                                <svg class="w-3 h-3 text-accent-primary" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                                </svg>
                                            @elseif($insight['type'] === 'frequency')
                                                <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            @elseif($insight['type'] === 'best_day')
                                                <svg class="w-3 h-3 text-yellow-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <span
                                            class="text-[10px] font-bold text-accent-primary uppercase tracking-widest">{{ $insight['title'] }}</span>
                                    </div>
                                    <p class="text-white font-medium text-xs leading-relaxed line-clamp-3">
                                        {{ $insight['content'] }}
                                    </p>
                                </div>

                                <div
                                    class="relative z-10 mt-auto pt-2 border-t border-white/5 flex justify-between items-center text-[8px] text-text-secondary uppercase tracking-tighter">
                                    <span>{{ __('Just Now') }}</span>
                                    <span class="font-mono opacity-50">AI-DRIVEN</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <style>
                    .swiper-slide {
                        display: flex;
                        flex-direction: column;
                        width: 100%;
                        height: 120px;
                    }
                </style>
            </div>
        </div>
    </div>

    {{-- Bento Grid Analytics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- 2) Activity --}}
        <div
            class="bg-secondary border border-white/5 rounded-xl p-5 relative group hover:border-white/10 transition-colors">
            <h5 class="text-white font-bold mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('Activity') }}
            </h5>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-text-secondary">{{ __('Today') }}</span>
                    <span class="text-white font-bold">{{ $activity->today_count }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-text-secondary">{{ __('This Week') }}</span>
                    <span class="text-white font-bold">{{ $activity->week_count }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-text-secondary">{{ __('This Month') }}</span>
                    <span class="text-white font-bold">{{ $activity->month_count }}</span>
                </div>
                <div class="pt-3 border-t border-white/5">
                    <div
                        class="bg-gradient-to-r from-accent-primary/20 to-transparent border-l-2 border-accent-primary p-3 rounded-r">
                        <span
                            class="text-[10px] font-bold uppercase tracking-wider text-accent-primary block mb-1">{{ __('Analysis') }}</span>
                        <p class="text-xs text-gray-300">{{ $activityInsight }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3) Credit/Debit Ratio --}}
        <div
            class="bg-secondary border border-white/5 rounded-xl p-5 relative group hover:border-white/10 transition-colors">
            <h5 class="text-white font-bold mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                </svg>
                {{ __('Credit/Debit Ratio') }}
            </h5>
            <div class="flex items-center gap-2 mb-2">
                <div class="flex-1 h-2 bg-white/10 rounded-full overflow-hidden flex">
                    <div class="h-full bg-emerald-500" style="width: {{ $creditRatio }}%"></div>
                    <div class="h-full bg-red-500" style="width: {{ $debitRatio }}%"></div>
                </div>
            </div>
            <div class="flex justify-between text-xs text-text-secondary mb-3">
                <span class="text-emerald-400">{{ round($creditRatio) }}% {{ __('Credit') }}</span>
                <span class="text-red-400">{{ round($debitRatio) }}% {{ __('Debit') }}</span>
            </div>
            <div
                class="mt-3 bg-gradient-to-r from-purple-500/20 to-transparent border-l-2 border-purple-500 p-3 rounded-r">
                <span
                    class="text-[10px] font-bold uppercase tracking-wider text-purple-400 block mb-1">{{ __('Pattern') }}</span>
                <p class="text-xs text-gray-300">{{ $ratioInsight }}</p>
            </div>
        </div>

        {{-- 4) Operational Health --}}
        <div
            class="bg-secondary border border-white/5 rounded-xl p-5 relative group hover:border-white/10 transition-colors">
            <h5 class="text-white font-bold mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('Status Check') }}
            </h5>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-text-secondary">{{ __('Pending') }}</span>
                    <span class="text-yellow-400 font-bold">{{ $statusCounts->pending_count }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-text-secondary">{{ __('Failed') }}</span>
                    <span class="text-red-400 font-bold">{{ $statusCounts->failed_count }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-text-secondary">{{ __('Success Rate') }}</span>
                    <span class="text-emerald-400 font-bold">{{ round($successRate) }}%</span>
                </div>
                <div class="pt-3 border-t border-white/5">
                    <div
                        class="bg-gradient-to-r from-yellow-500/20 to-transparent border-l-2 border-yellow-500 p-3 rounded-r">
                        <span
                            class="text-[10px] font-bold uppercase tracking-wider text-yellow-400 block mb-1">{{ __('Health') }}</span>
                        <p class="text-xs text-gray-300">{{ $operationalInsight }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5) Currency Stats --}}
        <div
            class="bg-secondary border border-white/5 rounded-xl p-5 relative group hover:border-white/10 transition-colors">
            <h5 class="text-white font-bold mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                {{ __('Currency') }}
            </h5>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-text-secondary">{{ __('Most Used') }}</span>
                    <span class="text-white font-bold">{{ $mostUsedCurrency }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-text-secondary">{{ __('Most Converted') }}</span>
                    <span class="text-white font-bold">{{ $mostConvertedCurrency }}</span>
                </div>
                <div class="pt-3 border-t border-white/5">
                    <div class="bg-gradient-to-r from-blue-500/20 to-transparent border-l-2 border-blue-500 p-3 rounded-r">
                        <span
                            class="text-[10px] font-bold uppercase tracking-wider text-blue-400 block mb-1">{{ __('Trend') }}</span>
                        <p class="text-xs text-gray-300">{{ $currencyInsight }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Expanded Analytics --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- 7) Balance Trend Chart --}}
        <div class="lg:col-span-2 bg-secondary border border-white/5 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-white font-bold flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                    {{ __('Balance Trend') }}
                </h3>
                <div class="text-sm text-text-secondary">
                    {{ __('High:') }} <span class="text-white font-bold">{{ number_format($highestBalance, 2) }}</span>
                </div>
            </div>
            <div id="balanceTrendChart" class="w-full h-[300px]"></div>
        </div>

        {{-- 6) Avg Transaction Size & Extremes --}}
        <div class="lg:col-span-1 bg-secondary border border-white/5 rounded-2xl p-6 flex flex-col">
            <h3 class="text-white font-bold mb-4 text-center">{{ __('Transaction Sizes') }}</h3>
            <div class="flex-1 flex flex-col justify-between">
                {{-- Stats Grid --}}
                <div class="grid grid-cols-2 gap-3 mb-2">
                    <div class="bg-white/5 rounded-lg p-2 text-center">
                        <p class="text-text-secondary text-[10px] uppercase">{{ __('Avg Deposit') }}</p>
                        <p class="text-sm font-bold text-emerald-400">{{ number_format($sizeStats->avg_credit, 2) }}
                        </p>
                    </div>
                    <div class="bg-white/5 rounded-lg p-2 text-center">
                        <p class="text-text-secondary text-[10px] uppercase">{{ __('Avg Withdraw') }}</p>
                        <p class="text-sm font-bold text-red-400">{{ number_format($sizeStats->avg_debit, 2) }}</p>
                    </div>
                    <div class="bg-white/5 rounded-lg p-2 text-center">
                        <p class="text-text-secondary text-[10px] uppercase">{{ __('Largest') }}</p>
                        <p class="text-xs font-bold text-white">{{ number_format($sizeStats->max_tx, 2) }}</p>
                    </div>
                    <div class="bg-white/5 rounded-lg p-2 text-center">
                        <p class="text-text-secondary text-[10px] uppercase">{{ __('Smallest') }}</p>
                        <p class="text-xs font-bold text-white">{{ number_format($sizeStats->min_tx, 2) }}</p>
                    </div>
                </div>

                {{-- Volume Distribution Chart --}}
                <div id="transactionSizeChart" class="flex-1 min-h-[140px]"></div>

                <div
                    class="mt-4 bg-gradient-to-r from-emerald-500/20 to-transparent border-l-2 border-emerald-500 p-3 rounded-r">
                    <span
                        class="text-[10px] font-bold uppercase tracking-wider text-emerald-400 block mb-1">{{ __('Summary') }}</span>
                    <p class="text-xs text-gray-300">{{ $sizeInsight }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 9) Transactions Table --}}
    <div class="bg-secondary border border-white/5 rounded-2xl overflow-hidden relative" id="transactions-wrapper">
        {{-- Loading Spinner Overlay --}}
        <div id="loading-spinner"
            class="hidden absolute inset-0 bg-secondary/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-opacity duration-300">
            <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin"></div>
            <p class="mt-3 text-text-secondary font-medium animate-pulse">{{ __('Loading records...') }}</p>
        </div>

        {{-- Table Filter Header --}}
        <div class="p-6 border-b border-white/5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                {{ __('Transaction History') }}
            </h3>

            {{-- Filters --}}
            <form id="filter-form" action="{{ route('user.transactions') }}" method="GET"
                class="flex flex-wrap gap-2 items-center">
                <div class="relative">
                    <input type="text" name="search" placeholder="{{ __('Search Ref/Desc...') }}"
                        value="{{ request('search') }}"
                        class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white placeholder-text-secondary focus:ring-1 focus:ring-accent-primary focus:border-accent-primary outline-none w-40">
                </div>

                {{-- Custom Type Dropdown --}}
                <div class="relative custom-dropdown" data-name="type">
                    <input type="hidden" name="type" value="{{ request('type', 'all') }}">
                    <button type="button"
                        class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white focus:ring-1 focus:ring-accent-primary outline-none cursor-pointer flex items-center justify-between w-32 dropdown-trigger">
                        <span
                            class="dropdown-label">{{ request('type') == 'credit' ? __('Credit') : (request('type') == 'debit' ? __('Debit') : __('All Types')) }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div
                        class="absolute top-full left-0 mt-1 w-full bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-50 flex flex-col py-1 overflow-hidden hidden dropdown-menu">
                        <div data-value="all"
                            class="px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer dropdown-item">
                            {{ __('All Types') }}</div>
                        <div data-value="credit"
                            class="px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer dropdown-item">
                            {{ __('Credit') }}</div>
                        <div data-value="debit"
                            class="px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer dropdown-item">
                            {{ __('Debit') }}</div>
                    </div>
                </div>

                {{-- Custom Status Dropdown --}}
                <div class="relative custom-dropdown" data-name="status">
                    <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                    <button type="button"
                        class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white focus:ring-1 focus:ring-accent-primary outline-none cursor-pointer flex items-center justify-between w-36 dropdown-trigger">
                        <span
                            class="dropdown-label">{{ request('status') == 'completed' ? __('Completed') : (request('status') == 'pending' ? __('Pending') : (request('status') == 'failed' ? __('Failed') : __('All Status'))) }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div
                        class="absolute top-full left-0 mt-1 w-full bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-50 flex flex-col py-1 overflow-hidden hidden dropdown-menu">
                        <div data-value="all"
                            class="px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer dropdown-item">
                            {{ __('All Status') }}</div>
                        <div data-value="completed"
                            class="px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer dropdown-item">
                            {{ __('Completed') }}</div>
                        <div data-value="pending"
                            class="px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer dropdown-item">
                            {{ __('Pending') }}</div>
                        <div data-value="failed"
                            class="px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer dropdown-item">
                            {{ __('Failed') }}</div>
                    </div>
                </div>

                <button type="submit"
                    class="bg-accent-primary hover:bg-accent-primary/90 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors cursor-pointer">
                    {{ __('Filter') }}
                </button>

                {{-- Export Dropdown --}}
                <div class="relative group ml-2">
                    <button type="button"
                        class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white hover:bg-white/10 transition-colors flex items-center gap-2 cursor-pointer export-trigger">
                        <svg class="w-4 h-4 text-text-secondary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        {{ __('Export') }}
                    </button>
                    <div
                        class="absolute right-0 top-full mt-2 w-32 bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-50 flex flex-col hidden export-dropdown-menu">
                        <a href="{{ route('user.transactions', array_merge(request()->all(), ['export' => 'csv'])) }}"
                            target="_blank"
                            class="block px-4 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 first:rounded-t-lg text-left cursor-pointer">
                            {{ __('CSV') }}
                        </a>
                        <a href="{{ route('user.transactions', array_merge(request()->all(), ['export' => 'pdf'])) }}"
                            target="_blank"
                            class="block px-4 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 last:rounded-b-lg text-left cursor-pointer">
                            {{ __('PDF') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div id="table-content">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Reference') }}</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider cursor-pointer group">
                                <a href="{{ route('user.transactions', array_merge(request()->all(), ['sort' => 'created_at', 'direction' => request('sort') == 'created_at' && request('direction') == 'desc' ? 'asc' : 'desc'])) }}"
                                    class="sort-link flex items-center gap-1">
                                    {{ __('Date') }}
                                    <svg class="w-3 h-3 text-text-secondary/50 group-hover:text-white transition-colors {{ request('sort') == 'created_at' ? 'text-accent-primary' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if (request('sort') == 'created_at' && request('direction') == 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Description') }}</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider cursor-pointer group">
                                <a href="{{ route('user.transactions', array_merge(request()->all(), ['sort' => 'type', 'direction' => request('sort') == 'type' && request('direction') == 'desc' ? 'asc' : 'desc'])) }}"
                                    class="sort-link flex items-center gap-1">
                                    {{ __('Type') }}
                                    <svg class="w-3 h-3 text-text-secondary/50 group-hover:text-white transition-colors {{ request('sort') == 'type' ? 'text-accent-primary' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if (request('sort') == 'type' && request('direction') == 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                </a>
                            </th>
                            <th
                                class="px-6 py-4 text-right text-xs font-bold text-text-secondary uppercase tracking-wider cursor-pointer group">
                                <a href="{{ route('user.transactions', array_merge(request()->all(), ['sort' => 'amount', 'direction' => request('sort') == 'amount' && request('direction') == 'desc' ? 'asc' : 'desc'])) }}"
                                    class="sort-link flex items-center justify-end gap-1">
                                    {{ __('Amount') }}
                                    <svg class="w-3 h-3 text-text-secondary/50 group-hover:text-white transition-colors {{ request('sort') == 'amount' ? 'text-accent-primary' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if (request('sort') == 'amount' && request('direction') == 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                </a>
                            </th>
                            <th
                                class="px-6 py-4 text-right text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Balance') }}</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($transactions as $tx)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary font-mono"
                                    title="{{ $tx->reference }}">
                                    {{ strlen($tx->reference) > 6 ? substr($tx->reference, 0, 3) . '...' . substr($tx->reference, -3) : $tx->reference }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-white block">{{ $tx->created_at->format('M d, Y') }}</span>
                                    <span class="text-xs text-text-secondary">{{ $tx->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-white max-w-xs truncate"
                                    title="{{ $tx->description }}">
                                    {{ $tx->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($tx->type == 'credit')
                                        <span
                                            class="px-2 py-1 rounded bg-emerald-500/10 text-emerald-400 text-xs font-bold uppercase">{{ __('Credit') }}</span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded bg-red-500/10 text-red-400 text-xs font-bold uppercase">{{ __('Debit') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span
                                        class="text-sm font-bold {{ $tx->type == 'credit' ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $tx->type == 'credit' ? '+' : '-' }}{{ number_format($tx->amount, getSetting('decimal_places', 2)) }}
                                    </span>
                                    <span class="text-xs text-text-secondary block">{{ $tx->currency }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-white font-mono">
                                    {{ number_format($tx->new_balance, getSetting('decimal_places', 2)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($tx->status == 'completed')
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-400">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ __('Success') }}
                                        </span>
                                    @elseif($tx->status == 'pending')
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-yellow-400">
                                            <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ __('Pending') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-red-400">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            {{ __('Failed') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-text-secondary">
                                    {{ __('No transactions found matching your criteria.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($transactions->hasPages())
                <div class="p-6 border-t border-white/5 ajax-pagination">
                    {{ $transactions->links('templates.bento.blades.partials.pagination') }}
                </div>
            @endif
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- Swiper JS & CSS (CDN) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // AJAX Pagination & Sorting
            $(document).on('click', '.ajax-pagination a, .sort-link', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                loadTable(url);
            });

            // AJAX Filtering
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action') + '?' + $(this).serialize();
                loadTable(url);
            });

            // Custom Dropdowns (Type & Status)
            $(document).on('click', '.dropdown-trigger', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $menu = $(this).siblings('.dropdown-menu');

                // Close other dropdowns
                $('.dropdown-menu').not($menu).addClass('hidden');
                $('.export-dropdown-menu').addClass('hidden'); // Close export dropdown
                $('.dropdown-trigger svg').not($(this).find('svg')).removeClass('rotate-180');

                $menu.toggleClass('hidden');
                $(this).find('svg').toggleClass('rotate-180');
            });

            // Export Dropdown
            $(document).on('click', '.export-trigger', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $menu = $(this).siblings('.export-dropdown-menu');

                // Close other dropdowns
                $('.dropdown-menu').addClass('hidden');
                $('.dropdown-trigger svg').removeClass('rotate-180');

                $menu.toggleClass('hidden');
            });

            // Handle Export Links (Dynamic URL generation based on current filters)
            $(document).on('click', '.export-dropdown-menu a', function(e) {
                e.preventDefault();
                var exportType = $(this).attr('href').includes('pdf') ? 'pdf' : 'csv';
                var baseUrl = $('#filter-form').attr('action');
                var formData = $('#filter-form').serialize();
                var finalUrl = baseUrl + '?' + formData + '&export=' + exportType;

                window.open(finalUrl, '_blank');

                // Close dropdown after click
                $('.export-dropdown-menu').addClass('hidden');
            });

            $(document).on('click', '.dropdown-item', function(e) {
                e.stopPropagation();
                var $container = $(this).closest('.custom-dropdown');
                var value = $(this).data('value');
                var label = $(this).text();

                $container.find('input[type="hidden"]').val(value);
                $container.find('.dropdown-label').text(label);
                $container.find('.dropdown-menu').addClass('hidden');
                $container.find('.dropdown-trigger svg').removeClass('rotate-180');
            });

            $(document).on('click', function() {
                $('.dropdown-menu').addClass('hidden');
                $('.export-dropdown-menu').addClass('hidden');
                $('.dropdown-trigger svg').removeClass('rotate-180');
            });

            function loadTable(url) {
                $('#loading-spinner').removeClass('hidden');
                $('html, body').animate({
                    scrollTop: $("#transactions-wrapper").offset().top - 100
                }, 500);

                $.get(url, function(data) {
                    var newContent = $(data).find('#table-content').html();
                    $('#table-content').html(newContent);
                }).always(function() {
                    setTimeout(() => $('#loading-spinner').addClass('hidden'), 300);
                });
            }

            // Balance Trend Chart
            const trendData = @json($trendData);
            const trendLabels = trendData.map(item => new Date(item.created_at).toLocaleDateString());
            const trendValues = trendData.map(item => parseFloat(item.new_balance));

            if (document.querySelector("#balanceTrendChart") && trendData.length > 0) {
                const options = {
                    chart: {
                        type: 'area',
                        height: 300,
                        toolbar: {
                            show: false
                        },
                        background: 'transparent'
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.05,
                            stops: [0, 90, 100],
                            colorStops: [{
                                offset: 0,
                                color: '#8b5cf6',
                                opacity: 0.4
                            }, {
                                offset: 100,
                                color: '#8b5cf6',
                                opacity: 0
                            }]
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: [{
                        name: "{{ __('Balance') }}",
                        data: trendValues
                    }],
                    xaxis: {
                        categories: trendLabels,
                        labels: {
                            show: false
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: '#94a3b8'
                            },
                            formatter: (val) => val.toFixed(2)
                        }
                    },
                    grid: {
                        borderColor: 'rgba(255, 255, 255, 0.05)',
                        strokeDashArray: 4
                    },
                    theme: {
                        mode: 'dark'
                    },
                    tooltip: {
                        theme: 'dark'
                    },
                    colors: ['#8b5cf6']
                };
                new ApexCharts(document.querySelector("#balanceTrendChart"), options).render();
            }

            // Transaction Size Pie Chart (Volume Distribution)
            // Use moneyFlow data for Total Credits vs Total Debits
            const flowData = @json($moneyFlow);
            const totalCredits = parseFloat(flowData ? (flowData.total_credits || 0) : 0);
            const totalDebits = parseFloat(flowData ? (flowData.total_debits || 0) : 0);

            if (document.querySelector("#transactionSizeChart") && (totalCredits > 0 || totalDebits > 0)) {
                var sizeOptions = {
                    series: [totalCredits, totalDebits],
                    chart: {
                        width: '100%',
                        type: 'donut',
                        height: 160,
                        background: 'transparent',
                    },
                    labels: ["{{ __('Credits') }}", "{{ __('Debits') }}"],
                    colors: ['#34d399', '#f87171'], // Emerald-400, Red-400
                    stroke: {
                        show: false,
                        width: 0
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            colors: '#94a3b8',
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        color: '#94a3b8',
                                    },
                                    value: {
                                        show: true,
                                        color: '#fff',
                                        formatter: function(val) {
                                            return val.toLocaleString();
                                        }
                                    },
                                    total: {
                                        show: false,
                                        showAlways: false,
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        theme: 'dark',
                        y: {
                            formatter: function(val) {
                                return val.toLocaleString(undefined, {
                                    minimumFractionDigits: {{ getSetting('decimal_places', 2) }},
                                    maximumFractionDigits: {{ getSetting('decimal_places', 2) }}
                                });
                            }
                        }
                    }
                };
                var chart = new ApexCharts(document.querySelector("#transactionSizeChart"), sizeOptions);
                chart.render();
            }

            // Smart Insights Carousel (Swiper Cards Effect)
            if (document.querySelector('.mySwiper')) {
                var swiper = new Swiper(".mySwiper", {
                    effect: "cards",
                    grabCursor: true,
                    loop: true,
                    initialSlide: 0,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    cardsEffect: {
                        perSlideOffset: 12,
                        perSlideRotate: 4,
                        slideShadows: true,
                    },
                    navigation: {
                        nextEl: ".swiper-button-next-custom",
                        prevEl: ".swiper-button-prev-custom",
                    },
                });
            }
        });
    </script>
@endsection
