@extends('dashboard._layouts.app')

@section('title', 'Submission Locked')
@section('page-title', 'Submission')

@section('content')

    <div class="row justify-content-center">

        <div class="col-lg-6 col-md-8">

            <div class="card shadow-sm text-center">

                <div class="card-body py-5">

                    {{-- ICON --}}
                    <div class="mb-3">
                        <i class="bi bi-lock-fill text-danger" style="font-size: 48px;"></i>
                    </div>

                    {{-- TITLE --}}
                    <h5 class="fw-semibold mb-2">
                        Submission Locked
                    </h5>

                    {{-- DESCRIPTION --}}
                    <p class="text-muted mb-4">
                        You need to complete your event registration before submitting
                        an abstract or case report.
                    </p>

                    {{-- ACTION --}}
                    <a href="{{ route('dashboard.buy-package') }}" class="btn btn-danger px-4">
                        Buy Package →
                    </a>

                </div>
            </div>

        </div>

    </div>

@endsection
