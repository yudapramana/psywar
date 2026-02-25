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

            {{-- VENUE MAIN --}}
            <div class="row gy-5">

                <div class="col-lg-6">
                    <div class="venue-image">
                        <img src="{{ asset('projects/assets/img/events/zhm-premiere.jpg') }}" alt="ZHM Premiere Hotel Padang" class="img-fluid rounded">
                        <div class="venue-badge">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Main Venue</span>
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
                            ZHM Premiere Hotel Padang is a premium business and convention hotel
                            located in the heart of Padang city. The hotel offers modern facilities,
                            spacious ballrooms, and multiple meeting rooms, making it an ideal venue
                            for national and international scientific meetings such as SYMCARD 2026.
                        </p>

                        <div class="venue-features">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="feature-item">
                                        <i class="bi bi-people-fill"></i>
                                        <span>Large Ballroom Capacity</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="feature-item">
                                        <i class="bi bi-wifi"></i>
                                        <span>High-Speed Internet</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="feature-item">
                                        <i class="bi bi-p-square-fill"></i>
                                        <span>On-site Parking</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="feature-item">
                                        <i class="bi bi-universal-access"></i>
                                        <span>Fully Accessible</span>
                                    </div>
                                </div>
                            </div>
                        </div>

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

            {{-- MAP --}}
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="venue-map">
                        <h4>Venue Location</h4>
                        <div class="map-container rounded overflow-hidden">
                            <iframe src="https://www.google.com/maps?q=ZHM+Premiere+Hotel+Padang&output=embed" style="border:0; width:100%; height:400px;" allowfullscreen loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TRANSPORT INFO --}}
            <div class="row mt-5">

                <div class="col-lg-4">
                    <div class="travel-info text-center">
                        <div class="travel-icon">
                            <i class="bi bi-airplane-fill"></i>
                        </div>
                        <h5>By Air</h5>
                        <p>
                            Minangkabau International Airport is approximately
                            45 minutes from the hotel by car.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="travel-info text-center">
                        <div class="travel-icon">
                            <i class="bi bi-train-front-fill"></i>
                        </div>
                        <h5>By Public Transport</h5>
                        <p>
                            Easily accessible by taxi and online transportation
                            services within Padang city.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="travel-info text-center">
                        <div class="travel-icon">
                            <i class="bi bi-car-front-fill"></i>
                        </div>
                        <h5>By Car</h5>
                        <p>
                            Direct access from the city center with
                            on-site parking available for participants.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </section>
    {{-- /VENUE SECTION --}}

@endsection
