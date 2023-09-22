<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Discount::create([
            'discount_percent' => 0,
        ]);
        Discount::create([
            'discount_percent' => 10,
        ]);

        Discount::create([
            'discount_percent' => 20,
        ]);

        Discount::create([
            'discount_percent' => 30,
        ]);
    }
}
