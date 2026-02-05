@extends('dashboard._layouts.app')

@section('title', 'Edit Draft Submission')
@section('page-title', 'Submission')

@section('content')

    <div class="row g-4">

        {{-- FORM --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">

                {{-- HEADER --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Edit Draft Submission</span>

                    <a href="{{ route('dashboard.submission.index') }}" class="btn btn-outline-secondary btn-sm">
                        ← Back
                    </a>
                </div>

                <div class="card-body">

                    {{-- LOCK WARNING --}}
                    @if ($paper->status !== 'draft')
                        <div class="alert alert-warning">
                            This submission has been
                            <b>{{ str_replace('_', ' ', $paper->status) }}</b>
                            and can no longer be edited.
                        </div>
                    @endif

                    <form id="editSubmissionForm" method="POST" action="{{ route('dashboard.submission.update', $paper->uuid) }}" enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        {{-- SUBMISSION TYPE --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Submission Type
                            </label>

                            <select name="paper_type_id" class="form-select" {{ $paper->status !== 'draft' ? 'disabled' : '' }}>
                                @foreach ($paperTypes as $type)
                                    <option value="{{ $type->id }}" {{ $paper->paper_type_id == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TITLE --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Paper Title
                            </label>

                            <textarea type="text" name="title" maxlength="300" class="form-control" {{ $paper->status !== 'draft' ? 'readonly' : '' }}>{{ $paper->title }}</textarea>

                            <div class="form-text">
                                Maximum 300 characters
                            </div>
                        </div>

                        {{-- ABSTRACT --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Abstract / Case Summary
                            </label>

                            <textarea name="abstract" id="abstract" rows="10" class="form-control @error('abstract') is-invalid @enderror" {{ $paper->status !== 'draft' ? 'readonly' : '' }} required>{{ old('abstract', $paper->abstract) }}</textarea>

                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">
                                    Maximum 300 words
                                </small>
                                <small class="text-muted">
                                    <span id="wordCount">0</span>/300 words
                                </small>
                            </div>

                            @error('abstract')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                        {{-- AUTHORS --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Authors
                            </label>

                            <div id="authorsWrapper" class="d-flex flex-column gap-3">

                                @foreach ($paper->authors->sortBy('order')->values() as $i => $author)
                                    <div class="border rounded-3 p-3 author-item bg-light">

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-semibold small">Author #{{ $i + 1 }}</span>

                                            @if ($paper->status === 'draft' && $i > 0)
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-author">
                                                    Remove
                                                </button>
                                            @endif
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label small">Author Name</label>
                                                <input type="text" name="authors[{{ $i }}][name]" class="form-control" value="{{ $author->name }}" {{ $paper->status !== 'draft' ? 'readonly' : '' }} required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label small">Affiliation</label>
                                                <input type="text" name="authors[{{ $i }}][affiliation]" class="form-control" value="{{ $author->affiliation }}" {{ $paper->status !== 'draft' ? 'readonly' : '' }}>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-4 mt-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="authors[{{ $i }}][is_corresponding]" value="1" {{ $author->is_corresponding ? 'checked' : '' }} {{ $paper->status !== 'draft' ? 'disabled' : '' }}>
                                                <label class="form-check-label small">
                                                    Corresponding Author
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="authors[{{ $i }}][is_presenting]" value="1" {{ $author->is_presenting ? 'checked' : '' }} {{ $paper->status !== 'draft' ? 'disabled' : '' }}>
                                                <label class="form-check-label small">
                                                    Presenting Author
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach

                            </div>

                            @if ($paper->status === 'draft')
                                <button type="button" id="addAuthorBtn" class="btn btn-outline-secondary btn-sm mt-3">
                                    + Add Author
                                </button>
                            @endif

                            <div class="form-text mt-1">
                                Author list is locked after submission.
                            </div>
                        </div>


                        {{-- FILE INFO --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Uploaded Manuscript
                            </label>

                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('submissions.download', $paper) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    View / Download File →
                                </a>
                            </div>
                        </div>

                        {{-- ACTION --}}
                        @if ($paper->status === 'draft')
                            <div class="d-flex gap-2">
                                <button type="submit" id="saveDraftBtn" class="btn btn-primary">
                                    Save Draft
                                </button>


                                {{-- <form id="finalSubmitForm" method="POST" action="{{ route('dashboard.submission.submit', $paper->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" id="finalSubmitBtn" class="btn btn-success">
                                        Submit Final
                                    </button>
                                </form> --}}

                            </div>
                        @endif

                    </form>

                </div>
            </div>
        </div>

        {{-- INFO --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">
                    Submission Notes
                </div>

                <div class="card-body small text-muted">
                    <ul class="mb-0">
                        <li>Only <b>draft</b> submissions can be edited.</li>
                        <li>Once submitted, editing will be locked.</li>
                        <li>Please review carefully before final submission.</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                const editForm = document.getElementById('editSubmissionForm');
                const finalForm = document.getElementById('finalSubmitForm');

                const saveBtn = document.getElementById('saveDraftBtn');
                const finalBtn = document.getElementById('finalSubmitBtn');

                let locked = false;

                /* ===============================
                 * SAVE DRAFT LOCK
                 * =============================== */
                editForm?.addEventListener('submit', (e) => {

                    if (locked) {
                        e.preventDefault();
                        return false;
                    }

                    locked = true;
                    editForm.dataset.submitted = 'true';

                    saveBtn.disabled = true;
                    finalBtn && (finalBtn.disabled = true);

                    saveBtn.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2"></span>
                        Saving...
                    `;
                });

                /* ===============================
                 * FINAL SUBMIT LOCK + CONFIRM
                 * =============================== */
                finalForm?.addEventListener('submit', (e) => {

                    if (locked) {
                        e.preventDefault();
                        return false;
                    }

                    const ok = confirm(
                        'Submit this paper?\n\nYou will NOT be able to edit after submission.'
                    );

                    if (!ok) {
                        e.preventDefault();
                        return false;
                    }

                    locked = true;
                    finalForm.dataset.submitted = 'true';

                    finalBtn.disabled = true;
                    saveBtn && (saveBtn.disabled = true);

                    finalBtn.innerHTML = `
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Submitting...
                        `;
                });

            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {

                const abstract = document.getElementById('abstract');
                const counter = document.getElementById('wordCount');
                const MAX_WORD = 300;

                function countWords(text) {
                    if (!text) return 0;

                    return text
                        .trim()
                        .replace(/\s+/g, ' ')
                        .split(' ')
                        .filter(word => word.length > 0)
                        .length;
                }

                function updateWordCounter() {
                    const words = countWords(abstract.value);
                    counter.textContent = words;

                    if (words > MAX_WORD) {
                        counter.classList.add('text-danger');
                        abstract.setCustomValidity('Abstract exceeds 300 words.');
                    } else {
                        counter.classList.remove('text-danger');
                        abstract.setCustomValidity('');
                    }
                }

                if (abstract && counter) {
                    updateWordCounter();
                    abstract.addEventListener('input', updateWordCounter);
                }

            });
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', () => {

                let authorIndex = {{ $paper->authors->count() }};
                const wrapper = document.getElementById('authorsWrapper');
                const addBtn = document.getElementById('addAuthorBtn');

                addBtn?.addEventListener('click', () => {
                    const div = document.createElement('div');
                    div.className = 'border rounded-3 p-3 author-item bg-light';

                    div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold small">Author #${authorIndex + 1}</span>
                <button type="button" class="btn btn-outline-danger btn-sm remove-author">
                    Remove
                </button>
            </div>

            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label small">Author Name</label>
                    <input type="text" name="authors[${authorIndex}][name]" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small">Affiliation</label>
                    <input type="text" name="authors[${authorIndex}][affiliation]" class="form-control">
                </div>
            </div>

            <div class="d-flex gap-4 mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           name="authors[${authorIndex}][is_corresponding]" value="1">
                    <label class="form-check-label small">Corresponding Author</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           name="authors[${authorIndex}][is_presenting]" value="1">
                    <label class="form-check-label small">Presenting Author</label>
                </div>
            </div>
        `;

                    wrapper.appendChild(div);
                    authorIndex++;
                });

                wrapper?.addEventListener('click', (e) => {
                    if (e.target.classList.contains('remove-author')) {
                        e.target.closest('.author-item').remove();
                    }
                });

            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {

                const wrapper = document.getElementById('authorsWrapper');

                /**
                 * =====================================================
                 * ONLY ONE CORRESPONDING & PRESENTING AUTHOR (EDIT)
                 * =====================================================
                 */
                wrapper?.addEventListener('change', (e) => {

                    const target = e.target;

                    // ==========================
                    // CORRESPONDING AUTHOR (1)
                    // ==========================
                    if (
                        target.matches('input[type="checkbox"]') &&
                        target.name.endsWith('[is_corresponding]')
                    ) {
                        if (target.checked) {
                            wrapper
                                .querySelectorAll('input[name$="[is_corresponding]"]')
                                .forEach(cb => {
                                    if (cb !== target) cb.checked = false;
                                });
                        }
                    }

                    // ==========================
                    // PRESENTING AUTHOR (1)
                    // ==========================
                    if (
                        target.matches('input[type="checkbox"]') &&
                        target.name.endsWith('[is_presenting]')
                    ) {
                        if (target.checked) {
                            wrapper
                                .querySelectorAll('input[name$="[is_presenting]"]')
                                .forEach(cb => {
                                    if (cb !== target) cb.checked = false;
                                });
                        }
                    }

                });

            });
        </script>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: @json(session('success')),
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });
                });
            </script>
        @endif
    @endpush


@endsection
