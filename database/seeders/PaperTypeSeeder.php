<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaperTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('paper_types')->insert([
            [
                'name' => 'Abstract',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Full Paper',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Case Report',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
