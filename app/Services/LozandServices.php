<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * LozandServices
 *
 * Market data is sourced from two free APIs:
 *  - Binance Public API  (no key required) — crypto futures & spot/margin
 *  - Twelve Data API     (free API key)    — stocks, ETFs, forex
 *
 * Static data is used for bonds and mutual funds.
 * IP lookup is handled via ipify.org (no key required).
 */
class LozandServices
{
    // ── Binance endpoints (no API key required) ────────────────────────────
    protected string $binanceFuturesUrl = 'https://fapi.binance.com/fapi/v1';
    protected string $binanceSpotUrl    = 'https://api.binance.com/api/v3';

    // ── Twelve Data endpoint ───────────────────────────────────────────────
    protected string $twelveDataUrl;
    protected string $twelveDataKey;

    // ── Curated instrument lists ───────────────────────────────────────────
    protected array $marginSymbols = [
        'BTCUSDT', 'ETHUSDT', 'BNBUSDT', 'SOLUSDT', 'XRPUSDT',
        'ADAUSDT', 'DOTUSDT', 'AVAXUSDT', 'MATICUSDT', 'LINKUSDT',
        'DOGEUSDT', 'LTCUSDT', 'UNIUSDT', 'ATOMUSDT', 'ETCUSDT',
    ];

    protected array $stockSymbols = [
        'AAPL', 'MSFT', 'GOOGL', 'AMZN', 'NVDA', 'META', 'TSLA',
        'UNH',  'JPM',  'JNJ',   'V',    'PG',   'MA',   'HD',
        'BAC',  'XOM',  'ABBV',  'MRK',  'CVX',  'PFE',  'WMT',
        'KO',   'NFLX', 'DIS',   'INTC', 'AMD',  'CSCO', 'ORCL',
    ];

    protected array $etfSymbols = [
        'SPY', 'QQQ', 'IWM', 'EFA', 'AGG', 'VTI', 'BND',
        'GLD', 'SLV', 'TLT', 'HYG', 'LQD', 'VNQ', 'XLE',
        'XLF', 'XLV', 'XLK', 'XLI', 'XLU', 'XLC',
    ];

    protected array $forexPairs = [
        'EUR/USD', 'GBP/USD', 'USD/JPY', 'USD/CHF', 'AUD/USD',
        'USD/CAD', 'NZD/USD', 'EUR/GBP', 'EUR/JPY', 'GBP/JPY',
        'EUR/CHF', 'AUD/JPY', 'USD/HKD', 'USD/SGD', 'USD/MXN',
    ];

    public function __construct()
    {
        $this->twelveDataUrl = config('services.twelvedata.base_url', 'https://api.twelvedata.com');
        $this->twelveDataKey = config('services.twelvedata.api_key', '');
    }

    // ══════════════════════════════════════════════════════════════════════
    //  STOCKS  (Twelve Data)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Get market stocks data.
     */
    public function marketStocks(): array
    {
        if (Cache::has('market_stocks')) {
            return Cache::get('market_stocks');
        }

        $result = $this->fetchTwelveDataBatch($this->stockSymbols);
        if ($result['status'] === 'success') {
            Cache::put('market_stocks', $result, now()->addHours(6));
        }
        return $result;
    }

    /**
     * Get ticker information for a single stock.
     */
    public function ticker(string $ticker): array
    {
        $cacheKey = 'stock_ticker_' . strtoupper($ticker);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $result = $this->fetchTwelveDataSingle(strtoupper($ticker));
        if ($result['status'] === 'success') {
            Cache::put($cacheKey, $result, now()->addMinutes(15));
        }
        return $result;
    }

    // ══════════════════════════════════════════════════════════════════════
    //  ETFs  (Twelve Data)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Get market ETFs data.
     */
    public function marketEtfs(): array
    {
        if (Cache::has('market_etfs')) {
            return Cache::get('market_etfs');
        }

        $result = $this->fetchTwelveDataBatch($this->etfSymbols);
        if ($result['status'] === 'success') {
            Cache::put('market_etfs', $result, now()->addHours(6));
        }
        return $result;
    }

