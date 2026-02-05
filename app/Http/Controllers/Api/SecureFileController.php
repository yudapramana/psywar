<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class SecureFileController extends Controller
{
    public function streamPaymentProof(Payment $payment)
    {
        abort_if(!$payment->proof_file, 404);

        $disk = Storage::disk('private');

        abort_if(!$disk->exists($payment->proof_file), 404);

        return response()->stream(
            fn () => print $disk->get($payment->proof_file),
            200,
            [
                'Content-Type'        => $disk->mimeType($payment->proof_file),
                'Content-Disposition'=> 'inline',
                'Cache-Control'       => 'no-store, no-cache',
                'Pragma'              => 'no-cache',
            ]
        );
    }

    public function getSignedPaymentProof(Payment $payment)
    {
        abort_if($payment->status !== 'pending', 403);

        abort_if(!$payment->proof_file, 404);

        return response()->json([
            'url' => URL::temporarySignedRoute(
                'secure.payment.proof.stream',
                now()->addSeconds(5),
                ['payment' => $payment->id]
            ),
            'expires_at' => now()->addSeconds(5),
        ]);
    }
}
