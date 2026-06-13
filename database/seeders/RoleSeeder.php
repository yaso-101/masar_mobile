<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Checks if roles exist to prevent duplicates
        if (Role::query()->exists()) {
            return;
        }

        $roles = [
            ['name' => 'Student', 'slug' => 'student'],
            ['name' => 'Driver', 'slug' => 'driver'],
            ['name' => 'Guardian', 'slug' => 'guardian'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
