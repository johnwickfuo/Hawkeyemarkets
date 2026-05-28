@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="relative min-h-screen space-y-12 pb-20">

        {{-- Page Header --}}
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="h-1 w-8 bg-gradient-to-r from-violet-500 to-cyan-500 rounded-full"></div>
                    <span
                        class="text-[10px] font-black text-violet-400 uppercase tracking-[0.3em] font-mono">{{ __('Node: Mainnet-Beta') }}</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter uppercase italic italic-none">
                    {{ __('Trading') }} <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-cyan-400">{{ __('Hub') }}</span>
                </h1>
            </div>
            <div class="flex items-center gap-4 bg-white/5 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/10">
                <div class="text-right">
                    <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest">{{ __('Total Net Worth') }}</p>
                    <p class="text-2xl font-black text-white font-mono tracking-tighter">
                        {{ number_format($total_networth, getSetting('decimail_places')) }} <span
                            class="text-violet-400 font-sans text-xs italic">{{ getSetting('currency') }}</span>
                    </p>
                </div>
                <div
                    class="w-10 h-10 rounded-xl bg-violet-500/20 flex items-center justify-center border border-violet-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-violet-400">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Main Account Verticals --}}
        <div class="relative z-10 flex flex-col gap-6">
            @foreach ([
            'fiat' => [
                'label' => 'Primary Wallet',
                'type' => 'fiat',
                'color' => 'indigo',
                'desc' => 'Your main secure account for deposits, withdrawals, and holding protocol liquidity.',
            ],
            'futures' => [
                'label' => 'Futures Trading',
                'type' => 'futures',
                'color' => 'cyan',
                'desc' => 'High-leverage perpetual contracts with institutional liquidity and sub-millisecond execution.',
            ],
            'margin' => [
                'label' => 'Margin Trading',
                'type' => 'margin',
                'color' => 'amber',
                'desc' => 'Cross-margin and isolated-margin protocols for amplified capital efficiency across 100+ pairs.',
            ],
            'forex_live' => [
                'label' => 'Forex Trading (Live)',
                'type' => 'forex',
                'mode' => 'live',
                'color' => 'emerald',
                'desc' => 'Direct ECN market access for major, minor, and exotic currency pairs with raw spreads.',
            ],
            'forex_demo' => [
                'label' => 'Forex Trading (Demo)',
                'type' => 'forex',
                'mode' => 'demo',
                'color' => 'blue',
                'desc' => 'Risk-free simulated environment for protocol testing and strategy refinement with real market data.',
            ],
        ] as $key => $data)
                @if (
                    ($key === 'futures' && !moduleEnabled('futures_module')) ||
                        ($key === 'margin' && !moduleEnabled('margin_module')) ||
                        (str_starts_with($key, 'forex') && !moduleEnabled('forex_module')))
                    @continue
                @endif
                @php
                    $account = $trading_accounts[$key] ?? null;
                    $type = $data['type'];
                    $color = $data['color'];
                    $label = $data['label'];
                    $desc = $data['desc'];
                    $mode = $data['mode'] ?? null;

                    // Predefined color maps for Tailwind Static Analysis
                    $colorMaps = [
                        'indigo' => [
                            'border_hover' => 'hover:border-indigo-500/20',
                            'ambient_gradient' => 'from-indigo-500/0 via-indigo-500/40 to-indigo-500/0',
                            'icon_bg' => 'bg-indigo-500/10',
                            'icon_border' => 'border-indigo-500/20',
                            'icon_text' => 'text-indigo-400',
                            'icon_bg_hover' => 'group-hover:bg-indigo-500/20',
                            'icon_shadow' => 'shadow-indigo-500/10',
                            'text_color' => 'text-indigo-400',
                            'btn_bg' => 'from-indigo-500 to-indigo-600',
                            'btn_shadow' => 'hover:shadow-[0_0_20px_rgba(79,70,229,0.3)]',
                        ],
                        'cyan' => [
                            'border_hover' => 'hover:border-cyan-500/20',
                            'ambient_gradient' => 'from-cyan-500/0 via-cyan-500/40 to-cyan-500/0',
                            'icon_bg' => 'bg-cyan-500/10',
                            'icon_border' => 'border-cyan-500/20',
                            'icon_text' => 'text-cyan-400',
                            'icon_bg_hover' => 'group-hover:bg-cyan-500/20',
                            'icon_shadow' => 'shadow-cyan-500/10',
                            'text_color' => 'text-cyan-400',
                            'btn_bg' => 'from-cyan-500 to-cyan-600',
                            'btn_shadow' => 'hover:shadow-[0_0_20px_rgba(6,182,212,0.3)]',
                        ],
                        'amber' => [
                            'border_hover' => 'hover:border-amber-500/20',
                            'ambient_gradient' => 'from-amber-500/0 via-amber-500/40 to-amber-500/0',
                            'icon_bg' => 'bg-amber-500/10',
                            'icon_border' => 'border-amber-500/20',
                            'icon_text' => 'text-amber-400',
                            'icon_bg_hover' => 'group-hover:bg-amber-500/20',
                            'icon_shadow' => 'shadow-amber-500/10',
                            'text_color' => 'text-amber-400',
                            'btn_bg' => 'from-amber-500 to-amber-600',
                            'btn_shadow' => 'hover:shadow-[0_0_20px_rgba(245,158,11,0.3)]',
                        ],
                        'emerald' => [
                            'border_hover' => 'hover:border-emerald-500/20',
                            'ambient_gradient' => 'from-emerald-500/0 via-emerald-500/40 to-emerald-500/0',
                            'icon_bg' => 'bg-emerald-500/10',
                            'icon_border' => 'border-emerald-500/20',
                            'icon_text' => 'text-emerald-400',
                            'icon_bg_hover' => 'group-hover:bg-emerald-500/20',
                            'icon_shadow' => 'shadow-emerald-500/10',
                            'text_color' => 'text-emerald-400',
                            'btn_bg' => 'from-emerald-500 to-emerald-600',
                            'btn_shadow' => 'hover:shadow-[0_0_20px_rgba(16,185,129,0.3)]',
                        ],
                        'blue' => [
                            'border_hover' => 'hover:border-blue-500/20',
                            'ambient_gradient' => 'from-blue-500/0 via-blue-500/40 to-blue-500/0',
                            'icon_bg' => 'bg-blue-500/10',
                            'icon_border' => 'border-blue-500/20',
                            'icon_text' => 'text-blue-400',
                            'icon_bg_hover' => 'group-hover:bg-blue-500/20',
                            'icon_shadow' => 'shadow-blue-500/10',
                            'text_color' => 'text-blue-400',
                            'btn_bg' => 'from-blue-500 to-blue-600',
                            'btn_shadow' => 'hover:shadow-[0_0_20px_rgba(59,130,246,0.3)]',
                        ],
                    ];

                    $cm = $colorMaps[$color] ?? $colorMaps['indigo'];

                    $icon = match ($type) {
                        'fiat'
                            => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><line x1="12" y1="15" x2="12" y2="15"/></svg>',
                        'futures'
                            => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>',
                        'margin'
                            => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>',
                        'forex' => $mode === 'live'
                            ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="21" x2="21" y2="21"/><line x1="3" y1="7" x2="21" y2="7"/><polyline points="3 7 12 2 21 7"/><line x1="5" y1="21" x2="5" y2="7"/><line x1="9" y1="21" x2="9" y2="7"/><line x1="15" y1="21" x2="15" y2="7"/><line x1="19" y1="21" x2="19" y2="7"/></svg>'
                            : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.31"/><path d="M14 9.3V2"/><path d="M8.5 2h7"/><path d="M14 9.3a6.5 6.5 0 1 1-4 0"/><path d="M5.52 16h12.96"/></svg>',
                    };
                @endphp

                <div
                    class="group relative bg-[#0A0A0E]/40 backdrop-blur-3xl border border-white/5 rounded-[2rem] overflow-hidden transition-all duration-500 hover:bg-[#0A0A0E]/60 {{ $cm['border_hover'] }}">
                    {{-- Ambient Light --}}
                    <div
                        class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b {{ $cm['ambient_gradient'] }} opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <div class="relative p-8 flex flex-col lg:flex-row lg:items-center gap-8">
                        {{-- Icon & Status --}}
                        <div class="flex items-center gap-6 lg:w-1/4">
                            <div
                                class="w-16 h-16 rounded-2xl {{ $cm['icon_bg'] }} border {{ $cm['icon_border'] }} flex items-center justify-center {{ $cm['icon_text'] }} {{ $cm['icon_bg_hover'] }} transition-all duration-500 {{ $cm['icon_shadow'] }} shadow-lg shrink-0">
                                {!! $icon !!}
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-white tracking-tighter uppercase italic">
                                    {{ __($label) }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <div
                                        class="w-1.5 h-1.5 rounded-full {{ $type === 'fiat'
                                            ? 'bg-emerald-400 animate-pulse'
                                            : ($account
                                                ? match ($account->account_status) {
                                                    'active' => 'bg-emerald-400 animate-pulse',
                                                    'suspended' => 'bg-red-400',
                                                    'closed' => 'bg-white/10',
                                                    default => 'bg-amber-400',
                                                }
                                                : 'bg-white/20') }}">
                                    </div>
                                    <span
                                        class="text-[9px] font-black uppercase tracking-[0.2em] {{ $type === 'fiat'
                                            ? 'text-emerald-400'
                                            : ($account
                                                ? match ($account->account_status) {
                                                    'active' => 'text-emerald-400',
                                                    'suspended' => 'text-red-400',
                                                    'closed' => 'text-white/20',
                                                    default => 'text-amber-400',
                                                }
                                                : 'text-white/20') }}">
                                        {{ $type === 'fiat' ? __('Protocol Active') : ($account ? ucfirst(__($account->account_status)) : __('Unauthorized')) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Metadata --}}
                        <div class="lg:flex-1">
                            <p class="text-white/40 text-xs leading-relaxed font-medium lg:max-w-md italic">
                                {{ __($desc) }}
                            </p>
                        </div>

                        {{-- Balance Section --}}
                        <div class="lg:w-1/4 lg:text-right">
                            <p class="text-[9px] font-black text-white/20 uppercase tracking-[0.3em] mb-1">
                                {{ __('Portfolio Value') }}</p>
                            <p
                                class="text-2xl font-black text-white font-mono tracking-tighter {{ $type === 'fiat' || $account ? '' : 'opacity-10' }}">
                                {{ number_format($type === 'fiat' ? $user->balance : $account->balance ?? 0.0, 2) }}
                                <span
                                    class="{{ $cm['text_color'] }} font-sans text-xs italic">{{ $type === 'fiat' ? getSetting('currency') : $account->currency ?? 'USD' }}</span>
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="lg:w-1/5 flex gap-3">
                            @if ($type === 'fiat')
                                <a href="{{ route('user.deposits.index') }}"
                                    class="flex-1 flex items-center justify-center gap-2 py-4 rounded-xl bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all active:scale-95 cursor-pointer">
                                    <span>{{ __('Deposit') }}</span>
                                </a>
                                <a href="javascript:void(0)"
                                    @if (!$has_trading_account) title="{{ __('Create a trading account to enable transfers') }}" 
                                        class="flex-1 flex items-center justify-center gap-2 py-4 rounded-xl bg-white/5 border border-white/10 text-white/20 text-[10px] font-black uppercase tracking-widest cursor-not-allowed"
                                    @else
                                        onclick="openTransferModal()"
                                        class="flex-1 flex items-center justify-center gap-2 py-4 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white text-[10px] font-black uppercase tracking-widest hover:shadow-[0_0_20px_rgba(79,70,229,0.3)] transition-all active:scale-95 cursor-pointer" @endif>
                                    <span>{{ __('Transfer') }}</span>
                                </a>
                            @elseif ($account)
                                @if ($account->account_status === 'active')
                                    @php
                                        $account_type = $account->account_type;
                                        $terminal_url =
                                            $account_type === 'forex'
                                                ? route('user.trading.forex.' . $mode)
                                                : route('user.trading.' . $account_type);
                                    @endphp

                                    <a href="{{ $terminal_url }}"
                                        class="flex-1 flex items-center justify-center gap-2 py-4 rounded-xl bg-white/5 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all active:scale-95 cursor-pointer">
                                        <span>{{ __('Terminal') }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="m12 5 7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <div
                                        class="flex-1 flex items-center justify-center gap-2 py-4 rounded-xl bg-white/5 border border-white/10 text-white/20 text-[10px] font-black uppercase tracking-widest cursor-not-allowed">
                                        <span>{{ __('Terminal Locked') }}</span>
                                    </div>
                                @endif
                            @else
                                <button
                                    onclick="openCreateModal('{{ $type }}', '{{ $label }}', '{{ $mode }}')"
                                    class="flex-1 flex items-center justify-center gap-2 py-4 rounded-xl bg-gradient-to-r {{ $cm['btn_bg'] }} text-white text-[10px] font-black uppercase tracking-widest {{ $cm['btn_shadow'] }} transition-all active:scale-95 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 5v14" />
                                        <path d="M5 12h14" />
                                    </svg>
                                    <span>{{ __('Initialize') }}</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Account Activity Ledger --}}
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="h-10 w-1 bg-violet-500 rounded-full shadow-[0_0_15px_rgba(139,92,246,0.5)]"></div>
                <h2 class="text-3xl font-black text-white tracking-tighter uppercase italic">{{ __('Live Activity') }}
                    <span class="text-white/20 font-mono italic-none ml-2 text-xl tracking-normal">[{ ledger_v1
                        }]</span>
                </h2>
            </div>

            <div class="bg-[#0A0A0E]/40 backdrop-blur-2xl border border-white/5 rounded-[2.5rem] overflow-hidden p-6">
                {{-- Tabs --}}
                <div class="flex flex-wrap items-center gap-2 mb-6">
                    @php $firstTab = null; @endphp
                    @if (moduleEnabled('futures_module'))
                        @php $firstTab = $firstTab ?? 'futures'; @endphp
                        <button data-target="#tab-futures"
                            class="ledger-tab cursor-pointer {{ $firstTab === 'futures' ? 'bg-cyan-500/20 border border-cyan-500/30 text-white shadow-lg shadow-cyan-500/10 font-bold' : 'bg-white/5 border border-white/10 text-white/50 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl px-4 py-2 text-sm transition-all">{{ __('Futures') }}</button>
                    @endif
                    @if (moduleEnabled('margin_module'))
                        @php $firstTab = $firstTab ?? 'margin'; @endphp
                        <button data-target="#tab-margin"
                            class="ledger-tab cursor-pointer {{ $firstTab === 'margin' ? 'bg-cyan-500/20 border border-cyan-500/30 text-white shadow-lg shadow-cyan-500/10 font-bold' : 'bg-white/5 border border-white/10 text-white/50 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl px-4 py-2 text-sm transition-all">{{ __('Margin') }}</button>
                    @endif
                    @if (moduleEnabled('forex_module'))
                        @php $firstTab = $firstTab ?? 'forex_live'; @endphp
                        <button data-target="#tab-forex-live"
                            class="ledger-tab cursor-pointer {{ $firstTab === 'forex_live' ? 'bg-cyan-500/20 border border-cyan-500/30 text-white shadow-lg shadow-cyan-500/10 font-bold' : 'bg-white/5 border border-white/10 text-white/50 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl px-4 py-2 text-sm transition-all">{{ __('Forex Live') }}</button>
                        <button data-target="#tab-forex-demo"
                            class="ledger-tab bg-white/5 border border-white/10 text-white/50 hover:text-white hover:bg-white/10 font-medium rounded-xl px-4 py-2 text-sm transition-all">{{ __('Forex Demo') }}</button>
                    @endif
                </div>

                <div>
                    @if (moduleEnabled('futures_module'))
                        {{-- Futures Tab --}}
                        <div id="tab-futures" class="ledger-content {{ $firstTab === 'futures' ? '' : 'hidden' }}">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="text-xs font-bold text-white/40 uppercase tracking-widest border-b border-white/5">
                                            <th class="py-4 px-4">{{ __('Time') }}</th>
                                            <th class="py-4 px-4">{{ __('Symbol') }}</th>
                                            <th class="py-4 px-4">{{ __('Type') }}</th>
                                            <th class="py-4 px-4">{{ __('Side') }}</th>
                                            <th class="py-4 px-4 text-right">{{ __('Size') }}</th>
                                            <th class="py-4 px-4 text-right">{{ __('Price') }}</th>
                                            <th class="py-4 px-4 text-right">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        @forelse ($futuresOrders as $order)
                                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                                                <td class="py-4 px-4 text-white/60 font-mono text-xs">
                                                    {{ $order->created_at ? $order->created_at->diffForHumans() : __('N/A') }}
                                                </td>
                                                <td class="py-4 px-4 font-bold text-white">{{ $order->ticker }}</td>
                                                <td class="py-4 px-4 text-white/60">{{ ucfirst($order->type) }}</td>
                                                <td class="py-4 px-4">
                                                    <span
                                                        class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-wider {{ $order->side === 'buy' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                                        {{ $order->side }}
                                                    </span>
                                                </td>
                                                <td class="py-4 px-4 text-right font-mono text-white/80">
                                                    {{ number_format($order->size, 4) }}</td>
                                                <td class="py-4 px-4 text-right font-mono text-white/80">
                                                    ${{ number_format($order->price, 2) }}</td>
                                                <td class="py-4 px-4 text-right">
                                                    <span class="text-white/60">{{ ucfirst($order->status) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="py-8 text-center text-white/30 italic">
                                                    {{ __('No Futures history found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if (moduleEnabled('margin_module'))
                        {{-- Margin Tab --}}
                        <div id="tab-margin" class="ledger-content {{ $firstTab === 'margin' ? '' : 'hidden' }}">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="text-xs font-bold text-white/40 uppercase tracking-widest border-b border-white/5">
                                            <th class="py-4 px-4">{{ __('Time') }}</th>
                                            <th class="py-4 px-4">{{ __('Symbol') }}</th>
                                            <th class="py-4 px-4">{{ __('Type') }}</th>
                                            <th class="py-4 px-4">{{ __('Side') }}</th>
                                            <th class="py-4 px-4 text-right">{{ __('Size') }}</th>
                                            <th class="py-4 px-4 text-right">{{ __('Price') }}</th>
                                            <th class="py-4 px-4 text-right">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        @forelse ($marginOrders as $order)
                                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                                                <td class="py-4 px-4 text-white/60 font-mono text-xs">
                                                    {{ $order->created_at ? $order->created_at->diffForHumans() : __('N/A') }}
                                                </td>
                                                <td class="py-4 px-4 font-bold text-white">{{ $order->ticker }}</td>
                                                <td class="py-4 px-4 text-white/60">{{ ucfirst($order->type) }}</td>
                                                <td class="py-4 px-4">
                                                    <span
                                                        class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-wider {{ $order->side === 'buy' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                                        {{ $order->side }}
                                                    </span>
                                                </td>
                                                <td class="py-4 px-4 text-right font-mono text-white/80">
                                                    {{ number_format($order->size, 4) }}</td>
                                                <td class="py-4 px-4 text-right font-mono text-white/80">
                                                    ${{ number_format($order->price, 2) }}</td>
                                                <td class="py-4 px-4 text-right">
                                                    <span class="text-white/60">{{ ucfirst($order->status) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="py-8 text-center text-white/30 italic">
                                                    {{ __('No Margin history found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if (moduleEnabled('forex_module'))
                        {{-- Forex Live Tab --}}
                        <div id="tab-forex-live" class="ledger-content {{ $firstTab === 'forex_live' ? '' : 'hidden' }}">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="text-xs font-bold text-white/40 uppercase tracking-widest border-b border-white/5">
                                            <th class="py-4 px-4">{{ __('Time') }}</th>
                                            <th class="py-4 px-4">{{ __('Symbol') }}</th>
                                            <th class="py-4 px-4">{{ __('Type') }}</th>
                                            <th class="py-4 px-4">{{ __('Side') }}</th>
                                            <th class="py-4 px-4 text-right">{{ __('Volume') }}</th>
                                            <th class="py-4 px-4 text-right">{{ __('Price') }}</th>
                                            <th class="py-4 px-4 text-right">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        @forelse ($forexLiveOrders as $order)
                                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                                                <td class="py-4 px-4 text-white/60 font-mono text-xs">
                                                    {{ $order->created_at ? $order->created_at->diffForHumans() : __('N/A') }}
                                                </td>
                                                <td class="py-4 px-4 font-bold text-white">
                                                    {{ str_replace('_', '/', $order->symbol) }}</td>
                                                <td class="py-4 px-4 text-white/60">{{ ucfirst($order->order_type) }}</td>
                                                <td class="py-4 px-4">
                                                    <span
                                                        class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-wider {{ $order->type === 'Buy' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                                        {{ $order->type }}
                                                    </span>
                                                </td>
                                                <td class="py-4 px-4 text-right font-mono text-white/80">
                                                    {{ number_format($order->volume, 2) }}</td>
                                                <td class="py-4 px-4 text-right font-mono text-white/80">
                                                    {{ number_format($order->price, 5) }}</td>
                                                <td class="py-4 px-4 text-right">
                                                    <span class="text-white/60">{{ ucfirst($order->status) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="py-8 text-center text-white/30 italic">
                                                    {{ __('No Forex Live history found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Forex Demo Tab --}}
                        <div id="tab-forex-demo" class="ledger-content hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="text-xs font-bold text-white/40 uppercase tracking-widest border-b border-white/5">
                                            <th class="py-4 px-4">{{ __('Time') }}</th>
                                            <th class="py-4 px-4">{{ __('Symbol') }}</th>
                                            <th class="py-4 px-4">{{ __('Type') }}</th>
                                            <th class="py-4 px-4">{{ __('Side') }}</th>
                                            <th class="py-4 px-4 text-right">{{ __('Volume') }}</th>
                                            <th class="py-4 px-4 text-right">{{ __('Price') }}</th>
                                            <th class="py-4 px-4 text-right">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        @forelse ($forexDemoOrders as $order)
                                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                                                <td class="py-4 px-4 text-white/60 font-mono text-xs">
                                                    {{ $order->created_at ? $order->created_at->diffForHumans() : __('N/A') }}
                                                </td>
                                                <td class="py-4 px-4 font-bold text-white">
                                                    {{ str_replace('_', '/', $order->symbol) }}</td>
                                                <td class="py-4 px-4 text-white/60">{{ ucfirst($order->order_type) }}
                                                </td>
                                                <td class="py-4 px-4">
                                                    <span
                                                        class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-wider {{ $order->type === 'Buy' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                                        {{ $order->type }}
                                                    </span>
                                                </td>
                                                <td class="py-4 px-4 text-right font-mono text-white/80">
                                                    {{ number_format($order->volume, 2) }}</td>
                                                <td class="py-4 px-4 text-right font-mono text-white/80">
                                                    {{ number_format($order->price, 5) }}</td>
                                                <td class="py-4 px-4 text-right">
                                                    <span class="text-white/60">{{ ucfirst($order->status) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="py-8 text-center text-white/30 italic">
                                                    {{ __('No Forex Demo history found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Ledger Tabs Script --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tabs = document.querySelectorAll('.ledger-tab');
                const contents = document.querySelectorAll('.ledger-content');

                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        // Deactivate all tabs
                        tabs.forEach(t => {
                            t.classList.remove('bg-cyan-500/20', 'border-cyan-500/30',
                                'text-white', 'shadow-lg', 'shadow-cyan-500/10', 'font-bold'
                            );
                            t.classList.add('bg-white/5', 'border-white/10', 'text-white/50',
                                'font-medium');
                        });

                        // Activate clicked tab
                        tab.classList.remove('bg-white/5', 'border-white/10', 'text-white/50',
                            'font-medium');
                        tab.classList.add('bg-cyan-500/20', 'border-cyan-500/30', 'text-white',
                            'shadow-lg', 'shadow-cyan-500/10', 'font-bold');

                        // Show target content
                        const target = tab.dataset.target;
                        contents.forEach(content => {
                            content.classList.add('hidden');
                        });
                        document.querySelector(target).classList.remove('hidden');
                    });
                });
            });
        </script>
    </div>

    {{-- Account Creation Modal (Web3 Theme) --}}
    <div id="createAccountModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/90 backdrop-blur-xl transition-opacity duration-500 opacity-0"
            id="modalBackdrop"></div>
        <div class="relative min-h-full flex items-center justify-center p-6">
            <div class="w-full max-w-lg bg-[#0E0E12] border border-white/10 rounded-[2.5rem] shadow-[0_0_100px_-20px_rgba(0,0,0,1)] transform scale-95 opacity-0 transition-all duration-500 overflow-hidden"
                id="modalContent">

                {{-- Decorative scanlines --}}
                <div
                    class="absolute inset-0 pointer-events-none opacity-5 bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.06),rgba(0,255,0,0.02),rgba(0,0,255,0.06))] z-0 bg-[length:100%_4px,3px_100%]">
                </div>

                <div class="relative z-10 flex flex-col">
                    <div class="px-10 py-8 border-b border-white/5 flex justify-between items-center">
                        <div>
                            <h3 class="text-3xl font-black text-white tracking-tighter uppercase italic" id="modalTitle">
                                {{ __('Open Node') }}</h3>
                            <p class="text-[10px] font-mono text-white/30 uppercase tracking-[0.2em] mt-1">
                                {{ __('Request access to trading protocol') }}</p>
                        </div>
                        <button
                            class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white/40 hover:bg-white/10 hover:text-white transition-all cursor-pointer close-modal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="createAccountForm" method="POST" action="{{ route('user.trading.account.store') }}"
                        class="ajax-form p-10 space-y-8" data-action="reload">
                        @csrf
                        <input type="hidden" name="account_type" id="formAccountType">
                        <input type="hidden" name="mode" id="formAccountMode">
                        <input type="hidden" name="currency" value="USDT">

                        <div class="space-y-6">
                            {{-- Onboarding & Risks --}}
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-6 bg-violet-500 rounded-full"></div>
                                    <h4 class="text-xs font-black text-white uppercase tracking-[0.2em] font-mono">
                                        {{ __('Onboarding & Risk Protocol') }}</h4>
                                </div>

                                <div class="grid gap-3">
                                    <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5 space-y-2">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                                class="text-violet-400">
                                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10" />
                                            </svg>
                                            <span
                                                class="text-[10px] font-black text-white uppercase tracking-wider">{{ __('Asset Security') }}</span>
                                        </div>
                                        <p class="text-[11px] text-white/40 leading-relaxed">
                                            {{ __('Your assets are secured via institutional-grade MPC (Multi-Party Computation) and isolated sub-accounts.') }}
                                        </p>
                                    </div>

                                    <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5 space-y-2">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                                class="text-amber-400">
                                                <path
                                                    d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                                                <path d="M12 9v4" />
                                                <path d="M12 17h.01" />
                                            </svg>
                                            <span id="riskTitle"
                                                class="text-[10px] font-black text-white uppercase tracking-wider">{{ __('Market Volatility') }}</span>
                                        </div>
                                        <p id="riskDesc" class="text-[11px] text-white/40 leading-relaxed">
                                            {{ __('Trading involves significant risk. Leveraged positions may be liquidated if market prices hit the liquidation threshold.') }}
                                        </p>
                                    </div>

                                    <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5 space-y-2">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                                class="text-cyan-400">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                <path d="M14 2v6h6" />
                                                <path d="M16 13H8" />
                                                <path d="M16 17H8" />
                                                <path d="M10 9H8" />
                                            </svg>
                                            <span
                                                class="text-[10px] font-black text-white uppercase tracking-wider">{{ __('User Agreement') }}</span>
                                        </div>
                                        <p class="text-[11px] text-white/40 leading-relaxed">
                                            {{ __('By continuing, you acknowledge that you have read and accepted the global trading risk disclosure and terms of service.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-6 rounded-[1.5rem] bg-gradient-to-r from-violet-500 to-cyan-500 text-white text-[13px] font-black uppercase tracking-[0.3em] font-mono shadow-[0_20px_40px_-10px_rgba(139,92,246,0.4)] transition-all hover:scale-[1.02] hover:shadow-[0_25px_50px_-10px_rgba(139,92,246,0.6)] active:scale-95 group cursor-pointer">
                            {{ __('Confirm') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Transfer Modal (Liquidity Hub) --}}
    <div id="transferModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/90 backdrop-blur-xl transition-opacity duration-500 opacity-0"
            id="transferModalBackdrop"></div>
        <div class="relative min-h-full flex items-center justify-center p-6">
            <div class="w-full max-w-lg bg-[#0E0E12] border border-white/10 rounded-[2.5rem] shadow-[0_0_100px_-20px_rgba(0,0,0,1)] transform scale-95 opacity-0 transition-all duration-500 overflow-hidden"
                id="transferModalContent">

                {{-- Decorative scanlines --}}
                <div
                    class="absolute inset-0 pointer-events-none opacity-5 bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.06),rgba(0,255,0,0.02),rgba(0,0,255,0.06))] z-0 bg-[length:100%_4px,3px_100%]">
                </div>

                <div class="relative z-10 flex flex-col">
                    <div class="px-10 py-8 border-b border-white/5 flex justify-between items-center">
                        <div>
                            <h3 class="text-3xl font-black text-white tracking-tighter uppercase italic">
                                {{ __('Liquidity Hub') }}</h3>
                            <p class="text-[10px] font-mono text-white/30 uppercase tracking-[0.2em] mt-1">
                                {{ __('Internal Protocol Transfer') }}</p>
                        </div>
                        <button onclick="closeTransferModal()"
                            class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white/40 hover:bg-white/10 hover:text-white transition-all cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="transferForm" method="POST" action="{{ route('user.trading.account.transfer') }}"
                        class="ajax-form p-10 space-y-6" data-action="reload">
                        @csrf

                        <div class="space-y-4">
                            {{-- From Account --}}
                            <div class="relative custom-dropdown" id="dropdownFrom"
                                data-selected-balance="{{ $user->balance }}">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-3 block">
                                    {{ __('From Account') }}
                                </label>
                                <input type="hidden" name="from_type" value="fiat">
                                <button type="button"
                                    class="dropdown-trigger w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold flex items-center justify-between hover:border-white/20 transition-all outline-none">
                                    <span class="selected-text">{{ __('Primary Wallet') }}
                                        ({{ number_format($user->balance, 2) }} {{ getSetting('currency') }})</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="text-white/20 transition-transform duration-300">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>
                                <div
                                    class="dropdown-options hidden absolute left-0 right-0 mt-2 bg-[#16161D] border border-white/10 rounded-2xl shadow-2xl z-[110] overflow-hidden max-h-60 overflow-y-auto backdrop-blur-xl">
                                    <div class="option px-6 py-4 hover:bg-white/5 cursor-pointer transition-colors border-b border-white/5"
                                        data-value="fiat" data-balance="{{ $user->balance }}">
                                        <div class="font-bold text-white text-sm">{{ __('Primary Wallet') }}</div>
                                        <div class="text-[10px] text-white/40 uppercase tracking-wider">
                                            {{ number_format($user->balance, 2) }} {{ getSetting('currency') }}</div>
                                    </div>
                                    @foreach ($trading_accounts as $key => $acc)
                                        @if ($acc && $key !== 'forex_demo')
                                            @if (
                                                ($key === 'futures' && !moduleEnabled('futures_module')) ||
                                                    ($key === 'margin' && !moduleEnabled('margin_module')) ||
                                                    (str_starts_with($key, 'forex') && !moduleEnabled('forex_module')))
                                                @continue
                                            @endif
                                            <div class="option px-6 py-4 hover:bg-white/5 cursor-pointer transition-colors border-b border-white/5"
                                                data-value="{{ $key }}" data-balance="{{ $acc->balance }}">
                                                <div class="font-bold text-white text-sm">
                                                    {{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                                                <div class="text-[10px] text-white/40 uppercase tracking-wider">
                                                    {{ number_format($acc->balance, 2) }} {{ $acc->currency }}</div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            {{-- To Account --}}
                            <div class="relative custom-dropdown" id="dropdownTo">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-3 block">
                                    {{ __('To Account') }}
                                </label>
                                <input type="hidden" name="to_type" value="">
                                <button type="button"
                                    class="dropdown-trigger w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold flex items-center justify-between hover:border-white/20 transition-all outline-none">
                                    <span class="selected-text text-white/20">{{ __('Select Destination') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="text-white/20 transition-transform duration-300">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>
                                <div
                                    class="dropdown-options hidden absolute left-0 right-0 mt-2 bg-[#16161D] border border-white/10 rounded-2xl shadow-2xl z-[110] overflow-hidden max-h-60 overflow-y-auto backdrop-blur-xl">
                                    <div class="option px-6 py-4 hover:bg-white/5 cursor-pointer transition-colors border-b border-white/5"
                                        data-value="fiat">
                                        <div class="font-bold text-white text-sm">{{ __('Primary Wallet') }}</div>
                                    </div>
                                    @foreach ($trading_accounts as $key => $acc)
                                        @if ($acc)
                                            @if (
                                                ($key === 'futures' && !moduleEnabled('futures_module')) ||
                                                    ($key === 'margin' && !moduleEnabled('margin_module')) ||
                                                    (str_starts_with($key, 'forex') && !moduleEnabled('forex_module')))
                                                @continue
                                            @endif
                                            <div class="option px-6 py-4 hover:bg-white/5 cursor-pointer transition-colors border-b border-white/5"
                                                data-value="{{ $key }}">
                                                <div class="font-bold text-white text-sm">
                                                    {{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            {{-- Amount --}}
                            <div class="relative">
                                <label class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-3 block">
                                    {{ __('Amount to Transfer') }}
                                </label>
                                <input type="number" name="amount" step="0.01" min="0.01" required
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 pr-20 py-4 text-white font-mono text-base focus:border-violet-500/50 transition-all outline-none placeholder:text-white/10"
                                    placeholder="0.00">
                                <button type="button" onclick="setMaxTransfer()"
                                    class="absolute right-4 bottom-4 z-20 text-[9px] font-black text-violet-400 uppercase tracking-widest hover:text-violet-300 transition-colors cursor-pointer bg-white/5 px-2 py-1 rounded-md border border-white/5">
                                    {{ __('MAX') }}
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-6 rounded-[1.5rem] bg-gradient-to-r from-violet-500 to-cyan-500 text-white text-[13px] font-black uppercase tracking-[0.3em] font-mono shadow-[0_20px_40px_-10px_rgba(139,92,246,0.4)] transition-all hover:scale-[1.02] hover:shadow-[0_25px_50px_-10px_rgba(139,92,246,0.6)] active:scale-95 group cursor-pointer">
                            {{ __('Execute Transfer') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const riskProfiles = {
            'futures': {
                'title': '{{ __('Derivative Risk') }}',
                'desc': '{{ __('Perpetual contracts involve high leverage. Small price movements can lead to rapid liquidation of your entire collateral.') }}'
            },
            'margin': {
                'title': '{{ __('Lending & Collateral') }}',
                'desc': '{{ __('Isolated margin involves borrowing assets. You are responsible for interest accrual and maintaining collateral ratios to avoid forced closure.') }}'
            },
            'forex_live': {
                'title': '{{ __('ECN Market Liquidity') }}',
                'desc': '{{ __('Live Forex trading involves real capital exposure. High volatility events can lead to slippage and execution at significantly different prices.') }}'
            },
            'forex_demo': {
                'title': '{{ __('Simulation Protocol') }}',
                'desc': '{{ __('This is a risk-free environment using virtual funds. Performance in demo does not guarantee identical results in live market conditions.') }}'
            }
        };

        function openCreateModal(type, label, mode = null) {
            $('#formAccountType').val(type);
            $('#formAccountMode').val(mode);
            $('#modalTitle').text('{{ __('Setup') }} ' + label);

            // Update Risk Profile
            const key = type === 'forex' ? `forex_${mode}` : type;
            const profile = riskProfiles[key];
            if (profile) {
                $('#riskTitle').text(profile.title);
                $('#riskDesc').text(profile.desc);
            } else {
                $('#riskTitle').text('{{ __('Risk Protocol') }}');
                $('#riskDesc').text('{{ __('Please review all protocol risks before proceeding.') }}');
            }

            const modal = $('#createAccountModal');
            const backdrop = $('#modalBackdrop');
            const content = $('#modalContent');

            modal.removeClass('hidden');
            setTimeout(() => {
                backdrop.removeClass('opacity-0').addClass('opacity-100');
                content.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
            }, 10);
        }

        function closeModal() {
            const backdrop = $('#modalBackdrop');
            const content = $('#modalContent');

            backdrop.removeClass('opacity-100').addClass('opacity-0');
            content.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');

            setTimeout(() => {
                $('#createAccountModal').addClass('hidden');
            }, 500);
        }

        $('.close-modal').on('click', closeModal);
        $('#modalBackdrop').on('click', closeModal);

        // Prevent modal content clicks from closing the modal
        $('#modalContent').on('click', function(e) {
            e.stopPropagation();
        });

        // Transfer Modal Logic
        function openTransferModal() {
            const modal = $('#transferModal');
            const backdrop = $('#transferModalBackdrop');
            const content = $('#transferModalContent');

            modal.removeClass('hidden');
            setTimeout(() => {
                backdrop.removeClass('opacity-0').addClass('opacity-100');
                content.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
            }, 10);
        }

        function closeTransferModal() {
            const backdrop = $('#transferModalBackdrop');
            const content = $('#transferModalContent');

            backdrop.removeClass('opacity-100').addClass('opacity-0');
            content.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');

            setTimeout(() => {
                $('#transferModal').addClass('hidden');
            }, 500);
        }

        $('#transferModalBackdrop').on('click', closeTransferModal);
        $('#transferModalContent').on('click', function(e) {
            e.stopPropagation();
        });

        // Custom Dropdown Logic
        $('.custom-dropdown').each(function() {
            const dropdown = $(this);
            const trigger = dropdown.find('.dropdown-trigger');
            const options = dropdown.find('.dropdown-options');
            const input = dropdown.find('input[type="hidden"]');
            const selectedText = dropdown.find('.selected-text');
            const icon = dropdown.find('svg');

            trigger.on('click', function(e) {
                e.stopPropagation();
                $('.dropdown-options').not(options).addClass('hidden');
                $('.dropdown-trigger svg').not(icon).removeClass('rotate-180');

                options.toggleClass('hidden');
                icon.toggleClass('rotate-180');
            });

            options.find('.option').on('click', function() {
                const val = $(this).data('value');
                const text = $(this).find('div:first-child').text();
                const balance = $(this).data('balance');
                const otherDropdownId = dropdown.attr('id') === 'dropdownFrom' ? '#dropdownTo' :
                    '#dropdownFrom';
                const otherVal = $(otherDropdownId).find('input[type="hidden"]').val();

                if (val === otherVal && val !== "") {
                    // Flash the other dropdown or show a toast if available
                    $(this).closest('.custom-dropdown').addClass('shake');
                    setTimeout(() => $(this).closest('.custom-dropdown').removeClass('shake'), 500);

                    // Specific feedback: "Source and destination cannot be the same"
                    const originalText = selectedText.text();
                    selectedText.text("{{ __('Invalid Selection') }}").addClass('text-red-400');
                    setTimeout(() => {
                        selectedText.text(originalText).removeClass('text-red-400');
                    }, 2000);

                    options.addClass('hidden');
                    icon.removeClass('rotate-180');
                    return;
                }

                input.val(val);
                selectedText.text($(this).text().trim());
                selectedText.removeClass('text-white/20 text-red-400');

                options.addClass('hidden');
                icon.removeClass('rotate-180');

                if (dropdown.attr('id') === 'dropdownFrom') {
                    dropdown.data('selected-balance', balance);
                    updateTransferBalance(balance);
                }
            });
        });

        // Add shake animation CSS if not present
        if (!$('#dropdown-styles').length) {
            $('head').append(`
                <style id="dropdown-styles">
                    @keyframes shake {
                        0%, 100% { transform: translateX(0); }
                        25% { transform: translateX(-5px); }
                        75% { transform: translateX(5px); }
                    }
                    .shake { animation: shake 0.2s ease-in-out 0s 2; }
                </style>
            `);
        }

        $(document).on('click', function() {
            $('.dropdown-options').addClass('hidden');
            $('.dropdown-trigger svg').removeClass('rotate-180');
        });

        function setMaxTransfer() {
            const balance = $('#dropdownFrom').data('selected-balance') || {{ $user->balance }};
            $('#transferForm input[name="amount"]').val(balance);
        }

        function updateTransferBalance(balance) {
            const displayBalance = balance !== undefined ? balance : {{ $user->balance }};
            $('#transferForm input[name="amount"]').attr('placeholder', 'Max: ' + displayBalance);
        }

        // Initialize balance on load
        $(document).ready(function() {
            updateTransferBalance({{ $user->balance }});
        });
    </script>
@endsection
