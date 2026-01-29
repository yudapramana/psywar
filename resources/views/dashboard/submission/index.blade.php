@extends('dashboard._layouts.app')

@section('title', 'My Submissions')
@section('page-title', 'Submission')

@section('content')

    <div class="row g-4">

        <div class="col-12">
            <div class="card shadow-sm">

                {{-- HEADER --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="fw-semibold">
                        My Submissions
                        <span class="text-muted small">
                            ({{ $submissionCount }}/3)
                        </span>
                    </div>

                    @if ($submissionCount < 3)
                        <a href="{{ route('dashboard.submission.create') }}" class="btn btn-danger btn-sm">
                            + Submit Abstract / Case
                        </a>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled>
                            Submission Limit Reached
                        </button>
                    @endif
                </div>


                {{-- BODY --}}
                <div class="card-body p-0">

                    @if ($papers->isEmpty())
                        <div class="p-4 text-center text-muted">
                            <div class="fw-semibold mb-1">
                                No submissions yet
                            </div>
                            <div class="small">
                                Start by submitting your abstract or case.
                            </div>
                        </div>
                    @else
                        @php
                            $statusColors = [
                                'draft' => 'secondary',
                                'submitted' => 'primary',
                                'under_review' => 'warning',
                                'accepted' => 'success',
                                'rejected' => 'danger',
                                'withdrawn' => 'dark',
                            ];
                        @endphp

                        <div class="divide-y">

                            {{-- @if ($submissionCount >= 3)
                                <div class="alert alert-warning m-3 small">
                                    You have reached the maximum of <b>3 submissions</b>.
                                    You cannot create a new submission unless one is deleted.
                                </div>
                            @endif --}}


                            @foreach ($papers as $paper)
                                <div class="px-4 py-3 border-bottom">

                                    <div class="d-flex justify-content-between align-items-start gap-3">

                                        {{-- LEFT --}}
                                        <div class="flex-grow-1">

                                            {{-- TITLE + PREVIEW (INLINE, NEMPEL) --}}
                                            <div class="fw-semibold">
                                                {{ $paper->title }}<a href="{{ route('submissions.download', $paper) }}" target="_blank" class="text-muted" title="Preview file" style="text-decoration: none;" class="text-muted ms-1" title="Preview file" onmouseover="this.style.color='#dc3545'" onmouseout="this.style.color=''">📄</a>
                                            </div>



                                            {{-- AUTHORS (ONE LINE, CLEAN) --}}
                                            <div class="small text-muted mb-1">
                                                Authors: {{ $paper->authors->sortBy('order')->pluck('name')->implode(', ') }}
                                            </div>


                                            {{-- META --}}
                                            <div class="d-flex flex-wrap gap-1 mt-1 align-items-center">
                                                {{-- PAPER TYPE --}}
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                                    {{ $paper->paperType->name }}
                                                </span>

                                                {{-- DATE --}}
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 small">
                                                    {{ $paper->submitted_at?->format('d M Y') ?? 'Not submitted yet' }}
                                                </span>
                                            </div>




                                        </div>

                                        {{-- RIGHT --}}
                                        <div class="text-end d-flex flex-column align-items-end gap-2">

                                            {{-- STATUS --}}
                                            <span class="badge bg-{{ $statusColors[$paper->status] ?? 'secondary' }}">
                                                {{ str_replace('_', ' ', ucfirst($paper->status)) }}
                                            </span>

                                            {{-- ACTION BUTTONS (HORIZONTAL) --}}
                                            <div class="d-flex gap-1 flex-wrap justify-content-end">

                                                @if ($paper->status === 'draft')
                                                    <a href="{{ route('dashboard.submission.edit', $paper->id) }}" class="btn btn-outline-primary btn-sm">
                                                        Edit
                                                    </a>

                                                    <form method="POST" action="{{ route('dashboard.submission.submit', $paper->id) }}" class="submit-paper-form d-inline" data-title="{{ $paper->title }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm submit-paper-btn">
                                                            Submit
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('dashboard.submission.show', $paper->id) }}" class="btn btn-outline-secondary btn-sm">
                                                        View
                                                    </a>
                                                @endif

                                            </div>
                                        </div>


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
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                let locked = false;

                document.querySelectorAll('.submit-paper-form').forEach(form => {

                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        if (locked) return;

                        const title = this.dataset.title ?? 'this paper';
                        const button = this.querySelector('.submit-paper-btn');

                        Swal.fire({
                            title: 'Submit Paper?',
                            html: `
                    <div class="text-start small">
                        <p class="mb-2">
                            You are about to submit:
                        </p>
                        <b>${title}</b>
                        <hr class="my-2">
                        <p class="text-danger mb-0">
                            After submission, editing will be <b>locked</b>.
                        </p>
                    </div>
                `,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, Submit',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#198754',
                            reverseButtons: true
                        }).then(result => {

                            if (!result.isConfirmed) return;

                            // lock global
                            locked = true;

                            // disable ALL submit buttons
                            document.querySelectorAll('.submit-paper-btn').forEach(btn => {
                                btn.disabled = true;
                            });

                            // loading state
                            button.innerHTML = `
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Submitting...
                            `;

                            form.submit();
                        });
                    });

                });

            });
        </script>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: @json(session('success')),
                        confirmButtonColor: '#dc3545'
                    });
                });
            </script>
        @endif

        @if (session('warning'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: @json(session('warning')),
                        confirmButtonColor: '#ffc107'
                    });
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: `{!! implode('<br>', $errors->all()) !!}`,
                        confirmButtonColor: '#dc3545'
                    });
                });
            </script>
        @endif
    @endpush


@endsection
