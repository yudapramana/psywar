@extends('layouts.app')

@section('title', 'Abstracts & Cases Submission | SYMCARD 2026')

@section('content')

    {{-- ================= PAGE TITLE ================= --}}
    <div class="page-title dark-background" style="
        background-image:
        linear-gradient(
            135deg,
            rgba(11, 28, 61, 0.23) 0%,
            rgba(18, 58, 130, 0.23) 45%,
            rgba(128, 20, 40, 0.23) 100%
        ),
        url('{{ asset('projects/assets/img/symcardheadercontent/symcardheadercontent4.jpg') }}');
        background-size: cover;
        background-position: center;
    ">
        <div class="container position-relative">
            <h1>Abstracts & Cases Submission</h1>
            <p>
                E-Science Station • SYMCARD 2026
            </p>

            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('landing') }}">Home</a></li>
                    <li>E-Science Station</li>
                    <li class="current">Abstracts & Cases Submission</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- /PAGE TITLE --}}

    {{-- ================= MAIN SECTION ================= --}}
    <section class="section py-5">
        <div class="container">
            <div class="row g-4">

                {{-- ================= SIDEBAR ================= --}}
                <div class="col-lg-4">

                    <div class="card shadow-sm submission-card mb-4">
                        <div class="card-body">

                            <h5 class="fw-bold mb-3">Submission</h5>

                            <div class="submission-item mb-3">
                                <i class="bi bi-calendar-event"></i>
                                <div>
                                    <small class="text-muted">Deadline for abstract submission</small>
                                    <div class="fw-semibold">15 June 2026</div>
                                </div>
                            </div>

                            <div class="submission-item mb-3">
                                <i class="bi bi-bell"></i>
                                <div>
                                    <small class="text-muted">Notification of acceptance</small>
                                    <div class="fw-semibold">20 June 2026</div>
                                </div>
                            </div>

                            {{-- STATUS --}}
                            <div class="alert alert-success text-center fw-semibold mb-3">
                                Submission is open
                            </div>

                            <a href="{{ asset('downloads/abstract-submission-guidelines.pdf') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-file-earmark-text"></i>
                                Abstract Submission Guidelines
                            </a>

                        </div>
                    </div>

                </div>


                {{-- ================= CONTENT ================= --}}
                <div class="col-lg-8">

                    {{-- HOW TO SUBMIT --}}
                    <h4 class="fw-bold mb-3">
                        How To Submit Research & Cases Tutorial
                    </h4>

                    <div class="ratio ratio-16x9 mb-5">
                        {{-- Ganti URL sesuai kebutuhan --}}
                        <iframe src="https://drive.google.com/file/d/0B4F2UNmFsOSxVG5nRm5CUDBzOWM/preview" allow="autoplay" loading="lazy">
                        </iframe>
                    </div>

                    {{-- GENERAL INFORMATION --}}
                    <h4 class="fw-bold mb-3">General Information</h4>
                    <p>
                        Participants may submit more than one research or case.
                        However, participants accepted for oral presentation will be permitted
                        to present only one research or case. The remaining accepted research
                        or case will be presented as posters only.
                    </p>

                    {{-- RULES --}}
                    <h5 class="fw-semibold mt-4">Case, Research, and Poster Competition</h5>
                    <ul class="list-unstyled ps-3">
                        <li class="mb-2">
                            ✓ All Case and Research must be submitted and received before
                            <strong>15 June 2026</strong>.
                        </li>
                        <li class="mb-2">
                            ✓ Poster and Case competition for Medical Professionals
                            (Nurse, Technician, Radiographer, Pharmacists)
                            must be submitted before <strong>12 June 2026</strong>.
                        </li>
                    </ul>


                    {{-- NOTE --}}
                    <div class="alert alert-warning mt-4">
                        <strong>Note:</strong><br>
                        Corresponding author must be registered as a participant of SYMCARD 2026.
                        Research or case must be submitted using the online research or case form.
                        Paper, faxed, or emailed submissions will be rejected.
                    </div>

                </div>

            </div>
        </div>
    </section>

@endsection
