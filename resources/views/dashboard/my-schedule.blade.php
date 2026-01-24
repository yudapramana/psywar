@extends('dashboard._layouts.app')

@section('title', 'My Schedule')
@section('page-title', 'My Schedule')

@section('content')

    <div class="row g-4">

        {{-- WORKSHOP --}}
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">
                    My Workshops
                </div>

                <div class="card-body">
                    @if ($workshops->isEmpty())
                        <p class="text-muted mb-0">No workshop registered.</p>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($workshops as $item)
                                @foreach ($item->activity->sessions as $session)
                                    <div class="list-group-item px-0">
                                        <div class="fw-semibold">
                                            {{ $item->activity->code }} — {{ $item->activity->title }}
                                        </div>

                                        <div class="small text-muted">
                                            📅 {{ $session->eventDay->label ?? $session->eventDay->date }}
                                            |
                                            🕒 {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
                                            –
                                            {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                                            |
                                            📍 {{ $session->room->name }}
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- SYMPOSIUM --}}
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">
                    My Symposiums
                </div>

                <div class="card-body">
                    @if ($symposiums->isEmpty())
                        <p class="text-muted mb-0">No symposium registered.</p>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($symposiums as $item)
                                @foreach ($item->activity->sessions as $session)
                                    <div class="list-group-item px-0">
                                        <div class="fw-semibold">
                                            {{ $item->activity->code }} — {{ $item->activity->title }}
                                        </div>

                                        <div class="small text-muted">
                                            📅 {{ $session->eventDay->label ?? $session->eventDay->date }}
                                            |
                                            🕒 {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
                                            –
                                            {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                                            |
                                            📍 {{ $session->room->name }}
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection
