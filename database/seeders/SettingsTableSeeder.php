<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'key' => 'app_name',
                'value' => 'PadangSYMCARD',
            ],
            [
                'key' => 'date_format',
                'value' => 'MM/DD/YYYY',
            ],
            [
                'key' => 'pagination_limit',
                'value' => 10,
            ],
            [
                'key' => 'environment',
                'value' => 'development',
            ],
        ]);
    }
}