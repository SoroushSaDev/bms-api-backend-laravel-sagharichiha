<?php

use App\Models\Translation;

function translate(string $key, $lang = null)
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
