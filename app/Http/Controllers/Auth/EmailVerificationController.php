<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailOtpMail;
use App\Models\EmailVerification;
use App\Models\OtpLog;
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
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email'],
        ], [
            'email.unique' => 'This email is already registered.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first('email'),
            ], 422);
        }

        $email = $request->email;

        // Check cooldown
        $existing = EmailVerification::where('email', $email)->first();

        if ($existing && $existing->updated_at->diffInSeconds(now()) < 30) {
            return response()->json([
                'message' => 'Please wait before requesting another OTP.',
            ], 429);
        }

        $otp = (string) random_int(100000, 999999);

        EmailVerification::updateOrCreate(
            ['email' => $email],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5),
                'verified' => false,
            ]
        );

        Mail::to($email)->send(
            new EmailOtpMail($email, $otp)
        );

        // LOG ATTEMPT
        OtpLog::create([
            'email' => $email,
            'ip_address' => $request->ip(),
            'action' => 'send',
        ]);

        return response()->json([
            'message' => 'OTP has been sent to your email address.',
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'otp' => ['required', 'regex:/^[0-9]{6}$/'],
        ], [
            'otp.required' => 'OTP code is required.',
            'otp.regex' => 'OTP must be exactly 6 digits.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid OTP format.',
                'errors' => $validator->errors(),
            ], 422);
        }

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
            'verified_at' => now(),
        ]);

        return response()->json([
            'message' => 'Email successfully verified.',
        ]);
    }

}
