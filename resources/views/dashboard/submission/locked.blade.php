@extends('dashboard._layouts.app')

@section('title', 'Submission Locked')
@section('page-title', 'Submission')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm mt-4">
                <div class="card-body text-center py-5">

                    <h4 class="fw-bold mb-2">
                        Submission Unavailable
                    </h4>

                    @if ($submissionStatus === 'not_open')
                        <p class="text-muted">
                            Submission has not opened yet.<br>
                            It will open on
                            <strong>
                                {{ $event->submission_open_at?->format('d F Y') }}
                            </strong>.
                        </p>
                    @else
                        <p class="text-muted">
                            Submission period has ended.<br>
                            Deadline was
                            <strong>
                                {{ $event->submission_deadline_at?->format('d F Y') }}
                            </strong>.
                        </p>
                    @endif

                    <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary mt-3">
                        ← Back to Dashboard
                    </a>

                </div>
            </div>

        </div>
    </div>

@endsection
