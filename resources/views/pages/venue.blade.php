@extends('layouts.app')

@section('body-class', 'inner-page')

@section('title', 'Venue | SYMCARD 2026')

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
        url('{{ asset('projects/assets/img/symcardheadercontent/symcardheadercontent1.jpg') }}');
        background-size: cover;
        background-position: center;
    ">
        <div class="container position-relative">
            <h1>Venue</h1>
            <p>
                ZHM Premiere Hotel Padang<br>
                Official Venue of SYMCARD 2026
            </p>

            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('landing') }}">Home</a></li>
                    <li class="current">Venue</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- /PAGE TITLE --}}

    {{-- ================= VENUE SECTION ================= --}}
    <section id="venue-2" class="venue-2 section py-5">
        <div class="container">

            {{-- ================= VENUE 1 ================= --}}
            <div class="row gy-5 mb-5">

                <div class="col-lg-6">
                    <div class="venue-image">
                        <img src="{{ asset('projects/assets/img/events/zhm-premiere.jpg') }}" alt="ZHM Premiere Hotel Padang" class="img-fluid rounded">

                        <div class="venue-badge">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Main Symposium Venue</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="venue-content">

                        <h3>ZHM Premiere Hotel Padang</h3>

                        <div class="venue-address">
                            <i class="bi bi-geo-alt"></i>
                            <span>Jl. M. Thamrin No.27, Padang, West Sumatra, Indonesia</span>
                        </div>

                        <p>
                            ZHM Premiere Hotel Padang serves as the main venue for the SYMCARD 2026 symposium sessions.
                            The hotel provides a modern ballroom and multiple meeting rooms suitable for international
                            conferences, keynote lectures, and scientific presentations.
                        </p>

                        <div class="venue-actions mt-4">
                            <a href="https://maps.google.com/?q=ZHM+Premiere+Hotel+Padang" target="_blank" class="btn btn-primary">
                                <i class="bi bi-map"></i>
                                Get Directions
                            </a>

                            <a href="https://www.zhmhotels.com/hotel/the-zhm-premiere-padang/" target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-building"></i>
                                Official Website
                            </a>
                        </div>

                    </div>
                </div>

            </div>


            {{-- ================= VENUE 2 ================= --}}
            <div class="row gy-5">

                <div class="col-lg-6">
                    <div class="venue-image">
                        <img src="https://res.cloudinary.com/dezj1x6xp/image/upload/v1772715757/PandanViewMandeh/b7xiwglw7t9oqhmq232f.jpg" alt="RSUP Dr. M. Djamil Padang" class="img-fluid rounded">

                        <div class="venue-badge">
                            <i class="bi bi-hospital-fill"></i>
                            <span>Workshop & Clinical Sessions</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="venue-content">

                        <h3>RSUP Dr. M. Djamil Padang</h3>

                        <div class="venue-address">
                            <i class="bi bi-geo-alt"></i>
                            <span>Jl. Perintis Kemerdekaan, Padang, West Sumatra, Indonesia</span>
                        </div>

                        <p>
                            RSUP Dr. M. Djamil Padang is the largest referral hospital in West Sumatra and serves
                            as the clinical venue for selected SYMCARD 2026 workshops and hands-on sessions.
                            Participants will have the opportunity to engage in practical learning and clinical
                            demonstrations conducted by experienced cardiology specialists.
                        </p>

                        <div class="venue-actions mt-4">

                            <a href="https://maps.google.com/?q=RSUP+Dr+M+Djamil+Padang" target="_blank" class="btn btn-primary">
                                <i class="bi bi-map"></i>
                                Get Directions
                            </a>

                            <a href="https://rsdjamil.co.id/" target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-hospital"></i>
                                Official Website
                            </a>

                        </div>

                    </div>
                </div>

            </div>


            {{-- ================= MAP ================= --}}
            <div class="row mt-5">
                <div class="col-lg-12">

                    <div class="venue-map">
                        <h4>Venue Locations</h4>

                        <div class="map-container rounded overflow-hidden">

                            <iframe src="https://www.google.com/maps?q=Padang&output=embed" style="border:0; width:100%; height:400px;" allowfullscreen loading="lazy">
                            </iframe>

                        </div>
                    </div>

                </div>
            </div>


        </div>
    </section>

@endsection
