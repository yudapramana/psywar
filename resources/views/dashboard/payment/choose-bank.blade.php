@extends('dashboard._layouts.package')

@section('title', 'Choose Bank')

@section('content')

    <style>
        .bank-option {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: .15s ease;
        }

        .bank-option:hover {
            background: #f8f9fa;
            border-color: #dc3545;
        }

        .bank-option input {
            margin-top: 2px;
        }

        .bank-code {
            font-weight: 700;
            font-size: .9rem;
        }

        .bank-name {
            font-size: .85rem;
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

            <h2 class="auth-title mb-1">Choose Bank</h2>
            <p class="auth-subtitle mb-0">Select bank for transfer</p>
        </div>

        @include('dashboard._partials.payment-stepper', [
            'currentStep' => 'choose_bank',
        ])


        {{-- FORM --}}
        <form method="POST" action="{{ route('dashboard.payment.store-bank') }}">
            @csrf

            <div class="d-flex flex-column gap-2 mb-4">
                @foreach ($banks as $bank)
                    <label class="bank-option">
                        <input type="radio" name="bank_id" value="{{ $bank->id }}" required>

                        <div>
                            <div class="bank-code">
                                {{ $bank->code }}
                            </div>
                            <div class="bank-name">
                                {{ $bank->name }}
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <button class="btn btn-auth w-100">
                Continue →
            </button>
        </form>

    </div>
@endsection
