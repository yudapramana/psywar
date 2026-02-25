@extends('dashboard._layouts.app')

@section('title', 'Registration Required')
@section('page-title', 'Submission')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm mt-4">
                <div class="card-body text-center py-5">

                    <h4 class="fw-bold mb-2">
                        Registration Package Required
                    </h4>

                    <p class="text-muted">
                        You have not purchased a registration package yet.<br>
                        Please complete your registration before accessing submission.
                    </p>

                    <div class="mt-3">
                        <a href="{{ route('dashboard.buy-package') }}" class="btn btn-primary">
                            Buy Registration Package
                        </a>

                        <a href="{{ route('dashboard.my-package') }}" class="btn btn-outline-secondary ms-2">
                            ← Back to Package
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
