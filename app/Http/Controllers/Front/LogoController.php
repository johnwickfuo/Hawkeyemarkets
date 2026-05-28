<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\LozandServices;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Serves stock / ETF logos through a single in-app endpoint so the frontend
 * doesn't depend on any one upstream logo provider.
 *
 * Resolution order for a given ticker:
 *   1. Long-lived cache hit (bytes saved from a previous successful fetch)
 *   2. Clearbit, using the curated ticker -> issuer-domain map
 *   3. Financial Modeling Prep's public stock image CDN (ticker-keyed)
 *   4. DuckDuckGo icon service against the curated domain
 *   5. ui-avatars.com letter avatar (always succeeds)
 *
 * Any successful image is cached for a week so we don't keep hitting
 * upstream services. The HTTP response carries far-future cache headers so
 * browsers / CDNs hold onto it too.
 */
class LogoController extends Controller
{
    /**
     * GET /img/stock-logo/{ticker}.png
     */
    public function stock(string $ticker)
    {
        // Sanitize the path segment — only letters, digits and hyphens make
        // sense for an equity / ETF symbol. Prevents the controller being
        // abused as an arbitrary URL fetcher.
        $ticker = strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $ticker));
        if ($ticker === '') {
            return $this->avatarRedirect('NA');
        }

        $cacheKey = 'stock_logo_bin_' . $ticker;
        $cached   = Cache::get($cacheKey);

        if (is_array($cached) && isset($cached['body'], $cached['type'])) {
            return response($cached['body'], 200)
                ->header('Content-Type', $cached['type'])
                ->header('Cache-Control', 'public, max-age=604800, immutable');
        }

        $domain = (new LozandServices())->domainForTicker($ticker);

        $sources = array_values(array_filter([
            $domain ? 'https://logo.clearbit.com/' . $domain                    : null,
            'https://financialmodelingprep.com/image-stock/' . $ticker . '.png',
            $domain ? 'https://icons.duckduckgo.com/ip3/' . $domain . '.ico'    : null,
        ]));

        foreach ($sources as $url) {
            $image = $this->tryFetchImage($url);
            if ($image !== null) {
                Cache::put($cacheKey, $image, now()->addDays(7));
                return response($image['body'], 200)
                    ->header('Content-Type', $image['type'])
                    ->header('Cache-Control', 'public, max-age=604800, immutable');
            }
        }

        // Negative cache so we don't keep hammering upstreams for a missing
        // ticker — short TTL so a later add to the domain map fixes it
        // automatically.
        Cache::put('stock_logo_miss_' . $ticker, true, now()->addHours(6));
        return $this->avatarRedirect($ticker);
    }

    /**
     * Fetch a candidate URL and verify it actually returned image bytes.
     * Returns ['body' => string, 'type' => string] or null on any failure.
     */
    private function tryFetchImage(string $url): ?array
    {
        try {
            $response = Http::timeout(8)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; HawkeyeLogoFetcher/1.0)'])
                ->get($url);
        } catch (\Throwable $e) {
            Log::warning('[LogoController] fetch failed for ' . $url . ': ' . $e->getMessage());
            return null;
        }

        if (!$response->successful()) {
            return null;
        }

        $type = strtolower((string) $response->header('Content-Type'));
        $body = $response->body();

        // Some upstreams return a tiny 1x1 / transparent placeholder when they
        // have nothing — reject anything implausibly small.
        if (!str_starts_with($type, 'image/') || strlen($body) < 200) {
            return null;
        }

        return ['body' => $body, 'type' => $type];
    }

    /**
     * Redirect to a deterministic letter-avatar PNG. Always renders.
     */
    private function avatarRedirect(string $ticker)
    {
        $url = 'https://ui-avatars.com/api/?' . http_build_query([
            'name'       => $ticker,
            'background' => '1f2937',
            'color'      => 'ffffff',
            'bold'       => 'true',
            'format'     => 'png',
            'size'       => 128,
        ]);
        return redirect()->away($url, 302)
            ->header('Cache-Control', 'public, max-age=21600');
    }
}
