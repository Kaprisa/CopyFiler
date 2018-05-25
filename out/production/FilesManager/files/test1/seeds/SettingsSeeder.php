<?php

use App\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'name' => 'app_name',
            'value' => 'Quadric',
            'field_type' => 'input'
        ]);

        Setting::create([
            'name' => 'logo',
            'value' => 'Quadric',
            'field_type' => 'input'
        ]);

        Setting::create([
            'name' => 'seo_title',
            'value' => 'Quadric',
            'field_type' => 'input'
        ]);

        Setting::create([
            'name' => 'seo_description',
            'value' => 'Сайт online образования',
            'field_type' => 'textarea'
        ]);
    }
}
