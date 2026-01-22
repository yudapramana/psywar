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
        Validator::make($input, [
            // USER
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],

            // PARTICIPANT
            'participant_category_id' => ['required', 'exists:participant_categories,id'],
            'registration_type' => ['required', 'in:sponsored,non_sponsored'],

            'nik' => ['nullable', 'digits_between:8,20'],
            'mobile_phone' => ['nullable', 'string', 'max:20'],
            'institution' => ['nullable', 'string', 'max:255'],
        ], [
            // CUSTOM MESSAGES
            'participant_category_id.required' => 'Please select participant category.',
            'participant_category_id.exists' => 'Selected category is invalid.',

            'registration_type.required' => 'Please select registration type.',
            'registration_type.in' => 'Registration type is invalid.',

            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',

            'nik.digits_between' => 'NIK must be between 8–20 digits.',
        ])->after(function ($validator) use ($input) {
            $verified = EmailVerification::where('email', $input['email'])
                ->where('verified', true)
                ->exists();

            if (! $verified) {
                $validator->errors()->add('email', 'Email has not been verified.');
            }
        })->validate();

        // ROLE = PARTICIPANT (ID = 4)
        $role = Role::where('name', 'participant')->firstOrFail();

        // CREATE USER
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role_id' => $role->id,
        ]);

        // CREATE PARTICIPANT PROFILE
        Participant::create([
            'full_name' => $input['name'],
            'email' => $input['email'],
            'nik' => $input['nik'] ?? null,
            'mobile_phone' => $input['mobile_phone'] ?? null,
            'institution' => $input['institution'] ?? null,
            'participant_category_id' => $input['participant_category_id'],
            'registration_type' => $input['registration_type'],
        ]);

        return $user;
    }
}
