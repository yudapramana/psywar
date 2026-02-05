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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            SettingsTableSeeder::class,
            RoleSeeder::class,
            RolePermissionSeeder::class,
            ParticipantCategorySeeder::class,
            PaperTypeSeeder::class,
            BoardCommitteeSeeder::class,
            GallerySeeder::class,
            EventSeeder::class,
            RoomSeeder::class,
            ActivitySeeder::class,
            SessionSeeder::class,
            BankSeeder::class,
            PricingItemSeeder::class,
            UserSeeder::class,
            UserParticipantSeeder::class,
        ]);
    }
}
