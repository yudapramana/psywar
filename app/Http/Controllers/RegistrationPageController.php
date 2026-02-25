<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\ParticipantCategory;
use App\Models\PricingItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RegistrationPageController extends Controller
{
    // public function index()
    // {
    //     $event = Event::where('is_active', true)->latest()->first();

    //     if (!$event) {
    //         abort(404, 'Active event not found');
    //     }

    //     $today = Carbon::today();
    //     $isEarlyBird = $event->early_bird_end_date
    //         ? $today->lte($event->early_bird_end_date)
    //         : false;

    //     $birdType = $isEarlyBird ? 'early' : 'late';

    //     // Ambil semua kategori peserta
    //     $categories = ParticipantCategory::with([
    //         'pricingItems' => function ($query) use ($birdType) {
    //             $query->where('bird_type', $birdType);
    //         }
    //     ])->get();

    //     return view('pages.registration', [
    //         'event'        => $event,
    //         'categories'   => $categories,
    //         'birdType'     => $birdType,
    //         'isEarlyBird'  => $isEarlyBird,
    //     ]);
    // }

    public function index()
    {
        $event = Event::where('is_active', true)->latest()->first();
        abort_if(!$event, 404);

        $categories = \App\Models\ParticipantCategory::all();

        $pricing = \App\Models\PricingItem::with('participantCategory')
            ->get()
            ->groupBy(function ($item) {
                return $item->includes_symposium . '-' . $item->workshop_count;
            });

        return view('pages.registration', compact(
            'event',
            'pricing',
            'categories'
        ));
    }
}