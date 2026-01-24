@extends('dashboard._layouts.package')

@section('title', 'Waiting Transfer')

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

                <a href="{{ route('dashboard.payment.choose-bank') }}" class="back-link">
                    ← Back
                </a>
            </div>

            <h2 class="auth-title mb-1">Transfer Payment</h2>
            <p class="auth-subtitle mb-0">
                Please transfer the <b>exact amount</b> below
            </p>
        </div>

        @include('dashboard._partials.payment-stepper', [
            'currentStep' => 'waiting_transfer',
        ])


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
                    {{ $registration->bank_name }}
                </div>

                <div class="bank-number mb-1" id="bankNumber">
                    {{ $registration->account_number }}
                </div>

                <div class="fw-semibold mb-2">
                    a.n. {{ $registration->account_name }}
                </div>

                <button type="button" class="btn btn-outline-secondary copy-btn" onclick="navigator.clipboard.writeText('{{ $registration->account_number }}')">
                    Copy Account Number
                </button>
            </div>

            <div class="note mt-2">
                Please transfer the <b>exact amount</b> including the unique code
                for manual verification.
            </div>
        </div>

        {{-- ACTION --}}
        <a href="{{ route('dashboard.payment.upload-proof', $registration->id) }}" class="btn btn-auth w-100">
            Upload Payment Proof →
        </a>

    </div>

@endsection
