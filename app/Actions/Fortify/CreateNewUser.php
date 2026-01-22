<?php

namespace App\Actions\Fortify;

use App\Models\EmailVerification;
use App\Models\User;
use App\Models\Participant;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input): User
    {
        $validator = Validator::make($input, [
            // USER
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],

            // PARTICIPANT
            'participant_category_id' => ['required', 'exists:participant_categories,id'],
            'registration_type' => ['required', 'in:sponsored,non_sponsored'],

            // IDENTITY
            'nik' => ['required', 'digits:16'],
            'mobile_phone' => [
                'required',
                'digits_between:9,13',
                'regex:/^[1-9][0-9]+$/'
            ],
            'institution' => ['required', 'string', 'min:3', 'max:255'],
        ], [
            // CATEGORY
            'participant_category_id.required' => 'Please select a participant category.',
            'participant_category_id.exists' => 'The selected participant category is invalid.',

            // REGISTRATION TYPE
            'registration_type.required' => 'Please select a registration type.',
            'registration_type.in' => 'The selected registration type is invalid.',

            // PASSWORD
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',

            // NIK
            'nik.required' => 'NIK is required.',
            'nik.digits' => 'NIK must consist of exactly 16 digits.',

            // MOBILE PHONE
            'mobile_phone.required' => 'Mobile phone number is required.',
            'mobile_phone.digits_between' => 'Mobile phone number must be between 9 and 13 digits.',
            'mobile_phone.regex' => 'Mobile phone number must contain numbers only and must not start with 0.',

            // INSTITUTION
            'institution.required' => 'Institution name is required.',
            'institution.min' => 'Institution name must be at least 3 characters.',
            'institution.max' => 'Institution name may not be greater than 255 characters.',
        ]);

        $normalizedPhone = null;

        if (!empty($input['mobile_phone'])) {
            $normalizedPhone = '0' . ltrim($input['mobile_phone'], '0');
        }

        $validator->after(function ($validator) use ($input, $normalizedPhone) {

            // EMAIL OTP CHECK
            $verified = EmailVerification::where('email', $input['email'])
                ->where('verified', true)
                ->exists();

            if (! $verified) {
                $validator->errors()->add('email', 'Email has not been verified.');
            }

            // NIK UNIQUE CHECK
            if (!empty($input['nik'])) {
                if (Participant::where('nik', $input['nik'])->whereNull('deleted_at')->exists()) {
                    $validator->errors()->add('nik', 'This NIK is already registered.');
                }
            }

            // MOBILE UNIQUE CHECK
            if ($normalizedPhone) {
                if (
                    Participant::where('mobile_phone', $normalizedPhone)
                        ->whereNull('deleted_at')
                        ->exists()
                ) {
                    $validator->errors()->add(
                        'mobile_phone',
                        'This mobile phone number is already registered.'
                    );
                }
            }

        });

        /**
         * 🔐 INI KUNCI UTAMANYA
         * Jika gagal → set session flag SEBELUM exception dilempar
         */
        if ($validator->fails()) {

            // kalau email sudah verified → simpan ke session
            if (
                EmailVerification::where('email', $input['email'])
                    ->where('verified', true)
                    ->exists()
            ) {
                session()->flash('email_verified_session', true);
            }

            $validator->validate(); // lempar ValidationException
        }


        // ROLE = PARTICIPANT
        $role = Role::where('name', 'participant')->firstOrFail();

        // CREATE USER
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => now(),
            'password' => Hash::make($input['password']),
            'role_id' => $role->id,
        ]);

        // CREATE PARTICIPANT PROFILE
        Participant::create([
            'full_name' => $input['name'],
            'email' => $input['email'],
            'nik' => $input['nik'] ?? null,
            'mobile_phone' => $normalizedPhone,
            'institution' => $input['institution'] ?? null,
            'participant_category_id' => $input['participant_category_id'],
            'registration_type' => $input['registration_type'],
        ]);

        return $user;
    }

}
