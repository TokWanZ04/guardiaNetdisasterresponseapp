<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_type' => 'Admin',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Responder John',
            'email' => 'responder@example.com',
            'password' => bcrypt('password'),
            'role_type' => 'Responder',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Citizen Jane',
            'email' => 'citizen@example.com',
            'password' => bcrypt('password'),
            'role_type' => 'Citizen',
        ]);
    }
}
