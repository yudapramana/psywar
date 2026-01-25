@extends('layouts.app')

@section('title', 'Meeting at a Glance | SYMCARD 2026')

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
        url('{{ asset('projects/assets/img/symcardheadercontent/symcardheadercontent1.jpg') }}');
        background-size: cover;
        background-position: center;
    ">
        <div class="container position-relative">
            <h1>Full Programs</h1>
            <p>
                11<sup>th</sup> Padang Symposium on Cardiovascular Disease<br>
                Cardiology 360°: Integrating Knowledge, Technology, and Practice
            </p>

            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('landing') }}">Home</a></li>
                    <li class="current">Full Programs</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- /PAGE TITLE --}}

    {{-- ================= MAIN CONTENT ================= --}}
    <section class="section py-5">
        <div class="container">

            {{-- TOP BAR --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <input type="text" id="programSearch" class="form-control w-50" placeholder="Search Program…">
            </div>

            {{-- ================= DAY LOOP ================= --}}
            @foreach ($days as $day)
                <h3 class="fw-bold mt-5 mb-3 program-day">
                    {{ $day->date->format('l, F d') }}
                </h3>

                @php
                    // group session by activity category
                    $grouped = $day->sessions->load('activity.topics', 'room')->groupBy(fn($s) => $s->activity->category);
                @endphp

                {{-- ================= CATEGORY LOOP ================= --}}
                @foreach ($grouped as $category => $sessions)
                    <h5 class="mt-4 mb-3 text-capitalize program-category">
                        {{ str_replace('_', ' ', $category) }}
                    </h5>

                    <div class="accordion mb-4" id="accordion-{{ $day->id }}-{{ $category }}">

                        @foreach ($sessions->groupBy('activity_id') as $activitySessions)
                            @php
                                $activity = $activitySessions->first()->activity;
                                $session = $activitySessions->first();
                                $collapseId = 'collapse-' . $day->id . '-' . $activity->id;
                            @endphp

                            <div class="accordion-item mb-2 border-0 program-item" data-activity-title="{{ strtolower($activity->title) }}" data-topics="{{ strtolower($activity->topics->pluck('title')->join(' ')) }}">

                                <h2 class="accordion-header">

                                    <button class="accordion-button collapsed session-header" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">

                                        <span class="me-2">+</span>
                                        {{ $activity->title }}


                                    </button>

                                </h2>

                                <div id="{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#accordion-{{ $day->id }}-{{ $category }}">

                                    <div class="accordion-body session-detail">

                                        {{-- SESSION META --}}
                                        <div class="mb-3 text-muted small">
                                            {{ $day->date->format('d M Y') }}
                                            &nbsp; • &nbsp;
                                            {{ $session->start_time->format('H:i') }} – {{ $session->end_time->format('H:i') }}
                                            &nbsp; • &nbsp;
                                            {{ $session->room->name }}
                                        </div>

                                        {{-- TOPICS --}}
                                        <table class="table table-sm align-middle">
                                            <tbody>
                                                @foreach ($activity->topics as $topic)
                                                    <tr>
                                                        <td style="width:160px" class="fw-semibold">
                                                            {{ ucfirst($topic->type) }}
                                                        </td>
                                                        <td>
                                                            {{ $topic->title }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endforeach

        </div>
    </section>

@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('programSearch');

            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase().trim();

                const days = document.querySelectorAll('.program-day');

                days.forEach(dayTitle => {

                    let dayHasVisible = false;
                    let current = dayTitle.nextElementSibling;

                    while (current && !current.classList.contains('program-day')) {

                        if (current.classList.contains('program-category')) {
                            let categoryHasVisible = false;
                            let catNext = current.nextElementSibling;

                            while (catNext && !catNext.classList.contains('program-category') && !catNext.classList.contains('program-day')) {

                                if (catNext.classList.contains('accordion')) {

                                    catNext.querySelectorAll('.program-item').forEach(item => {
                                        const title = item.dataset.activityTitle;
                                        const topics = item.dataset.topics;

                                        const match =
                                            title.includes(keyword) ||
                                            topics.includes(keyword);

                                        item.style.display = match ? '' : 'none';

                                        if (match) {
                                            categoryHasVisible = true;
                                            dayHasVisible = true;
                                        }
                                    });
                                }

                                catNext = catNext.nextElementSibling;
                            }

                            current.style.display = categoryHasVisible ? '' : 'none';
                        }

                        current = current.nextElementSibling;
                    }

                    dayTitle.style.display = dayHasVisible ? '' : 'none';
                });
            });
        });
    </script>
@endpush
