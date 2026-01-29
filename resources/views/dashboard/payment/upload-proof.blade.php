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

        .bank-box {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 14px;
            background: #f8f9fa;
        }

        .note {
            font-size: .8rem;
            color: #6c757d;
        }

        /* ⛔ ANTI SPAM */
        .btn-loading {
            pointer-events: none;
            opacity: .8;
        }
    </style>

    <div class="auth-card">

        {{-- HEADER --}}
        <div class="auth-header mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <img src="{{ asset('projects/assets/img/symcardlogolong.png') }}" height="41">

                <a href="{{ route('dashboard.my-package') }}" class="back-link">
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

        {{-- ================= PAYMENT STATUS CARD ================= --}}
        @if ($payment)
            <div class="border rounded-3 p-3 mb-4 bg-light">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-semibold small text-muted">
                        Payment Status
                    </div>

                    @if ($payment->status === 'pending')
                        <span class="badge bg-warning text-dark">
                            Waiting for Verification
                        </span>
                    @elseif ($payment->status === 'rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @elseif ($payment->status === 'verified')
                        <span class="badge bg-success">Verified</span>
                    @endif
                </div>

                {{-- PENDING --}}
                @if ($payment->status === 'pending')
                    <div class="small mb-3">
                        Your payment proof has been received and is currently being reviewed by the admin.
                    </div>

                    <button type="button" class="btn btn-outline-secondary w-100" onclick="window.location.reload()">
                        Refresh Status
                    </button>

                    <div class="note text-center mt-2">
                        Click refresh to check if your payment has been verified.
                    </div>

                    {{-- REJECTED --}}
                @elseif ($payment->status === 'rejected')
                    <div class="small mb-2">
                        Your payment could not be verified. Please upload a new proof.
                    </div>

                    @if ($verification && $verification->notes)
                        <div class="mt-2 p-2 bg-white border rounded small">
                            <div class="fw-semibold text-muted mb-1">
                                Admin Notes
                            </div>
                            {{ $verification->notes }}
                        </div>
                    @endif

                    {{-- VERIFIED --}}
                @elseif ($payment->status === 'verified')
                    <div class="small">
                        Payment has been successfully verified.
                    </div>
                @endif

            </div>
        @endif
        {{-- ================= END PAYMENT STATUS CARD ================= --}}

        {{-- ================= UPLOAD FORM ================= --}}
        @if (!$payment || $payment->status === 'rejected')
            <form id="uploadProofForm" method="POST" action="{{ route('dashboard.payment.store-proof') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">
                        {{ $payment ? 'Upload New Payment Proof' : 'Upload Payment Proof' }}
                    </label>

                    <input type="file" name="proof_file" class="form-control" accept="image/*" required>

                    <div class="note mt-1 mb-3">
                        Upload a clear screenshot or photo of your bank transfer receipt.<br>
                        Accepted format: <b>JPG / PNG</b> • Maximum size: <b>2 MB</b>.
                    </div>
                </div>

                <button id="submitBtn" type="submit" class="btn btn-auth w-100">
                    Submit Proof →
                </button>
            </form>
        @endif

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const form = document.getElementById('uploadProofForm')
            if (!form) return

            const btn = document.getElementById('submitBtn')
            let submitted = false

            form.addEventListener('submit', (e) => {

                if (submitted) {
                    e.preventDefault()
                    return
                }

                submitted = true
                btn.disabled = true
                btn.classList.add('btn-loading')
                btn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Uploading...
        `
            })
        })
    </script>
@endpush
