@extends('templates.bento.blades.layouts.user')

@section('content')
    @include('templates.bento.blades.partials.dashboard-partials')

    {{-- Section 1: At-a-Glance Command Center --}}
    <div class="space-y-8 mb-12">

        {{-- Header / Welcome --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-2 md:gap-4 mb-6 md:mb-8">
            <div>
                <h2
                    class="text-2xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-indigo-200 to-indigo-400 tracking-tight leading-tight">
                    {{ __('Command Center') }}
                </h2>
                <p class="text-indigo-200/60 font-mono text-[9px] md:text-sm mt-0.5 md:mt-1 tracking-widest uppercase">
                    {{ __('Real-time Financial Telemetry') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4 md:gap-6">

            {{-- 1. TOTAL EQUITY (Dominant Card) --}}
            <div class="col-span-12 lg:col-span-8 relative group">
                <div class="relative h-full bg-secondary border border-white/5 rounded-2xl p-8 overflow-hidden">
                    {{-- Subtle Mesh Gradient Background --}}
                    <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-20">
                    </div>

                    {{-- Smart Patterns from Transactions --}}
                    <div
                        class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-accent-primary/20 rounded-full blur-3xl pointer-events-none z-0">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 -mb-12 -ml-12 w-48 h-48 bg-purple-500/20 rounded-full blur-3xl pointer-events-none z-0">
                    </div>

                    {{-- Decorative Background Icon (Smart Pattern) --}}
                    <div
                        class="absolute -right-[100px] -bottom-[100px] text-accent-primary/10 transform rotate-45 pointer-events-none z-0">
                        <svg class="w-80 h-80" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z">
                            </path>
                        </svg>
                    </div>

                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase tracking-widest shadow-[0_0_10px_rgba(99,102,241,0.2)]">
                                    {{ __('Net Worth') }}
                                </div>
                            </div>
                            <h3
                                class="text-3xl md:text-7xl font-bold text-white tracking-tight mb-1 md:mb-2 drop-shadow-xl">
                                <span class="text-transparent bg-clip-text bg-gradient-to-br from-white to-gray-400">
                                    {{ getSetting('currency_symbol', '$') }}{{ number_format($total_equity, 2) }}
                                </span>
                            </h3>
                            <p class="text-slate-400 font-medium text-[10px] md:text-sm flex items-center gap-2">
                                <span class="w-1 md:w-1.5 h-1 md:h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ __('Aggregated Assets') }}
                            </p>
                        </div>

                        {{-- Sub-stats Container --}}
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-8">
                            {{-- Balance --}}
                            <div
                                class="bg-white/[0.03] border border-white/[0.05] rounded-lg p-4 hover:bg-white/[0.06] transition-all cursor-pointer group/stat backdrop-blur-sm">
                                <div class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-1.5">
                                    {{ __('Wallet Balance') }}</div>
                                <div class="text-xl font-bold text-white tracking-wide">{{ showAmount($balance) }}</div>
                            </div>
                            {{-- Margin --}}
                            <div
                                class="bg-white/[0.03] border border-white/[0.05] rounded-lg p-4 hover:bg-white/[0.06] transition-all cursor-pointer group/stat backdrop-blur-sm">
                                <div class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-1.5">
                                    {{ __('Margin Used') }}</div>
                                <div
                                    class="text-xl font-bold text-slate-200 group-hover/stat:text-amber-400 transition-colors tracking-wide">
                                    {{ showAmount($margin_used) }}</div>
                            </div>
                            {{-- Borrowed (Hidden on mobile) --}}
                            <div
                                class="hidden md:block bg-white/[0.03] border border-white/[0.05] rounded-lg p-4 hover:bg-white/[0.06] transition-all cursor-pointer group/stat backdrop-blur-sm">
                                <div class="text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-1.5">
                                    {{ __('Borrowed') }}</div>
                                <div
                                    class="text-xl font-bold text-slate-200 group-hover/stat:text-rose-400 transition-colors tracking-wide">
                                    {{ showAmount($borrowed) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. PnL & Exposure (Vertical Stack) --}}
            <div class="col-span-12 md:col-span-6 lg:col-span-4 flex flex-col gap-6">
                {{-- PnL Card --}}
                <div
                    class="relative flex-1 group bg-secondary border border-white/5 rounded-2xl p-6 transition-all hover:border-white/10 overflow-hidden">
                    <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10">
                    </div>
                    {{-- Soft Glow --}}
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-{{ $open_pnl >= 0 ? 'emerald' : 'rose' }}-500/10 blur-2xl rounded-full -mr-8 -mt-8 pointer-events-none">
                    </div>

                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h4 class="text-slate-400 text-xs font-bold uppercase tracking-wider">
                                    {{ __('Unrealized PnL') }}</h4>
                            </div>
                            <div
                                class="px-2 py-0.5 rounded {{ $open_pnl >= 0 ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border-rose-500/30' }} border text-[10px] font-bold uppercase tracking-wide">
                                {{ $open_pnl >= 0 ? 'Profit' : 'Loss' }}
                            </div>
                        </div>

                        <div
                            class="text-2xl md:text-4xl font-bold {{ $open_pnl >= 0 ? 'text-emerald-400' : 'text-rose-400' }} tracking-tight mb-1 md:mb-2 flex items-baseline gap-1">
                            <span>{{ $open_pnl >= 0 ? '+' : '' }}</span>{{ showAmount($open_pnl) }}
                        </div>
                        <div class="text-slate-500 text-[10px] md:text-xs font-medium">{{ __('Live Mark-to-Market') }}
                        </div>

                        {{-- Progress Bar --}}
                        <div class="mt-6">
                            <div
                                class="flex justify-between text-[10px] text-slate-500 mb-1.5 font-medium uppercase tracking-wider">
                                <span>{{ __('Risk Impact') }}</span>
                                <span>{{ $total_equity > 0 ? number_format((abs($open_pnl) / $total_equity) * 100, 1) : 0 }}%</span>
                            </div>
                            <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full {{ $open_pnl >= 0 ? 'bg-gradient-to-r from-emerald-600 to-emerald-400' : 'bg-gradient-to-r from-rose-600 to-rose-400' }}"
                                    style="width: {{ min((abs($open_pnl) / ($total_equity ?: 1)) * 100 * 5, 100) }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Active Positions & Trades --}}
                <div
                    class="flex-1 bg-secondary border border-white/5 rounded-2xl p-6 flex flex-col justify-center gap-6 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10">
                    </div>

                    {{-- Row 1 --}}
                    <div class="flex justify-between items-center relative z-10">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-10 w-10 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 shadow-[0_0_15px_rgba(59,130,246,0.15)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-white font-bold text-lg">{{ $open_positions_count }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                    {{ __('Open Positions') }}</div>
                            </div>
                        </div>
                        <a href="{{ route('user.trading.account') }}"
                            class="h-8 w-8 rounded-full border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-all cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    {{-- Row 2 --}}
                    <div class="flex justify-between items-center relative z-10">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-10 w-10 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shadow-[0_0_15px_rgba(168,85,247,0.15)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-white font-bold text-lg">{{ number_format($trades_count) }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                    {{ __('Total Trades') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions (Minimalist Grid) - Moved Below Stats on Mobile --}}
            <div class="col-span-12 order-last lg:order-none mb-2 md:mb-0">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 md:gap-3 w-full">
                    {{-- Deposit --}}
                    <a href="{{ route('user.deposits.new') }}"
                        class="group flex items-center justify-center gap-2 md:gap-3 px-3 md:px-5 py-2.5 md:py-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl transition-all hover:bg-emerald-500/20 hover:border-emerald-500/30 cursor-pointer">
                        <svg class="h-3 md:h-4 w-3 md:w-4 text-emerald-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span
                            class="text-emerald-50 text-[10px] md:text-xs font-bold tracking-widest uppercase">{{ __('Deposit') }}</span>
                    </a>

                    {{-- Withdraw --}}
                    <a href="{{ route('user.withdrawals.new') }}"
                        class="group flex items-center justify-center gap-2 md:gap-3 px-3 md:px-5 py-2.5 md:py-3 bg-rose-500/10 border border-rose-500/20 rounded-xl transition-all hover:bg-rose-500/20 hover:border-rose-500/30 cursor-pointer">
                        <svg class="h-3 md:h-4 w-3 md:w-4 text-rose-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span
                            class="text-rose-50 text-[10px] md:text-xs font-bold tracking-widest uppercase">{{ __('Withdraw') }}</span>
                    </a>

                    {{-- Trade --}}
                    <a href="{{ route('user.trading.account') }}"
                        class="group flex items-center justify-center gap-2 md:gap-3 px-3 md:px-5 py-2.5 md:py-3 bg-accent-primary/10 border border-accent-primary/20 rounded-xl transition-all hover:bg-accent-primary/20 hover:border-accent-primary/30 cursor-pointer">
                        <svg class="h-3 md:h-4 w-3 md:w-4 text-accent-primary" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span
                            class="text-white text-[10px] md:text-xs font-bold tracking-widest uppercase">{{ __('Trade') }}</span>
                    </a>

                    {{-- Invest --}}
                    <a href="{{ route('user.investments.new') }}"
                        class="group flex items-center justify-center gap-2 md:gap-3 px-3 md:px-5 py-2.5 md:py-3 bg-purple-500/10 border border-purple-500/20 rounded-xl transition-all hover:bg-purple-500/20 hover:border-purple-500/30 cursor-pointer">
                        <svg class="h-3 md:h-4 w-3 md:w-4 text-purple-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span
                            class="text-purple-50 text-[10px] md:text-xs font-bold tracking-widest uppercase">{{ __('Invest') }}</span>
                    </a>
                </div>
            </div>

            {{-- 3. Monthly Cashflow (Horizontal) --}}
            <div
                class="col-span-12 md:col-span-6 lg:col-span-12 bg-secondary border border-white/5 rounded-2xl p-6 lg:flex items-center justify-between gap-8 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10"></div>

                {{-- Ambient Glows (Smart Pattern) --}}
                <div
                    class="absolute top-0 right-0 -mt-8 -mr-8 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none z-0">
                </div>
                <div
                    class="absolute bottom-0 left-0 -mb-8 -ml-8 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl pointer-events-none z-0">
                </div>

                <div class="mb-5 lg:mb-0 relative z-10 text-center lg:text-left">
                    <h4 class="text-slate-400 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-1">
                        {{ now()->format('F') }}
                        {{ __('Flow') }}</h4>
                    <div class="text-white font-black text-xl md:text-2xl tracking-tight leading-tight">
                        {{ __('Money Movement') }}</div>
                </div>

                <div class="flex-1 grid grid-cols-2 md:grid-cols-2 gap-4 md:gap-8 relative z-10">
                    {{-- Connector Line --}}
                    <div
                        class="absolute left-1/2 top-1 bottom-1 w-px bg-gradient-to-b from-transparent via-white/10 to-transparent">
                    </div>

                    {{-- In --}}
                    <div class="text-right pr-4 md:pr-10">
                        <div class="text-emerald-400 font-mono text-lg md:text-2xl font-bold tracking-tight">
                            +{{ showAmount($deposits_month) }}</div>
                        <div
                            class="text-[9px] md:text-[11px] text-slate-500 font-bold uppercase mt-1 flex items-center justify-end gap-1.5 tracking-wider">
                            {{ __('In') }}
                            <div
                                class="h-3.5 w-3.5 md:h-5 md:w-5 rounded-full bg-emerald-500/10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 md:h-3 md:w-3 text-emerald-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        @if ($deposits_pending > 0)
                            <div
                                class="text-[8px] md:text-[10px] font-bold text-amber-500 mt-1 md:mt-2 uppercase tracking-wide bg-amber-500/10 px-1.5 md:px-2 py-0.5 rounded inline-block">
                                {{ $deposits_pending }} {{ __('processing') }}</div>
                        @endif
                    </div>

                    {{-- Out --}}
                    <div class="text-left pl-4 md:pl-10">
                        <div class="text-rose-400 font-mono text-lg md:text-2xl font-bold tracking-tight">
                            -{{ showAmount($withdrawals_month_amount) }}</div>
                        <div
                            class="text-[9px] md:text-[11px] text-slate-500 font-bold uppercase mt-1 flex items-center gap-1.5 tracking-wider">
                            <div
                                class="h-3.5 w-3.5 md:h-5 md:w-5 rounded-full bg-rose-500/10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 md:h-3 md:w-3 text-rose-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            {{ __('Out') }}
                        </div>
                        @if ($withdrawals_pending > 0)
                            <div
                                class="text-[8px] md:text-[10px] font-bold text-amber-500 mt-1 md:mt-2 uppercase tracking-wide bg-amber-500/10 px-1.5 md:px-2 py-0.5 rounded inline-block">
                                {{ $withdrawals_pending }} {{ __('processing') }}</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Section 2: Analytics & Smart Insights --}}
    <div class="space-y-8 mb-12">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-2 md:gap-4 mb-2 md:mb-4">
            <div>
                <h3 class="text-xl md:text-3xl font-black text-white tracking-tight leading-tight">
                    {{ __('Analytics & Intelligence') }}
                </h3>
                <p class="text-slate-500 font-mono text-[9px] md:text-xs mt-0.5 tracking-widest uppercase">
                    {{ __('Comprehensive Financial Telemetry & AI Insights') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            {{-- 1. Smart Insights (Fanned Cards Effect) --}}
            <div
                class="col-span-12 lg:col-span-8 bg-gradient-to-br from-secondary to-secondary/80 border border-white/5 rounded-2xl relative overflow-hidden flex flex-col h-[350px] shadow-2xl">
                <div class="px-6 pt-6 mb-2 flex items-center justify-between z-10">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-accent-primary/20 rounded-lg text-accent-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-black text-white text-sm uppercase tracking-widest">{{ __('Smart Insights') }}
                        </h3>
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="swiper-button-prev-custom p-2 rounded-full bg-white/5 hover:bg-white/10 text-white transition-colors cursor-pointer">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button
                            class="swiper-button-next-custom p-2 rounded-full bg-white/5 hover:bg-white/10 text-white transition-colors cursor-pointer">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7 7 7">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Swiper Container --}}
                <div class="swiper mySwiper w-full h-full pb-10 px-4 sm:px-14">
                    <div class="swiper-wrapper">
                        @forelse($smart_insights as $insight)
                            <div class="swiper-slide rounded-2xl p-6 flex flex-col justify-between relative overflow-hidden group shadow-2xl border border-white/10"
                                style="background: linear-gradient(135deg, {{ $insight['index'] % 2 == 0 ? '#1e293b, #0f172a' : '#1e1b4b, #0f172a' }});">

                                {{-- Background Illustration --}}
                                <div
                                    class="absolute top-1/2 right-0 transform -translate-y-1/2 translate-x-1/4 opacity-10 pointer-events-none">
                                    @switch($insight['type'])
                                        @case('trend_up')
                                            <svg class="w-48 h-48 text-emerald-400" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="0.5">
                                                <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                        @break

                                        @case('trend_down')
                                            <svg class="w-48 h-48 text-rose-400" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="0.5">
                                                <path d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                            </svg>
                                        @break

                                        @case('source')
                                            <svg class="w-48 h-48 text-accent-primary" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="0.5">
                                                <path d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                                <path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                            </svg>
                                        @break

                                        @case('frequency')
                                            <svg class="w-48 h-48 text-blue-400" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="0.5">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                        @break

                                        @default
                                            <svg class="w-48 h-48 text-white/20" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="0.5">
                                                <circle cx="12" cy="12" r="10" />
                                                <path d="M12 6v6l4 2" />
                                            </svg>
                                    @endswitch
                                </div>

                                <div class="relative z-10">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div
                                            class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/10 text-accent-primary">
                                            @if ($insight['type'] === 'trend_up')
                                                <svg class="w-4 h-4 text-emerald-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                </svg>
                                            @elseif($insight['type'] === 'trend_down')
                                                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-accent-primary" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <span
                                            class="text-[10px] font-black text-accent-primary uppercase tracking-[0.2em]">{{ $insight['title'] }}</span>
                                    </div>
                                    <p class="text-white font-bold text-lg md:text-xl leading-snug drop-shadow-md">
                                        {{ $insight['text'] }}
                                    </p>
                                </div>

                                <div
                                    class="relative z-10 mt-auto pt-4 border-t border-white/5 flex justify-between items-center text-[9px] text-slate-500 font-bold tracking-widest uppercase">
                                    <span>{{ __('Real-time Insight') }}</span>
                                    <span class="opacity-50 font-mono tracking-tighter">AI-TELEMETRY</span>
                                </div>
                            </div>
                            @empty
                                <div
                                    class="swiper-slide bg-secondary/50 border border-white/10 rounded-2xl p-6 flex items-center justify-center">
                                    <p class="text-slate-500 font-mono text-xs">{{ __('GATHERING TELEMETRY...') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- 2. Money Distribution (Donut Chart) --}}
                <div
                    class="col-span-12 lg:col-span-4 bg-secondary border border-white/5 rounded-2xl p-6 relative overflow-hidden flex flex-col items-center justify-center group hover:border-white/10 transition-colors">
                    <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10"></div>
                    <h4
                        class="absolute top-6 left-6 text-slate-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 shadow-[0_0_8px_rgba(168,85,247,0.5)]"></span>
                        {{ __('Asset Weighting') }}
                    </h4>
                    <div id="distributionDonutChart" class="w-full h-[280px]"></div>
                </div>

                {{-- 3. Transaction Trend (Main Graph) --}}
                <div
                    class="col-span-12 bg-secondary border border-white/5 rounded-2xl p-6 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10"></div>
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                        <div>
                            <h4 class="text-slate-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                                {{ __('Global Cashflow Trend') }}
                            </h4>
                            <div class="text-white font-black text-xl mt-1">{{ __('Movement vs Velocity') }}</div>
                        </div>
                        {{-- Custom Dropdown Filter --}}
                        <div class="relative" id="tx-chart-filter-container">
                            <button id="tx-chart-filter-button"
                                class="flex items-center gap-2 bg-white/5 px-4 py-2 rounded-xl border border-white/10 text-[10px] font-bold uppercase tracking-widest text-white hover:bg-white/10 transition-all cursor-pointer">
                                <span id="current-filter-label">{{ __('7 Days') }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div id="tx-chart-filter-menu"
                                class="absolute right-0 mt-2 w-40 origin-top-right rounded-xl bg-[#0f172a] border border-white/10 shadow-2xl opacity-0 invisible scale-95 transition-all z-[100] backdrop-blur-xl">
                                <div class="p-1.5 space-y-1">
                                    <button data-days="7"
                                        class="w-full text-left px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-all">{{ __('7 Days') }}</button>
                                    <button data-days="30"
                                        class="w-full text-left px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-all">{{ __('30 Days') }}</button>
                                    <button data-days="90"
                                        class="w-full text-left px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-all">{{ __('90 Days') }}</button>
                                    <button data-days="365"
                                        class="w-full text-left px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-all">{{ __('1 Year') }}</button>
                                    <button data-days="all"
                                        class="w-full text-left px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-all">{{ __('All Time') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="transactionTrendChart" class="w-full h-[350px]"></div>
                </div>
            </div>
        </div>






        {{-- Section 3: Portfolio Analysis (Stocks & ETFs) --}}
        <div class="space-y-8 mb-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-2 md:gap-4 mb-2 md:mb-4">
                <div>
                    <h3 class="text-xl md:text-3xl font-black text-white tracking-tight leading-tight">
                        {{ __('Portfolio Intelligence') }}
                    </h3>
                    <p class="text-slate-500 font-mono text-[9px] md:text-xs mt-0.5 tracking-widest uppercase">
                        {{ __('Equity Distribution & Performance Metrics') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 md:gap-6">
                {{-- Analytics Overview Cards --}}
                <div class="col-span-12 lg:col-span-4 grid grid-cols-1 gap-4">
                    {{-- Realized vs Unrealized --}}
                    <div
                        class="bg-secondary border border-white/5 rounded-2xl p-5 relative overflow-hidden group transition-all hover:border-white/10">
                        <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10"></div>
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-indigo-500/10 blur-2xl rounded-full -mr-8 -mt-8 pointer-events-none">
                        </div>

                        <div class="relative z-10">
                            <h4 class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-4">
                                {{ __('Returns Breakdown') }}</h4>
                            <div class="space-y-4">
                                <div>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-widest mb-1">
                                        {{ __('Unrealized PnL') }}</div>
                                    <div
                                        class="text-2xl font-bold {{ $open_pnl >= 0 ? 'text-emerald-400' : 'text-rose-400' }} tracking-tight">
                                        {{ $open_pnl >= 0 ? '+' : '' }}{{ showAmount($open_pnl) }}
                                    </div>
                                </div>
                                <div class="pt-4 border-t border-white/5">
                                    <div class="text-[10px] text-slate-500 uppercase tracking-widest mb-1">
                                        {{ __('Realized PnL') }}</div>
                                    <div
                                        class="text-2xl font-bold {{ $realized_pnl >= 0 ? 'text-emerald-400' : 'text-rose-400' }} tracking-tight">
                                        {{ $realized_pnl >= 0 ? '+' : '' }}{{ showAmount($realized_pnl) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fee & Cost Analysis --}}
                    <div
                        class="bg-secondary border border-white/5 rounded-2xl p-5 relative overflow-hidden group transition-all hover:border-white/10">
                        <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10"></div>
                        <div
                            class="absolute bottom-0 left-0 w-24 h-24 bg-amber-500/10 blur-2xl rounded-full -ml-8 -mb-8 pointer-events-none">
                        </div>

                        <div class="relative z-10 text-center lg:text-left">
                            <h4 class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-4">
                                {{ __('Fee Impact') }}</h4>
                            <div class="flex items-end justify-between gap-4">
                                <div>
                                    <div class="text-3xl font-bold text-white tracking-tight">
                                        {{ showAmount($total_fees) }}
                                    </div>
                                    <div class="text-[10px] text-amber-500/80 uppercase font-bold tracking-widest mt-1">
                                        {{ __('Avg') }} {{ number_format($avg_fee_percent, 2) }}%
                                        {{ __('per action') }}
                                    </div>
                                </div>
                                <div class="px-3 py-1.5 bg-white/5 rounded-lg border border-white/10">
                                    <span
                                        class="text-accent-primary font-mono text-sm font-bold">{{ $all_holdings->count() }}</span>
                                    <span
                                        class="text-slate-500 text-[9px] uppercase font-bold ml-1">{{ __('Assets') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Holdings Table Section --}}
                <div
                    class="col-span-12 lg:col-span-8 bg-secondary border border-white/5 rounded-2xl p-5 md:p-6 relative overflow-hidden group transition-all hover:border-white/10">
                    <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10"></div>
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-accent-primary/5 blur-[80px] rounded-full -mr-20 -mt-20 pointer-events-none">
                    </div>

                    <div class="relative z-10 h-full flex flex-col">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                                {{ __('Active Holdings') }}</h4>
                            <div class="flex gap-2">
                                <span
                                    class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 text-[9px] font-bold rounded border border-emerald-500/20 uppercase">{{ __('Verified Live') }}</span>
                            </div>
                        </div>

                        <div class="overflow-x-auto -mx-5 px-5">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left border-b border-white/5">
                                        <th class="pb-3 text-[10px] text-slate-500 uppercase font-bold tracking-widest">
                                            {{ __('Ticker') }}</th>
                                        <th
                                            class="pb-3 text-[10px] text-slate-500 uppercase font-bold tracking-widest text-right">
                                            {{ __('Shares') }}</th>
                                        <th
                                            class="pb-3 text-[10px] text-slate-500 uppercase font-bold tracking-widest text-right">
                                            {{ __('Avg Price') }}</th>
                                        <th
                                            class="pb-3 text-[10px] text-slate-500 uppercase font-bold tracking-widest text-right">
                                            {{ __('Performance') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @forelse ($all_holdings->take(5) as $holding)
                                        <tr class="group/row transition-colors hover:bg-white/5">
                                            <td class="py-4">
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="w-7 h-7 bg-white/5 rounded-lg flex items-center justify-center border border-white/10 group-hover/row:border-accent-primary/30 transition-colors overflow-hidden">
                                                        <img src="https://financialmodelingprep.com/image-stock/{{ strtoupper($holding->ticker) }}.png"
                                                            class="w-5 h-5 object-contain"
                                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                                                            alt="{{ $holding->ticker }}">
                                                        <span
                                                            class="text-[10px] font-black text-slate-400 hidden">{{ substr($holding->ticker, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="text-white font-bold text-xs uppercase tracking-tight">
                                                            {{ $holding->ticker }}</div>
                                                        <div class="text-[9px] text-slate-500 tracking-wider">
                                                            {{ $holding->updated_at->diffForHumans(['short' => true]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-right">
                                                <div class="text-white font-mono text-xs">
                                                    {{ number_format($holding->shares, 2) }}</div>
                                            </td>
                                            <td class="py-4 text-right">
                                                <div class="text-slate-400 font-mono text-xs">
                                                    {{ showAmount($holding->average_price) }}</div>
                                            </td>
                                            <td class="py-4 text-right">
                                                <div class="inline-flex flex-col items-end">
                                                    <div
                                                        class="text-xs font-bold {{ $holding->pnl >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                                        {{ $holding->pnl >= 0 ? '+' : '' }}{{ showAmount($holding->pnl) }}
                                                    </div>
                                                    <div
                                                        class="text-[9px] font-bold opacity-60 {{ $holding->pnl >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                                        {{ $holding->pnl_percent }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="py-12 text-center text-slate-500 font-mono text-xs uppercase tracking-widest">
                                                {{ __('No active holdings detected') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($all_holdings->count() > 5)
                            <div class="mt-auto pt-4 flex justify-center">
                                <a href="{{ route('user.capital-instruments.stocks') }}"
                                    class="text-[10px] text-slate-400 hover:text-white uppercase font-bold tracking-widest transition-colors flex items-center gap-2 group cursor-pointer">
                                    {{ __('Expand Full Portfolio') }}
                                    <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Portfolio Velocity (Top Movers & Most Traded) --}}
                <div class="col-span-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Winners --}}
                    <div class="bg-secondary border border-white/5 rounded-2xl p-5 relative overflow-hidden">
                        <h4 class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            {{ __('Top Winners') }}
                        </h4>
                        <div class="space-y-3">
                            @foreach ($top_winners as $winner)
                                <div class="flex items-center justify-between group/win">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-5 h-5 bg-white/5 rounded border border-white/5 flex items-center justify-center overflow-hidden">
                                            <img src="https://financialmodelingprep.com/image-stock/{{ strtoupper($winner->ticker) }}.png"
                                                class="w-3.5 h-3.5 object-contain"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <span
                                                class="text-[8px] font-black text-slate-500 hidden">{{ strtoupper(substr($winner->ticker, 0, 1)) }}</span>
                                        </div>
                                        <span class="text-white font-bold text-xs">{{ $winner->ticker }}</span>
                                    </div>
                                    <span
                                        class="bg-emerald-500/10 text-emerald-400 text-[10px] px-1.5 py-0.5 rounded font-bold">+{{ $winner->pnl_percent }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Allocation --}}
                    <div class="bg-secondary border border-white/5 rounded-2xl p-5 relative overflow-hidden">
                        <h4 class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.5)]"></span>
                            {{ __('Asset Allocation') }}
                        </h4>
                        <div class="space-y-3">
                            @foreach ($allocation_by_ticker as $ticker => $percent)
                                <div>
                                    <div class="flex justify-between text-[10px] text-slate-400 mb-1 font-bold">
                                        <span>{{ $ticker }}</span>
                                        <span>{{ $percent }}%</span>
                                    </div>
                                    <div class="w-full bg-white/5 rounded-full h-1 overflow-hidden">
                                        <div class="bg-indigo-500 h-full rounded-full transition-all duration-1000"
                                            style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Most Traded --}}
                    <div class="bg-secondary border border-white/5 rounded-2xl p-5 relative overflow-hidden">
                        <h4 class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-accent-primary shadow-[0_0_8px_rgba(99,102,241,0.5)]"></span>
                            {{ __('High Velocity (Activity Map)') }}
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($most_traded_tickers as $ticker => $count)
                                <div
                                    class="px-2 py-1 bg-white/5 rounded-lg border border-white/10 hover:border-accent-primary/30 transition-colors group">
                                    <span class="text-white font-bold text-[10px]">{{ $ticker }}</span>
                                    <span
                                        class="text-slate-500 text-[10px] ml-1 group-hover:text-accent-primary transition-colors">{{ $count }}x</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 4: Investment Ecosystem --}}
        <div class="space-y-6 pt-12 border-t border-white/5">
            {{-- Section Title --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-1">{{ __('Investment Ecosystem') }}</h2>
                    <p class="text-slate-400 text-sm">{{ __('Passive income strategies and performance tracking') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full">
                        <span
                            class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">{{ __('Active Strategies') }}</span>
                    </div>
                </div>
            </div>

            {{-- Simplified Stats Bar --}}
            <div
                class="bg-secondary/50 border border-white/5 rounded-2xl p-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 backdrop-blur-sm overflow-hidden">
                <div class="w-full overflow-x-auto scrollbar-hide py-1">
                    <div class="flex items-center gap-6 divide-x divide-white/10 min-w-max">
                        {{-- Active Plans --}}
                        <div class="flex flex-col">
                            <span
                                class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">{{ __('Active Strategies') }}</span>
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-bold text-white">{{ $investment_stats['active_count'] }}</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            </div>
                        </div>
                        {{-- Invested --}}
                        <div class="flex flex-col pl-6">
                            <span
                                class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">{{ __('Invested Capital') }}</span>
                            <span
                                class="text-lg font-bold text-white">{{ showAmount($investment_stats['total_capital']) }}</span>
                        </div>
                        {{-- Compounding --}}
                        <div class="flex flex-col pl-6">
                            <span
                                class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">{{ __('Compounding') }}</span>
                            <span
                                class="text-lg font-bold text-purple-400">{{ showAmount($investment_stats['total_compounding']) }}</span>
                        </div>
                        {{-- Secured ROI --}}
                        <div class="flex flex-col pl-6">
                            <span
                                class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">{{ __('ROI Secured') }}</span>
                            <span
                                class="text-lg font-bold text-emerald-400">{{ showAmount($investment_stats['total_roi']) }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('user.investments.new') }}"
                        class="px-4 py-2 bg-accent-primary/10 hover:bg-accent-primary/20 border border-accent-primary/20 rounded-xl text-[10px] font-bold text-accent-primary uppercase tracking-widest transition-all cursor-pointer">
                        {{ __('New Strategy') }}
                    </a>
                </div>
            </div>

            {{-- Main Content Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Active Strategies List (Left 2/3) --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-white font-bold text-sm">{{ __('Active Yield Strategies') }}</h4>
                        <a href="{{ route('user.investments.new') }}"
                            class="text-[10px] font-bold text-accent-primary uppercase tracking-widest hover:underline cursor-pointer">{{ __('New Investment') }}</a>
                    </div>

                    @forelse($active_investments as $inv)
                        <div
                            class="bg-secondary/40 border border-white/5 rounded-xl p-4 hover:border-white/10 transition-colors group">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                {{-- Strategy Identity --}}
                                <div class="flex items-center gap-3 min-w-[180px]">
                                    <div class="w-2 h-2 rounded-full bg-accent-primary shadow-[0_0_8px_rgba(139,92,246,0.3)]">
                                    </div>
                                    <div>
                                        <h5 class="text-white font-bold text-sm">{{ $inv->plan->name }}</h5>
                                        <span
                                            class="text-[9px] text-slate-500 uppercase font-bold tracking-tighter">{{ $inv->plan->risk_profile }}
                                            • {{ str_replace('_', ' ', $inv->plan->investment_goal) }}</span>
                                    </div>
                                </div>

                                {{-- Capital Stats --}}
                                <div class="flex items-center gap-8">
                                    <div class="text-left">
                                        <span
                                            class="text-[8px] text-slate-500 uppercase font-bold block mb-0.5">{{ __('Capital') }}</span>
                                        <span
                                            class="text-xs text-white font-bold">{{ showAmount($inv->capital_invested) }}</span>
                                    </div>
                                    <div class="text-left">
                                        <span
                                            class="text-[8px] text-slate-500 uppercase font-bold block mb-0.5">{{ __('ROI Net') }}</span>
                                        <span
                                            class="text-xs text-emerald-400 font-bold">+{{ showAmount($inv->roi_earned) }}</span>
                                    </div>
                                </div>

                                {{-- Progress --}}
                                <div class="flex-1 max-w-[120px]">
                                    <div class="flex justify-between text-[8px] mb-1 font-bold italic">
                                        <span class="text-slate-500 uppercase">{{ __('Progress') }}</span>
                                        <span
                                            class="text-white">{{ round(($inv->cycle_count / max(1, $inv->total_cycles)) * 100) }}%</span>
                                    </div>
                                    <div class="w-full bg-white/5 rounded-full h-1 overflow-hidden">
                                        <div class="bg-accent-primary h-full rounded-full transition-all duration-1000"
                                            style="width: {{ ($inv->cycle_count / max(1, $inv->total_cycles)) * 100 }}%">
                                        </div>
                                    </div>
                                </div>

                                {{-- Status/Meta --}}
                                <div class="flex items-center gap-4 text-right">
                                    <div class="hidden sm:block">
                                        <span
                                            class="text-[8px] text-slate-500 uppercase font-bold block mb-0.5">{{ __('Next Payout') }}</span>
                                        <span
                                            class="text-[10px] text-white font-mono">{{ $inv->next_roi_at ? \Carbon\Carbon::parse((int) $inv->next_roi_at)->diffForHumans(null, true) : 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if ($inv->auto_reinvest)
                                            <div class="w-6 h-6 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center"
                                                title="{{ __('Auto-Reinvest') }}">
                                                <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </div>
                                        @endif
                                        <a href="{{ route('user.investments.index') }}"
                                            class="w-8 h-8 flex items-center justify-center bg-white/5 hover:bg-white/10 border border-white/5 rounded-lg text-slate-400 hover:text-white transition-colors cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-secondary/20 border border-dashed border-white/5 rounded-2xl p-8 text-center">
                            <h5 class="text-white font-bold mb-1 text-sm">{{ __('Ready to scale?') }}</h5>
                            <p class="text-slate-500 text-[10px] mb-4">
                                {{ __('Your idle capital is currently losing value to inflation.') }}</p>
                            <a href="{{ route('user.investments.new') }}"
                                class="inline-flex items-center gap-2 bg-accent-primary text-white px-4 py-1.5 rounded-lg text-[10px] font-bold transition-all cursor-pointer">
                                {{ __('Explore Strategies') }}
                            </a>
                        </div>
                    @endforelse

                    {{-- Performance Hub (Analytics) --}}
                    <div class="bg-secondary/40 border border-white/5 rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-white font-bold text-sm flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                </svg>
                                {{ __('Yield Analytics') }}
                            </h4>
                            <div class="flex items-center bg-white/5 rounded-lg p-0.5 border border-white/5">
                                <button data-yield-days="7"
                                    class="cursor-pointer yield-filter-btn px-2.5 py-1 text-[9px] font-bold uppercase rounded-md transition-all bg-white/10 text-white">{{ __('7D') }}</button>
                                <button data-yield-days="30"
                                    class="cursor-pointer yield-filter-btn px-2.5 py-1 text-[9px] font-bold uppercase rounded-md transition-all text-slate-500 hover:text-white">{{ __('30D') }}</button>
                            </div>
                        </div>
                        <div id="earningsChart" class="w-full h-[180px]"></div>
                    </div>
                </div>

                {{-- Sidebar Simplified --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Investor Profile --}}
                    <div class="bg-secondary/40 border border-white/5 rounded-2xl p-5 relative overflow-hidden group">
                        <h4 class="text-white font-bold text-xs mb-4 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Strategy Profile') }}
                        </h4>
                        @php $onboarding = auth()->user()->onboarding; @endphp
                        <div class="space-y-3 relative z-10">
                            <div class="flex items-center justify-between p-2.5 bg-white/5 rounded-lg border border-white/5">
                                <span
                                    class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ __('Tolerance') }}</span>
                                <span
                                    class="text-[10px] font-bold text-white uppercase">{{ $onboarding->risk_profile ?? __('N/A') }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 bg-white/5 rounded-lg border border-white/5">
                                <span
                                    class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ __('Horizon') }}</span>
                                <span
                                    class="text-[10px] font-bold text-white uppercase">{{ str_replace('_', ' ', $onboarding->investment_goal ?? __('N/A')) }}</span>
                            </div>
                            <div class="pt-2 px-1">
                                <div class="flex justify-between text-[8px] text-slate-500 uppercase font-bold mb-1.5 italic">
                                    <span>{{ __('System Efficiency') }}</span>
                                    <span
                                        class="text-emerald-400 font-bold">{{ round($investment_stats['total_roi'] > 0 ? ($investment_stats['total_roi'] / max(1, $investment_stats['total_capital'])) * 100 : 0) }}%</span>
                                </div>
                                <div class="w-full bg-white/5 rounded-full h-1 overflow-hidden">
                                    <div class="bg-emerald-500 h-full rounded-full"
                                        style="width: {{ min(100, round($investment_stats['total_roi'] > 0 ? ($investment_stats['total_roi'] / max(1, $investment_stats['total_capital'])) * 100 : 0)) }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recent ROI Distribution --}}
                    <div class="bg-secondary/40 border border-white/5 rounded-2xl p-5">
                        <h4 class="text-white font-bold text-xs mb-4 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-accent-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('Recent Yields') }}
                        </h4>
                        <div class="space-y-3">
                            @forelse($earnings_ledger->take(4) as $earning)
                                <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                                    <div>
                                        <span
                                            class="text-[10px] font-bold text-white block">{{ optional($earning->investment->plan)->name ?? __('Payout') }}</span>
                                        <span
                                            class="text-[8px] text-slate-500 block">{{ $earning->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="text-[11px] font-bold text-emerald-400 block">+{{ showAmount($earning->amount) }}</span>
                                        <span
                                            class="text-[8px] text-slate-500 block font-bold">{{ $earning->interest }}%</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-slate-500 text-[10px] py-4">{{ __('No recent payouts') }}</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- High-Yield Info Card --}}
                    <div
                        class="bg-accent-primary/5 border border-accent-primary/10 rounded-2xl p-5 group hover:bg-accent-primary/10 transition-all">
                        <span
                            class="text-[9px] font-bold text-accent-primary uppercase tracking-widest block mb-1.5 opacity-80">{{ __('Smart Yield Engine') }}</span>
                        <p class="text-[11px] text-white/70 leading-relaxed mb-4 italic">
                            "{{ __('Your compounding capital is automatically integrated into active cycles to maximize yield.') }}"
                        </p>
                        <div class="flex items-center justify-between pt-4 border-t border-white/5">
                            <div>
                                <span
                                    class="text-[8px] text-slate-500 uppercase block font-bold tracking-widest mb-0.5">{{ __('Life-Time Yield') }}</span>
                                <span
                                    class="text-sm font-bold text-white font-mono">{{ showAmount($investment_stats['total_interest']) }}</span>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                                <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 5: Trading Cockpit (Forex, Futures, Margin) --}}
        <div class="mt-12">
            {{-- Cockpit Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-black text-white flex items-center gap-3">
                        <span class="w-2 h-8 bg-accent-primary rounded-full shadow-[0_0_15px_rgba(139,92,246,0.5)]"></span>
                        {{ __('Trading Cockpit') }}
                    </h2>
                    <p class="text-slate-500 text-sm mt-1 uppercase tracking-widest font-bold opacity-70">
                        {{ __('Unified Terminal • Multi-Segment Execution') }}
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex flex-col items-end">
                        <span class="text-[10px] text-slate-500 uppercase font-black">{{ __('Total Exposure') }}</span>
                        <span class="text-lg font-mono font-bold text-white">{{ showAmount($total_market_exposure) }}</span>
                    </div>
                    <div class="w-px h-8 bg-white/10"></div>
                </div>
            </div>

            {{-- Trading Health Grid (Account Monitoring) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                @foreach ($trading_accounts as $acc)
                    <div
                        class="bg-secondary/40 border border-white/5 rounded-2xl p-5 group hover:border-accent-primary/20 transition-all relative overflow-hidden">
                        <div class="flex items-center justify-between mb-4">
                            <span
                                class="text-[9px] font-bold text-slate-500 uppercase tracking-widest bg-white/5 px-2 py-1 rounded">{{ $acc->account_type }}
                                • {{ $acc->level }} ({{ $acc->mode }})</span>
                            <div class="flex items-center gap-1.5">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ $acc->account_status == 'active' ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}"></span>
                                <span class="text-[9px] font-bold text-white uppercase">{{ $acc->account_status }}</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <span
                                    class="text-[10px] text-slate-500 uppercase font-bold block mb-0.5">{{ __('Equity') }}</span>
                                <span class="text-xl font-bold text-white font-mono">{{ showAmount($acc->equity) }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-white/5">
                                <div>
                                    <span
                                        class="text-[8px] text-slate-500 uppercase font-bold block">{{ __('Borrowed') }}</span>
                                    <span class="text-[10px] text-white font-bold">{{ showAmount($acc->borrowed) }}</span>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="text-[8px] text-slate-500 uppercase font-bold block">{{ __('Margin Level') }}</span>
                                    <span
                                        class="text-[10px] {{ $acc->margin_call > 120 ? 'text-emerald-400' : ($acc->margin_call > 100 ? 'text-amber-400' : 'text-rose-400') }} font-bold">{{ $acc->margin_call }}%</span>
                                </div>
                            </div>
                        </div>
                        {{-- Background Decor --}}
                        <div
                            class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/5 rounded-full blur-2xl group-hover:bg-accent-primary/5">
                        </div>
                    </div>
                @endforeach
                {{-- Quick Control Card --}}
                <div
                    class="bg-accent-primary/5 border border-dashed border-accent-primary/20 rounded-2xl p-5 flex flex-col items-center justify-center text-center group cursor-pointer hover:bg-accent-primary/10 transition-all">
                    <div
                        class="w-10 h-10 rounded-full bg-accent-primary/10 flex items-center justify-center text-accent-primary mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-white">{{ __('Open Trading Account') }}</span>
                    <span
                        class="text-[10px] text-slate-500 mt-1 uppercase font-bold tracking-tighter">{{ __('Expand Ecosystem') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Main Cockpit Column (Open Positions) --}}
                <div class="space-y-6">
                    <div class="bg-secondary/40 border border-white/5 rounded-2xl overflow-hidden">
                        <div class="p-6 border-b border-white/5">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <h4 class="text-white font-bold text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    {{ __('Active Liquidity Pool') }}
                                </h4>
                                <div class="flex items-center p-1 bg-white/5 rounded-xl border border-white/5">
                                    @foreach (['Futures', 'Margin', 'Forex'] as $segment)
                                        <button onclick="switchCockpitTab('positions', '{{ $segment }}')"
                                            data-tab-btn="positions-{{ $segment }}"
                                            class="cockpit-tab-btn cursor-pointer px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-tighter transition-all {{ $loop->first ? 'bg-accent-primary text-white shadow-lg' : 'text-slate-500 hover:text-white' }}">
                                            {{ $segment }}
                                            <span
                                                class="ml-1 opacity-50">({{ $categorized_positions[$segment]->count() }})</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-white/5 text-slate-500">
                                    <tr>
                                        <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">
                                            {{ __('Asset') }}</th>
                                        <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">
                                            {{ __('Side/Size') }}</th>
                                        <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">
                                            {{ __('Entry vs Mark') }}</th>
                                        <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">
                                            {{ __('Risk Control') }}</th>
                                        <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-right">
                                            {{ __('Unrealized PnL') }}</th>
                                    </tr>
                                </thead>
                                @foreach ($categorized_positions as $type => $positions)
                                    <tbody data-tab-panel="positions-{{ $type }}"
                                        class="cockpit-tab-panel divide-y divide-white/5 {{ $loop->first ? '' : 'hidden' }}">
                                        @forelse ($positions as $pos)
                                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="w-8 h-8 rounded bg-white/5 flex items-center justify-center font-bold text-[10px] text-white">
                                                            {{ substr($pos['symbol'], 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="text-xs font-bold text-white block">{{ $pos['symbol'] }}</span>
                                                            <span
                                                                class="text-[9px] text-slate-500 uppercase font-bold tracking-tighter">{{ $type }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="px-2 py-0.5 rounded text-[9px] font-black uppercase {{ $pos['side'] == 'buy' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                                            {{ $pos['side'] }}
                                                        </span>
                                                        <span
                                                            class="text-[10px] text-slate-500 font-mono italic">{{ $pos['leverage'] }}x</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="space-y-0.5">
                                                        <span
                                                            class="text-[10px] text-slate-300 block font-mono">{{ number_format($pos['entry'], 4) }}</span>
                                                        <span
                                                            class="text-[11px] text-white block font-black font-mono">{{ number_format($pos['current'], 4) }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-col gap-1">
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="text-[8px] text-slate-500 font-bold uppercase w-4">TP</span>
                                                            <span
                                                                class="text-[9px] text-emerald-400 font-bold font-mono">{{ $pos['tp'] ? number_format($pos['tp'], 4) : '—' }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="text-[8px] text-slate-500 font-bold uppercase w-4">SL</span>
                                                            <span
                                                                class="text-[9px] text-rose-400 font-bold font-mono">{{ $pos['sl'] ? number_format($pos['sl'], 4) : '—' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <div>
                                                        <span
                                                            class="text-sm font-black font-mono {{ $pos['pnl'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                                            {{ $pos['pnl'] >= 0 ? '+' : '' }}{{ showAmount($pos['pnl']) }}
                                                        </span>
                                                        <span
                                                            class="text-[9px] text-slate-500 block font-bold uppercase">{{ __('Real-time PnL') }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-12 text-center">
                                                    <p
                                                        class="text-slate-500 text-xs font-bold uppercase tracking-widest opacity-40">
                                                        {{ __('No active :market positions', ['market' => $type]) }}</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Recent Orders Ledger --}}
                <div class="bg-secondary/40 border border-white/5 rounded-2xl p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <h4 class="text-white font-bold text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-accent-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('Order Flow') }}
                        </h4>
                        <div class="flex items-center p-1 bg-white/5 rounded-xl border border-white/5 self-start">
                            @foreach (['Futures', 'Margin', 'Forex'] as $segment)
                                <button onclick="switchCockpitTab('orders', '{{ $segment }}')"
                                    data-tab-btn="orders-{{ $segment }}"
                                    class="cockpit-tab-btn cursor-pointer px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-tighter transition-all {{ $loop->first ? 'bg-accent-primary text-white shadow-lg' : 'text-slate-500 hover:text-white' }}">
                                    {{ $segment }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-4">
                        @foreach ($categorized_orders as $type => $orders)
                            <div data-tab-panel="orders-{{ $type }}"
                                class="cockpit-tab-panel space-y-4 {{ $loop->first ? '' : 'hidden' }}">
                                @forelse ($orders as $order)
                                    <div
                                        class="flex items-center justify-between group py-3 border-b border-white/5 last:border-0 grow">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-1.5 h-1.5 rounded-full {{ $order['status'] == 'filled' ? 'bg-emerald-400' : ($order['status'] == 'cancelled' ? 'bg-rose-400' : 'bg-amber-400 animate-pulse') }}">
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-bold text-white">{{ $order['asset'] }}</span>
                                                    <span
                                                        class="text-[9px] text-slate-500 uppercase bg-white/5 px-1.5 py-0.5 rounded tracking-tighter">{{ $type }}</span>
                                                </div>
                                                <span
                                                    class="text-[10px] text-slate-500 block mt-0.5 italic">{{ $order['at']->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-8">
                                            <div class="hidden sm:block">
                                                <span
                                                    class="text-[8px] text-slate-500 uppercase font-black block mb-0.5">{{ __('Action') }}</span>
                                                <span
                                                    class="text-[10px] font-bold {{ $order['side'] == 'buy' ? 'text-emerald-400' : 'text-rose-400' }} uppercase">{{ $order['side'] }}
                                                    @ {{ number_format($order['price'], 2) }}</span>
                                            </div>
                                            <div class="text-right">
                                                <span
                                                    class="text-[10px] font-black uppercase tracking-widest {{ $order['status'] == 'filled' ? 'text-emerald-400' : ($order['status'] == 'cancelled' ? 'text-rose-400' : 'text-amber-400') }}">
                                                    {{ $order['status'] }}
                                                </span>
                                                <span
                                                    class="text-[8px] text-slate-500 block uppercase font-bold">{{ $order['type'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-8 text-center opacity-40">
                                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                            {{ __('No recent :market activity', ['market' => $type]) }}</p>
                                    </div>
                                @endforelse
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- New 3-Column Insights Row --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                {{-- Net Asset Exposure --}}
                <div class="bg-secondary/40 border border-white/5 rounded-2xl p-6 relative overflow-hidden">
                    <h4 class="text-white font-bold text-sm mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        {{ __('Net Asset Exposure') }}
                    </h4>
                    <div class="space-y-4">
                        @forelse ($net_exposure as $ticker => $size)
                            <div class="space-y-2">
                                <div class="flex justify-between items-center text-[10px]">
                                    <span class="text-white font-bold uppercase tracking-tighter">{{ $ticker }}</span>
                                    <span class="text-slate-500 font-mono">{{ number_format($size, 4) }} Units</span>
                                </div>
                                <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full {{ $size > 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"
                                        style="width: {{ min(100, (abs($size) / max(1, $net_exposure->map(fn($v) => abs($v))->max())) * 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center opacity-40">
                                <p class="text-[10px] font-bold text-slate-500 uppercase">{{ __('No aggregated exposure') }}
                                </p>
                            </div>
                        @endforelse
                    </div>
                    <div class="absolute -left-6 -bottom-6 w-20 h-20 bg-accent-primary/5 blur-3xl rounded-full"></div>
                </div>

                {{-- Liquidity Stress / Risk Meter --}}
                <div class="bg-secondary/40 border border-white/5 rounded-2xl p-6 relative group overflow-hidden">
                    <h4 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ __('Liquidity Stress') }}
                    </h4>
                    @php $avg_level = $trading_accounts->avg('margin_call') ?? 100; @endphp
                    <div class="text-center py-4">
                        <div class="relative inline-block">
                            <span
                                class="text-4xl font-black font-mono {{ $avg_level > 200 ? 'text-emerald-400' : ($avg_level > 120 ? 'text-amber-400' : 'text-rose-400') }}">
                                {{ round($avg_level) }}%
                            </span>
                            <span
                                class="text-[9px] text-slate-500 uppercase font-black block mt-1 tracking-widest">{{ __('Avg Margin Level') }}</span>
                        </div>
                    </div>
                    <div class="p-4 bg-white/5 rounded-xl border border-white/5 mt-4">
                        <span
                            class="text-[9px] text-slate-500 uppercase font-black block mb-2">{{ __('Stress Analysis') }}</span>
                        <p class="text-[10px] text-white/70 leading-relaxed italic">
                            @if ($avg_level > 200)
                                "{{ __('Optimal safety buffer. Portfolio can withstand significant volatility.') }}"
                            @elseif($avg_level > 120)
                                "{{ __('Moderate stress detected. Monitor positions near resistance levels.') }}"
                            @else
                                "{{ __('Critical margin levels. High risk of liquidation events.') }}"
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Professional Info Widget --}}
                <div
                    class="p-6 bg-accent-primary/5 rounded-2xl border border-accent-primary/10 group hover:bg-accent-primary/10 transition-all cursor-default relative overflow-hidden">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-accent-primary group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m-4 6h4m-4 4h4m1 1H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-white font-black text-xs uppercase tracking-widest">{{ __('Market Ops') }}
                            </h5>
                            <span
                                class="text-[9px] text-accent-primary uppercase font-bold">{{ __('Terminal Active') }}</span>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 leading-relaxed">
                        {{ __('The Trading Cockpit aggregates real-time traces from all connected liquidity providers. Unrealized PnL is calculated based on Mark Price feeds.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Section 6: Money Movement Hub --}}
        <div class="mt-12 pt-12 border-t border-white/5">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-black text-white flex items-center gap-3">
                        <span class="w-2 h-8 bg-emerald-500 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.5)]"></span>
                        {{ __('Money Movement Hub') }}
                    </h2>
                    <p class="text-slate-500 text-sm mt-1 uppercase tracking-widest font-bold opacity-70">
                        {{ __('Operations • Reconciliation • Cashflow Hub') }}
                    </p>
                </div>
                <div class="flex items-center gap-6">
                    <div class="hidden md:flex flex-col items-end">
                        <span class="text-[10px] text-slate-500 uppercase font-black">{{ __('Net Cashflow (MTD)') }}</span>
                        <span
                            class="text-lg font-mono font-bold {{ $movement_stats['net_cashflow'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $movement_stats['net_cashflow'] >= 0 ? '+' : '' }}{{ showAmount($movement_stats['net_cashflow']) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Column 1: Deposit Telemetry --}}
                <div class="bg-secondary/50 border border-white/5 rounded-2xl p-6 backdrop-blur-sm flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-sm uppercase tracking-wider">{{ __('Deposits') }}</h3>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span
                                        class="text-[9px] text-slate-500 uppercase font-bold">{{ __('Active Channels') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                            <span
                                class="text-[8px] text-slate-500 uppercase font-bold block mb-1">{{ __('Approved') }}</span>
                            <span
                                class="text-sm font-bold text-white">{{ showAmount($movement_stats['deposits']['approved']) }}</span>
                        </div>
                        <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                            <span class="text-[8px] text-slate-500 uppercase font-bold block mb-1">{{ __('Pending') }}</span>
                            <span
                                class="text-sm font-bold text-amber-400">{{ showAmount($movement_stats['deposits']['pending']) }}</span>
                        </div>
                    </div>

                    {{-- Data List --}}
                    <div class="space-y-4 mb-auto">
                        <h4 class="text-[9px] text-slate-500 uppercase font-bold tracking-widest border-b border-white/5 pb-2">
                            {{ __('Recent Inflow') }}</h4>
                        <div class="space-y-3">
                            @forelse($movement_stats['recent_deposits'] as $deposit)
                                <div class="flex items-center justify-between group">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-xs font-bold text-white group-hover:text-emerald-400 transition-colors uppercase tracking-tight">{{ $deposit->paymentMethod?->name ?? __('Direct') }}</span>
                                        <span
                                            class="text-[9px] text-slate-500 font-mono">{{ $deposit->created_at->format('M d, H:i') }}</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs font-bold text-white">{{ showAmount($deposit->amount) }}</div>
                                        <div
                                            class="text-[9px] {{ $deposit->status == 'completed' ? 'text-emerald-500' : ($deposit->status == 'pending' ? 'text-amber-500' : 'text-rose-500') }} font-bold uppercase">
                                            {{ __($deposit->status) }}</div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-[10px] text-slate-500 italic py-2">{{ __('No recent deposits') }}</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Footer Insight --}}
                    <div class="mt-8 pt-4 border-t border-white/5">
                        <div class="flex items-center justify-between opacity-80">
                            <div>
                                <span
                                    class="text-[8px] text-slate-500 uppercase block font-bold tracking-widest">{{ __('Avg Size') }}</span>
                                <span
                                    class="text-xs font-bold text-white">{{ showAmount($movement_stats['deposits']['avg_size']) }}</span>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-[8px] text-slate-500 uppercase block font-bold tracking-widest">{{ __('Top Method') }}</span>
                                <span
                                    class="text-xs font-bold text-emerald-400">{{ $movement_stats['deposits']['most_used_method'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Column 2: Withdrawal Telemetry --}}
                <div class="bg-secondary/50 border border-white/5 rounded-2xl p-6 backdrop-blur-sm flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17l-5 5-5-5M12 4v18" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-sm uppercase tracking-wider">{{ __('Withdrawals') }}
                                </h3>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                    <span
                                        class="text-[9px] text-slate-500 uppercase font-bold">{{ __('Outbound Pipeline') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                            <span class="text-[8px] text-slate-500 uppercase font-bold block mb-1">{{ __('Paid') }}</span>
                            <span
                                class="text-sm font-bold text-white">{{ showAmount($movement_stats['withdrawals']['paid']) }}</span>
                        </div>
                        <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                            <span
                                class="text-[8px] text-slate-500 uppercase font-bold block mb-1">{{ __('Settling') }}</span>
                            <span
                                class="text-sm font-bold text-purple-400">{{ showAmount($movement_stats['withdrawals']['pending']) }}</span>
                        </div>
                    </div>

                    {{-- Data List --}}
                    <div class="space-y-4 mb-auto">
                        <h4 class="text-[9px] text-slate-500 uppercase font-bold tracking-widest border-b border-white/5 pb-2">
                            {{ __('Recent Outflow') }}</h4>
                        <div class="space-y-3">
                            @forelse($movement_stats['recent_withdrawals'] as $withdrawal)
                                <div class="flex items-center justify-between group">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-xs font-bold text-white group-hover:text-purple-400 transition-colors uppercase tracking-tight">{{ $withdrawal->withdrawalMethod?->name ?? __('Direct') }}</span>
                                        <span
                                            class="text-[9px] text-slate-500 font-mono">{{ $withdrawal->created_at->format('M d, H:i') }}</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs font-bold text-white">{{ showAmount($withdrawal->amount) }}</div>
                                        <div
                                            class="text-[9px] {{ $withdrawal->status == 'completed' ? 'text-emerald-500' : ($withdrawal->status == 'pending' ? 'text-purple-500' : 'text-rose-500') }} font-bold uppercase">
                                            {{ $withdrawal->status == 'completed' ? __('Paid') : __($withdrawal->status) }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-[10px] text-slate-500 italic py-2">{{ __('No recent withdrawals') }}</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Footer Insight --}}
                    <div class="mt-8 pt-4 border-t border-white/5">
                        <div class="flex items-center justify-between opacity-80">
                            <div>
                                <span
                                    class="text-[8px] text-slate-500 uppercase block font-bold tracking-widest">{{ __('Total Fees Paid') }}</span>
                                <span
                                    class="text-xs font-bold text-white">{{ showAmount($movement_stats['withdrawals']['total_fees']) }}</span>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-[8px] text-slate-500 uppercase block font-bold tracking-widest">{{ __('rejected') }}</span>
                                <span
                                    class="text-xs font-bold text-rose-400">{{ showAmount($movement_stats['withdrawals']['rejected']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Column 3: Transactions Hub --}}
                <div class="bg-secondary/50 border border-white/5 rounded-2xl p-6 backdrop-blur-sm flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-accent-primary/10 flex items-center justify-center text-accent-primary">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-sm uppercase tracking-wider">{{ __('Ops Ledger') }}</h3>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-accent-primary animate-pulse"></span>
                                    <span
                                        class="text-[9px] text-slate-500 uppercase font-bold">{{ __('System Traces') }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('user.transactions') }}"
                            class="text-[10px] text-accent-primary font-bold uppercase hover:underline">{{ __('View All') }}</a>
                    </div>

                    {{-- Monthly Highlights --}}
                    <div class="bg-white/5 rounded-xl p-4 border border-white/5 mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span
                                class="text-[9px] text-slate-500 uppercase font-bold tracking-widest">{{ __('Monthly Fees') }}</span>
                            <span
                                class="text-xs font-bold text-amber-500">{{ showAmount($movement_stats['total_fees_month']) }}</span>
                        </div>
                        <div class="w-full bg-white/5 h-1 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full w-2/3"></div>
                        </div>
                    </div>

                    {{-- Transactions List --}}
                    <div class="space-y-4 mb-auto overflow-y-auto max-h-[220px] scrollbar-hide">
                        @forelse($recent_transactions_hub as $tx)
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 transition-all group">
                                <div
                                    class="w-8 h-8 rounded-lg {{ $tx->type == 'credit' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }} flex items-center justify-center">
                                    @if ($tx->type == 'credit')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <span
                                            class="text-[11px] font-bold text-white truncate pr-2">{{ $tx->description }}</span>
                                        <span
                                            class="text-[11px] font-bold {{ $tx->type == 'credit' ? 'text-emerald-400' : 'text-rose-400' }}">
                                            {{ $tx->type == 'credit' ? '+' : '-' }}{{ showAmount($tx->amount) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-[8px] text-slate-500 font-mono">{{ $tx->created_at->format('d/m H:i') }}</span>
                                        <span
                                            class="text-[8px] text-slate-600 uppercase font-black tracking-tighter">{{ $tx->reference }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-[10px] text-slate-500 italic py-2">{{ __('No recent transactions') }}</p>
                        @endforelse
                    </div>

                    {{-- Footer Action --}}
                    <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-center">
                        <div class="flex -space-x-2">
                            <div
                                class="w-6 h-6 rounded-full border-2 border-secondary bg-slate-800 flex items-center justify-center text-[8px] text-white">
                                B</div>
                            <div
                                class="w-6 h-6 rounded-full border-2 border-secondary bg-slate-700 flex items-center justify-center text-[8px] text-white font-bold">
                                +</div>
                        </div>
                        <span
                            class="ml-3 text-[9px] text-slate-500 uppercase font-bold">{{ __('Unified reconciliation active') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        {{-- Charting & Carousel Dependencies --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. Swiper Initialization (Fanned Cards)
                if (document.querySelector('.mySwiper')) {
                    new Swiper(".mySwiper", {
                        effect: "cards",
                        grabCursor: true,
                        loop: true,
                        centeredSlides: true,
                        slidesPerView: "auto",
                        autoplay: {
                            delay: 3500,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true,
                        },
                        watchSlidesProgress: true,
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

                // 2. Money Distribution Donut Chart
                const distData = @json($money_distribution);
                const distLabels = Object.keys(distData);
                const distValues = Object.values(distData);

                if (document.querySelector("#distributionDonutChart")) {
                    const distOptions = {
                        chart: {
                            type: 'donut',
                            height: 280,
                            background: 'transparent',
                            sparkline: {
                                enabled: false
                            }
                        },
                        series: distValues,
                        labels: distLabels,
                        colors: ['#8b5cf6', '#6366f1', '#ec4899', '#10b981', '#f59e0b'],
                        stroke: {
                            show: false
                        },
                        legend: {
                            position: 'bottom',
                            fontSize: '10px',
                            fontFamily: 'inherit',
                            fontWeight: 700,
                            labels: {
                                colors: '#94a3b8'
                            },
                            markers: {
                                radius: 12
                            },
                            itemMargin: {
                                horizontal: 5,
                                vertical: 5
                            }
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '78%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: "{{ __('Total Value') }}",
                                            color: '#94a3b8',
                                            fontSize: '10px',
                                            fontWeight: 700,
                                            formatter: function(w) {
                                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                                return new Intl.NumberFormat('en-US', {
                                                    style: 'currency',
                                                    currency: '{{ getSetting('currency', 'USD') }}',
                                                    maximumFractionDigits: 0
                                                }).format(total);
                                            }
                                        },
                                        value: {
                                            show: true,
                                            fontSize: '18px',
                                            fontWeight: 900,
                                            color: '#ffffff',
                                            formatter: function(val) {
                                                return new Intl.NumberFormat('en-US', {
                                                    style: 'currency',
                                                    currency: '{{ getSetting('currency', 'USD') }}',
                                                    maximumFractionDigits: 0
                                                }).format(val);
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        tooltip: {
                            theme: 'dark',
                            y: {
                                formatter: (val) => new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: '{{ getSetting('currency', 'USD') }}'
                                }).format(val)
                            }
                        }
                    };
                    new ApexCharts(document.querySelector("#distributionDonutChart"), distOptions).render();
                }

                // 3. Transaction Trend Chart
                const chartData = @json($chart_data);
                let trendChart;

                if (document.querySelector("#transactionTrendChart")) {
                    const trendOptions = {
                        series: [{
                            name: "{{ __('Credits') }}",
                            data: chartData.credits
                        }, {
                            name: "{{ __('Debits') }}",
                            data: chartData.debits
                        }],
                        chart: {
                            type: 'area',
                            height: 350,
                            toolbar: {
                                show: false
                            },
                            stacked: false,
                            zoom: {
                                enabled: false
                            }
                        },
                        colors: ['#10b981', '#ef4444'],
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.7,
                                opacityTo: 0.2,
                                stops: [0, 90, 100]
                            }
                        },
                        markers: {
                            size: 4,
                            colors: ['#10b981', '#ef4444'],
                            strokeColors: '#0f172a',
                            strokeWidth: 2,
                            hover: {
                                size: 6
                            }
                        },
                        xaxis: {
                            categories: chartData.labels,
                            axisBorder: {
                                show: false
                            },
                            axisTicks: {
                                show: false
                            },
                            labels: {
                                style: {
                                    colors: '#64748b',
                                    fontSize: '10px'
                                },
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: '#64748b',
                                    fontSize: '10px'
                                },
                                formatter: (val) => '{{ getSetting('currency_symbol', '$') }}' + Intl
                                    .NumberFormat().format(val)
                            }
                        },
                        grid: {
                            borderColor: 'rgba(255, 255, 255, 0.03)',
                            padding: {
                                right: 20
                            }
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            labels: {
                                colors: '#94a3b8'
                            }
                        },
                        tooltip: {
                            theme: 'dark'
                        }
                    };
                    trendChart = new ApexCharts(document.querySelector("#transactionTrendChart"), trendOptions);
                    trendChart.render();
                }

                // Chart Scaling Logic
                const filterButton = document.getElementById('tx-chart-filter-button');
                const filterMenu = document.getElementById('tx-chart-filter-menu');
                const filterOptions = document.querySelectorAll('#tx-chart-filter-menu button');
                const filterLabel = document.getElementById('current-filter-label');

                // Set initial active state for 7 Days
                document.querySelector('#tx-chart-filter-menu button[data-days="7"]').classList.add('text-white',
                    'bg-white/5');


                // Toggle Dropdown
                filterButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    filterMenu.classList.toggle('opacity-0');
                    filterMenu.classList.toggle('invisible');
                    filterMenu.classList.toggle('scale-95');
                });

                // Close on click outside
                document.addEventListener('click', () => {
                    filterMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                });

                // Handle Selection
                filterOptions.forEach(opt => {
                    opt.addEventListener('click', async function() {
                        const days = this.getAttribute('data-days');
                        const label = this.innerText;

                        // UI Update
                        filterLabel.innerText = label;
                        filterOptions.forEach(o => o.classList.remove('text-white',
                            'bg-white/5'));
                        this.classList.add('text-white', 'bg-white/5');

                        try {
                            const response = await fetch(
                                `{{ route('user.dashboard') }}?days=${days}`, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });
                            const newData = await response.json();

                            trendChart.updateOptions({
                                xaxis: {
                                    categories: newData.labels
                                }
                            });
                            trendChart.updateSeries([{
                                name: "{{ __('Credits') }}",
                                data: newData.credits
                            }, {
                                name: "{{ __('Debits') }}",
                                data: newData.debits
                            }]);
                        } catch (error) {
                            console.error('Failed to fetch chart data:', error);
                        }
                    });
                });

                // 3. ROI Generation Trend Chart
                const earningsData = @json($earnings_chart_data);
                let yieldChart;
                if (document.querySelector("#earningsChart")) {
                    const earningsOptions = {
                        chart: {
                            type: 'area',
                            height: 180,
                            toolbar: {
                                show: false
                            },
                            background: 'transparent',
                            sparkline: {
                                enabled: false
                            }
                        },
                        series: [{
                            name: "{{ __('ROI Payout') }}",
                            data: earningsData.amounts
                        }],
                        xaxis: {
                            categories: earningsData.labels,
                            labels: {
                                show: true,
                                style: {
                                    colors: '#64748b',
                                    fontSize: '10px'
                                },
                                rotate: -45,
                                maxHeight: 30
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
                                show: true,
                                style: {
                                    colors: '#64748b',
                                    fontSize: '10px'
                                },
                                formatter: (val) => val.toFixed(2)
                            }
                        },
                        grid: {
                            borderColor: 'rgba(255, 255, 255, 0.05)',
                            strokeDashArray: 4,
                            padding: {
                                left: 0,
                                right: 0
                            }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2,
                            colors: ['#8b5cf6']
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
                                    },
                                    {
                                        offset: 100,
                                        color: '#8b5cf6',
                                        opacity: 0
                                    }
                                ]
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        tooltip: {
                            theme: 'dark'
                        },
                        theme: {
                            mode: 'dark'
                        },
                        colors: ['#8b5cf6']
                    };
                    yieldChart = new ApexCharts(document.querySelector("#earningsChart"), earningsOptions);
                    yieldChart.render();
                }

                // Yield Analytics Scaling Logic
                const yieldFilterButtons = document.querySelectorAll('.yield-filter-btn');
                yieldFilterButtons.forEach(btn => {
                    btn.addEventListener('click', async function() {
                        const days = this.getAttribute('data-yield-days');

                        // UI Update
                        yieldFilterButtons.forEach(b => b.classList.remove('bg-white/10',
                            'text-white'));
                        yieldFilterButtons.forEach(b => b.classList.add('text-slate-500'));
                        this.classList.remove('text-slate-500');
                        this.classList.add('bg-white/10', 'text-white');

                        try {
                            const response = await fetch(
                                `{{ route('user.dashboard') }}?yield_days=${days}`, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });
                            const newData = await response.json();

                            yieldChart.updateOptions({
                                xaxis: {
                                    categories: newData.labels
                                }
                            });
                            yieldChart.updateSeries([{
                                name: "{{ __('ROI Payout') }}",
                                data: newData.amounts
                            }]);
                        } catch (error) {
                            console.error('Failed to fetch yield chart data:', error);
                        }
                    });
                });

                // Cockpit Tab Switching Logic
                window.switchCockpitTab = function(group, segment) {
                    // Update Buttons
                    const buttons = document.querySelectorAll(`[data-tab-btn^="${group}-"]`);
                    buttons.forEach(btn => {
                        const isTarget = btn.getAttribute('data-tab-btn') === `${group}-${segment}`;
                        if (isTarget) {
                            btn.classList.add('bg-accent-primary', 'text-white', 'shadow-lg');
                            btn.classList.remove('text-slate-500', 'hover:text-white');
                        } else {
                            btn.classList.remove('bg-accent-primary', 'text-white', 'shadow-lg');
                            btn.classList.add('text-slate-500', 'hover:text-white');
                        }
                    });

                    // Update Panels
                    const panels = document.querySelectorAll(`[data-tab-panel^="${group}-"]`);
                    panels.forEach(panel => {
                        const isTarget = panel.getAttribute('data-tab-panel') === `${group}-${segment}`;
                        if (isTarget) {
                            panel.classList.remove('hidden');
                        } else {
                            panel.classList.add('hidden');
                        }
                    });
                };
            });
        </script>
    @endsection
