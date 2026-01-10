<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'ithelpsdesk1@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => true,
        ]);
    }
}
