<?php

use App\Models\Translation;

function translate($key, $lang = null)
{
    $lang = $lang == null ? (auth()->check() ? (auth()->user()->Profile?->language ?? 'en') : 'en') : $lang;
    $translation = Translation::where('key', $key)->where('lang', $lang)->first();
    if (is_null($translation))
        $translation = Translation::create([
            'key' => $key,
            'lang' => $lang,
            'user_id' => auth()->check() ? auth()->id() : null,
        ]);
    return $translation->value ?? $key;
}
