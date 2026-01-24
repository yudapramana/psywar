<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PricingItemSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil category ID by name (AMAN, tidak hardcode)
        $categories = DB::table('participant_categories')
            ->pluck('id', 'name');

        $prices = [

            /* ======================================================
             * STUDENT / NURSE
             * ====================================================== */
            // Symposium only
            ['cat' => 'Student / Nurse', 'bird' => 'early', 'ws' => 0, 'price' => 300000],
            ['cat' => 'Student / Nurse', 'bird' => 'late',  'ws' => 0, 'price' => 400000],

            // Symposium + Workshop (Bundling)
            ['cat' => 'Student / Nurse', 'bird' => 'early', 'ws' => 1, 'price' => 1000000],
            ['cat' => 'Student / Nurse', 'bird' => 'late',  'ws' => 1, 'price' => 1200000],
            ['cat' => 'Student / Nurse', 'bird' => 'early', 'ws' => 2, 'price' => 1700000],
            ['cat' => 'Student / Nurse', 'bird' => 'late',  'ws' => 2, 'price' => 2000000],

            /* ======================================================
             * GENERAL PRACTITIONER / INTERNSHIP
             * ====================================================== */
            // Symposium only
            ['cat' => 'General Practitioner / Internship', 'bird' => 'early', 'ws' => 0, 'price' => 500000],
            ['cat' => 'General Practitioner / Internship', 'bird' => 'late',  'ws' => 0, 'price' => 600000],

            // Symposium + Workshop (Bundling)
            ['cat' => 'General Practitioner / Internship', 'bird' => 'early', 'ws' => 1, 'price' => 1200000],
            ['cat' => 'General Practitioner / Internship', 'bird' => 'late',  'ws' => 1, 'price' => 1500000],
            ['cat' => 'General Practitioner / Internship', 'bird' => 'early', 'ws' => 2, 'price' => 1800000],
            ['cat' => 'General Practitioner / Internship', 'bird' => 'late',  'ws' => 2, 'price' => 2200000],

            /* ======================================================
             * SPECIALIST
             * ====================================================== */
            // Symposium only
            ['cat' => 'Specialist', 'bird' => 'early', 'ws' => 0, 'price' => 900000],
            ['cat' => 'Specialist', 'bird' => 'late',  'ws' => 0, 'price' => 1200000],

            // Symposium + Workshop (Bundling)
            ['cat' => 'Specialist', 'bird' => 'early', 'ws' => 1, 'price' => 2300000],
            ['cat' => 'Specialist', 'bird' => 'late',  'ws' => 1, 'price' => 2800000],
            ['cat' => 'Specialist', 'bird' => 'early', 'ws' => 2, 'price' => 3700000],
            ['cat' => 'Specialist', 'bird' => 'late',  'ws' => 2, 'price' => 4300000],
        ];

        $now = now();

        $data = collect($prices)->map(function ($row) use ($categories, $now) {
            return [
                'participant_category_id' => $categories[$row['cat']],
                'bird_type'               => $row['bird'],
                'workshop_count'          => $row['ws'],
                'price'                   => $row['price'],
                'created_at'              => $now,
                'updated_at'              => $now,
            ];
        })->toArray();

        DB::table('pricing_items')->insert($data);
    }
}
