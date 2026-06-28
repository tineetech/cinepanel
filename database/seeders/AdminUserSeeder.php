<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Panel',
                'role' => 'superadmin',
                'password' => Hash::make('admin'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'crew@gmail.com'],
            [
                'name' => 'Crew Panel',
                'role' => 'crew',
                'password' => Hash::make('crew'),
                'email_verified_at' => now(),
            ]
        );
    }
}
