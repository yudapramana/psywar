@extends('dashboard._layouts.package')

@section('title', 'Payment')

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

                <a href="{{ route('dashboard.my-package') }}" class="back-link">
                    ← Back
                </a>
            </div>

            <h2 class="auth-title mb-1">Payment</h2>
            <p class="auth-subtitle mb-0">Complete your payment manually</p>
        </div>





        {{-- CONFIRM --}}
        <form method="POST" action="{{ route('dashboard.payment.selectBank') }}">
            @csrf

            @foreach ($banks as $bank)
                <div class="bank-box mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bank_id" value="{{ $bank->id }}" required>

                        <label class="form-check-label w-100">
                            <div class="fw-semibold">{{ $bank->name }}</div>
                            <div class="small text-muted">{{ $bank->account_number }}</div>
                            <div class="small">a.n. {{ $bank->account_name }}</div>
                        </label>
                    </div>
                </div>
            @endforeach

            <button class="btn btn-auth w-100 mt-3">
                Continue →
            </button>
        </form>


    </div>

@endsection
