@php
    /**
     * $currentStep wajib dikirim dari blade
     */
    $steps = [
        'choose_package' => 'Package',
        'choose_bank' => 'Bank',
        'waiting_transfer' => 'Transfer',
        'waiting_verification' => 'Verification',
        'paid' => 'Completed',
    ];

    $stepKeys = array_keys($steps);
    $currentIndex = array_search($currentStep, $stepKeys);
@endphp

<style>
    .payment-stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .step-item {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .step-item:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 14px;
        right: -50%;
        width: 100%;
        height: 2px;
        background: #dee2e6;
        z-index: 0;
    }

    .step-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .85rem;
        font-weight: 600;
        z-index: 1;
        position: relative;
        background: #fff;
        border: 2px solid #dee2e6;
        color: #6c757d;
    }

    .step-label {
        font-size: .75rem;
        margin-top: 6px;
        color: #6c757d;
    }

    .step-item.completed .step-circle {
        background: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }

    .step-item.completed::after {
        background: #dc3545;
    }

    .step-item.active .step-circle {
        border-color: #dc3545;
        color: #dc3545;
    }

    .step-item.active .step-label {
        font-weight: 600;
        color: #212529;
    }
</style>

<div class="payment-stepper">
    @foreach ($steps as $key => $label)
        @php
            $index = array_search($key, $stepKeys);

            $state = 'upcoming';
            if ($index < $currentIndex) {
                $state = 'completed';
            }
            if ($index === $currentIndex) {
                $state = 'active';
            }
        @endphp

        <div class="step-item {{ $state }}">
            <div class="step-circle">
                @if ($state === 'completed')
                    ✓
                @else
                    {{ $index + 1 }}
                @endif
            </div>
            <div class="step-label">{{ $label }}</div>
        </div>
    @endforeach
</div>
