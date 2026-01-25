<!-- Hero Section -->
<section id="hero" class="hero section dark-background symcard-hero-medical p-0">

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

                        <div class="cta-buttons">

                            @auth
                                {{-- USER SUDAH LOGIN --}}
                                <a href="{{ route('home') }}" class="btn btn-primary btn-cta">
                                    Go to Dashboard
                                </a>
                            @else
                                {{-- USER BELUM LOGIN --}}
                                <a href="{{ route('register') }}" class="btn btn-primary btn-cta">
                                    Register Now
                                </a>
                            @endauth

                            <a href="{{ route('program.full-program') }}" class="btn btn-secondary btn-cta">
                                Scientific Program
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
