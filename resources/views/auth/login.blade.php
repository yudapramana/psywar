@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-card">

        <div class="mb-4 text-center">
            <img src="{{ asset('projects/assets/img/symcardlong.png') }}" height="40" class="mb-3">
            <h2 class="auth-title">Login</h2>
            <p class="auth-subtitle">Access your SYMCARD account</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-auth w-100">
                Login
            </button>

            <div class="text-center mt-3">
                <a href="{{ route('register') }}" class="text-decoration-none">
                    Create new account
                </a>
            </div>

        </form>

    </div>
@endsection
