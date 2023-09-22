<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Color::create([
            'color' => 'blue',
        ]);

        Color::create([
            'color' => 'red',
        ]);

        Color::create([
            'color' => 'black',
        ]);

        Color::create([
            'color' => 'white',
        ]);

        Color::create([
            'color' => 'yellow',
        ]);

        Color::create([
            'color' => 'green',
        ]);
    }
}
