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

    // ── Twelve Data rate-limit and cache tuning ────────────────────────────
    // Defaults assume the free "Basic 8" plan (8 credits/min, 800/day) with
    // a safety margin. Override via env to match a paid plan.
    protected int $tdPerMinuteLimit;   // max API credits per minute
    protected int $tdPerDayLimit;      // max API credits per UTC day
    protected int $tdChunkSize;        // max symbols per single /quote call
    protected int $tdBatchTtl;         // seconds — market_stocks / market_etfs
    protected int $tdSingleTtl;        // seconds — stock_ticker_X, etf_ticker_X
    protected int $tdForexTtl;         // seconds — forex_tickers / forex_ticker_X
    protected int $tdStaleTtl;         // seconds — persistent per-symbol fallback

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

    // Ticker → corporate / issuer domain. Used to build Clearbit logo URLs,
    // which need the actual domain (e.g. apple.com), not the ticker. Anything
    // not in this map falls back to a generic letter-avatar logo so the UI
    // never shows a broken-image placeholder.
    protected array $tickerDomainMap = [
        // Stocks
        'AAPL' => 'apple.com',
        'MSFT' => 'microsoft.com',
        'GOOGL' => 'abc.xyz',
        'GOOG' => 'abc.xyz',
        'AMZN' => 'amazon.com',
        'NVDA' => 'nvidia.com',
        'META' => 'meta.com',
        'TSLA' => 'tesla.com',
        'UNH' => 'unitedhealthgroup.com',
        'JPM' => 'jpmorganchase.com',
        'JNJ' => 'jnj.com',
        'V' => 'visa.com',
        'PG' => 'pg.com',
        'MA' => 'mastercard.com',
        'HD' => 'homedepot.com',
        'BAC' => 'bankofamerica.com',
        'XOM' => 'exxonmobil.com',
        'ABBV' => 'abbvie.com',
        'MRK' => 'merck.com',
        'CVX' => 'chevron.com',
        'PFE' => 'pfizer.com',
        'WMT' => 'walmart.com',
        'KO' => 'coca-colacompany.com',
        'NFLX' => 'netflix.com',
        'DIS' => 'thewaltdisneycompany.com',
        'INTC' => 'intel.com',
        'AMD' => 'amd.com',
        'CSCO' => 'cisco.com',
        'ORCL' => 'oracle.com',
        // ETFs — issuer domain
        'SPY' => 'ssga.com',
        'QQQ' => 'invesco.com',
        'IWM' => 'ishares.com',
        'EFA' => 'ishares.com',
        'AGG' => 'ishares.com',
        'VTI' => 'vanguard.com',
        'BND' => 'vanguard.com',
        'GLD' => 'spdrgoldshares.com',
        'SLV' => 'ishares.com',
        'TLT' => 'ishares.com',
        'HYG' => 'ishares.com',
        'LQD' => 'ishares.com',
        'VNQ' => 'vanguard.com',
        'XLE' => 'sectorspdrs.com',
        'XLF' => 'sectorspdrs.com',
        'XLV' => 'sectorspdrs.com',
        'XLK' => 'sectorspdrs.com',
        'XLI' => 'sectorspdrs.com',
        'XLU' => 'sectorspdrs.com',
        'XLC' => 'sectorspdrs.com',
    ];

    public function __construct()
    {
        $this->twelveDataUrl = config('services.twelvedata.base_url', 'https://api.twelvedata.com');
        $this->twelveDataKey = config('services.twelvedata.api_key', '');

        // Leave one credit of headroom under the per-minute hard cap so concurrent
        // requests can't push us over.
        $this->tdPerMinuteLimit = max(1, (int) config('services.twelvedata.per_minute_limit', 7));
        $this->tdPerDayLimit    = max(1, (int) config('services.twelvedata.per_day_limit', 750));
        $this->tdChunkSize      = max(1, min(
            (int) config('services.twelvedata.chunk_size', 7),
            $this->tdPerMinuteLimit
        ));
        $this->tdBatchTtl  = (int) config('services.twelvedata.batch_ttl', 21600);   // 6h
        $this->tdSingleTtl = (int) config('services.twelvedata.single_ttl', 3600);   //  1h
        $this->tdForexTtl  = (int) config('services.twelvedata.forex_ttl', 900);     // 15m
        $this->tdStaleTtl  = (int) config('services.twelvedata.stale_ttl', 604800);  //  7d
    }

    // ══════════════════════════════════════════════════════════════════════
    //  STOCKS  (Twelve Data)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Get market stocks data.
     */
    public function marketStocks(): array
    {
        return $this->cachedBatchQuote('market_stocks', $this->stockSymbols);
    }

    /**
     * Get ticker information for a single stock.
     */
    public function ticker(string $ticker): array
    {
        return $this->cachedSingleQuote(strtoupper($ticker), 'stock_ticker_', 'market_stocks');
    }

    /**
     * Preload (warm) a set of stock quotes via a single chunked batch call.
     * Useful from cron jobs that iterate user holdings to avoid one API call
     * per ticker — each batch chunk costs N credits but only counts as one
     * HTTP request in the per-minute budget window.
     */
    public function preloadStockQuotes(array $tickers): void
    {
        $this->warmSingleQuotes($tickers, 'stock_ticker_');
    }

    // ══════════════════════════════════════════════════════════════════════
    //  ETFs  (Twelve Data)
    // ══════════════════════════════════════════════════════════════════════

    /**
     * Get market ETFs data.
     */
    public function marketEtfs(): array
    {
        return $this->cachedBatchQuote('market_etfs', $this->etfSymbols);
    }

    /**
     * Get ETF ticker information.
     */
    public function etfTicker(string $ticker): array
    {
        return $this->cachedSingleQuote(strtoupper($ticker), 'etf_ticker_', 'market_etfs');
    }

    /**
     * Preload (warm) a set of ETF quotes via a single chunked batch call.
     */
    public function preloadEtfQuotes(array $tickers): void
    {
        $this->warmSingleQuotes($tickers, 'etf_ticker_');
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
            return $this->assembleForexFromFallback($this->forexPairs)
                ?? $this->friendlyMarketError();
        }

        $lock = Cache::lock('lock_' . $cacheKey, 60);
        if (!$lock->get()) {
            // Another worker is already fetching. Wait briefly for it to populate
            // the cache; if not, fall back to stale per-symbol data.
            usleep(500_000);
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }
            return $this->assembleForexFromFallback($this->forexPairs)
                ?? $this->friendlyMarketError();
        }

        try {
            // Re-check inside the lock — another worker may have just populated it.
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $fetched = $this->quoteTwelveDataForex($this->forexPairs);

            // Merge fresh quotes with any per-symbol stale fallback to fill gaps.
            $data    = [];
            $partial = false;
            foreach ($this->forexPairs as $pair) {
                $upper = strtoupper($pair);
                if (isset($fetched[$upper])) {
                    $data[] = $fetched[$upper];
                    continue;
                }
                $fb = Cache::get('td_forex_fallback_' . str_replace('/', '_', $upper));
                if ($fb) {
                    $data[] = $fb;
                    $partial = true;
                }
            }

            if (empty($data)) {
                return $this->friendlyMarketError();
            }

            $result = ['status' => 'success', 'data' => $data, 'code' => 200];
            // Cache for a shorter time if we served partial data so we retry sooner.
            $ttl = $partial ? min(120, $this->tdForexTtl) : $this->tdForexTtl;
            Cache::put($cacheKey, $result, now()->addSeconds($ttl));
            return $result;

        } finally {
            optional($lock)->release();
        }
    }

    /**
     * Get a single forex ticker.
     */
    public function forexTicker(string $ticker): array
    {
        // Accept both EUR_USD and EUR/USD formats
        $ticker   = str_replace('_', '/', strtoupper($ticker));
        $cacheKey = 'forex_ticker_' . str_replace('/', '_', $ticker);
        $fbKey    = 'td_forex_fallback_' . str_replace('/', '_', $ticker);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Reuse the batch cache when it's already warm — costs zero API credits.
        if (Cache::has('forex_tickers')) {
            $batch = Cache::get('forex_tickers');
            if (($batch['status'] ?? null) === 'success') {
                foreach (($batch['data'] ?? []) as $row) {
                    if (strtoupper($row['s'] ?? '') === $ticker) {
                        $result = ['status' => 'success', 'data' => $row, 'code' => 200];
                        Cache::put($cacheKey, $result, now()->addSeconds($this->tdForexTtl));
                        return $result;
                    }
                }
            }
        }

        if (!empty($this->twelveDataKey)) {
            $lock = Cache::lock('lock_' . $cacheKey, 30);
            if ($lock->get()) {
                try {
                    if (Cache::has($cacheKey)) {
                        return Cache::get($cacheKey);
                    }
                    $fetched = $this->quoteTwelveDataForex([$ticker]);
                    if (isset($fetched[$ticker])) {
                        $result = ['status' => 'success', 'data' => $fetched[$ticker], 'code' => 200];
                        Cache::put($cacheKey, $result, now()->addSeconds($this->tdForexTtl));
                        return $result;
                    }
                } finally {
                    optional($lock)->release();
                }
            }
        }

        $fb = Cache::get($fbKey);
        if ($fb) {
            return ['status' => 'success', 'data' => $fb, 'code' => 200, 'stale' => true];
        }

        return $this->friendlyMarketError();
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
     * Quote a list of stock/ETF symbols from Twelve Data, chunked to respect
     * the per-minute credit budget. Returns a map of upper-case ticker => quote
     * for symbols that were successfully fetched. Each successful symbol is
     * also written to a long-lived per-symbol fallback cache so subsequent
     * requests can serve stale data when the budget is exhausted.
     */
    private function quoteTwelveData(array $symbols): array
    {
        if (empty($this->twelveDataKey) || empty($symbols)) {
            return [];
        }

        $symbols = array_values(array_unique(array_map('strtoupper', $symbols)));
        $results = [];

        foreach (array_chunk($symbols, $this->tdChunkSize) as $chunk) {
            $cost = count($chunk);

            if (!$this->reserveTwelveDataCredits($cost)) {
                Log::warning('[LozandServices] Twelve Data budget exhausted (need ' . $cost . ' credits); serving stale data for remaining symbols');
                break;
            }

            try {
                $response = Http::timeout(30)->get($this->twelveDataUrl . '/quote', [
                    'symbol' => implode(',', $chunk),
                    'apikey' => $this->twelveDataKey,
                ]);
            } catch (\Exception $e) {
                Log::error('[LozandServices] quoteTwelveData: ' . $e->getMessage());
                continue;
            }

            if (!$response->successful()) {
                // 429 = rate-limited despite our local budget — refund the
                // reservation by NOT recording the credits we just spent.
                // We can't actually un-spend on the upstream side, but we can
                // log so operators see the divergence.
                Log::error('[LozandServices] quoteTwelveData (' . implode(',', $chunk) . '): ' . $response->body());
                continue;
            }

            $json = $response->json();
            // Single-symbol responses are returned as a plain object; multi-symbol
            // responses are keyed by symbol.
            if (isset($json['symbol'])) {
                $json = [$json['symbol'] => $json];
            }

            foreach ($json as $key => $quote) {
                if (!is_array($quote) || !isset($quote['close'])) {
                    continue; // skip error entries / per-symbol failures
                }
                $normalized = $this->normalizeTwelveDataQuote($quote);
                $sym        = strtoupper($normalized['ticker'] ?? $key);
                $results[$sym] = $normalized;
                Cache::put('td_fallback_' . $sym, $normalized, $this->tdStaleTtl);
            }
        }

        return $results;
    }

    /**
     * Quote a list of forex pairs from Twelve Data, chunked to respect the
     * per-minute credit budget. Returns a map of UPPER pair (e.g. "EUR/USD") =>
     * normalized forex quote. Successful pairs are also stored in a long-lived
     * per-symbol fallback cache (keyed with underscores, e.g. EUR_USD).
     */
    private function quoteTwelveDataForex(array $pairs): array
    {
        if (empty($this->twelveDataKey) || empty($pairs)) {
            return [];
        }

        $pairs   = array_values(array_unique(array_map(fn($p) => strtoupper(str_replace('_', '/', $p)), $pairs)));
        $results = [];

        foreach (array_chunk($pairs, $this->tdChunkSize) as $chunk) {
            $cost = count($chunk);

            if (!$this->reserveTwelveDataCredits($cost)) {
                Log::warning('[LozandServices] Twelve Data budget exhausted (forex, need ' . $cost . ')');
                break;
            }

            try {
                $response = Http::timeout(30)->get($this->twelveDataUrl . '/quote', [
                    'symbol' => implode(',', $chunk),
                    'apikey' => $this->twelveDataKey,
                ]);
            } catch (\Exception $e) {
                Log::error('[LozandServices] quoteTwelveDataForex: ' . $e->getMessage());
                continue;
            }

            if (!$response->successful()) {
                Log::error('[LozandServices] quoteTwelveDataForex (' . implode(',', $chunk) . '): ' . $response->body());
                continue;
            }

            $json = $response->json();
            if (isset($json['symbol'])) {
                $json = [$json['symbol'] => $json];
            }

            foreach ($json as $key => $quote) {
                if (!is_array($quote) || !isset($quote['close'])) {
                    continue;
                }
                $normalized = $this->normalizeForexQuote($quote);
                $sym        = strtoupper($normalized['s'] ?? $key);
                $results[$sym] = $normalized;
                Cache::put('td_forex_fallback_' . str_replace('/', '_', $sym), $normalized, $this->tdStaleTtl);
            }
        }

        return $results;
    }

    /**
     * Reserve credits against the per-minute and per-day budgets. Returns true
     * if the reservation succeeded; false if either budget would be exceeded.
     *
     * Best-effort under concurrency: there is a small race between read and
     * increment, but the safety margin baked into the limits absorbs it.
     */
    private function reserveTwelveDataCredits(int $needed): bool
    {
        if ($needed <= 0) {
            return true;
        }

        $minuteKey = 'twelvedata_credits_minute_' . floor(time() / 60);
        $dayKey    = 'twelvedata_credits_day_' . gmdate('Ymd');

        $minuteUsed = (int) Cache::get($minuteKey, 0);
        if ($minuteUsed + $needed > $this->tdPerMinuteLimit) {
            return false;
        }

        $dayUsed = (int) Cache::get($dayKey, 0);
        if ($dayUsed + $needed > $this->tdPerDayLimit) {
            return false;
        }

        // Ensure both counters exist with a TTL before incrementing — Laravel's
        // Cache::increment requires a pre-existing key on some drivers.
        Cache::add($minuteKey, 0, 90);     //  90s covers the full minute window
        Cache::add($dayKey, 0, 90000);     //  ~25h covers the UTC day window
        Cache::increment($minuteKey, $needed);
        Cache::increment($dayKey, $needed);
        return true;
    }

    /**
     * Fetch a batch of symbols with cache, stampede lock, stale fallback and
     * partial-result handling. Used by marketStocks() and marketEtfs().
     */
    private function cachedBatchQuote(string $cacheKey, array $symbols): array
    {
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        if (empty($this->twelveDataKey)) {
            return $this->assembleStaleBatch($symbols) ?? $this->friendlyMarketError();
        }

        $lock = Cache::lock('lock_' . $cacheKey, 60);
        if (!$lock->get()) {
            usleep(500_000);
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }
            return $this->assembleStaleBatch($symbols) ?? $this->friendlyMarketError();
        }

        try {
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $fetched = $this->quoteTwelveData($symbols);

            // Merge fresh + per-symbol fallback to fill any gaps.
            $data    = [];
            $partial = false;
            foreach ($symbols as $sym) {
                $upper = strtoupper($sym);
                if (isset($fetched[$upper])) {
                    $data[] = $fetched[$upper];
                    continue;
                }
                $fb = Cache::get('td_fallback_' . $upper);
                if ($fb) {
                    $data[] = $fb;
                    $partial = true;
                }
            }

            if (empty($data)) {
                return $this->friendlyMarketError();
            }

            $result = ['status' => 'success', 'data' => $data, 'code' => 200];
            $ttl    = $partial ? min(300, $this->tdBatchTtl) : $this->tdBatchTtl;
            Cache::put($cacheKey, $result, now()->addSeconds($ttl));
            return $result;
        } finally {
            optional($lock)->release();
        }
    }

    /**
     * Fetch a single stock/ETF quote, preferring the batch cache to save a
     * credit, and falling back to long-lived stale data on API failure.
     */
    private function cachedSingleQuote(string $ticker, string $cachePrefix, ?string $batchCacheKey): array
    {
        $cacheKey = $cachePrefix . $ticker;

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Reuse the batch cache when warm — zero API cost.
        if ($batchCacheKey && Cache::has($batchCacheKey)) {
            $batch = Cache::get($batchCacheKey);
            if (($batch['status'] ?? null) === 'success') {
                foreach (($batch['data'] ?? []) as $row) {
                    if (strtoupper($row['ticker'] ?? '') === $ticker) {
                        $result = ['status' => 'success', 'data' => $row, 'code' => 200];
                        Cache::put($cacheKey, $result, now()->addSeconds($this->tdSingleTtl));
                        return $result;
                    }
                }
            }
        }

        if (!empty($this->twelveDataKey)) {
            $lock = Cache::lock('lock_' . $cacheKey, 30);
            if ($lock->get()) {
                try {
                    if (Cache::has($cacheKey)) {
                        return Cache::get($cacheKey);
                    }
                    $fetched = $this->quoteTwelveData([$ticker]);
                    if (isset($fetched[$ticker])) {
                        $result = ['status' => 'success', 'data' => $fetched[$ticker], 'code' => 200];
                        Cache::put($cacheKey, $result, now()->addSeconds($this->tdSingleTtl));
                        return $result;
                    }
                } finally {
                    optional($lock)->release();
                }
            }
        }

        // Last resort: persistent per-symbol stale fallback.
        $fb = Cache::get('td_fallback_' . $ticker);
        if ($fb) {
            return ['status' => 'success', 'data' => $fb, 'code' => 200, 'stale' => true];
        }

        return $this->friendlyMarketError();
    }

    /**
     * Warm individual stock/ETF caches by batch-fetching a list of tickers in a
     * single chunked call. Used by cron jobs (e.g. UpdateStockPnl) that would
     * otherwise issue one single-symbol request per holding.
     */
    private function warmSingleQuotes(array $tickers, string $cachePrefix): void
    {
        $tickers = array_values(array_unique(array_map('strtoupper', $tickers)));
        // Skip any ticker whose fresh cache is already populated.
        $cold = array_filter($tickers, fn($t) => !Cache::has($cachePrefix . $t));
        if (empty($cold)) {
            return;
        }

        $fetched = $this->quoteTwelveData($cold);
        foreach ($fetched as $ticker => $quote) {
            Cache::put($cachePrefix . $ticker, ['status' => 'success', 'data' => $quote, 'code' => 200], now()->addSeconds($this->tdSingleTtl));
        }
    }

    /**
     * Try to reconstruct a batch response entirely from the persistent stale
     * fallback cache. Returns null if no symbols have any cached data.
     */
    private function assembleStaleBatch(array $symbols): ?array
    {
        $data = [];
        foreach ($symbols as $sym) {
            $fb = Cache::get('td_fallback_' . strtoupper($sym));
            if ($fb) {
                $data[] = $fb;
            }
        }
        if (empty($data)) {
            return null;
        }
        return ['status' => 'success', 'data' => $data, 'code' => 200, 'stale' => true];
    }

    /**
     * Same as assembleStaleBatch() but for forex pairs.
     */
    private function assembleForexFromFallback(array $pairs): ?array
    {
        $data = [];
        foreach ($pairs as $pair) {
            $key = 'td_forex_fallback_' . strtoupper(str_replace('/', '_', $pair));
            $fb  = Cache::get($key);
            if ($fb) {
                $data[] = $fb;
            }
        }
        if (empty($data)) {
            return null;
        }
        return ['status' => 'success', 'data' => $data, 'code' => 200, 'stale' => true];
    }

    /**
     * Build a logo URL for a stock or ETF ticker. Known tickers map to the
     * issuer's domain and resolve via Clearbit's free logo API; unknown
     * tickers fall back to a ui-avatars.com letter avatar so the templates
     * never render a broken-image icon.
     */
    private function logoForTicker(string $ticker): string
    {
        $ticker = strtoupper(trim($ticker));
        if ($ticker === '') {
            return '';
        }

        $domain = $this->tickerDomainMap[$ticker] ?? null;
        if ($domain) {
            return 'https://logo.clearbit.com/' . $domain;
        }

        // Deterministic letter avatar; PNG, no auth, always returns 200.
        return 'https://ui-avatars.com/api/?'
            . http_build_query([
                'name'       => $ticker,
                'background' => '1f2937',
                'color'      => 'ffffff',
                'bold'       => 'true',
                'format'     => 'png',
                'size'       => 128,
            ]);
    }

    /**
     * User-facing error response when no fresh or stale data can be returned.
     * Never surfaces the upstream "ran out of credits" wording to end users.
     */
    private function friendlyMarketError(): array
    {
        return [
            'status'  => 'error',
            'message' => __('Market data is temporarily unavailable. Please try again in a few minutes.'),
            'code'    => 503,
        ];
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
            // Stock / ETF logo — falls back to a deterministic letter avatar
            // when the ticker isn't in our domain map, so the UI never renders
            // a broken-image placeholder.
            'public_png_logo_url'       => $this->logoForTicker($symbol),
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
