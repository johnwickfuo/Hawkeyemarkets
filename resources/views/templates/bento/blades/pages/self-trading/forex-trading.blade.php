@extends('templates.bento.blades.layouts.front')

@section('content')
    <style>
        /* Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 99px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        button,
        .cursor-pointer {
            cursor: pointer !important;
        }

        .glass-panel {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.03));
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.55), inset 0 1px 0 rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }
    </style>

    <div class="container mx-auto px-4 py-12 pt-20 md:pt-32">
        <div class="mb-12">
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 mb-6 font-bold text-xs text-blue-400 uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                {{ __('Trading Preview') }}
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 uppercase tracking-tighter leading-none">
                {{ __('Forex Trading') }}
            </h1>
            <p class="text-xl text-text-secondary leading-relaxed mb-8 max-w-2xl font-light">
                {{ __('Trade the global currency markets with tight spreads, high leverage, and real-time execution. Experience our institutional Forex terminal.') }}
            </p>
        </div>

        <div id="general-page"
            class="w-full bg-[#020617] text-slate-300 font-sans p-2 overflow-y-auto lg:overflow-hidden flex flex-col gap-2 rounded-2xl relative">
            @include('templates.bento.blades.pages.self-trading.forex-trading-inner')
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://s3.tradingview.com/tv.js"></script>
    <script>
        $(document).ready(function() {
            let currentSymbol = "{{ $current_ticker_formatted }}";
            let currentTicker = "{{ $current_ticker }}";

            function initChart(symbol) {
                $('#chartContainer').html('');

                // Define Major Currencies supported by OANDA
                const majors = ['USD', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'NZD'];
                const parts = symbol.split('/');
                const base = parts[0];
                const quote = parts[1];

                // Determine Prefix: OANDA for majors, FX_IDC for exotics/regional pairs
                let prefix = "OANDA:";
                if (!majors.includes(base) || !majors.includes(quote)) {
                    prefix = "FX_IDC:";
                }

                let tvSymbol = prefix + symbol.replace('/', '');

                const script = document.createElement('script');
                script.src = 'https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js';
                script.async = true;
                script.innerHTML = JSON.stringify({
                    "autosize": true,
                    "symbol": tvSymbol,
                    "interval": "15",
                    "timezone": "Etc/UTC",
                    "theme": "dark",
                    "style": "1",
                    "locale": "en",
                    "enable_publishing": false,
                    "backgroundColor": "rgba(15, 23, 42, 1)",
                    "gridColor": "rgba(255, 255, 255, 0.05)",
                    "hide_top_toolbar": false,
                    "hide_legend": true,
                    "save_image": false,
                    "calendar": false,
                    "allow_symbol_change": false,
                    "hide_volume": true,
                    "support_host": "https://www.tradingview.com"
                });
                document.getElementById('chartContainer').appendChild(script);

                // Show loader then hide
                $('#chartLoader').removeClass('hidden opacity-0');
                setTimeout(() => {
                    $('#chartLoader').addClass('opacity-0');
                    setTimeout(() => {
                        $('#chartLoader').addClass('hidden');
                    }, 500);
                }, 1500);
            }

            // Initial Chart Load
            initChart(currentSymbol);

            // AJAX Data Loading
            function loadTickerData(url, ticker, isFullReplace = false) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        if (isFullReplace) {
                            $('#general-page').html(response);
                            history.pushState(null, '', url);
                            currentTicker = ticker;
                            const newSymbol = $('#chartSymbol').text();
                            currentSymbol = newSymbol;
                            initChart(currentSymbol);
                        } else {
                            const $newData = $(response);
                            $('#topPanelStats').html($newData.find('#topPanelStats').html());
                            $('#mobileStats').html($newData.find('#mobileStats').html());
                            $('#marketWatchContent').html($newData.find('#marketWatchContent').html());

                            // Re-apply filters
                            filterMarkets();

                            // Re-apply active class to quote buttons
                            $('.quote-filter-btn').removeClass('bg-blue-500 text-white shadow-sm')
                                .addClass('bg-white/5 text-slate-500 hover:text-slate-300');
                            $(`.quote-filter-btn[data-quote="${activeQuote}"]`).removeClass(
                                'bg-white/5 text-slate-500 hover:text-slate-300').addClass(
                                'bg-blue-500 text-white shadow-sm');

                            $('#orderPanelContainer').html($newData.find('#orderPanelContainer')
                                .html());
                            // No need to update history terminals as they are locked
                        }
                    }
                });
            }

            let activeQuote = '';

            function filterMarkets() {
                const query = $('#marketSearch').val().toLowerCase();

                $('.pair-item').each(function() {
                    const symbol = $(this).data('symbol').toLowerCase();
                    const matchesSearch = symbol.includes(query);
                    const matchesQuote = activeQuote === '' || symbol.includes(activeQuote.toLowerCase());

                    if (matchesSearch && matchesQuote) {
                        $(this).removeClass('hidden');
                    } else {
                        $(this).addClass('hidden');
                    }
                });
            }

            // Market Search Filtering
            $(document).on('input', '#marketSearch', function() {
                filterMarkets();
            });

            // Quote Filtering
            $(document).on('click', '.quote-filter-btn', function() {
                $('.quote-filter-btn').removeClass('bg-blue-500 text-white shadow-sm').addClass(
                    'bg-white/5 text-slate-500 hover:text-slate-300');
                $(this).removeClass('bg-white/5 text-slate-500 hover:text-slate-300').addClass(
                    'bg-blue-500 text-white shadow-sm');

                activeQuote = $(this).data('quote');
                filterMarkets();
            });

            // Pair Switching
            $(document).on('click', '.pair-item', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const ticker = url.split('/').pop();

                if (ticker !== currentTicker) {
                    loadTickerData(url, ticker, true);
                }
            });

            // Adjust Lots
            window.adjustLots = function(delta) {
                let input = $('#lotInput');
                let val = parseFloat(input.val()) || 0;
                let newVal = Math.max(0.01, Math.round((val + delta) * 100) / 100);
                input.val(newVal.toFixed(2));
            };

            window.setLots = function(val) {
                $('#lotInput').val(val.toFixed(2));
            };

            // Order Tabs Logic
            $(document).on('click', '.order-tab', function() {
                $('.order-tab').removeClass('bg-[#334155] text-white shadow-sm').addClass(
                    'text-slate-500 hover:text-white');
                $(this).removeClass('text-slate-500 hover:text-white').addClass(
                    'bg-[#334155] text-white shadow-sm');

                $('.order-content').addClass('hidden');
                $('#' + $(this).data('target')).removeClass('hidden');
            });

            // Terminal Tabs Logic (Disabled in preview but kept for UI consistency)
            $(document).on('click', '.term-tab', function() {
                $('.term-tab').removeClass('text-white border-blue-500').addClass(
                    'text-slate-500 border-transparent hover:text-slate-300');
                $(this).removeClass('text-slate-500 border-transparent hover:text-slate-300').addClass(
                    'text-white border-blue-500');

                $('.term-content').addClass('hidden');
                $('#' + $(this).data('target')).removeClass('hidden');
            });

            // 15 second data polling
            setInterval(() => {
                const url = window.location.href;
                loadTickerData(url, currentTicker, false);
            }, 15000);
        });
    </script>
@endpush
