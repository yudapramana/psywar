@extends('dashboard._layouts.app')

@section('title', 'My Package')
@section('page-title', 'Package')

@section('content')

    @php
        $p = $participant;
        $price = $registration?->pricingItem;

        // Package label
        $packageLabel = '-';
        if ($price) {
            $packageLabel = 'Symposium';
            if ($price->workshop_count > 0) {
                $packageLabel .= ' + ' . $price->workshop_count . ' Workshop';
            }
            $packageLabel .= ' (' . ucfirst($price->bird_type) . ' Bird)';
        }
    @endphp

    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- PERSONAL INFORMATION --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-semibold">Personal Information</div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td>Category</td>
                            <td>{{ $p->participantCategory->name }}</td>
                        </tr>
                        <tr>
                            <td>Full Name</td>
                            <td>{{ $p->full_name }}</td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>{{ $p->nik ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{ $p->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Phone Number</td>
                            <td>{{ $p->mobile_phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Institution</td>
                            <td>{{ $p->institution ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Registration Type</td>
                            <td class="text-capitalize">
                                {{ str_replace('_', ' ', $p->registration_type) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- CTA IF NO REGISTRATION --}}
            @if (!$registration)
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <p class="mb-3">You have not purchased any package yet.</p>
                        <a href="{{ route('dashboard.buy-package') }}" class="btn btn-danger">
                            Buy Package →
                        </a>
                    </div>
                </div>
            @endif

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">

            {{-- EVENT / PACKAGE INFO --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-semibold">Package Information</div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td>Package</td>
                            <td>{{ $packageLabel }}</td>
                        </tr>
                        <tr>
                            <td>Package Price</td>
                            <td>
                                Rp {{ number_format($price->price ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="fw-bold">
                            <td>Total Price</td>
                            <td>
                                Rp {{ number_format($registration->total_amount ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td class="text-capitalize">
                                {{ $registration->status ?? '-' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- PAYMENT INFO --}}
            @if ($registration)
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Payment Information</div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td>Payment Method</td>
                                <td>Bank Transfer</td>
                            </tr>

                            <tr>
                                <td>Payment Step</td>
                                <td class="text-capitalize">
                                    {{ str_replace('_', ' ', $registration->payment_step) }}
                                </td>
                            </tr>

                            <tr>
                                <td>Registration Status</td>
                                <td class="text-capitalize">
                                    {{ $registration->status }}
                                </td>
                            </tr>
                        </table>

                        {{-- ACTION BUTTON --}}
                        @switch($registration->payment_step)
                            @case('choose_bank')
                                <a href="{{ route('dashboard.payment.choose-bank', $registration->id) }}" class="btn btn-danger w-100 mt-3">
                                    Choose Bank →
                                </a>
                            @break

                            @case('waiting_transfer')
                                <a href="{{ route('dashboard.payment.transfer', $registration->id) }}" class="btn btn-danger w-100 mt-3">
                                    View Transfer Detail →
                                </a>
                            @break

                            @case('waiting_verification')
                                <div class="alert alert-warning mt-3 text-center mb-0">
                                    Payment is being verified by admin
                                </div>
                            @break

                            @case('paid')
                                <div class="alert alert-success mt-3 text-center mb-0">
                                    Payment completed ✔
                                </div>
                            @break
                        @endswitch
                    </div>
                </div>
            @endif

        </div>

    </div>
@endsection
