@extends('layouts.app')

@section('title', '404 – Page Not Found | SYMCARD 2026')

@section('content')

    {{-- ================= PAGE TITLE ================= --}}
    <div class="page-title dark-background" style="
        background-image:
        linear-gradient(
            135deg,
            rgba(11, 28, 61, 0.47) 0%,
            rgba(18, 58, 130, 0.47) 45%,
            rgba(128, 20, 40, 0.47) 100%
        ),
        url('{{ asset('projects/assets/img/symcardheadercontent/symcardheadercontent2.jpg') }}');
        background-size: cover;
        background-position: center;
    ">
        <div class="container position-relative text-center">
            <h1>404</h1>
            <p>
                Page Not Found<br>
                SYMCARD 2026
            </p>

            <nav class="breadcrumbs justify-content-center">
                <ol>
                    <li><a href="{{ route('landing') }}">Home</a></li>
                    <li class="current">404</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- /PAGE TITLE --}}

    {{-- ================= MAIN SECTION ================= --}}
    <!-- Error 404 Section -->
    <section id="error-404" class="error-404 section">

        <div class="container">

            <div class="text-center">
                <div class="error-icon mb-4">
                    <i class="bi bi-exclamation-circle"></i>
                </div>

                <h1 class="error-code mb-4">404</h1>

                <h2 class="error-title mb-3">Oops! Page Not Found</h2>

                <p class="error-text mb-4">
                    The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
                </p>

                <div class="search-box mb-4">
                    <form action="#" class="search-form">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for pages..." aria-label="Search">
                            <button class="btn search-btn" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="error-action cta-buttons">
                    <a href="{{ route('landing') }}" class="btn btn-primary">Back to Home</a>
                    <a href="{{ url()->previous() }}" class="btn btn-primary btn-cta">
                        Go Back
                    </a>
                </div>
            </div>

        </div>

    </section><!-- /Error 404 Section -->

@endsection
