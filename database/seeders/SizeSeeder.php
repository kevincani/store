<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Size::create([
            'size' => '37',
        ]);

        Size::create([
            'size' => '38',
        ]);

        Size::create([
            'size' => '39',
        ]);

        Size::create([
            'size' => '40',
        ]);

        Size::create([
            'size' => '41',
        ]);

        Size::create([
            'size' => 'S',
        ]);

        Size::create([
            'size' => 'M',
        ]);

        Size::create([
            'size' => 'L',
        ]);
    }
}
