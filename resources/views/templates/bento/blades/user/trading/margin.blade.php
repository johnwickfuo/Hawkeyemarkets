@php
    $marketPairs = $all_margin_tickers;
    $orderBook = $order_book;
    $recentTrades = $recent_trades;
@endphp

@extends('templates.bento.blades.layouts.user')

@section('content')
    <style>
        /* Custom Scrollbar for this page */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        button {
            cursor: pointer;
        }

        .glass-panel {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.03));
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.55), inset 0 1px 0 rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .glow-border {
            position: relative;
        }

        .glow-border:before {
            content: "";
            position: absolute;
            inset: -2px;
            border-radius: 1.5rem;
            background: linear-gradient(90deg, rgba(124, 58, 237, 0.35), rgba(168, 85, 247, 0.35), rgba(59, 130, 246, 0.35));
            filter: blur(18px);
            opacity: 0.15;
            z-index: 0;
            pointer-events: none;
        }

        .glow-border>* {
            position: relative;
            z-index: 1;
        }

        .tab-active {
            background: linear-gradient(90deg, rgba(124, 58, 237, 0.45), rgba(168, 85, 247, 0.35));
            border-color: rgba(168, 85, 247, 0.35);
        }
    </style>

    <div id="general-page">
        @include('templates.bento.blades.user.trading.margin_inner')
    </div>
@endsection

