<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@bluestcoffee.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Erik (Pemilik)',
            'username' => 'erik',
            'email' => 'erik@bluestcoffee.com',
            'password' => Hash::make('erik123'),
            'role' => 'admin',
        ]);
    }
}