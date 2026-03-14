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
            ['email' => 'admin@refrens.com'],
            [
                'name' => 'Administrator',
                'phone' => '081234567890',
                'address' => 'Kantor Pusat',
                'password' => Hash::make('123456'),
                'role' => 'admin',
            ]
        );
    }
}

