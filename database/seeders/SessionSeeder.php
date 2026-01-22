<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventDay;
use App\Models\Activity;
use App\Models\Session;
use App\Models\Room;

class SessionSeeder extends Seeder
{
    public function run()
    {
        $day1 = EventDay::where('date', '2026-06-05')->first(); // Workshop
        $day2 = EventDay::where('date', '2026-06-06')->first(); // Symposium
        $day3 = EventDay::where('date', '2026-06-07')->first(); // Symposium + Jeopardy

        $ballroom = Room::where('name', 'BallRoom')->first();
        $mulia9 = Room::where('name', 'Ruang Mulia 9')->first();
        $mulia10 = Room::where('name', 'Ruang Mulia 10')->first();
        $jeopardyRoom = Room::where('name', 'Ruang Mulia 2')->first();

        /**
         * =====================================================
         * DAY 1 – WORKSHOP (PARALLEL ROOMS)
         * =====================================================
         */
        $workshops = Activity::where('category', 'workshop')->get();

        $workshopSlots = [
            ['07:30', '12:30'],
            ['13:30', '18:00'],
        ];

        foreach ($workshops as $index => $workshop) {
            $room = $index % 2 === 0 ? $mulia9 : $mulia10;

            foreach ($workshopSlots as $slot) {
                Session::create([
                    'activity_id' => $workshop->id,
                    'event_day_id' => $day1->id,
                    'room_id' => $room->id,
                    'start_time' => $slot[0],
                    'end_time' => $slot[1],
                ]);
            }
        }

        /**
         * =====================================================
         * DAY 2 – SYMPOSIUM (BALLROOM)
         * =====================================================
         */
        $symposiumDay2 = Activity::where('category', 'symposium')
            ->whereIn('code', ['SYM-01','SYM-02','SYM-03','SYM-04','SYM-05','SYM-06'])
            ->get();

        $symposiumSlotsDay2 = [
            ['09:10', '10:05'], // SYM-01
            ['10:05', '11:00'], // SYM-02
            ['11:00', '12:00'], // SYM-03
            ['13:05', '14:00'], // SYM-04
            ['14:00', '14:55'], // SYM-05
            ['14:55', '15:50'], // SYM-06
        ];

        foreach ($symposiumDay2 as $index => $activity) {
            Session::create([
                'activity_id' => $activity->id,
                'event_day_id' => $day2->id,
                'room_id' => $ballroom->id,
                'start_time' => $symposiumSlotsDay2[$index][0],
                'end_time' => $symposiumSlotsDay2[$index][1],
            ]);
        }

        /**
         * =====================================================
         * DAY 3 – SYMPOSIUM + JEOPARDY
         * =====================================================
         */
        $symposiumDay3 = Activity::where('category', 'symposium')
            ->whereIn('code', ['SYM-07','SYM-08','SYM-09','SYM-10','SYM-11','SYM-12'])
            ->get();

        $symposiumSlotsDay3 = [
            ['08:00', '08:55'], // SYM-07
            ['09:05', '10:00'], // SYM-08
            ['10:00', '10:55'], // SYM-09
            ['11:00', '11:55'], // SYM-10
            ['13:05', '14:00'], // SYM-11
            ['14:00', '14:55'], // SYM-12
        ];

        foreach ($symposiumDay3 as $index => $activity) {
            Session::create([
                'activity_id' => $activity->id,
                'event_day_id' => $day3->id,
                'room_id' => $ballroom->id,
                'start_time' => $symposiumSlotsDay3[$index][0],
                'end_time' => $symposiumSlotsDay3[$index][1],
            ]);
        }

        /**
         * =====================================================
         * JEOPARDY – FINAL
         * =====================================================
         */
        $jeopardy = Activity::where('category', 'jeopardy')->first();

        Session::create([
            'activity_id' => $jeopardy->id,
            'event_day_id' => $day3->id,
            'room_id' => $jeopardyRoom->id,
            'start_time' => '15:00',
            'end_time' => '16:00',
        ]);
    }
}
