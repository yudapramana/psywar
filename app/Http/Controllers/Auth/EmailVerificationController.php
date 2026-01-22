<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailOtpMail;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailVerificationController extends Controller
{
    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email'],
        ], [
            'email.unique' => 'This email is already registered.',
        ])->validate();

        $email = $request->email;

        // Generate 6-digit OTP
        $otp = (string) random_int(100000, 999999);

        // Save or update OTP record
        EmailVerification::updateOrCreate(
            ['email' => $email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5),
                'verified' => false,
            ]
        );

        // Send email
        Mail::to($email)->send(
            new EmailOtpMail($email, $otp)
        );

        return response()->json([
            'message' => 'OTP has been sent to your email address.',
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
        ])->validate();

        $record = EmailVerification::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (! $record) {
            return response()->json([
                'message' => 'Invalid or expired OTP code.',
            ], 422);
        }

        $record->update([
            'verified' => true,
        ]);

        return response()->json([
            'message' => 'Email successfully verified.',
        ]);
    }
}
