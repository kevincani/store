<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ColorSeeder::class);
        $this->call(DiscountSeeder::class);
        $this->call(SizeSeeder::class);

//         OrderItem::factory(5)->create();


         $users=User::all();

        foreach ($users as $user){
            if (!$user->hasRole('admin') || !$user->hasRole('manager')){
                $user->assignRole('client');
            }
        }







        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
