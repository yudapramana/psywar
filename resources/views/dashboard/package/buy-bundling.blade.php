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
                    <option value="1">Symposium</option>
                    <option value="2">Symposium + 1 Workshop</option>
                    <option value="3">Symposium + 2 Workshop</option>
                </select>
            </div>

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
                        </option>
                    @endforeach
                </select>
            </div>

            @php
                $morning = $workshops->filter(fn($w) => $w->start_time < '12:00:00')->values();

                $afternoon = $workshops->filter(fn($w) => $w->start_time >= '12:00:00')->values();
            @endphp

            {{-- WORKSHOP BUNDLING --}}
            <div class="mb-3 d-none" id="workshopBundling">
                <label class="form-label">Workshop Bundling</label>

                <select name="workshop_bundling" id="workshopBundlingSelect" class="form-select">
                    <option value="">Choose Bundling</option>

                    @if ($morning->count() >= 2 && $afternoon->count() >= 2)
                        <option value="{{ $morning[0]->id }},{{ $afternoon[0]->id }}" data-items='[
                    "{{ $morning[0]->code }} – {{ $morning[0]->title }} [{{ substr($morning[0]->start_time, 0, 5) }}–{{ substr($morning[0]->end_time, 0, 5) }}]",
                    "{{ $afternoon[0]->code }} – {{ $afternoon[0]->title }} [{{ substr($afternoon[0]->start_time, 0, 5) }}–{{ substr($afternoon[0]->end_time, 0, 5) }}]"
                ]'>
                            BND-01 – {{ $morning[0]->code }} + {{ $afternoon[0]->code }}
                            [07:30–12:30 & 13:30–18:00]
                        </option>

                        <option value="{{ $morning[1]->id }},{{ $afternoon[1]->id }}" data-items='[
                    "{{ $morning[1]->code }} – {{ $morning[1]->title }} [{{ substr($morning[1]->start_time, 0, 5) }}–{{ substr($morning[1]->end_time, 0, 5) }}]",
                    "{{ $afternoon[1]->code }} – {{ $afternoon[1]->title }} [{{ substr($afternoon[1]->start_time, 0, 5) }}–{{ substr($afternoon[1]->end_time, 0, 5) }}]"
                ]'>
                            BND-02 – {{ $morning[1]->code }} + {{ $afternoon[1]->code }}
                            [07:30–12:30 & 13:30–18:00]
                        </option>
                    @endif
                </select>

                {{-- INFO BUNDLING --}}
                <div id="bundlingInfo" class="mt-2 d-none">
                    <div class="border rounded-3 p-2 bg-light">
                        <div class="small text-muted fw-semibold mb-1">
                            Bundling includes
                        </div>

                        <div id="bundlingInfoList" class="d-flex flex-column gap-1"></div>
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
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                const packageType = document.getElementById('packageType')
                const priceInput = document.getElementById('packagePrice')

                const symposium = document.getElementById('symposiumSection')
                const workshopSingle = document.getElementById('workshopSingle')
                const workshopBundling = document.getElementById('workshopBundling')

                const workshopSingleSelect = document.getElementById('workshopSingleSelect')
                const workshopBundlingSelect = document.getElementById('workshopBundlingSelect')

                const bundlingInfo = document.getElementById('bundlingInfo')
                const bundlingInfoList = document.getElementById('bundlingInfoList')

                workshopBundlingSelect.addEventListener('change', function() {

                    bundlingInfoList.innerHTML = ''
                    bundlingInfo.classList.add('d-none')

                    const selected = this.options[this.selectedIndex]
                    const items = selected?.dataset?.items

                    if (!items) return


                    console.log('items');
                    console.log(items);

                    JSON.parse(items).forEach(text => {

                        console.log('text')
                        console.log(text)

                        const wrapper = document.createElement('div')
                        wrapper.className = 'bundling-item'

                        const badge = document.createElement('span')
                        badge.className = 'bundling-badge'
                        badge.innerText = text.startsWith('WS-01') || text.startsWith('WS-02') ?
                            'Morning' :
                            'Afternoon'

                        const content = document.createElement('div')
                        content.innerHTML = `
            <div class="fw-semibold">${text.split('[')[0]}</div>
            <div class="bundling-time">(${text.split('[')[1]}</div>
        `

                        wrapper.appendChild(badge)
                        wrapper.appendChild(content)

                        bundlingInfoList.appendChild(wrapper)
                    })

                    bundlingInfo.classList.remove('d-none')
                })


                const loading = document.getElementById('cardLoading')

                const form = document.querySelector('form')
                const submitBtn = document.getElementById('submitBtn')

                form.addEventListener('submit', function() {

                    // 🔒 LOCK BUTTON
                    submitBtn.disabled = true
                    submitBtn.innerHTML = 'Processing…'

                    // 🔒 SHOW LOADING OVERLAY
                    // loading.classList.remove('d-none')

                })


                const resetSections = () => {
                    symposium.classList.add('d-none')
                    workshopSingle.classList.add('d-none')
                    workshopBundling.classList.add('d-none')

                    workshopSingleSelect.required = false
                    workshopBundlingSelect.required = false

                    workshopSingleSelect.disabled = true
                    workshopBundlingSelect.disabled = true
                }


                packageType.addEventListener('change', async function() {

                    const type = this.value
                    priceInput.value = 'Rp -'
                    resetSections()

                    if (!type) return

                    // 🔄 SHOW LOADING
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

                        // 💰 SET PRICE
                        priceInput.value =
                            'Rp ' + new Intl.NumberFormat('id-ID').format(data.price) +
                            ` (${data.bird_type.toUpperCase()} BIRD)`

                        // ✅ AFTER PRICE LOADED → SHOW FIELDS
                        symposium.classList.remove('d-none')

                        if (type === '2') {
                            workshopSingle.classList.remove('d-none')
                            workshopSingleSelect.required = true
                            workshopSingleSelect.disabled = false
                        }

                        if (type === '3') {
                            workshopBundling.classList.remove('d-none')
                            workshopBundlingSelect.required = true
                            workshopBundlingSelect.disabled = false
                        }


                    } catch (err) {
                        console.error(err)
                        priceInput.value = 'Rp -'
                    } finally {
                        // 🧹 HIDE LOADING
                        loading.classList.add('d-none')
                    }
                })
            })
        </script>
    @endpush




@endsection
