<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventDay;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::create([
            // =========================
            // BASIC EVENT INFO
            // =========================
            'name' => '11th Padang Symposium on Cardiovascular Diseases',
            'slug' => 'symcard-2026',
            'theme' => 'Cardiology 360°: Integrating Knowledge, Technology, and Practice',
            'description' => 'The 11th Padang Symposium on Cardiovascular Diseases (SYMCARD 2026)',

            // =========================
            // EVENT DATE
            // =========================
            'start_date' => '2026-06-05',
            'end_date' => '2026-06-07',
            'early_bird_end_date' => '2026-05-25',

            // =========================
            // SUBMISSION TIMELINE
            // =========================
            'submission_open_at' => Carbon::create(2026, 3, 1, 0, 0, 0),
            'submission_deadline_at' => Carbon::create(2026, 5, 20, 23, 59, 59),
            'notification_date' => Carbon::create(2026, 5, 25, 9, 0, 0),
            'submission_close_at' => Carbon::create(2026, 5, 20, 23, 59, 59),

            // =========================
            // SUBMISSION CONTROL
            // =========================
            'submission_is_active' => true,

            // =========================
            // LOCATION
            // =========================
            'location' => 'Padang',
            'venue' => 'ZHM Premiere Hotel',

            // =========================
            // STATUS
            // =========================
            'is_active' => true,
        ]);

        // =========================
        // EVENT DAYS
        // =========================
        EventDay::insert([
            [
                'event_id' => $event->id,
                'date' => '2026-06-05',
                'label' => 'Day 1',
            ],
            [
                'event_id' => $event->id,
                'date' => '2026-06-06',
                'label' => 'Day 2',
            ],
            [
                'event_id' => $event->id,
                'date' => '2026-06-07',
                'label' => 'Day 3',
            ],
        ]);
    }
}
