document.addEventListener('DOMContentLoaded', function () {

    /* ===============================
     * BOOTSTRAP VALIDATION
     * =============================== */
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

    /* ===============================
     * PASSWORD TOGGLE & VALIDATION
     * =============================== */
    const form = document.querySelector('form')
    const password = document.getElementById('password')
    const confirm = document.getElementById('password_confirmation')

    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.target)
            const icon = btn.querySelector('i')

            if (target.type === 'password') {
                target.type = 'text'
                icon.classList.replace('bi-eye', 'bi-eye-slash')
            } else {
                target.type = 'password'
                icon.classList.replace('bi-eye-slash', 'bi-eye')
            }
        })
    })

    const validatePasswordMatch = () => {
        if (password.value !== confirm.value) {
            confirm.setCustomValidity('Password mismatch')
        } else {
            confirm.setCustomValidity('')
        }
    }

    password.addEventListener('input', validatePasswordMatch)
    confirm.addEventListener('input', validatePasswordMatch)

    /* ===============================
     * OTP EMAIL VERIFICATION
     * =============================== */
    let timerInterval
    let remainingSeconds = 60
    let emailVerified = false

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
    const emailError = document.getElementById('emailError')
    const submitBtn = document.getElementById('submitBtn')

    function updateSubmitState() {
        submitBtn.disabled = !emailVerified
    }

    async function sendOtp() {
        const email = emailInput.value
        if (!email) return

        emailError.classList.add('d-none')
        emailStatus.classList.add('d-none')

        sendOtpBtn.disabled = true
        emailInput.readOnly = true
        emailLoading.classList.remove('d-none')
        sendOtpBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"></span>
            Sending...
        `

        try {
            const res = await fetch(sendOtpBtn.dataset.url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email })
            })

            const data = await res.json()
            if (!res.ok) throw new Error(data.message)

            otpSection.classList.remove('d-none')
            startTimer()

        } catch (err) {
            emailError.innerText = err.message
            emailError.classList.remove('d-none')
            sendOtpBtn.disabled = false
            emailInput.readOnly = false
        } finally {
            emailLoading.classList.add('d-none')
            sendOtpBtn.innerHTML = 'Verify'
        }
    }

    function startTimer() {
        clearInterval(timerInterval)
        remainingSeconds = 60
        otpTimer.innerText = remainingSeconds + 's'

        timerInterval = setInterval(() => {
            remainingSeconds--
            otpTimer.innerText = remainingSeconds + 's'

            if (remainingSeconds <= 0) {
                clearInterval(timerInterval)
                resendOtpBtn.classList.remove('d-none')
                sendOtpBtn.disabled = false
            }
        }, 1000)
    }

    sendOtpBtn?.addEventListener('click', sendOtp)
    resendOtpBtn?.addEventListener('click', sendOtp)

    otpInput?.addEventListener('blur', async () => {
        const otp = otpInput.value.trim()

        if (!/^[0-9]{6}$/.test(otp)) {
            otpError.innerText = 'OTP must be exactly 6 digits.'
            otpError.classList.remove('d-none')
            return
        }

        const res = await fetch(otpInput.dataset.url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: emailInput.value,
                otp
            })
        })

        if (res.ok) {
            emailVerified = true
            updateSubmitState()
            emailVerifiedInput.value = 1
            emailStatus.classList.remove('d-none')

            emailInput.readOnly = true
            otpInput.disabled = true
            sendOtpBtn?.remove()
            resendOtpBtn?.remove()
            otpTimer?.remove()
        } else {
            otpError.innerText = 'Invalid OTP'
            otpError.classList.remove('d-none')
        }
    })

    /* ===============================
     * PREVENT DOUBLE SUBMIT
     * =============================== */
    let submitted = false
    form.addEventListener('submit', e => {
        if (submitted) {
            e.preventDefault()
            return
        }

        if (!form.checkValidity()) return

        submitted = true
        submitBtn.disabled = true
        submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Processing...
        `
    })

    /* ===============================
     * INTL TEL INPUT
     * =============================== */
    const phoneInput = document.querySelector("#mobile_phone")
    if (phoneInput) {
        window.intlTelInput(phoneInput, {
            initialCountry: "id",
            onlyCountries: ["id"],
            separateDialCode: true,
            nationalMode: false,
            allowDropdown: false,
            utilsScript: phoneInput.dataset.utils
        })
    }
})
