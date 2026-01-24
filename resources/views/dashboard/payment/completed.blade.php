@extends('dashboard._layouts.package')

@section('title', 'Payment Completed')

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

        .success-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 16px;
        }
    </style>

    <div class="auth-card">

        {{-- HEADER --}}
        <div class="auth-header mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <img src="{{ asset('projects/assets/img/symcardlogolong.png') }}" alt="Logo" height="41">

                <a href="{{ route('dashboard.my-package') }}" class="back-link">
                    ← Back to packages
                </a>
            </div>

            <h2 class="auth-title mb-1">Payment Completed</h2>
            <p class="auth-subtitle mb-0">
                Your payment has been verified successfully
            </p>
        </div>

        {{-- STEPPER --}}
        @include('dashboard._partials.payment-stepper', [
            'currentStep' => 'paid',
        ])

        {{-- SUCCESS MESSAGE --}}
        <div class="text-center my-4">
            <div class="success-icon">✓</div>

            <h3 class="fw-semibold mb-2">
                Thank you for your payment
            </h3>

            <p class="auth-subtitle mb-0">
                Your registration is now fully confirmed.
            </p>
        </div>

        {{-- PAYMENT SUMMARY --}}
        <div class="mb-4">
            <label class="form-label">Payment Summary</label>

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
                    <span>Total Paid</span>
                    <span class="text-danger">
                        Rp {{ number_format($registration->total_amount + $registration->unique_code, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <a href="{{ route('dashboard.my-package') }}" class="btn btn-auth w-100">
            Back to My Package →
        </a>

    </div>
@endsection
