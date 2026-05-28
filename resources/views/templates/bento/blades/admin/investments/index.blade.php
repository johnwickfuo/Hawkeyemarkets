@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div class="space-y-8">
        {{-- Investment Statistics --}}
        {{-- Message  if module is disabled --}}
        @if (!moduleEnabled('investment_module'))
            <div
                class="relative z-10 p-8 flex flex-col items-center justify-center text-center h-[300px] bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden backdrop-blur-xl">
                <div
                    class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-500" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">
                    {{ __('Investment module disabled') }}</h4>
                <p class="text-[10px] text-slate-500 max-w-[200px] mb-6 font-medium leading-relaxed italic">
                    {{ __('Investment module is disabled. Please enable the investment module in settings to initialize.') }}
                </p>
                <a href="{{ route('admin.settings.modules.index') }}"
                    class="px-5 py-2 rounded-xl bg-white/5 border border-white/10 text-[9px] font-black text-white uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all active:scale-95">
                    {{ __('Settings') }}
                </a>
            </div>
        @endif

        @if (moduleEnabled('investment_module'))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="p-3 bg-accent-primary/20 rounded-lg text-accent-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                                {{ __('Total Invested') }}</p>
                            <h4 class="text-xl font-bold text-white">{{ showAmount($stats['total_invested']) }}</h4>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="p-3 bg-emerald-500/20 rounded-lg text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                                {{ __('Total ROI') }}</p>
                            <h4 class="text-xl font-bold text-white">{{ showAmount($stats['total_roi']) }}</h4>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="p-3 bg-blue-500/20 rounded-lg text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                                {{ __('Active') }}</p>
                            <h4 class="text-xl font-bold text-white">{{ number_format($stats['active_count']) }}</h4>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="p-3 bg-amber-500/20 rounded-lg text-amber-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                                {{ __('Completed') }}</p>
                            <h4 class="text-xl font-bold text-white">{{ number_format($stats['completed_count']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Graph and Chart --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                {{-- Trend Graph (2/3 width) --}}
                <div
                    class="lg:col-span-2 relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                    <div
                        class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                    </div>
                    <div
                        class="absolute top-0 left-0 -mt-10 -ml-10 w-48 h-48 bg-accent-primary/10 rounded-full blur-3xl pointer-events-none z-0">
                    </div>

                    <div class="relative z-10 p-6">
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                            <div>
                                <div id="graph-title" class="text-sm font-bold text-white tracking-wide">
                                    {{ __('Investment Growth Trend') }}
                                </div>
                                <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                                    {{ __('Capital Invested') }} · {{ getSetting('currency') }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                <div
                                    class="flex items-center flex-wrap gap-1 bg-white/[0.03] border border-white/[0.06] rounded-lg p-1">
                                    @foreach (['7d' => '7D', '30d' => '30D', '60d' => '60D', '90d' => '90D', '1y' => '1Y', 'ytd' => 'YTD'] as $key => $label)
                                        <button data-period="{{ $key }}"
                                            class="graph-period-btn cursor-pointer px-2.5 py-1 rounded-md text-[9px] font-bold uppercase tracking-wider transition-all
                                            {{ $key === '7d' ? 'bg-white/10 text-white' : 'text-slate-500 hover:text-slate-300' }}">
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div id="graph-legend" class="flex items-center gap-4 mb-4 flex-wrap"></div>

                        <div class="relative h-64">
                            <canvas id="investmentTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Plan Distribution (1/3 width) --}}
                <div
                    class="relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                    <div
                        class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                    </div>
                    <div
                        class="absolute bottom-0 right-0 -mb-10 -mr-10 w-40 h-40 bg-accent-primary/15 rounded-full blur-2xl pointer-events-none z-0">
                    </div>

                    <div class="relative z-10 p-6 h-full flex flex-col">
                        <div class="mb-5">
                            <div class="text-sm font-bold text-white tracking-wide">{{ __('Investments by Plan') }}</div>
                            <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                                {{ __('Invested vs ROI Earned') }}
                            </p>
                        </div>

                        <div class="relative flex-1" style="min-height: 250px;">
                            <canvas id="planDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Investments Table Section --}}
            <div class="bg-secondary border border-white/5 rounded-2xl overflow-hidden relative" id="investments-wrapper">
                {{-- Loading Spinner Overlay --}}
                <div id="loading-spinner"
                    class="hidden absolute inset-0 bg-secondary/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-opacity duration-300">
                    <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin">
                    </div>
                    <p class="mt-3 text-text-secondary font-medium">{{ __('Loading investments...') }}</p>
                </div>

                {{-- Table Filter Header --}}
                <div class="p-6 border-b border-white/5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-text-secondary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        {{ __('Investments History') }}
                    </h3>

                    {{-- Filters --}}
                    <form id="filter-form" action="{{ route('admin.investments.index') }}" method="GET"
                        class="flex flex-wrap gap-2 items-center">
                        @if (request('user_id'))
                            <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        @endif
                        <div class="relative">
                            <input type="text" name="search" placeholder="{{ __('User or Plan Name...') }}"
                                value="{{ request('search') }}"
                                class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-white text-base placeholder-text-secondary focus:ring-1 focus:ring-accent-primary focus:border-accent-primary outline-none w-full lg:w-64">
                        </div>

                        <div class="relative custom-dropdown" id="status-filter-dropdown">
                            <input type="hidden" name="status" id="status-input"
                                value="{{ request('status', 'all') }}">
                            <button type="button"
                                class="bg-[#1E293B] border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[120px] dropdown-btn cursor-pointer">
                                <span class="selected-label">
                                    @if (request('status') == 'active')
                                        {{ __('Active') }}
                                    @elseif(request('status') == 'completed')
                                        {{ __('Completed') }}
                                    @elseif(request('status') == 'suspended')
                                        {{ __('Suspended') }}
                                    @else
                                        {{ __('All Status') }}
                                    @endif
                                </span>
                                <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <div
                                class="absolute top-full right-0 mt-2 w-full lg:w-48 bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-[60] py-1 hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200">
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-value="all">{{ __('All Status') }}</div>
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-value="active">{{ __('Active') }}</div>
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-value="completed">{{ __('Completed') }}</div>
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-value="suspended">{{ __('Suspended') }}</div>
                            </div>
                        </div>

                        <div class="relative custom-dropdown" id="export-dropdown">
                            <button type="button"
                                class="bg-[#1E293B] border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[120px] dropdown-btn cursor-pointer">
                                <span class="selected-label">{{ __('Export') }}</span>
                                <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <div
                                class="absolute top-full right-0 mt-2 w-full lg:w-48 bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-[60] py-1 hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200">
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-export="csv">CSV</div>
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-export="sql">SQL</div>
                                <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                    data-export="pdf">PDF</div>
                            </div>
                        </div>

                        <button type="submit"
                            class="p-2 bg-accent-primary/10 text-accent-primary rounded-lg hover:bg-accent-primary/20 transition-all cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto overflow-y-clip">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead>
                            <tr class="bg-white/[0.02]">
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                    {{ __('User') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                    {{ __('Plan') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                    {{ __('Date') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                    {{ __('Invested') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                    {{ __('Compounding') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                    {{ __('ROI Earned') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-center">
                                    {{ __('Cycles') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-center">
                                    {{ __('Auto') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-center">
                                    {{ __('Status') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-right">
                                    {{ __('Next ROI/Expires') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-right">
                                    {{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($investments as $investment)
                                <tr class="hover:bg-white/[0.01] transition-all group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($investment->user->photo)
                                                <div
                                                    class="w-8 h-8 rounded-full border border-white/10 shadow-lg overflow-hidden shrink-0">
                                                    <img src="{{ asset('storage/profile/' . $investment->user->photo) }}"
                                                        alt="{{ $investment->user->username }}"
                                                        class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div
                                                    class="w-8 h-8 rounded-full bg-accent-primary/10 flex items-center justify-center text-accent-primary font-black text-xs shrink-0">
                                                    {{ substr($investment->user->username, 0, 2) }}
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('admin.users.detail', $investment->user->id) }}"
                                                    class="text-xs text-white font-bold hover:text-accent-primary transition-colors block cursor-pointer">{{ $investment->user->username }}</a>
                                                <span
                                                    class="text-[10px] text-text-secondary block mb-1.5">{{ $investment->user->email }}</span>

                                                <div class="flex items-center gap-1.5">
                                                    @php
                                                        $goalColor = match ($investment->plan->investment_goal) {
                                                            'short_term'
                                                                => 'text-sky-400 bg-sky-400/10 border-sky-400/20',
                                                            'medium_term'
                                                                => 'text-indigo-400 bg-indigo-400/10 border-indigo-400/20',
                                                            'long_term'
                                                                => 'text-violet-400 bg-violet-400/10 border-violet-400/20',
                                                            default => 'text-slate-400 bg-white/5 border-white/5',
                                                        };

                                                        $riskColor = match ($investment->plan->risk_profile) {
                                                            'conservative'
                                                                => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
                                                            'balanced'
                                                                => 'text-amber-400 bg-amber-400/10 border-amber-400/20',
                                                            'growth'
                                                                => 'text-rose-400 bg-rose-400/10 border-rose-400/20',
                                                            default => 'text-slate-400 bg-white/5 border-white/5',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="px-1.5 py-0.5 rounded-md text-[8px] font-bold uppercase tracking-wider border {{ $goalColor }}">{{ __($investment->plan->investment_goal) }}</span>
                                                    <span
                                                        class="px-1.5 py-0.5 rounded-md text-[8px] font-bold uppercase tracking-wider border {{ $riskColor }}">{{ __($investment->plan->risk_profile) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs text-white font-medium block mb-1.5">{{ $investment->plan->name }}</span>
                                        <div class="flex flex-wrap gap-1 max-w-[150px]">
                                            @foreach ((array) $investment->plan->interests as $interest)
                                                <span
                                                    class="text-[8px] px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20">{{ __($interest) }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[10px] text-text-secondary block mb-1">
                                            {{ $investment->created_at->format('M d, Y') }}
                                        </span>
                                        <span class="text-[9px] text-text-secondary/60">
                                            {{ $investment->created_at->format('H:i') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs text-white font-bold">{{ showAmount($investment->capital_invested) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs text-text-secondary">{{ showAmount($investment->compounding_capital) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs text-emerald-400 font-bold">+{{ showAmount($investment->roi_earned) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-[10px] text-white font-bold">{{ $investment->cycle_count }} /
                                            {{ $investment->total_cycles }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($investment->auto_reinvest)
                                            <span class="text-emerald-500">
                                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </span>
                                        @else
                                            <span class="text-text-secondary">
                                                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($investment->status == 'active')
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-tighter">{{ __('Active') }}</span>
                                        @elseif($investment->status == 'completed')
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-500 text-[10px] font-black uppercase tracking-tighter">{{ __('Completed') }}</span>
                                        @elseif($investment->status == 'suspended')
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase tracking-tighter">{{ __('Suspended') }}</span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-red-500/10 text-red-500 text-[10px] font-black uppercase tracking-tighter">{{ $investment->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-[10px] text-white font-bold">{{ $investment->next_roi_at ? date('M d, H:i', $investment->next_roi_at) : 'N/A' }}</span>
                                            <span
                                                class="text-[9px] text-text-secondary">{{ $investment->expires_at ? date('M d, Y', $investment->expires_at) : 'No Expiry' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button"
                                                class="edit-investment-btn p-2 bg-accent-primary/10 text-accent-primary rounded-lg hover:bg-accent-primary/20 transition-all cursor-pointer"
                                                data-id="{{ $investment->id }}" data-status="{{ $investment->status }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button type="button"
                                                class="delete-investment-btn p-2 bg-red-500/10 text-red-400 rounded-lg hover:bg-red-500/20 transition-all cursor-pointer"
                                                data-id="{{ $investment->id }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-text-secondary text-sm italic">
                                        {{ __('No investments found matching your criteria.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($investments->hasPages())
                    <div class="px-6 py-4 border-t border-white/5 ajax-pagination">
                        {{ $investments->links('templates.bento.blades.partials.pagination') }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Edit Status Modal --}}
    <div id="edit-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity modal-close"></div>

            <div
                class="relative w-full max-w-md transform rounded-3xl bg-secondary border border-white/10 p-8 text-left shadow-2xl transition-all">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Edit Status') }}</h3>
                            <p class="text-xs text-slate-500 mt-1 uppercase tracking-widest font-mono">
                                {{ __('Update investment status') }}</p>
                        </div>
                        <button type="button"
                            class="text-slate-500 hover:text-white transition-colors modal-close cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="edit-status-form" class="space-y-6">
                        @csrf
                        <input type="hidden" name="investment_id" id="edit-investment-id">

                        <div class="pb-20">
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 font-mono ml-1">
                                {{ __('Select New Status') }}
                            </label>
                            <div class="relative custom-dropdown" id="edit-status-dropdown" style="z-index: 50;">
                                <button type="button"
                                    class="dropdown-btn w-full flex items-center justify-between gap-3 px-4 py-3.5 rounded-xl bg-white/[0.03] border border-white/10 text-white text-sm focus:border-accent-primary/50 transition-all cursor-pointer">
                                    <span class="selected-label">{{ __('Select Status') }}</span>
                                    <svg class="w-4 h-4 text-slate-500 dropdown-icon transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <input type="hidden" name="status" id="edit-status-value">
                                <div
                                    class="dropdown-menu absolute z-[120] mt-2 w-full bg-slate-900/98 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-2xl py-2 hidden animate-in fade-in slide-in-from-top-2 duration-200">
                                    @foreach (['active', 'suspended', 'completed'] as $st)
                                        <div class="dropdown-option px-4 py-3 text-sm text-slate-400 hover:bg-white/5 hover:text-white transition-colors cursor-pointer capitalize"
                                            data-value="{{ $st }}">
                                            {{ ucfirst($st) }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/5 flex gap-3">
                            <button type="button"
                                class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all modal-close cursor-pointer">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-3 rounded-xl bg-accent-primary text-white font-bold hover:bg-accent-primary/90 shadow-lg shadow-accent-primary/20 transition-all cursor-pointer">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity modal-close"></div>

            <div
                class="relative w-full max-w-md transform rounded-3xl bg-secondary border border-white/10 p-8 text-left shadow-2xl transition-all">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none rounded-3xl">
                </div>

                <div class="relative z-10 text-center">
                    <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-white tracking-tight mb-2">{{ __('Confirm Deletion') }}</h3>
                    <p class="text-sm text-slate-400 mb-8 px-4">
                        {{ __('Are you sure you want to delete this investment? This action cannot be undone.') }}
                    </p>

                    <div class="flex gap-3">
                        <input type="hidden" id="delete-investment-id">
                        <button type="button"
                            class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all modal-close cursor-pointer">
                            {{ __('Cancel') }}
                        </button>
                        <button type="button" id="confirm-delete"
                            class="flex-1 px-4 py-3 rounded-xl bg-red-500 text-white font-bold hover:bg-red-600 shadow-lg shadow-red-500/20 transition-all cursor-pointer">
                            {{ __('Delete Forever') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Export Modal --}}
    <div id="export-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity modal-close cursor-pointer">
            </div>

            <div
                class="relative w-full max-w-md transform rounded-3xl bg-secondary border border-white/10 p-8 text-left shadow-2xl transition-all">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none rounded-3xl">
                </div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Export Settings') }}</h3>
                            <p class="text-xs text-slate-500 mt-1 uppercase tracking-widest font-mono">
                                {{ __('Select columns to include') }}</p>
                        </div>
                        <button type="button"
                            class="text-slate-500 hover:text-white transition-colors modal-close cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 font-mono ml-1">
                                {{ __('Select Columns') }}
                            </p>
                            <div class="grid grid-cols-2 gap-3" id="export-columns-container">
                                @php
                                    $columns = [
                                        'username' => ['label' => 'User', 'default' => true],
                                        'plan_name' => ['label' => 'Plan', 'default' => true],
                                        'investment_goal' => ['label' => 'Goal', 'default' => true],
                                        'risk_profile' => ['label' => 'Risk', 'default' => true],
                                        'interests' => ['label' => 'Interests', 'default' => true],
                                        'capital_invested' => ['label' => 'Invested', 'default' => true],
                                        'compounding_capital' => ['label' => 'Compounding', 'default' => true],
                                        'roi_earned' => ['label' => 'ROI Earned', 'default' => true],
                                        'cycle_count' => ['label' => 'Cycles', 'default' => true],
                                        'status' => ['label' => 'Status', 'default' => true],
                                        'next_roi_at' => ['label' => 'Next ROI', 'default' => false],
                                        'expires_at' => ['label' => 'Expires', 'default' => false],
                                        'created_at' => ['label' => 'Date', 'default' => true],
                                    ];
                                @endphp
                                @foreach ($columns as $key => $col)
                                    <label
                                        class="flex items-center gap-3 p-3 rounded-xl border border-white/5 bg-white/5 hover:border-accent-primary/50 transition-all cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="export_cols[]" value="{{ $key }}"
                                                {{ $col['default'] ? 'checked' : '' }}
                                                class="w-5 h-5 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary focus:ring-offset-0 cursor-pointer">
                                        </div>
                                        <span
                                            class="text-[11px] text-slate-400 group-hover:text-white transition-colors">{{ __($col['label']) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/5 flex gap-3">
                            <input type="hidden" id="pending-export-type" value="">
                            <button type="button"
                                class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all modal-close cursor-pointer">
                                {{ __('Cancel') }}
                            </button>
                            <button type="button" id="confirm-export"
                                class="flex-1 px-4 py-3 rounded-xl bg-accent-primary text-white font-bold hover:bg-accent-primary/90 shadow-lg shadow-accent-primary/20 transition-all cursor-pointer">
                                {{ __('Generate Export') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            // ── Data from PHP ────────────────────────────────────────────────────────
            const GRAPH_DATA = @json($graph_data);
            const PLAN_CHART_DATA = @json($plan_chart_data);

            // ── Shared Chart.js defaults ─────────────────────────────────────────────
            const CURRENCY = "{{ getSetting('currency') }}";
            Chart.defaults.color = 'rgba(148,163,184,0.7)';
            Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
            Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui';
            Chart.defaults.font.size = 10;

            const tooltipOptions = {
                backgroundColor: 'rgba(15,23,42,0.95)',
                borderColor: 'rgba(255,255,255,0.08)',
                borderWidth: 1,
                padding: 10,
                titleFont: {
                    size: 10,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 10
                },
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: CURRENCY
                            }).format(context.parsed.y);
                        }
                        return label;
                    }
                }
            };

            // ── Investment Trend Chart ───────────────────────────────────────────────
            const trendCtx = document.getElementById('investmentTrendChart').getContext('2d');
            const trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: []
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: tooltipOptions,
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255,255,255,0.03)'
                            },
                            ticks: {
                                maxTicksLimit: 8,
                                maxRotation: 0
                            },
                        },
                        y: {
                            grid: {
                                color: 'rgba(255,255,255,0.04)'
                            },
                            ticks: {
                                maxTicksLimit: 5
                            },
                            beginAtZero: true,
                        },
                    },
                },
            });

            // ── Plan Distribution Chart ──────────────────────────────────────────────
            const planCtx = document.getElementById('planDistributionChart').getContext('2d');
            const planChart = new Chart(planCtx, {
                type: 'bar',
                data: {
                    labels: PLAN_CHART_DATA.map(d => d.name),
                    datasets: [{
                            label: 'Invested',
                            data: PLAN_CHART_DATA.map(d => d.invested),
                            backgroundColor: 'rgba(99, 102, 241, 0.6)',
                            borderColor: '#6366f1',
                            borderWidth: 1,
                            borderRadius: 4,
                        },
                        {
                            label: 'ROI Earned',
                            data: PLAN_CHART_DATA.map(d => d.roi),
                            backgroundColor: 'rgba(52, 211, 153, 0.6)',
                            borderColor: '#34d399',
                            borderWidth: 1,
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                font: {
                                    size: 9
                                }
                            }
                        },
                        tooltip: {
                            ...tooltipOptions,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    const val = context.raw;
                                    label += new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: CURRENCY
                                    }).format(val);
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255,255,255,0.03)'
                            },
                            stacked: false
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            stacked: false
                        }
                    }
                }
            });

            // ── Trend Logic ─────────────────────────────────────────────────────────
            let activePeriod = '7d';
            const SERIES_META = {
                active: {
                    label: 'Active',
                    color: '#34d399'
                },
                suspended: {
                    label: 'Suspended',
                    color: '#f43f5e'
                },
                completed: {
                    label: 'Completed',
                    color: '#6366f1'
                }
            };

            function updateTrendChart() {
                const periodData = GRAPH_DATA[activePeriod];
                const labels = periodData.active.labels;

                trendChart.data.labels = labels;
                trendChart.data.datasets = Object.entries(SERIES_META).map(([key, meta]) => ({
                    label: meta.label,
                    data: periodData[key].data,
                    borderColor: meta.color,
                    backgroundColor: meta.color + '18',
                    fill: true,
                    tension: 0.4,
                    pointRadius: labels.length <= 15 ? 3 : 0,
                    pointHoverRadius: 5,
                    borderWidth: 2,
                }));

                trendChart.update('active');

                // Render Legend
                const legendEl = document.getElementById('graph-legend');
                legendEl.innerHTML = '';
                Object.values(SERIES_META).forEach(meta => {
                    legendEl.insertAdjacentHTML('beforeend', `
                        <span class="flex items-center gap-1.5 text-[10px] text-slate-400">
                            <span style="width:8px;height:8px;border-radius:50%;background:${meta.color};display:inline-block;"></span>
                            ${meta.label}
                        </span>
                    `);
                });
            }

            $('.graph-period-btn').on('click', function() {
                activePeriod = $(this).data('period');
                $('.graph-period-btn').removeClass('bg-white/10 text-white').addClass('text-slate-500');
                $(this).removeClass('text-slate-500').addClass('bg-white/10 text-white');
                updateTrendChart();
            });

            // Init
            updateTrendChart();

            // AJAX Pagination
            $(document).on('click', '.ajax-pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                loadTable(url);
            });

            // AJAX Filtering
            $(document).on('submit', '#filter-form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action') + '?' + $(this).serialize();
                loadTable(url);
            });

            function loadTable(url) {
                $('#loading-spinner').removeClass('hidden').addClass('flex');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#investments-wrapper').html($(data).find('#investments-wrapper').html());
                        window.history.pushState({}, '', url);
                    },
                    error: function() {
                        toastNotification('Error loading content.', 'error');
                    },
                    complete: function() {
                        $('#loading-spinner').addClass('hidden').removeClass('flex');
                    }
                });
            }

            // Dropdown Toggle
            $(document).on('click', '.dropdown-btn', function(e) {
                e.stopPropagation();
                const menu = $(this).siblings('.dropdown-menu');
                $('.dropdown-menu').not(menu).addClass('hidden');
                menu.toggleClass('hidden');
                $(this).find('.dropdown-icon').toggleClass('rotate-180');
            });

            // Handle Dropdown Options
            $(document).on('click', '.dropdown-option', function() {
                const val = $(this).data('value');
                const label = $(this).text();
                const dropdown = $(this).closest('.custom-dropdown');

                dropdown.find('input[type="hidden"]').val(val);
                dropdown.find('.selected-label').text(label);
                dropdown.find('.dropdown-menu').addClass('hidden');
                dropdown.find('.dropdown-icon').removeClass('rotate-180');

                $('#filter-form').submit();
            });

            // Close dropdowns on outside click
            $(document).on('click', function() {
                $('.dropdown-menu').addClass('hidden');
                $('.dropdown-icon').removeClass('rotate-180');
            });

            // Close modal functions
            function closeModal(modalId) {
                $(`#${modalId}`).addClass('hidden');
                document.body.style.overflow = '';
            }

            function openModal(modalId) {
                $(`#${modalId}`).removeClass('hidden');
                document.body.style.overflow = 'hidden';
            }

            $(document).on('click', '.modal-close', function(e) {
                if (e.target === this || $(this).hasClass('modal-close')) {
                    const modal = $(this).closest('.fixed.inset-0');
                    if (modal.length) {
                        closeModal(modal.attr('id'));
                    }
                }
            });

            // Status Update Logic
            $(document).on('click', '.edit-investment-btn', function() {
                const id = $(this).data('id');
                const status = $(this).data('status');

                $('#edit-investment-id').val(id);
                $('#edit-status-value').val(status);
                $('#edit-status-dropdown .selected-label').text(status.charAt(0).toUpperCase() + status
                    .slice(1));

                openModal('edit-modal');
            });

            $('#edit-status-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#edit-investment-id').val();
                const status = $('#edit-status-value').val();
                const url = "{{ route('admin.investments.update', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(resp) {
                        if (resp.success || resp.status === 'success') {
                            closeModal('edit-modal');
                            toastNotification(resp.message, 'success');
                            loadTable(window.location.href);
                        }
                    },
                    error: function(xhr) {
                        toastNotification(xhr.responseJSON?.message || 'Error updating status',
                            'error');
                    }
                });
            });

            // Delete Logic
            $(document).on('click', '.delete-investment-btn', function() {
                const id = $(this).data('id');
                $('#delete-investment-id').val(id);
                openModal('delete-modal');
            });

            $('#confirm-delete').on('click', function() {
                const id = $('#delete-investment-id').val();
                const url = "{{ route('admin.investments.delete', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(resp) {
                        if (resp.success || resp.status === 'success') {
                            closeModal('delete-modal');
                            toastNotification(resp.message, 'success');
                            loadTable(window.location.href);
                        }
                    },
                    error: function(xhr) {
                        toastNotification(xhr.responseJSON?.message ||
                            'Error deleting investment', 'error');
                    }
                });
            });

            // Status Dropdown in Edit Modal
            $('#edit-status-dropdown .dropdown-option').on('click', function() {
                const val = $(this).data('value');
                const label = $(this).text().trim();

                $('#edit-status-value').val(val);
                $('#edit-status-dropdown .selected-label').text(label);
                $('#edit-status-dropdown .dropdown-menu').addClass('hidden');
                $('#edit-status-dropdown .dropdown-icon').removeClass('rotate-180');
            });

            // Export Modal Logic
            $(document).on('click', '#export-dropdown .dropdown-option', function() {
                const type = $(this).data('export');
                $('#pending-export-type').val(type);
                openModal('export-modal');
            });

            $(document).on('click', '#confirm-export', function() {
                const type = $('#pending-export-type').val();
                const columns = [];
                $('input[name="export_cols[]"]:checked').each(function() {
                    columns.push($(this).val());
                });

                if (columns.length === 0) {
                    toastNotification('Please select at least one column.', 'error');
                    return;
                }

                const form = $('#filter-form');
                const baseUrl = form.attr('action');
                const params = form.serialize();

                const exportUrl = `${baseUrl}?${params}&export=${type}&columns=${columns.join(',')}`;
                window.location.href = exportUrl;

                closeModal('export-modal');
                toastNotification('Export generated successfully.', 'success');
            });
        });
    </script>
@endpush
