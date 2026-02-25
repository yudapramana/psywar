<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Participant Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicons -->
    <link href="{{ asset('projects/assets/img/symcardfavicon.ico') }}" rel="icon">
    <link href="{{ asset('projects/assets/img/symcardfavicon.ico') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Rubik&family=Kanit&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="{{ asset('projects/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('projects/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    {{-- Sweetalert 2 --}}
    <link href="{{ asset('projects/assets/vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

    <!-- Main CSS -->
    <link href="{{ asset('projects/assets/css/main.css') }}" rel="stylesheet">

    <style>
        body {
            background: #f6f7fb;
        }

        /* ===== GLOBAL HEADER ===== */
        .global-header {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 20px;
        }

        .global-header .brand img {
            height: 36px;
        }

        /* ===== LAYOUT ===== */
        .dashboard-wrapper {
            display: flex;
            min-height: calc(100vh - 64px);
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            padding-top: 12px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            padding: 24px;
        }

        .page-header {
            margin-bottom: 20px;
        }

        /* ===== MENU ===== */
        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #374151;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .menu-item:hover,
        .menu-item.active {
            background: #fef2f2;
            color: #e53935;
            font-weight: 600;
        }

        /* ===== MOBILE ===== */
        @media (max-width: 991px) {
            .sidebar {
                display: none;
            }

            .main-content {
                padding: 16px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- ================= GLOBAL HEADER ================= --}}
    <header class="global-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center gap-2">
            {{-- MOBILE BURGER --}}
            <button class="btn btn-outline-secondary d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                <i class="bi bi-list"></i>
            </button>

            <div class="brand">
                <a href="{{ route('landing') }}">
                    <img src="{{ asset('projects/assets/img/symcardlong.png') }}">
                </a>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('dashboard.buy-package') }}" class="btn btn-warning btn-sm d-none d-md-inline">
                + Buy Package
            </a>

            <div class="dropdown">
                <a href="#" class="text-dark" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-4"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-item text-muted">
                        {{ auth()->user()->name }}
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </header>

    {{-- ================= DASHBOARD BODY ================= --}}
    <div class="dashboard-wrapper">

        {{-- SIDEBAR DESKTOP --}}
        <aside class="sidebar d-none d-lg-block">
            @include('dashboard._partials.sidebar')
        </aside>

        {{-- MAIN --}}
        <main class="main-content">

            {{-- PAGE HEADER --}}
            <div class="page-header">
                <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
            </div>

            {{-- PAGE CONTENT --}}
            @yield('content')

        </main>

    </div>

    {{-- ================= MOBILE SIDEBAR ================= --}}
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <img src="{{ asset('projects/assets/img/symcardlong.png') }}" height="32">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            @include('dashboard._partials.sidebar')
        </div>
    </div>

    <script src="{{ asset('projects/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('projects/assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @stack('scripts')
</body>

</html>
