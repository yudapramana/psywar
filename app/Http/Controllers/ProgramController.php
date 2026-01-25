<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventDay;
use App\Models\Room;
use App\Models\Session;
use Carbon\Carbon;

class ProgramController extends Controller
{
    public function fullProgram()
    {
        $event = Event::where('is_active', true)->firstOrFail();

        $days = EventDay::with([
            'sessions.activity',
            'sessions.room',
        ])
        ->where('event_id', $event->id)
        ->orderBy('date')
        ->get();

        $rooms = Room::orderBy('name')->get();

        // slot waktu (ISICAM style)
        $timeSlots = [
            '07:30','08:00','08:30','09:00','09:30',
            '10:00','10:30','11:00','11:30','12:00',
            '13:00','13:30','14:00','14:30','15:00',
            '15:30','16:00','16:30','17:00','18:00',
        ];

    
        return view('pages.program.full-program', compact(
            'event',
            'days',
            'rooms',
            'timeSlots'
        ));
    }

    public function meetingAtGlance()
    {
        $event = Event::where('is_active', true)->firstOrFail();

        $days = EventDay::with([
            'sessions.activity',
            'sessions.room',
        ])
        ->where('event_id', $event->id)
        ->orderBy('date')
        ->get();

        $rooms = Room::orderBy('name')->get();

        // slot waktu (ISICAM style)
        $timeSlots = [
            '07:30','08:00','08:30','09:00','09:30',
            '10:00','10:30','11:00','11:30','12:00',
            '13:00','13:30','14:00','14:30','15:00',
            '15:30','16:00','16:30','17:00','18:00',
        ];

        $data = compact(
            'event',
            'days',
            'rooms',
            'timeSlots'
        );

        // return $data;
    
        return view('pages.program.meeting-at-glance', compact(
            'event',
            'days',
            'rooms',
            'timeSlots'
        ));
    }

    public function resources()
    {

    
        return view('pages.program.program-resources');
    }
}
