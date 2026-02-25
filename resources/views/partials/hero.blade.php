<!-- Hero Section -->
<section id="hero" class="hero section dark-background symcard-hero-medical p-0">

    <!-- Background Slider -->
    <div class="hero-slider">
        <div class="slide active" style="background-image: url('{{ asset('projects/assets/img/hero/symposium.jpg') }}');"></div>
        <div class="slide" style="background-image: url('{{ asset('projects/assets/img/hero/masjidrayasumbar.png') }}');"></div>
        <div class="slide" style="background-image: url('{{ asset('projects/assets/img/hero/jamgadang.jpg') }}');"></div>
        <div class="slide" style="background-image: url('{{ asset('projects/assets/img/hero/rumahgaadang.jpg') }}');"></div>
        <div class="slide" style="background-image: url('{{ asset('projects/assets/img/hero/tabuik.jpg') }}');"></div>
        <div class="slide" style="background-image: url('{{ asset('projects/assets/img/hero/pulaupasumpahan.jpg') }}');"></div>
        <div class="slide" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/7/71/Jakarta_TMII_-_West_Sumatra_%282025%29_-_img_14.jpg')"></div>
        <div class="slide" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/2/22/Padang_City_as_seen_from_the_peak_of_Gunung_Padang%2C_2017-02-14.jpg')"></div>
        <div class="slide" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/f/f1/Paragliding_Agam.jpg')"></div>
        <div class="slide" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/2/28/Dispar_Lembah_Harau.jpg')"></div>
        <div class="slide" style="background-image: url('{{ asset('projects/assets/img/hero/kepulauanmentawai.jpg') }}');"></div>
    </div>

    <!-- Golden Overlay -->
    <div class="background-overlay"></div>

    <div class="hero-content">

        <div class="container">

            <div class="row justify-content-center text-center">

                <div class="col-lg-10">

                    <div class="hero-text">

                        <h1 class="hero-title">
                            The 11<sup>th</sup> Padang Symposium on
                            Cardiovascular Disease
                        </h1>

                        <p class="hero-subtitle">
                            Cardiology 360°: Integrating Knowledge, Technology, and Practice
                        </p>

                        <div class="event-details">
                            <div class="detail-item">
                                <i class="bi bi-calendar-event"></i>
                                <span>5–7 June 2026</span>
                            </div>
                            <div class="detail-item">
                                <i class="bi bi-geo-alt"></i>
                                <span>ZHM Premiere Hotel, Padang – West Sumatra</span>
                            </div>
                        </div>

                    </div>

                    <!-- Countdown -->
                    {{-- <div class="countdown-section">

                        <h3 class="countdown-label">Event Starts In</h3>


                        <div class="countdown d-flex justify-content-center" data-count="2026/06/05">
                            <div>
                                <h3 class="count-days">0</h3>
                                <h4>Days</h4>
                            </div>
                            <div>
                                <h3 class="count-hours">0</h3>
                                <h4>Hours</h4>
                            </div>
                            <div>
                                <h3 class="count-minutes">0</h3>
                                <h4>Minutes</h4>
                            </div>
                            <div>
                                <h3 class="count-seconds">0</h3>
                                <h4>Seconds</h4>
                            </div>
                        </div>

                    </div> --}}

                    <!-- CTA -->
                    <div class="cta-section">

                        {{-- <div class="cta-buttons">

                            @auth
                                
                                <a href="{{ route('home') }}" class="btn btn-primary btn-cta">
                                    Go to Dashboard
                                </a>
                            @else
                                
                                <a href="{{ route('registration') }}" class="btn btn-primary btn-cta">
                                    Register Now
                                </a>
                            @endauth

                            <a href="{{ route('program.full-program') }}" class="btn btn-secondary btn-cta">
                                Scientific Program
                            </a>

                            <a href="{{ route('escience.abstracts-cases-submission') }}" class="btn btn-secondary btn-cta">
                                Abstract Submission
                            </a>

                        </div> --}}

                        <div class="cta-buttons">

                            @auth
                                <a href="{{ route('home') }}" class="btn btn-cta btn-cta-primary">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('registration') }}" class="btn btn-cta btn-cta-primary">
                                    Register Now
                                </a>
                            @endauth

                            <a href="{{ route('program.full-program') }}" class="btn btn-cta btn-cta-secondary">
                                Scientific Program
                            </a>

                            <a href="{{ route('escience.abstracts-cases-submission') }}" class="btn btn-cta btn-cta-tertiary">
                                Abstract Submission
                            </a>

                        </div>

                        <p class="cta-note">
                            Annual scientific meeting • Continuing Medical Education • Cardiovascular Focus
                        </p>

                    </div>


                </div><!-- End col-lg-10 -->

            </div><!-- End row -->

            <!-- Sponsors -->
            {{-- <div class="sponsors-section">

                <p class="sponsors-label">In collaboration with</p>

                <div class="sponsors-logos">
                    <img src="{{ asset('projects/assets/img/clients/clients-1.webp') }}" class="sponsor-logo">
                    <img src="{{ asset('projects/assets/img/clients/clients-3.webp') }}" class="sponsor-logo">
                    <img src="{{ asset('projects/assets/img/clients/clients-5.webp') }}" class="sponsor-logo">
                </div>

            </div><!-- End sponsors-section --> --}}

        </div><!-- End container -->

    </div>

</section>
<!-- /Hero Section -->
