<?php

namespace App\Services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NowpaymentService
{
    /**
     * @var string
     */
    protected $api_key;

    /**
     * @var string
     */
    protected $secret_key;

    protected $email;

    protected $password;

    protected $google_auth;

    /**
     * NowpaymentService constructor.
     */
    public function __construct()
    {
        $this->api_key = $this->safeDecrypt(config('site.nowpayment_api_key'));
        $this->secret_key = $this->safeDecrypt(config('site.nowpayment_secret_key'));
        $this->email = $this->safeDecrypt(config('site.nowpayment_email'));
        $this->password = $this->safeDecrypt(config('site.nowpayment_password'));
        $this->google_auth = $this->safeDecrypt(config('site.nowpayment_2fa_secret'));
    }

    /**
     * Get all available currencies from NOWPayments.
     * 
     * @return array
     */
    public function getAvailableCurrencies()
    {
        try {
            $response = Http::timeout(120)->withHeaders([
                'x-api-key' => $this->api_key,
            ])->get('https://api.nowpayments.io/v1/full-currencies');

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json(),
                    'message' => 'Currencies Via NOWPayments API returned successfully',

                ];
            }

            return [
                'status' => false,
                'message' => 'Currencies Via NOWPayments API returned an error: ' . $response->status(),
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('NowpaymentService Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Currencies Via NOWPayments API An unexpected error occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get estimate for given amount from one currency to another.
     * 
     * @param float $amount
     * @param string $currency_from
     * @param string $currency_to
     * @return array
     */
    public function FiatToNowpayment($amount, $currency_from, $currency_to)
    {
        try {
            $response = Http::timeout(120)->withHeaders([
                'x-api-key' => $this->api_key,
            ])->get('https://api.nowpayments.io/v1/estimate', [
                        'amount' => $amount,
                        'currency_from' => $currency_from,
                        'currency_to' => $currency_to,
                    ]);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json(),
                    'message' => 'Estimate Via NOWPayments API returned successfully',
                ];
            }

            return [
                'status' => false,
                'message' => 'Estimate Via NOWPayments API returned an error: ' . $response->status(),
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('NowpaymentService FiatToNowpayment Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Estimate Via NOWPayments API An unexpected error occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Create a payment request.
     * 
     * @param float $price_amount
     * @param string $price_currency
     * @param float|null $pay_amount
     * @param string $pay_currency
     * @param string|null $ipn_callback_url
     * @param string|null $order_id
     * @return array
     */
    public function createPayment($price_amount, $price_currency, $pay_amount, $pay_currency, $ipn_callback_url, $order_id)
    {
        try {
            $data = [
                'price_amount' => $price_amount,
                'price_currency' => $price_currency,
                'pay_amount' => $pay_amount,
                'pay_currency' => $pay_currency,
                'ipn_callback_url' => $ipn_callback_url,
                'order_id' => $order_id,
            ];

            // Remove null values
            $data = array_filter($data, function ($value) {
                return !is_null($value);
            });

            $response = Http::timeout(120)->withHeaders([
                'x-api-key' => $this->api_key,
                'Content-Type' => 'application/json',
            ])->post('https://api.nowpayments.io/v1/payment', $data);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json(),
                    'message' => 'Payment created successfully via NOWPayments API',
                ];
            }

            // log error
            Log::error('NowpaymentService CreatePayment Error: ' . $response->body());
            $response_body = json_decode($response->body(), true);
            $error_messsage = $response_body['message'] ?? 'Payment creation via NOWPayments API returned an error: ' . $response->status();

            return [
                'status' => false,
                'message' => $error_messsage,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('NowpaymentService CreatePayment Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Payment creation Via NOWPayments API An unexpected error occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get payment status.
     * 
     * @param string $payment_id
     * @return array
     */
    public function getPaymentStatus($payment_id)
    {
        try {
            $response = Http::timeout(120)->withHeaders([
                'x-api-key' => $this->api_key,
            ])->get('https://api.nowpayments.io/v1/payment/' . $payment_id);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json(),
                    'message' => 'Payment status retrieved successfully',
                ];
            }

            return [
                'status' => false,
                'message' => 'Payment status retrieval returned an error: ' . $response->status(),
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('NowpaymentService GetStatus Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Payment status retrieval An unexpected error occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Sort array by keys recursively for IPN verification.
     *
     * @param array $array
     * @return void
     */
    public function tkSort(&$array)
    {
        ksort($array);
        foreach (array_keys($array) as $k) {
            if (gettype($array[$k]) == "array") {
                $this->tkSort($array[$k]);
            }
        }
    }

    /**
     * Verify NOWPayments IPN signature.
     *
     * @param string $json_data Raw JSON content from request
     * @param string $received_hmac HMAC signature from header
     * @return bool|string True on success, error message on failure
     */
    public function verifyIpnSignature($json_data, $received_hmac)
    {
        if (empty($received_hmac)) {
            return 'No HMAC signature sent.';
        }

        $request_data = json_decode($json_data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return 'Error decoding JSON data';
        }

        $this->tkSort($request_data);
        $sorted_request_json = json_encode($request_data, JSON_UNESCAPED_SLASHES);

        if ($json_data !== false && !empty($json_data)) {
            $hmac = hash_hmac("sha512", $sorted_request_json, trim($this->secret_key));
            if (hash_equals($hmac, $received_hmac)) {
                return true;
            } else {
                return 'HMAC signature does not match';
            }
        } else {
            return 'Error reading POST data';
        }
    }


    /**
     * Get Balance
     * @return array
     */

    public function getBalance()
    {
        try {
            $response = Http::timeout(120)->withHeaders([
                'x-api-key' => $this->api_key,
            ])->get('https://api.nowpayments.io/v1/balance');

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json(),
                    'message' => 'Balance retrieval returned successfully',
                ];
            }

            // log error
            Log::error('NowpaymentService GetBalance Error: ' . $response->body());
            $response_body = json_decode($response->body(), true);
            $error_messsage = $response_body['message'] ?? 'Balance retrieval via NOWPayments API returned an error: ' . $response->status();

            return [
                'status' => false,
                'message' => $error_messsage,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('NowpaymentService GetBalance Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Balance retrieval An unexpected error occurred: ' . $e->getMessage(),
            ];
        }
    }



    /**
     * Create payout on nowpayments
     * @param string $address
     * @param string $currency
     * @param float $amount
     * @param string $ref
     * @param string $ipn_callback_url
     * @return array
     */
    public function createPayout($address, $currency, $amount, $ref, $ipn_callback_url)
    {
        $data = [
            'address' => $address,
            'currency' => $currency,
            'amount' => $amount,
            'unique_external_id' => $ref,
            'ipn_callback_url' => $ipn_callback_url,
        ];

        $fields = [
            'ipn_callback_url' => $ipn_callback_url,
            'withdrawals' => [$data]
        ];
        $generate_bearer_token = $this->generatBearerToken();
        if (!$generate_bearer_token['status']) {
            return $generate_bearer_token;
        }


        $bearer_token = $generate_bearer_token['data']['token'];
        Log::error($bearer_token);
        try {
            $response = Http::timeout(120)->withHeaders([
                'x-api-key' => $this->api_key,
                'Authorization' => "Bearer " . $bearer_token,
                'Content-Type' => 'application/json',
            ])->post('https://api.nowpayments.io/v1/payout', $fields);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json(),
                    'message' => 'Payout created successfully via NOWPayments API',
                ];
            }

            // log error
            Log::error('NowpaymentService CreatePayout Error: ' . $response->body());
            $response_body = json_decode($response->body(), true);
            $error_messsage = $response_body['message'] ?? 'Payout creation via NOWPayments API returned an error: ' . $response->status();

            return [
                'status' => false,
                'message' => $error_messsage,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('NowpaymentService CreatePayout Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Payout creation Via NOWPayments API An unexpected error occurred: ' . $e->getMessage(),
            ];
        }
    }


    /**
     * Get payout status.
     * 
     * @param string $payment_id
     * @return array
     */
    public function getPayoutStatus($payment_id)
    {
        try {
            $response = Http::timeout(120)->withHeaders([
                'x-api-key' => $this->api_key,
            ])->get('https://api.nowpayments.io/v1/payout/' . $payment_id);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json(),
                    'message' => 'Payment status retrieved successfully',
                ];
            }

            Log::error('NowpaymentService GetStatus Error: ' . $response->body());
            $response_body = json_decode($response->body(), true);
            $error_messsage = $response_body['message'] ?? 'Payment status retrieval via NOWPayments API returned an error: ' . $response->status();

            return [
                'status' => false,
                'message' => $error_messsage,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('NowpaymentService GetStatus Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Payment status retrieval An unexpected error occurred: ' . $e->getMessage(),
            ];
        }
    }


    /**
     * Generate Bearer Token
     * @return array
     * 
     */
    public function generatBearerToken()
    {
        // check if token is in cache
        if (Cache::has('nowpayments_bearer_token')) {
            return [
                'status' => true,
                'data' => Cache::get('nowpayments_bearer_token'),
                'message' => 'Bearer token retrieved from cache',
            ];
        }
        try {
            $response = Http::timeout(120)->post('https://api.nowpayments.io/v1/auth', [
                'email' => $this->email,
                'password' => $this->password,
            ]);

            if ($response->successful()) {
                // store in cache for 2 minutes
                Cache::put('nowpayments_bearer_token', $response->json(), now()->addMinutes(2));
                return [
                    'status' => true,
                    'data' => $response->json(),
                    'message' => 'Bearer token generated successfully',
                ];
            }

            Log::error('NowpaymentService GenerateNpBearerToken Error: ' . $response->body());
            $response_body = json_decode($response->body(), true);
            $error_messsage = $response_body['message'] ?? 'Bearer token generation via NOWPayments API returned an error: ' . $response->status();

            return [
                'status' => false,
                'message' => $error_messsage,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('NowpaymentService GenerateNpBearerToken Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Bearer token generation An unexpected error occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payout on nowpayments
     * @param string|int $batch_id
     * @return array
     */
    public function verifyPayout($batch_id)
    {
        $verification_code = $this->getTOTPCode();
        if (empty($verification_code)) {
            return [
                'status' => false,
                'message' => 'Failed to generate verification code',
            ];
        }

        $generate_bearer_token = $this->generatBearerToken();
        if (!$generate_bearer_token['status']) {
            return $generate_bearer_token;
        }

        $bearer_token = $generate_bearer_token['data']['token'];

        try {
            $response = Http::timeout(120)->withHeaders([
                'x-api-key' => $this->api_key,
                'Authorization' => "Bearer " . $bearer_token,
                'Content-Type' => 'application/json',
            ])->post("https://api.nowpayments.io/v1/payout/{$batch_id}/verify", [
                        'verification_code' => $verification_code
                    ]);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json(),
                    'message' => 'Payout verified successfully via NOWPayments API',
                ];
            }

            // log error
            Log::error('NowpaymentService VerifyPayout Error: ' . $response->body());
            $response_body = json_decode($response->body(), true);
            $error_messsage = $response_body['message'] ?? 'Payout verification via NOWPayments API returned an error: ' . $response->status();

            return [
                'status' => false,
                'message' => $error_messsage,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('NowpaymentService VerifyPayout Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Payout verification Via NOWPayments API An unexpected error occurred: ' . $e->getMessage(),
            ];
        }
    }


    /**
     * Generate TOTP code from secret
     * @return string|null
     */
    public function getTOTPCode()
    {
        $secret = $this->google_auth;
        if (empty($secret)) {
            return null;
        }

        $time_step = 30;
        $digits = 6;
        $timestamp = floor(time() / $time_step);

        $binary_data = $this->base32Decode($secret);
        if (!$binary_data) {
            return null;
        }

        // Pack timestamp into 8-byte binary string
        $time_hex = str_pad(dechex($timestamp), 16, '0', STR_PAD_LEFT);
        $time_bin = pack('H*', $time_hex);

        // HMAC-SHA1
        $hash = hash_hmac('sha1', $time_bin, $binary_data, true);

        // Dynamic truncation
        $offset = ord($hash[19]) & 0xf;
        $otp = (
            (ord($hash[$offset]) & 0x7f) << 24 |
            (ord($hash[$offset + 1]) & 0xff) << 16 |
            (ord($hash[$offset + 2]) & 0xff) << 8 |
            (ord($hash[$offset + 3]) & 0xff)
        ) % pow(10, $digits);

        return str_pad($otp, $digits, '0', STR_PAD_LEFT);
    }

    /**
     * Decode Base32 string
     * @param string $base32
     * @return string|false
     */
    protected function base32Decode($base32)
    {
        $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $base32chars_array = array_flip(str_split($base32chars));

        $base32 = strtoupper($base32);
        $base32 = str_replace('=', '', $base32);
        $binary_string = '';

        foreach (str_split($base32) as $char) {
            if (!isset($base32chars_array[$char])) {
                return false;
            }
            $binary_string .= str_pad(decbin($base32chars_array[$char]), 5, '0', STR_PAD_LEFT);
        }

        $binary_data = '';
        foreach (str_split($binary_string, 8) as $byte) {
            if (strlen($byte) === 8) {
                $binary_data .= chr(bindec($byte));
            }
        }

        return $binary_data;
    }


    protected function safeDecrypt($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            return decrypt($value);
        } catch (DecryptException $e) {
            return null;
        }
    }


    // Get server ip from Nowpayment
    public function getServerIps()
    {
        $server_ips = [];
        try {
            $response = Http::timeout(120)->withHeaders([
                // 'x-api-key' => $this->api_key,
                'x-api-key' => '1T3TP59-XTG47KS-M12WJF8-MYF0MH6',
            ])->get('https://api.nowpayments.io/v1/balance');


            if (!$response->successful()) {

                $response_body = json_decode($response->body(), true);
                $error_messsage = $response_body['message'];
                //extract server ip from message
                preg_match('/Invalid IP - (.*)/', $error_messsage, $matches);
                if (isset($matches[1])) {
                    $server_ips[] = $matches[1];
                }



            }




        } catch (\Exception $e) {
            //do nothing
        }

        //lets get server ips
        $server_ips[] = $_SERVER['SERVER_ADDR'] ?? null;
        $server_ips[] = $_SERVER['REMOTE_ADDR'] ?? null;
        $host_name = gethostname();
        $local_ip = gethostbyname($host_name);
        $server_ips[] = $local_ip;

        // unique
        $server_ips = array_unique($server_ips);

        return $server_ips;
    }
}







