<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = Country::all();
        foreach ($countries as $country) {
            $translation = Translation::where('key', $country->en_name)->where('lang', 'fa')->first();
            if (is_null($translation))
                Translation::create([
                    'lang' => 'fa',
                    'user_id' => null,
                    'key' => $country->en_name,
                    'value' => $country->fa_name,
                ]);
            else
                $translation->update([
                    'value' => $country->fa_name,
                ]);
        }
    }
}
