@extends('layouts.app')

@section('body-class', 'inner-page')

@section('title', 'Accepted Research & Case | SYMCARD 2026')

@section('content')

    <style>
        .escience-tabs-wrapper {
            border: 1px solid #dee2e6;
            border-radius: 14px;
            overflow: hidden;
            background: #f8f9fa;
            margin-bottom: 0;
        }

        .escience-tabs {
            display: flex;
            width: 100%;
        }

        .escience-tabs .nav-item {
            width: 50%;
        }

        .escience-tabs .nav-link {
            width: 100%;
            padding: 16px 0;
            font-weight: 600;
            font-size: 1rem;
            text-align: center;
            border: none;
            background: transparent;
            color: #495057;
            border-radius: 0;
        }

        .escience-tabs .nav-link.active {
            background: #ffffff;
            color: #0d6efd;
            box-shadow: inset 0 -3px 0 #0d6efd;
        }

        .escience-tabs .nav-link:hover {
            background: #ffffff;
        }

        /* Table should look connected */
        .escience-table-card {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>



    {{-- ================= PAGE TITLE ================= --}}
    <div class="page-title dark-background" style="
        background-image:
        linear-gradient(
            135deg,
            rgba(70, 40, 8, 0.78) 0%,       /* deep brown */
            rgba(150, 100, 25, 0.72) 45%,   /* rich gold */
            rgba(90, 55, 12, 0.82) 100%     /* dark bronze */
        ),
        url('{{ asset('projects/assets/img/symcardheadercontent/symcardheadercontent4.jpg') }}');
        background-size: cover;
        background-position: center;
    ">
        <div class="container position-relative">
            <h1>Accepted Research & Case</h1>
            <p>E-Science Station • SYMCARD 2026</p>

            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('landing') }}">Home</a></li>
                    <li>E-Science Station</li>
                    <li class="current">Accepted Research & Case</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- /PAGE TITLE --}}

    <section class="section py-5">
        <div class="container">

            {{-- TABS --}}
            <div class="escience-tabs-wrapper">

                {{-- TABS --}}
                <ul class="nav escience-tabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#research" type="button">
                            Research
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#case" type="button">
                            Case
                        </button>
                    </li>
                </ul>

            </div>

            <div class="tab-content">

                {{-- ================= RESEARCH ================= --}}
                <div class="tab-pane fade show active" id="research">
                    @include('pages.escience._partials.accepted-table', [
                        'papers' => $researchPapers,
                        'cardClass' => 'escience-table-card',
                    ])
                </div>

                {{-- ================= CASE ================= --}}
                <div class="tab-pane fade" id="case">
                    @include('pages.escience._partials.accepted-table', [
                        'papers' => $casePapers,
                        'cardClass' => 'escience-table-card',
                    ])
                </div>

            </div>

        </div>
    </section>

@endsection
