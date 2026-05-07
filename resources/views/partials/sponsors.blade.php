<!-- Sponsors / Partners Section -->
<section id="sponsors" class="sponsors section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Our Valuable Partners</h2>
    </div>

    <div class="container">
        <div class="row g-0 clients-wrap">
            @for ($i = 1; $i <= 17; $i++)
                <div class="col-md-3 client-logo">
                    <img src="{{ asset("projects/assets/img/webp/sponsor-$i.webp") }}" class="img-fluid" alt="Partner sponsor {{ $i }}">
                </div>
            @endfor
        </div>
    </div>
</section>


<!-- Clients Slider Section -->
<section id="clients" class="clients section light-background">
    <div class="container">
        <!-- Swiper container -->
        <div class="swiper init-swiper" id="clients-swiper">
            <div class="swiper-wrapper align-items-center">
                @for ($i = 1; $i <= 17; $i++)
                    <div class="swiper-slide" data-swiper-slide-index="{{ $i }}" role="group" aria-label="{{ $i }} / 17">
                        <img src="{{ asset("projects/assets/img/webp/sponsor-$i.webp") }}" class="img-fluid" alt="Client logo {{ $i }}">
                    </div>
                @endfor
            </div>

            <!-- Pagination -->
            {{-- <div class="swiper-pagination"></div> --}}

            <!-- Optional: Navigation arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>


<!-- Swiper JS (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        new Swiper("#clients-swiper", {
            loop: true,
            speed: 600,
            autoplay: {
                delay: 1000,
                disableOnInteraction: false,
            },
            slidesPerView: "auto",
            centeredSlides: false,
            spaceBetween: 120,
            pagination: {
                el: ".swiper-pagination",
                type: "bullets",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                320: {
                    slidesPerView: 2,
                    spaceBetween: 40,
                },
                480: {
                    slidesPerView: 3,
                    spaceBetween: 60,
                },
                640: {
                    slidesPerView: 4,
                    spaceBetween: 80,
                },
                992: {
                    slidesPerView: 6,
                    spaceBetween: 120,
                },
            },
        });
    });
</script>
