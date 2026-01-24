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

        // 🔒 CEK REGISTRASI AKTIF (BELUM SELESAI BAYAR)
        $activeRegistration = Registration::where('participant_id', $participant->id)
            ->whereIn('payment_step', [
                'choose_bank',
                'waiting_transfer',
                'waiting_verification',
            ])
            ->latest()
            ->first();

        // ❌ JIKA ADA → LANGSUNG ARAHKAN KE PAYMENT FLOW
        if ($activeRegistration) {

            session(['registration_id' => $activeRegistration->id]);

            $route = match ($activeRegistration->payment_step) {
                'choose_bank' => route('dashboard.payment.choose-bank'),
                'waiting_transfer' => route('dashboard.payment.transfer', $activeRegistration->id),
                'waiting_verification' => route('dashboard.my-package'),
                'paid' => route('dashboard.my-package'),
                default => route('dashboard.my-package'),
            };

            return redirect($route)
                ->with('warning', 'You already have an active registration. Please complete your payment.');
        }


        // ✅ AMBIL DATA WORKSHOP (BARU BOLEH BELI)
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

        $request->validate([
            'package_type' => 'required|in:1,2,3',

            // PACKAGE 2 → SINGLE WORKSHOP
            'workshops'   => 'exclude_unless:package_type,2|required|array|min:1',
            'workshops.*' => 'exclude_unless:package_type,2|required|exists:activities,id',

            // PACKAGE 3 → BUNDLING
            'workshop_bundling' => 'exclude_unless:package_type,3|required|string',
        ]);

        $event = Event::where('is_active', true)->firstOrFail();

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


        DB::transaction(function () use ($request, $participant) {

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
            | ACTIVE EVENT + BIRD TYPE
            |----------------------------------------------------------
            */
            $event = Event::where('is_active', true)->firstOrFail();

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
            | SYMPOSIUM → AUTO INCLUDE
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
            $workshopIds = [];

            if ((int) $request->package_type === 2) {
                $workshopIds = $request->workshops;
            }

            if ((int) $request->package_type === 3) {
                $workshopIds = explode(',', $request->workshop_bundling);

                if (count($workshopIds) !== 2) {
                    throw new \Exception('Invalid workshop bundling.');
                }
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

        // 🔥 REDIRECT KE PAYMENT FLOW BARU
        return redirect()->route('dashboard.payment.choose-bank');
    }


}
