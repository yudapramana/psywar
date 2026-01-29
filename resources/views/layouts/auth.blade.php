<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Authentication') | SYMCARD 2026</title>

    <!-- Bootstrap -->
    <link href="{{ asset('projects/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('projects/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- Main CSS -->
    <link href="{{ asset('projects/assets/css/main.css') }}" rel="stylesheet">

    {{-- Sweetalert 2 --}}
    <link href="{{ asset('projects/assets/vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

    <style>
        /* ===== Background Pattern ===== */
        body {
            min-height: 100vh;
            background-color: #f5f7fb;
            background-image:
                radial-gradient(#e3e7f1 1px, transparent 1px),
                radial-gradient(#e3e7f1 1px, transparent 1px);
            background-size: 24px 24px;
            background-position: 0 0, 12px 12px;
        }

        /* ===== Wrapper ===== */
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 80px 16px 40px;
            /* space atas */
        }

        /* ===== Card ===== */
        .auth-card {
            width: 100%;
            max-width: 540px;
            background: #ffffff;
            border-radius: 16px;
            padding: 36px 34px;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.08);
        }

        /* ===== Typography ===== */
        .auth-title {
            font-weight: 700;
            font-size: 1.55rem;
        }

        .auth-subtitle {
            color: #6b7280;
            font-size: 0.95rem;
        }

        /* ===== Button ===== */
        .btn-auth {
            background: var(--accent-color);
            color: #fff;
            font-weight: 600;
            border-radius: 30px;
            padding: 12px;
        }

        .btn-auth:hover {
            background: #c62828;
            color: #fff;
        }

        /* ===== Form ===== */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 10px 12px;
        }

        .input-group .btn {
            border-radius: 0 10px 10px 0;
        }

        /* Mobile tweak */
        @media (max-width: 576px) {
            .auth-wrapper {
                padding-top: 60px;
            }

            .auth-card {
                padding: 28px 22px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="auth-wrapper">
        @yield('content')
    </div>

    <script src="{{ asset('projects/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('projects/assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @stack('scripts')
</body>

</html>
