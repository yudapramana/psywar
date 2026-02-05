<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. DEFINISI PERMISSIONS (SESUAI SIDEBAR)
        |--------------------------------------------------------------------------
        */
        $permissions = [
            // DASHBOARD
            ['name' => 'View Dashboard', 'slug' => 'view.dashboard'],

            // =========================
            // MASTER DATA
            // =========================
            ['name' => 'Manage Master Data', 'slug' => 'manage.master'],
            ['name' => 'Manage Permissions & Roles', 'slug' => 'manage.master.permissions'],
            ['name' => 'Manage Users', 'slug' => 'manage.master.users'],
            ['name' => 'Manage Participant Categories', 'slug' => 'manage.master.participant-categories'],
            ['name' => 'Manage Participants', 'slug' => 'manage.master.participants'],

            // =========================
            // EVENT & STRUKTUR
            // =========================
            ['name' => 'Manage Event & Structure', 'slug' => 'manage.event'],
            ['name' => 'Manage Events', 'slug' => 'manage.event.events'],
            ['name' => 'Manage Event Days', 'slug' => 'manage.event.event-days'],
            ['name' => 'Manage Rooms', 'slug' => 'manage.event.rooms'],
            ['name' => 'Manage Activities', 'slug' => 'manage.event.activities'],

            // =========================
            // PRICING & BANK
            // =========================
            ['name' => 'Manage Pricing', 'slug' => 'manage.pricing'],
            ['name' => 'Manage Pricing Items', 'slug' => 'manage.pricing.items'],
            ['name' => 'Manage Banks', 'slug' => 'manage.pricing.banks'],

            // =========================
            // OPERASIONAL EVENT
            // =========================
            ['name' => 'Manage Sessions', 'slug' => 'manage.sessions'],

            // =========================
            // TRANSAKSIONAL
            // =========================
            ['name' => 'Manage Registrations', 'slug' => 'manage.registrations'],
            ['name' => 'Manage Payments', 'slug' => 'manage.payments'],

            // PAYMENT VERIFICATIONS
            ['name' => 'Manage Payment Verifications', 'slug' => 'manage.payment-verifications'],
            ['name' => 'Payment Verification Queue', 'slug' => 'manage.payment-verifications.queue'],
            ['name' => 'Payment Verification History', 'slug' => 'manage.payment-verifications.history'],

            // FINANCE
            ['name' => 'View Finance Dashboard', 'slug' => 'view.finance-dashboard'],

            // =========================
            // SCIENTIFIC
            // =========================
            ['name' => 'Manage Paper Types', 'slug' => 'manage.paper-types'],
            ['name' => 'Review Papers', 'slug' => 'manage.papers.review'],
            ['name' => 'Finalize Papers', 'slug' => 'manage.papers.final'],

            // =========================
            // SETTINGS
            // =========================
            ['name' => 'Manage Settings', 'slug' => 'manage.settings'],
        ];

        // simpan permission
        $permissionModels = [];
        foreach ($permissions as $perm) {
            $permissionModels[$perm['slug']] = Permission::updateOrCreate(
                ['slug' => $perm['slug']],
                ['name' => $perm['name']]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. AMBIL ROLE
        |--------------------------------------------------------------------------
        */
        $roles = Role::whereIn('slug', [
            'superadmin',
            'master_admin',
            'finance_admin',
            'content_admin',
            'science_admin',
            'committee',
        ])->get()->keyBy('slug');

        $allPermissionIds = collect($permissionModels)->pluck('id')->all();

        /*
        |--------------------------------------------------------------------------
        | 3. MAPPING PER ROLE (SESUAI TUGAS)
        |--------------------------------------------------------------------------
        */

        // 🔝 SUPERADMIN → SEMUA
        $roles['superadmin']->permissions()->sync($allPermissionIds);

        // 🛠️ MASTER ADMIN → SEMUA KECUALI FINANCE DASHBOARD
        $roles['master_admin']->permissions()->sync(
            collect($permissionModels)
                ->reject(fn ($p) => $p->slug === 'view.finance-dashboard')
                ->pluck('id')
                ->all()
        );

        // 💰 FINANCE ADMIN → TRANSAKSIONAL + FINANCE
        $roles['finance_admin']->permissions()->sync(
            collect($permissionModels)
                ->whereIn('slug', [
                    'manage.registrations',
                    'manage.payments',
                    'manage.payment-verifications',
                    'manage.payment-verifications.queue',
                    'manage.payment-verifications.history',
                    'view.finance-dashboard',
                ])
                ->pluck('id')
                ->all()
        );

        // 🖼️ CONTENT ADMIN → SETTINGS ONLY
        $roles['content_admin']->permissions()->sync(
            collect($permissionModels)
                ->whereIn('slug', [
                    'manage.settings',
                ])
                ->pluck('id')
                ->all()
        );

        // 🔬 SCIENCE ADMIN → SCIENTIFIC MODULE
        $roles['science_admin']->permissions()->sync(
            collect($permissionModels)
                ->whereIn('slug', [
                    'manage.papers.review',
                    'manage.papers.final',
                ])
                ->pluck('id')
                ->all()
        );

        // 🧩 COMMITTEE → OPERASIONAL + REGISTRASI
        $roles['committee']->permissions()->sync(
            collect($permissionModels)
                ->whereIn('slug', [
                    'manage.sessions',
                    'manage.registrations',
                ])
                ->pluck('id')
                ->all()
        );

    }
}
