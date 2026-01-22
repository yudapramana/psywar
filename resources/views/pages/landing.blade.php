@extends('layouts.app')

@section('title', 'SYMCARD 2026')

@section('content')

    {{-- ================= HERO ================= --}}
    @include('partials.hero')

    {{-- ================= INTRO ================= --}}
    @include('partials.intro')

    {{-- ================= ABOUT ================= --}}
    @include('partials.about')

    {{-- ================= PROGRAMS ================= --}}
    @include('partials.programs')

    {{-- ================= COURSE DIRECTOR ================= --}}
    @include('partials.course-director')

    {{-- ================= SPONSORS ================= --}}
    @include('partials.sponsors')

    {{-- ================= CTA ================= --}}
    @include('partials.cta')

    {{-- ================= TESTIMONIALS ================= --}}
    @include('partials.testimonials')

    {{-- ================= GALLERY ================= --}}
    @include('partials.gallery')

@endsection
