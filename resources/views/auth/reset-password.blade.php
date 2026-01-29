@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <style>
        .auth-header {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 1rem;
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #212529;
        }

        .auth-subtitle {
            font-size: .95rem;
            color: #6c757d;
        }

        .back-link {
            font-size: .9rem;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>

    <div class="auth-card">

        {{-- AUTH HEADER (MATCH LOGIN & FORGOT) --}}
        <div class="auth-header mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <img src="{{ asset('projects/assets/img/symcardlogolong.png') }}" alt="Logo" height="41">

                <a href="{{ route('login') }}" class="back-link">
                    ← Back to Login
                </a>
            </div>

            <h2 class="auth-title mb-1">Reset Password</h2>
            <p class="auth-subtitle mb-0">
                Create a new password for your account
            </p>
        </div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('password.update') }}" id="resetForm">
            @csrf

            {{-- TOKEN & EMAIL (WAJIB FORTIFY) --}}
            <input type="hidden" name="token" value="{{ request()->route('token') }}">
            <input type="hidden" name="email" value="{{ request()->email }}">

            {{-- PASSWORD --}}
            <div class="mb-3">
                <label class="form-label">New Password</label>

                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>

                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- CONFIRM --}}
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>

                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            {{-- SUBMIT --}}
            <button type="submit" class="btn btn-auth w-100 d-flex justify-content-center align-items-center" id="resetBtn">
                <span id="resetText">Reset Password</span>
                <span id="resetSpinner" class="spinner-border spinner-border-sm ms-2 d-none"></span>
            </button>
        </form>

    </div>

    {{-- ANTI SPAM CLICK --}}
    <script>
        document.getElementById('resetForm').addEventListener('submit', function() {
            const btn = document.getElementById('resetBtn')
            const text = document.getElementById('resetText')
            const spinner = document.getElementById('resetSpinner')

            btn.disabled = true
            text.innerText = 'Resetting...'
            spinner.classList.remove('d-none')
        })
    </script>
@endsection
