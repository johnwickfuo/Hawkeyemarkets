<?php

namespace App\Listeners;

use App\Models\NotificationMessage;
use Illuminate\Support\Facades\File;

class LogMissingTranslation
{
    /**
     * Handle the missing translation.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @param  bool  $fallback
     * @return string|null
     */
    public function __invoke($key, $replace, $locale, $fallback)
    {
        $not_translate_path = storage_path('untranslated/not-translate.json');
        $not_translate = [];
        if (File::exists($not_translate_path)) {
            $json = File::get($not_translate_path);
            $decoded = json_decode($json, true);
            if (is_array($decoded)) {
                $not_translate = $decoded;
            }
        }

        if (in_array($key, $not_translate)) {
            return $key;
        }

        $path = storage_path('untranslated/to-translate.json');

        $current = [];

        if (File::exists($path)) {
            $json = File::get($path);
            $decoded = json_decode($json, true);
            if (is_array($decoded)) {
                $current = $decoded;
            }
        }

        // if already exist, skip
        if (array_key_exists($key, $current)) {
            return $key;
        }



        // check if Notification Message and exclude it
        $is_notification_message = NotificationMessage::where('body', $key)
            ->orWhere('title', $key)
            ->exists();
        if ($is_notification_message) {
            $not_translate[] = $key;
            File::put($not_translate_path, json_encode($not_translate, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return $key;
        }


        // Add the missing key if not already present
        if (!array_key_exists($key, $current)) {
            $current[$key] = $key;
            File::put($path, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        return $key; // Return the key as the translation
    }
}
