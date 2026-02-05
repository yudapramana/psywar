<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ============================
        // AMBIL ROLE BERDASARKAN SLUG
        // ============================
        $roles = Role::whereIn('slug', [
            'superadmin',
            'master_admin',
            'finance_admin',
            'content_admin',
            'science_admin',
            'committee',
        ])->get()->keyBy('slug');

        // ============================
        // SUPERADMIN
        // ============================
        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@padangsymcard.com'],
            [
                'name'              => 'Super Admin',
                'username'          => 'superadmin',
                'password'          => Hash::make('password'),
                'role_id'           => $roles['superadmin']->id,
                'can_multiple_role' => true,
            ]
        );

        $superadmin->roles()->syncWithoutDetaching([
            $roles['superadmin']->id,
            $roles['master_admin']->id,
            $roles['finance_admin']->id,
            $roles['content_admin']->id,
            $roles['science_admin']->id,
        ]);

        // ============================
        // MASTER ADMIN
        // ============================
        $masterAdmin = User::updateOrCreate(
            ['email' => 'admin@padangsymcard.com'],
            [
                'name'              => 'Master Admin',
                'username'          => 'admin',
                'password'          => Hash::make('password'),
                'role_id'           => $roles['master_admin']->id,
                'can_multiple_role' => true,
            ]
        );

        $masterAdmin->roles()->syncWithoutDetaching([
            $roles['master_admin']->id,
        ]);

        // ============================
        // FINANCE ADMIN
        // ============================
        $financeAdmin = User::updateOrCreate(
            ['email' => 'finance@padangsymcard.com'],
            [
                'name'              => 'Finance Admin',
                'username'          => 'finance_admin',
                'password'          => Hash::make('password'),
                'role_id'           => $roles['finance_admin']->id,
                'can_multiple_role' => false,
            ]
        );

        $financeAdmin->roles()->syncWithoutDetaching([
            $roles['finance_admin']->id,
        ]);

        // ============================
        // CONTENT ADMIN
        // ============================
        $contentAdmin = User::updateOrCreate(
            ['email' => 'content@padangsymcard.com'],
            [
                'name'              => 'Content Admin',
                'username'          => 'content_admin',
                'password'          => Hash::make('password'),
                'role_id'           => $roles['content_admin']->id,
                'can_multiple_role' => false,
            ]
        );

        $contentAdmin->roles()->syncWithoutDetaching([
            $roles['content_admin']->id,
        ]);

        // ============================
        // SCIENCE ADMIN (PAPER REVIEW)
        // ============================
        $scienceAdmin = User::updateOrCreate(
            ['email' => 'science@padangsymcard.com'],
            [
                'name'              => 'Science Admin',
                'username'          => 'science_admin',
                'password'          => Hash::make('password'),
                'role_id'           => $roles['science_admin']->id,
                'can_multiple_role' => false,
            ]
        );

        $scienceAdmin->roles()->syncWithoutDetaching([
            $roles['science_admin']->id,
        ]);

        // ============================
        // COMMITTEE
        // ============================
        $committee = User::updateOrCreate(
            ['email' => 'committee@padangsymcard.com'],
            [
                'name'              => 'Committee',
                'username'          => 'committee',
                'password'          => Hash::make('password'),
                'role_id'           => $roles['committee']->id,
                'can_multiple_role' => false,
            ]
        );

        $committee->roles()->syncWithoutDetaching([
            $roles['committee']->id,
        ]);
    }
}
