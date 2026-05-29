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
        // 1. Create Core Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@ams.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        $hannane = User::firstOrCreate(
            ['email' => 'hannane@ams.com'],
            [
                'name' => 'Hannane Admin',
                'password' => Hash::make('password'),
            ]
        );
        $hannane->assignRole('admin');

        // 2. Create Core Teachers
        $imane = User::firstOrCreate(
            ['email' => 'imane@ams.com'],
            [
                'name' => 'Imane Teacher',
                'password' => Hash::make('password'),
            ]
        );
        $imane->assignRole('teacher');

        $ahmed = User::firstOrCreate(
            ['email' => 'ahmed@ams.com'],
            [
                'name' => 'Ahmed Teacher',
                'password' => Hash::make('password'),
            ]
        );
        $ahmed->assignRole('teacher');
    }
}
