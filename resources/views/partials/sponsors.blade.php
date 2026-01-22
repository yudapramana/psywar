<section id="sponsors" class="sponsors section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Our Valuable Partners</h2>
    </div>
    <!-- End Section Title -->

    <div class="container">
        <div class="row g-0 clients-wrap">

            @for ($i = 1; $i <= 6; $i++)
                <div class="col-md-4 client-logo">
                    <img src="{{ asset("projects/assets/img/clients/clients-$i.webp") }}" class="img-fluid">
                </div>
            @endfor

        </div>
    </div>

</section>
