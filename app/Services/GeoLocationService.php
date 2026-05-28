<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GeoLocationService
{
    /**
     * Get human-readable location information from an IP address.
     *
     * @param string|null $ip
     * @return string
     */
    public function getLocation($ip): string
    {
        // Skip lookup for local/reserved addresses
        if (!$ip || $ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return 'Localhost';
        }

        $cacheKey = "ip_loc_{$ip}";
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        $services = [
            fn() => $this->lookupIpApi($ip),
            fn() => $this->lookupIpwhoIs($ip),
            fn() => $this->lookupIpapiCo($ip),
        ];

        foreach ($services as $service) {
            try {
                $result = $service();
                if ($result) {
                    Cache::forever($cacheKey, $result);
                    return $result;
                }
            } catch (\Throwable $e) {
                Log::debug('IP geolocation service failed: ' . $e->getMessage());
            }
        }

        return 'Unknown Location';
    }

    /**
     * Get only the country name from an IP address.
     *
     * @param string|null $ip
     * @return string|null
     */
    public function getCountry($ip): ?string
    {
        if (!$ip || $ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return null;
        }

        $cacheKey = "ip_country_{$ip}";
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        foreach (['lookupIpApi', 'lookupIpwhoIs', 'lookupIpapiCo'] as $method) {
            try {
                $response = $this->rawLookup($method, $ip);
                $country = $response['country'] ?? $response['country_name'] ?? null;
                if ($country) {
                    Cache::forever($cacheKey, $country);
                    return $country;
                }
            } catch (\Throwable $e) {
                Log::debug("IP country lookup failed for {$method}: " . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Perform raw lookup for a specific service.
     */
    private function rawLookup(string $method, string $ip): ?array
    {
        switch ($method) {
            case 'lookupIpApi':
                $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}", ['fields' => 'status,country']);
                return ($response->successful() && ($response->json()['status'] ?? '') === 'success') ? $response->json() : null;
            case 'lookupIpwhoIs':
                $response = Http::timeout(5)->get("https://ipwho.is/{$ip}");
                return ($response->successful() && ($response->json()['success'] ?? false)) ? $response->json() : null;
            case 'lookupIpapiCo':
                $response = Http::timeout(5)->get("https://ipapi.co/{$ip}/json/");
                return ($response->successful() && empty($response->json()['error'])) ? $response->json() : null;
        }
        return null;
    }

    /** Service 1: ip-api.com (HTTP, no key needed, 45 req/min free) */
    private function lookupIpApi(string $ip): ?string
    {
        $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}", [
            'fields' => 'status,city,regionName,country',
        ]);

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        if (($data['status'] ?? '') !== 'success') {
            return null;
        }

        return $this->formatLocation($data['city'] ?? null, $data['regionName'] ?? null, $data['country'] ?? null);
    }

    /** Service 2: ipwho.is (HTTPS, no key needed) */
    private function lookupIpwhoIs(string $ip): ?string
    {
        $response = Http::timeout(5)->get("https://ipwho.is/{$ip}");

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        if (!($data['success'] ?? false)) {
            return null;
        }

        return $this->formatLocation($data['city'] ?? null, $data['region'] ?? null, $data['country'] ?? null);
    }

    /** Service 3: ipapi.co (HTTPS, no key, 30k req/month free) */
    private function lookupIpapiCo(string $ip): ?string
    {
        $response = Http::timeout(5)->get("https://ipapi.co/{$ip}/json/");

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        // ipapi.co returns an "error" key on failure
        if (!empty($data['error'])) {
            return null;
        }

        return $this->formatLocation($data['city'] ?? null, $data['region'] ?? null, $data['country_name'] ?? null);
    }

    /** Format city/region/country into a readable string, omitting blank parts. */
    private function formatLocation(?string $city, ?string $region, ?string $country): ?string
    {
        $parts = array_filter([$city, $region, $country], fn($v) => !empty(trim((string) $v)));

        if (empty($parts)) {
            return null;
        }

        return implode(', ', array_unique($parts));
    }
}