@section('scripts')
    <script src="https://s3.tradingview.com/tv.js"></script>
    <script>
        $(document).ready(function() {
            let currentSymbol = "{{ $current_ticker }}";
            let currentPrice = {{ $current_ticker_info['current_price'] ?? 0 }};
            let availableBalance = {{ $add_available }};
            let isBuy = true;
            let currentLeverage = 3;
            let currentInterval = "15";
            let showToolbar = false;
            let isDragging = false;

            // Initialize TradingView Chart
            function initChart(symbol, interval = '15', showToolbar = false) {
                $('#chartLoader').removeClass('hidden');

                let tvSymbol = "BINANCE:" + symbol;
                $('#chartContainer').html('');

                const script = document.createElement('script');
                script.src = 'https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js';
                script.async = true;
                script.innerHTML = JSON.stringify({
                    "autosize": true,
                    "symbol": tvSymbol,
                    "interval": interval,
                    "timezone": "Etc/UTC",
                    "theme": "dark",
                    "style": "1",
                    "locale": "en",
                    "enable_publishing": false,
                    "backgroundColor": "rgba(19, 23, 34, 1)",
                    "gridColor": "rgba(42, 46, 57, 0.5)",
                    "hide_top_toolbar": !showToolbar,
                    "hide_legend": true,
                    "save_image": false,
                    "calendar": false,
                    "allow_symbol_change": true,
                    "hide_volume": true,
                    "support_host": "https://www.tradingview.com"
                });
                document.getElementById('chartContainer').appendChild(script);

                setTimeout(() => {
                    $('#chartLoader').addClass('hidden');
                }, 1500);
            }

            function updateSubmitButton() {
                const $btn = $('#btnSubmit');
                const order_mode = $('.margin-mode-btn.bg-white\\/10').data('mode') || 'normal';

                if (order_mode === 'repay') {
                    $btn.text("{{ __('Repay Now') }}")
                        .removeClass(
                            'from-green-500/70 via-green-400/70 to-emerald-500/70 border-green-500/30 shadow-green-500/10 from-red-500/70 via-red-400/70 to-rose-500/70 border-red-500/30 shadow-red-500/10'
                        )
                        .addClass(
                            'from-accent-primary/70 via-accent-primary/60 to-accent-primary/70 border-accent-primary/30 shadow-accent-primary/10'
                        );
                    return;
                }

                if (isBuy) {
                    $btn.text("{{ __('Buy Now') }}")
                        .removeClass(
                            'from-red-500/70 via-red-400/70 to-rose-500/70 border-red-500/30 shadow-red-500/10 from-accent-primary/70 via-accent-primary/60 to-accent-primary/70 border-accent-primary/30 shadow-accent-primary/10'
                        )
                        .addClass(
                            'from-green-500/70 via-green-400/70 to-emerald-500/70 border-green-500/30 shadow-green-500/10'
                        );
                } else {
                    $btn.text("{{ __('Sell Now') }}")
                        .removeClass(
                            'from-green-500/70 via-green-400/70 to-emerald-500/70 border-green-500/30 shadow-green-500/10 from-accent-primary/70 via-accent-primary/60 to-accent-primary/70 border-accent-primary/30 shadow-accent-primary/10'
                        )
                        .addClass(
                            'from-red-500/70 via-red-400/70 to-rose-500/70 border-red-500/30 shadow-red-500/10');
                }
            }


            // Event Delegation
            $(document).on('click', '#btnBuy', function() {
                isBuy = true;
                $(this).removeClass('text-white/70').addClass(
                    'bg-green-500/20 border-green-500/30 text-green-400');
                $('#btnSell').removeClass('bg-red-500/20 border-red-500/30 text-red-400').addClass(
                    'text-white/70');
                updateSubmitButton();
            });

            $(document).on('click', '#btnSell', function() {
                isBuy = false;
                $(this).removeClass('text-white/70').addClass(
                    'bg-red-500/20 border-red-500/30 text-red-400');
                $('#btnBuy').removeClass('bg-green-500/20 border-green-500/30 text-green-400').addClass(
                    'text-white/70');
                updateSubmitButton();
            });

            $(document).on('click', '.order-item', function() {
                let price = $(this).data('price');
                $('#inputPrice').val(price);
            });

            $(document).on('click', '.pct-btn', function() {
                let pct = $(this).data('pct');
                let amount = (availableBalance * pct) / 100;
                $('#inputAmount').val(amount.toFixed(4));
            });

            $(document).on('click', '.position-tab', function() {
                $('.position-tab').removeClass('bg-accent-primary/20 border-accent-primary/30 text-white')
                    .addClass('bg-white/5 border-white/10 text-white/70');
                $(this).removeClass('bg-white/5 border-white/10 text-white/70').addClass(
                    'bg-accent-primary/20 border-accent-primary/30 text-white');
                const target = $(this).data('target');
                $('.tab-content').addClass('hidden');
                $(target).removeClass('hidden');
            });

            $(document).on('click', '.order-type-tab', function() {
                $('.order-type-tab').removeClass('bg-accent-primary/20 border-accent-primary/30 text-white')
                    .addClass('bg-white/5 border-white/10 text-white/70');
                $(this).removeClass('bg-white/5 border-white/10 text-white/70').addClass(
                    'bg-accent-primary/20 border-accent-primary/30 text-white');
                const type = $(this).data('type');
                if (type === 'market') {
                    $('#priceInputGroup').addClass('hidden');
                    $('#inputPrice').prop('disabled', true);
                } else {
                    $('#priceInputGroup').removeClass('hidden');
                    $('#inputPrice').prop('disabled', false).val(currentPrice);
                }
            });

            $(document).on('click', '.time-btn', function() {
                $('.time-btn').removeClass('bg-accent-primary/20 border-accent-primary/30 text-white')
                    .addClass('bg-white/5 border-white/10 text-white/70');
                $(this).removeClass('bg-white/5 border-white/10 text-white/70').addClass(
                    'bg-accent-primary/20 border-accent-primary/30 text-white');
                currentInterval = $(this).attr('data-interval');
                initChart(currentSymbol, currentInterval, showToolbar);
            });

            $(document).on('click', '#toggleToolbarBtn', function() {
                showToolbar = !showToolbar;
                if (showToolbar) {
                    $(this).text("{{ __('Hide') }}").addClass(
                        'text-accent-primary border-accent-primary/30 bg-accent-primary/10');
                } else {
                    $(this).text("{{ __('Show') }}").removeClass(
                        'text-accent-primary border-accent-primary/30 bg-accent-primary/10');
                }
                initChart(currentSymbol, currentInterval, showToolbar);
            });

            $(document).on('click', '.margin-mode-btn', function() {
                $('.margin-mode-btn').removeClass(
                    'bg-white/10 text-white font-medium shadow-sm border border-white/5').addClass(
                    'hover:bg-white/5 text-white/60 transition').removeAttr('style');
                $(this).removeClass('hover:bg-white/5 text-white/60 transition').addClass(
                    'bg-white/10 text-white font-medium shadow-sm border border-white/5');

                const mode = $(this).data('mode');
                if (mode === 'repay') {
                    $('#priceInputGroup').addClass('hidden');
                } else {
                    const type = $('.order-type-tab.bg-accent-primary\\/20').data('type');
                    if (type === 'limit') {
                        $('#priceInputGroup').removeClass('hidden');
                    }
                }
                updateSubmitButton();
            });

            $(document).on('click', '#pairDropdownBtn', function(e) {
                e.stopPropagation();
                $('#pairDropdownMenu').toggleClass('hidden');
            });

            // Leverage Selection Logic
            $(document).on('click', '#leverageDropdownBtn', function(e) {
                e.stopPropagation();
                $('#leverageDropdownMenu').toggleClass('hidden');
            });

            $(document).on('click', '.leverage-option', function() {
                currentLeverage = $(this).data('leverage');
                let marginText = "{{ __('Margin') }}";
                $('#currentLeverageLabel').text(marginText + ' ' + currentLeverage + 'x');
                $('.leverage-display').text(marginText + ' ' + currentLeverage + 'x');
                $('#leverageDropdownMenu').addClass('hidden');
            });

            $(document).click(function(e) {
                const $lMenu = $('#leverageDropdownMenu');
                const $lBtn = $('#leverageDropdownBtn');
                if (!$lMenu.is(e.target) && $lMenu.has(e.target).length === 0 && !$lBtn.is(e.target) &&
                    $lBtn
                    .has(e.target).length === 0) {
                    $lMenu.addClass('hidden');
                }

                const $menu = $('#pairDropdownMenu');
                const $btn = $('#pairDropdownBtn');
                if (!$menu.is(e.target) && $menu.has(e.target).length === 0 && !$btn.is(e.target) && $btn
                    .has(e.target).length === 0) {
                    $menu.addClass('hidden');
                }
            });

            $(document).on('input', '#pairSearch', function() {
                let val = $(this).val().toLowerCase();
                $('.pair-item').each(function() {
                    let ticker = $(this).find('.font-semibold').text().toLowerCase();
                    if (ticker.includes(val)) {
                        $(this).removeClass('hidden').addClass('flex');
                    } else {
                        $(this).removeClass('flex').addClass('hidden');
                    }
                });
            });

            $(document).on('click', '#btnSubmit', function() {
                const type = $('.order-type-tab.bg-accent-primary\\/20').data('type');
                const order_mode = $('.margin-mode-btn.bg-white\\/10').data('mode') || 'normal';
                const price = $('#inputPrice').val();
                const amount = $('#inputAmount').val();
                const side = isBuy ? 'buy' : 'sell';

                if (!amount || amount <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Amount',
                        text: 'Please enter a valid amount'
                    });
                    return;
                }

                if (type === 'limit' && (!price || price <= 0)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Price',
                        text: 'Please enter a valid price for limit order'
                    });
                    return;
                }

                confirmAction({
                    title: 'Confirm ' + (isBuy ? 'Buy' : 'Sell'),
                    text: 'Are you sure you want to place this ' + type + ' order?',
                    confirmButtonText: 'Yes, Place Order'
                }).then((result) => {
                    if (result) {
                        const $btn = $(this);
                        const originalText = $btn.text();
                        $btn.prop('disabled', true).text('Processing...');

                        $.ajax({
                            url: "{{ route('user.trading.margin.trade') }}",
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ticker: currentSymbol,
                                type: type,
                                order_mode: order_mode,
                                side: side,
                                amount: amount,
                                price: price,
                                leverage: currentLeverage
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                loadTickerData(window.location.href, currentSymbol,
                                    false);
                                $('#inputAmount').val('');
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Order Failed',
                                    text: xhr.responseJSON ? xhr.responseJSON
                                        .message : 'An error occurred'
                                });
                            },
                            complete: function() {
                                $btn.prop('disabled', false).text(originalText);
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.btn-cancel-order', function() {
                const orderId = $(this).data('order-id');

                confirmAction({
                    title: 'Cancel Order',
                    text: 'Are you sure you want to cancel this order?',
                    confirmButtonText: 'Yes, Cancel It',
                    mode: 'danger'
                }).then((result) => {
                    if (result) {
                        const $btn = $(this);
                        $btn.prop('disabled', true).text('...');

                        $.ajax({
                            url: "{{ route('user.trading.margin.cancel-order') }}",
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                order_id: orderId
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Canceled',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                loadTickerData(window.location.href, currentSymbol,
                                    false);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Cancellation Failed',
                                    text: xhr.responseJSON ? xhr.responseJSON
                                        .message : 'An error occurred'
                                });
                            },
                            complete: function() {
                                $btn.prop('disabled', false).text(
                                    "{{ __('Cancel') }}");
                            }
                        });
                    }
                });
            });

            // AJAX Data Loading
            function loadTickerData(url, ticker, isFullReplace = false) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        if (isFullReplace) {
                            $('#general-page').html(response);
                            history.pushState(null, '', url);
                            currentSymbol = ticker;

                            const rawPrice = $('#lastPrice').text().replace(/,/g, '');
                            currentPrice = parseFloat(rawPrice) || 0;
                            const rawBalance = $('#availableBalanceValue').text().replace(/,/g, '');
                            availableBalance = parseFloat(rawBalance) || 0;

                            initChart(currentSymbol, currentInterval, showToolbar);
                            // Sync leverage UI after load
                            let marginText = "{{ __('Margin') }}";
                            $('#currentLeverageLabel').text(marginText + ' ' + currentLeverage + 'x');
                            $('.leverage-display').text(marginText + ' ' + currentLeverage + 'x');
                        } else {
                            const $newData = $(response);
                            $('#topPanelStats').html($newData.find('#topPanelStats').html());
                            $('#orderBookContainer').html($newData.find('#orderBookContainer').html());
                            $('#recentTradesContainer').html($newData.find('#recentTradesContainer')
                                .html());

                            // Update Tabs
                            $('#tab-balance').html($newData.find('#tab-balance').html());
                            $('#tab-positions').html($newData.find('#tab-positions').html());
                            $('#tab-open-orders').html($newData.find('#tab-open-orders').html());
                            $('#tab-closed-orders').html($newData.find('#tab-closed-orders').html());

                            // Update Balance Display
                            const newBalanceHtml = $newData.find('#availableBalanceValue').html();
                            $('#availableBalanceValue').html(newBalanceHtml);
                            const rawBalance = newBalanceHtml.replace(/,/g, '');
                            availableBalance = parseFloat(rawBalance) || 0;

                            const rawPrice = $('#lastPrice').text().replace(/,/g, '');
                            currentPrice = parseFloat(rawPrice) || 0;
                        }
                    }
                });
            }

            $(document).on('click', '.pair-item', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const ticker = $(this).find('.font-semibold').text();
                loadTickerData(url, ticker, true);
            });

            // Initial Setup
            initChart(currentSymbol);

            // 10 second data polling
            setInterval(() => {
                const url = window.location.href;
                loadTickerData(url, currentSymbol, false);
            }, 10000);

            // 1 second clock
            setInterval(() => {
                const now = new Date();
                const options = {
                    timeZone: '{{ config('app.timezone') }}',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                };
                const timeString = now.toLocaleTimeString('en-GB', options);
                $('#chartTime').text(timeString + ' {{ config('app.timezone') }}');
            }, 1000);
        });
    </script>
@endsection
