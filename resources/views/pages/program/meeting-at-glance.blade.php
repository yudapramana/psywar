@extends('layouts.app')

@section('title', 'Meeting at a Glance | SYMCARD 2026')

@section('content')

    <style>
        /* ================= BASIC ================= */
        .meeting-glance table {
            font-size: 13px;
            border-collapse: separate;
        }


        /* ================= FINAL SESSION BOX FIX ================= */
        .session-cell {
            position: relative;
            padding: 6px !important;
            /* sedikit diperbesar */
            vertical-align: top;
        }

        .session-cell>.session-box {
            position: absolute;
            inset: 6px;
            /* jarak aman dari border */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            /* center horizontal */
            text-align: center;

            border-radius: 6px;
            box-sizing: border-box;

            /* 🔑 ANTI OFFSIDE */
            overflow: hidden;
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
        }

        /* Judul */
        .session-box strong {
            font-size: 13px;
            line-height: 1.3;
            display: block;
            max-width: 100%;
        }

        .session-box strong {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }


        /* Subtitle & waktu */
        .session-box small {
            font-size: 11px;
            line-height: 1.25;
            opacity: 0.9;
            max-width: 100%;
        }


        /* ================= STICKY TIME ================= */
        .meeting-glance th.time-col,
        .meeting-glance td.time-col {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 3;
            min-width: 90px;
            font-weight: 600;
        }

        /* ================= LEGEND ================= */
        .legend {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
        }

        .legend-color {
            width: 14px;
            height: 14px;
            border-radius: 4px;
        }

        /* ================= PRINT ================= */
        @media print {
            @page {
                size: A3 landscape;
                margin: 10mm;
            }

            .nav,
            .page-title {
                display: none !important;
            }
        }
    </style>

    {{-- ================= PAGE TITLE ================= --}}
    <div class="page-title dark-background" style="
