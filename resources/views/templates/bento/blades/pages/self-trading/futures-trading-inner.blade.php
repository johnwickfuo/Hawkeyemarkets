@if ($last_error_message)
    <div
        class="glass-panel rounded-xl md:rounded-2xl px-4 py-3 mb-4 md:mb-6 border-red-500/30 bg-red-500/10 text-red-400 text-sm flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="12" />
            <line x1="12" y1="16" x2="12.01" y2="16" />
        </svg>
        <span>{{ $last_error_message }}</span>
    </div>
@endif

@if (!$last_error_message || !empty($current_ticker_info))
    <div id="topPanelStats" class="glass-panel rounded-xl md:rounded-2xl px-3 md:px-4 py-3 mb-4 md:mb-6 relative z-50">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-2 md:gap-3">
                <div
                    class="w-8 h-8 md:w-9 md:h-9 rounded-xl grid place-items-center bg-accent-primary/15 border border-accent-primary/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" class="text-accent-primary w-5 h-5 md:w-6 md:h-6" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2 2 7l10 5 10-5-10-5Z" />
                        <path d="m2 17 10 5 10-5" />
                        <path d="m2 12 10 5 10-5" />
                    </svg>
                </div>

                <div class="flex items-center gap-2 relative">
                    <button id="pairDropdownBtn"
                        class="flex items-center gap-1.5 hover:bg-white/5 pr-2 pl-1 py-1 rounded-lg transition-colors group">
                        <div class="font-semibold tracking-tight text-white text-sm md:text-base">
                            {{ $current_ticker }}</div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="text-white/50 group-hover:text-white transition-colors">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <div id="pairDropdownMenu"
                        class="absolute top-full left-0 mt-2 w-56 rounded-xl border border-white/10 shadow-2xl shadow-black/50 overflow-hidden hidden z-50 bg-[#131722]">
                        <div class="px-3 py-2 border-b border-white/10">
                            <input type="text" placeholder="{{ __('Search') }}" id="pairSearch"
                                class="w-full bg-white/5 border border-white/10 rounded-lg px-2 py-1.5 text-base text-white outline-none focus:border-accent-primary/50 transition-colors">
                        </div>
                        <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
                            @foreach ($all_crypto_tickers as $asset)
                                <a href="{{ route('trading.futures', $asset['ticker']) }}"
                                    class="pair-item w-full text-left px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition-colors flex items-center justify-between group">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold">{{ $asset['ticker'] }}</span>
                                        <span class="text-xs text-white/40 group-hover:text-white/60">/USD</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <span
                        class="text-xs px-2 py-1 rounded-full bg-white/5 border border-white/10 text-white/70">{{ __('Perp') }}</span>
                </div>

                <div class="flex items-center gap-2 ml-2 md:ml-4">
                    <div class="text-white/70 text-xs md:text-sm">{{ __('Last') }}</div>
                    <div class="font-semibold text-white text-sm md:text-base" id="lastPrice">
                        @php $price = $current_ticker_info['current_price'] ?? 0; @endphp
                        {{ number_format($price, $price < 1 ? 4 : 2) }}</div>
                    @php $change = $current_ticker_info['change_1d_percentage'] ?? 0; @endphp
                    <div
                        class="text-xs px-2 py-1 rounded-full {{ $change >= 0 ? 'bg-green-500/15 border-green-500/25 text-green-400' : 'bg-red-500/10 border-red-500/25 text-red-400' }} hidden md:block">
                        {{ $change >= 0 ? '+' : '' }}{{ $change }}%
                    </div>
                </div>
            </div>


            <div class="flex items-center gap-2 overflow-x-auto pb-1 -mx-3 px-3 md:mx-0 md:px-0">
                <div
                    class="bg-white/5 border border-white/10 rounded-xl px-2.5 md:px-3 py-1.5 md:py-2 flex items-center gap-2 min-w-[140px] md:min-w-[170px]">
                    <span class="text-white/40">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </span>
                    <span class="font-semibold text-white text-xs md:text-sm">{{ __('open') }}</span>
                    <span class="text-white/80 text-xs md:text-sm">{{ $current_ticker_info['open_price'] }}</span>
                </div>

                <div
                    class="bg-white/5 border border-white/10 rounded-xl px-2.5 md:px-3 py-1.5 md:py-2 flex items-center gap-2 ">
                    <span class="text-white/40">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M6 12L12 18L18 12" />
                            <path d="M6 6L12 12L18 6" />
                        </svg>
                    </span>
                    <span class="font-semibold text-white text-xs md:text-sm">{{ __('Volume') }}</span>
                    <span
                        class="text-white/80 text-xs md:text-sm">{{ number_format($current_ticker_info['volume'] ?? 0, 2) }}</span>
                </div>

                <div
                    class="bg-white/5 border border-white/10 rounded-xl px-2.5 md:px-3 py-1.5 md:py-2 flex items-center gap-2 min-w-[150px] md:min-w-[190px]">
                    <span class="text-white/60 text-xs md:text-sm whitespace-nowrap">{{ __('24h Low') }}</span>
                    <span
                        class="font-semibold text-red-400 text-xs md:text-sm">{{ number_format($current_ticker_info['low'], 2) }}</span>
                </div>

                <div
                    class="bg-white/5 border border-white/10 rounded-xl px-2.5 md:px-3 py-1.5 md:py-2 flex items-center gap-2 min-w-[150px] md:min-w-[190px]">
                    <span class="text-white/60 text-xs md:text-sm whitespace-nowrap">{{ __('24h High') }}</span>
                    <span
                        class="font-semibold text-green-400 text-sm">{{ number_format($current_ticker_info['high'], 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 md:gap-5 mb-4 md:mb-6">
        {{-- Chart Panel --}}
        <section class="lg:col-span-8 glow-border order-1">
            <div class="glass-panel rounded-2xl md:rounded-3xl p-3 md:p-4 lg:p-5">
                {{-- Chart Controls --}}
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-3 md:mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-white/60 text-xs md:text-sm">{{ __('Time') }}</span>
                        <div class="flex items-center gap-1">
                            <button data-interval="15"
                                class="time-btn bg-accent-primary/20 border border-accent-primary/30 rounded-lg md:rounded-xl px-2.5 md:px-3 py-1.5 text-xs md:text-sm text-white">15m</button>
                            <button data-interval="60"
                                class="time-btn bg-white/5 border border-white/10 rounded-lg md:rounded-xl px-2.5 md:px-3 py-1.5 text-xs md:text-sm text-white/70 hover:text-white">1h</button>
                            <button data-interval="240"
                                class="time-btn bg-white/5 border border-white/10 rounded-lg md:rounded-xl px-2.5 md:px-3 py-1.5 text-xs md:text-sm text-white/70 hover:text-white">4h</button>
                            <button data-interval="D"
                                class="time-btn bg-white/5 border border-white/10 rounded-lg md:rounded-xl px-2.5 md:px-3 py-1.5 text-xs md:text-sm text-white/70 hover:text-white">1d</button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-white/60 text-xs md:text-sm">{{ __('Indicator') }}</span>
                        <button id="toggleToolbarBtn"
                            class="bg-white/5 border border-white/10 rounded-lg md:rounded-xl px-2.5 md:px-3 py-1.5 text-xs md:text-sm text-white/70 hover:text-white">{{ __('Show') }}</button>
                    </div>
                </div>

                {{-- Chart Area --}}
                <div
                    class="relative h-[250px] sm:h-[350px] md:h-[400px] lg:h-[470px] rounded-xl md:rounded-2xl border border-white/10 bg-gradient-to-br from-accent-primary/10 via-transparent to-blue-500/10 overflow-hidden">
                    {{-- Loader --}}
                    <div id="chartLoader"
                        class="absolute inset-0 z-20 flex items-center justify-center bg-[#131722]/50 backdrop-blur-sm">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-accent-primary"></div>
                    </div>
                    {{-- Chart Container --}}
                    <div id="chartContainer" class="absolute inset-0 z-10 w-full h-full">
                    </div>
                </div>

                <div
                    class="mt-3 px-3 md:px-4 py-2 md:py-3 flex flex-wrap items-center justify-between text-xs text-white/50 border-t border-white/10 gap-2">
                    <div class="flex items-center gap-1.5 md:gap-2 flex-wrap">

                    </div>
                    <div id="chartTime" class="text-xs">12:00:00 UTC</div>
                </div>
            </div>
        </section>

        {{-- Right Column: Place Order --}}
        <div class="lg:col-span-4 space-y-4 md:space-y-5 order-2">
            {{-- Place Order --}}
            <section class="glow-border">
                <div class="glass-panel rounded-2xl md:rounded-3xl p-3 md:p-4">
                    <div class="flex items-center justify-between mb-3 md:mb-4">
                        <h3 class="font-semibold text-white text-sm md:text-base">{{ __('Place Order') }}</h3>
                    </div>

                    <div>
                        {{-- Order Type Tabs --}}
                        <div class="flex gap-1.5 md:gap-2 mb-3">
                            <button data-type="limit"
                                class="order-type-tab flex-1 bg-accent-primary/20 border border-accent-primary/30 rounded-lg md:rounded-xl px-2.5 md:px-3 py-2 text-xs md:text-sm text-white">{{ __('Limit') }}</button>
                            <button data-type="market"
                                class="order-type-tab flex-1 bg-white/5 border border-white/10 rounded-lg md:rounded-xl px-2.5 md:px-3 py-2 text-xs md:text-sm text-white/70 hover:text-white">{{ __('Market') }}</button>
                        </div>

                        {{-- Buy/Sell Toggle --}}
                        <div class="mb-3 md:mb-4 bg-white/5 border border-white/10 rounded-xl md:rounded-2xl p-1 flex">
                            <button id="btnBuy"
                                class="flex-1 rounded-lg md:rounded-xl py-2 md:py-2.5 text-xs md:text-sm font-semibold bg-green-500/20 border border-green-500/30 text-green-400 uppercase">{{ __('Buy') }}</button>
                            <button id="btnSell"
                                class="flex-1 rounded-lg md:rounded-xl py-2 md:py-2.5 text-xs md:text-sm font-semibold text-white/70 hover:text-white uppercase">{{ __('Sell') }}</button>
                        </div>

                        {{-- Inputs --}}
                        <div class="space-y-3 mb-4 md:mb-5">
                            <div id="priceInputGroup">
                                <label class="text-xs text-white/55 block mb-1.5">{{ __('price') }}</label>
                                <div
                                    class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-xl md:rounded-2xl px-3 py-2.5 md:py-3">
                                    <input id="inputPrice"
                                        class="w-full bg-transparent outline-none text-base text-white"
                                        value="{{ $current_ticker_info['current_price'] }}" />
                                    <span class="text-xs text-white/55">{{ $current_ticker_info['quote'] }}</span>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-1.5">
                                    <label class="text-xs text-white/55">{{ __('Amount') }}
                                        ({{ $current_ticker_info['base'] }})</label>
                                    <span class="text-xs text-white/55">{{ __('Avail:') }} <span
                                            id="availableBalanceValue" class="text-white">0.00</span>
                                        {{ $current_ticker_info['quote'] }}</span>
                                </div>
                                <div
                                    class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-xl md:rounded-2xl px-3 py-2.5 md:py-3">
                                    <input id="inputAmount"
                                        class="w-full bg-transparent outline-none text-base text-white"
                                        placeholder="0.00" />
                                    <span class="text-xs text-white/55">{{ $current_ticker_info['quote'] }}</span>
                                </div>
                                <div class="flex gap-2 mt-2">
                                    @foreach ([10, 25, 50, 75, 100] as $pct)
                                        <button
                                            class="pct-btn flex-1 py-1 rounded-lg bg-white/5 border border-white/10 text-[10px] md:text-xs text-white/60 hover:text-white hover:bg-white/10 transition"
                                            data-pct="{{ $pct }}">{{ $pct }}%</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Leverage Slider --}}
                        <div class="mb-4 md:mb-5">
                            <input type="hidden" id="inputLeverage" name="leverage" value="30">
                            <div class="flex items-center justify-between mb-2 md:mb-3">
                                <span class="text-xs md:text-sm text-white/70">{{ __('Leverage') }}</span>
                                <span class="text-xs text-white/55" id="leverageValue">30x</span>
                            </div>

                            <div class="px-1">
                                <div id="leverageContainer"
                                    class="h-2 rounded-full bg-white/10 relative cursor-pointer touch-none">
                                    <div id="leverageBar"
                                        class="absolute inset-y-0 left-0 w-[60%] bg-gradient-to-r from-accent-primary to-purple-500 rounded-l-full pointer-events-none group">
                                        <div
                                            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-4 h-4 bg-white rounded-full shadow-[0_0_10px_rgba(255,255,255,0.5)] border border-accent-primary z-10 transition-transform group-active:scale-110">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 flex justify-between text-xs text-white/55">
                                    <span>1x</span><span>10x</span><span>20x</span><span
                                        class="text-white/80">30x</span><span>40x</span><span>50x</span>
                                </div>
                            </div>
                        </div>

                        {{-- TP / SL --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <label class="text-xs text-white/55 block mb-1.5">{{ __('Take Profit') }}</label>
                                <div
                                    class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-xl md:rounded-2xl px-3 py-2.5">
                                    <input class="w-full bg-transparent outline-none text-base text-white"
                                        placeholder="{{ __('Price') }}" />
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-white/55 block mb-1.5">{{ __('Stop Loss') }}</label>
                                <div
                                    class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-xl md:rounded-2xl px-3 py-2.5">
                                    <input class="w-full bg-transparent outline-none text-base text-white"
                                        placeholder="{{ __('Price') }}" />
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('user.login') }}"
                            class="w-full py-3 md:py-3.5 rounded-xl md:rounded-2xl font-semibold text-sm md:text-base bg-gradient-to-r from-accent-primary to-accent-secondary hover:opacity-90 transition border border-white/10 shadow-lg shadow-accent-primary/20 text-white flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                <polyline points="10 17 15 12 10 7" />
                                <line x1="15" y1="12" x2="3" y2="12" />
                            </svg>
                            {{ __('Login') }}
                        </a>
                    </div>
                </div>
            </section>
        </div>

        {{-- Full Width: Order Book & Trades --}}
        <div class="lg:col-span-12 order-3 grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-5">
            {{-- Order Book --}}
            <section id="orderBookContainer" class="glow-border">
                <div class="glass-panel rounded-2xl md:rounded-3xl p-3 md:p-4">
                    <div class="flex items-center justify-between mb-3 md:mb-4">
                        <h3 class="font-semibold text-white text-sm md:text-base">{{ __('Order Book') }}</h3>
                    </div>

                    <div class="grid grid-cols-3 text-xs text-white/55 mb-2 md:mb-3">
                        <div>{{ __('Price') }}</div>
                        <div class="text-center">{{ __('Qty') }}</div>
                        <div class="text-right">{{ __('Total') }}</div>
                    </div>

                    <div
                        class="space-y-1.5 md:space-y-2 max-h-[250px] md:max-h-[350px] lg:max-h-[350px] overflow-auto pr-1">
                        {{-- Asks --}}
                        @if (!empty($order_book['asks']))
                            @foreach (array_slice($order_book['asks'], 0, 15) as $ask)
                                <div class="grid grid-cols-3 items-center text-xs md:text-sm order-item cursor-pointer hover:bg-white/5 p-1 rounded transition-all"
                                    data-price="{{ $ask[0] }}">
                                    <div class="text-red-400">{{ number_format($ask[0], 4) }}</div>
                                    <div class="text-center text-white/80">{{ number_format($ask[1], 4) }}</div>
                                    <div class="text-right text-white/70">{{ number_format($ask[0] * $ask[1], 2) }}
                                    </div>
                                    <div
                                        class="col-span-3 h-1.5 md:h-2 rounded bg-red-500/20 mt-1 relative overflow-hidden">
                                        <div class="absolute inset-y-0 right-0 bg-red-500/35"
                                            style="width: {{ ($ask[1] / ($order_book['asks'][0][1] ?? 1)) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{-- Current Price --}}
                        <div
                            class="py-1.5 md:py-2 flex items-center justify-center gap-2 text-sm md:text-base font-bold text-white border-y border-white/5 my-1">
                            <span>{{ number_format($current_ticker_info['current_price'], 2) }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="text-green-400">
                                <line x1="12" y1="19" x2="12" y2="5"></line>
                                <polyline points="5 12 12 5 19 12"></polyline>
                            </svg>
                        </div>

                        {{-- Bids --}}
                        @if (!empty($order_book['bids']))
                            @foreach (array_slice($order_book['bids'], 0, 15) as $bid)
                                <div class="grid grid-cols-3 items-center text-xs md:text-sm order-item cursor-pointer hover:bg-white/5 p-1 rounded transition-all"
                                    data-price="{{ $bid[0] }}">
                                    <div class="text-green-400">{{ number_format($bid[0], 4) }}</div>
                                    <div class="text-center text-white/80">{{ number_format($bid[1], 4) }}</div>
                                    <div class="text-right text-white/70">{{ number_format($bid[0] * $bid[1], 2) }}
                                    </div>
                                    <div
                                        class="col-span-3 h-1.5 md:h-2 rounded bg-green-500/20 mt-1 relative overflow-hidden">
                                        <div class="absolute inset-y-0 left-0 bg-green-500/35"
                                            style="width: {{ ($bid[1] / ($order_book['bids'][0][1] ?? 1)) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </section>

            {{-- Recent Trades --}}
            <section id="recentTradesContainer" class="glow-border">
                <div class="glass-panel rounded-2xl md:rounded-3xl p-3 md:p-4">
                    <div class="flex items-center justify-between mb-3 md:mb-4">
                        <h3 class="font-semibold text-white text-sm md:text-base">{{ __('Recent Trades') }}</h3>
                    </div>

                    <div class="grid grid-cols-3 text-xs text-white/55 mb-2 md:mb-3">
                        <div>{{ __('Price') }}</div>
                        <div class="text-center">{{ __('Size') }}</div>
                        <div class="text-right">{{ __('Time') }}</div>
                    </div>

                    <div
                        class="space-y-1.5 md:space-y-2 max-h-[250px] md:max-h-[350px] lg:max-h-[350px] overflow-auto pr-1">
                        @foreach ($recent_trades as $trade)
                            @php
                                $isBuy = $trade['isBuyerMaker'] == true;
                            @endphp
                            <div class="grid grid-cols-3 text-xs md:text-sm p-1 hover:bg-white/5 rounded">
                                <div class="{{ $isBuy ? 'text-green-400' : 'text-red-400' }}">
                                    {{ number_format($trade['price'], 4) }}
                                </div>
                                <div class="text-center text-white/80">{{ number_format($trade['qty'], 4) }}</div>
                                <div class="text-right text-white/55">
                                    {{ \Carbon\Carbon::createFromTimestampMs($trade['time'])->format('H:i:s') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- Bottom Table --}}
    <div class="glow-border">
        <div class="glass-panel rounded-3xl p-4 md:p-5">
            {{-- Tabs --}}
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <button data-target="#tab-positions"
                    class="position-tab bg-accent-primary/20 border border-accent-primary/30 rounded-xl px-4 py-2 text-sm text-white">{{ __('Positions') }}</button>
                <button data-target="#tab-open-orders"
                    class="position-tab bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white/70 hover:text-white">{{ __('Open Orders') }}</button>
                <button data-target="#tab-closed-orders"
                    class="position-tab bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white/70 hover:text-white">{{ __('Closed Orders') }}</button>
            </div>

            {{-- Content: Positions --}}
            <div id="tab-positions" class="tab-content h-[200px] flex items-center justify-center">
                <div class="text-center">
                    <div
                        class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="text-white/40">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                            <polyline points="10 17 15 12 10 7" />
                            <line x1="15" y1="12" x2="3" y2="12" />
                        </svg>
                    </div>
                    <p class="text-white/60 mb-4">{{ __('Login to View your open Order') }}</p>
                    <a href="{{ route('user.login') }}"
                        class="text-accent-primary font-bold hover:underline">{{ __('Click here to Login') }}</a>
                </div>
            </div>

            {{-- Content: Open Orders --}}
            <div id="tab-open-orders" class="tab-content hidden h-[200px] flex items-center justify-center">
                <div class="text-center">
                    <p class="text-white/60 mb-2">{{ __('Login to View your open Order') }}</p>
                    <a href="{{ route('user.login') }}"
                        class="text-accent-primary font-bold hover:underline">{{ __('Login Now') }}</a>
                </div>
            </div>

            {{-- Content: Closed Orders --}}
            <div id="tab-closed-orders" class="tab-content hidden h-[200px] flex items-center justify-center">
                <div class="text-center">
                    <p class="text-white/60 mb-2">{{ __('Login to View your open Order') }}</p>
                    <a href="{{ route('user.login') }}"
                        class="text-accent-primary font-bold hover:underline">{{ __('Login Now') }}</a>
                </div>
            </div>
        </div>
    </div>
@endif
