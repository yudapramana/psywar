@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="auth-card">

        <div class="mb-4 text-center">
            <img src="{{ asset('projects/assets/img/symcardlong.png') }}" height="40" class="mb-3">
            <h2 class="auth-title">Personal Registration</h2>
            <p class="auth-subtitle">Create your own account</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
            @csrf

            {{-- CATEGORY --}}
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="participant_category_id" class="form-select @error('participant_category_id') is-invalid @enderror" required>
                    <option value="">Choose...</option>
                    @foreach ($participantCategories ?? [] as $cat)
                        <option value="{{ $cat->id }}" {{ old('participant_category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>


                <div class="invalid-feedback">
                    {{ $errors->first('participant_category_id') ?? 'Please select a category.' }}
                </div>
            </div>

            {{-- FULL NAME --}}
            <div class="mb-3">
                <label class="form-label">Full Name (Without Any Title)</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>


                <div class="invalid-feedback">
                    {{ $errors->first('name') ?? 'Please enter your full name.' }}
                </div>
            </div>

            {{-- NIK --}}
            <div class="mb-3">
                <label class="form-label">NIK / National Identity Number</label>
                <input type="text" name="nik" value="{{ old('nik') }}" class="form-control @error('nik') is-invalid @enderror" pattern="[0-9]{16}">

                <div class="invalid-feedback">
                    {{ $errors->first('nik') ?? 'Please enter a valid 16-digit NIK.' }}
                </div>

                <small class="text-warning">
                    Please fill in the informastion as registered in your Satu Sehat account
                </small>
            </div>

            {{-- REGISTRATION TYPE --}}
            <div class="mb-3">
                <label class="form-label">Registration Type</label>
                <select name="registration_type" class="form-select @error('registration_type') is-invalid @enderror" required>
                    <option value="">Choose...</option>
                    <option value="non_sponsored" {{ old('registration_type') === 'non_sponsored' ? 'selected' : '' }}>
                        Non Sponsored
                    </option>
                    <option value="sponsored" {{ old('registration_type') === 'sponsored' ? 'selected' : '' }}>
                        Sponsored
                    </option>
                </select>

                <div class="invalid-feedback">
                    {{ $errors->first('registration_type') ?? 'Please select registration type.' }}
                </div>
            </div>

            {{-- EMAIL --}}
            <div class="mb-3">
                <label class="form-label">Email Address</label>

                <div class="input-group">
                    <input type="email" id="email" name="email" class="form-control" required>
                    <button type="button" class="btn btn-outline-primary" id="sendOtp">
                        Verify Email
                    </button>
                </div>

                <small id="emailStatus" class="text-success d-none">Email available</small>
            </div>

            {{-- OTP FIELD --}}
            <div class="mb-3 d-none" id="otpSection">
                <label class="form-label">OTP Code</label>

                <div class="input-group">
                    <input type="text" id="otp" class="form-control" maxlength="6">
                    <span class="input-group-text" id="otpTimer">30s</span>
                    <button type="button" class="btn btn-link p-0 mt-1 d-none" id="resendOtp">
                        Resend OTP
                    </button>

                </div>

                <small class="text-danger d-none" id="otpError">Invalid OTP</small>
            </div>

            <input type="hidden" name="email_verified" id="emailVerified" value="0">



            {{-- PASSWORD --}}
            <div class="mb-3">
                <label class="form-label">Create Password</label>

                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8" required>

                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                        <i class="bi bi-eye"></i>
                    </button>

                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <small class="text-muted">Minimum 8 characters</small>
            </div>

            {{-- CONFIRM PASSWORD --}}
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>

                <div class="input-group">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>

                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                        <i class="bi bi-eye"></i>
                    </button>

                    <div class="invalid-feedback">
                        Password confirmation does not match.
                    </div>
                </div>
            </div>



            {{-- MOBILE PHONE --}}
            <div class="mb-3">
                <label class="form-label">Mobile Phone Number</label>
                <input type="text" name="mobile_phone" value="{{ old('mobile_phone') }}" class="form-control @error('mobile_phone') is-invalid @enderror" pattern="^\+?[0-9]{9,15}$">

                <div class="invalid-feedback">
                    {{ $errors->first('mobile_phone') ?? 'Please provide a valid phone number.' }}
                </div>
            </div>

            {{-- INSTITUTION --}}
            <div class="mb-4">
                <label class="form-label">Institution</label>
                <input type="text" name="institution" value="{{ old('institution') }}" class="form-control @error('institution') is-invalid @enderror" required>

                <div class="invalid-feedback">
                    {{ $errors->first('institution') ?? 'Please enter your institution.' }}
                </div>
            </div>

            <button type="submit" class="btn btn-auth w-100">
                Submit form
            </button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none">
                    ← Back to login
                </a>
            </div>

        </form>
    </div>

    {{-- Bootstrap validation trigger --}}
    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>

    <script>
        (() => {
            'use strict'

            const form = document.querySelector('form')
            const password = document.getElementById('password')
            const confirm = document.getElementById('password_confirmation')

            // Toggle eye button
            document.querySelectorAll('.toggle-password').forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = document.getElementById(btn.dataset.target)
                    const icon = btn.querySelector('i')

                    if (target.type === 'password') {
                        target.type = 'text'
                        icon.classList.remove('bi-eye')
                        icon.classList.add('bi-eye-slash')
                    } else {
                        target.type = 'password'
                        icon.classList.remove('bi-eye-slash')
                        icon.classList.add('bi-eye')
                    }
                })
            })

            // Password match validation
            const validatePasswordMatch = () => {
                if (password.value !== confirm.value) {
                    confirm.setCustomValidity('Password mismatch')
                } else {
                    confirm.setCustomValidity('')
                }
            }

            password.addEventListener('input', validatePasswordMatch)
            confirm.addEventListener('input', validatePasswordMatch)

            form.addEventListener('submit', event => {
                validatePasswordMatch()
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            })
        })()
    </script>

    <script>
        let timerInterval
        let remainingSeconds = 30

        const sendOtpBtn = document.getElementById('sendOtp')
        const resendOtpBtn = document.getElementById('resendOtp')
        const otpSection = document.getElementById('otpSection')
        const otpTimer = document.getElementById('otpTimer')
        const emailInput = document.getElementById('email')
        const otpInput = document.getElementById('otp')
        const emailVerifiedInput = document.getElementById('emailVerified')
        const emailStatus = document.getElementById('emailStatus')
        const otpError = document.getElementById('otpError')

        // SEND OTP
        async function sendOtp() {
            const email = emailInput.value
            if (!email) return

            sendOtpBtn.disabled = true
            resendOtpBtn.classList.add('d-none')
            otpError.classList.add('d-none')

            await fetch('{{ route('email.sendOtp') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email
                })
            })

            otpSection.classList.remove('d-none')
            startTimer()
        }

        sendOtpBtn.addEventListener('click', sendOtp)
        resendOtpBtn.addEventListener('click', sendOtp)

        // TIMER
        function startTimer() {
            clearInterval(timerInterval)
            remainingSeconds = 30
            otpTimer.innerText = remainingSeconds + 's'

            timerInterval = setInterval(() => {
                remainingSeconds--
                otpTimer.innerText = remainingSeconds + 's'

                if (remainingSeconds <= 0) {
                    clearInterval(timerInterval)
                    otpTimer.innerText = '0s'
                    resendOtpBtn.classList.remove('d-none')
                    sendOtpBtn.disabled = false
                }
            }, 1000)
        }

        // VERIFY OTP
        otpInput.addEventListener('blur', async () => {
            const email = emailInput.value
            const otp = otpInput.value

            if (otp.length !== 6) return

            const res = await fetch('{{ route('email.verifyOtp') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email,
                    otp
                })
            })

            if (res.ok) {
                emailVerifiedInput.value = 1
                emailStatus.classList.remove('d-none')
                otpError.classList.add('d-none')

                // CLEAN UI AFTER VERIFIED
                clearInterval(timerInterval)
                otpTimer.remove()
                resendOtpBtn.remove()
                sendOtpBtn.remove()
                otpInput.setAttribute('disabled', true)
            } else {
                otpError.classList.remove('d-none')
            }
        })
    </script>




@endsection
