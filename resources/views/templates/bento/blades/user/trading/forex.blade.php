@extends('templates.bento.blades.layouts.user')

@section('content')
    <div id="general-page" class="w-full">
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
        </style>

        @include('templates.bento.blades.user.trading.forex_inner')
    </div>
@endsection

@section('scripts')
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
            function loadTickerData(url, ticker, isFullReplace = false, switchTab = null) {
                // Determine current active tabs to preserve state
                const activeOrderTab = $('.order-tab.bg-\\[\\#334155\\]').data('target');
                const activeTermTab = $('.term-tab.text-white').data('target');

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
                            // Wrap response to ensure .find() works on root elements
                            const $newData = $('<div>').append(response);

                            $('#topPanelStats').html($newData.find('#topPanelStats').html());
                            $('#mobileStats').html($newData.find('#mobileStats').html());
                            $('#marketWatchContent').html($newData.find('#marketWatchContent').html());

                            // Re-apply current filters
                            filterMarkets();

                            // Re-apply active class to quote buttons
                            $('.quote-filter-btn').removeClass('bg-blue-500 text-white shadow-sm')
                                .addClass('bg-white/5 text-slate-500 hover:text-slate-300');
                            $(`.quote-filter-btn[data-quote="${activeQuote}"]`).removeClass(
                                'bg-white/5 text-slate-500 hover:text-slate-300').addClass(
                                'bg-blue-500 text-white shadow-sm');

                            $('#orderPanelContainer').html($newData.find('#orderPanelContainer')
                                .html());
                            $('#terminalContainer').html($newData.find('#terminalContainer').html());

                            // Restore active Order Tab
                            if (activeOrderTab) {
                                $(`.order-tab[data-target="${activeOrderTab}"]`).addClass(
                                    'bg-[#334155] text-white shadow-sm').removeClass(
                                    'text-slate-500 hover:text-white');
                                $('.order-content').addClass('hidden');
                                $('#' + activeOrderTab).removeClass('hidden');
                            }

                            // Restore active Terminal Tab or switch to new one
                            const targetTab = switchTab || activeTermTab || 'term-positions';
                            $(`.term-tab[data-target="${targetTab}"]`).click();
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
                const symbol = $(this).data('symbol');
                // Extract ticker from URL or data attribute
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

            // Terminal Tabs Logic
            $(document).on('click', '.term-tab', function() {
                $('.term-tab').removeClass('text-white border-blue-500').addClass(
                    'text-slate-500 border-transparent hover:text-slate-300');
                $(this).removeClass('text-slate-500 border-transparent hover:text-slate-300').addClass(
                    'text-white border-blue-500');

                $('.term-content').addClass('hidden');
                $('#' + $(this).data('target')).removeClass('hidden');
            });

            // 10 second data polling
            setInterval(() => {
                const url = window.location.href;
                loadTickerData(url, currentTicker, false);
            }, 10000);

            window.submitForexTrade = function(type, orderType) {
                const volume = $('#lotInput').val();
                const sl = $('#slInput').val();
                const tp = $('#tpInput').val();
                const symbol = currentSymbol;
                const mode = "{{ $mode }}";

                $.ajax({
                    url: "{{ route('user.trading.forex.trade') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        symbol: symbol,
                        type: type,
                        volume: volume,
                        order_type: orderType,
                        stop_loss: sl,
                        take_profit: tp,
                        mode: mode
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            // If Market, switch to positions. If others, switch to pending
                            const targetTab = (orderType === 'Market') ? 'term-positions' :
                                'term-pending';
                            loadTickerData(window.location.href, currentTicker, false, targetTab);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    }
                });
            };

            window.closeForexPosition = function(id) {
                $.ajax({
                    url: "{{ route('user.trading.forex.close-position') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            loadTickerData(window.location.href, currentTicker, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    }
                });
            };

            window.cancelForexOrder = function(id) {
                $.ajax({
                    url: "{{ route('user.trading.forex.cancel-order') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            loadTickerData(window.location.href, currentTicker, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    }
                });
            };
        });
    </script>
@endsection
