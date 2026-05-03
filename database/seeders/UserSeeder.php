<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Admin account
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // ✅ Staff account
        User::create([
            'name' => 'Staff Member',
            'email' => 'staff@example.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);
    }
}