background-image:
linear-gradient(
    135deg,
    rgba(11, 28, 61, 0.47) 0%,
    rgba(18, 58, 130, 0.47) 45%,
    rgba(128, 20, 40, 0.47) 100%
),
url('{{ asset('projects/assets/img/symcardheadercontent/symcardheadercontent1.jpg') }}');
background-size: cover;
background-position: center;
">
        <div class="container position-relative">
            <h1>Meeting at a Glance</h1>
            <p>
                11<sup>th</sup> Padang Symposium on Cardiovascular Disease<br>
                Cardiology 360°: Integrating Knowledge, Technology, and Practice
            </p>
        </div>
    </div>

    <section class="section py-5">
        <div class="container">

            {{-- ================= LEGEND ================= --}}
            <div class="legend">
                <div class="legend-item"><span class="legend-color bg-warning"></span> Workshop</div>
                <div class="legend-item"><span class="legend-color bg-danger"></span> Symposium</div>
                <div class="legend-item"><span class="legend-color bg-primary"></span> Plenary</div>
                <div class="legend-item"><span class="legend-color bg-secondary"></span> Poster</div>
                <div class="legend-item"><span class="legend-color bg-success"></span> Jeopardy</div>
            </div>

            {{-- ================= DAY TABS ================= --}}
            <ul class="nav nav-tabs mb-4">
                @foreach ($days as $day)
                    <li class="nav-item">
                        <button class="nav-link @if ($loop->first) active @endif" data-bs-toggle="tab" data-bs-target="#day-{{ $day->id }}" type="button">
                            {{ \Carbon\Carbon::parse($day->date)->format('l, M d') }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">

                @foreach ($days as $day)
                    @php
                        /* ================= BUILD DYNAMIC TIMESLOTS ================= */
                        $boundaries = [];

                        foreach ($day->sessions as $s) {
                            $boundaries[] = \Carbon\Carbon::parse($s->start_time)->format('H:i');
                            $boundaries[] = \Carbon\Carbon::parse($s->end_time)->format('H:i');
                        }

                        $timeSlots = collect($boundaries)->unique()->sort()->values()->toArray();
                    @endphp

                    {{-- DEBUG --}}
                    <pre style="font-size:11px">
                        Day ID: {{ $day->id }}
                        Sessions count: {{ $day->sessions->count() }}

                        @foreach ($day->sessions as $s)
- Session ID: {{ $s->id }}
                        Room: {{ $s->room_id }}
                        Start: {{ $s->start_time }}
                        End: {{ $s->end_time }}
@endforeach
                        </pre>


                    <div class="tab-pane fade @if ($loop->first) show active @endif" id="day-{{ $day->id }}">

                        <div class="table-responsive meeting-glance">
                            <table class="table table-bordered text-center align-middle">

                                <thead class="table-light">
                                    <tr>
                                        <th class="time-col">Time</th>
                                        @foreach ($rooms as $room)
                                            <th style="min-width:200px">{{ $room->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($timeSlots as $i => $slot)
                                        @if (!isset($timeSlots[$i + 1]))
                                            @break
                                        @endif

                                        @php
                                            $slotStart = \Carbon\Carbon::parse($slot);
                                            $slotEnd = \Carbon\Carbon::parse($timeSlots[$i + 1]);
                                        @endphp

                                        <tr>
                                            <td class="time-col text-muted">
                                                {{ $slot }}<br>–<br>{{ $timeSlots[$i + 1] }}
                                            </td>

                                            @foreach ($rooms as $room)
                                                @php
                                                    $session = $day->sessions->first(function ($s) use ($room, $slotStart, $slotEnd) {
                                                        if ($s->room_id !== $room->id) {
                                                            return false;
                                                        } else {
                                                            echo 's->room_id:' . $s->room_id . ', room->id: ' . $room->id . '<br>';
                                                        }
                                                        echo gettype($s->room_id) . ' | ' . gettype($room->id);

                                                        $ss = \Carbon\Carbon::parse($s->start_time)->format('H:i');
                                                        $se = \Carbon\Carbon::parse($s->end_time)->format('H:i');

                                                        $slotS = $slotStart->format('H:i');
                                                        $slotE = $slotEnd->format('H:i');

                                                        return $ss < $slotE && $se > $slotS;
                                                    });

                                                    if (!$session) {
                                                        echo '<td></td>';
                                                        continue;
                                                    }

                                                    $sessionStart = \Carbon\Carbon::parse($session->start_time);
                                                    $sessionEnd = \Carbon\Carbon::parse($session->end_time);

                                                    // FIRST OVERLAPPING SLOT ONLY
                                                    $isFirstSlot = true;
                                                    foreach ($timeSlots as $j => $t) {
                                                        if (!isset($timeSlots[$j + 1])) {
                                                            break;
                                                        }

                                                        $ts = \Carbon\Carbon::parse($t);
                                                        $te = \Carbon\Carbon::parse($timeSlots[$j + 1]);

                                                        if ($ts < $sessionEnd && $te > $sessionStart) {
                                                            $isFirstSlot = $slotStart->equalTo($ts);
                                                            break;
                                                        }
                                                    }

                                                    if (!$isFirstSlot) {
                                                        continue;
                                                    }

                                                    // ROWSPAN
                                                    $rowspan = 0;
                                                    foreach ($timeSlots as $j => $t) {
                                                        if (!isset($timeSlots[$j + 1])) {
                                                            break;
                                                        }

                                                        $ts = \Carbon\Carbon::parse($t);
                                                        $te = \Carbon\Carbon::parse($timeSlots[$j + 1]);

                                                        if ($ts < $sessionEnd && $te > $sessionStart) {
                                                            $rowspan++;
                                                        }
                                                    }

                                                    $category = $session->activity->category;
                                                    $color = match ($category) {
                                                        'workshop' => 'bg-warning',
                                                        'symposium' => 'bg-danger',
                                                        'plenary' => 'bg-primary',
                                                        'poster' => 'bg-secondary',
                                                        'jeopardy' => 'bg-success',
                                                        default => 'bg-light',
                                                    };
                                                @endphp

                                                <td rowspan="{{ $rowspan }}" class="session-cell">
                                                    <div class="session-box {{ $color }}">
                                                        <strong>{{ $session->activity->title }}</strong>
                                                        <small>{{ ucfirst($category) }}</small>
                                                        <small>{{ $sessionStart->format('H:i') }} – {{ $sessionEnd->format('H:i') }}</small>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                    </div>
                @endforeach

            </div>
        </div>
    </section>
@endsection
