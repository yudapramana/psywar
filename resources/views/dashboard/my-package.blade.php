@extends('dashboard._layouts.app')

@section('title', 'My Package')
@section('page-title', 'Package')

@section('content')

    {{-- FLASH INFO MESSAGE --}}
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


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
                                <a href="{{ route('dashboard.payment.choose-bank') }}" class="btn btn-danger w-100 mt-3">
                                    Choose Bank →
                                </a>
                            @break

                            @case('waiting_transfer')
                                <a href="{{ route('dashboard.payment.transfer') }}" class="btn btn-danger w-100 mt-3">
                                    View Transfer Detail →
                                </a>
                            @break

                            @case('waiting_verification')
                                @php
                                    $payment = $registration->payment;
                                    $verification = $payment?->verification;
                                @endphp

                                {{-- STATUS: PENDING --}}
                                @if ($payment && $payment->status === 'pending')
                                    <div class="alert alert-warning mt-3 text-center mb-2">
                                        <strong>Payment Under Review</strong><br>
                                        Your payment is currently being verified by admin.
                                    </div>

                                    {{-- STATUS: REJECTED --}}
                                @elseif ($payment && $payment->status === 'rejected')
                                    <div class="alert alert-danger mt-3 mb-2">
                                        <strong>Payment Rejected</strong><br>

                                        @if ($verification?->notes)
                                            <div class="small mt-1">
                                                <strong>Admin Note:</strong><br>
                                                {{ $verification->notes }}
                                            </div>
                                        @endif
                                    </div>

                                    <a href="{{ route('dashboard.payment.upload-proof') }}" class="btn btn-danger w-100">
                                        Upload Payment Proof Again →
                                    </a>

                                    {{-- STATUS: VERIFIED (edge case safety) --}}
                                @elseif ($payment && $payment->status === 'verified')
                                    <div class="alert alert-success mt-3 text-center mb-0">
                                        Payment verified ✔
                                    </div>
                                @endif
                            @break

                            @case('paid')
                                <a href="{{ route('dashboard.payment.completed') }}" class="btn btn-success w-100 mt-3">
                                    Payment completed ✔
                                </a>
                            @break

                        @endswitch
                    </div>
                </div>
            @endif

        </div>

    </div>
@endsection
