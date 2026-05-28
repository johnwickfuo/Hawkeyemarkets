<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SiteMapGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:site-map-generator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $urls = [];

        foreach ($routes as $route) {
            // Only GET routes
            if (!in_array('GET', $route->methods())) {
                continue;
            }

            $uri = $route->uri();
            $name = $route->getName();

            // Exclude common internal/development routes
            if (
                str_starts_with($uri, '_') ||
                str_starts_with($uri, 'sanctum') ||
                str_starts_with($uri, 'api') ||
                str_starts_with($uri, 'telescope') ||
                str_starts_with($uri, 'horizon') ||
                str_starts_with($uri, 'up')
            ) {
                continue;
            }

            // Exclude routes that clearly belong to other route files (admin, user, utils)
            if (
                str_starts_with($uri, 'admin') ||
                str_starts_with($uri, 'user') ||
                str_starts_with($uri, 'utils') ||
                str_starts_with($uri, 'storage') ||
                ($name && (
                    str_starts_with($name, 'admin.') ||
                    str_starts_with($name, 'user.') ||
                    str_starts_with($name, 'utils.') ||
                    str_starts_with($name, 'api.')
                ))
            ) {
                continue;
            }

            // Exclude specific testing/internal routes mentioned in web.php
            if ($uri === 'effects-preview' || $uri === 'lang/{locale}') {
                continue;
            }

            // Remove optional parameters and trailing slashes for the sitemap
            $cleanUri = preg_replace('/\{[^\}]+\?\}/', '', $uri);
            $cleanUri = rtrim($cleanUri, '/');

            // Construct full URL
            $url = url($cleanUri);

            // Avoid duplicates
            if (!in_array($url, $urls)) {
                $urls[] = $url;
            }
        }

        if (empty($urls)) {
            $this->warn('No suitable routes found for sitemap.');
            return;
        }

        $xml = $this->buildXml($urls);
        $path = public_path('sitemap.xml');

        if (file_put_contents($path, $xml)) {
            $this->info("Sitemap generated successfully at: {$path}");
            $this->info("Total URLs: " . count($urls));

            $this->updateRobotsTxt();

            updateLastCronJob($this->signature);
        } else {
            $this->error("Failed to write sitemap to: {$path}");
        }
    }

    /**
     * Update robots.txt with the sitemap URL.
     */
    protected function updateRobotsTxt()
    {
        $path = public_path('robots.txt');
        $sitemapUrl = url('sitemap.xml');

        $content = "User-agent: *" . PHP_EOL;
        $content .= "Disallow: /admin" . PHP_EOL;
        $content .= "Disallow: /user" . PHP_EOL;
        $content .= "Disallow: /api" . PHP_EOL;
        $content .= "Disallow: /utils" . PHP_EOL;
        $content .= "Disallow: /storage" . PHP_EOL . PHP_EOL;
        $content .= "Sitemap: {$sitemapUrl}" . PHP_EOL;

        if (file_put_contents($path, $content)) {
            $this->info("Robots.txt updated successfully at: {$path}");
        } else {
            $this->error("Failed to update robots.txt at: {$path}");
        }
    }

    /**
     * Build the sitemap XML structure.
     */
    protected function buildXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . now()->toAtomString() . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>weekly</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . ($url === url('/') ? '1.0' : '0.8') . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
