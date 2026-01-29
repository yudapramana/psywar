@extends('layouts.auth')

@section('title', 'Forgot Password')

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

    @php
        use App\Http\Controllers\Auth\CaptchaController;
        $captcha = CaptchaController::generate();
    @endphp

    <div class="auth-card">

        {{-- AUTH HEADER --}}
        <div class="auth-header mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <img src="{{ asset('projects/assets/img/symcardlogolong.png') }}" alt="Logo" height="41">

                <a href="{{ route('login') }}" class="back-link">
                    ← Back to Login
                </a>
            </div>

            <h2 class="auth-title mb-1">Forgot your password?</h2>
            <p class="auth-subtitle mb-0">
                Enter your email to receive a reset link
            </p>
        </div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
            @csrf

            {{-- EMAIL --}}
            <div class="mb-3">
                <label class="form-label">Email Address</label>

                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com" required autofocus>

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- CAPTCHA --}}
            <div class="mb-3">
                <label class="form-label">Captcha</label>

                <div class="input-group">
                    <span class="input-group-text fw-bold text-uppercase bg-light" style="letter-spacing:2px">
                        {{ session('login_captcha') }}
                    </span>

                    <input type="text" name="captcha" class="form-control text-uppercase" placeholder="Enter captcha" required oninput="this.value = this.value.toUpperCase()">
                </div>

                @error('captcha')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- SUBMIT --}}
            <button type="submit" class="btn btn-auth w-100 d-flex justify-content-center align-items-center" id="resetBtn">
                <span id="resetText">Send Reset Link</span>
                <span id="resetSpinner" class="spinner-border spinner-border-sm ms-2 d-none"></span>
            </button>
        </form>

    </div>

    {{-- ANTI SPAM CLICK --}}
    <script>
        document.getElementById('forgotForm').addEventListener('submit', function() {
            const btn = document.getElementById('resetBtn')
            const text = document.getElementById('resetText')
            const spinner = document.getElementById('resetSpinner')

            btn.disabled = true
            text.innerText = 'Sending...'
            spinner.classList.remove('d-none')
        })
    </script>

    {{-- SWEETALERT SUCCESS --}}
    @push('scripts')
        @if (session('status'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Reset Link Sent',
                    text: @json(session('status')),
                    confirmButtonColor: '#dc3545'
                });
            </script>
        @endif
    @endpush
@endsection
