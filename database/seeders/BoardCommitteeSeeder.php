<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoardCommitteeSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | BOARD GROUPS
            |--------------------------------------------------------------------------
            */
            $groups = [
                1 => 'Patron',
                2 => 'Advisor',
                3 => 'Organizing Committee',
                4 => 'Scientific and Event Committee',
            ];

            foreach ($groups as $id => $name) {
                DB::table('board_groups')->insert([
                    'id' => $id,
                    'name' => $name,
                    'order' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | BOARD SUB SECTIONS
            |--------------------------------------------------------------------------
            */
            $subSections = [
                // Organizing Committee
                1  => [3, 'Core'],
                2  => [3, 'Secretary and Registration'],
                3  => [3, 'Treasurer'],
                4  => [3, 'Funding and Sponsorship'],
                5  => [3, 'Promotion, Publication & Documentation'],
                6  => [3, 'IT and Multimedia'],
                7  => [3, 'Logistic, Exhibition & Consumption'],

                // Scientific & Event Committee
                8  => [4, 'Workshop'],
                9  => [4, 'Symposium'],
                10 => [4, 'Scientific Members'],
                11 => [4, 'Poster & Abstract Competition'],
            ];

            foreach ($subSections as $id => [$groupId, $name]) {
                DB::table('board_sub_sections')->insert([
                    'id' => $id,
                    'board_group_id' => $groupId,
                    'name' => $name,
                    'order' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | BOARD MEMBERS
            |--------------------------------------------------------------------------
            */
            $members = [

                // ================= Pelindung =================
                [1, null, 'Dekan FK Universitas Andalas'],
                [1, null, 'Ketua PERKI Cabang Sumatera Barat'],
                [1, null, 'Kepala Departemen Kardiologi dan Kedokteran Vaskular'],
                [1, null, 'FK Universitas Andalas / RSUP Dr. M. Djamil Padang'],

                // ================= Advisor =================
                [2, null, 'dr. Yerizal Karani, Sp.JP(K)'],
                [2, null, 'dr. Muhammad Syukri, Sp.JP(K)'],
                [2, null, 'Dr. dr. Masrul Syafri, Sp.PD, Sp.JP(K)'],
                [2, null, 'Prof. dr. Hardisman, M.HID, Dr.PH, FRSHP'],
                [2, null, 'dr. Didik Hariyanto, Sp.A(K)'],

                // ================= Organizing Committee – Core =================
                [3, 1, 'dr. Citra Kiki Krevani, Sp.JP(K)', 'Chairperson'],
                [3, 1, 'dr. Nani, Sp.JP(K)', 'Vice Chairperson'],
                [3, 1, 'dr. Rengga Sebastian', 'Coordinator'],
                [3, 1, 'dr. Franky Ladediska', 'Vice Coordinator'],

                // ================= Secretary & Registration =================
                [3, 2, 'dr. Rita Hamdani, Sp.JP(K)'],
                [3, 2, 'dr. Putri Risani'],
                [3, 2, 'dr. Tiffany Adelina'],
                [3, 2, 'Afriandi Putra, S.Pd'],
                [3, 2, 'Nova Ariani, SE'],
                [3, 2, 'Nelfazrita, S.Kom'],
                [3, 2, 'Wira'],

                // ================= Treasurer =================
                [3, 3, 'dr. Putri Handayani, Sp.JP'],
                [3, 3, 'dr. Figa Prima Dani'],
                [3, 3, 'dr. Monica Oktariyanthy'],

                // ================= Scientific & Event – Workshop =================
                [4, 8, 'dr. Wiza Erlanda, Sp.JP(K)'],

                // ================= Scientific & Event – Symposium =================
                [4, 9, 'Dr. dr. Tommy Daindes, Sp.JP(K)'],

                // ================= Scientific Members =================
                [4,10,'dr. Merlin Sari Mutma Indah'],
                [4,10,'dr. Ridha Hayyu Nisa'],
                [4,10,'dr. Indra Fahlevi'],
                [4,10,'dr. Restu'],
                [4,10,'dr. Yaumil Wahyuni'],
                [4,10,'dr. Wahyudi Firmana'],
                [4,10,'dr. Heka Widya'],
                [4,10,'dr. Herlina'],
                [4,10,'dr. Khaula Luthfiyah'],
                [4,10,'dr. Heri Suhendra'],
                [4,10,'dr. Jolatuel Bahana'],
                [4,10,'dr. Hajar Nurfa Jirin'],
                [4,10,'dr. Angga Putra Perdana'],
                [4,10,'dr. Andy Kurnia'],

                // ================= Poster & Abstract Competition =================
                [4,11,'dr. Kino, Sp.JP(K)'],
                [4,11,'Prof. Dr. dr. Aisyah Elyanti, Sp.KN(K)'],
                [4,11,'dr. Hauda El Rasyid, Sp.JP(K)'],
                [4,11,'dr. Hirowati Ali, Ph.D'],
                [4,11,'dr. Fiqi Decroli'],
                [4,11,'dr. Fikri Satria'],

                // ================= Funding & Sponsorship =================
                [3,4,'dr. Mefri Yanni, Sp.JP(K)'],
                [3,4,'dr. Eka Fithra Elfi, Sp.JP(K)'],
                [3,4,'dr. Yose Ramda Ilhami, Sp.JP(K)'],
                [3,4,'dr. Putri Handayani, Sp.JP'],
                [3,4,'dr. M. Syukran G Syabena'],
                [3,4,'dr. Tuti Irma Rahayu'],
                [3,4,'dr. Sri Yuliana Bakar'],
                [3,4,'dr. Reza Nurdesni'],
                [3,4,'dr. Apri Yola'],
                [3,4,'dr. Dwiyana Roselin'],

                // ================= Promotion, Publication & Documentation =================
                [3,5,'dr. Harben Fernando, Sp.JP'],
                [3,5,'dr. Muhammad Fakhri, Sp.JP'],
                [3,5,'dr. Viftrya Rosady'],
                [3,5,'dr. Lona Azyenela'],
                [3,5,'dr. Desi Paramitha Manelly'],
                [3,5,'dr. Suci Wijayanti'],
                [3,5,'dr. Muhammad Marzain'],

                // ================= IT & Multimedia =================
                [3,6,'dr. Yose Ramda Ilhami, Sp.JP(K)'],
                [3,6,'dr. Rully Perdana'],
                [3,6,'dr. Bram Sesario Rendi'],
                [3,6,'dr. Agung F Zulfi'],
                [3,6,'dr. Yola Avisha'],
                [3,6,'dr. Aishah Salimar Putri'],
                [3,6,'dr. M. Farhan'],

                // ================= Logistic, Exhibition & Consumption =================
                [3,7,'dr. Tia Febrianti, Sp.JP'],
                [3,7,'dr. Hamzah Muhammad Zein, Sp.JP'],
                [3,7,'dr. Rifan Ahadi Rizki'],
                [3,7,'dr. Budiman Ade Satria'],
                [3,7,'dr. Krisna Dwi Saputra'],
                [3,7,'dr. Alvin Zulmaeta'],
                [3,7,'dr. Najmi Ilal Hayati'],
            ];

            foreach ($members as $i => $m) {
                DB::table('board_members')->insert([
                    'board_group_id' => $m[0],
                    'board_sub_section_id' => $m[1],
                    'name' => $m[2],
                    'position' => $m[3] ?? null,
                    'order' => $i + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

        });
    }
}