    /**
     * Get ETF ticker information.
     */
    public function etfTicker(string $ticker): array
    {
        $cacheKey = 'etf_ticker_' . strtoupper($ticker);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $result = $this->fetchTwelveDataSingle(strtoupper($ticker));
        if ($result['status'] === 'success') {
            Cache::put($cacheKey, $result, now()->addMinutes(15));
        }
        return $result;
    }

    // ══════════════════════════════════════════════════════════════════════
    //  FOREX  (Twelve Data)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Get all forex tickers.
     */
    public function forexTickers(): array
    {
        $cacheKey = 'forex_tickers';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        if (empty($this->twelveDataKey)) {
            return ['status' => 'error', 'message' => 'Twelve Data API key not configured.', 'code' => 500];
        }

        try {
            $symbols = implode(',', $this->forexPairs);
            $response = Http::timeout(30)->get($this->twelveDataUrl . '/quote', [
                'symbol'   => $symbols,
                'apikey'   => $this->twelveDataKey,
            ]);

            if ($response->successful()) {
                $json = $response->json();

                // When a single symbol is requested Twelve Data returns the object directly,
                // for multiple symbols it returns an associative array keyed by symbol.
                if (isset($json['symbol'])) {
                    $json = [$json['symbol'] => $json];
                }

                $data = [];
                foreach ($json as $symbol => $quote) {
                    if (!isset($quote['close'])) {
                        continue;
                    }
                    $data[] = $this->normalizeForexQuote($quote);
                }

                $result = ['status' => 'success', 'data' => $data, 'code' => 200];
                Cache::put($cacheKey, $result, now()->addMinutes(5));
                return $result;
            }

            $msg = $response->json()['message'] ?? 'Request failed with status: ' . $response->status();
            Log::error('[LozandServices] forexTickers: ' . $response->body());
            return ['status' => 'error', 'message' => $msg, 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] forexTickers: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    /**
     * Get a single forex ticker.
     */
    public function forexTicker(string $ticker): array
    {
        // Accept both EUR_USD and EUR/USD formats
        $ticker    = str_replace('_', '/', strtoupper($ticker));
        $cacheKey  = 'forex_ticker_' . str_replace('/', '_', $ticker);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        if (empty($this->twelveDataKey)) {
            return ['status' => 'error', 'message' => 'Twelve Data API key not configured.', 'code' => 500];
        }

        try {
            $response = Http::timeout(30)->get($this->twelveDataUrl . '/quote', [
                'symbol' => $ticker,
                'apikey' => $this->twelveDataKey,
            ]);

            if ($response->successful()) {
                $quote = $response->json();
                if (isset($quote['close'])) {
                    $result = ['status' => 'success', 'data' => $this->normalizeForexQuote($quote), 'code' => 200];
                    Cache::put($cacheKey, $result, now()->addMinutes(5));
                    return $result;
                }
            }

            $msg = $response->json()['message'] ?? 'Request failed with status: ' . $response->status();
            Log::error('[LozandServices] forexTicker: ' . $response->body());
            return ['status' => 'error', 'message' => $msg, 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] forexTicker: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  BONDS  (static US Treasury data)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Get all bonds (static US Treasury list).
     */
    public function bonds(): array
    {
        if (Cache::has('bonds')) {
            return Cache::get('bonds');
        }

        $data   = $this->staticBonds();
        $result = ['status' => 'success', 'data' => $data, 'code' => 200];
        Cache::put('bonds', $result, now()->addHours(6));
        return $result;
    }

    /**
     * Get a single bond by CUSIP.
     */
    public function bond(string $cusip): array
    {
        $cacheKey = 'bond_' . $cusip;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $bond = collect($this->staticBonds())->firstWhere('cusip', $cusip);

        if (!$bond) {
            return ['status' => 'error', 'message' => 'Bond not found.', 'code' => 404];
        }

        $result = ['status' => 'success', 'data' => $bond, 'code' => 200];
        Cache::put($cacheKey, $result, now()->addHours(6));
        return $result;
    }

    // ══════════════════════════════════════════════════════════════════════
    //  FUTURES  (Binance Futures API — no key required)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Get all futures tickers.
     */
    public function futureTickers(): array
    {
        $cacheKey = 'future_tickers';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)->get($this->binanceFuturesUrl . '/ticker/24hr');

            if ($response->successful()) {
                $raw  = $response->json();
                $data = array_map([$this, 'normalizeBinanceTicker'], $raw);
                $result = ['status' => 'success', 'data' => $data, 'code' => 200];
                Cache::put($cacheKey, $result, now()->addSeconds(6));
                return $result;
            }

            $msg = $response->json()['msg'] ?? 'Request failed with status: ' . $response->status();
            Log::error('[LozandServices] futureTickers: ' . $response->body());
            return ['status' => 'error', 'message' => $msg, 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] futureTickers: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    /**
     * Get a single futures ticker.
     */
    public function futureTicker(string $ticker): array
    {
        $cacheKey = 'future_ticker_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)->get($this->binanceFuturesUrl . '/ticker/24hr', [
                'symbol' => strtoupper($ticker),
            ]);

            if ($response->successful()) {
                $result = [
                    'status' => 'success',
                    'data'   => $this->normalizeBinanceTicker($response->json()),
                    'code'   => 200,
                ];
                Cache::put($cacheKey, $result, now()->addSeconds(6));
                return $result;
            }

            $msg = $response->json()['msg'] ?? 'Request failed with status: ' . $response->status();
            Log::error('[LozandServices] futureTicker: ' . $response->body());
            return ['status' => 'error', 'message' => $msg, 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] futureTicker: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    /**
     * Get futures order book.
     */
    public function futuresOrderBook(string $ticker): array
    {
        $cacheKey = 'futures_order_book_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)->get($this->binanceFuturesUrl . '/depth', [
                'symbol' => strtoupper($ticker),
                'limit'  => 20,
            ]);

            if ($response->successful()) {
                $json   = $response->json();
                $result = [
                    'status' => 'success',
                    'data'   => [
                        'bids' => $json['bids'] ?? [],
                        'asks' => $json['asks'] ?? [],
                    ],
                    'code'   => 200,
                ];
                Cache::put($cacheKey, $result, now()->addSeconds(6));
                return $result;
            }

            $msg = $response->json()['msg'] ?? 'Request failed with status: ' . $response->status();
            Log::error('[LozandServices] futuresOrderBook: ' . $response->body());
            return ['status' => 'error', 'message' => $msg, 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] futuresOrderBook: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    /**
     * Get futures recent trades.
     */
    public function futuresRecentTrades(string $ticker): array
    {
        $cacheKey = 'futures_recent_trades_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)->get($this->binanceFuturesUrl . '/trades', [
                'symbol' => strtoupper($ticker),
                'limit'  => 30,
            ]);

            if ($response->successful()) {
                $data = array_map([$this, 'normalizeBinanceTrade'], $response->json());
                $result = ['status' => 'success', 'data' => $data, 'code' => 200];
                Cache::put($cacheKey, $result, now()->addSeconds(6));
                return $result;
            }

            $msg = $response->json()['msg'] ?? 'Request failed with status: ' . $response->status();
            Log::error('[LozandServices] futuresRecentTrades: ' . $response->body());
            return ['status' => 'error', 'message' => $msg, 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] futuresRecentTrades: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  MARGIN TRADING  (Binance Spot API — no key required)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Get all margin trading tickers (curated popular pairs).
     */
    public function margins(): array
    {
        $cacheKey = 'margins';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            // Fetch only our curated margin symbols to avoid downloading all 2000+ spot pairs
            $symbolsJson = json_encode($this->marginSymbols);
            $response = Http::timeout(30)->get($this->binanceSpotUrl . '/ticker/24hr', [
                'symbols' => $symbolsJson,
            ]);

            if ($response->successful()) {
                $data   = array_map([$this, 'normalizeBinanceTicker'], $response->json());
                $result = ['status' => 'success', 'data' => $data, 'code' => 200];
                Cache::put($cacheKey, $result, now()->addSeconds(30));
                return $result;
            }

            $msg = $response->json()['msg'] ?? 'Request failed with status: ' . $response->status();
            Log::error('[LozandServices] margins: ' . $response->body());
            return ['status' => 'error', 'message' => $msg, 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] margins: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    /**
     * Get a single margin trading ticker.
     */
    public function margin(string $ticker): array
    {
        $cacheKey = 'margin_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)->get($this->binanceSpotUrl . '/ticker/24hr', [
                'symbol' => strtoupper($ticker),
            ]);

            if ($response->successful()) {
                $result = [
                    'status' => 'success',
                    'data'   => $this->normalizeBinanceTicker($response->json()),
                    'code'   => 200,
                ];
                Cache::put($cacheKey, $result, now()->addSeconds(30));
                return $result;
            }

            $msg = $response->json()['msg'] ?? 'Request failed with status: ' . $response->status();
            Log::error('[LozandServices] margin: ' . $response->body());
            return ['status' => 'error', 'message' => $msg, 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] margin: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    /**
     * Get margin order book.
     */
    public function marginOrderBook(string $ticker): array
    {
        $cacheKey = 'margin_order_book_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)->get($this->binanceSpotUrl . '/depth', [
                'symbol' => strtoupper($ticker),
                'limit'  => 20,
            ]);

