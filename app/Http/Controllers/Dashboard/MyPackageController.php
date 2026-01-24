<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;

class MyPackageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil participant (WAJIB ADA UNTUK HALAMAN INI)
        $participant = $user->participant;

        // return $user;

        // Jika user belum punya participant profile sama sekali
        if (!$participant) {
            abort(403, 'Participant profile not found.');
        }

        // Ambil registrasi TERBARU (boleh null)
        $registration = Registration::with([
                'participant.participantCategory',
                'pricingItem',
                'payment.verification'
            ])
            ->where('participant_id', $participant->id)
            ->latest()
            ->first();

        return view('dashboard.my-package', [
            'participant'  => $participant,
            'registration' => $registration
        ]);
    }
}
