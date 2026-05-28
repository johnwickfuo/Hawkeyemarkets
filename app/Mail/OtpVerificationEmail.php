<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtpVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $name;
    public $otp_code;
    public $ip;
    public $user_agent;
    public $body_message;
    public $subject;

    /**
     * Create a new message instance.
     */
    public function __construct($email, $name, $otp_code, $ip, $user_agent, $message, $subject)
    {
        $this->email = $email;
        $this->name = $name;
        $this->otp_code = $otp_code;
        $this->ip = $ip;
        $this->user_agent = $user_agent;
        $this->body_message = $message;
        $this->subject = $subject;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->subject);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $template = config('site.template');
        $view = "templates.{$template}.mail.otp-verification-email";

        return new Content(
            view: $view,
            with: [
                'email' => $this->email,
                'name' => $this->name,
                'otp_code' => $this->otp_code,
                'ip' => $this->ip,
                'user_agent' => $this->user_agent,
                'location' => $this->getLocationInformation(),
                'body_message' => $this->body_message,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    // ─── Location Lookup ──────────────────────────────────────────────────────

    /**
     * Try 3 free IP geolocation services in order.
     * Returns a human-readable location string, or 'Unknown Location' if all fail.
     *
     * Services used (all free, no API key required):
     *   1. ip-api.com       — http, high rate-limit (45 req/min)
     *   2. ipwho.is         — https, generous free tier
     *   3. ipapi.co         — https, 30k req/month free
     */
    private function getLocationInformation(): string
    {
        $ip = $this->ip;

        // Skip lookup for local/reserved addresses
        if (!$ip || $ip === '127.0.0.1' || $ip === '::1') {
            return 'Localhost';
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
                    return $result;
                }
            } catch (\Throwable $e) {
                Log::debug('IP geolocation service failed: ' . $e->getMessage());
            }
        }

        return 'Unknown Location';
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
