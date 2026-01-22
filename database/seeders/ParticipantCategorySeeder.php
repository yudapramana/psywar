<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Fellow in Training',
            'General Practitioners',
            'Interventional Cardiologists',
            'Medical Technicians / Radiographer',
            'Nurse',
            'Other Specialist',
        ];

        $data = collect($categories)->map(function ($name) {
            return [
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        DB::table('participant_categories')->insert($data);
    }
}
