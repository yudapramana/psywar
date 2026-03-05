<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('banks')->insert([
            [
                'code' => 'BSI',
                'name' => 'Bank Syariah Indonesia',
                'account_number' => '2222211557',
                'account_name' => 'Kelakar Unand',
                'order' => 1,
            ],
        ]);
    }
}
