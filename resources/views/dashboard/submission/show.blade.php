@extends('dashboard._layouts.app')

@section('title', 'Submission Detail')
@section('page-title', 'Submission')

@section('content')

    <div class="row g-4">

        {{-- LEFT : PAPER DETAIL --}}
        <div class="col-lg-8">

            <div class="card shadow-sm">

                {{-- HEADER --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Submission Detail</span>

                    <a href="{{ route('dashboard.submission.index') }}" class="btn btn-outline-secondary btn-sm">
                        ← Back
                    </a>
                </div>

                {{-- BODY --}}
                <div class="card-body">

                    {{-- TITLE --}}
                    <h5 class="fw-semibold mb-1">
                        {{ $paper->title }}
                    </h5>

                    <div class="small text-muted mb-3">
                        📝 {{ $paper->paperType->name }}
                        |
                        📌 Status:
                        <span class="text-capitalize fw-semibold">
                            {{ str_replace('_', ' ', $paper->status) }}
                        </span>
                        |
                        📅 Submitted:
                        {{ $paper->submitted_at?->format('d M Y, H:i') ?? '-' }}
                    </div>

                    {{-- ABSTRACT --}}
                    <div class="mb-4">
                        <div class="fw-semibold mb-2">
                            Abstract / Case Summary
                        </div>

                        <div class="p-3 border rounded-3 bg-white" style="text-align: justify; line-height: 1.7; font-size: 0.95rem;">
                            {{ $paper->abstract }}
                        </div>
                    </div>


                    {{-- FILE --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Submitted File
                        </label>

                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-secondary text-uppercase">
                                {{ $paper->file_type }}
                            </span>

                            <a href="{{ route('submissions.download', $paper) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                View / Download File →
                            </a>
                        </div>
                    </div>

                    @if ($paper->status === 'draft')
                        <div class="mt-3 d-flex gap-2">
                            <a href="{{ route('dashboard.submission.edit', $paper->id) }}" class="btn btn-outline-primary">
                                Edit Draft
                            </a>

                            <form method="POST" action="{{ route('dashboard.submission.submit', $paper->id) }}">
                                @csrf
                                <button class="btn btn-success" onclick="return confirm('Submit this paper?')">
                                    Submit Final
                                </button>
                            </form>
                        </div>
                    @endif


                </div>
            </div>

        </div>

        {{-- RIGHT : AUTHORS --}}
        <div class="col-lg-4">

            <div class="card shadow-sm">

                <div class="card-header fw-semibold">
                    Authors
                </div>

                <div class="card-body">

                    @if ($paper->authors->isEmpty())
                        <p class="text-muted mb-0 small">
                            No authors data available.
                        </p>
                    @else
                        <div class="list-group list-group-flush">

                            @foreach ($paper->authors->sortBy('order') as $author)
                                <div class="list-group-item px-0">

                                    <div class="fw-semibold">
                                        {{ $author->name }}
                                    </div>

                                    @if ($author->affiliation)
                                        <div class="small text-muted">
                                            {{ $author->affiliation }}
                                        </div>
                                    @endif

                                    <div class="mt-1 d-flex flex-wrap gap-1">
                                        @if ($author->is_corresponding)
                                            <span class="badge bg-primary">
                                                Corresponding
                                            </span>
                                        @endif

                                        @if ($author->is_presenting)
                                            <span class="badge bg-success">
                                                Presenting
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            @endforeach

                        </div>
                    @endif

                </div>
            </div>

        </div>

    </div>


    @push('scripts')
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Submission Complete',
                        text: @json(session('success')),
                        confirmButtonColor: '#198754'
                    });
                });
            </script>
        @endif
    @endpush




@endsection
