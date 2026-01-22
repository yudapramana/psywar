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

    <style>
        body {
            background: #f5f7fb;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            width: 100%;
            max-width: 520px;
            background: #ffffff;
            border-radius: 14px;
            padding: 32px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .auth-title {
            font-weight: 700;
            font-size: 1.6rem;
        }

        .auth-subtitle {
            color: #6b7280;
            font-size: 0.95rem;
        }

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

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="auth-wrapper">
        @yield('content')
    </div>

    <script src="{{ asset('projects/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>

</html>
