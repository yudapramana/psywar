@extends('layouts.app')

@section('title', 'Registration | SYMCARD 2026')

@section('content')

    <style>
        /* ===============================
                                                               FINAL PRODUCTION STYLE
                                                            ================================= */

        .package-card {
            border-radius: 18px;
            overflow: hidden;
            color: #fff;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .package-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.12) 2px, transparent 2px);
            background-size: 26px 26px;
            opacity: 0.25;
        }

        .package-inner {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: stretch;
            /* 🔥 samakan tinggi kiri & kanan */
            padding: 30px 40px;
            gap: 40px;
        }

        /* ===============================
                           FIX PACKAGE TITLE & BADGE
                        ================================= */

        .package-title {
            width: 240px;
            min-width: 240px;
            max-width: 240px;
            padding-right: 30px;
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* 🔥 TITLE PUTIH FIX */
        .package-title h2 {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.2;
            margin-top: 10px;
            color: #ffffff;
            /* pastikan putih */
        }

        /* 🔥 BADGE AUTO WIDTH */
        .badge-package {
            display: inline-flex;
            /* lebih stabil dari inline-block */
            align-items: center;
            justify-content: center;
            background: #ff5c35;
            padding: 3px 10px;
            /* lebih kecil */
            border-radius: 12px;
            /* tidak terlalu oval */
            font-size: 10px;
            /* sedikit lebih kecil */
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #fff;
            width: fit-content;
            /* 🔥 penting */
            max-width: fit-content;
            align-items: flex-start;
            /* supaya badge tidak ikut stretch */
        }

        /* RIGHT SIDE */
        .package-categories {
            display: flex;
            flex: 1;
            justify-content: space-between;
            gap: 35px;
        }

        /* CATEGORY TITLE WHITE */
        .category-item h6 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #ffffff;
            /* 🔥 putih */
        }

        /* PRICING */
        .category-item {
            flex: 1;
            text-align: right;
        }

        .price-early strong {
            font-size: 20px;
            font-weight: 700;
            display: block;
            color: #ffffff;
        }

        .price-early small {
            font-size: 12px;
            opacity: 0.85;
            display: block;
        }

        .price-normal {
            margin-top: 14px;
        }

        .price-normal strong {
            font-size: 18px;
            font-weight: 700;
            display: block;
            color: #ffffff;
        }

        .price-normal small {
            font-size: 12px;
            opacity: 0.75;
        }

        /* ===============================
                                                               MOBILE
                                                            ================================= */

        @media (max-width: 768px) {

            .package-inner {
                flex-direction: column;
                gap: 20px;
            }

            .package-title {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.3);
                padding-bottom: 15px;
            }

            .package-categories {
                flex-direction: column;
                gap: 20px;
            }

            .category-item {
                text-align: left;
            }
        }

        /* PACKAGE 1 – Blue */
        .package-0 {
            background: linear-gradient(135deg, #0f4c75, #1b3c73, #2c2f8f);
        }

        /* PACKAGE 2 – Purple / Teal */
        .package-1 {
            background: linear-gradient(135deg, #7b1e3c, #5f2c82, #134e5e);
        }

        /* PACKAGE 3 – Red */
        .package-2 {
            background: linear-gradient(135deg, #d7263d, #c2185b, #8e0038);
        }
    </style>

    {{-- ================= PAGE TITLE ================= --}}
    <div class="page-title dark-background" style="
    background-image:
    linear-gradient(
        135deg,
        rgba(11, 28, 61, 0.65) 0%,
        rgba(18, 58, 130, 0.65) 45%,
        rgba(128, 20, 40, 0.65) 100%
    ),
    url('{{ asset('projects/assets/img/symcardheadercontent/symcardheadercontent1.jpg') }}');
    background-size: cover;
    background-position: center;
">
        <div class="container position-relative">
            <h1>Registration</h1>
            <p>
                {{ $event->name }} <br>
                {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                -
                {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
            </p>

            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('landing') }}">Home</a></li>
                    <li class="current">Registration</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- /PAGE TITLE --}}

    {{-- ================= REGISTRATION SECTION ================= --}}
    <section class="registration-section py-5">
        <div class="container">

            @foreach ($pricing as $key => $items)
                @php
                    [$symposium, $workshop] = explode('-', $key);

                    $packageName = 'Symposium';

                    if ($workshop == 1) {
                        $packageName = 'Symposium + Workshop';
                    }
                    if ($workshop == 2) {
                        $packageName = 'Symposium + 2 Workshops';
                    }
                    if ($symposium == 0 && $workshop == 1) {
                        $packageName = 'Workshop Only';
                    }
                @endphp

                @php
                    $index = $loop->index;
                @endphp

                <div class="package-card mb-4 package-{{ $index }}">

                    <div class="package-inner">

                        {{-- LEFT : PACKAGE --}}
                        <div class="package-title">
                            <span class="badge-package">PACKAGE</span>
                            <h2>{{ $packageName }}</h2>
                        </div>

                        {{-- RIGHT : ALL CATEGORIES IN ONE ROW --}}
                        <div class="package-categories">

                            @foreach ($items->groupBy('participant_category_id') as $catId => $catPrices)
                                @php
                                    $early = $catPrices->firstWhere('bird_type', 'early');
                                    $late = $catPrices->firstWhere('bird_type', 'late');
                                @endphp

                                <div class="category-item">

                                    <h6>
                                        {{ $early?->participantCategory->name ?? $late?->participantCategory->name }}
                                    </h6>

                                    @if ($early)
                                        <div class="price-early">
                                            <strong>
                                                {{ str_contains($early->participantCategory->name, 'Overseas') ? 'USD' : 'IDR' }}
                                                {{ number_format($early->price, 0, ',', '.') }}
                                            </strong>
                                            <small>
                                                Early bird until
                                                {{ \Carbon\Carbon::parse($event->early_bird_end_date)->format('F d, Y') }}
                                            </small>
                                        </div>
                                    @endif

                                    @if ($late)
                                        <div class="price-normal">
                                            <strong>
                                                {{ str_contains($late->participantCategory->name, 'Overseas') ? 'USD' : 'IDR' }}
                                                {{ number_format($late->price, 0, ',', '.') }}
                                            </strong>
                                            <small>Normal Price</small>
                                        </div>
                                    @endif

                                </div>
                            @endforeach

                        </div>

                    </div>

                </div>
            @endforeach

        </div>
    </section>

    {{-- ================= CTA SECTION ================= --}}
    <section class="section bg-light py-5">
        <div class="container text-center">

            <h3 class="mb-4">
                What are you waiting for? Let's Register Now!
            </h3>

            <a href="{{ route('register') }}" class="btn btn-primary btn-cta">
                Register as Participant
            </a>

        </div>
    </section>

@endsection
