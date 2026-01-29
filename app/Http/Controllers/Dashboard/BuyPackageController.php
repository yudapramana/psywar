<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Event;
use App\Models\PricingItem;
use App\Models\Registration;
use App\Models\RegistrationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class BuyPackageController extends Controller
{
    /**
     * Show Buy Package Page
     */
    public function create()
    {
        $participant = auth()->user()->participant;

        if (!$participant) {
            abort(403, 'Participant profile not found.');
        }

        $event = Event::where('is_active', true)->firstOrFail();

        // 🔒 CEK APAKAH SUDAH ADA REGISTRASI (APAPUN STATUSNYA)
        $registration = Registration::where('participant_id', $participant->id)
            ->where('event_id', $event->id)
            ->latest()
            ->first();

        if ($registration) {

            session(['registration_id' => $registration->id]);

            $route = match ($registration->payment_step) {
                'choose_bank' => route('dashboard.payment.choose-bank'),
                'waiting_transfer' => route('dashboard.payment.transfer', $registration->id),
                'waiting_verification' => route('dashboard.my-package'),
                'paid' => route('dashboard.my-package'),
                default => route('dashboard.my-package'),
            };

            return redirect($route)->with(
                'info',
                'You already have a registration for this event.'
            );
        }

        // ✅ BARU BOLEH BUY PACKAGE JIKA BELUM ADA REGISTRASI SAMA SEKALI
        $workshops = DB::table('activities')
            ->join('sessions', 'sessions.activity_id', '=', 'activities.id')
            ->select(
                'activities.id',
                'activities.code',
                'activities.title',
                'sessions.start_time',
                'sessions.end_time'
            )
            ->where('activities.category', 'workshop')
            ->where('activities.is_paid', true)
            ->orderBy('sessions.start_time')
            ->get();

        return view('dashboard.package.buy', compact(
            'participant',
            'workshops'
        ));
    }




    public function getPrice(Request $request)
    {
        $request->validate([
            'package_type' => 'required|in:1,2,3',
        ]);

        $participant = auth()->user()->participant;

        // =========================
        // ACTIVE EVENT
        // =========================
        $event = Event::where('is_active', true)
            ->firstOrFail();

        // =========================
        // EARLY / LATE BIRD
        // =========================
        $birdType = $event->early_bird_end_date &&
                    now()->lte($event->early_bird_end_date)
            ? 'early'
            : 'late';

        // =========================
        // WORKSHOP COUNT
        // =========================
        $workshopCount = match ((int) $request->package_type) {
            1 => 0,
            2 => 1,
            3 => 2,
        };

        // =========================
        // PRICING ITEM
        // =========================
        $pricing = DB::table('pricing_items')
            ->where('participant_category_id', $participant->participant_category_id)
            ->where('bird_type', $birdType)
            ->where('workshop_count', $workshopCount)
            ->first();

        if (!$pricing) {
            return response()->json([
                'message' => 'Pricing not available'
            ], 404);
        }

        return response()->json([
            'price'     => (int) $pricing->price,
            'bird_type' => $birdType,
        ]);
    }



    /**
     * Store Buy Package
     */
    public function store(Request $request)
    {
        $participant = Auth::user()->participant;

        /*
        |----------------------------------------------------------
        | VALIDATION
        |----------------------------------------------------------
        */
        $request->validate([
            'package_type' => 'required|in:1,2,3',

            // package 2 & 3 pakai workshops[]
            'workshops' => 'exclude_unless:package_type,2,3|array',
            'workshops.*' => 'exclude_unless:package_type,2,3|exists:activities,id',
        ]);

        /*
        |----------------------------------------------------------
        | ACTIVE EVENT
        |----------------------------------------------------------
        */
        $event = Event::where('is_active', true)->firstOrFail();

        /*
        |----------------------------------------------------------
        | PREVENT DUPLICATE ACTIVE REGISTRATION
        |----------------------------------------------------------
        */
        $existingRegistration = Registration::where('event_id', $event->id)
            ->where('participant_id', $participant->id)
            ->whereIn('payment_step', [
                'choose_bank',
                'waiting_transfer',
                'waiting_verification'
            ])
            ->first();

        if ($existingRegistration) {
            return redirect()
                ->route('dashboard.payment.choose-bank')
                ->with('warning', 'You already have an active registration. Please complete your payment.');
        }

        /*
        |----------------------------------------------------------
        | TRANSACTION
        |----------------------------------------------------------
        */
        DB::transaction(function () use ($request, $participant, $event) {

            /*
            |----------------------------------------------------------
            | WORKSHOP COUNT
            |----------------------------------------------------------
            */
            $workshopCount = match ((int) $request->package_type) {
                1 => 0,
                2 => 1,
                3 => 2,
            };

            /*
            |----------------------------------------------------------
            | BIRD TYPE
            |----------------------------------------------------------
            */
            $birdType = $event->early_bird_end_date &&
                        now()->lte($event->early_bird_end_date)
                ? 'early'
                : 'late';

            /*
            |----------------------------------------------------------
            | PRICING
            |----------------------------------------------------------
            */
            $pricingItem = PricingItem::where('participant_category_id', $participant->participant_category_id)
                ->where('bird_type', $birdType)
                ->where('workshop_count', $workshopCount)
                ->firstOrFail();

            /*
            |----------------------------------------------------------
            | CREATE REGISTRATION
            |----------------------------------------------------------
            */
            $registrationId = Registration::insertGetId([
                'event_id'        => $event->id,
                'participant_id'  => $participant->id,
                'pricing_item_id' => $pricingItem->id,
                'status'          => 'pending',
                'payment_step'    => 'choose_bank',
                'total_amount'    => $pricingItem->price,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            /*
            |----------------------------------------------------------
            | SYMPOSIUM (AUTO INCLUDE)
            |----------------------------------------------------------
            */
            $symposiumIds = Activity::where('category', 'symposium')
                ->where('is_paid', true)
                ->pluck('id');

            foreach ($symposiumIds as $symId) {
                RegistrationItem::create([
                    'registration_id' => $registrationId,
                    'activity_id'     => $symId,
                    'activity_type'   => 'symposium',
                ]);
            }

            /*
            |----------------------------------------------------------
            | WORKSHOPS
            |----------------------------------------------------------
            */
            $workshopIds = $request->workshops ?? [];

            if ($workshopCount !== count($workshopIds)) {
                throw new \Exception('Invalid workshop selection count.');
            }

            foreach ($workshopIds as $workshopId) {

                $valid = Activity::where('id', $workshopId)
                    ->where('category', 'workshop')
                    ->where('is_paid', true)
                    ->exists();

                if (!$valid) {
                    throw new \Exception('Invalid workshop selected.');
                }

                RegistrationItem::create([
                    'registration_id' => $registrationId,
                    'activity_id'     => $workshopId,
                    'activity_type'   => 'workshop',
                ]);
            }

            session(['registration_id' => $registrationId]);
        });

        /*
        |----------------------------------------------------------
        | REDIRECT TO PAYMENT
        |----------------------------------------------------------
        */
        return redirect()
            ->route('dashboard.payment.choose-bank')
            ->with('success', 'Package successfully selected. Please choose your bank.');

    }



}
