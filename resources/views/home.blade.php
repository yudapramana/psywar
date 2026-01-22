@extends('layouts.dashboard')

@section('title', 'My Schedule')
@section('page-title', 'My Schedule')

@section('content')

    <div class="row g-4">

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-2">My Workshop</h5>
                    <p class="text-muted mb-0">No Workshop Here</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-2">My Symposium</h5>
                    <p class="text-muted mb-0">No Symposium Here</p>
                </div>
            </div>
        </div>

    </div>

@endsection
