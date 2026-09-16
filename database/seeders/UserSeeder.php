<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supervisor',
                'email' => 'spv@example.com',
                'password' => Hash::make('password123'),
                'role' => 'SPV',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Outlet 1',
                'email' => 'outlet1@example.com',
                'password' => Hash::make('password123'),
                'role' => 'outlet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Outlet 2',
                'email' => 'outlet2@example.com',
                'password' => Hash::make('password123'),
                'role' => 'outlet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Outlet 3',
                'email' => 'outlet3@example.com',
                'password' => Hash::make('password123'),
                'role' => 'outlet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'name' => 'Outlet 4',
                'email' => 'outlet4@example.com',
                'password' => Hash::make('password123'),
                'role' => 'outlet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                'name' => 'Outlet 5',
                'email' => 'outlet5@example.com',
                'password' => Hash::make('password123'),
                'role' => 'outlet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}