            if ($response->successful()) {
                $json   = $response->json();
                $result = [
                    'status' => 'success',
                    'data'   => [
                        'bids' => $json['bids'] ?? [],
                        'asks' => $json['asks'] ?? [],
                    ],
                    'code'   => 200,
                ];
                Cache::put($cacheKey, $result, now()->addSeconds(6));
                return $result;
            }

            $msg = $response->json()['msg'] ?? 'Request failed with status: ' . $response->status();
            Log::error('[LozandServices] marginOrderBook: ' . $response->body());
            return ['status' => 'error', 'message' => $msg, 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] marginOrderBook: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    /**
     * Get margin recent trades.
     */
    public function marginRecentTrades(string $ticker): array
    {
        $cacheKey = 'margin_recent_trades_' . $ticker;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout(30)->get($this->binanceSpotUrl . '/trades', [
                'symbol' => strtoupper($ticker),
                'limit'  => 30,
            ]);

            if ($response->successful()) {
                $data   = array_map([$this, 'normalizeBinanceTrade'], $response->json());
                $result = ['status' => 'success', 'data' => $data, 'code' => 200];
                Cache::put($cacheKey, $result, now()->addSeconds(6));
                return $result;
            }

            $msg = $response->json()['msg'] ?? 'Request failed with status: ' . $response->status();
            Log::error('[LozandServices] marginRecentTrades: ' . $response->body());
            return ['status' => 'error', 'message' => $msg, 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] marginRecentTrades: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  MUTUAL FUNDS  (static placeholder data)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Get mutual funds data (static curated list).
     */
    public function mutualFunds(): array
    {
        $cacheKey = 'mutual_funds';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $result = ['status' => 'success', 'data' => $this->staticMutualFunds(), 'code' => 200];
        Cache::put($cacheKey, $result, now()->addHours(6));
        return $result;
    }

    // ══════════════════════════════════════════════════════════════════════
    //  IP LOOKUP  (ipify.org — no key required)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Get the server's public IP address.
     */
    public function getIp(): array
    {
        try {
            $response = Http::timeout(10)->get('https://api.ipify.org', ['format' => 'json']);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data'   => ['ip' => $response->json()['ip'] ?? null],
                    'code'   => 200,
                ];
            }

            return ['status' => 'error', 'message' => 'IP lookup failed.', 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] getIp: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  PRIVATE HELPERS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Fetch a batch of symbols from Twelve Data and normalize each to the
     * standard stock/ETF structure expected by controllers and views.
     */
    private function fetchTwelveDataBatch(array $symbols): array
    {
        if (empty($this->twelveDataKey)) {
            return ['status' => 'error', 'message' => 'Twelve Data API key not configured.', 'code' => 500];
        }

        try {
            $symbolList = implode(',', $symbols);
            $response   = Http::timeout(30)->get($this->twelveDataUrl . '/quote', [
                'symbol' => $symbolList,
                'apikey' => $this->twelveDataKey,
            ]);

            if ($response->successful()) {
                $json = $response->json();

                // When exactly one symbol is queried the response is a plain object
                if (isset($json['symbol'])) {
                    $json = [$json['symbol'] => $json];
                }

                $data = [];
                foreach ($json as $symbol => $quote) {
                    if (!isset($quote['close'])) {
                        continue; // skip error entries (e.g. "status":"error")
                    }
                    $data[] = $this->normalizeTwelveDataQuote($quote);
                }

                return ['status' => 'success', 'data' => $data, 'code' => 200];
            }

            $msg = $response->json()['message'] ?? 'Request failed with status: ' . $response->status();
            Log::error('[LozandServices] fetchTwelveDataBatch: ' . $response->body());
            return ['status' => 'error', 'message' => $msg, 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] fetchTwelveDataBatch: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    /**
     * Fetch a single symbol from Twelve Data.
     */
    private function fetchTwelveDataSingle(string $symbol): array
    {
        if (empty($this->twelveDataKey)) {
            return ['status' => 'error', 'message' => 'Twelve Data API key not configured.', 'code' => 500];
        }

        try {
            $response = Http::timeout(30)->get($this->twelveDataUrl . '/quote', [
                'symbol' => $symbol,
                'apikey' => $this->twelveDataKey,
            ]);

            if ($response->successful()) {
                $quote = $response->json();
                if (isset($quote['close'])) {
                    return [
                        'status' => 'success',
                        'data'   => $this->normalizeTwelveDataQuote($quote),
                        'code'   => 200,
                    ];
                }
                // Twelve Data returns status=error inside a 200 response for invalid symbols
                $msg = $quote['message'] ?? 'Symbol not found.';
                return ['status' => 'error', 'message' => $msg, 'code' => 404];
            }

            $msg = $response->json()['message'] ?? 'Request failed with status: ' . $response->status();
            Log::error('[LozandServices] fetchTwelveDataSingle (' . $symbol . '): ' . $response->body());
            return ['status' => 'error', 'message' => $msg, 'code' => $response->status()];

        } catch (\Exception $e) {
            Log::error('[LozandServices] fetchTwelveDataSingle: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage(), 'code' => 500];
        }
    }

    /**
     * Normalize a Twelve Data quote to the standard stock/ETF structure.
     */
    private function normalizeTwelveDataQuote(array $quote): array
    {
        $fiftyTwoWeek = $quote['fifty_two_week'] ?? [];
        $symbol       = $quote['symbol'] ?? '';
        $changePct    = (float)($quote['percent_change'] ?? 0);
        $change       = (float)($quote['change']         ?? 0);

        return [
            'ticker'                    => $symbol,
            'name'                      => $quote['name']             ?? '',
            'exchange'                  => $quote['exchange']         ?? '',
            'currency'                  => $quote['currency']         ?? 'USD',
            'current_price'             => (float)($quote['close']    ?? 0),
            'open'                      => (float)($quote['open']     ?? 0),
            'high'                      => (float)($quote['high']     ?? 0),
            'low'                       => (float)($quote['low']      ?? 0),
            'previous_close'            => (float)($quote['previous_close'] ?? 0),
            'change'                    => $change,
            'change_percent'            => $changePct,
            'volume'                    => (float)($quote['volume']   ?? 0),
            '52w_high'                  => (float)($fiftyTwoWeek['high'] ?? 0),
            '52w_low'                   => (float)($fiftyTwoWeek['low']  ?? 0),
            // Fields used by ETF portfolio calculations (not in free tier)
            'ytd_return'                => 0,
            'change_50_day_percentage'  => 0,
            'change_200_day_percentage' => 0,
            // Aliases expected by existing blade templates (previously from Binso)
            'change_1d'                 => $change,
            'change_1d_percentage'      => $changePct,
            'change_1d_percent'         => $changePct,
            // Stock detail fields (not in free tier — safe empty defaults)
            'sector'                    => $quote['sector']              ?? '',
            'cik'                       => $quote['cik']                 ?? '',
            'dividend_yield'            => (float)($quote['dividend_yield'] ?? 0),
            // ETF-specific fields (free tier doesn't return AUM or NAV separately)
            'assets_under_management'   => (float)($quote['aum']         ?? 0),
            'current_nav'               => (float)($quote['nav']         ?? $quote['close'] ?? 0),
            // Stock logo via Clearbit Logo API (returns blank image if not found, never crashes)
            'public_png_logo_url'       => 'https://logo.clearbit.com/' . strtolower($symbol) . '.com',
        ];
    }

    /**
     * Normalize a Twelve Data forex quote to the structure expected by ForexController.
     *
     * ForexController accesses: $ticker['s'], $ticker['a'], $ticker['b']
     */
    private function normalizeForexQuote(array $quote): array
    {
        $close = (float)($quote['close'] ?? 0);
        $bid   = isset($quote['bid'])  ? (float)$quote['bid']  : $close;
        $ask   = isset($quote['ask'])  ? (float)$quote['ask']  : $close;

        return [
            's'               => $quote['symbol']          ?? '',   // e.g. "EUR/USD"
            'a'               => $ask,                               // ask price
            'b'               => $bid,                               // bid price
            'current_price'   => $close,
            'open'            => (float)($quote['open']           ?? 0),
            'high'            => (float)($quote['high']           ?? 0),
            'low'             => (float)($quote['low']            ?? 0),
            'change'          => (float)($quote['change']         ?? 0),
            'percent_change'  => (float)($quote['percent_change'] ?? 0),
        ];
    }

    /**
     * Normalize a Binance 24hr ticker to the standard crypto structure.
     *
     * Works for both the Futures API and the Spot API response shapes.
     */
    private function normalizeBinanceTicker(array $ticker): array
    {
        $symbol    = $ticker['symbol'] ?? '';
        $change    = (float)($ticker['priceChange']        ?? 0);
        $changePct = (float)($ticker['priceChangePercent'] ?? 0);

        // Derive base/quote currencies from symbol (e.g. "BTCUSDT" → base="BTC", quote="USDT")
        $quoteAssets = ['USDT', 'BUSD', 'USDC', 'BNB', 'BTC', 'ETH'];
        $quoteAsset  = 'USDT';
        $baseAsset   = $symbol;
        foreach ($quoteAssets as $qa) {
            if (str_ends_with($symbol, $qa)) {
                $quoteAsset = $qa;
                $baseAsset  = substr($symbol, 0, -strlen($qa));
                break;
            }
        }

        // Derive a crypto logo filename for the cryptocurrency-icons SVG repo.
        $logo = $baseAsset ? strtolower($baseAsset) . '.svg' : 'generic.svg';

        return [
            'ticker'               => $symbol,
            'base'                 => $baseAsset,   // e.g. "BTC"
            'quote'                => $quoteAsset,  // e.g. "USDT"
            'current_price'        => (float)($ticker['lastPrice']  ?? 0),
            'open_price'           => (float)($ticker['openPrice']  ?? 0),
            'bid'                  => (float)($ticker['bidPrice']   ?? 0),
            'ask'                  => (float)($ticker['askPrice']   ?? 0),
            'change'               => $change,
            'change_percent'       => $changePct,
            'high_24h'             => (float)($ticker['highPrice']  ?? 0),
            'low_24h'              => (float)($ticker['lowPrice']   ?? 0),
            // Blade aliases — some templates use 'high'/'low' directly
            'high'                 => (float)($ticker['highPrice']  ?? 0),
            'low'                  => (float)($ticker['lowPrice']   ?? 0),
            'volume_24h'           => (float)($ticker['volume']     ?? 0),
            'quote_volume'         => (float)($ticker['quoteVolume'] ?? 0),
            // Aliases expected by existing blade templates (previously from Binso)
            'logo'                 => $logo,
            'change_1d'            => $change,
            'change_1d_percentage' => $changePct,
            'change_1d_percent'    => $changePct,
        ];
    }

    /**
     * Normalize a Binance trade entry.
     */
    private function normalizeBinanceTrade(array $trade): array
    {
        $isBuyerMaker = $trade['isBuyerMaker'] ?? false;
        return [
            'id'              => $trade['id']    ?? null,
            'price'           => $trade['price'] ?? '0',
            'qty'             => $trade['qty']   ?? '0',
            'time'            => $trade['time']  ?? 0,
            'side'            => $isBuyerMaker ? 'sell' : 'buy',
            'is_buyer_maker'  => $isBuyerMaker,
            'isBuyerMaker'    => $isBuyerMaker,  // raw Binance key alias for blade templates
        ];
    }

    // ══════════════════════════════════════════════════════════════════════
    //  STATIC DATA
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Curated list of US Treasury bonds.
     * Fields required by BondsController:
     *   cusip, name, coupon (% p.a.), maturity (Unix ts), issue (Unix ts)
     */
    private function staticBonds(): array
    {
        return [
            [
                'cusip'         => '912810TM0',
                'name'          => 'US Treasury Bond 4.375% 2043',
                'coupon'        => 4.375,
                'maturity'      => mktime(0, 0, 0, 8, 15, 2043),
                'issue'         => mktime(0, 0, 0, 8, 15, 2023),
                'price'         => 95.50,
                'yield'         => 4.72,
                'type'          => 'Treasury Bond',
                'issuer'        => 'US Department of the Treasury',
                'flag'          => 'us',
                'county'        => 'United States',
                'rating'        => 'AAA',
            ],
            [
                'cusip'         => '912810TL2',
                'name'          => 'US Treasury Bond 3.625% 2053',
                'coupon'        => 3.625,
                'maturity'      => mktime(0, 0, 0, 8, 15, 2053),
                'issue'         => mktime(0, 0, 0, 2, 15, 2023),
                'price'         => 82.25,
                'yield'         => 4.89,
                'type'          => 'Treasury Bond',
                'issuer'        => 'US Department of the Treasury',
                'flag'          => 'us',
                'county'        => 'United States',
                'rating'        => 'AAA',
            ],
            [
                'cusip'         => '912810TK4',
                'name'          => 'US Treasury Bond 2.875% 2052',
                'coupon'        => 2.875,
                'maturity'      => mktime(0, 0, 0, 5, 15, 2052),
                'issue'         => mktime(0, 0, 0, 5, 15, 2022),
                'price'         => 68.75,
                'yield'         => 4.78,
                'type'          => 'Treasury Bond',
                'issuer'        => 'US Department of the Treasury',
                'flag'          => 'us',
                'county'        => 'United States',
                'rating'        => 'AAA',
            ],
            [
                'cusip'         => '912810SN7',
                'name'          => 'US Treasury Bond 2.25% 2050',
                'coupon'        => 2.25,
                'maturity'      => mktime(0, 0, 0, 8, 15, 2050),
                'issue'         => mktime(0, 0, 0, 8, 15, 2020),
                'price'         => 61.50,
                'yield'         => 4.81,
                'type'          => 'Treasury Bond',
                'issuer'        => 'US Department of the Treasury',
                'flag'          => 'us',
                'county'        => 'United States',
                'rating'        => 'AAA',
            ],
            [
                'cusip'         => '912828YV6',
                'name'          => 'US Treasury Note 1.5% 2030',
                'coupon'        => 1.5,
                'maturity'      => mktime(0, 0, 0, 11, 30, 2030),
                'issue'         => mktime(0, 0, 0, 11, 30, 2020),
                'price'         => 88.25,
                'yield'         => 4.62,
                'type'          => 'Treasury Note',
                'issuer'        => 'US Department of the Treasury',
                'flag'          => 'us',
                'county'        => 'United States',
                'rating'        => 'AAA',
            ],
            [
                'cusip'         => '91282CAF2',
                'name'          => 'US Treasury Note 0.625% 2027',
                'coupon'        => 0.625,
                'maturity'      => mktime(0, 0, 0, 8, 15, 2027),
                'issue'         => mktime(0, 0, 0, 8, 15, 2021),
                'price'         => 92.10,
                'yield'         => 4.48,
                'type'          => 'Treasury Note',
                'issuer'        => 'US Department of the Treasury',
                'flag'          => 'us',
                'county'        => 'United States',
                'rating'        => 'AAA',
            ],
            [
                'cusip'         => '912828ZT0',
                'name'          => 'US Treasury Note 2.375% 2029',
                'coupon'        => 2.375,
                'maturity'      => mktime(0, 0, 0, 3, 31, 2029),
                'issue'         => mktime(0, 0, 0, 3, 31, 2021),
                'price'         => 90.75,
                'yield'         => 4.55,
                'type'          => 'Treasury Note',
                'issuer'        => 'US Department of the Treasury',
                'flag'          => 'us',
                'county'        => 'United States',
                'rating'        => 'AAA',
            ],
            [
                'cusip'         => '912828YK0',
                'name'          => 'US Treasury Note 1.875% 2031',
                'coupon'        => 1.875,
                'maturity'      => mktime(0, 0, 0, 2, 28, 2031),
                'issue'         => mktime(0, 0, 0, 2, 28, 2021),
                'price'         => 86.50,
                'yield'         => 4.65,
                'type'          => 'Treasury Note',
                'issuer'        => 'US Department of the Treasury',
                'flag'          => 'us',
                'county'        => 'United States',
                'rating'        => 'AAA',
            ],
            [
                'cusip'         => '912796ZR9',
                'name'          => 'US Treasury Bill 5.35% 2025',
                'coupon'        => 5.35,
                'maturity'      => mktime(0, 0, 0, 12, 31, 2025),
                'issue'         => mktime(0, 0, 0, 1, 1, 2025),
                'price'         => 99.40,
                'yield'         => 5.35,
                'type'          => 'Treasury Bill',
                'issuer'        => 'US Department of the Treasury',
                'flag'          => 'us',
                'county'        => 'United States',
                'rating'        => 'AAA',
            ],
            [
                'cusip'         => '912796YV1',
                'name'          => 'US Treasury Bill 5.25% 2026',
                'coupon'        => 5.25,
                'maturity'      => mktime(0, 0, 0, 6, 30, 2026),
                'issue'         => mktime(0, 0, 0, 7, 1, 2024),
                'price'         => 98.50,
                'yield'         => 5.25,
                'type'          => 'Treasury Bill',
                'issuer'        => 'US Department of the Treasury',
                'flag'          => 'us',
                'county'        => 'United States',
                'rating'        => 'AAA',
            ],
        ];
    }

    /**
     * Curated list of mutual funds (static data).
     */
    private function staticMutualFunds(): array
    {
        return [
            [
                'ticker'                 => 'VFIAX',
                'name'                   => 'Vanguard 500 Index Fund Admiral Shares',
                'category'               => 'Large Blend',
                'nav'                    => 489.25,
                'current_nav'            => 489.25,
                'ytd_return'             => 12.45,
                'expense_ratio'          => 0.04,
                'aum_billions'           => 890.5,
                'assets_under_management' => 890.5,
            ],
            [
                'ticker'                 => 'FXAIX',
                'name'                   => 'Fidelity 500 Index Fund',
                'category'               => 'Large Blend',
                'nav'                    => 196.80,
                'current_nav'            => 196.80,
                'ytd_return'             => 12.48,
                'expense_ratio'          => 0.015,
                'aum_billions'           => 550.2,
                'assets_under_management' => 550.2,
            ],
            [
                'ticker'                 => 'SWPPX',
                'name'                   => 'Schwab S&P 500 Index Fund',
                'category'               => 'Large Blend',
                'nav'                    => 78.50,
                'current_nav'            => 78.50,
                'ytd_return'             => 12.41,
                'expense_ratio'          => 0.02,
                'aum_billions'           => 90.3,
                'assets_under_management' => 90.3,
            ],
            [
                'ticker'                 => 'VTSAX',
                'name'                   => 'Vanguard Total Stock Market Index',
                'category'               => 'Large Blend',
                'nav'                    => 128.75,
                'current_nav'            => 128.75,
                'ytd_return'             => 11.98,
                'expense_ratio'          => 0.04,
                'aum_billions'           => 1320.8,
                'assets_under_management' => 1320.8,
            ],
            [
                'ticker'                 => 'AGTHX',
                'name'                   => 'American Funds Growth Fund of America',
                'category'               => 'Large Growth',
                'nav'                    => 67.30,
                'current_nav'            => 67.30,
                'ytd_return'             => 15.72,
                'expense_ratio'          => 0.64,
                'aum_billions'           => 238.4,
                'assets_under_management' => 238.4,
            ],
            [
                'ticker'                 => 'PIMCO',
                'name'                   => 'PIMCO Total Return Fund',
                'category'               => 'Intermediate Core Bond',
                'nav'                    => 9.45,
                'current_nav'            => 9.45,
                'ytd_return'             => 3.82,
                'expense_ratio'          => 0.82,
                'aum_billions'           => 64.1,
                'assets_under_management' => 64.1,
            ],
        ];
    }
}
