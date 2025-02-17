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
            'name' => 'AdminUSU',
            'email' => 'gurenorakun@gmail.com',
            'password' => Hash::make('glendy'), // Change this password
        ]);

    }
}

