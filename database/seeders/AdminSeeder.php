<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@salon.com'],
            [
                'name' => 'salon Admin',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
            ]
        );
    }
}