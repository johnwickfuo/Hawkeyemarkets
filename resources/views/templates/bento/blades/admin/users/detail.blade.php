@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow {
            border-color: rgba(255, 255, 255, 0.1) !important;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 12px 12px 0 0;
            padding: 12px !important;
        }

        .ql-container.ql-snow {
            border-color: rgba(255, 255, 255, 0.1) !important;
            border-radius: 0 0 12px 12px;
            font-family: inherit;
            font-size: 16px;
            min-height: 300px;
        }

        #email-editor {
            min-height: 300px;
            background: rgba(255, 255, 255, 0.02);
        }

        .ql-editor {
            min-height: 300px;
            color: white;
            padding: 20px !important;
        }

        .ql-editor.ql-blank::before {
            color: rgba(255, 255, 255, 0.2) !important;
            font-style: normal !important;
            left: 20px !important;
        }

        .ql-snow .ql-stroke {
            stroke: #94a3b8 !important;
        }

        .ql-snow .ql-fill {
            fill: #94a3b8 !important;
        }

        .ql-snow .ql-picker {
            color: #94a3b8 !important;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-8 pb-10">
        <div id="user-detail-content" class="space-y-8">
            {{-- Header & Progress Bar --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('admin.users.index') }}"
                            class="p-2 rounded-xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-all group">
                            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                                </path>
                            </svg>
                        </a>
                        <h2 class="text-3xl font-black text-white tracking-tight">
                            {{ __('User Profile') }}
                        </h2>
                    </div>
                    <p class="text-text-secondary text-sm flex items-center gap-2">
                        {{ __('Managing details for') }} <span
                            class="text-white font-mono font-bold">{{ $user->username }}</span>
                        <span class="w-1 h-1 rounded-full bg-white/20"></span>
                        <span class="text-accent-primary font-bold">UID: {{ $user->id }}</span>
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex flex-col items-end">
                        <span
                            class="text-[10px] text-text-secondary uppercase tracking-widest font-bold mb-1">{{ __('Account Status') }}</span>
                        @if ($user->status == 'active')
                            <span
                                class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ __('Active') }}
                            </span>
                        @else
                            <span
                                class="px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                {{ __('Banned') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Main Layout Container --}}
            <div class="space-y-6">
                {{-- 1. HERO SECTION: Identity & Net Worth --}}
                <div class="grid grid-cols-12 gap-6">
                    {{-- Dominant Identity & Total Equity Card --}}
                    <div class="col-span-12 lg:col-span-8 relative group">
                        <div class="relative h-full bg-secondary border border-white/5 rounded-3xl p-8 overflow-hidden">
                            <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-20">
                            </div>
                            <div
                                class="absolute top-0 right-0 -mt-24 -mr-24 w-64 h-64 bg-accent-primary/10 rounded-full blur-3xl z-0">
                            </div>

                            <div class="relative z-10 flex flex-col h-full justify-between">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-10">
                                    {{-- Identity Fused --}}
                                    <div class="flex items-center gap-6">
                                        <div class="relative">
                                            @if ($user->photo)
                                                <div
                                                    class="w-24 h-24 rounded-3xl border border-white/10 shadow-2xl overflow-hidden aspect-square shrink-0">
                                                    <img src="{{ asset('storage/profile/' . $user->photo) }}"
                                                        alt="{{ $user->name ?? $user->username }}"
                                                        class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div
                                                    class="w-24 h-24 rounded-3xl bg-gradient-to-br from-indigo-500/20 to-violet-500/20 border border-white/10 flex items-center justify-center shadow-2xl shrink-0">
                                                    <span class="text-4xl font-black text-white">
                                                        {{ substr($user->name ?? $user->username, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div
                                                class="absolute -bottom-1 -right-1 w-8 h-8 rounded-xl bg-primary border border-white/10 flex items-center justify-center text-accent-primary shadow-lg">
                                                @if ($user->kv == 1)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                        </path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-text-secondary" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-3xl font-black text-white tracking-tight leading-none mb-2">
                                                {{ $user->fullname ?? $user->username }}</h3>
                                            <p
                                                class="text-text-secondary text-sm font-mono tracking-widest uppercase opacity-70">
                                                UID: {{ $user->id }} • {{ $user->email }}</p>
                                            <div class="flex gap-2 mt-3">
                                                <span
                                                    class="px-2.5 py-1 rounded-lg {{ $user->status == 'active' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20' }} border text-[10px] font-black uppercase tracking-widest">
                                                    {{ $user->status == 'active' ? 'Active' : 'Banned' }}
                                                </span>
                                                @if ($user->ev)
                                                    <span
                                                        class="px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-400 border-blue-500/20 border text-[10px] font-black uppercase tracking-widest">Email
                                                        Verified</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Net Worth Display --}}
                                    <div class="text-left md:text-right flex flex-col items-start md:items-end">
                                        <div
                                            class="px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[10px] font-black uppercase tracking-widest mb-3">
                                            {{ __('Total Financial Standing') }}
                                        </div>
                                        <h3
                                            class="text-5xl md:text-7xl font-black text-white tracking-tighter drop-shadow-2xl">
                                            <span
                                                class="text-transparent bg-clip-text bg-gradient-to-br from-white via-white to-gray-500">
                                                {{ showAmount($total_equity) }}
                                            </span>
                                        </h3>
                                    </div>
                                </div>

                                {{-- Hero Sub-stats --}}
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 pt-8 border-t border-white/5">
                                    <div>
                                        <div
                                            class="text-text-secondary text-[10px] font-bold uppercase tracking-widest mb-1.5 opacity-50">
                                            {{ __('Main Wallet') }}</div>
                                        <div class="text-2xl font-black text-white tracking-wide">
                                            {{ showAmount($balance) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div
                                            class="text-text-secondary text-[10px] font-bold uppercase tracking-widest mb-1.5 opacity-50">
                                            {{ __('Equity Borrowed') }}</div>
                                        <div class="text-2xl font-black text-amber-400 tracking-wide">
                                            {{ showAmount($borrowed) }}</div>
                                    </div>
                                    <div class="hidden md:block">
                                        <div
                                            class="text-text-secondary text-[10px] font-bold uppercase tracking-widest mb-1.5 opacity-50">
                                            {{ __('Partner Referrals') }}</div>
                                        <div class="text-2xl font-black text-white tracking-wide">
                                            {{ $user->referrals->count() }} <span
                                                class="text-xs text-text-secondary font-medium">Clients</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Open Exposure & Risk Card --}}
                    <div class="col-span-12 lg:col-span-4 flex flex-col gap-6">
                        <div
                            class="flex-1 bg-secondary border border-white/5 rounded-3xl p-8 relative overflow-hidden group hover:border-white/10 transition-all">
                            <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10">
                            </div>
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-{{ $open_pnl >= 0 ? 'emerald' : 'rose' }}-500/10 blur-3xl rounded-full -mr-12 -mt-12">
                            </div>

                            <div class="relative z-10 h-full flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-center mb-6">
                                        <h4
                                            class="text-text-secondary text-xs font-bold uppercase tracking-widest opacity-60">
                                            {{ __('Live PnL Tracking') }}</h4>
                                        <span
                                            class="px-2 py-0.5 rounded {{ $open_pnl >= 0 ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20' }} border text-[9px] font-black uppercase tracking-widest">
                                            {{ $open_pnl >= 0 ? 'Surplus' : 'Deficit' }}
                                        </span>
                                    </div>
                                    <div
                                        class="text-5xl font-black {{ $open_pnl >= 0 ? 'text-emerald-400' : 'text-rose-400' }} tracking-tighter mb-2">
                                        {{ $open_pnl >= 0 ? '+' : '' }}{{ showAmount($open_pnl) }}
                                    </div>
                                    <div
                                        class="text-[10px] text-text-secondary font-bold uppercase tracking-widest opacity-50">
                                        {{ __('Current Market Exposure') }}</div>
                                </div>

                                <div class="space-y-4 mt-8">
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <div class="text-white font-black text-2xl leading-none mb-1">
                                                {{ $open_positions_count }}</div>
                                            <div
                                                class="text-[9px] text-text-secondary font-bold uppercase tracking-widest opacity-50 text-left">
                                                {{ __('Open Positions') }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-white font-black text-2xl leading-none mb-1">
                                                {{ number_format($trades_count) }}</div>
                                            <div
                                                class="text-[9px] text-text-secondary font-bold uppercase tracking-widest opacity-50 text-right">
                                                {{ __('Total Trade Volume') }}</div>
                                        </div>
                                    </div>
                                    <div class="pt-4 border-t border-white/5 flex justify-between items-center">
                                        <span
                                            class="text-[10px] text-text-secondary font-bold uppercase tracking-widest opacity-50">{{ __('Active Collateral') }}</span>
                                        <span class="text-white font-black text-sm">{{ showAmount($margin_used) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. DIVERSIFIED BALANCE GRID --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                    {{-- Futures Balance Card --}}
                    @php $futuresEnabled = moduleEnabled('futures_module'); @endphp
                    <div
                        class="bg-secondary border border-white/5 rounded-2xl p-5 hover:border-white/10 transition-all group relative overflow-hidden">
                        @if (!$futuresEnabled)
                            <div
                                class="absolute inset-0 z-10 flex items-center justify-center bg-black/20 backdrop-blur-[1px]">
                                <span
                                    class="bg-red-500/20 text-red-500 text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg border border-red-500/20 shadow-lg">
                                    {{ __('Disabled') }}
                                </span>
                            </div>
                        @endif

                        <div class="{{ !$futuresEnabled ? 'opacity-40 grayscale pointer-events-none' : '' }}">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500/5 blur-xl rounded-full -mr-8 -mt-8">
                            </div>
                            <div
                                class="text-[9px] text-text-secondary font-bold uppercase tracking-widest mb-3 opacity-60">
                                {{ __('Futures Balance') }}</div>
                            <div class="text-xl font-black text-white mb-1">{{ showAmount($futures_balance) }}</div>
                            <div class="flex items-center gap-1.5 mt-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                <span
                                    class="text-[8px] text-text-secondary font-bold uppercase tracking-tighter opacity-70">{{ __('Trading Account') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Margin Balance Card --}}
                    @php $marginEnabled = moduleEnabled('margin_module'); @endphp
                    <div
                        class="bg-secondary border border-white/5 rounded-2xl p-5 hover:border-white/10 transition-all group relative overflow-hidden">
                        @if (!$marginEnabled)
                            <div
                                class="absolute inset-0 z-10 flex items-center justify-center bg-black/20 backdrop-blur-[1px]">
                                <span
                                    class="bg-red-500/20 text-red-500 text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg border border-red-500/20 shadow-lg">
                                    {{ __('Disabled') }}
                                </span>
                            </div>
                        @endif

                        <div class="{{ !$marginEnabled ? 'opacity-40 grayscale pointer-events-none' : '' }}">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-amber-500/5 blur-xl rounded-full -mr-8 -mt-8">
                            </div>
                            <div
                                class="text-[9px] text-text-secondary font-bold uppercase tracking-widest mb-3 opacity-60">
                                {{ __('Margin Balance') }}</div>
                            <div class="text-xl font-black text-white mb-1">{{ showAmount($margin_balance) }}</div>
                            <div class="flex items-center gap-1.5 mt-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                                <span
                                    class="text-[8px] text-text-secondary font-bold uppercase tracking-tighter opacity-70">{{ __('Leveraged Account') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Stock Balance Card --}}
                    @php $stockEnabled = moduleEnabled('stock_module'); @endphp
                    <div
                        class="bg-secondary border border-white/5 rounded-2xl p-5 hover:border-white/10 transition-all group relative overflow-hidden">
                        @if (!$stockEnabled)
                            <div
                                class="absolute inset-0 z-10 flex items-center justify-center bg-black/20 backdrop-blur-[1px]">
                                <span
                                    class="bg-red-500/20 text-red-500 text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg border border-red-500/20 shadow-lg">
                                    {{ __('Disabled') }}
                                </span>
                            </div>
                        @endif

                        <div class="{{ !$stockEnabled ? 'opacity-40 grayscale pointer-events-none' : '' }}">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-indigo-500/5 blur-xl rounded-full -mr-8 -mt-8">
                            </div>
                            <div
                                class="text-[9px] text-text-secondary font-bold uppercase tracking-widest mb-3 opacity-60">
                                {{ __('Stock Holdings') }}</div>
                            <div class="text-xl font-black text-white mb-1">{{ showAmount($stock_balance) }}</div>
                            <div class="flex items-center gap-1.5 mt-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                <span
                                    class="text-[8px] text-text-secondary font-bold uppercase tracking-tighter opacity-70">{{ __('Equity Portfolio') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Forex Live Card --}}
                    @php $forexEnabled = moduleEnabled('forex_module'); @endphp
                    <div
                        class="bg-secondary border border-white/5 rounded-2xl p-5 hover:border-white/10 transition-all group relative overflow-hidden">
                        @if (!$forexEnabled)
                            <div
                                class="absolute inset-0 z-10 flex items-center justify-center bg-black/20 backdrop-blur-[1px]">
                                <span
                                    class="bg-red-500/20 text-red-500 text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg border border-red-500/20 shadow-lg">
                                    {{ __('Disabled') }}
                                </span>
                            </div>
                        @endif

                        <div class="{{ !$forexEnabled ? 'opacity-40 grayscale pointer-events-none' : '' }}">
                            <div
                                class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/5 blur-xl rounded-full -mr-8 -mt-8">
                            </div>
                            <div
                                class="text-[9px] text-text-secondary font-bold uppercase tracking-widest mb-3 opacity-60">
                                {{ __('Forex (Live)') }}</div>
                            <div class="text-xl font-black text-white mb-1">{{ showAmount($forex_live_balance) }}</div>
                            <div class="flex items-center gap-1.5 mt-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span
                                    class="text-[8px] text-text-secondary font-bold uppercase tracking-tighter opacity-70">{{ __('Live Market') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- ETF Card --}}
                    @php $etfEnabled = moduleEnabled('etf_module'); @endphp
                    <div
                        class="bg-secondary border border-white/5 rounded-2xl p-5 hover:border-white/10 transition-all group relative overflow-hidden">
                        @if (!$etfEnabled)
                            <div
                                class="absolute inset-0 z-10 flex items-center justify-center bg-black/20 backdrop-blur-[1px]">
                                <span
                                    class="bg-red-500/20 text-red-500 text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg border border-red-500/20 shadow-lg">
                                    {{ __('Disabled') }}
                                </span>
                            </div>
                        @endif

                        <div class="{{ !$etfEnabled ? 'opacity-40 grayscale pointer-events-none' : '' }}">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-purple-500/5 blur-xl rounded-full -mr-8 -mt-8">
                            </div>
                            <div
                                class="text-[9px] text-text-secondary font-bold uppercase tracking-widest mb-3 opacity-60">
                                {{ __('ETF Portfolio') }}</div>
                            <div class="text-xl font-black text-white mb-1">{{ showAmount($etf_balance) }}</div>
                            <div class="flex items-center gap-1.5 mt-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div>
                                <span
                                    class="text-[8px] text-text-secondary font-bold uppercase tracking-tighter opacity-70">{{ __('Index Assets') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Bonds Card --}}
                    @php $bondsEnabled = moduleEnabled('bonds_module'); @endphp
                    <div
                        class="bg-secondary border border-white/5 rounded-2xl p-5 hover:border-white/10 transition-all group relative overflow-hidden">
                        @if (!$bondsEnabled)
                            <div
                                class="absolute inset-0 z-10 flex items-center justify-center bg-black/20 backdrop-blur-[1px]">
                                <span
                                    class="bg-red-500/20 text-red-500 text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg border border-red-500/20 shadow-lg">
                                    {{ __('Disabled') }}
                                </span>
                            </div>
                        @endif

                        <div class="{{ !$bondsEnabled ? 'opacity-40 grayscale pointer-events-none' : '' }}">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-slate-500/5 blur-xl rounded-full -mr-8 -mt-8">
                            </div>
                            <div
                                class="text-[9px] text-text-secondary font-bold uppercase tracking-widest mb-3 opacity-60">
                                {{ __('Bonds / Assets') }}</div>
                            <div class="text-xl font-black text-white mb-1">{{ showAmount($bond_balance) }}</div>
                            <div class="flex items-center gap-1.5 mt-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-slate-500"></div>
                                <span
                                    class="text-[8px] text-text-secondary font-bold uppercase tracking-tighter opacity-70">{{ __('Fixed Income') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. QUICK MANAGEMENT ACTIONS (Standalone Section) --}}
                <div class="bg-secondary/30 border border-white/5 rounded-3xl p-4">
                    @php
                        $quick_actions = [
                            [
                                'name' => 'Ban User',
                                'type' => 'modal',
                                'action' => 'ban',
                                'show' => $user->status == 'active',
                                'is_status_action' => true,
                                'color' =>
                                    'bg-red-500/10 text-red-500 border-red-500/20 hover:bg-red-500 hover:text-white',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>',
                            ],
                            [
                                'name' => 'Unban User',
                                'type' => 'modal',
                                'action' => 'unban',
                                'show' => $user->status != 'active',
                                'is_status_action' => true,
                                'color' =>
                                    'bg-emerald-500/10 text-emerald-500 border-emerald-500/20 hover:bg-emerald-500 hover:text-white',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                            ],
                            [
                                'name' => 'Delete User',
                                'type' => 'modal',
                                'action' => 'delete',
                                'show' => true,
                                'color' => 'bg-white/5 text-white border-white/10 hover:bg-white/10',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>',
                            ],
                            [
                                'name' => 'Credit/Debit',
                                'type' => 'modal',
                                'modal_id' => 'credit_debit_user_modal',
                                'show' => true,
                                'color' => 'bg-blue-500/10 text-blue-400 border-blue-500/20 hover:bg-blue-500/20',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                            ],
                            [
                                'name' => 'Login As User',
                                'type' => 'modal',
                                'action' => 'login_as',
                                'show' => true,
                                'color' =>
                                    'bg-indigo-500/10 text-indigo-400 border-indigo-500/20 hover:bg-indigo-500/20',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>',
                            ],
                            [
                                'name' => 'Send Email',
                                'type' => 'modal',
                                'modal_id' => 'send_email_modal',
                                'show' => true,
                                'color' => 'bg-amber-500/10 text-amber-400 border-amber-500/20 hover:bg-amber-500/20',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
                            ],
                            [
                                'name' => 'Investments',
                                'type' => 'link',
                                'module' => 'investment_module',
                                'route_name' => 'admin.investments.index',
                                'params' => ['user_id' => $user->id],
                                'show' => true,
                                'color' =>
                                    'bg-purple-500/10 text-purple-400 border-purple-500/20 hover:bg-purple-500/20',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>',
                            ],
                            [
                                'name' => 'Withdrawals',
                                'type' => 'link',
                                'route_name' => 'admin.withdrawals.index',
                                'params' => ['user_id' => $user->id],
                                'show' => true,
                                'color' => 'bg-rose-500/10 text-rose-400 border-rose-500/20 hover:bg-rose-500/20',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                            ],
                            [
                                'name' => 'Deposits',
                                'type' => 'link',
                                'route_name' => 'admin.deposits.index',
                                'params' => ['user_id' => $user->id],
                                'show' => true,
                                'color' =>
                                    'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 hover:bg-emerald-500/20',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>',
                            ],
                            [
                                'name' => 'KYC Doc',
                                'type' => 'link',
                                'module' => 'kyc_module',
                                'route_name' => 'admin.kyc.index',
                                'params' => ['user_id' => $user->id],
                                'show' => true,
                                'color' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20 hover:bg-cyan-500/20',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                            ],
                            [
                                'name' => 'Trading',
                                'type' => 'modal',
                                'modal_id' => 'trading_modules_modal',
                                'is_trading' => true,
                                'show' => true,
                                'color' =>
                                    'bg-violet-500/10 text-violet-400 border-violet-500/20 hover:bg-violet-500/20',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
                            ],
                            [
                                'name' => 'Referrals',
                                'type' => 'link',
                                'route_name' => 'admin.referrals.index',
                                'params' => ['user_id' => $user->id],
                                'show' => true,
                                'color' => 'bg-pink-500/10 text-pink-400 border-pink-500/20 hover:bg-pink-500/20',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
                            ],
                            [
                                'name' => 'Transactions',
                                'type' => 'link',
                                'route_name' => 'admin.transactions.index',
                                'params' => ['user_id' => $user->id],
                                'show' => true,
                                'color' =>
                                    'bg-orange-500/10 text-orange-400 border-orange-500/20 hover:bg-orange-500/20',
                                'icon' =>
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>',
                            ],
                        ];
                    @endphp
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        @foreach ($quick_actions as $action)
                            @php
                                $isStatusAction = $action['is_status_action'] ?? false;
                                $isEnabled = true;
                                if (isset($action['module'])) {
                                    $isEnabled = moduleEnabled($action['module']);
                                } elseif (isset($action['is_trading'])) {
                                    $isEnabled = true;
                                }
                            @endphp

                            @if ($isStatusAction)
                                @if ($action['show'])
                                    <button type="button"
                                        class="group flex items-center justify-center gap-3 px-4 py-3 {{ $action['color'] }} rounded-2xl transition-all font-bold text-[10px] uppercase tracking-widest cursor-pointer open-confirm-modal"
                                        data-id="{{ $user->id }}" data-action="{{ $action['action'] }}"
                                        data-name="{{ $user->username }}">
                                        {!! $action['icon'] !!}
                                        {{ __($action['name']) }}
                                    </button>
                                @endif
                            @else
                                <div class="relative group/action">
                                    @if (!$isEnabled)
                                        <div
                                            class="absolute inset-0 z-10 flex items-center justify-center bg-black/5 rounded-2xl backdrop-blur-[2px] border border-white/5">
                                            <span
                                                class="bg-red-500/20 text-white text-[7px] font-black uppercase tracking-tighter px-2 py-1 rounded shadow-lg whitespace-nowrap">
                                                {{ __($action['name'] . ' Module Disabled') }}
                                            </span>
                                        </div>
                                    @endif

                                    @if ($action['type'] == 'modal')
                                        <button type="button"
                                            class="w-full group flex items-center justify-center gap-3 px-4 py-3 {{ $action['color'] }} rounded-2xl transition-all font-bold text-[10px] uppercase tracking-widest {{ $isEnabled ? 'cursor-pointer' : 'cursor-not-allowed grayscale opacity-40' }} {{ isset($action['action']) ? 'open-confirm-modal' : '' }} {{ isset($action['modal_id']) ? 'trigger-modal' : '' }}"
                                            @if ($isEnabled && isset($action['modal_id'])) data-target="#{{ $action['modal_id'] }}" @endif
                                            @if ($isEnabled && isset($action['action'])) data-id="{{ $user->id }}" data-action="{{ $action['action'] }}" data-name="{{ $user->username }}" @endif>
                                            {!! $action['icon'] !!}
                                            {{ __($action['name']) }}
                                        </button>
                                    @else
                                        @php
                                            $has_route =
                                                isset($action['route_name']) && Route::has($action['route_name']);
                                            $url =
                                                $isEnabled && $has_route
                                                    ? route($action['route_name'], $action['params'] ?? [])
                                                    : 'javascript:void(0)';
                                        @endphp
                                        <a href="{{ $url }}"
                                            class="group flex items-center justify-center gap-3 px-4 py-3 {{ $action['color'] }} rounded-2xl transition-all font-bold text-[10px] uppercase tracking-widest {{ !$isEnabled || !$has_route ? 'opacity-40 grayscale cursor-not-allowed' : '' }}"
                                            @if (!$has_route && $isEnabled) title="{{ __('Route not defined') }}" @endif>
                                            {!! $action['icon'] !!}
                                            {{ __($action['name']) }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- 4. MONTHLY CASHFLOW --}}
                <div
                    class="bg-secondary border border-white/5 rounded-3xl p-6 lg:flex items-center justify-between gap-8 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10"></div>
                    <div
                        class="absolute top-0 right-0 -mt-8 -mr-8 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none z-0">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 -mb-8 -ml-8 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl pointer-events-none z-0">
                    </div>

                    <div class="mb-5 lg:mb-0 relative z-10 text-center lg:text-left">
                        <h4 class="text-text-secondary text-[10px] font-bold uppercase tracking-widest mb-1 opacity-60">
                            {{ now()->format('F') }} {{ __('Activity') }}</h4>
                        <div class="text-white font-black text-2xl tracking-tight leading-tight">
                            {{ __('Cash Movement') }}</div>
                    </div>

                    <div class="flex-1 grid grid-cols-2 gap-4 md:gap-8 relative z-10">
                        <div
                            class="absolute left-1/2 top-1 bottom-1 w-px bg-gradient-to-b from-transparent via-white/10 to-transparent">
                        </div>

                        <div class="text-right pr-4 md:pr-12">
                            <div class="text-emerald-400 font-mono text-xl md:text-3xl font-black tracking-tighter">
                                +{{ showAmount($deposits_month) }}</div>
                            <div
                                class="text-[9px] text-text-secondary font-bold uppercase mt-1 flex items-center justify-end gap-1.5 tracking-widest opacity-60">
                                {{ __('Approved') }}
                                <div class="h-4 w-4 rounded-full bg-emerald-500/10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-emerald-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            @if ($deposits_pending > 0)
                                <div
                                    class="text-[8px] font-black text-amber-500 mt-1 uppercase tracking-widest bg-amber-500/10 px-2 py-0.5 rounded inline-block border border-amber-500/20">
                                    {{ $deposits_pending }} {{ __('Pending') }}</div>
                            @endif
                        </div>

                        <div class="text-left pl-4 md:pl-12">
                            <div class="text-rose-400 font-mono text-xl md:text-3xl font-black tracking-tighter">
                                -{{ showAmount($withdrawals_month_amount) }}</div>
                            <div
                                class="text-[9px] text-text-secondary font-bold uppercase mt-1 flex items-center gap-1.5 tracking-widest opacity-60">
                                <div class="h-4 w-4 rounded-full bg-rose-500/10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-rose-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                {{ __('Approved') }}
                            </div>
                            @if ($withdrawals_pending > 0)
                                <div
                                    class="text-[8px] font-black text-amber-500 mt-1 uppercase tracking-widest bg-amber-500/10 px-2 py-0.5 rounded inline-block border border-amber-500/20">
                                    {{ $withdrawals_pending }} {{ __('Pending') }}</div>
                            @endif
                        </div>
                    </div>
                </div> {{-- End Main Layout Container --}}

                {{-- Detailed Info Section --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Personal Information --}}
                    <div class="bg-secondary border border-white/5 rounded-3xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-white/5 bg-white/[0.02] flex items-center justify-between">
                            <h4 class="text-sm font-bold text-white uppercase tracking-wider">
                                {{ __('Personal Information') }}
                            </h4>
                            <svg class="w-4 h-4 text-text-secondary opacity-50" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mb-1">
                                        {{ __('Full Name') }}</p>
                                    <p class="text-sm text-white font-medium">{{ $user->fullname ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mb-1">
                                        {{ __('Username') }}</p>
                                    <p class="text-sm text-white font-mono">{{ $user->username }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mb-1">
                                        {{ __('Email') }}</p>
                                    <p class="text-sm text-white font-medium">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mb-1">
                                        {{ __('Phone') }}</p>
                                    <p class="text-sm text-white font-medium">{{ $user->mobile ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mb-1">
                                        {{ __('Joined') }}</p>
                                    <p class="text-sm text-white font-medium">
                                        {{ $user->created_at->format('M j, Y - H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mb-1">
                                        {{ __('Reference By') }}</p>
                                    <p class="text-sm text-accent-primary font-bold">
                                        @if ($user->referrer)
                                            <a href="{{ route('admin.users.detail', $user->referrer->id) }}"
                                                class="hover:underline">{{ $user->referrer->username }}</a>
                                        @else
                                            <span class="text-text-secondary/50">{{ __('Direct') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Location & Security --}}
                    <div class="bg-secondary border border-white/5 rounded-3xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-white/5 bg-white/[0.02] flex items-center justify-between">
                            <h4 class="text-sm font-bold text-white uppercase tracking-wider">
                                {{ __('Location & Security') }}
                            </h4>
                            <svg class="w-4 h-4 text-text-secondary opacity-50" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="p-6 space-y-4">
                            @php $kyc = $user->kyc->first(); @endphp
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mb-1">
                                        {{ __('Country') }}</p>
                                    <p class="text-sm text-white font-medium">{{ $kyc->country ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mb-1">
                                        {{ __('City') }}</p>
                                    <p class="text-sm text-white font-medium">{{ $kyc->city ?? 'N/A' }}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mb-1">
                                        {{ __('Address') }}</p>
                                    <p class="text-sm text-white font-medium">{{ $kyc->address_line_1 ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mb-1">
                                        {{ __('2FA Status') }}</p>
                                    @if ($user->ts)
                                        <span
                                            class="px-2 py-0.5 rounded-lg bg-emerald-500/10 text-emerald-400 text-[10px] font-bold uppercase">{{ __('Enabled') }}</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded-lg bg-red-500/10 text-red-400 text-[10px] font-bold uppercase">{{ __('Disabled') }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mb-1">
                                        {{ __('Last Login') }}</p>
                                    <p class="text-sm text-white font-medium">
                                        {{ $last_login ? $last_login->diffForHumans() : __('Never') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KYC Information --}}
                @php
                    $kycEnabled = moduleEnabled('kyc_module');
                    $kyc = $user->kyc->first();
                @endphp
                <div class="relative">
                    @if (!$kycEnabled)
                        <div
                            class="absolute inset-0 z-10 flex items-center justify-center bg-black/20 rounded-3xl backdrop-blur-[1px]">
                            <div
                                class="flex flex-col items-center gap-4 px-8 py-6 bg-red-500/10 border border-red-500/20 rounded-2xl shadow-2xl">
                                <div class="flex flex-col items-center gap-2">
                                    <span
                                        class="bg-red-500 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl border border-white/20 whitespace-nowrap shadow-lg">
                                        {{ __('KYC Module is disabled') }}
                                    </span>
                                    <p class="text-[10px] text-red-400 font-bold uppercase tracking-wider opacity-80">
                                        {{ __('This feature is currently inactive in your system') }}
                                    </p>
                                </div>

                                <a href="{{ route('admin.settings.modules.index') }}"
                                    class="group/enable-btn flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-red-500 border border-white/10 hover:border-red-400 rounded-xl transition-all duration-300">
                                    <span
                                        class="text-[10px] font-black text-white uppercase tracking-widest">{{ __('Enable in Settings') }}</span>
                                    <svg class="w-4 h-4 text-white group-hover/enable-btn:translate-x-0.5 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="{{ !$kycEnabled ? 'opacity-40 grayscale pointer-events-none' : '' }}">
                        @if ($kyc)
                            <div class="bg-secondary border border-white/5 rounded-3xl overflow-hidden">
                                <div
                                    class="px-6 py-4 border-b border-white/5 bg-white/[0.02] flex items-center justify-between">
                                    <h4 class="text-sm font-bold text-white uppercase tracking-wider">
                                        {{ __('KYC Documents') }}
                                    </h4>
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase 
                                @if ($kyc->status == 'approved') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                @elseif($kyc->status == 'pending') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                @else bg-red-500/10 text-red-400 border border-red-500/20 @endif">
                                        {{ $kyc->status }}
                                    </span>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                        @php
                                            $docs = [
                                                'document_front' => __('Front View'),
                                                'document_back' => __('Back View'),
                                                'selfie' => __('Selfie'),
                                                'proof_address' => __('Proof of Address'),
                                            ];
                                        @endphp
                                        @foreach ($docs as $field => $label)
                                            @if ($kyc->$field)
                                                <div class="bg-white/[0.02] border border-white/5 rounded-2xl p-4">
                                                    <p
                                                        class="text-[10px] text-text-secondary uppercase font-bold tracking-widest mb-2">
                                                        {{ $label }} ({{ $kyc->document_type }})</p>
                                                    <a href="{{ asset('storage/' . $kyc->$field) }}" target="_blank"
                                                        class="flex items-center gap-2 text-accent-primary hover:text-white transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                        <span class="text-sm font-bold">{{ __('View Document') }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach

                                        {{-- button to view the kyc record --}}
                                        <div class="col-span-full mt-4 pt-6 border-t border-white/5">
                                            <a href="{{ route('admin.kyc.view', $kyc->id) }}"
                                                class="group/kyc-btn relative flex items-center justify-between gap-4 p-4 rounded-2xl bg-accent-primary/5 border border-accent-primary/20 hover:bg-accent-primary hover:border-accent-primary transition-all duration-300 shadow-lg shadow-accent-primary/5">

                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-12 h-12 rounded-xl bg-accent-primary/10 flex items-center justify-center text-accent-primary group-hover/kyc-btn:bg-white/20 group-hover/kyc-btn:text-white transition-colors">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h5
                                                            class="text-sm font-black text-white group-hover/kyc-btn:text-slate-900 transition-colors tracking-tight">
                                                            {{ __('Complete KYC Record Analysis') }}
                                                        </h5>
                                                        <p
                                                            class="text-[10px] text-accent-primary group-hover/kyc-btn:text-slate-800 transition-colors uppercase font-bold tracking-widest mt-0.5 opacity-80">
                                                            {{ __('Review documents, verification history & logs') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 pr-2">
                                                    <span
                                                        class="text-[10px] font-black uppercase text-accent-primary group-hover/kyc-btn:text-slate-900 transition-colors tracking-widest">{{ __('OPEN RECORD') }}</span>
                                                    <svg class="w-5 h-5 text-accent-primary group-hover/kyc-btn:text-slate-900 transition-transform group-hover/kyc-btn:translate-x-1 duration-300"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                    </svg>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-secondary border border-white/5 rounded-3xl overflow-hidden">
                                <div
                                    class="px-6 py-4 border-b border-white/5 bg-white/[0.02] flex items-center justify-between">
                                    <h4 class="text-sm font-bold text-white uppercase tracking-wider">
                                        {{ __('KYC Documents') }}
                                    </h4>
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-red-500/10 text-red-400 border border-red-500/20">
                                        {{ __('Not Submitted') }}
                                    </span>
                                </div>
                                <div class="p-6">
                                    <p class="text-text-secondary text-sm">{{ __('No KYC documents submitted yet.') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div> {{-- End user-detail-content --}}
        </div>

        {{-- Confirmation Modal (Reused from index) --}}
        <div id="confirm-modal" class="fixed inset-0 z-[110] hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm modal-close"></div>
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm p-6">
                <div
                    class="bg-secondary border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                    <div class="p-8 text-center">
                        <div id="confirm-icon-container"
                            class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center"></div>
                        <h3 id="confirm-title" class="text-xl font-bold text-white mb-2"></h3>
                        <p id="confirm-message" class="text-text-secondary text-sm mb-8"></p>
                        <div class="flex gap-3">
                            <button type="button"
                                class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all modal-close cursor-pointer">{{ __('Cancel') }}</button>
                            <button type="button" id="confirm-action-btn"
                                class="flex-1 px-4 py-3 rounded-xl text-white font-bold shadow-lg transition-all cursor-pointer">{{ __('Confirm') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Credit/Debit User Modal --}}
        <div id="credit_debit_user_modal" class="fixed inset-0 z-[110] hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm modal-close"></div>
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-6">
                <div
                    class="bg-secondary border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                    <div class="p-6 border-b border-white/5 bg-white/[0.02] flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            {{ __('Adjust User Balance') }}
                        </h3>
                        <button type="button"
                            class="text-text-secondary hover:text-white transition-colors modal-close cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form id="credit-debit-form" class="p-6 space-y-6">
                        @csrf
                        {{-- Select Type --}}
                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold text-text-secondary uppercase tracking-widest">{{ __('Adjustment Type') }}</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label
                                    class="relative flex items-center justify-center p-4 rounded-xl border border-white/10 bg-white/5 cursor-pointer hover:bg-white/10 transition-all group has-[:checked]:border-blue-500/50 has-[:checked]:bg-blue-500/10">
                                    <input type="radio" name="type" value="credit" class="hidden peer" checked>
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        <span
                                            class="text-xs font-bold text-white uppercase tracking-widest">{{ __('Credit') }}</span>
                                    </div>
                                    <div
                                        class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <div class="w-4 h-4 rounded-full bg-blue-500 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                                <label
                                    class="relative flex items-center justify-center p-4 rounded-xl border border-white/10 bg-white/5 cursor-pointer hover:bg-white/10 transition-all group has-[:checked]:border-blue-500/50 has-[:checked]:bg-blue-500/10">
                                    <input type="radio" name="type" value="debit" class="hidden peer">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 12H4"></path>
                                        </svg>
                                        <span
                                            class="text-xs font-bold text-white uppercase tracking-widest">{{ __('Debit') }}</span>
                                    </div>
                                    <div
                                        class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <div class="w-4 h-4 rounded-full bg-blue-500 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Amount Input --}}
                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold text-text-secondary uppercase tracking-widest">{{ __('Amount') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span
                                        class="text-text-secondary font-bold">{{ getSetting('currency_symbol', '$') }}</span>
                                </div>
                                <input type="number" name="amount" step="any" required
                                    class="w-full bg-[#1e293b] border border-white/10 rounded-xl py-3 pl-8 pr-4 text-white text-base font-bold focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-text-secondary/30"
                                    placeholder="0.00">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold text-text-secondary uppercase tracking-widest">{{ __('Description (Optional)') }}</label>
                            <textarea name="description" rows="3"
                                class="w-full bg-[#1e293b] border border-white/10 rounded-xl p-4 text-white text-base font-medium focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-text-secondary/30"
                                placeholder="{{ __('Reason for this adjustment...') }}"></textarea>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-4 pt-4">
                            <button type="button"
                                class="flex-1 py-4 rounded-xl border border-white/10 text-white font-bold hover:bg-white/5 transition-all modal-close cursor-pointer">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit"
                                class="flex-1 py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black shadow-lg shadow-blue-500/20 transition-all cursor-pointer">
                                {{ __('Update Balance') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Send Email Modal (Full Page) --}}
        <div id="send_email_modal" class="fixed inset-0 z-[200] hidden flex-col bg-[#0B0F1A] overflow-hidden"
            style="background-color: #0B0F1A !important; opacity: 1 !important;">
            <div class="flex items-center justify-between p-6 border-b border-white/10 bg-white/5">
                <h3 class="text-xl font-bold text-white flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    {{ __('Send Email to') }} {{ $user->fullname ?? $user->username }}
                </h3>
                <button type="button"
                    class="p-2 rounded-xl bg-white/5 border border-white/10 text-text-secondary hover:text-white transition-all modal-close cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 md:p-10">
                <div class="max-w-4xl mx-auto space-y-8">
                    <form id="send-email-form" class="space-y-8">
                        @csrf
                        {{-- Subject --}}
                        <div class="space-y-3">
                            <label
                                class="text-sm font-bold text-text-secondary uppercase tracking-widest">{{ __('Subject') }}</label>
                            <input type="text" name="subject" required
                                class="w-full bg-white/[0.03] border border-white/10 rounded-2xl py-4 px-6 text-white text-base font-bold focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all placeholder:text-text-secondary/30"
                                placeholder="{{ __('Enter email subject...') }}">
                        </div>

                        {{-- Message --}}
                        <div class="space-y-3">
                            <label
                                class="text-sm font-bold text-text-secondary uppercase tracking-widest">{{ __('Message') }}</label>
                            <div id="email-editor-container"
                                class="bg-white/[0.03] border border-white/10 rounded-2xl overflow-hidden">
                                <div id="email-editor"></div>
                            </div>
                            <input type="hidden" name="message" id="email-message-input">
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6">
                            <button type="button"
                                class="px-8 py-4 rounded-2xl border border-white/10 text-white font-bold hover:bg-white/5 transition-all modal-close cursor-pointer">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit"
                                class="px-10 py-4 rounded-2xl bg-amber-500 hover:bg-amber-600 text-primary-dark font-black shadow-lg shadow-amber-500/20 transition-all cursor-pointer">
                                {{ __('Send Message') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        {{-- Trading Modules Modal --}}
        <div id="trading_modules_modal" class="fixed inset-0 z-[110] hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm modal-close"></div>
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl p-6">
                <div
                    class="bg-[#0F172A] border border-white/10 rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                    <div class="p-6 border-b border-white/5 bg-white/[0.02] flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-violet-500/20 flex items-center justify-center text-violet-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            {{ __('Trading Module Quick Links') }}
                        </h3>
                        <button type="button"
                            class="text-text-secondary hover:text-white transition-colors modal-close cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="p-8 overflow-y-auto max-h-[70vh]">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @php
                                $modules = [
                                    'Futures' => [
                                        'module' => 'futures_module',
                                        'links' => [
                                            [
                                                'name' => 'Trading Account',
                                                'route' => 'admin.futures-trading.accounts.index',
                                                'icon' =>
                                                    'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                            ],
                                            [
                                                'name' => 'Order History',
                                                'route' => 'admin.futures-trading.orders.index',
                                                'icon' =>
                                                    'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
                                            ],
                                            [
                                                'name' => 'Open Positions',
                                                'route' => 'admin.futures-trading.positions.index',
                                                'icon' =>
                                                    'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z',
                                            ],
                                        ],
                                    ],
                                    'Forex' => [
                                        'module' => 'forex_module',
                                        'links' => [
                                            [
                                                'name' => 'Trading Account',
                                                'route' => 'admin.forex-trading.accounts.index',
                                                'icon' =>
                                                    'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                            ],
                                            [
                                                'name' => 'Order History',
                                                'route' => 'admin.forex-trading.orders.index',
                                                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                            ],
                                            [
                                                'name' => 'Open Positions',
                                                'route' => 'admin.forex-trading.positions.index',
                                                'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
                                            ],
                                        ],
                                    ],
                                    'Margin' => [
                                        'module' => 'margin_module',
                                        'links' => [
                                            [
                                                'name' => 'Trading Account',
                                                'route' => 'admin.margin-trading.accounts.index',
                                                'icon' =>
                                                    'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
                                            ],
                                            [
                                                'name' => 'Order History',
                                                'route' => 'admin.margin-trading.orders.index',
                                                'icon' =>
                                                    'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                            ],
                                            [
                                                'name' => 'Open Positions',
                                                'route' => 'admin.margin-trading.positions.index',
                                                'icon' =>
                                                    'M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
                                            ],
                                        ],
                                    ],
                                    'Stocks' => [
                                        'module' => 'stock_module',
                                        'links' => [
                                            [
                                                'name' => 'Holdings',
                                                'route' => 'admin.stocks.index',
                                                'icon' =>
                                                    'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4',
                                            ],
                                            [
                                                'name' => 'History',
                                                'route' => 'admin.stocks.history',
                                                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                            ],
                                        ],
                                    ],
                                    'ETFs' => [
                                        'module' => 'etf_module',
                                        'links' => [
                                            [
                                                'name' => 'Holdings',
                                                'route' => 'admin.etfs.index',
                                                'icon' =>
                                                    'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                                            ],
                                            [
                                                'name' => 'History',
                                                'route' => 'admin.etfs.history',
                                                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                            ],
                                        ],
                                    ],
                                    'Bonds' => [
                                        'module' => 'bonds_module',
                                        'links' => [
                                            [
                                                'name' => 'Holdings',
                                                'route' => 'admin.bonds.index',
                                                'icon' =>
                                                    'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                                            ],
                                            [
                                                'name' => 'History',
                                                'route' => 'admin.bonds.history',
                                                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                            ],
                                        ],
                                    ],
                                ];
                            @endphp

                            @foreach ($modules as $title => $data)
                                @php $isEnabled = moduleEnabled($data['module']); @endphp
                                <div class="space-y-4 relative">
                                    @if (!$isEnabled)
                                        <div
                                            class="absolute inset-0 z-10 flex items-center justify-center bg-black/40 rounded-3xl backdrop-blur-[1px]">
                                            <span
                                                class="bg-red-500/30 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl shadow-2xl border border-white/20 whitespace-nowrap">
                                                {{ $title }} {{ __('Module is disabled') }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-2 px-2">
                                        <div class="w-1 h-4 bg-violet-500 rounded-full"></div>
                                        <span
                                            class="text-[10px] font-black text-violet-400 uppercase tracking-[0.2em]">{{ $title }}</span>
                                    </div>
                                    <div class="space-y-2 {{ !$isEnabled ? 'opacity-80 grayscale' : '' }}">
                                        @foreach ($data['links'] as $link)
                                            <a href="{{ $isEnabled ? route($link['route'], ['user_id' => $user->id]) : 'javascript:void(0)' }}"
                                                class="group flex items-center gap-4 p-4 rounded-2xl bg-white/[0.03] border border-white/5 {{ $isEnabled ? 'hover:border-violet-500/30 hover:bg-violet-500/5 transition-all duration-300' : 'cursor-not-allowed' }}">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-text-secondary {{ $isEnabled ? 'group-hover:bg-violet-500/10 group-hover:text-violet-400 transition-colors' : '' }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="{{ $link['icon'] }}"></path>
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <div
                                                        class="text-sm font-bold text-white {{ $isEnabled ? 'group-hover:text-violet-300 transition-colors' : '' }}">
                                                        {{ $link['name'] }}</div>
                                                    <div
                                                        class="text-[9px] text-text-secondary font-bold uppercase tracking-widest opacity-50 {{ $isEnabled ? 'group-hover:opacity-100 transition-opacity' : '' }}">
                                                        {{ __('View Records') }}</div>
                                                </div>
                                                <svg class="w-4 h-4 text-text-secondary/30 {{ $isEnabled ? 'group-hover:text-violet-400 group-hover:translate-x-1 transition-all' : '' }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-6 border-t border-white/5 bg-white/[0.02] flex items-center justify-end">
                        <button type="button"
                            class="px-6 py-3 rounded-xl border border-white/10 text-white text-xs font-bold uppercase tracking-widest hover:bg-white/5 transition-all modal-close">
                            {{ __('Close Menu') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="loading-spinner"
            class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-16 h-16 border-4 border-accent-primary/20 border-t-accent-primary rounded-full animate-spin">
            </div>
        </div>
    @endsection

    @section('scripts')
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Quill globally so it's ready when modal opens
                let quill = null;

                function initializeQuill() {
                    if (quill) return;
                    setTimeout(function() {
                        try {
                            quill = new Quill('#email-editor', {
                                modules: {
                                    toolbar: [
                                        [{
                                            header: [1, 2, 3, false]
                                        }],
                                        ['bold', 'italic', 'underline', 'strike'],
                                        [{
                                            'list': 'ordered'
                                        }, {
                                            'list': 'bullet'
                                        }],
                                        ['link', 'image'],
                                        ['clean']
                                    ]
                                },
                                placeholder: '{{ __('Write your email message here...') }}',
                                theme: 'snow'
                            });

                            quill.on('text-change', function() {
                                $('#email-message-input').val(quill.root.innerHTML);
                            });
                        } catch (e) {
                            console.error('Quill initialization failed:', e);
                        }
                    }, 100);
                }
                // Re-using the same confirmation logic as index
                let currentUserId = null;
                let currentAction = null;

                $(document).on('click', '.open-confirm-modal', function() {
                    currentUserId = $(this).data('id');
                    currentAction = $(this).data('action');
                    const userName = $(this).data('name');

                    let title, message, icon, iconBg, btnClass;

                    if (currentAction === 'ban') {
                        title = "{{ __('Ban User') }}";
                        message =
                            `{{ __('Are you sure you want to ban') }} <strong>${userName}</strong>? {{ __('They will no longer be able to access their account.') }}`;
                        icon =
                            '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>';
                        iconBg = 'bg-red-500/20 text-red-500';
                        btnClass = 'bg-red-500 hover:bg-red-600 shadow-red-500/20';
                    } else if (currentAction === 'unban') {
                        title = "{{ __('Unban User') }}";
                        message = `{{ __('Are you sure you want to unban') }} <strong>${userName}</strong>?`;
                        icon =
                            '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                        iconBg = 'bg-emerald-500/20 text-emerald-500';
                        btnClass = 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20';
                    } else if (currentAction === 'delete') {
                        title = "{{ __('Delete User') }}";
                        message =
                            `{{ __('Are you sure you want to delete') }} <strong>${userName}</strong>? {{ __('This action is permanent and cannot be undone.') }}`;
                        icon =
                            '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
                        iconBg = 'bg-red-500/20 text-red-500';
                        btnClass = 'bg-red-500 hover:bg-red-600 shadow-red-500/20';
                    } else if (currentAction === 'login_as') {
                        title = "{{ __('Login As User') }}";
                        message =
                            `{{ __('Are you sure you want to login as') }} <strong>${userName}</strong>? {{ __('This will permit you to see the dashboard as they do.') }}`;
                        icon =
                            '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>';
                        iconBg = 'bg-indigo-500/20 text-indigo-500';
                        btnClass = 'bg-indigo-500 hover:bg-indigo-600 shadow-indigo-500/20';
                    }

                    $('#confirm-icon-container').html(icon).removeClass().addClass(
                        `w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center ${iconBg}`);
                    $('#confirm-title').text(title);
                    $('#confirm-message').html(message);
                    $('#confirm-action-btn').removeClass().addClass(
                        `flex-1 px-4 py-3 rounded-xl text-white font-bold shadow-lg transition-all cursor-pointer ${btnClass}`
                    );

                    $('#confirm-modal').removeClass('hidden').addClass('flex');
                });

                $(document).on('click', '.trigger-modal', function() {
                    const targetId = $(this).data('target');
                    if (targetId) {
                        $(targetId).removeClass('hidden').addClass('flex');
                        if (targetId === '#send_email_modal') {
                            initializeQuill();
                        }
                    }
                });

                $(document).on('click', '.modal-close', function() {
                    $('#confirm-modal').addClass('hidden').removeClass('flex');
                    $('#credit_debit_user_modal').addClass('hidden').removeClass('flex');
                });

                $('#confirm-action-btn').on('click', function() {
                    if (!currentUserId || !currentAction) return;

                    $('#loading-spinner').removeClass('hidden').addClass('flex');
                    $('#confirm-modal').addClass('hidden').removeClass('flex');

                    let url = "{{ route('admin.users.bulk-action') }}";
                    let data = {
                        _token: "{{ csrf_token() }}",
                        ids: [currentUserId],
                        action: currentAction
                    };

                    if (currentAction === 'login_as') {
                        url = "{{ route('admin.users.login-as', ':id') }}".replace(':id', currentUserId);
                        data = {
                            _token: "{{ csrf_token() }}"
                        };
                    }

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.success || response.status === 'success') {
                                if (currentAction === 'delete') {
                                    window.location.href = "{{ route('admin.users.index') }}";
                                } else if (currentAction === 'login_as') {
                                    window.open(response.redirect_url, '_blank');
                                    toastNotification(response.message, 'success');
                                } else {
                                    // No reload, update content via ajax
                                    updateDetailContent();
                                    toastNotification(response.message, 'success');
                                }
                            } else {
                                toastNotification(response.message || 'An error occurred.',
                                    'error');
                            }
                        },
                        error: function(xhr) {
                            const error = xhr.responseJSON ? xhr.responseJSON.message :
                                "{{ __('An error occurred while processing the request.') }}";
                            toastNotification(error, 'error');
                        },
                        complete: function() {
                            $('#loading-spinner').addClass('hidden').removeClass('flex');
                            currentUserId = null;
                            currentAction = null;
                        }
                    });
                });

                $('#credit-debit-form').on('submit', function(e) {
                    e.preventDefault();
                    const formData = $(this).serialize();

                    $('#loading-spinner').removeClass('hidden').addClass('flex');
                    $('#credit_debit_user_modal').addClass('hidden').removeClass('flex');

                    $.ajax({
                        url: "{{ route('admin.users.credit-debit', $user->id) }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.success || response.status === 'success') {
                                updateDetailContent();
                                toastNotification(response.message, 'success');
                                $('#credit-debit-form')[0].reset();
                            } else {
                                toastNotification(response.message ||
                                    '{{ __('An error occurred.') }}',
                                    'error');
                            }
                        },
                        error: function(xhr) {
                            const error = xhr.responseJSON ? xhr.responseJSON.message :
                                "{{ __('An error occurred while processing the request.') }}";
                            toastNotification(error, 'error');
                        },
                        complete: function() {
                            $('#loading-spinner').addClass('hidden').removeClass('flex');
                        }
                    });
                });

                $('#send-email-form').on('submit', function(e) {
                    e.preventDefault();
                    const formData = $(this).serialize();

                    $('#loading-spinner').removeClass('hidden').addClass('flex');
                    $('#send_email_modal').addClass('hidden').removeClass('flex');

                    $.ajax({
                        url: "{{ route('admin.users.send-email', $user->id) }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.success || response.status === 'success') {
                                toastNotification(response.message, 'success');
                                $('#send-email-form')[0].reset();
                                // Reset Quill
                                if (quill) {
                                    quill.setContents([]);
                                }
                            } else {
                                toastNotification(response.message ||
                                    '{{ __('An error occurred.') }}',
                                    'error');
                                $('#send_email_modal').removeClass('hidden').addClass('flex');
                            }
                        },
                        error: function(xhr) {
                            const error = xhr.responseJSON ? xhr.responseJSON.message :
                                "{{ __('An error occurred while processing the request.') }}";
                            toastNotification(error, 'error');
                            $('#send_email_modal').removeClass('hidden').addClass('flex');
                        },
                        complete: function() {
                            $('#loading-spinner').addClass('hidden').removeClass('flex');
                        }
                    });
                });

                function updateDetailContent() {
                    $.get(window.location.href, function(data) {
                        $('#user-detail-content').html($(data).find('#user-detail-content').html());
                    });
                }

                $(document).on('click', '.modal-close', function() {
                    $('#confirm-modal').addClass('hidden').removeClass('flex');
                    $('#credit_debit_user_modal').addClass('hidden').removeClass('flex');
                    $('#send_email_modal').addClass('hidden').removeClass('flex');
                    $('#trading_modules_modal').addClass('hidden').removeClass('flex');
                });
            });
        </script>
    @endsection
