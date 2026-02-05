@extends('layouts.auth')

@section('title', 'Register')

@section('content')

    <link rel="stylesheet" href="{{ asset('vendor/intl-tel-input/css/intlTelInput.css') }}">
    <script src="{{ asset('vendor/intl-tel-input/js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('vendor/intl-tel-input/js/utils.js') }}"></script>



    <style>
        button[disabled] {
            opacity: .75;
            cursor: not-allowed;
        }

        input[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        #sendOtp .spinner-border {
            vertical-align: middle;
        }

        .auth-header {
            text-align: left;
        }

        .auth-header {
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

        /* === intl-tel-input + Bootstrap FIX (FLAG AKTIF) === */

        .iti {
            width: 100%;
        }

        .iti--separate-dial-code .iti__selected-flag {
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            padding: 0 12px;
            min-width: 72px;
        }

        .iti__flag {
            margin-right: 6px;
        }

        .iti input.form-control {
            padding-left: 90px !important;
            /* ruang flag +62 */
            height: calc(2.5rem + 2px);
        }

        .iti__selected-flag {
            display: flex;
            align-items: center;
        }
    </style>

    <div class="auth-card">

        {{-- REGISTER HEADER --}}
        <div class="auth-header mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <img src="{{ asset('projects/assets/img/symcardlogolong.png') }}" alt="Logo" height="41">

                <a href="{{ route('login') }}" class="back-link">
                    ← Back to login
                </a>
            </div>

            <h2 class="auth-title mb-1">Personal Registration</h2>
            <p class="auth-subtitle mb-0">Create your own account</p>
        </div>


        <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" id="emailVerifiedSession" value="{{ session('email_verified_session') ? '1' : '0' }}">

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
                <input type="text" name="nik" id="nik" value="{{ old('nik') }}" class="form-control @error('nik') is-invalid @enderror" pattern="[0-9]{16}" inputmode="numeric" maxlength="16" required oninput="validateNik(this)" oninvalid="validateNik(this)">

                <div class="invalid-feedback">
                    @error('nik')
                        {{ $message }}
                    @else
                        NIK must contain exactly 16 digits.
                    @enderror
                </div>

                <small class="text-warning">
                    Please fill in the informastion as registered in your Satu Sehat account
                </small><br>



            </div>

            {{-- MOBILE PHONE --}}
            <div class="mb-3">
                <label class="form-label">Mobile Phone </label>

                {{-- <input type="tel" id="mobile_phone" name="mobile_phone" class="form-control" placeholder="8123456789" value="{{ old('mobile_phone') }}"> --}}
                <input type="tel" id="mobile_phone" name="mobile_phone" class="form-control" placeholder="8123456789" value="{{ old('mobile_phone') }}" inputmode="numeric" autocomplete="tel" pattern="[0-9]*" @error('mobile_phone') is-invalid @enderror />

                <div class="invalid-feedback">
                    {{ $errors->first('mobile_phone') ?? 'Please provide a valid phone number.' }}
                </div>
                @error('mobile_phone')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
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
                    <input type="email" id="email" name="email" class="form-control" required value="{{ old('email') }}">
                    <button type="button" class="btn btn-outline-primary" id="sendOtp">
                        Verify
                    </button>
                </div>

                <small id="emailLoading" class="text-muted d-none mt-1">
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Sending OTP code...
                </small>

                <small id="checkEmailHint" class="text-info d-none mt-1">
                    Please check your email inbox (and spam folder) for the OTP code.
                </small>


                <small id="emailError" class="text-danger d-none mt-1"></small>
                <small id="emailStatus" class="text-success d-none mt-1">
                    Email verified
                </small>
            </div>


            {{-- OTP FIELD --}}
            <div class="mb-3 d-none" id="otpSection">
                <label class="form-label">OTP Code</label>

                <div class="input-group">
                    <input type="text" id="otp" class="form-control" maxlength="6">
                    <span class="input-group-text" id="otpTimer">60s</span>
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





            {{-- INSTITUTION --}}
            <div class="mb-4">
                <label class="form-label">Institution</label>
                <input type="text" name="institution" value="{{ old('institution') }}" class="form-control @error('institution') is-invalid @enderror" required>

                <div class="invalid-feedback">
                    {{ $errors->first('institution') ?? 'Please enter your institution.' }}
                </div>
            </div>

            <button type="submit" class="btn btn-auth w-100" id="submitBtn" disabled>
                Submit Register
            </button>


            {{-- <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none">
                    ← Back to login
                </a>
            </div> --}}

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
        let remainingSeconds = 60

        const sendOtpBtn = document.getElementById('sendOtp')
        const resendOtpBtn = document.getElementById('resendOtp')
        const otpSection = document.getElementById('otpSection')
        const otpTimer = document.getElementById('otpTimer')
        const emailInput = document.getElementById('email')
        const otpInput = document.getElementById('otp')
        const emailVerifiedInput = document.getElementById('emailVerified')
        const emailStatus = document.getElementById('emailStatus')
        const otpError = document.getElementById('otpError')
        const emailLoading = document.getElementById('emailLoading')


        const submitBtn = document.getElementById('submitBtn')

        let emailVerified = false

        function updateSubmitState() {
            submitBtn.disabled = !emailVerified
        }

        // SEND OTP
        const emailError = document.getElementById('emailError')

        async function sendOtp() {
            const email = emailInput.value
            if (!email) return

            // Reset UI
            emailError.classList.add('d-none')
            emailStatus.classList.add('d-none')

            // 🔄 LOADING STATE
            sendOtpBtn.disabled = true
            emailInput.readOnly = true
            emailLoading.classList.remove('d-none')

            sendOtpBtn.innerHTML = `
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Sending...
                                `

            try {
                const res = await fetch('{{ route('email.sendOtp') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email
                    })
                })

                const data = await res.json()

                if (!res.ok) {
                    throw new Error(data.message || 'Failed to send OTP')
                }

                // ✅ SUCCESS
                document.getElementById('checkEmailHint').classList.remove('d-none')
                otpSection.classList.remove('d-none')
                startTimer()

            } catch (err) {

                // ❌ ERROR
                emailError.innerText = err.message
                emailError.classList.remove('d-none')

                sendOtpBtn.disabled = false
                emailInput.readOnly = false

            } finally {

                // 🧹 CLEAN LOADING
                emailLoading.classList.add('d-none')
                sendOtpBtn.innerHTML = 'Verify Email'
            }
        }


        sendOtpBtn.addEventListener('click', sendOtp)
        resendOtpBtn.addEventListener('click', sendOtp)

        // TIMER
        function startTimer() {
            clearInterval(timerInterval)
            remainingSeconds = 60
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
            const otp = otpInput.value.trim()

            // 🚫 VALIDASI FRONTEND KERAS
            if (!/^[0-9]{6}$/.test(otp)) {
                otpError.innerText = 'OTP must be exactly 6 digits.'
                otpError.classList.remove('d-none')
                return
            }

            otpError.classList.add('d-none')

            const res = await fetch('{{ route('email.verifyOtp') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email,
                    otp
                })
            })

            if (res.ok) {
                emailVerified = true
                updateSubmitState()

                emailVerifiedInput.value = 1
                emailStatus.classList.remove('d-none')
                otpError.classList.add('d-none')

                // 🔒 LOCK EMAIL & OTP
                emailInput.readOnly = true
                emailInput.classList.add('bg-light')
                otpInput.setAttribute('disabled', true)

                sendOtpBtn?.remove()
                resendOtpBtn?.remove()
                otpTimer?.remove()
                clearInterval(timerInterval)
            } else {
                const data = await res.json()
                otpError.innerText = data.message || 'Invalid OTP'
                otpError.classList.remove('d-none')
            }
        })
    </script>


    <script>
        (function() {
            const form = document.querySelector('form')
            const submitBtn = document.getElementById('submitBtn')

            let submitted = false

            form.addEventListener('submit', function(e) {

                // Kalau sudah pernah submit → stop
                if (submitted) {
                    e.preventDefault()
                    return false
                }

                // Jika form tidak valid → jangan disable
                if (!form.checkValidity()) {
                    return
                }

                // Tandai sudah submit
                submitted = true

                // Disable tombol
                submitBtn.disabled = true

                // Optional: ubah teks & tampilkan loading
                submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Processing...
            `
            })
        })()
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const emailVerifiedSession = document.getElementById('emailVerifiedSession')?.value === '1'

            if (emailVerifiedSession) {
                emailVerified = true
                updateSubmitState()

                // Kunci email
                emailInput.readOnly = true
                emailInput.classList.add('bg-light')

                // Set hidden input
                emailVerifiedInput.value = 1

                // Tampilkan status
                emailStatus.classList.remove('d-none')

                // Pastikan OTP tidak muncul lagi
                otpSection.classList.add('d-none')
                otpError.classList.add('d-none')

                // Remove tombol verify jika ada
                if (sendOtpBtn) sendOtpBtn.remove()
            }
        })
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.querySelector("#mobile_phone")
            const form = document.querySelector("form")
            const phoneError = document.getElementById("phoneError")


            const iti = window.intlTelInput(phoneInput, {
                initialCountry: "id",
                onlyCountries: ["id"],
                separateDialCode: true,
                nationalMode: false,
                autoPlaceholder: "polite",
                allowDropdown: false,
                utilsScript: "{{ asset('vendor/intl-tel-input/js/utils.js') }}"
            })
        })
    </script>

    <script>
        function validateNik(input) {
            const regex = /^[0-9]{16}$/

            if (!input.value) {
                input.setCustomValidity('NIK is required.')
            } else if (!regex.test(input.value)) {
                input.setCustomValidity('NIK must contain exactly 16 digits.')
            } else {
                input.setCustomValidity('')
            }
        }
    </script>










@endsection
