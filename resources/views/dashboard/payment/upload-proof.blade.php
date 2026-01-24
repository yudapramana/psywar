@extends('dashboard._layouts.package')

@section('title', 'Upload Payment Proof')

@section('content')

    <style>
        .auth-card {
            position: relative;
        }

        .payment-box {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 14px;
            background: #fff;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            font-size: .9rem;
            margin-bottom: 6px;
        }

        .payment-row.total {
            font-weight: 700;
            font-size: 1rem;
            border-top: 1px dashed #dee2e6;
            padding-top: 8px;
            margin-top: 8px;
        }

        .bank-box {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 14px;
            background: #f8f9fa;
        }

        .bank-name {
            font-weight: 700;
            font-size: .95rem;
        }

        .bank-number {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: .5px;
        }

        .copy-btn {
            font-size: .8rem;
            padding: 4px 10px;
        }

        .note {
            font-size: .8rem;
            color: #6c757d;
        }
    </style>

    <div class="auth-card">

        {{-- HEADER --}}
        <div class="auth-header mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <img src="{{ asset('projects/assets/img/symcardlogolong.png') }}" height="41">

                <a href="{{ route('dashboard.payment.transfer') }}" class="back-link">
                    ← Back
                </a>
            </div>

            <h2 class="auth-title mb-1">Upload Payment Proof</h2>
            <p class="auth-subtitle mb-0">
                Upload screenshot or photo of your transfer receipt
            </p>
        </div>

        @include('dashboard._partials.payment-stepper', [
            'currentStep' => 'waiting_verification',
        ])


        {{-- ERROR --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        @php
            $payment = $registration->payment ?? null;
            $verification = optional($payment)->verification;
        @endphp


        @if ($payment)
            <div class="mb-4">
                <label class="form-label">Payment Details</label>

                <div class="payment-box">

                    <div class="payment-row">
                        <span>Package Price</span>
                        <span>
                            Rp {{ number_format($registration->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="payment-row">
                        <span>Unique Code</span>
                        <span class="text-danger fw-semibold">
                            Rp {{ number_format($registration->unique_code, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="payment-row total">
                        <span>Total Transfer</span>
                        <span class="text-danger">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="payment-row mt-2 small text-muted">
                        <span>Submitted At</span>
                        <span>
                            {{ $payment->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                </div>
            </div>
        @endif


        @if ($payment)
            <div class="border rounded-3 p-3 mb-3 bg-light">

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="fw-semibold small text-muted">
                        Payment Status
                    </div>

                    @if ($payment->status === 'pending')
                        <span class="badge bg-warning text-dark">Pending Verification</span>
                    @elseif ($payment->status === 'rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @elseif ($payment->status === 'verified')
                        <span class="badge bg-success">Verified</span>
                    @endif
                </div>

                @if ($payment->status === 'pending')
                    <div class="small">
                        Your payment proof has been received and is currently being reviewed by the admin.
                    </div>
                @elseif ($payment->status === 'rejected')
                    <div class="small mb-2">
                        Your payment could not be verified. Please upload a new proof.
                    </div>

                    @if ($verification && $verification->notes)
                        <div class="mt-2 p-2 bg-white border rounded">
                            <div class="fw-semibold small text-muted mb-1">
                                Admin Notes
                            </div>
                            <div class="small">
                                {{ $verification->notes }}
                            </div>
                        </div>
                    @endif
                @elseif ($payment->status === 'verified')
                    <div class="small">
                        Payment has been successfully verified.
                    </div>
                @endif

            </div>
        @endif


        @if (!$payment || $payment->status === 'rejected')
            {{-- PAYMENT DETAIL --}}
            <div class="mb-4">
                <label class="form-label">Payment Details</label>

                <div class="payment-box">
                    <div class="payment-row">
                        <span>Package Price</span>
                        <span>
                            Rp {{ number_format($registration->total_amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="payment-row">
                        <span>Unique Code</span>
                        <span class="text-danger fw-semibold">
                            Rp {{ number_format($registration->unique_code, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="payment-row total">
                        <span>Total Transfer</span>
                        <span class="text-danger">
                            Rp {{ number_format($registration->total_amount + $registration->unique_code, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- BANK INFO --}}
            <div class="mb-4">
                <label class="form-label">Transfer To</label>

                <div class="bank-box">
                    <div class="bank-name mb-1">
                        {{ $registration->bank->name }}
                    </div>

                    <div class="bank-number mb-1" id="bankNumber">
                        {{ $registration->bank->account_number }}
                    </div>

                    <div class="fw-semibold">
                        a.n. {{ $registration->bank->account_name }}
                    </div>
                </div>

                <div class="note mt-2">
                    Please transfer the <b>exact amount</b> including the unique code
                    for manual verification.
                </div>
            </div>

            <form method="POST" action="{{ route('dashboard.payment.store-proof') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">
                        {{ $payment ? 'Upload New Payment Proof' : 'Upload Payment Proof' }}
                    </label>
                    <input type="file" name="proof_file" class="form-control" accept="image/*" required>
                </div>

                <div class="note mb-3">
                    Accepted format: <b>JPG / PNG</b><br>
                    Maximum file size: <b>2 MB</b>
                </div>

                <button class="btn btn-auth w-100">
                    Submit Proof →
                </button>
            </form>
        @elseif ($payment->status === 'pending')
            <button class="btn btn-auth w-100" disabled>
                Waiting for Verification
            </button>
        @endif








    </div>

@endsection
