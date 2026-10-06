<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // 🚀 Use Spatie's Role model instead!

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // firstOrCreate ensures it doesn't duplicate if they already exist
        Role::firstOrCreate(['name' => 'student']);
        Role::firstOrCreate(['name' => 'driver']);
        Role::firstOrCreate(['name' => 'guardian']);
    }
}
