<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::first();

        // Safety check
        if (!$event) {
            $this->command->warn('RoomSeeder dilewati: belum ada data event.');
            return;
        }

        $eventId = $event->id;
        $now = Carbon::now();

        Room::insert([
            // =====================
            // SYMPOSIUM
            // =====================
            [
                'event_id'   => $eventId,
                'name'       => 'BallRoom',
                'category'   => 'symposium',
                'capacity'   => 500,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // =====================
            // WORKSHOP
            // =====================
            [
                'event_id'   => $eventId,
                'name'       => 'Ruang Mulia 09',
                'category'   => 'workshop',
                'capacity'   => 50,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'event_id'   => $eventId,
                'name'       => 'Ruang Mulia 10',
                'category'   => 'workshop',
                'capacity'   => 50,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // =====================
            // JEOPARDY
            // =====================
            [
                'event_id'   => $eventId,
                'name'       => 'Ruang Mulia 02',
                'category'   => 'jeopardy',
                'capacity'   => 30,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
