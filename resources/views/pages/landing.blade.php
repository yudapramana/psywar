@extends('layouts.app')

@section('body-class', 'landing-page')

@section('title', 'SYMCARD 2026')

@section('content')

    {{-- ================= HERO ================= --}}
    @include('partials.hero')

    {{-- ================= EVENT FLYER ================= --}}
    <section class="symcard-flyer-section py-5 section about section">
        <div class="container">

            <div class="row justify-content-center mb-3">
                <div class="col-lg-8 text-center" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="section-heading">Event Information</h2>
                    <p class="lead">
                        Complete overview of SYMCARD 2026 Symposium & Workshop
                    </p>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">

                <div class="col-lg-6">
                    <div class="flyer-card">
                        <img src="https://res.cloudinary.com/dezj1x6xp/image/upload/v1772167706/PandanViewMandeh/c79bdgx80ycdansy2zjh.png" alt="Symcard Flyer 1" class="img-fluid rounded-4 shadow-lg" loading="lazy" decoding="async" width="800" height="1000">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="flyer-card">
                        <img src="https://res.cloudinary.com/dezj1x6xp/image/upload/v1772067760/PandanViewMandeh/dn9hdzfhzpqz1mklcjez.jpg" alt="Symcard Flyer 1" class="img-fluid rounded-4 shadow-lg" loading="lazy" decoding="async" width="800" height="1000">
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- ================= INTRO ================= --}}
    @include('partials.intro')

    {{-- ================= ABOUT ================= --}}
    @include('partials.about')

    {{-- ================= PROGRAMS ================= --}}
    @include('partials.programs')

    {{-- ================= COURSE DIRECTOR ================= --}}
    @include('partials.course-director')

    {{-- ================= SPONSORS ================= --}}
    {{-- @include('partials.sponsors') --}}

    {{-- ================= CTA ================= --}}
    @include('partials.cta')

    {{-- ================= TESTIMONIALS ================= --}}
    @include('partials.testimonials')

    {{-- ================= GALLERY ================= --}}
    @include('partials.gallery')


    @push('scripts')
        <script defer>
            document.addEventListener("DOMContentLoaded", function() {

                const slides = document.querySelectorAll(".hero-slider .slide");
                let current = 0;

                function loadBackground(slide) {
                    if (!slide.dataset.loaded) {
                        const bg = slide.dataset.bg;
                        if (bg) {
                            slide.style.backgroundImage = `url('${bg}')`;
                            slide.dataset.loaded = true;
                        }
                    }
                }

                function nextSlide() {
                    slides[current].classList.remove("active");
                    current = (current + 1) % slides.length;
                    loadBackground(slides[current]);
                    slides[current].classList.add("active");
                }

                setInterval(nextSlide, 6000);
            });
        </script>
    @endpush
@endsection
