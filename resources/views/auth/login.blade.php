@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <style>
        .auth-header {
            text-align: left;
        }

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

        <div class="auth-header mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <a href="{{ route('landing') }}">
                    <img src="{{ asset('projects/assets/img/symcardlogolong.png') }}" alt="Logo" height="41">
                </a>
                <a href="{{ route('register') }}" class="back-link">
                    ← Go to Register
                </a>
            </div>

            <h2 class="auth-title mb-1">Login to account</h2>
            <p class="auth-subtitle mb-0">Access your SYMCARD account</p>
        </div>

        @php
            use App\Http\Controllers\Auth\CaptchaController;
            $captcha = CaptchaController::generate();
        @endphp


        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
            </div>

            <!-- Password -->
            <div class="mb-0">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="text-end mt-0 mb-2">
                <a href="{{ route('password.request') }}" class="small text-muted">
                    Forgot your password?
                </a>
            </div>

            <!-- Captcha -->
            <div class="mb-3">
                <label class="form-label">Captcha</label>

                <div class="input-group">
                    <span class="input-group-text fw-bold text-uppercase bg-light" style="letter-spacing: 2px;">
                        {{ session('login_captcha') }}
                    </span>

                    <input type="text" name="captcha" class="form-control text-uppercase" placeholder="Enter captcha" required oninput="this.value = this.value.toUpperCase()">

                </div>

                @error('captcha')
                    <div class="text-danger text-sm mt-1">
                        {{ $message }}
                    </div>
                @enderror

                @error('email')
                    <div class="text-danger text-sm mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>




            <button type="submit" class="btn btn-auth w-100 d-flex justify-content-center align-items-center" id="loginBtn">
                <span id="loginText">Login</span>
                <span id="loginSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
            </button>


            <div class="text-center mt-3">
                <a href="{{ route('register') }}" class="text-decoration-none">
                    Create new account
                </a>
            </div>

        </form>

    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn')
            const text = document.getElementById('loginText')
            const spinner = document.getElementById('loginSpinner')

            btn.disabled = true
            text.innerText = 'Logging in...'
            spinner.classList.remove('d-none')
        })
    </script>

@endsection
