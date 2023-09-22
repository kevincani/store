<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'kevincani1',
            'first_name' => 'Kevin',
            'last_name' => 'Cani',
            'email' => 'kevincani@hotmail.com',
            'email_verified_at' => now(),
            'password' => 'canial321', // password
            'address' => 'Kamez',
            'phone' => '0693242843',
            'remember_token' => Str::random(10),
        ])->assignRole('admin');

        User::create([
            'username' => 'riseldcani1',
            'first_name' => 'Riseld',
            'last_name' => 'Cani',
            'email' => 'riseldcani@hotmail.com',
            'email_verified_at' => now(),
            'password' => 'canial321', // password
            'address' => 'Kamez',
            'phone' => '0692324348',
            'remember_token' => Str::random(10),
        ])->assignRole('manager');

//        $users=User::all();
//        foreach ($users as $user){
//            if (!$user->hasRole('admin') || !$user->hasRole('manager')){
//                $user->assignRole('client');
//            }
//        }

    }
}
