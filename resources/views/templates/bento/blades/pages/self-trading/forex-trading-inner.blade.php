@php
    $marketPairs = $forex_tickers;
@endphp

{{-- Top Stats Bar --}}
<div id="topPanelStats"
    class="flex items-center justify-between bg-[#0f172a] rounded-xl px-4 py-2 border border-white/5 shadow-sm shrink-0">
    <div class="flex items-center gap-4 text-sm overflow-x-auto no-scrollbar w-full lg:w-auto">
        {{-- Account Type Switcher Style --}}
        <div class="flex bg-[#1e293b] rounded-lg p-0.5 border border-white/5 shrink-0">
            <div
                class="px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-500 text-white shadow-sm cursor-pointer transition-all">
                {{ __('Live') }}</div>
            <div
                class="px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider text-slate-500 hover:text-slate-300 cursor-pointer transition-all">
                {{ __('Demo') }}</div>
        </div>

        <div class="h-6 w-px bg-white/10 shrink-0"></div>

        {{-- Server Info --}}
        <div class="flex items-center gap-2 shrink-0">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-medium text-slate-400 font-mono text-nowrap">{{ $server ?? 'London-Pro-1' }}</span>
        </div>

        <div class="h-6 w-px bg-white/10 hidden md:block shrink-0"></div>

        {{-- User Level --}}
        <div class="hidden md:flex items-center gap-1.5 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="text-amber-400">
                <polygon
                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                </polygon>
            </svg>
            <span
                class="text-xs font-bold text-amber-100 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded text-nowrap">{{ __($userLevel ?? 'VIP Trader') }}</span>
        </div>

        <div class="h-6 w-px bg-white/10 hidden lg:block shrink-0"></div>

        {{-- Financials --}}
        <div class="flex items-center gap-4 hidden lg:flex shrink-0">
            <div class="flex flex-col">
                <span
                    class="text-[9px] uppercase tracking-wider text-slate-500 font-semibold">{{ __('Balance') }}</span>
                <span class="font-mono font-bold text-white text-xs">$0.00</span>
            </div>
            <div class="flex flex-col">
                <span
                    class="text-[9px] uppercase tracking-wider text-slate-500 font-semibold">{{ __('Equity') }}</span>
                <span class="font-mono font-bold text-emerald-400 text-xs">$0.00</span>
            </div>
            <div class="flex flex-col">
                <span
                    class="text-[9px] uppercase tracking-wider text-slate-500 font-semibold">{{ __('Margin %') }}</span>
                <span class="font-mono font-medium text-blue-400 text-xs">0.00%</span>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-2 hidden sm:flex">
        <a href="{{ route('user.login') }}"
            class="bg-[#1e293b] hover:bg-[#334155] text-xs font-semibold px-3 py-1.5 rounded-lg border border-white/5 transition-colors text-white flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                <polyline points="10 17 15 12 10 7" />
                <line x1="15" y1="12" x2="3" y2="12" />
            </svg>
            <span class="hidden md:inline">{{ __('Login') }}</span>
        </a>
    </div>
</div>

{{-- Mobile Financials --}}
<div id="mobileStats"
    class="lg:hidden grid grid-cols-3 gap-2 bg-[#0f172a] rounded-xl px-4 py-2 border border-white/5 shadow-sm">
    <div class="flex flex-col items-center">
        <span class="text-[9px] uppercase tracking-wider text-slate-500 font-semibold">{{ __('Balance') }}</span>
        <span class="font-mono font-bold text-white text-xs">$0.00</span>
    </div>
    <div class="flex flex-col items-center border-x border-white/5">
        <span class="text-[9px] uppercase tracking-wider text-slate-500 font-semibold">{{ __('Equity') }}</span>
        <span class="font-mono font-bold text-emerald-400 text-xs">$0.00</span>
    </div>
    <div class="flex flex-col items-center">
        <span class="text-[9px] uppercase tracking-wider text-slate-500 font-semibold">{{ __('Margin %') }}</span>
        <span class="font-mono font-medium text-blue-400 text-xs">0.00%</span>
    </div>
</div>

