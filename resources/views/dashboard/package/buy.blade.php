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

        .select2-container--default .select2-results__option {
            padding: 10px;
        }

        .select2-container--default .select2-results__option--highlighted {
            background-color: #0d6efd !important;
            color: #fff;
        }

        /* Samakan tinggi dengan Bootstrap form-select */
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        /* Align text vertical */
        .select2-container--default .select2-selection__rendered {
            line-height: 24px !important;
            padding-left: 0 !important;
        }

        /* Align arrow */
        .select2-container--default .select2-selection__arrow {
            height: 36px !important;
            right: 10px;
        }

        .select2-selection {
            display: flex !important;
            align-items: center;
        }

        .d-none {
            display: none !important;
        }

        .fade-in {
            animation: fadeIn .2s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #nurseWorkshopCard {
            transition: all 0.2s ease;
            border: 1px solid #e9ecef;
        }

        #nurseWorkshopCard:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
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
                    {{-- <div class="fw-semibold">Loading price…</div> --}}
                    <div class="fw-semibold">Calculating best price…</div>
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

                    @if ($nurseWorkshopAvailable && auth()->user()->participant->participantCategory->name == 'Student / Nurse')
                        <option value="4">Workshop for Nurse</option>
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
                        {{-- <option value="{{ $ws->id }}">
                            {{ $ws->code }} – {{ $ws->title }}
                            [{{ substr($ws->start_time, 0, 5) }} – {{ substr($ws->end_time, 0, 5) }}]
                            ({{ $ws->quota - $ws->used }} seats left)
                        </option> --}}
                        <option value="{{ $ws->id }}" data-title="{{ $ws->code }} – {{ $ws->title }}" data-time="[{{ substr($ws->start_time, 0, 5) }} – {{ substr($ws->end_time, 0, 5) }}]" data-seat="({{ $ws->quota - $ws->used }} seats left)">

                            {{ $ws->code }} – {{ $ws->title }}
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
                            {{-- <option value="{{ $ws->id }}">
                                {{ $ws->code }} – {{ $ws->title }}
                                [{{ substr($ws->start_time, 0, 5) }} – {{ substr($ws->end_time, 0, 5) }}]
                                ({{ $ws->quota - $ws->used }} seats left)
                            </option> --}}
                            <option value="{{ $ws->id }}" data-title="{{ $ws->code }} – {{ $ws->title }}" data-time="[{{ substr($ws->start_time, 0, 5) }} – {{ substr($ws->end_time, 0, 5) }}]" data-seat="({{ $ws->quota - $ws->used }} seats left)">

                                {{ $ws->code }} – {{ $ws->title }}
                            </option>
                        @endif
                    @endforeach
                </select>

                {{-- AFTERNOON --}}
                <select name="workshops[]" id="workshopAfternoonSelect" class="form-select">
                    <option value="">Choose Afternoon Workshop</option>
                    @foreach ($workshops as $ws)
                        @if ($ws->start_time >= '12:00:00')
                            {{-- <option value="{{ $ws->id }}">
                                {{ $ws->code }} – {{ $ws->title }}
                                [{{ substr($ws->start_time, 0, 5) }} – {{ substr($ws->end_time, 0, 5) }}]
                                ({{ $ws->quota - $ws->used }} seats left)
                            </option> --}}
                            <option value="{{ $ws->id }}" data-title="{{ $ws->code }} – {{ $ws->title }}" data-time="[{{ substr($ws->start_time, 0, 5) }} – {{ substr($ws->end_time, 0, 5) }}]" data-seat="({{ $ws->quota - $ws->used }} seats left)">

                                {{ $ws->code }} – {{ $ws->title }}
                            </option>
                        @endif
                    @endforeach
                </select>

                <div class="form-text mt-1">
                    Select <b>1 morning</b> and <b>1 afternoon</b> workshop.
                </div>
            </div>

            {{-- ONLY WORKSHOP NURSE --}}
            <div class="mb-3 d-none" id="workshopNurse">
                <label class="form-label">Nurse Workshop</label>

                {{-- <textarea type="text" id="nurseWorkshopTitle" class="form-control" readonly>
                </textarea> --}}
                <div id="nurseWorkshopCard" class="border rounded p-3" style="background:#f8f9fa;">

                    <div class="fw-semibold mb-1" id="nurseTitle">-</div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small" id="nurseTime">-</span>

                        <span id="nurseBadge" class="badge bg-success">
                            Available
                        </span>
                    </div>

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
        <script src="{{ asset('projects/assets/vendor/jquery/jquery-3.6.0.min.js') }}"></script>

        <link href="{{ asset('projects/assets/vendor/select2/select2.min.css') }}" rel="stylesheet" />
        <script src="{{ asset('projects/assets/vendor/select2/select2.min.js') }}"></script>


        {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

        {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

        {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

        <script>
            $(document).ready(function() {

                function formatWorkshop(option) {
                    if (!option.id) return option.text

                    const el = $(option.element)

                    const title = el.data('title')
                    const time = el.data('time')?.replace('[', '').replace(']', '')
                    const seatRaw = el.data('seat')?.replace('(', '').replace(')', '')

                    let seatNumber = parseInt(seatRaw)

                    let badge = ''
                    let badgeColor = '#198754' // green

                    if (seatNumber <= 5 && seatNumber > 0) {
                        badge = 'Almost Full'
                        badgeColor = '#fd7e14'
                    } else if (seatNumber === 0) {
                        badge = 'Full'
                        badgeColor = '#dc3545'
                    } else {
                        badge = 'Available'
                    }

                    return $(`
        <div style="padding:6px 0;">
            <div style="font-weight:600; font-size:14px;">
                ${title}
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:2px;">
                <span style="font-size:12px; color:#6c757d;">
                    ${time}
                </span>

                <span style="
                    font-size:11px;
                    padding:2px 6px;
                    border-radius:6px;
                    background:${badgeColor};
                    color:#fff;
                ">
                    ${badge}
                </span>
            </div>
        </div>
    `)
                }

                $('#workshopSingleSelect').select2({
                    width: '100%',
                    placeholder: 'Choose Workshop',
                    templateResult: formatWorkshop,
                    templateSelection: function(option) {
                        if (!option.id) return option.text

                        const el = $(option.element)
                        const title = el.data('title')
                        const time = el.data('time')?.replace('[', '').replace(']', '')

                        return `${title} (${time})`
                    },
                    minimumResultsForSearch: 5 // auto hide search kalau sedikit
                })

                $('#workshopMorningSelect').select2({
                    width: '100%',
                    placeholder: 'Choose Workshop',
                    templateResult: formatWorkshop,
                    templateSelection: function(option) {
                        if (!option.id) return option.text

                        const el = $(option.element)
                        const title = el.data('title')
                        const time = el.data('time')?.replace('[', '').replace(']', '')

                        return `${title} (${time})`
                    },
                    minimumResultsForSearch: 5 // auto hide search kalau sedikit
                })

                $('#workshopAfternoonSelect').select2({
                    width: '100%',
                    placeholder: 'Choose Workshop',
                    templateResult: formatWorkshop,
                    templateSelection: function(option) {
                        if (!option.id) return option.text

                        const el = $(option.element)
                        const title = el.data('title')
                        const time = el.data('time')?.replace('[', '').replace(']', '')

                        return `${title} (${time})`
                    },
                    minimumResultsForSearch: 5 // auto hide search kalau sedikit
                })

            })

            document.addEventListener('DOMContentLoaded', () => {

                const nurseTitle = document.getElementById('nurseTitle')
                const nurseTime = document.getElementById('nurseTime')
                const nurseBadge = document.getElementById('nurseBadge')

                const workshopNurse = document.getElementById('workshopNurse')
                const nurseWorkshopTitle = document.getElementById('nurseWorkshopTitle')

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
                        submitBtn.innerHTML = `
                                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                                    Processing...
                                                `

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
                    workshopNurse.classList.add('d-none')

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

                        const data = await res.json()

                        if (!res.ok) {
                            throw new Error(data.message ?? 'Price not available')
                        }

                        priceInput.value =
                            'Rp ' + new Intl.NumberFormat('id-ID').format(data.price) +
                            ` (${data.bird_type.toUpperCase()} BIRD)`

                        /* ===============================
                         * UI LOGIC PER PACKAGE
                         * =============================== */

                        // DEFAULT: hide semua
                        symposium.classList.add('d-none')

                        if (type === '1') {
                            // Symposium only
                            symposium.classList.remove('d-none')
                        }

                        if (type === '2') {
                            symposium.classList.remove('d-none')
                            workshopSingle.classList.remove('d-none')
                            wsSingle.required = true
                            wsSingle.disabled = false
                        }

                        if (type === '3') {
                            symposium.classList.remove('d-none')
                            workshopDouble.classList.remove('d-none')
                            wsMorning.required = true
                            wsAfternoon.required = true
                            wsMorning.disabled = false
                            wsAfternoon.disabled = false
                        }

                        if (type === '4') {
                            workshopNurse.classList.remove('d-none')
                            workshopNurse.classList.add('fade-in')

                            const nurse = data.nurse_workshop

                            nurseTitle.innerText = nurse.title
                            nurseTime.innerText = nurse.time

                            // Extract seat info (kalau nanti kamu kirim quota)
                            let badgeText = 'Available'
                            let badgeClass = 'bg-success'

                            // OPTIONAL (kalau backend kirim seat)
                            if (nurse.seats !== undefined) {
                                if (nurse.seats === 0) {
                                    badgeText = 'Full'
                                    badgeClass = 'bg-danger'
                                } else if (nurse.seats <= 5) {
                                    badgeText = 'Almost Full'
                                    badgeClass = 'bg-warning'
                                } else {
                                    badgeText = `${nurse.seats} seats left`
                                    badgeClass = 'bg-success'
                                }
                            }

                            nurseBadge.className = 'badge ' + badgeClass
                            nurseBadge.innerText = badgeText
                        }

                    } catch (err) {
                        console.error(err)
                        priceInput.value = 'Rp -'
                        Swal.fire({
                            icon: 'warning',
                            title: 'Package not available',
                            text: err.message,
                            confirmButtonColor: '#dc3545'
                        })
                    } finally {
                        loading.classList.add('d-none')
                    }
                })

            })
        </script>
    @endpush







@endsection
