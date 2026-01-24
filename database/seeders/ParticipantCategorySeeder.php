<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Student / Nurse',
            ],
            [
                'name' => 'General Practitioner / Internship',
            ],
            [
                'name' => 'Specialist',
            ],
        ];

        DB::table('participant_categories')->insert(
            collect($categories)->map(fn ($item) => [
                'name'       => $item['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray()
        );
    }
}
