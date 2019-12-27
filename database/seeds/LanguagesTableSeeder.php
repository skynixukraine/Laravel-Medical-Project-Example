<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Language::insert([
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'de', 'name' => 'German'],
            ['code' => 'fr', 'name' => 'French'],
            ['code' => 'nl', 'name' => 'Dutch'],
            ['code' => 'cs', 'name' => 'Czech'],
            ['code' => 'uk', 'name' => 'Ukrainian'],
            ['code' => 'ru', 'name' => 'Russian'],
            ['code' => 'da', 'name' => 'Danish'],
            ['code' => 'be', 'name' => 'Belarusian'],
            ['code' => 'az', 'name' => 'Azerbaijani'],
            ['code' => 'et', 'name' => 'Estonian'],
            ['code' => 'he', 'name' => 'Hebrew'],
            ['code' => 'it', 'name' => 'Italian'],
            ['code' => 'ga', 'name' => 'Irish'],
            ['code' => 'ja', 'name' => 'Japanese'],
            ['code' => 'kk', 'name' => 'Kazakh'],
            ['code' => 'ko', 'name' => 'Korean'],
            ['code' => 'pl', 'name' => 'Polish'],
            ['code' => 'sk', 'name' => 'Slovak'],
            ['code' => 'sl', 'name' => 'Slovenian'],
            ['code' => 'es', 'name' => 'Spanish'],
            ['code' => 'sv', 'name' => 'Swedish'],
            ['code' => 'tr', 'name' => 'Turkish'],
            ['code' => 'zh', 'name' => 'Chinese'],
        ]);
    }
}