{{-- Main Grid Content (Dynamically updated parts) --}}
<div class="flex-1 flex flex-col lg:flex-row gap-2 overflow-hidden">
    {{-- Left: Market Watch --}}
    <div id="marketWatchContainer"
        class="w-full lg:w-72 bg-[#0f172a] rounded-2xl border border-white/5 flex flex-col overflow-hidden shadow-lg h-[600px] order-4 lg:order-1 shrink-0">
        <div class="px-4 py-3 border-b border-white/5 flex justify-between items-center group">
            <h3 class="text-sm font-semibold text-white">{{ __('Markets') }}</h3>
            <button class="text-slate-500 hover:text-white transition lg:hidden"
                onclick="$('#marketWatchContent').toggleClass('hidden')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
        </div>
        {{-- Search Input Container (Always Visible) --}}
        <div id="searchContainer" class="px-4 py-2 border-b border-white/5">
            <input type="text" id="marketSearch" placeholder="{{ __('Search markets...') }}"
                class="w-full bg-[#1e293b] border border-white/10 rounded-lg px-3 py-1.5 text-xs text-white outline-none focus:border-blue-500/50 transition-colors">
        </div>
        {{-- Quote Filter Tabs --}}
        <div id="quoteFilters" class="px-4 py-2 border-b border-white/5 flex gap-1 overflow-x-auto no-scrollbar">
            @foreach (['ALL', 'USD', 'EUR', 'GBP', 'JPY'] as $quote)
                <button
                    class="quote-filter-btn px-2.5 py-1 rounded text-[10px] font-bold uppercase transition-all {{ $quote === 'ALL' ? 'bg-blue-500 text-white shadow-sm' : 'bg-white/5 text-slate-500 hover:text-slate-300' }}"
                    data-quote="{{ $quote === 'ALL' ? '' : $quote }}">
                    {{ $quote }}
                </button>
            @endforeach
        </div>
        <div id="marketWatchContent" class="flex-1 overflow-y-auto block">
            @foreach ($marketPairs as $pair)
                @php
                    $pair_ticker = str_replace('/', '_', $pair['s']);
                @endphp
                <a href="{{ route('trading.forex', ['ticker' => $pair_ticker]) }}"
                    class="group px-4 py-3 border-b border-white/5 hover:bg-[#1e293b] cursor-pointer transition-all duration-200 pair-item flex flex-col {{ $current_ticker == $pair_ticker ? 'bg-[#1e293b]' : '' }}"
                    data-symbol="{{ $pair['s'] }}">
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-bold text-sm text-white">{{ $pair['s'] }}</span>
                    </div>
                    <div class="flex justify-between items-center gap-2">
                        <div
                            class="flex-1 bg-[#1e293b] group-hover:bg-[#334155] rounded px-2 py-1 transition-colors text-right relative overflow-hidden">
                            <span
                                class="text-[10px] text-slate-500 absolute left-2 top-1.5">{{ __('Bid') }}</span>
                            <span
                                class="font-mono text-sm text-rose-400 font-medium">{{ number_format($pair['b'], 5) }}</span>
                        </div>
                        <div
                            class="flex-1 bg-[#1e293b] group-hover:bg-[#334155] rounded px-2 py-1 transition-colors text-right relative overflow-hidden">
                            <span
                                class="text-[10px] text-slate-500 absolute left-2 top-1.5">{{ __('Ask') }}</span>
                            <span
                                class="font-mono text-sm text-emerald-400 font-medium">{{ number_format($pair['a'], 5) }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Center: Chart Placeholder/Container --}}
    <div id="chartPanel"
        class="flex-1 bg-[#0f172a] rounded-2xl border border-white/5 overflow-hidden shadow-lg relative flex flex-col h-[600px] order-1 lg:order-2 shrink-0">
        <div class="absolute top-4 left-4 z-10 flex gap-2">
            <div
                class="bg-[#1e293b]/80 backdrop-blur-sm px-3 py-1.5 rounded-lg border border-white/10 text-xs font-bold text-white shadow-sm flex items-center gap-2">
                <span id="chartSymbol">{{ $current_ticker_formatted }}</span>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            </div>
        </div>
        {{-- Loader --}}
        <div id="chartLoader"
            class="absolute inset-0 z-20 flex items-center justify-center bg-[#0f172a]/80 backdrop-blur-sm transition-opacity duration-500 hidden">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-white/10 border-b-emerald-500"></div>
        </div>
        <div id="chartContainer" class="w-full h-full"></div>
    </div>

    {{-- Right: Order Panel --}}
    <div id="orderPanelContainer"
        class="w-full lg:w-80 bg-[#0f172a] rounded-2xl border border-white/5 flex flex-col shadow-lg h-[600px] order-2 lg:order-3 shrink-0">
        <div class="flex p-1 m-3 bg-[#1e293b] rounded-xl">
            <button id="tab-market"
                class="flex-1 py-1.5 text-xs font-semibold text-white bg-[#334155] rounded-lg shadow-sm transition-all order-tab"
                data-target="order-market">{{ __('Market') }}</button>
            <button id="tab-pending"
                class="flex-1 py-1.5 text-xs font-semibold text-slate-500 hover:text-white transition-all order-tab"
                data-target="order-pending">{{ __('Pending') }}</button>
        </div>

        <div id="order-market" class="flex-1 px-4 py-2 flex flex-col gap-5 overflow-y-auto order-content">
            {{-- Price Display --}}
            <div class="flex items-center justify-between">
                @php
                    $bid = $current_ticker_info['b'] ?? 0;
                    $ask = $current_ticker_info['a'] ?? 0;

                    // Split bid/ask for specialized formatting
                    $bid_base = substr(number_format($bid, 5), 0, -3);
                    $bid_main = substr(number_format($bid, 5), -3, 2);
                    $bid_sub = substr(number_format($bid, 5), -1);

                    $ask_base = substr(number_format($ask, 5), 0, -3);
                    $ask_main = substr(number_format($ask, 5), -3, 2);
                    $ask_sub = substr(number_format($ask, 5), -1);
                @endphp
                <div class="text-center w-1/2 border-r border-white/5 pr-2">
                    <div class="text-xs text-slate-500 mb-1">{{ __('Sell') }}</div>
                    <div class="text-2xl font-mono font-bold text-rose-500 tracking-tighter">{{ $bid_base }}<span
                            class="text-3xl">{{ $bid_main }}</span><span
                            class="text-base align-top">{{ $bid_sub }}</span></div>
                </div>
                <div class="text-center w-1/2 pl-2">
                    <div class="text-xs text-slate-500 mb-1">{{ __('Buy') }}</div>
                    <div class="text-2xl font-mono font-bold text-emerald-500 tracking-tighter">
                        {{ $ask_base }}<span class="text-3xl">{{ $ask_main }}</span><span
                            class="text-base align-top">{{ $ask_sub }}</span></div>
                </div>
            </div>

            {{-- Inputs --}}
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <label class="text-xs font-medium text-slate-400">{{ __('Volume (Lots)') }}</label>
                        <span class="text-[10px] text-slate-600">{{ __('1 Lot = 100k Units') }}</span>
                    </div>
                    <div class="relative group">
                        <button type="button"
                            class="absolute left-1 top-1 w-8 h-8 rounded-lg bg-[#1e293b] hover:bg-[#334155] text-slate-400 hover:text-white transition flex items-center justify-center font-bold"
                            onclick="adjustLots(-0.01)">-</button>
                        <input type="number" id="lotInput" value="1.00" step="0.01"
                            class="w-full bg-[#020617] border border-white/10 rounded-xl py-2.5 px-10 text-center font-mono text-white text-base outline-none focus:border-blue-500/50 transition-colors" />
                        <button type="button"
                            class="absolute right-1 top-1 w-8 h-8 rounded-lg bg-[#1e293b] hover:bg-[#334155] text-slate-400 hover:text-white transition flex items-center justify-center font-bold"
                            onclick="adjustLots(0.01)">+</button>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-400 mb-1 block">{{ __('Protection') }}</label>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <input type="number" placeholder="{{ __('SL') }}"
                                class="w-full bg-[#020617] border border-white/10 rounded-xl py-2 pl-3 pr-2 text-base text-white outline-none focus:border-rose-500/50 transition-colors" />
                            <span class="absolute right-2 top-2 text-[10px] text-slate-600">{{ __('Price') }}</span>
                        </div>
                        <div class="relative flex-1">
                            <input type="number" placeholder="{{ __('TP') }}"
                                class="w-full bg-[#020617] border border-white/10 rounded-xl py-2 pl-3 pr-2 text-base text-white outline-none focus:border-emerald-500/50 transition-colors" />
                            <span class="absolute right-2 top-2 text-[10px] text-slate-600">{{ __('Price') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-auto space-y-3">
                <a href="{{ route('user.login') }}"
                    class="w-full py-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-500 hover:from-blue-500 hover:to-indigo-400 text-white font-bold shadow-lg shadow-blue-900/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg>
                    <span>{{ __('Login') }}</span>
                </a>
            </div>
        </div>

        <div id="order-pending" class="flex-1 px-4 py-2 flex flex-col gap-5 overflow-y-auto order-content hidden">
            <div class="text-center py-10 text-slate-500">
                <p class="mb-2">{{ __('Pending Orders') }}</p>
                <p class="text-[10px]">{{ __('Limit and Stop orders will appear here.') }}</p>
                <a href="{{ route('user.login') }}"
                    class="mt-4 inline-block px-4 py-2 bg-[#1e293b] rounded hover:bg-[#334155] text-xs text-white">{{ __('Login to Create Order') }}</a>
            </div>
        </div>
    </div>
</div>

{{-- Bottom: Terminal --}}
<div id="terminalContainer"
    class="lg:h-64 h-auto min-h-[300px] bg-[#0f172a] rounded-2xl border border-white/5 flex flex-col overflow-hidden shadow-lg shrink-0 order-3 lg:order-4 group relative">

    {{-- Restricted Access Overlay --}}
    <div
        class="absolute inset-0 z-50 bg-[#0f172a]/60 backdrop-blur-[2px] flex items-center justify-center pointer-events-none group-hover:pointer-events-auto transition-all">
        <div class="text-center p-6 glass-panel rounded-2xl border border-white/10 shadow-2xl">
            <div
                class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="text-blue-400">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h4 class="text-white font-bold mb-2 uppercase tracking-wide">{{ __('Trading Terminal Locked') }}</h4>
            <p class="text-slate-400 text-xs mb-4 max-w-[200px] mx-auto">
                {{ __('Login to view your open positions and trade history.') }}</p>
            <a href="{{ route('user.login') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold transition-colors pointer-events-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <polyline points="10 17 15 12 10 7"></polyline>
                    <line x1="15" y1="12" x2="3" y2="12"></line>
                </svg>
                {{ __('SIGN IN') }}
            </a>
        </div>
    </div>

    <div class="flex items-center px-4 border-b border-white/5 bg-[#1e293b]/50">
        <button id="tab-positions"
            class="py-3 px-4 text-xs font-semibold text-white border-b-2 border-blue-500 term-tab transition-all"
            data-target="term-positions">{{ __('Positions') }}</button>
        <button id="tab-term-pending"
            class="py-3 px-4 text-xs font-semibold text-slate-500 hover:text-slate-300 border-b-2 border-transparent term-tab transition-all"
            data-target="term-pending">{{ __('Pending') }}</button>
        <button id="tab-history"
            class="py-3 px-4 text-xs font-semibold text-slate-500 hover:text-slate-300 border-b-2 border-transparent term-tab transition-all"
            data-target="term-history">{{ __('History') }}</button>
    </div>
    <div id="term-positions" class="flex-1 overflow-x-auto bg-[#0f172a] term-content">
        <table class="w-full text-xs text-left min-w-[700px] opacity-20 grayscale pointer-events-none">
            <thead class="text-slate-500 sticky top-0 bg-[#0f172a] z-10">
                <tr>
                    <th class="py-3 px-5 font-medium">{{ __('Symbol') }}</th>
                    <th class="py-3 px-2 font-medium">{{ __('ID') }}</th>
                    <th class="py-3 px-2 font-medium">{{ __('Side') }}</th>
                    <th class="py-3 px-2 font-medium text-right">{{ __('Volume') }}</th>
                    <th class="py-3 px-2 font-medium text-right">{{ __('Open Price') }}</th>
                    <th class="py-3 px-2 font-medium text-right">{{ __('Current') }}</th>
                    <th class="py-3 px-2 font-medium text-right">{{ __('Swap') }}</th>
                    <th class="py-3 px-5 font-medium text-right">{{ __('Profit') }}</th>
                    <th class="py-3 px-4 w-10"></th>
                </tr>
            </thead>
            <tbody class="text-slate-300 divide-y divide-white/5">
                <tr>
                    <td colspan="9" class="py-10 text-center text-slate-500">{{ __('Loading positions...') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
