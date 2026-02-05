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
        /*
        |=====================================================
        | EVENT DAYS
        |=====================================================
        */
        $day1 = EventDay::where('date', '2026-06-05')->first(); // Workshop
        $day2 = EventDay::where('date', '2026-06-06')->first(); // Symposium
        $day3 = EventDay::where('date', '2026-06-07')->first(); // Symposium + Jeopardy

        /*
        |=====================================================
        | ROOMS
        |=====================================================
        */
        $ballroom     = Room::where('name', 'BallRoom')->first();
        $mulia9       = Room::where('name', 'Ruang Mulia 09')->first();
        $mulia10      = Room::where('name', 'Ruang Mulia 10')->first();
        $jeopardyRoom = Room::where('name', 'Ruang Mulia 02')->first();

        /*
        |=====================================================
        | DAY 1 – WORKSHOP (FIXED SESSION & ROOM)
        |=====================================================
        */

        // WS-01 → PAGI → RUANG MULIA 9
        $ws01 = Activity::where('code', 'WS-01')->first();
        Session::create([
            'activity_id'  => $ws01->id,
            'event_day_id' => $day1->id,
            'room_id'      => $mulia9->id,
            'start_time'   => '07:30',
            'end_time'     => '12:30',
        ]);

        // WS-02 → PAGI → RUANG MULIA 10
        $ws02 = Activity::where('code', 'WS-02')->first();
        Session::create([
            'activity_id'  => $ws02->id,
            'event_day_id' => $day1->id,
            'room_id'      => $mulia10->id,
            'start_time'   => '07:30',
            'end_time'     => '12:30',
        ]);

        // WS-03 → SORE → RUANG MULIA 9
        $ws03 = Activity::where('code', 'WS-03')->first();
        Session::create([
            'activity_id'  => $ws03->id,
            'event_day_id' => $day1->id,
            'room_id'      => $mulia9->id,
            'start_time'   => '13:30',
            'end_time'     => '18:00',
        ]);

        // WS-04 → SORE → RUANG MULIA 10
        $ws04 = Activity::where('code', 'WS-04')->first();
        Session::create([
            'activity_id'  => $ws04->id,
            'event_day_id' => $day1->id,
            'room_id'      => $mulia10->id,
            'start_time'   => '13:30',
            'end_time'     => '18:00',
        ]);

        /*
        |=====================================================
        | DAY 2 – SYMPOSIUM (BALLROOM)
        |=====================================================
        */
        $symposiumDay2 = Activity::where('category', 'symposium')
            ->whereIn('code', ['SYM-01','SYM-02','SYM-03','SYM-04','SYM-05','SYM-06'])
            ->get();

        $symposiumSlotsDay2 = [
            ['09:10', '10:05'],
            ['10:05', '11:00'],
            ['11:00', '12:00'],
            ['13:05', '14:00'],
            ['14:00', '14:55'],
            ['14:55', '15:50'],
        ];

        foreach ($symposiumDay2 as $index => $activity) {
            Session::create([
                'activity_id'  => $activity->id,
                'event_day_id' => $day2->id,
                'room_id'      => $ballroom->id,
                'start_time'   => $symposiumSlotsDay2[$index][0],
                'end_time'     => $symposiumSlotsDay2[$index][1],
            ]);
        }

        /*
        |=====================================================
        | DAY 3 – SYMPOSIUM (BALLROOM)
        |=====================================================
        */
        $symposiumDay3 = Activity::where('category', 'symposium')
            ->whereIn('code', ['SYM-07','SYM-08','SYM-09','SYM-10','SYM-11','SYM-12'])
            ->get();

        $symposiumSlotsDay3 = [
            ['08:00', '08:55'],
            ['09:05', '10:00'],
            ['10:00', '10:55'],
            ['11:00', '11:55'],
            ['13:05', '14:00'],
            ['14:00', '14:55'],
        ];

        foreach ($symposiumDay3 as $index => $activity) {
            Session::create([
                'activity_id'  => $activity->id,
                'event_day_id' => $day3->id,
                'room_id'      => $ballroom->id,
                'start_time'   => $symposiumSlotsDay3[$index][0],
                'end_time'     => $symposiumSlotsDay3[$index][1],
            ]);
        }

        /*
        |=====================================================
        | DAY 3 – JEOPARDY FINAL
        |=====================================================
        */
        $jeopardy = Activity::where('category', 'jeopardy')->first();

        Session::create([
            'activity_id'  => $jeopardy->id,
            'event_day_id' => $day3->id,
            'room_id'      => $jeopardyRoom->id,
            'start_time'   => '15:00',
            'end_time'     => '16:00',
        ]);
    }
}
