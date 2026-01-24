<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class PaymentController extends Controller
{
    
    /**
     * STEP 1 — Choose / Change Bank
     */
    public function chooseBank()
    {
        $registration = Registration::where('id', session('registration_id'))
            ->firstOrFail();

        // ❌ Tidak boleh ganti bank kalau sudah upload bukti / diverifikasi
        if (in_array($registration->payment_step, ['waiting_verification', 'paid'])) {
            abort(403, 'You cannot change bank after submitting payment proof.');
        }

        $banks = DB::table('banks')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('dashboard.payment.choose-bank', compact('registration', 'banks'));
    }


    /**
     * STEP 1 SUBMIT
     */
    public function storeBank(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|exists:banks,id'
        ]);

        $registrationId = session('registration_id');

        DB::table('registrations')
            ->where('id', $registrationId)
            ->update([
                'bank_id' => $request->bank_id,
                'unique_code' => rand(100, 999),
                'payment_step' => 'waiting_transfer',
                'updated_at' => now(),
            ]);

        return redirect()->route('dashboard.payment.transfer');
    }

    /**
     * STEP 2 — Waiting Transfer
     */
    public function waitingTransfer()
    {
        $registration = Registration::join('banks', 'banks.id', '=', 'registrations.bank_id')
            ->where('registrations.id', session('registration_id'))
            ->select(
                'registrations.*',
                'banks.name as bank_name',
                'banks.account_number',
                'banks.account_name'
            )
            ->firstOrFail();

        abort_if($registration->payment_step !== 'waiting_transfer', 403);

        return view('dashboard.payment.waiting-transfer', compact('registration'));
    }

    public function uploadProof()
    {
        $registration = Registration::with('payment', 'bank')
            ->where('id', session('registration_id'))
            ->firstOrFail();


        // 🚫 Sudah selesai → redirect ke completed
        if ($registration->payment_step === 'paid') {
            return redirect()
                ->route('dashboard.payment.completed', $registration->id);
        }

        // 🚫 Step tidak valid
        abort_if(
            !in_array($registration->payment_step, [
                'waiting_transfer',
                'waiting_verification',
            ]),
            403
        );

        return view('dashboard.payment.upload-proof', compact('registration'));
    }



    public function storeProof(Request $request)
    {
        $request->validate([
            'proof_file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $registrationId = session('registration_id');

        $registration = Registration::with('payment')
            ->where('id', $registrationId)
            ->firstOrFail();

        abort_if($registration->payment_step !== 'waiting_transfer'
            && $registration->payment_step !== 'waiting_verification', 403);

        // ==============================
        // UPLOAD FILE
        // ==============================
        $file = $request->file('proof_file');

        $filename = 'payment_' . $registrationId . '_' . Str::random(8) . '.' . $file->extension();

        $path = $file->storeAs(
            'payments/proofs',
            $filename,
            'public'
        );

        // ==============================
        // UPSERT PAYMENT
        // ==============================
        DB::table('payments')->updateOrInsert(
            ['registration_id' => $registrationId],
            [
                'payment_method' => 'bank_transfer',
                'amount'         => $registration->total_amount + $registration->unique_code,
                'proof_file'     => $path,
                'status'         => 'pending',
                'paid_at'        => now(),
                'updated_at'     => now(),
                'created_at'     => now(),
            ]
        );

        // ==============================
        // UPDATE REGISTRATION
        // ==============================
        $registration->update([
            'payment_step' => 'waiting_verification',
        ]);

        return redirect()
            ->route('dashboard.payment.upload-proof', $registrationId)
            ->with('success', 'Payment proof uploaded. Waiting for verification.');
    }

    

    public function completed()
    {
        $participant = Auth::user()->participant;

        abort_if(!$participant, 403);

        // Ambil registrasi aktif TERBARU
        $registration = Registration::where('participant_id', $participant->id)
            ->latest()
            ->firstOrFail();

        // 🔒 Hanya boleh jika sudah PAID
        abort_if($registration->payment_step !== 'paid', 403);

        return view('dashboard.payment.completed', compact('registration'));
    }




}
