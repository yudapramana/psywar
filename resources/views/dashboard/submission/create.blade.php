@extends('dashboard._layouts.app')

@section('title', 'Submit Paper')
@section('page-title', 'Submission')

@section('content')

    <style>
        .btn-loading {
            pointer-events: none;
            opacity: .8;
        }
    </style>

    @php
        $participant = auth()->user()->participant;
    @endphp


    <div class="row g-4">

        {{-- LEFT : FORM --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">

                {{-- HEADER --}}
                <div class="card-header fw-semibold">
                    Submit Abstract / Case
                </div>

                {{-- BODY --}}
                <div class="card-body">

                    <form id="submissionForm" method="POST" action="{{ route('dashboard.submission.store') }}" enctype="multipart/form-data">

                        @csrf

                        {{-- SUBMISSION TYPE --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Submission Type
                            </label>

                            <select name="paper_type_id" class="form-select @error('paper_type_id') is-invalid @enderror" required>
                                <option value="">— Select Type —</option>
                                @foreach ($paperTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('paper_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="form-text">
                                Choose the appropriate submission category based on guideline.
                            </div>

                            @error('paper_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- TITLE --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Paper Title
                            </label>

                            <textarea type="text" name="title" maxlength="300" class="form-control @error('title') is-invalid @enderror" placeholder="Enter paper title (max 300 characters)" required>{{ old('title') }}</textarea>

                            <div class="form-text">
                                Title must not exceed 300 characters.
                            </div>

                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ABSTRACT --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Abstract / Case Summary
                            </label>

                            <textarea name="abstract" id="abstract" rows="10" class="form-control @error('abstract') is-invalid @enderror" placeholder="Write an abstract or case summary (maximum 300 words)" required>{{ old('abstract') }}</textarea>

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

                                {{-- AUTHOR ITEM --}}
                                <div class="border rounded-3 p-3 author-item bg-light">

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-semibold small">Author #1</span>

                                        {{-- USE MY DATA BUTTON --}}
                                        <button type="button" id="useMyDataBtn" class="btn btn-outline-primary btn-sm">
                                            Use My Data
                                        </button>
                                    </div>

                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label small">
                                                Author Name
                                            </label>
                                            <input type="text" id="author0Name" name="authors[0][name]" class="form-control" placeholder="Full name" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small">
                                                Affiliation
                                            </label>
                                            <input type="text" id="author0Affiliation" name="authors[0][affiliation]" class="form-control" placeholder="Institution / University">
                                        </div>
                                    </div>

                                    <div class="d-flex gap-4 mt-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="authors[0][is_corresponding]" value="1" checked>
                                            <label class="form-check-label small">
                                                Corresponding Author
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="authors[0][is_presenting]" value="1" checked>
                                            <label class="form-check-label small">
                                                Presenting Author
                                            </label>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <button type="button" id="addAuthorBtn" class="btn btn-outline-secondary btn-sm mt-3">
                                + Add Author
                            </button>

                            <div class="form-text mt-1">
                                Add all contributing authors.
                                First author is assumed as corresponding & presenting by default.
                            </div>
                        </div>

                        {{-- FILE (GOOGLE DRIVE LINK) --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Google Drive File Link
                            </label>

                            <input type="url" name="gdrive_link" class="form-control @error('gdrive_link') is-invalid @enderror" placeholder="https://drive.google.com/..." value="{{ old('gdrive_link') }}" required>

                            <div class="form-text">
                                Upload your manuscript to Google Drive and paste the link here.<br>
                                Make sure access is set to <b>Anyone with the link → Viewer</b>.
                            </div>

                            @error('gdrive_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- ACTION --}}
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" id="saveBtn" class="btn btn-danger">
                                Save Paper
                            </button>

                            <a href="{{ route('dashboard.submission.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        {{-- RIGHT : GUIDELINES --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">
                    Submission Guidelines
                </div>

                <div class="card-body small text-muted">
                    <ul class="mb-0">
                        <li>Submission is available only for <b>paid participants</b>.</li>
                        <li>
                            Title is limited to <b>300 characters</b>,
                            abstract is limited to <b>300 words</b>.
                        </li>

                        <li>Accepted formats: <b>PDF / DOC / DOCX</b>.</li>
                        <li>Submitted papers will undergo editorial review.</li>
                        <li>Status can be tracked in your dashboard.</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* ===============================
             * WORD-LIKE CHAR COUNTER
             * =============================== */
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

            /* ===============================
             * DYNAMIC AUTHORS
             * =============================== */
            let authorIndex = 1;
            const wrapper = document.getElementById('authorsWrapper');
            const addBtn = document.getElementById('addAuthorBtn');

            addBtn?.addEventListener('click', () => {
                const div = document.createElement('div');
                div.className = 'border rounded-3 p-3 author-item bg-light';

                div.innerHTML = `
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

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex gap-4">
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

                <button type="button"
                        class="btn btn-outline-danger btn-sm remove-author">
                    Remove
                </button>
            </div>
        `;

                wrapper.appendChild(div);
                authorIndex++;
            });

            wrapper?.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-author')) {
                    e.target.closest('.author-item')?.remove();
                }
            });

            /* ===============================
             * ANTI DOUBLE SUBMIT
             * =============================== */
            const form = document.getElementById('submissionForm');
            const saveBtn = document.getElementById('saveBtn');

            form?.addEventListener('submit', (e) => {
                if (form.dataset.submitted === 'true') {
                    e.preventDefault();
                    return;
                }

                form.dataset.submitted = 'true';
                saveBtn.disabled = true;
                saveBtn.classList.add('btn-loading');
                saveBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Saving...
        `;
            });

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const useMyDataBtn = document.getElementById('useMyDataBtn')
            const nameInput = document.getElementById('author0Name')
            const affiliationInput = document.getElementById('author0Affiliation')

            // data dari backend (AMAN karena readonly di blade)
            const participantName = @json($participant?->full_name);
            const participantAffiliation = @json($participant?->institution);

            useMyDataBtn?.addEventListener('click', () => {
                if (participantName) {
                    nameInput.value = participantName
                }

                if (participantAffiliation) {
                    affiliationInput.value = participantAffiliation
                }
            })

        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const wrapper = document.getElementById('authorsWrapper');

            /**
             * =====================================================
             * ONLY ONE CORRESPONDING & PRESENTING AUTHOR
             * =====================================================
             */
            wrapper?.addEventListener('change', (e) => {

                const target = e.target;

                // -------------------------------
                // Corresponding Author (1 only)
                // -------------------------------
                if (target.matches('input[type="checkbox"][name$="[is_corresponding]"]')) {

                    if (target.checked) {
                        wrapper
                            .querySelectorAll('input[name$="[is_corresponding]"]')
                            .forEach(cb => {
                                if (cb !== target) cb.checked = false;
                            });
                    }
                }

                // -------------------------------
                // Presenting Author (1 only)
                // -------------------------------
                if (target.matches('input[type="checkbox"][name$="[is_presenting]"]')) {

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
@endpush
