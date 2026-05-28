@php

    $marketPairs = $all_crypto_tickers;
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

    <div class="min-h-screen px-2 md:px-0" id="general-page">
        @include('templates.bento.blades.user.trading.futures_inner')
    </div>
@endsection

@section('scripts')
    <script src="https://s3.tradingview.com/tv.js"></script>
    <script>
        $(document).ready(function() {
            let currentSymbol = "{{ $current_ticker }}";
            let currentPrice = {{ $current_ticker_info['current_price'] ?? 0 }};
            let availableBalance = {{ $add_available ?? 0 }};
            let isBuy = true;
            let currentInterval = "15";
            let showToolbar = false;
            let isDragging = false;

            // Initialize TradingView Chart
            function initChart(symbol, interval = '15', showToolbar = false) {
                $('#chartLoader').removeClass('hidden');
                let tvSymbol = "BINANCE:" + symbol + ".P";
                $('#chartContainer').html('');

                const script = document.createElement('script');
                script.src = 'https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js';
                script.async = true;
                script.innerHTML = JSON.stringify({
                    "autosize": true,
                    "symbol": tvSymbol,
                    "interval": interval,
                    "timezone": "{{ config('app.timezone') }}",
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
                if (isBuy) {
                    $btn.text("{{ __('Buy/Long') }}")
                        .removeClass(
                            'from-red-500/70 via-red-400/70 to-rose-500/70 border-red-500/30 shadow-red-500/10')
                        .addClass(
                            'from-green-500/70 via-green-400/70 to-emerald-500/70 border-green-500/30 shadow-green-500/10'
                        );
                } else {
                    $btn.text("{{ __('Sell/Short') }}")
                        .removeClass(
                            'from-green-500/70 via-green-400/70 to-emerald-500/70 border-green-500/30 shadow-green-500/10'
                        )
                        .addClass(
                            'from-red-500/70 via-red-400/70 to-rose-500/70 border-red-500/30 shadow-red-500/10');
                }
            }

            // Leverage Slider Logic
            function initLeverageSlider() {
                const $slider = $('#leverageContainer');
                const $bar = $('#leverageBar');
                const $val = $('#leverageValue');
                const $input = $('#inputLeverage');
                const maxLeverage = 50;

                function updateLeverage(e) {
                    if (!$slider.length) return;
                    const rect = $slider[0].getBoundingClientRect();
                    const clientX = e.clientX || (e.originalEvent.touches ? e.originalEvent.touches[0].clientX : e
                        .clientX);
                    let x = clientX - rect.left;
                    let percent = (x / rect.width) * 100;
                    if (percent < 0) percent = 0;
                    if (percent > 100) percent = 100;
                    let leverage = Math.max(1, Math.round((percent / 100) * maxLeverage));
                    $bar.css('width', (leverage / maxLeverage * 100) + '%');
                    $val.text(leverage + 'x');
                    $input.val(leverage);
                }

                $slider.on('mousedown touchstart', function(e) {
                    isDragging = true;
                    updateLeverage(e);
                });

                $(document).on('mousemove touchmove', function(e) {
                    if (isDragging) updateLeverage(e);
                });

                $(document).on('mouseup touchend', function() {
                    isDragging = false;
                });
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
                let leverage = parseInt($('#inputLeverage').val()) || 1;
                let amount = (availableBalance * pct / 100) * leverage;
                $('#inputAmount').val(amount.toFixed(2));
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

            // Initial state for Market orders
            if ($('.order-type-tab[data-type="market"]').hasClass('bg-accent-primary/20')) {
                $('#priceInputGroup').addClass('hidden');
                $('#inputPrice').prop('disabled', true);
            }

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

            $(document).on('click', '#pairDropdownBtn', function(e) {
                e.stopPropagation();
                $('#pairDropdownMenu').toggleClass('hidden');
            });

            $(document).click(function(e) {
                const $menu = $('#pairDropdownMenu');
                const $btn = $('#pairDropdownBtn');
                if (!$menu.is(e.target) && $menu.has(e.target).length === 0 && !$btn.is(e.target) && $btn
                    .has(e.target).length === 0) {
                    $menu.addClass('hidden');
                }
            });

            $(document).on('click', '.btn-cancel-order', function() {
                const $btn = $(this);
                const orderId = $btn.data('order-id');

                confirmAction(
                    "{{ __('Cancel Order?') }}",
                    "{{ __('Are you sure you want to cancel this order? Locked margin will be returned to your account.') }}",
                    'warning',
                    "{{ __('Confirm Cancellation') }}",
                    true,
                    "{{ __('Go Back') }}"
                ).then((confirmed) => {
                    if (!confirmed) return;

                    $btn.prop('disabled', true).html(
                        '<svg class="animate-spin h-3 w-3 mx-auto text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'
                    );

                    $.ajax({
                        url: "{{ route('user.trading.futures.cancel-order') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            order_id: orderId
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                toastNotification(response.message, 'success');
                                loadTickerData(window.location.href, currentSymbol,
                                    false);
                            } else {
                                toastNotification(response.message, 'error');
                                $btn.prop('disabled', false).text(
                                    "{{ __('Cancel') }}");
                            }
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON ? xhr.responseJSON
                                .message :
                                "{{ __('An error occurred') }}";
                            toastNotification(message, 'error');
                            $btn.prop('disabled', false).text("{{ __('Cancel') }}");
                        }
                    });
                });
            });

            $(document).on('click', '#btnSubmit', function() {
                const $btn = $(this);
                const originalText = $btn.text();

                // Validation
                const amount = $('#inputAmount').val();
                if (!amount || amount <= 0) {
                    toastNotification("{{ __('Please enter a valid amount') }}", 'error');
                    return;
                }

                const price = $('#inputPrice').val();
                const type = $('.order-type-tab.bg-accent-primary\\/20').data('type');

                if (type === 'limit' && (!price || price <= 0)) {
                    toastNotification("{{ __('Please enter a valid price for limit order') }}", 'error');
                    return;
                }

                const tp = $('#inputTP').val();
                if (!tp || tp <= 0) {
                    toastNotification("{{ __('Please enter a valid Take Profit price') }}", 'error');
                    return;
                }

                const sl = $('#inputSL').val();
                if (!sl || sl <= 0) {
                    toastNotification("{{ __('Please enter a valid Stop Loss price') }}", 'error');
                    return;
                }

                $btn.prop('disabled', true).html(
                    '<svg class="animate-spin h-5 w-5 mx-auto text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'
                );

                $.ajax({
                    url: "{{ route('user.trading.futures.trade') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ticker: currentSymbol,
                        type: type,
                        side: isBuy ? 'buy' : 'sell',
                        amount: amount,
                        price: price,
                        leverage: $('#inputLeverage').val(),
                        take_profit: tp,
                        stop_loss: sl
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            toastNotification(response.message, 'success');
                            // Refresh data
                            loadTickerData(window.location.href, currentSymbol, false);
                        } else {
                            toastNotification(response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON ? xhr.responseJSON.message :
                            "{{ __('An error occurred') }}";
                        toastNotification(message, 'error');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text(originalText);
                    }
                });
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

                            // Update current price and balance from elements
                            const rawPrice = $('#lastPrice').text().replace(/,/g, '');
                            currentPrice = parseFloat(rawPrice) || 0;
                            const rawBalance = $('#availableBalanceValue').text().replace(/,/g, '');
                            availableBalance = parseFloat(rawBalance) || 0;

                            // Re-initialize chart and slider
                            initChart(currentSymbol, currentInterval, showToolbar);
                            initLeverageSlider();
                        } else {
                            // Targeted updates
                            const $newData = $(response);
                            $('#topPanelStats').html($newData.find('#topPanelStats').html());
                            // Update Tabs
                            $('#tab-positions').html($newData.find('#tab-positions').html());
                            $('#tab-open-orders').html($newData.find('#tab-open-orders').html());
                            $('#tab-closed-orders').html($newData.find('#tab-closed-orders').html());

                            $('#orderBookContainer').html($newData.find('#orderBookContainer').html());
                            $('#recentTradesContainer').html($newData.find('#recentTradesContainer')
                                .html());

                            // Update Price & Balance Reference
                            const rawPrice = $('#lastPrice').text().replace(/,/g, '');
                            currentPrice = parseFloat(rawPrice) || 0;
                            const rawBalance = $('#availableBalanceValue').text().replace(/,/g, '');
                            availableBalance = parseFloat(rawBalance) || 0;
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
            initLeverageSlider();

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
