@extends('dashboard._layouts.package')

@section('title', 'Waiting Transfer')

@section('content')

    <div class="auth-card">

        <div class="auth-header mb-4">
            <h2 class="auth-title">Transfer Payment</h2>
            <p class="auth-subtitle">Please transfer the exact amount</p>
        </div>

        <div class="payment-box mb-4">
            <div class="payment-row">
                <span>Package Price</span>
                <span>Rp {{ number_format($registration->total_amount, 0, ',', '.') }}</span>
            </div>

            <div class="payment-row">
                <span>Unique Code</span>
                <span class="text-danger fw-bold">
                    Rp {{ number_format($registration->unique_code, 0, ',', '.') }}
                </span>
            </div>

            <div class="payment-row total">
                <span>Total Transfer</span>
                <span class="text-danger fw-bold">
                    Rp {{ number_format($registration->total_amount + $registration->unique_code, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="bank-box mb-3">
            <div class="fw-bold">{{ $registration->bank_name }}</div>
            <div class="fs-5 fw-bold">{{ $registration->account_number }}</div>
            <div>a.n. {{ $registration->account_name }}</div>
        </div>

        <div class="note">
            Transfer must be exact for manual verification.
        </div>

    </div>
@endsection
