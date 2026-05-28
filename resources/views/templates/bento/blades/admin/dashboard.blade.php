@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div class="space-y-8 mb-12">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-2 md:gap-4">
            <div>
                <h2
                    class="text-2xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-indigo-200 to-indigo-400 tracking-tight leading-tight">
                    {{ __('Admin Command Center') }}
                </h2>
                <p class="text-indigo-200/60 font-mono text-[9px] md:text-xs mt-0.5 tracking-widest uppercase">
                    {{ __('Real-time Platform Telemetry') }}
                </p>
            </div>
            <div class="flex items-center gap-2 text-[10px] text-emerald-400/80 font-mono">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                {{ \Carbon\Carbon::now()->format('l, F j, Y · H:i') }}
            </div>
        </div>

        {{-- 3-COLUMN BENTO GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            {{-- ══ CARD 1 · USERS ══ --}}
            @php $u = $top_cards['users']; @endphp
            <div
                class="relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>
                <div
                    class="absolute top-0 right-0 -mt-10 -mr-10 w-36 h-36 bg-blue-500/20 rounded-full blur-2xl pointer-events-none z-0">
                </div>
                <div
                    class="absolute bottom-0 left-0 -mb-8 -ml-8 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none z-0">
                </div>
                <div class="relative z-10 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-[10px] font-bold uppercase tracking-widest shadow-[0_0_10px_rgba(59,130,246,0.15)]">
                            {{ __('Users') }}</div>
                        <div
                            class="flex items-center gap-1.5 bg-blue-500/10 border border-blue-500/20 rounded-full px-2.5 py-1">
                            <span class="text-[9px] text-slate-500 font-mono">{{ __('today') }}</span>
                            <span
                                class="text-[10px] font-bold text-blue-400">+{{ number_format($u['new_users_today']) }}</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div
                            class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-white to-slate-400 tracking-tight leading-none">
                            {{ number_format($u['total']) }}</div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-widest font-mono mt-1">
                            {{ __('Total Registered Users') }}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mb-2">
                        <div
                            class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-2.5 text-center hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                            <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('Active') }}</div>
                            <div class="text-sm font-bold text-emerald-400">{{ number_format($u['active']) }}</div>
                        </div>
                        <div
                            class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-2.5 text-center hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                            <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('Banned') }}</div>
                            <div class="text-sm font-bold text-rose-400">{{ number_format($u['banned']) }}</div>
                        </div>
                        <div
                            class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-2.5 text-center hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                            <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('Today') }}</div>
                            <div class="text-sm font-bold text-blue-400">{{ number_format($u['new_users_today']) }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div
                            class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-2.5 hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                            <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('Email Verified') }}</div>
                            <div class="flex justify-between items-end">
                                <div class="text-sm font-bold text-white">{{ number_format($u['email_verified']) }}</div>
                                <div class="text-[9px] text-amber-400 font-bold">
                                    {{ number_format($u['pending_email_verification']) }} {{ __('pend.') }}</div>
                            </div>
                        </div>
                        <div
                            class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-2.5 hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                            <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('KYC Verified') }}</div>
                            @if (!moduleEnabled('kyc_module'))
                                <div class="text-[9px] text-red-400 font-bold">{{ __('Module disabled') }}</div>
                            @else
                                <div class="flex justify-between items-end">
                                    <div class="text-sm font-bold text-emerald-400">
                                        {{ number_format($u['kyc_verified']) }}</div>
                                    <div class="text-[9px] text-amber-400 font-bold">
                                        {{ number_format($u['pending_kyc']) }} {{ __('pend.') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- user quick action button --}}
                    <div class="mt-3">
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center justify-center gap-2 w-full px-4 py-2 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-300 text-[10px] font-bold uppercase tracking-widest hover:bg-blue-500/20 hover:border-blue-500/30 hover:text-blue-200 transition-all cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                            {{ __('Manage Users') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- ══ CARD 2 · SYSTEM EQUITY ══ --}}
            @php $eq = $top_cards['system_equity']; @endphp
            <div
                class="relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>
                <div
                    class="absolute top-0 right-0 -mt-10 -mr-10 w-36 h-36 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none z-0">
                </div>
                <div
                    class="absolute bottom-0 left-0 -mb-8 -ml-8 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl pointer-events-none z-0">
                </div>
                <div class="relative z-10 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase tracking-widest shadow-[0_0_10px_rgba(99,102,241,0.2)]">
                            {{ __('System Equity') }}</div>
                        <span class="flex items-center gap-1.5 text-[10px] text-emerald-400 font-mono">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>{{ __('Live') }}
                        </span>
                    </div>
                    <div class="mb-4">
                        <div
                            class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-white to-indigo-300 tracking-tight leading-none">
                            {{ showAmount($eq['total']) }}</div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-widest font-mono mt-1">
                            {{ __('Total Assets Under Management') }}</div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div
                            class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-3 hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                            <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('User Wallets') }}</div>
                            <div class="text-sm font-bold text-white">{{ showAmount($eq['users_balance']) }}</div>
                        </div>
                        <div
                            class="bg-white/[0.03] border border-rose-500/10 rounded-xl p-3 hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                            <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('Futures') }}</div>
                            @if (!moduleEnabled('futures_module'))
                                <div class="text-[9px] text-red-400 font-bold">{{ __('Module disabled') }}</div>
                            @else
                                <div class="text-sm font-bold text-rose-400">{{ showAmount($eq['futures_balance']) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div
                            class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-2.5 text-center hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                            <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('Margin') }}</div>
                            @if (!moduleEnabled('margin_module'))
                                <div class="text-[9px] text-red-400 font-bold">{{ __('Module disabled') }}</div>
                            @else
                                <div class="text-xs font-bold text-amber-400">{{ showAmount($eq['margin_balance']) }}</div>
                            @endif
                        </div>
                        <div
                            class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-2.5 text-center hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                            <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('Forex') }}</div>
                            @if (!moduleEnabled('forex_module'))
                                <div class="text-[9px] text-red-400 font-bold">{{ __('Module disabled') }}</div>
                            @else
                                <div class="text-xs font-bold text-cyan-400">{{ showAmount($eq['forex_balance']) }}</div>
                            @endif
                        </div>
                        <div
                            class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-2.5 text-center hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                            <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('Stocks') }}</div>
                            @if (!moduleEnabled('stock_module'))
                                <div class="text-[9px] text-red-400 font-bold">{{ __('Module disabled') }}</div>
                            @else
                                <div class="text-xs font-bold text-purple-400">{{ showAmount($eq['stocks_balance']) }}
                                </div>
                            @endif
                        </div>
                        <div
                            class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-2.5 text-center hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                            <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('ETFs') }}</div>
                            @if (!moduleEnabled('etf_module'))
                                <div class="text-[9px] text-red-400 font-bold">{{ __('Module disabled') }}</div>
                            @else
                                <div class="text-xs font-bold text-pink-400">{{ showAmount($eq['etfs_balance']) }}</div>
                            @endif
                        </div>
                        <div
                            class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-2.5 text-center hover:bg-white/[0.06] transition-all backdrop-blur-sm col-span-2">
                            <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                {{ __('Bonds') }}</div>
                            @if (!moduleEnabled('bonds_module'))
                                <div class="text-[9px] text-red-400 font-bold">{{ __('Module disabled') }}</div>
                            @else
                                <div class="text-xs font-bold text-slate-400">{{ showAmount($eq['bonds_balance']) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ CARD 3 · INVESTMENTS ══ --}}
            @php $inv = $top_cards['investment']; @endphp
            @if (moduleEnabled('investment_module'))
                <div
                    class="relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                    <div
                        class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                    </div>
                    <div
                        class="absolute top-0 right-0 -mt-10 -mr-10 w-36 h-36 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none z-0">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 -mb-8 -ml-8 w-24 h-24 bg-green-500/10 rounded-full blur-2xl pointer-events-none z-0">
                    </div>
                    <div class="relative z-10 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-[10px] font-bold uppercase tracking-widest shadow-[0_0_10px_rgba(16,185,129,0.15)]">
                                {{ __('Investments') }}</div>
                            <div
                                class="flex items-center gap-1.5 bg-white/[0.03] border border-white/[0.05] rounded-full px-2.5 py-1">
                                <span class="text-[9px] text-slate-500 font-mono">{{ __('plans') }}</span>
                                <span
                                    class="text-[10px] font-bold text-emerald-400">{{ number_format($inv['plans']['active']) }}</span>
                                <span
                                    class="text-[9px] text-slate-600">/{{ number_format($inv['plans']['total']) }}</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div
                                class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-br from-white to-emerald-300 tracking-tight leading-none">
                                {{ showAmount($inv['total']['amount']) }}</div>
                            <div class="text-[10px] text-slate-500 uppercase tracking-widest font-mono mt-1">
                                {{ number_format($inv['total']['count']) }} {{ __('total investments') }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <div
                                class="bg-white/[0.03] border border-emerald-500/10 rounded-xl p-3 hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                                <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                    {{ __('Active Capital') }}</div>
                                <div class="text-sm font-bold text-emerald-400">{{ showAmount($inv['active']['amount']) }}
                                </div>
                                <div class="text-[9px] text-slate-600 mt-0.5">{{ number_format($inv['active']['count']) }}
                                    plans</div>
                            </div>
                            <div
                                class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-3 hover:bg-white/[0.06] transition-all backdrop-blur-sm">
                                <div class="text-[8px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                    {{ __('Completed') }}</div>
                                <div class="text-sm font-bold text-slate-300">
                                    {{ showAmount($inv['completed']['amount']) }}
                                </div>
                                <div class="text-[9px] text-slate-600 mt-0.5">
                                    {{ number_format($inv['completed']['count']) }}
                                    plans</div>
                            </div>
                        </div>
                        <div class="bg-white/[0.03] border border-white/[0.05] rounded-xl p-3 backdrop-blur-sm">
                            <div class="text-[8px] font-bold uppercase tracking-widest text-slate-600 mb-2">
                                {{ __('ROI') }}</div>
                            <div class="grid grid-cols-3 gap-1 mb-2">
                                <div class="text-center">
                                    <div class="text-[8px] text-slate-500 uppercase tracking-wide mb-0.5">
                                        {{ __('Total') }}
                                    </div>
                                    <div class="text-xs font-bold text-emerald-400">{{ showAmount($inv['roi']['total']) }}
                                    </div>
                                </div>
                                <div class="text-center border-x border-white/[0.06]">
                                    <div class="text-[8px] text-slate-500 uppercase tracking-wide mb-0.5">
                                        {{ __('Paid') }}
                                    </div>
                                    <div class="text-xs font-bold text-white">{{ showAmount($inv['roi']['paid']) }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-[8px] text-slate-500 uppercase tracking-wide mb-0.5">
                                        {{ __('Pending') }}
                                    </div>
                                    <div class="text-xs font-bold text-amber-400">{{ showAmount($inv['roi']['pending']) }}
                                    </div>
                                </div>
                            </div>
                            @php $pct = $inv['roi']['total'] > 0 ? min(($inv['roi']['paid'] / $inv['roi']['total']) * 100, 100) : 0; @endphp
                            <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-full"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="text-[8px] text-slate-600 text-right mt-0.5">{{ number_format($pct, 1) }}%
                                {{ __('paid out') }}</div>
                        </div>
                    </div>
                </div>
            @else
                <div
                    class="relative bg-secondary/40 border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors group opacity-60">
                    <div
                        class="absolute inset-0 bg-[linear-gradient(45deg,rgba(0,0,0,0.1)_25%,transparent_25%,transparent_50%,rgba(0,0,0,0.1)_50%,rgba(0,0,0,0.1)_75%,transparent_75%,transparent)] bg-[length:20px_20px] opacity-20">
                    </div>
                    <div class="relative z-10 p-8 flex flex-col items-center justify-center text-center h-[300px]">
                        <div
                            class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-500" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">
                            {{ __('Investment Module Offline') }}</h4>
                        <p class="text-[10px] text-slate-500 max-w-[200px] mb-6 font-medium leading-relaxed italic">
                            {{ __('Protocol access restricted. Please enable the investment module in settings to initialize.') }}
                        </p>
                        <a href="{{ route('admin.settings.modules.index') }}"
                            class="px-5 py-2 rounded-xl bg-white/5 border border-white/10 text-[9px] font-black text-white uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all active:scale-95">
                            {{ __('Settings') }}
                        </a>
                    </div>
                </div>
            @endif

        </div>{{-- /3-col bento --}}

        {{-- ─── QUICK ACTIONS ─── --}}
        @php
            $action_buttons = [
                [
                    'route_name' => 'admin.users.index',
                    'name' => 'Users',
                    'color' => [
                        'bg' => 'bg-blue-500/10',
                        'border' => 'border-blue-500/20',
                        'text' => 'text-blue-300',
                        'hover' => 'hover:bg-blue-500/20 hover:border-blue-500/30',
                    ],
                    'icon' =>
                        '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                ],
                [
                    'route_name' => 'admin.deposits.index',
                    'name' => 'Deposits',
                    'color' => [
                        'bg' => 'bg-emerald-500/10',
                        'border' => 'border-emerald-500/20',
                        'text' => 'text-emerald-300',
                        'hover' => 'hover:bg-emerald-500/20 hover:border-emerald-500/30',
                    ],
                    'icon' => '<path d="M12 19V5m0 14l-4-4m4 4l4-4"/>',
                ],
                [
                    'route_name' => 'admin.withdrawals.index',
                    'name' => 'Withdrawals',
                    'color' => [
                        'bg' => 'bg-rose-500/10',
                        'border' => 'border-rose-500/20',
                        'text' => 'text-rose-300',
                        'hover' => 'hover:bg-rose-500/20 hover:border-rose-500/30',
                    ],
                    'icon' => '<path d="M12 5v14m0-14l-4 4m4-4l4 4"/>',
                ],
                [
                    'route_name' => 'admin.investments.index',
                    'name' => 'Investments',
                    'color' => [
                        'bg' => 'bg-amber-500/10',
                        'border' => 'border-amber-500/20',
                        'text' => 'text-amber-300',
                        'hover' => 'hover:bg-amber-500/20 hover:border-amber-500/30',
                    ],
                    'icon' => '<path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>',
                ],
                [
                    'route_name' => 'admin.trading.index',
                    'name' => 'Trades',
                    'color' => [
                        'bg' => 'bg-violet-500/10',
                        'border' => 'border-violet-500/20',
                        'text' => 'text-violet-300',
                        'hover' => 'hover:bg-violet-500/20 hover:border-violet-500/30',
                    ],
                    'icon' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
                ],
                [
                    'route_name' => 'admin.transactions.index',
                    'name' => 'Transactions',
                    'color' => [
                        'bg' => 'bg-cyan-500/10',
                        'border' => 'border-cyan-500/20',
                        'text' => 'text-cyan-300',
                        'hover' => 'hover:bg-cyan-500/20 hover:border-cyan-500/30',
                    ],
                    'icon' =>
                        '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>',
                ],
            ];
        @endphp
        <div class="flex items-center flex-wrap gap-2.5 w-full">
            @foreach ($action_buttons as $btn)
                @php
                    $c = $btn['color'];
                    $exists = Route::has($btn['route_name']);
                    $href = $exists ? route($btn['route_name']) : '#';
                @endphp
                <a href="{{ $href }}"
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2 rounded-xl {{ $c['bg'] }} border {{ $c['border'] }} {{ $c['text'] }} text-[10px] font-bold uppercase tracking-widest transition-all cursor-pointer
                        {{ $exists ? $c['hover'] : 'opacity-40 pointer-events-none' }} @if ($btn['route_name'] == 'admin.investments.index' && !moduleEnabled('investment_module')) opacity-40 pointer-events-none @endif">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        {!! $btn['icon'] !!}
                    </svg>
                    {{ __($btn['name']) }}
                    @unless ($exists)
                        <span class="text-[8px] font-mono normal-case tracking-normal opacity-60">{{ __('soon') }}</span>
                    @endunless
                </a>
            @endforeach
        </div>{{-- /quick actions --}}

        {{-- ─── TRADING CARD ─── --}}
        @php
            $fx = $trading_card['forex'];
            $totalPositions = 0;
            if (moduleEnabled('futures_module')) {
                $totalPositions += $trading_card['futures']['positions'];
            }
            if (moduleEnabled('margin_module')) {
                $totalPositions += $trading_card['margin']['positions'];
            }
            if (moduleEnabled('forex_module')) {
                $totalPositions += $fx['live']['positions'] + $fx['demo']['positions'];
            }
            $totalPending = 0;
            if (moduleEnabled('futures_module')) {
                $totalPending += $trading_card['futures']['orders']['pending'];
            }
            if (moduleEnabled('margin_module')) {
                $totalPending += $trading_card['margin']['orders']['pending'];
            }
            if (moduleEnabled('forex_module')) {
                $totalPending += $fx['live']['orders']['pending'] + $fx['demo']['orders']['pending'];
            }
            $totalFilled = 0;
            if (moduleEnabled('futures_module')) {
                $totalFilled += $trading_card['futures']['orders']['filled'];
            }
            if (moduleEnabled('margin_module')) {
                $totalFilled += $trading_card['margin']['orders']['filled'];
            }
            if (moduleEnabled('forex_module')) {
                $totalFilled += $fx['live']['orders']['filled'] + $fx['demo']['orders']['filled'];
            }
            $ft = $trading_card['futures'];
            $mg = $trading_card['margin'];
        @endphp
        @if (moduleEnabled('forex_module') || moduleEnabled('margin_module') || moduleEnabled('futures_module'))
            <div
                class="relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>
                <div
                    class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-violet-500/10 rounded-full blur-3xl pointer-events-none z-0">
                </div>
                <div
                    class="absolute bottom-0 left-0 -mb-12 -ml-12 w-48 h-48 bg-blue-500/8 rounded-full blur-3xl pointer-events-none z-0">
                </div>

                <div class="relative z-10 p-6">

                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="px-3 py-1 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-300 text-[10px] font-bold uppercase tracking-widest shadow-[0_0_10px_rgba(139,92,246,0.2)]">
                                {{ __('Trading') }}
                            </div>
                            <span class="flex items-center gap-1.5 text-[10px] text-emerald-400/80 font-mono">
                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>{{ __('Live Data') }}
                            </span>
                        </div>
                        <div class="hidden md:flex items-center divide-x divide-white/5">
                            <div class="text-center px-5">
                                <div class="text-lg font-black text-white">{{ number_format($totalPositions) }}</div>
                                <div class="text-[9px] text-slate-500 uppercase tracking-widest font-mono">
                                    {{ __('Open') }}
                                </div>
                            </div>
                            <div class="text-center px-5">
                                <div class="text-lg font-black text-amber-400">{{ number_format($totalPending) }}</div>
                                <div class="text-[9px] text-slate-500 uppercase tracking-widest font-mono">
                                    {{ __('Pending') }}
                                </div>
                            </div>
                            <div class="text-center pl-5">
                                <div class="text-lg font-black text-emerald-400">{{ number_format($totalFilled) }}</div>
                                <div class="text-[9px] text-slate-500 uppercase tracking-widest font-mono">
                                    {{ __('Filled') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Terminal table --}}
                    <div class="w-full overflow-x-auto">
                        {{-- Column headers --}}
                        <div class="grid grid-cols-[150px_1fr_1fr_1fr_1fr_1fr_1fr] gap-0 mb-2 px-4 min-w-[700px]">
                            <div class="text-[9px] font-bold uppercase tracking-widest text-slate-600">{{ __('Module') }}
                            </div>
                            <div class="text-[9px] font-bold uppercase tracking-widest text-slate-600 text-right">
                                {{ __('Equity') }}</div>
                            <div class="text-[9px] font-bold uppercase tracking-widest text-slate-600 text-right">
                                {{ __('Positions') }}</div>
                            <div class="text-[9px] font-bold uppercase tracking-widest text-slate-600 text-right">
                                {{ __('Filled') }}</div>
                            <div class="text-[9px] font-bold uppercase tracking-widest text-slate-600 text-right">
                                {{ __('Pending') }}</div>
                            <div class="text-[9px] font-bold uppercase tracking-widest text-slate-600 text-right">
                                {{ __('Cancelled') }}</div>
                            <div class="text-[9px] font-bold uppercase tracking-widest text-slate-600 text-right">
                                {{ __('Accounts') }}</div>
                        </div>

                        <div class="space-y-1.5 min-w-[700px]">
                            {{-- Futures --}}
                            @if (moduleEnabled('futures_module'))
                                <div
                                    class="grid grid-cols-[150px_1fr_1fr_1fr_1fr_1fr_1fr] gap-0 items-center border-l-2 border-rose-500/70 bg-white/[0.02] hover:bg-rose-500/[0.04] rounded-r-xl px-4 py-3.5 transition-colors">
                                    <div class="text-[11px] font-bold text-rose-300 uppercase tracking-wide">
                                        {{ __('Futures') }}
                                    </div>
                                    <div class="text-right text-sm font-semibold text-white">
                                        {{ showAmount($ft['accounts']['equity']) }}</div>
                                    <div class="text-right font-black text-white text-base">
                                        {{ number_format($ft['positions']) }}
                                    </div>
                                    <div class="text-right text-sm font-semibold text-emerald-400">
                                        {{ number_format($ft['orders']['filled']) }}</div>
                                    <div class="text-right text-sm font-semibold text-amber-400">
                                        {{ number_format($ft['orders']['pending']) }}</div>
                                    <div class="text-right text-sm font-semibold text-slate-500">
                                        {{ number_format($ft['orders']['cancelled']) }}</div>
                                    <div class="text-right text-sm font-semibold text-slate-400">
                                        {{ number_format($ft['accounts']['count']) }}</div>
                                </div>
                            @endif

                            {{-- Margin --}}
                            @if (moduleEnabled('margin_module'))
                                <div
                                    class="grid grid-cols-[150px_1fr_1fr_1fr_1fr_1fr_1fr] gap-0 items-center border-l-2 border-amber-500/70 bg-white/[0.02] hover:bg-amber-500/[0.04] rounded-r-xl px-4 py-3.5 transition-colors">
                                    <div class="text-[11px] font-bold text-amber-300 uppercase tracking-wide">
                                        {{ __('Margin') }}
                                    </div>
                                    <div class="text-right text-sm font-semibold text-white">
                                        {{ showAmount($mg['accounts']['equity']) }}</div>
                                    <div class="text-right font-black text-white text-base">
                                        {{ number_format($mg['positions']) }}
                                    </div>
                                    <div class="text-right text-sm font-semibold text-emerald-400">
                                        {{ number_format($mg['orders']['filled']) }}</div>
                                    <div class="text-right text-sm font-semibold text-amber-400">
                                        {{ number_format($mg['orders']['pending']) }}</div>
                                    <div class="text-right text-sm font-semibold text-slate-500">
                                        {{ number_format($mg['orders']['cancelled']) }}</div>

                                    <div class="text-right text-sm font-semibold text-slate-400">
                                        {{ number_format($mg['accounts']['count']) }}</div>
                                </div>
                            @endif

                            {{-- Forex Live --}}
                            @if (moduleEnabled('forex_module'))
                                <div
                                    class="grid grid-cols-[150px_1fr_1fr_1fr_1fr_1fr_1fr] gap-0 items-center border-l-2 border-cyan-500/70 bg-white/[0.02] hover:bg-cyan-500/[0.04] rounded-r-xl px-4 py-3.5 transition-colors">
                                    <div>
                                        <div class="text-[11px] font-bold text-cyan-300 uppercase tracking-wide">
                                            {{ __('Forex') }}</div>
                                        <div class="text-[8px] font-bold text-emerald-400 uppercase tracking-wider mt-0.5">
                                            ●
                                            {{ __('Live') }}</div>
                                    </div>
                                    <div class="text-right text-sm font-semibold text-white">
                                        {{ showAmount($fx['live']['accounts']['equity']) }}</div>
                                    <div class="text-right font-black text-white text-base">
                                        {{ number_format($fx['live']['positions']) }}</div>
                                    <div class="text-right text-sm font-semibold text-emerald-400">
                                        {{ number_format($fx['live']['orders']['filled']) }}</div>
                                    <div class="text-right text-sm font-semibold text-amber-400">
                                        {{ number_format($fx['live']['orders']['pending']) }}</div>
                                    <div class="text-right text-sm font-semibold text-slate-500">
                                        {{ number_format($fx['live']['orders']['cancelled']) }}</div>
                                    <div class="text-right text-sm font-semibold text-slate-400">
                                        {{ number_format($fx['live']['accounts']['count']) }}</div>
                                </div>

                                {{-- Forex Demo --}}
                                <div
                                    class="grid grid-cols-[150px_1fr_1fr_1fr_1fr_1fr_1fr] gap-0 items-center border-l-2 border-slate-600/60 bg-white/[0.01] hover:bg-white/[0.03] rounded-r-xl px-4 py-3.5 transition-colors opacity-50 hover:opacity-90">
                                    <div>
                                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">
                                            {{ __('Forex') }}</div>
                                        <div class="text-[8px] font-bold text-slate-600 uppercase tracking-wider mt-0.5">○
                                            {{ __('Demo') }}</div>
                                    </div>
                                    <div class="text-right text-sm font-semibold text-white">
                                        {{ showAmount($fx['demo']['accounts']['equity']) }}</div>
                                    <div class="text-right font-black text-slate-300 text-base">
                                        {{ number_format($fx['demo']['positions']) }}</div>
                                    <div class="text-right text-sm font-semibold text-slate-400">
                                        {{ number_format($fx['demo']['orders']['filled']) }}</div>
                                    <div class="text-right text-sm font-semibold text-slate-500">
                                        {{ number_format($fx['demo']['orders']['pending']) }}</div>
                                    <div class="text-right text-sm font-semibold text-slate-600">
                                        {{ number_format($fx['demo']['orders']['cancelled']) }}</div>
                                    <div class="text-right text-sm font-semibold text-slate-500">
                                        {{ number_format($fx['demo']['accounts']['count']) }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>{{-- /trading card --}}
        @endif



        {{-- ─── GRAPH + CHART ─── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ── Left: Multi-purpose Line Graph (2/3 width) ── --}}
            <div
                class="lg:col-span-2 relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>
                <div
                    class="absolute top-0 left-0 -mt-10 -ml-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none z-0">
                </div>

                <div class="relative z-10 p-6">
                    {{-- Header row --}}
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                        <div>
                            <div id="graph-title" class="text-sm font-bold text-white tracking-wide">
                                {{ __('Transaction History') }}</div>
                            <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                                {{ __('Daily volume') }} · {{ getSetting('currency') }} </p>
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            {{-- Dataset switcher --}}
                            <div
                                class="flex items-center flex-wrap gap-1 bg-white/[0.03] border border-white/[0.06] rounded-lg p-1">
                                @foreach (['transactions' => 'Transactions', 'deposits' => 'Deposits', 'withdrawals' => 'Withdrawals', 'investments' => 'Investments'] as $key => $label)
                                    <button data-dataset="{{ $key }}"
                                        class="graph-dataset-btn cursor-pointer px-2.5 py-1 rounded-md text-[9px] font-bold uppercase tracking-wider transition-all
                                            {{ $key === 'transactions' ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : 'text-slate-500 hover:text-slate-300' }} @if (!moduleEnabled('investment_module') && $key === 'investments') hidden @endif">
                                        {{ __($label) }}
                                    </button>
                                @endforeach
                            </div>
                            {{-- Period filter --}}
                            <div
                                class="flex items-center flex-wrap gap-1 bg-white/[0.03] border border-white/[0.06] rounded-lg p-1">
                                @foreach (['7d' => '7D', '30d' => '30D', '1y' => '1Y', 'ytd' => 'YTD'] as $key => $label)
                                    <button data-period="{{ $key }}"
                                        class="graph-period-btn cursor-pointer px-2.5 py-1 rounded-md text-[9px] font-bold uppercase tracking-wider transition-all
                                            {{ $key === '7d' ? 'bg-white/10 text-white' : 'text-slate-500 hover:text-slate-300' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div id="graph-legend" class="flex items-center gap-4 mb-4 flex-wrap"></div>

                    {{-- Chart canvas --}}
                    <div class="relative h-56">
                        <canvas id="mainLineChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- ── Right: Equity Donut (1/3 width) ── --}}
            <div
                class="relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>
                <div
                    class="absolute bottom-0 right-0 -mb-10 -mr-10 w-40 h-40 bg-violet-500/15 rounded-full blur-2xl pointer-events-none z-0">
                </div>

                <div class="relative z-10 p-6">
                    <div class="mb-4">
                        <div class="text-sm font-bold text-white tracking-wide">{{ __('Equity Breakdown') }}</div>
                        <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                            {{ __('By trading module') }}</p>
                    </div>

                    <div class="relative flex items-center justify-center" style="height:180px;">
                        <canvas id="equityDonut"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <div class="text-xs font-black text-white">
                                {{ showAmount(array_sum(array_values($chart_data))) }}</div>
                            <div class="text-[8px] text-slate-500 uppercase tracking-widest font-mono">
                                {{ __('Total') }}</div>
                        </div>
                    </div>

                    {{-- Donut legend --}}
                    <div class="mt-4 space-y-2">
                        @php
                            $donutItems = [
                                'account_balance' => ['label' => 'Accounts', 'color' => '#6366f1'],
                                'futures' => ['label' => 'Futures', 'color' => '#f43f5e'],
                                'margin' => ['label' => 'Margin', 'color' => '#f59e0b'],
                                'forex' => ['label' => 'Forex', 'color' => '#06b6d4'],
                                'stock' => ['label' => 'Stocks', 'color' => '#a855f7'],
                                'etf' => ['label' => 'ETFs', 'color' => '#ec4899'],
                                'bonds' => ['label' => 'Bonds', 'color' => '#64748b'],
                            ];

                            // remove disabled modules
                            foreach ($donutItems as $key => $meta) {
                                if (!moduleEnabled($key . '_module') && $key !== 'account_balance') {
                                    unset($donutItems[$key]);
                                }
                            }

                            $donutTotal = array_sum(array_values($chart_data)) ?: 1;
                        @endphp
                        @foreach ($donutItems as $key => $meta)
                            @php
                                $val = $chart_data[$key] ?? 0;
                                $pct = round(($val / $donutTotal) * 100, 1);
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full shrink-0"
                                        style="background:{{ $meta['color'] }}"></span>
                                    <span class="text-[10px] text-slate-400">{{ __($meta['label']) }}</span>
                                </div>
                                <span class="text-[10px] font-bold text-white font-mono">{{ $pct }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>{{-- /graph + chart --}}


        {{-- ─── CAPITAL INSTRUMENTS ─── --}}
        @php
            $instrumentDefs = [
                'stocks' => [
                    'label' => 'Stocks',
                    'icon' => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14',
                    'accent' => [
                        'bg' => 'bg-violet-500/10',
                        'border' => 'border-violet-500/20',
                        'text' => 'text-violet-300',
                        'glow' => 'bg-violet-500/10',
                    ],
                ],
                'etf' => [
                    'label' => 'ETFs',
                    'icon' =>
                        'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                    'accent' => [
                        'bg' => 'bg-pink-500/10',
                        'border' => 'border-pink-500/20',
                        'text' => 'text-pink-300',
                        'glow' => 'bg-pink-500/10',
                    ],
                ],
                'bonds' => [
                    'label' => 'Bonds',
                    'icon' =>
                        'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'accent' => [
                        'bg' => 'bg-slate-500/10',
                        'border' => 'border-slate-500/20',
                        'text' => 'text-slate-400',
                        'glow' => 'bg-slate-500/10',
                    ],
                ],
            ];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach ($instrumentDefs as $key => $def)
                @php
                    $module_key = match ($key) {
                        'stocks' => 'stock_module',
                        'etf' => 'etf_module',
                        'bonds' => 'bonds_module',
                        default => null,
                    };
                @endphp
                @if ($module_key && !moduleEnabled($module_key))
                    <div
                        class="relative bg-secondary/40 border border-white/5 rounded-2xl overflow-hidden group opacity-60 h-[240px]">
                        <div class="relative z-10 p-5 flex flex-col items-center justify-center text-center h-full">
                            <div
                                class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $def['icon'] }}" />
                                </svg>
                            </div>
                            <div class="text-xs font-black text-white uppercase tracking-widest mb-1">
                                {{ __($def['label']) }}</div>
                            <div class="text-[8px] text-slate-600 font-bold uppercase tracking-wider italic">
                                {{ __('Module Disabled') }}</div>
                        </div>
                    </div>
                    @continue
                @endif
                @php
                    $m = $capital_instruments_metrics[$key] ?? [];
                    $a = $def['accent'];
                    $pnl = $m['pnl']['amount'] ?? 0;
                    $pct = $m['pnl']['percent'] ?? 0;
                    $pos = $pnl >= 0;
                @endphp
                <div
                    class="relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                    <div
                        class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                    </div>
                    <div
                        class="absolute top-0 right-0 -mt-6 -mr-6 w-32 h-32 {{ $a['glow'] }} rounded-full blur-2xl pointer-events-none z-0">
                    </div>
                    <div class="relative z-10 p-5">
                        {{-- Header --}}
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2.5">
                                <div
                                    class="w-8 h-8 rounded-lg {{ $a['bg'] }} border {{ $a['border'] }} flex items-center justify-center">
                                    <svg class="w-4 h-4 {{ $a['text'] }}" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $def['icon'] }}" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-white">{{ __($def['label']) }}</div>
                                    <div class="text-[9px] text-slate-500 uppercase tracking-widest font-mono">
                                        {{ $m['holdings'] ?? 0 }} {{ __('holdings') }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-black {{ $pos ? 'text-emerald-400' : 'text-red-400' }}">
                                    {{ $pos ? '+' : '' }}{{ showAmount($pnl) }}
                                </div>
                                <div
                                    class="text-[9px] font-bold font-mono {{ $pos ? 'text-emerald-500/80' : 'text-red-500/80' }}">
                                    {{ $pos ? '▲' : '▼' }} {{ number_format(abs($pct), 2) }}%
                                </div>
                            </div>
                        </div>
                        {{-- Bought / Sold --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white/[0.03] border border-white/[0.04] rounded-xl p-3">
                                <div class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mb-1">
                                    {{ __('Bought') }}</div>
                                <div class="text-xs font-bold text-white">{{ showAmount($m['bought']['amonut'] ?? 0) }}
                                </div>
                                <div class="text-[9px] text-slate-600 font-mono mt-0.5">{{ __('Fee') }}:
                                    {{ showAmount($m['bought']['fees'] ?? 0) }}</div>
                            </div>
                            <div class="bg-white/[0.03] border border-white/[0.04] rounded-xl p-3">
                                <div class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mb-1">
                                    {{ __('Sold') }}</div>
                                <div class="text-xs font-bold text-white">{{ showAmount($m['sold']['amonut'] ?? 0) }}
                                </div>
                                <div class="text-[9px] text-slate-600 font-mono mt-0.5">{{ __('Fee') }}:
                                    {{ showAmount($m['sold']['fees'] ?? 0) }}</div>
                            </div>
                        </div>
                        {{-- Balance footer --}}
                        <div class="mt-3 flex items-center justify-between pt-3 border-t border-white/[0.05]">
                            <span
                                class="text-[9px] text-slate-500 uppercase tracking-widest font-mono">{{ __('Portfolio Value') }}</span>
                            <span class="text-sm font-black text-white">{{ showAmount($m['balance'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>{{-- /capital instruments --}}

        {{-- ─── RECENT ACTIVITY TABLES ─── --}}
        @php
            /**
             * Table section definition:
             * key        → $recent_data key
             * title      → Card heading
             * route_name → Route for "View All" button
             * accent     → Tailwind colour slug
             * columns    → [ label, callable($row) ]
             */
            $recentTables = [
                // ── Deposits ───────────────────────────────────────────────
                [
                    'key' => 'deposits',
                    'title' => 'Recent Deposits',
                    'route_name' => 'admin.deposits.index',
                    'accent' => 'emerald',
                    'module' => null,
                    'columns' => [
                        ['#', fn($r) => $r->id],
                        ['User', fn($r) => $r->user?->username ?? '—'],
                        ['Amount', fn($r) => showAmount($r->total_amount) . ' ' . $r->currency],
                        ['Method', fn($r) => $r->paymentMethod?->name ?? '—'],
                        ['Status', fn($r) => $r->status],
                        ['Date', fn($r) => $r->created_at?->diffForHumans()],
                    ],
                ],

                // ── Withdrawals ────────────────────────────────────────────
                [
                    'key' => 'withdrawals',
                    'title' => 'Recent Withdrawals',
                    'route_name' => 'admin.withdrawals.index',
                    'accent' => 'rose',
                    'module' => null,
                    'columns' => [
                        ['#', fn($r) => $r->id],
                        ['User', fn($r) => $r->user?->username ?? '—'],
                        ['Amount', fn($r) => showAmount($r->amount_payable) . ' ' . $r->currency],
                        ['Status', fn($r) => $r->status],
                        ['Date', fn($r) => $r->created_at?->diffForHumans()],
                    ],
                ],

                // ── Investments ────────────────────────────────────────────
                [
                    'key' => 'investments',
                    'title' => 'Recent Investments',
                    'route_name' => 'admin.investments.index',
                    'accent' => 'amber',
                    'module' => 'investment_module',
                    'columns' => [
                        ['#', fn($r) => $r->id],
                        ['User', fn($r) => $r->user?->username ?? '—'],
                        ['Plan', fn($r) => $r->plan?->name ?? '—'],
                        ['Capital', fn($r) => showAmount($r->capital_invested)],
                        ['ROI Earned', fn($r) => showAmount($r->roi_earned)],
                        ['Status', fn($r) => $r->status],
                        ['Date', fn($r) => $r->created_at?->diffForHumans()],
                    ],
                ],

                // ── Investment Earnings ────────────────────────────────────
                [
                    'key' => 'investment_earnings',
                    'title' => 'Recent Investment Earnings',
                    'route_name' => 'admin.investments.earnings.index',
                    'accent' => 'yellow',
                    'module' => 'investment_module',
                    'columns' => [
                        ['#', fn($r) => $r->id],
                        ['User', fn($r) => $r->user?->username ?? '—'],
                        ['Inv #', fn($r) => $r->investment_id],
                        ['Amount', fn($r) => showAmount($r->amount)],
                        ['Interest', fn($r) => $r->interest . '%'],
                        ['Date', fn($r) => $r->created_at?->diffForHumans()],
                    ],
                ],

                // ── Transactions ───────────────────────────────────────────
                [
                    'key' => 'transactions',
                    'title' => 'Recent Transactions',
                    'route_name' => 'admin.transactions.index',
                    'accent' => 'cyan',
                    'module' => null,
                    'columns' => [
                        ['#', fn($r) => $r->id],
                        ['User', fn($r) => $r->user?->username ?? '—'],
                        ['Type', fn($r) => $r->type],
                        ['Amount', fn($r) => showAmount($r->converted_amount)],
                        ['Desc', fn($r) => \Str::limit($r->description, 30)],
                        ['Date', fn($r) => $r->created_at?->diffForHumans()],
                    ],
                ],

                // ── Futures Orders ─────────────────────────────────────────
                [
                    'key' => 'futures_orders',
                    'title' => 'Recent Futures Orders',
                    'route_name' => 'admin.trading.futures.orders',
                    'accent' => 'violet',
                    'module' => 'futures_module',
                    'columns' => [
                        ['#', fn($r) => $r->id],
                        ['User', fn($r) => $r->user?->username ?? '—'],
                        ['Ticker', fn($r) => $r->ticker],
                        ['Side', fn($r) => $r->side],
                        ['Size', fn($r) => $r->size],
                        ['Price', fn($r) => $r->price],
                        ['Status', fn($r) => $r->status],
                        ['Date', fn($r) => $r->created_at?->diffForHumans()],
                    ],
                ],

                // ── Forex Live Orders ──────────────────────────────────────
                [
                    'key' => 'forex_orders_live',
                    'title' => 'Recent Forex Orders · Live',
                    'route_name' => 'admin.trading.forex.orders',
                    'accent' => 'cyan',
                    'module' => 'forex_module',
                    'columns' => [
                        ['#', fn($r) => $r->id],
                        ['User', fn($r) => $r->user?->username ?? '—'],
                        ['Pair', fn($r) => $r->ticker],
                        ['Side', fn($r) => $r->side],
                        ['Size', fn($r) => $r->size],
                        ['Price', fn($r) => $r->price],
                        ['Status', fn($r) => $r->status],
                        ['Date', fn($r) => $r->created_at?->diffForHumans()],
                    ],
                ],

                // ── Forex Demo Orders ──────────────────────────────────────
                [
                    'key' => 'forex_orders_demo',
                    'title' => 'Recent Forex Orders · Demo',
                    'route_name' => 'admin.trading.forex.orders',
                    'accent' => 'slate',
                    'module' => 'forex_module',
                    'columns' => [
                        ['#', fn($r) => $r->id],
                        ['User', fn($r) => $r->user?->username ?? '—'],
                        ['Pair', fn($r) => $r->ticker],
                        ['Side', fn($r) => $r->side],
                        ['Size', fn($r) => $r->size],
                        ['Price', fn($r) => $r->price],
                        ['Status', fn($r) => $r->status],
                        ['Date', fn($r) => $r->created_at?->diffForHumans()],
                    ],
                ],

                // ── Margin Orders ──────────────────────────────────────────
                [
                    'key' => 'margin_orders',
                    'title' => 'Recent Margin Orders',
                    'route_name' => 'admin.trading.margin.orders',
                    'accent' => 'orange',
                    'module' => 'margin_module',
                    'columns' => [
                        ['#', fn($r) => $r->id],
                        ['User', fn($r) => $r->user?->username ?? '—'],
                        ['Ticker', fn($r) => $r->ticker],
                        ['Side', fn($r) => $r->side],
                        ['Size', fn($r) => $r->size],
                        ['Price', fn($r) => $r->price],
                        ['Status', fn($r) => $r->status],
                        ['Date', fn($r) => $r->created_at?->diffForHumans()],
                    ],
                ],

                // ── Stock Holding History ──────────────────────────────────
                [
                    'key' => 'stock_history',
                    'title' => 'Recent Stock Transactions',
                    'route_name' => 'admin.stocks.history',
                    'accent' => 'violet',
                    'module' => 'stock_module',
                    'columns' => [
                        ['#', fn($r) => $r->id],
                        ['User', fn($r) => $r->user?->username ?? '—'],
                        ['Ticker', fn($r) => $r->ticker],
                        ['Type', fn($r) => $r->transaction_type],
                        ['Shares', fn($r) => $r->shares],
                        ['Amount', fn($r) => showAmount($r->amount)],
                        ['Fee', fn($r) => showAmount($r->fee_amount)],
                        ['Date', fn($r) => $r->created_at?->diffForHumans()],
                    ],
                ],

                // ── ETF Holding History ────────────────────────────────────
                [
                    'key' => 'etf_history',
                    'title' => 'Recent ETF Transactions',
                    'route_name' => 'admin.etfs.history',
                    'accent' => 'pink',
                    'module' => 'etf_module',
                    'columns' => [
                        ['#', fn($r) => $r->id],
                        ['User', fn($r) => $r->user?->username ?? '—'],
                        ['Ticker', fn($r) => $r->ticker],
                        ['Type', fn($r) => $r->transaction_type],
                        ['Shares', fn($r) => $r->shares],
                        ['Amount', fn($r) => showAmount($r->amount)],
                        ['Fee', fn($r) => showAmount($r->fee_amount)],
                        ['Date', fn($r) => $r->created_at?->diffForHumans()],
                    ],
                ],
            ];

            // Tailwind classes keyed by accent slug
            $accentMap = [
                'emerald' => [
                    'header_text' => 'text-emerald-300',
                    'header_bg' => 'bg-emerald-500/10',
                    'border' => 'border-emerald-500/20',
                    'badge' => 'bg-emerald-500/15 text-emerald-300',
                ],
                'rose' => [
                    'header_text' => 'text-rose-300',
                    'header_bg' => 'bg-rose-500/10',
                    'border' => 'border-rose-500/20',
                    'badge' => 'bg-rose-500/15 text-rose-300',
                ],
                'amber' => [
                    'header_text' => 'text-amber-300',
                    'header_bg' => 'bg-amber-500/10',
                    'border' => 'border-amber-500/20',
                    'badge' => 'bg-amber-500/15 text-amber-300',
                ],
                'yellow' => [
                    'header_text' => 'text-yellow-300',
                    'header_bg' => 'bg-yellow-500/10',
                    'border' => 'border-yellow-500/20',
                    'badge' => 'bg-yellow-500/15 text-yellow-300',
                ],
                'cyan' => [
                    'header_text' => 'text-cyan-300',
                    'header_bg' => 'bg-cyan-500/10',
                    'border' => 'border-cyan-500/20',
                    'badge' => 'bg-cyan-500/15 text-cyan-300',
                ],
                'violet' => [
                    'header_text' => 'text-violet-300',
                    'header_bg' => 'bg-violet-500/10',
                    'border' => 'border-violet-500/20',
                    'badge' => 'bg-violet-500/15 text-violet-300',
                ],
                'orange' => [
                    'header_text' => 'text-orange-300',
                    'header_bg' => 'bg-orange-500/10',
                    'border' => 'border-orange-500/20',
                    'badge' => 'bg-orange-500/15 text-orange-300',
                ],
                'slate' => [
                    'header_text' => 'text-slate-300',
                    'header_bg' => 'bg-slate-500/10',
                    'border' => 'border-slate-500/20',
                    'badge' => 'bg-slate-400/15 text-slate-300',
                ],
                'pink' => [
                    'header_text' => 'text-pink-300',
                    'header_bg' => 'bg-pink-500/10',
                    'border' => 'border-pink-500/20',
                    'badge' => 'bg-pink-500/15 text-pink-300',
                ],
            ];
        @endphp

        @foreach ($recentTables as $table)
            @if (isset($table['module']) && !moduleEnabled($table['module']))
                @continue
            @endif
            @php
                $rows = $recent_data[$table['key']] ?? collect();
                $a = $accentMap[$table['accent']];
                $exists = Route::has($table['route_name']);
                $allHref = $exists ? route($table['route_name']) : '#';
            @endphp
            <div
                class="relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>
                <div class="relative z-10">
                    {{-- Card header --}}
                    <div
                        class="flex items-center justify-between px-5 py-3.5 border-b border-white/[0.05] {{ $a['header_bg'] }}">
                        <div class="flex items-center gap-2">
                            <span
                                class="text-xs font-bold {{ $a['header_text'] }} uppercase tracking-widest">{{ __($table['title']) }}</span>
                            <span
                                class="text-[9px] font-mono {{ $a['badge'] }} px-1.5 py-0.5 rounded-md">{{ $rows->count() }}</span>
                        </div>
                        <a href="{{ $allHref }}"
                            class="flex items-center gap-1 text-[9px] font-bold uppercase tracking-widest {{ $a['header_text'] }} opacity-70 hover:opacity-100 transition-opacity cursor-pointer {{ !$exists ? 'pointer-events-none opacity-40' : '' }}">
                            {{ __('View All') }}
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-white/[0.04]">
                                    @foreach ($table['columns'] as [$colLabel, $_])
                                        <th
                                            class="px-4 py-2.5 text-[9px] font-bold text-slate-500 uppercase tracking-widest whitespace-nowrap font-mono">
                                            {{ __($colLabel) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rows as $row)
                                    <tr class="border-b border-white/[0.03] hover:bg-white/[0.02] transition-colors">
                                        @foreach ($table['columns'] as [$_, $fn])
                                            @php $cell = $fn($row); @endphp
                                            <td class="px-4 py-2.5 text-[11px] text-slate-300 whitespace-nowrap font-mono">
                                                @if ($_ === 'Status')
                                                    @php
                                                        $statusColors = [
                                                            'completed' => 'bg-emerald-500/15 text-emerald-400',
                                                            'pending' => 'bg-amber-500/15 text-amber-400',
                                                            'failed' => 'bg-red-500/15 text-red-400',
                                                            'active' => 'bg-blue-500/15 text-blue-400',
                                                            'cancelled' => 'bg-slate-500/15 text-slate-400',
                                                            'filled' => 'bg-emerald-500/15 text-emerald-400',
                                                            'buy' => 'bg-emerald-500/15 text-emerald-400',
                                                            'sell' => 'bg-red-500/15 text-red-400',
                                                            'credit' => 'bg-emerald-500/15 text-emerald-400',
                                                            'debit' => 'bg-red-500/15 text-red-400',
                                                        ];
                                                        $sc = $statusColors[$cell] ?? 'bg-slate-500/15 text-slate-400';
                                                    @endphp
                                                    <span
                                                        class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $sc }}">{{ $cell }}</span>
                                                @elseif($_ === 'Type')
                                                    @php $sc = ($cell === 'credit' || $cell === 'buy') ? 'bg-emerald-500/15 text-emerald-400' : 'bg-red-500/15 text-red-400'; @endphp
                                                    <span
                                                        class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $sc }}">{{ $cell }}</span>
                                                @else
                                                    {{ $cell }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($table['columns']) }}"
                                            class="px-4 py-8 text-center text-[10px] text-slate-600 font-mono uppercase tracking-widest">
                                            {{ __('No records found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
        {{-- /recent activity tables --}}
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        (function() {
            'use strict';

            // ── Data from PHP ────────────────────────────────────────────────────────
            const GRAPH_DATA = @json($graph_data);
            const CHART_DATA = @json($chart_data);

            // ── Colour palettes ──────────────────────────────────────────────────────
            const DATASET_META = {
                transactions: {
                    title: 'Transaction History',
                    series: {
                        credit: {
                            label: 'Credit',
                            color: '#34d399'
                        },
                        debit: {
                            label: 'Debit',
                            color: '#f43f5e'
                        },
                    },
                },
                deposits: {
                    title: 'Deposit Volume',
                    series: {
                        completed: {
                            label: 'Completed',
                            color: '#34d399'
                        },
                        pending: {
                            label: 'Pending',
                            color: '#f59e0b'
                        },
                        failed: {
                            label: 'Failed',
                            color: '#f43f5e'
                        },
                        partial_payment: {
                            label: 'Partial Payment',
                            color: '#a855f7'
                        },
                    },
                },
                withdrawals: {
                    title: 'Withdrawal Volume',
                    series: {
                        completed: {
                            label: 'Completed',
                            color: '#34d399'
                        },
                        pending: {
                            label: 'Pending',
                            color: '#f59e0b'
                        },
                        failed: {
                            label: 'Failed',
                            color: '#f43f5e'
                        },
                        partial_payment: {
                            label: 'Partial Payment',
                            color: '#a855f7'
                        },
                    },
                },
                investments: {
                    title: 'Investment Capital',
                    series: {
                        active: {
                            label: 'Active',
                            color: '#34d399'
                        },
                        completed: {
                            label: 'Completed',
                            color: '#818cf8'
                        },
                        cancelled: {
                            label: 'Cancelled',
                            color: '#f43f5e'
                        },
                    },
                },
            };

            const DONUT_COLORS = ['#6366f1', '#f43f5e', '#f59e0b', '#06b6d4', '#a855f7', '#ec4899', '#64748b'];
            const DONUT_LABELS = ['Accounts', 'Futures', 'Margin', 'Forex', 'Stocks', 'ETFs', 'Bonds'];
            const DONUT_KEYS = ['account_balance', 'futures', 'margin', 'forex', 'stocks', 'etfs', 'bonds'];

            // ── Shared Chart.js defaults ─────────────────────────────────────────────
            Chart.defaults.color = 'rgba(148,163,184,0.7)';
            Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
            Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui';
            Chart.defaults.font.size = 10;

            // ── Line chart ───────────────────────────────────────────────────────────
            const lineCtx = document.getElementById('mainLineChart').getContext('2d');
            const lineChart = new Chart(lineCtx, {
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
                        tooltip: {
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
                        },
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

            // ── Donut chart ──────────────────────────────────────────────────────────
            const donutCtx = document.getElementById('equityDonut').getContext('2d');
            const donutChart = new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: DONUT_LABELS,
                    datasets: [{
                        data: DONUT_KEYS.map(k => CHART_DATA[k] || 0),
                        backgroundColor: DONUT_COLORS.map(c => c + 'cc'),
                        borderColor: DONUT_COLORS,
                        borderWidth: 1.5,
                        hoverOffset: 6,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15,23,42,0.95)',
                            borderColor: 'rgba(255,255,255,0.08)',
                            borderWidth: 1,
                            padding: 10,
                        },
                    },
                },
            });

            // ── State ────────────────────────────────────────────────────────────────
            let activeDataset = 'transactions';
            let activePeriod = '7d';

            // ── Render legend ────────────────────────────────────────────────────────
            function renderLegend(meta) {
                const el = document.getElementById('graph-legend');
                el.innerHTML = '';
                Object.entries(meta.series).forEach(([, s]) => {
                    el.insertAdjacentHTML('beforeend',
                        `<span class="flex items-center gap-1.5 text-[10px] text-slate-400">
                    <span style="width:8px;height:8px;border-radius:50%;background:${s.color};display:inline-block;"></span>
                    ${s.label}
                </span>`
                    );
                });
            }

            // ── Update line chart ────────────────────────────────────────────────────
            function updateLineChart() {
                const meta = DATASET_META[activeDataset];
                const periodData = GRAPH_DATA[activeDataset][activePeriod];

                // All series share the same labels
                const firstKey = Object.keys(meta.series)[0];
                const labels = periodData[firstKey]?.labels ?? [];

                lineChart.data.labels = labels;
                lineChart.data.datasets = Object.entries(meta.series).map(([key, s]) => {
                    const raw = periodData[key]?.data ?? [];
                    return {
                        label: s.label,
                        data: raw,
                        borderColor: s.color,
                        backgroundColor: s.color + '18',
                        fill: true,
                        tension: 0.4,
                        pointRadius: labels.length <= 15 ? 3 : 0,
                        pointHoverRadius: 5,
                        borderWidth: 2,
                    };
                });

                document.getElementById('graph-title').textContent = meta.title;
                lineChart.update('active');
                renderLegend(meta);
            }

            // ── Button interaction ───────────────────────────────────────────────────
            document.querySelectorAll('.graph-dataset-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    activeDataset = btn.dataset.dataset;
                    document.querySelectorAll('.graph-dataset-btn').forEach(b => {
                        b.className = b.className
                            .replace(
                                /bg-indigo-500\/20 text-indigo-300 border border-indigo-500\/30/g,
                                '')
                            .replace(/text-slate-500/g, 'text-slate-500')
                            .trim();
                        b.classList.remove('bg-indigo-500/20', 'text-indigo-300', 'border',
                            'border-indigo-500/30');
                        b.classList.add('text-slate-500');
                    });
                    btn.classList.remove('text-slate-500');
                    btn.classList.add('bg-indigo-500/20', 'text-indigo-300', 'border',
                        'border-indigo-500/30');
                    updateLineChart();
                });
            });

            document.querySelectorAll('.graph-period-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    activePeriod = btn.dataset.period;
                    document.querySelectorAll('.graph-period-btn').forEach(b => {
                        b.classList.remove('bg-white/10', 'text-white');
                        b.classList.add('text-slate-500');
                    });
                    btn.classList.remove('text-slate-500');
                    btn.classList.add('bg-white/10', 'text-white');
                    updateLineChart();
                });
            });

            // ── Init ─────────────────────────────────────────────────────────────────
            updateLineChart();
        })();
    </script>
@endpush
