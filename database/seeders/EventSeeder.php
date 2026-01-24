<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventDay;

class EventSeeder extends Seeder
{
    public function run(): void
    {

        $event = Event::create([
            'name' => '11th Padang Symposium on Cardiovascular Diseases',
            'theme' => 'Cardiology 360°: Integrating Knowledge, Technology, and Practice',
            'early_bird_end_date' => '2026-05-25',
            'start_date' => '2026-06-05',
            'end_date' => '2026-06-07',
            'location' => 'Padang',
            'venue' => 'ZHM Premiere Hotel',
            'is_active' => true,
        ]);

        EventDay::insert([
            ['event_id' => $event->id, 'date' => '2026-06-05', 'label' => 'Day 1'],
            ['event_id' => $event->id, 'date' => '2026-06-06', 'label' => 'Day 2'],
            ['event_id' => $event->id, 'date' => '2026-06-07', 'label' => 'Day 3'],
        ]);
    }
}
