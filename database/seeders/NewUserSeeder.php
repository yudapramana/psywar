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
        
        // MARZAIN
        $masterAdminMarzain = User::updateOrCreate(
            ['email' => 'mrzmhd@gmail.com'],
            [
                'name'              => 'dr. Muhammad Marzain',
                'username'          => 'mrzmhd',
                'password'          => Hash::make('marzainadmin'),
                'role_id'           => $roles['master_admin']->id,
                'can_multiple_role' => true,
            ]
        );

        $masterAdminMarzain->roles()->syncWithoutDetaching([
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

        // FIGA
        $financeAdminFiga = User::updateOrCreate(
            ['email' => 'figa15@yahoo.com'],
            [
                'name'              => 'dr. Figa Prima Dani',
                'username'          => 'figa15',
                'password'          => Hash::make('figaadmin'),
                'role_id'           => $roles['finance_admin']->id,
                'can_multiple_role' => false,
            ]
        );

        $financeAdminFiga->roles()->syncWithoutDetaching([
            $roles['finance_admin']->id,
        ]);

        // MONIVA
        $financeAdminMonica = User::updateOrCreate(
            ['email' => 'monicaok@gmail.com'],
            [
                'name'              => 'dr. Monica Oktariyanthy',
                'username'          => 'monicaok',
                'password'          => Hash::make('monicaadmin'),
                'role_id'           => $roles['finance_admin']->id,
                'can_multiple_role' => false,
            ]
        );

        $financeAdminMonica->roles()->syncWithoutDetaching([
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

        // FIKRI
        $scienceAdminFikri = User::updateOrCreate(
            ['email' => 'fikrisk@gmail.com'],
            [
                'name'              => 'Muhammad fikri satria kamal',
                'username'          => 'fikrisk',
                'password'          => Hash::make('fikriadmin'),
                'role_id'           => $roles['science_admin']->id,
                'can_multiple_role' => false,
            ]
        );

        $scienceAdminFikri->roles()->syncWithoutDetaching([
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
