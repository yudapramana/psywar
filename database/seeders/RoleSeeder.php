<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            // =========================
            // SYSTEM LEVEL
            // =========================
            [
                'name' => 'SUPERADMIN',
                'slug' => 'superadmin',
            ],

            // =========================
            // ADMIN LEVEL
            // =========================
            [
                'name' => 'MASTER_ADMIN',
                'slug' => 'master_admin',
            ],
            [
                'name' => 'FINANCE_ADMIN',
                'slug' => 'finance_admin',
            ],
            [
                'name' => 'CONTENT_ADMIN',
                'slug' => 'content_admin',
            ],
            [
                'name' => 'SCIENCE_ADMIN',
                'slug' => 'science_admin',
            ],

            // =========================
            // OPERATIONAL
            // =========================
            [
                'name' => 'COMMITTEE',
                'slug' => 'committee',
            ],

            // =========================
            // END USER
            // =========================
            [
                'name' => 'PARTICIPANT',
                'slug' => 'participant',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                ['name' => $role['name']]
            );
        }
    }
}
