@extends('layouts.app')

@section('body-class', 'landing-page')

@section('title', 'SYMCARD 2026')

@section('content')

    {{-- ================= HERO ================= --}}
    @include('partials.hero')

    {{-- ================= EVENT FLYER ================= --}}
    <section class="symcard-flyer-section py-5 section about section">
        <div class="container">
            {{-- 
            <div class="row justify-content-center text-center mb-4">
                <div class="col-lg-8">
                    <h2 class="section-title mb-1 pb-1">Event Information</h2>
                    <p class="section-subtitle">
                        Complete overview of SYMCARD 2026 Symposium & Workshop
                    </p>
                </div>
            </div> --}}

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
                        <img src="https://res.cloudinary.com/dezj1x6xp/image/upload/v1772167706/PandanViewMandeh/c79bdgx80ycdansy2zjh.png" alt="Symcard Flyer 1" class="img-fluid rounded-4 shadow-lg">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="flyer-card">
                        <img src="https://res.cloudinary.com/dezj1x6xp/image/upload/v1772067760/PandanViewMandeh/dn9hdzfhzpqz1mklcjez.jpg" alt="Symcard Flyer 2" class="img-fluid rounded-4 shadow-lg">
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


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const slides = document.querySelectorAll(".hero-slider .slide");
            let current = 0;

            function loadBackground(slide) {
                if (!slide.style.backgroundImage) {
                    const bg = slide.getAttribute("data-bg");
                    if (bg) {
                        slide.style.backgroundImage = `url('${bg}')`;
                    }
                }
            }

            function nextSlide() {
                slides[current].classList.remove("active");

                current = (current + 1) % slides.length;

                loadBackground(slides[current]);

                slides[current].classList.add("active");
            }

            setInterval(nextSlide, 5000);
        });
    </script>
@endsection
