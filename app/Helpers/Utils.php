<?php

use App\Models\CronJob;
use App\Models\NotificationMessage;
use App\Models\Setting;
use App\Models\Transaction;
use App\Services\GeoLocationService;
use App\Services\LozandServices;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Http;

// Update or store site settings
if (!function_exists('updateSetting')) {
    function updateSetting($key, $value)
    {
        // if its array, encode it first
        if (is_array($value)) {
            $value = json_encode($value);
        }
        $setting = Setting::where('key', $key)->first();
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            Setting::create([
                'key' => $key,
                'value' => $value,
            ]);
        }

        // forget core_site_settings from cache
        cache()->forget('core_site_settings');
        return true;
    }
}


// retrieve website settings
if (!function_exists('getSetting')) {
    function getSetting(string $key, $default = null)
    {
        // Retrieve settings from app container (singleton bound in AppServiceProvider)
        $settings = app()->make('website_settings');

        return $settings->$key ?? $default;
    }
}

// Global Password Validation Rule
if (!function_exists('validPassword')) {
    function validPassword($confirmed = true)
    {
        $rules = ['required', 'string'];

        if (getSetting('require_strong_password') === 'enabled') {
            $passRole = \Illuminate\Validation\Rules\Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised();
        } else {
            $passRole = \Illuminate\Validation\Rules\Password::min(5);
        }

        $rules[] = $passRole;

        if ($confirmed) {
            $rules[] = 'confirmed';
        }

        return $rules;
    }
}



//record transaction

if (!function_exists('recordTransaction')) {
    function recordTransaction($user, $amount, $currency, $converted_amount, $converted_currency, $rate, $type, $status, $reference, $description, $new_balance)
    {
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $amount;
        $transaction->currency = $currency;
        $transaction->converted_amount = $converted_amount;
        $transaction->converted_currency = $converted_currency;
        $transaction->rate = $rate;
        $transaction->type = $type;
        $transaction->status = $status;
        $transaction->reference = $reference;
        $transaction->description = $description;
        $transaction->new_balance = $new_balance;
        $transaction->save();

        // send traction email
        sendNewTransactionEmail($transaction);
    }
}


// record notification message
if (!function_exists('recordNotificationMessage')) {
    function recordNotificationMessage($user, $title, $body)
    {
        $notification = new NotificationMessage();
        $notification->user_id = $user->id;
        $notification->title = $title;
        $notification->body = $body;
        $notification->save();
    }
}


// convert 
if (!function_exists('rateConverter')) {
    function rateConverter($amount, $from_currency, $to_currency, $precision)
    {
        try {
            $url = "http://lozand.com/api/v1/convert";
            $params = [
                'from_currency' => $from_currency,
                'to_currency' => $to_currency,
                'amount' => $amount,
                'precision' => $precision
            ];

            $license_key = safeDecrypt(config('site.product_key'));

            $headers = [
                'x-license-key' => $license_key,
                'x-domain' => request()->getHost(),
            ];

            $response = Http::withHeaders($headers)->get($url, $params);

            if ($response->failed()) {
                return [];
            }

            $response_data = $response->json()['data'];

            $data = [
                'converted_amount' => $response_data['converted_amount'],
                'exchange_rate' => $response_data['exchange_rate'],
                'to_currency' => $to_currency,
                'from_currency' => $from_currency,
                'status' => 'success',
            ];

            return $data;
        } catch (\Exception $e) {
            return [];
        }



    }
}




// calculate next return time
if (!function_exists('getNextReturnTime')) {
    function getNextReturnTime($plan)
    {
        return match ($plan->return_interval) {
            'hourly' => now()->addHour(),
            'daily' => now()->addDay(),
            'weekly' => now()->addWeek(),
            'monthly' => now()->addMonth(),
            'yearly' => now()->addYear(),
        };
    }
}


