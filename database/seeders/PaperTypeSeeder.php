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
                'code' => 'RESEARCH',
                'name' => 'Research',
                'description' => 'Scientific Research submission (DOCX)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'CASE',
                'name' => 'Case Report',
                'description' => 'Clinical case submission with images inside document',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
