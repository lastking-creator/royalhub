<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cbo.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin1234'),
                'role' => 'admin',
                'status' => 'approved',
                'registration_number' => 'JVT-2026-0001',
            ]
        );
    }
}