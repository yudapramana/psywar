@extends('layouts.app')

@section('title', 'Program & Resources | SYMCARD 2026')

@section('content')

    {{-- ================= PAGE TITLE ================= --}}
    <div class="page-title dark-background" style="
        background-image:
        linear-gradient(
            135deg,
            rgba(11, 28, 61, 0.47) 0%,
            rgba(18, 58, 130, 0.47) 45%,
            rgba(128, 20, 40, 0.47) 100%
        ),
        url('{{ asset('projects/assets/img/symcardheadercontent/symcardheadercontent5.jpg') }}');
        background-size: cover;
        background-position: center;
    ">
        <div class="container position-relative">
            <h1>Program & Resources</h1>
            <p>
                11<sup>th</sup> Padang Symposium on Cardiovascular Disease<br>
                Download Program Book, Paper & Presentation Templates
            </p>

            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('landing') }}">Home</a></li>
                    <li class="current">Program & Resources</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- /PAGE TITLE --}}

    {{-- ================= MAIN CONTENT ================= --}}
    <section class="section py-5">
        <div class="container">

            {{-- SEARCH --}}
            <div class="mb-4">
                <input type="text" id="resourceSearch" class="form-control w-50" placeholder="Search resources…">
            </div>

            {{-- ================= CATEGORY ================= --}}
            @php
                $resources = [
                    'Program Book' => [
                        [
                            'title' => 'SYMCARD 2026 Program Book',
                            'desc' => 'Complete scientific program and schedule',
                            'file' => 'program-book-symcard-2026.pdf',
                        ],
                        [
                            'title' => 'Daily Schedule',
                            'desc' => 'Daily rundown of symposium and workshops',
                            'file' => 'daily-schedule-symcard-2026.pdf',
                        ],
                    ],

                    'Paper & Case Template' => [
                        [
                            'title' => 'Full Paper Template',
                            'desc' => 'Template for scientific paper submission',
                            'file' => 'paper-template-symcard.docx',
                        ],
                        [
                            'title' => 'Abstract Template',
                            'desc' => 'Abstract submission template',
                            'file' => 'abstract-template-symcard.docx',
                        ],
                        [
                            'title' => 'Case Report Template',
                            'desc' => 'Clinical case report format',
                            'file' => 'case-report-template-symcard.docx',
                        ],
                    ],

                    'Presentation Template' => [
                        [
                            'title' => 'Oral Presentation Template',
                            'desc' => 'PowerPoint template for oral presenters',
                            'file' => 'oral-presentation-template.pptx',
                        ],
                        [
                            'title' => 'Poster Presentation Template',
                            'desc' => 'Poster format template',
                            'file' => 'poster-template-symcard.pptx',
                        ],
                    ],
                ];
            @endphp

            {{-- ================= LOOP ================= --}}
            @foreach ($resources as $category => $items)
                <h4 class="fw-bold mt-5 mb-3 resource-category">
                    {{ $category }}
                </h4>

                <div class="accordion mb-4" id="accordion-{{ Str::slug($category) }}">

                    @foreach ($items as $index => $item)
                        @php
                            $collapseId = 'collapse-' . Str::slug($category) . '-' . $index;
                        @endphp

                        <div class="accordion-item mb-2 border-0 resource-item" data-title="{{ strtolower($item['title']) }}">

                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed session-header" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">

                                    <span class="me-2">+</span>
                                    {{ $item['title'] }}
                                </button>
                            </h2>

                            <div id="{{ $collapseId }}" class="accordion-collapse collapse" data-bs-parent="#accordion-{{ Str::slug($category) }}">

                                <div class="accordion-body session-detail">

                                    <p class="mb-3">
                                        {{ $item['desc'] }}
                                    </p>

                                    <a href="{{ asset('downloads/' . $item['file']) }}" class="btn btn-primary" download>
                                        <i class="bi bi-download"></i>
                                        Download File
                                    </a>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

        </div>
    </section>

@endsection

{{-- ================= SEARCH SCRIPT ================= --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('resourceSearch');

            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase().trim();

                document.querySelectorAll('.resource-item').forEach(item => {
                    const title = item.dataset.title;
                    item.style.display = title.includes(keyword) ? '' : 'none';
                });

                document.querySelectorAll('.resource-category').forEach(category => {
                    const nextAccordion = category.nextElementSibling;
                    const hasVisible = nextAccordion.querySelector('.resource-item:not([style*="display: none"])');
                    category.style.display = hasVisible ? '' : 'none';
                });
            });

        });
    </script>
@endpush
