<?php


// Configure languages and map the country flags to them

$all_languages = json_decode(file_get_contents(public_path('assets/json/languages.json')), true);

$enabled_languages = array_filter($all_languages, function ($language) {
    return $language['enabled'];
});

return $enabled_languages;
