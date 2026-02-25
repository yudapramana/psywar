<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title', 'SYMCARD 2026 | Padang Symposium on Cardiovascular Disease')</title>

    <!-- Favicons -->
    <link href="{{ asset('projects/assets/img/symcardfavicon.ico') }}" rel="icon">
    <link href="{{ asset('projects/assets/img/symcardfavicon.ico') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Rubik&family=Kanit&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link href="{{ asset('projects/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('projects/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('projects/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('projects/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">

    <!-- MAIN EVENTLY CSS -->
    <link href="{{ asset('projects/assets/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('projects/assets/css/symcard.css') }}" rel="stylesheet">


    {{-- Custom SYMCARD override --}}
    <link href="{{ asset('projects/assets/css/symcard.css') }}" rel="stylesheet">
</head>

{{-- <body class="index-page"> --}}

<body class="@yield('body-class')">

    @include('layouts.header')

    <main class="main">
        @yield('content')
    </main>

    @include('layouts.footer')
    @include('layouts.scripts')
    @stack('scripts')

</body>

</html>
