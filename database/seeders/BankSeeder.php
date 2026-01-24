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
                'account_number' => '1234567890',
                'account_name' => 'Kelakar Unand',
                'order' => 1,
            ],
            [
                'code' => 'BRI',
                'name' => 'Bank Rakyat Indonesia',
                'account_number' => '1234567891',
                'account_name' => 'Kelakar Unand',
                'order' => 2,
            ],
            [
                'code' => 'BNI',
                'name' => 'Bank Negara Indonesia',
                'account_number' => '1234567892',
                'account_name' => 'Kelakar Unand',
                'order' => 3,
            ],
            [
                'code' => 'BCA',
                'name' => 'Bank Central Asia',
                'account_number' => '1234567893',
                'account_name' => 'Kelakar Unand',
                'order' => 4,
            ],
            [
                'code' => 'MANDIRI',
                'name' => 'Bank Mandiri',
                'account_number' => '1234567894',
                'account_name' => 'Kelakar Unand',
                'order' => 5,
            ],
        ]);
    }
}
