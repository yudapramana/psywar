<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParticipantCheckController extends Controller
{
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => ['nullable', 'digits:16'],
            'mobile_phone' => ['nullable', 'regex:/^\+?[0-9]{9,15}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid input format.',
            ], 422);
        }

        $existsNik = false;
        $existsMobile = false;

        if ($request->filled('nik')) {
            $existsNik = Participant::where('nik', $request->nik)
                ->whereNull('deleted_at')
                ->exists();
        }

        if ($request->filled('mobile_phone')) {
            $existsMobile = Participant::where('mobile_phone', $request->mobile_phone)
                ->whereNull('deleted_at')
                ->exists();
        }

        return response()->json([
            'nik_exists' => $existsNik,
            'mobile_phone_exists' => $existsMobile,
        ]);
    }
}
