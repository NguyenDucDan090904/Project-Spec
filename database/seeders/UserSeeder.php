<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Account Admin
        User::create([
            'name'     => 'System Admin',
            'email'    => 'admin@gmail.com',
            'role'     => 'admin',
            'password' => Hash::make('password123'),
        ]);

        // Account User example
        User::create([
            'name'     => 'Regular User',
            'email'    => 'user@gmail.com',
            'role'     => 'user',
            'password' => Hash::make('password123'),
        ]);

        // Create 10 User random
        User::factory(10)->create([
            'role' => 'user'
        ]);
    }
}
