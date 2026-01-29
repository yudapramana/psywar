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
        $participant = auth()->user()->participant;

        if (!$participant) {
            abort(403, 'Participant profile not found.');
        }

        // 🔒 Ambil REGISTRASI AKTIF milik participant
        $registration = Registration::where('participant_id', $participant->id)
            ->whereIn('payment_step', [
                'choose_bank',
                'waiting_transfer',
            ])
            ->latest()
            ->first();

        // 🔁 JIKA TIDAK ADA REGISTRASI → KEMBALI KE BUY PACKAGE
        if (!$registration) {
            return redirect()
                ->route('dashboard.buy-package')
                ->with('info', 'Please choose a package before selecting a bank.');
        }

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
            'bank_id' => ['required', 'exists:banks,id'],
        ]);

        $participant = auth()->user()->participant;

        if (!$participant) {
            abort(403, 'Participant profile not found.');
        }

        // 🔒 Ambil REGISTRASI AKTIF milik participant
        $registration = Registration::where('participant_id', $participant->id)
            ->whereIn('payment_step', ['choose_bank', 'waiting_transfer'])
            ->latest()
            ->firstOrFail();

        // ❌ Tidak boleh ubah bank jika sudah upload / selesai
        abort_if(
            in_array($registration->payment_step, ['waiting_verification', 'paid']),
            403,
            'You cannot change bank after submitting payment proof.'
        );

        // 🔐 Generate unique code SEKALI SAJA (IMMUTABLE)
        if (!$registration->unique_code) {
            do {
                $uniqueCode = random_int(100, 999);
            } while (
                Registration::where('unique_code', $uniqueCode)
                    ->whereDate('created_at', now()->toDateString())
                    ->exists()
            );

            $registration->unique_code = $uniqueCode;
        }

        // ✅ Update bank & step (NOMINAL TETAP)
        $registration->update([
            'bank_id'      => $request->bank_id,
            'payment_step' => 'waiting_transfer',
        ]);

        return redirect()
            ->route('dashboard.payment.transfer')
            ->with('info', 'Bank updated successfully. Please complete the payment.');
    }



    /**
     * STEP 2 — Waiting Transfer
     */
    public function waitingTransfer()
    {
        $participant = auth()->user()->participant;

        if (!$participant) {
            abort(403, 'Participant profile not found.');
        }

        // 🔒 Ambil REGISTRASI AKTIF milik participant
        $registration = Registration::where('participant_id', $participant->id)
            ->where('payment_step', 'waiting_transfer')
            ->latest()
            ->first();

        // 🔁 JIKA TIDAK ADA REGISTRASI → KEMBALI KE BUY PACKAGE
        if (!$registration) {
            return redirect()
                ->route('dashboard.buy-package')
                ->with('info', 'Please choose a package before selecting a bank.');
        }

        return view('dashboard.payment.waiting-transfer', compact('registration'));
    }


    public function uploadProof()
    {
        $participant = auth()->user()->participant;

        if (!$participant) {
            abort(403, 'Participant profile not found.');
        }

        // 🔒 Ambil registrasi aktif milik participant
        $registration = Registration::with(['payment', 'bank'])
            ->where('participant_id', $participant->id)
            ->latest()
            ->first();

        // 🔁 JIKA TIDAK ADA REGISTRASI → KEMBALI KE BUY PACKAGE
        if (!$registration) {
            return redirect()
                ->route('dashboard.buy-package')
                ->with('info', 'Please choose a package before selecting a bank.');
        }

        // 🚫 Sudah selesai bayar → arahkan ke completed
        if ($registration->payment_step === 'paid') {
            return redirect()
                ->route('dashboard.payment.completed', $registration->id);
        }

        // 🚫 Step tidak valid untuk upload bukti
        abort_if(
            !in_array($registration->payment_step, [
                'waiting_transfer',
                'waiting_verification',
            ]),
            403,
            'You are not allowed to upload payment proof at this stage.'
        );

        // 🚫 Bank belum dipilih (edge case safety)
        abort_if(
            !$registration->bank,
            403,
            'Please choose a bank before uploading payment proof.'
        );

        return view('dashboard.payment.upload-proof', compact('registration'));
    }

    public function storeProof(Request $request)
    {
        // ======================================================
        // 1️⃣ VALIDATION (STRONG IMAGE VALIDATION)
        // ======================================================
        $request->validate([
            'proof_file' => [
                'required',
                'file',
                'max:2048', // 2MB
                'mimetypes:image/jpeg,image/png',
                'dimensions:min_width=100,min_height=100',
            ],
        ]);

        // ======================================================
        // 2️⃣ AUTH & PARTICIPANT CHECK
        // ======================================================
        $participant = auth()->user()->participant;

        abort_if(!$participant, 403, 'Participant profile not found.');

        // ======================================================
        // 3️⃣ ACTIVE REGISTRATION CHECK
        // ======================================================
        $registration = Registration::with('payment')
            ->where('participant_id', $participant->id)
            ->latest()
            ->firstOrFail();

        // ======================================================
        // 4️⃣ PAYMENT STEP GUARD
        // ======================================================
        abort_if(
            ! in_array($registration->payment_step, [
                'waiting_transfer',
                'waiting_verification',
            ]),
            403,
            'You are not allowed to upload payment proof at this stage.'
        );

        // ======================================================
        // 5️⃣ REAL IMAGE VERIFICATION (ANTI SPOOFING)
        // ======================================================
        $file = $request->file('proof_file');

        if (! @getimagesize($file->getPathname())) {
            return back()->withErrors([
                'proof_file' => 'Invalid image file. Please upload a valid JPG or PNG image.',
            ]);
        }

        // ======================================================
        // 6️⃣ SECURE FILE NAME
        // ======================================================
        $filename = 'payment_' .
            $registration->id . '_' .
            now()->format('YmdHis') . '_' .
            Str::random(8) . '.' .
            $file->extension();

        // ======================================================
        // 7️⃣ STORE FILE (PUBLIC OK, PRIVATE IS BETTER IF AVAILABLE)
        // ======================================================
        $path = $file->storeAs(
            'payments/proofs',
            $filename,
            'public' // change to 'private' if you use private disk
        );

        // ======================================================
        // 8️⃣ UPSERT PAYMENT RECORD (SAFE & IDEMPOTENT)
        // ======================================================
        DB::table('payments')->updateOrInsert(
            [
                'registration_id' => $registration->id,
            ],
            [
                'payment_method' => 'bank_transfer',
                'amount'         => $registration->total_amount + ($registration->unique_code ?? 0),
                'proof_file'     => $path,
                'status'         => 'pending',
                'paid_at'        => now(),
                'updated_at'     => now(),
                'created_at'     => now(),
            ]
        );

        // ======================================================
        // 9️⃣ UPDATE REGISTRATION STEP (IDEMPOTENT)
        // ======================================================
        if ($registration->payment_step !== 'waiting_verification') {
            $registration->update([
                'payment_step' => 'waiting_verification',
            ]);
        }

        // ======================================================
        // 🔟 SUCCESS RESPONSE
        // ======================================================
        return redirect()
            ->route('dashboard.payment.upload-proof')
            ->with('success', 'Payment proof uploaded successfully. Waiting for verification.');
    }



    

    public function completed()
    {
        $participant = auth()->user()->participant;

        if (!$participant) {
            abort(403, 'Participant profile not found.');
        }

        // 🔒 Ambil registrasi terakhir milik participant
        $registration = Registration::with(['bank', 'payment'])
            ->where('participant_id', $participant->id)
            ->latest()
            ->firstOrFail();

        // 🔒 Hanya boleh jika sudah PAID
        abort_if(
            $registration->payment_step !== 'paid'
            || $registration->status !== 'paid',
            403,
            'Payment has not been completed.'
        );

        return view('dashboard.payment.completed', compact('registration'));
    }





}
