@extends('layouts.app')

@section('title', 'About SYMCARD 2026')

@section('content')

    {{-- ================= PAGE TITLE ================= --}}
    <div class="page-title dark-background" style="background-image: url('{{ asset('projects/assets/img/events/showcase-9.webp') }}');">
        <div class="container position-relative">
            <h1>Gallery SYMCARD 2026</h1>
            <p>
                11<sup>th</sup> Padang Symposium on Cardiovascular Disease<br>
                Cardiology 360°: Integrating Knowledge, Technology, and Practice
            </p>

            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('landing') }}">Home</a></li>
                    <li class="current">Gallery</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- /PAGE TITLE --}}

    <section class="section py-5">
        <div class="container">
            <div class="row">

                {{-- SIDEBAR --}}
                <div class="col-lg-3 mb-4">
                    @include('pages.about.partials.sidebar')
                </div>

                {{-- CONTENT --}}
                <div class="col-lg-9">

                    <div class="content">

                        <div class="row gy-4 justify-content-center">

                            @forelse ($galleries as $gallery)
                                <div class="col-xl-3 col-lg-4 col-md-6">

                                    <div class="gallery-item h-100">
                                        <img src="{{ asset($gallery->image_path) }}" class="img-fluid" alt="{{ $gallery->title ?? 'SYMCARD Gallery' }}">

                                        <div class="gallery-links d-flex align-items-center justify-content-center">
                                            <a href="{{ asset($gallery->image_path) }}" title="{{ $gallery->title }}" class="glightbox preview-link">
                                                <i class="bi bi-arrows-angle-expand"></i>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        Gallery is currently unavailable.
                                    </div>
                                </div>
                            @endforelse

                        </div>

                    </div>


                </div>
            </div>
        </div>
    </section>

@endsection
