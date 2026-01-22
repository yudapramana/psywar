<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run()
    {
        Room::insert([
            // Symposium
            ['name' => 'BallRoom', 'category' => 'symposium'],

            // Workshop
            ['name' => 'Ruang Mulia 9', 'category' => 'workshop'],
            ['name' => 'Ruang Mulia 10', 'category' => 'workshop'],

            // Jeopardy
            ['name' => 'Ruang Mulia 2', 'category' => 'jeopardy'],
        ]);
    }
}