// calculate total cycles
if (!function_exists('calculateTotalCycles')) {
    function calculateTotalCycles($plan): int
    {
        // Convert duration into minutes
        $durationInMinutes = match ($plan->duration_type) {
            'hours' => $plan->duration * 60,
            'days' => $plan->duration * 60 * 24,
            'weeks' => $plan->duration * 60 * 24 * 7,
            'months' => $plan->duration * 60 * 24 * 30,  // financial standard
            'years' => $plan->duration * 60 * 24 * 365, // financial standard
        };

        // Convert return interval into minutes
        $intervalInMinutes = match ($plan->return_interval) {
            'hourly' => 60,
            'daily' => 60 * 24,
            'weekly' => 60 * 24 * 7,
            'monthly' => 60 * 24 * 30,
            'yearly' => 60 * 24 * 365,
        };

        return (int) floor($durationInMinutes / $intervalInMinutes);
    }
}




// Format number with suffix (K, M, B, T)
if (!function_exists('formatNumberAbbreviated')) {
    function formatNumberAbbreviated($number, $precision = 2)
    {
        if ($number < 1000) {
            return number_format($number, $precision);
        }

        $suffixes = ['', 'K', 'M', 'B', 'T', 'Q'];
        $suffixIndex = 0;

        while ($number >= 1000 && $suffixIndex < count($suffixes) - 1) {
            $number /= 1000;
            $suffixIndex++;
        }

        return number_format($number, $precision) . $suffixes[$suffixIndex];
    }
}



/**
 * Update or add a key in the .env file.
 * - Updates existing key
 * - Adds key if missing (appends)
 * - Removes duplicate keys (keeps the last one)
 * - Preserves comments and blank lines as much as possible
 */
if (!function_exists('updateEnv')) {
    function updateEnv(string $key, $value, bool $encrypt = false): bool
    {
        $envPath = base_path('.env');
        $key = strtoupper($key);

        if (!file_exists($envPath) || !is_readable($envPath) || !is_writable($envPath)) {
            return false;
        }

        if ($encrypt) {
            // Encrypt to a string safe for env
            $value = encrypt($value);
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES); // keeps empty lines
        if ($lines === false) {
            return false;
        }

        $keyPattern = '/^\s*' . preg_quote($key, '/') . '\s*=/';

        // Collect indices of lines that define this key (ignore comments)
        $matchIndexes = [];
        foreach ($lines as $i => $line) {
            $trim = ltrim($line);

            // Preserve comments and blank lines
            if ($trim === '' || str_starts_with($trim, '#')) {
                continue;
            }

            // If someone used ";", treat it like comment too (common in env files)
            if (str_starts_with($trim, ';')) {
                continue;
            }

            if (preg_match($keyPattern, $line)) {
                $matchIndexes[] = $i;
            }
        }

        // Normalize value for .env
        $formattedValue = envFormatValue($value);
        $newLine = $key . '=' . $formattedValue;

        if (count($matchIndexes) === 0) {
            // Append at bottom, but keep a clean newline before adding if needed
            if (count($lines) > 0 && trim(end($lines)) !== '') {
                $lines[] = '';
            }
            $lines[] = $newLine;
        } else {
            // Keep the last occurrence, remove the rest (dedupe)
            $lastIndex = end($matchIndexes);

            // Update last occurrence
            $lines[$lastIndex] = $newLine;

            // Remove earlier duplicates (from bottom to top so indices don't shift)
            array_pop($matchIndexes); // remove last, we keep it
            rsort($matchIndexes);
            foreach ($matchIndexes as $idx) {
                unset($lines[$idx]);
            }

            // Reindex after unset
            $lines = array_values($lines);
        }

        $content = implode(PHP_EOL, $lines);

        // Ensure file ends with newline (common convention)
        if (!str_ends_with($content, PHP_EOL)) {
            $content .= PHP_EOL;
        }

        return file_put_contents($envPath, $content) !== false;
    }
}

/**
 * Format a value for .env:
 * - null => empty
 * - bool => true/false
 * - numbers => as-is
 * - strings => quoted if needed, with escaping
 */
