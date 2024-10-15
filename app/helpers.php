<?php

use App\Models\Translation;

function translate($key, $lang = null)
{
    if ($key != null) {
        $lang = $lang == null ? (auth()->check() ? (auth()->user()->Profile?->language ?? 'en') : 'en') : $lang;
        $translation = Translation::where('key', $key)->where('lang', $lang)->first();
        if (is_null($translation))
            $translation = Translation::create([
                'key' => $key,
                'lang' => $lang,
                'user_id' => auth()->check() ? auth()->id() : null,
            ]);
        return $translation->value ?? $key;
    } else return $key;
}

function TranslateAll($model, array $properties): void
{
    foreach ($properties as $property) {
        $model->{$property} = translate($model->{$property});
    }
}


function GetDirection($lang = null): string
{
    $appLang = App::getLocale();
    $lang = is_null($lang) ? (auth()->check() ? (auth()->user()?->Profile?->language ?? $appLang) : $appLang) : $lang;
    return in_array($lang, ['fa', 'ar']) ? 'RTL' : 'LTR';
}

function CheckClass($type, $check, $checker): string
{
    $link = $check == $checker ? 'bg-blue-500 hover:bg-blue-600' : 'hover:bg-gray-100 dark:hover:bg-gray-700';
    $svg = $check == $checker ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500 dark:text-gray-400';
    return match ($type) {
        'link' => $link,
        'svg' => $svg,
        default => '',
    };
}
