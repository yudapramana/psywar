<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\RegistrationItem;

class MyScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $participant = $user->participant;

        if (!$participant) {
            abort(403, 'Participant not found.');
        }

        // Ambil registration aktif (paid atau masih proses)
        $registration = Registration::where('participant_id', $participant->id) 
            ->where('payment_step', 'paid')
            ->where('status', 'paid')
            ->latest()
            ->first();

        $workshops = collect();
        $symposiums = collect();

        if ($registration) {
            $items = RegistrationItem::with([
                    'activity.sessions.eventDay',
                    'activity.sessions.room',
                ])
                ->where('registration_id', $registration->id)
                ->get();

            $workshops = $items->where('activity_type', 'workshop');
            $symposiums = $items->where('activity_type', 'symposium');
        }

        // return $symposiums;

        return view('dashboard.my-schedule', compact(
            'participant',
            'registration',
            'workshops',
            'symposiums'
        ));
    }
}
