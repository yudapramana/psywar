<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserParticipantSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Ambil participant category ID berdasarkan nama
        $categories = DB::table('participant_categories')
            ->pluck('id', 'name');

        $users = [
            [
                'name'       => 'Nadya Rachmayani',
                'email'      => 'nadya@student.test',
                'category'   => 'Student / Nurse',
                'institution'=> 'Universitas Andalas',
            ],
            [
                'name'       => 'Lathifatul Hamidah',
                'email'      => 'lathifatul@gp.test',
                'category'   => 'General Practitioner / Internship',
                'institution'=> 'RSUD Dr. M. Djamil Padang',
            ],
            [
                'name'       => 'Najmi Ilal Hayati',
                'email'      => 'najmi@specialist.test',
                'category'   => 'Specialist',
                'institution'=> 'RSUP Dr. M. Djamil Padang',
            ],
        ];

        foreach ($users as $index => $data) {

            // ======================
            // VALIDASI CATEGORY
            // ======================
            if (!isset($categories[$data['category']])) {
                throw new \Exception("Participant category '{$data['category']}' not found.");
            }

            // ======================
            // GENERATE DATA
            // ======================
            $nik = $this->generateNik($index);
            $phone = $this->generatePhone($index);

            // ======================
            // CREATE USER
            // ======================
            $userId = DB::table('users')->insertGetId([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'email_verified_at' => $now,
                'password'          => Hash::make('password'),
                'role_id'           => 1, // user biasa
                'remember_token'    => Str::random(10),
                'created_at'        => $now,
                'updated_at'        => $now,
            ]);

            // ======================
            // CREATE PARTICIPANT
            // ======================
            DB::table('participants')->insert([
                'user_id'                 => $userId,
                'nik'                     => $nik,
                'full_name'               => $data['name'],
                'email'                   => $data['email'],
                'mobile_phone'            => $phone,
                'institution'             => $data['institution'],
                'participant_category_id' => $categories[$data['category']],
                'registration_type'       => 'non_sponsored',
                'created_at'              => $now,
                'updated_at'              => $now,
            ]);
        }
    }

    /**
     * Generate NIK 16 digit (dummy but valid format)
     */
    private function generateNik(int $index): string
    {
        // 13xxxx (Sumbar) + tanggal random + unique index
        return '1371' . str_pad((string)(rand(1000000000, 9999999999) + $index), 12, '0', STR_PAD_LEFT);
    }

    /**
     * Generate nomor HP Indonesia
     */
    private function generatePhone(int $index): string
    {
        return '08' . rand(1111, 9999) . rand(111111, 999999) + $index;
    }
}
