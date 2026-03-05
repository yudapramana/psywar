@extends('dashboard._layouts.package')

@section('title', 'Buy Package')

@section('content')

    <style>
        input[readonly],
        select:disabled {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        .auth-header {
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 1rem;
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #212529;
        }

        .auth-subtitle {
            font-size: .95rem;
            color: #6c757d;
        }

        .back-link {
            font-size: .9rem;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .auth-card {
            position: relative;
        }

        .bundling-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 0.85rem;
        }

        .bundling-badge {
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 6px;
            background: #dc3545;
            color: #fff;
            font-weight: 600;

            width: 72px;
            /* 🔒 KUNCI LEBAR */
            text-align: center;
            /* ⬅️ TEKS TENGAH */
            flex-shrink: 0;
            /* ⛔ JANGAN MENGECIL */
        }


        .bundling-time {
            font-size: 0.75rem;
            color: #6c757d;
        }
    </style>

    <div class="auth-card">

        {{-- LOADING OVERLAY --}}
        <div id="cardLoading" class="position-absolute top-0 start-0 w-100 h-100 d-none" style="
        background: rgba(255,255,255,.75);
        backdrop-filter: blur(2px);
        z-index: 10;
        border-radius: 16px;
     ">
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="text-center">
                    <div class="spinner-border text-danger mb-2"></div>
                    <div class="fw-semibold">Loading price…</div>
                </div>
            </div>
        </div>


        {{-- HEADER --}}
        <div class="auth-header mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <img src="{{ asset('projects/assets/img/symcardlogolong.png') }}" alt="Logo" height="41">

                <a href="{{ route('dashboard.my-package') }}" class="back-link">
                    ← Back to packages
                </a>
            </div>

            <h2 class="auth-title mb-1">Buy Package</h2>
            <p class="auth-subtitle mb-0">Choose your own package</p>
        </div>

        @include('dashboard._partials.payment-stepper', [
            'currentStep' => 'choose_package',
        ])

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('dashboard.buy-package.store') }}">
            @csrf

            {{-- PARTICIPANT CATEGORY (READ ONLY) --}}
            <div class="mb-3">
                <label class="form-label">Participant Category</label>
                <select class="form-select" disabled>
                    <option>
                        {{ auth()->user()->participant->participantCategory->name ?? '-' }}
                    </option>
                </select>

                {{-- value tetap dikirim ke backend --}}
                <input type="hidden" name="participant_category_id" value="{{ auth()->user()->participant->participant_category_id }}">
            </div>

            {{-- PACKAGE TYPE --}}
            <div class="mb-3">
                <label class="form-label">Package Type</label>
                <select id="packageType" name="package_type" class="form-select" required>

                    <option value="">Choose...</option>

                    <option value="1">
                        Symposium
                    </option>

                    @if ($hasWorkshopAvailable)
                        <option value="2">
                            Symposium + 1 Workshop
                        </option>
                    @endif

                    @if ($canTakeTwoWorkshops)
                        <option value="3">
                            Symposium + 2 Workshop
                        </option>
                    @endif

                </select>
            </div>


            @if (!$hasWorkshopAvailable)
                <div class="alert alert-warning small">
                    All workshops are fully booked. Only Symposium package is available.
                </div>
            @elseif (!$canTakeTwoWorkshops)
                <div class="alert alert-warning small">
                    Only 1 workshop package is available because either the morning or afternoon session is already full.
                </div>
            @endif

            {{-- SYMPOSIUM (FIXED INFO ONLY) --}}
            <div class="mb-3 d-none" id="symposiumSection">
                <label class="form-label">Symposium</label>

                <input type="text" class="form-control" value="All Symposium" readonly>
            </div>


            {{-- SINGLE WORKSHOP --}}
            <div class="mb-3 d-none" id="workshopSingle">
                <label class="form-label">Workshop</label>
                <select name="workshops[]" id="workshopSingleSelect" class="form-select">
                    <option value="">Choose Workshop</option>

                    @foreach ($workshops as $ws)
                        <option value="{{ $ws->id }}">
                            {{ $ws->code }} – {{ $ws->title }}
                            [{{ substr($ws->start_time, 0, 5) }} – {{ substr($ws->end_time, 0, 5) }}]
                            ({{ $ws->quota - $ws->used }} seats left)
                        </option>
                    @endforeach
                </select>
            </div>

            @php
                $morning = $workshops->filter(fn($w) => $w->start_time < '12:00:00')->values();

                $afternoon = $workshops->filter(fn($w) => $w->start_time >= '12:00:00')->values();
            @endphp

            {{-- DOUBLE WORKSHOP (MORNING & AFTERNOON) --}}
            <div class="mb-3 d-none" id="workshopDouble">
                <label class="form-label">Workshop (Choose 2)</label>

                {{-- MORNING --}}
                <select name="workshops[]" id="workshopMorningSelect" class="form-select mb-2">
                    <option value="">Choose Morning Workshop</option>
                    @foreach ($workshops as $ws)
                        @if ($ws->start_time < '12:00:00')
                            <option value="{{ $ws->id }}">
                                {{ $ws->code }} – {{ $ws->title }}
                                [{{ substr($ws->start_time, 0, 5) }} – {{ substr($ws->end_time, 0, 5) }}]
                                ({{ $ws->quota - $ws->used }} seats left)
                            </option>
                        @endif
                    @endforeach
                </select>

                {{-- AFTERNOON --}}
                <select name="workshops[]" id="workshopAfternoonSelect" class="form-select">
                    <option value="">Choose Afternoon Workshop</option>
                    @foreach ($workshops as $ws)
                        @if ($ws->start_time >= '12:00:00')
                            <option value="{{ $ws->id }}">
                                {{ $ws->code }} – {{ $ws->title }}
                                [{{ substr($ws->start_time, 0, 5) }} – {{ substr($ws->end_time, 0, 5) }}]
                                ({{ $ws->quota - $ws->used }} seats left)
                            </option>
                        @endif
                    @endforeach
                </select>

                <div class="form-text mt-1">
                    Select <b>1 morning</b> and <b>1 afternoon</b> workshop.
                </div>
            </div>





            {{-- PRICE --}}
            <div class="mb-4">
                <label class="form-label">Package Price</label>
                <input type="text" id="packagePrice" class="form-control" value="Rp -" readonly>
            </div>

            <button type="submit" class="btn btn-auth w-100" id="submitBtn">
                Continue →
            </button>

        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                const packageType = document.getElementById('packageType')
                const priceInput = document.getElementById('packagePrice')

                const symposium = document.getElementById('symposiumSection')
                const workshopSingle = document.getElementById('workshopSingle')
                const workshopDouble = document.getElementById('workshopDouble')

                const wsSingle = document.getElementById('workshopSingleSelect')
                const wsMorning = document.getElementById('workshopMorningSelect')
                const wsAfternoon = document.getElementById('workshopAfternoonSelect')

                const loading = document.getElementById('cardLoading')
                const form = document.querySelector('form')
                const submitBtn = document.getElementById('submitBtn')

                let locked = false

                /* ===============================
                 * SUBMIT WITH SWEETALERT CONFIRM
                 * =============================== */
                form.addEventListener('submit', function(e) {
                    e.preventDefault()

                    if (locked) return

                    // Build summary text
                    let workshopText = '-'
                    if (packageType.value === '2') {
                        workshopText = wsSingle.options[wsSingle.selectedIndex]?.text ?? '-'
                    }
                    if (packageType.value === '3') {
                        const m = wsMorning.options[wsMorning.selectedIndex]?.text ?? '-'
                        const a = wsAfternoon.options[wsAfternoon.selectedIndex]?.text ?? '-'
                        workshopText = `
                <div class="small">
                    <b>Morning:</b><br>${m}<br><br>
                    <b>Afternoon:</b><br>${a}
                </div>
            `
                    }

                    Swal.fire({
                        title: 'Confirm Package Purchase',
                        html: `
                <div class="text-start small">
                    <p><b>Package Type:</b><br>${packageType.options[packageType.selectedIndex].text}</p>
                    <p><b>Workshop:</b><br>${workshopText}</p>
                    <p><b>Price:</b><br>${priceInput.value}</p>
                    <hr>
                    <p class="text-danger mb-0">
                        After continuing, you will proceed to payment.
                    </p>
                </div>
            `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Continue',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#dc3545',
                        reverseButtons: true
                    }).then(result => {
                        if (!result.isConfirmed) return

                        locked = true

                        submitBtn.disabled = true
                        submitBtn.innerHTML = 'Processing…'

                        form.submit()
                    })
                })

                /* ===============================
                 * RESET SECTION
                 * =============================== */
                const resetSections = () => {
                    symposium.classList.add('d-none')
                    workshopSingle.classList.add('d-none')
                    workshopDouble.classList.add('d-none')

                    wsSingle.required = false
                    wsMorning.required = false
                    wsAfternoon.required = false

                    wsSingle.disabled = true
                    wsMorning.disabled = true
                    wsAfternoon.disabled = true
                }

                /* ===============================
                 * PACKAGE TYPE CHANGE
                 * =============================== */
                packageType.addEventListener('change', async function() {

                    const type = this.value
                    priceInput.value = 'Rp -'
                    resetSections()

                    if (!type) return

                    loading.classList.remove('d-none')

                    try {
                        const res = await fetch(
                            `{{ route('dashboard.buy-package.price') }}?package_type=${type}`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            }
                        )

                        if (!res.ok) throw new Error('Price not found')

                        const data = await res.json()

                        priceInput.value =
                            'Rp ' + new Intl.NumberFormat('id-ID').format(data.price) +
                            ` (${data.bird_type.toUpperCase()} BIRD)`

                        symposium.classList.remove('d-none')

                        if (type === '2') {
                            workshopSingle.classList.remove('d-none')
                            wsSingle.required = true
                            wsSingle.disabled = false
                        }

                        if (type === '3') {
                            workshopDouble.classList.remove('d-none')
                            wsMorning.required = true
                            wsAfternoon.required = true
                            wsMorning.disabled = false
                            wsAfternoon.disabled = false
                        }

                    } catch (err) {
                        console.error(err)
                        priceInput.value = 'Rp -'
                    } finally {
                        loading.classList.add('d-none')
                    }
                })

            })
        </script>
    @endpush







@endsection
