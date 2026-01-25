@extends('layouts.app')

@section('title', 'Board of Committee – SYMCARD 2026')

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
        url('{{ asset('projects/assets/img/symcardheadercontent/symcardheadercontent2.jpg') }}');
        background-size: cover;
        background-position: center;
    ">
        <div class="container position-relative">
            <h1>Board of Committee</h1>
            <p>
                11<sup>th</sup> Padang Symposium on Cardiovascular Disease<br>
                Cardiology 360°: Integrating Knowledge, Technology, and Practice
            </p>

            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('landing') }}">Home</a></li>
                    <li class="current">Board Member</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- /PAGE TITLE --}}

    {{-- ================= MAIN SECTION ================= --}}
    <section class="section py-5">
        <div class="container">
            <div class="row">

                {{-- SIDEBAR --}}
                <div class="col-lg-3 mb-4">
                    @include('pages.about.partials.sidebar')
                </div>

                {{-- CONTENT --}}
                <div class="col-lg-9">

                    @foreach ($boardGroups as $group)
                        {{-- GROUP TITLE --}}
                        <div class="mb-3">
                            <h4 class="group-title">
                                {{ $group->name }}
                            </h4>
                        </div>

                        {{-- MEMBERS WITHOUT SUB SECTION --}}
                        @if ($group->members->count())
                            <div class="row g-3 mb-4">
                                @foreach ($group->members as $member)
                                    <div class="col-sm-6 col-md-4">
                                        <div class="member-item">
                                            <div class="member-name">
                                                {{ $member->name }}
                                            </div>

                                            @if ($member->position)
                                                <div class="member-position">
                                                    {{ $member->position }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- SUB SECTIONS --}}
                        @foreach ($group->subSections as $sub)
                            <div class="mt-4 mb-2">
                                <h6 class="subsection-title">
                                    {{ $sub->name }}
                                </h6>
                            </div>

                            <div class="row g-3 mb-4">
                                @foreach ($sub->members as $member)
                                    <div class="col-sm-6 col-md-4">
                                        <div class="member-item">
                                            <div class="member-name">
                                                {{ $member->name }}
                                            </div>

                                            @if ($member->position)
                                                <div class="member-position">
                                                    {{ $member->position }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endforeach

                </div>
            </div>
        </div>
    </section>

@endsection