if (!function_exists('envFormatValue')) {
    function envFormatValue($value): string
    {
        if (is_null($value)) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        $value = (string) $value;

        // Only quote when required
        if (preg_match('/\s|#|"|\'|=/', $value)) {
            $value = str_replace('"', '\"', $value);
            return '"' . $value . '"';
        }

        return $value;
    }


    // show amount with currency
    if (!function_exists('showAmount')) {
        function showAmount($amount, $decimal = null)
        {
            $decimal_places = $decimal ?? getSetting('decimal_places', 2);
            $currency = getSetting('currency_symbol', '$');
            return $currency . number_format($amount, $decimal_places);
        }
    }
}

if (!function_exists('showDateTime')) {
    function showDateTime($date, $format = 'Y-m-d h:i A')
    {
        $lang = session()->get('lang', 'en');
        \Carbon\Carbon::setLocale($lang);
        return \Carbon\Carbon::parse($date)->translatedFormat($format);
    }
}

if (!function_exists('diffForHumans')) {
    function diffForHumans($date)
    {
        $lang = session()->get('lang', 'en');
        \Carbon\Carbon::setLocale($lang);
        return \Carbon\Carbon::parse($date)->diffForHumans();
    }
}

if (!function_exists('getFilePath')) {
    function getFilePath($key)
    {
        $paths = [
            'verify' => 'assets/images/verify',
            'userProfile' => 'assets/images/user/profile',
        ];
        return $paths[$key] ?? $key;
    }
}

if (!function_exists('getImage')) {
    function getImage($path)
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        return asset($path);
    }
}


// update last cron job
if (!function_exists('updateLastCronJob')) {
    function updateLastCronJob($command)
    {
        $cron_job = CronJob::where('command', $command)->first();
        if ($cron_job) {
            $cron_job->last_run = now()->timestamp;
            $cron_job->save();
        }
    }
}


if (!function_exists('safeDecrypt')) {
    function safeDecrypt($value)
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
}

// if module is enabled
if (!function_exists('moduleEnabled')) {
    function moduleEnabled($module_key)
    {
        $modules = json_decode(getSetting('modules'), true) ?? [];
        $module = $modules[$module_key] ?? null;

        if (!$module) {
            return false;
        }

        return $module['status'] == 'enabled' ? true : false;
    }
}

// check if module exists
if (!function_exists('moduleExists')) {
    function moduleExists($module_key)
    {
        $modules = json_decode(getSetting('modules'), true) ?? [];
        return array_key_exists($module_key, $modules);
    }
}


// safely display sensitive credentials in sandbox mode
if (!function_exists('sandBoxCredentials')) {
    function sandBoxCredentials($value)
    {
        if (!$value) {
            return null;
        }
        if (app()->environment('sandbox')) {
            $length = strlen($value);
            if ($length <= 4) {
                return str_repeat('*', $length);
            }
            return substr($value, 0, 2) . str_repeat('*', $length - 4) . substr($value, -2);
        }
        return $value;
    }
}


//log sandbox users
if (!function_exists('logSandBoxUsers')) {
    function logSandBoxUsers($email, $name)
    {
        $path = storage_path('sandbox-users.json');
        $sandbox_users = [];
        if (file_exists($path)) {
            $sandbox_users = json_decode(file_get_contents($path), true) ?: [];
        }


        $ip_address = request()->ip();
        $getCountry = new GeoLocationService();
        $country = $getCountry->getCountry($ip_address);

        // slipt name into 2
        $name_parts = explode(' ', $name);
        $first_name = $name_parts[0] ?? null;
        $last_name = $name_parts[1] ?? null;

        $sandbox_user = [
            'email' => $email,
            'name' => $name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'ip_address' => $ip_address,
            'country' => $country,
            'created_at' => now()->timestamp,
        ];

        // before we continue, verify that the email has not been stored before
        if (collect($sandbox_users)->contains('email', $email)) {
            session()->put('sandbox_user', (object) $sandbox_user);
            return;
        }

        $sandbox_users[] = $sandbox_user;

        session()->put('sandbox_user', (object) $sandbox_user);

        file_put_contents($path, json_encode($sandbox_users, JSON_PRETTY_PRINT));

        return;
    }
}




