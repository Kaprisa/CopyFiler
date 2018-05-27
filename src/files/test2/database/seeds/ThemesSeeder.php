<?php

use App\Theme;
use Illuminate\Database\Seeder;

class ThemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $colors = [
            'primary' => '#7fcbc4',
            'secondary' => '#ff1744',
            'accent' => '#ffc107',
            'error' => '#F44336',
            'info' => '#2196F3',
            'success' => '#00e676',
            'warning' => '#ab47bc'
        ];

        Theme::create([
            'name' => 'app',
            'colors' => json_encode($colors)
        ]);

        $colors = [
            'primary' => '#1976D2',
            'secondary' => '#424242',
            'accent' => '#82B1FF',
            'error' => '#FF5252',
            'info' => '#2196F3',
            'success' => '#4CAF50',
            'warning' => '#FFC107'
        ];

        Theme::create([
            'name' => 'app',
            'colors' => json_encode($colors)
        ]);
    }
}
