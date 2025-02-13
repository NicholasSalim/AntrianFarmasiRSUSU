<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Queue Operator',
            'email' => 'operator@example.com',
            'password' => Hash::make('password123'), // Change this password
        ]);

        User::create([
            'name' => 'Queue Operator',
            'email' => 'nicholassalim05@gmail.com',
            'password' => Hash::make('nicho'), // Change this password
        ]);

        User::create([
            'name' => 'Glendy',
            'email' => 'gurenoyumi@gmail.com',
            'password' => Hash::make('glendy'), // Change this password
        ]);

    }
}

