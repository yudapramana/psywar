<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Controllers\Auth\CaptchaController;
use App\Models\User;
use App\Models\ParticipantCategory;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Illuminate\Validation\ValidationException;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        // ======================
        // Reset Password
        // ======================

        Fortify::viewPrefix('auth.');

        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));

        // 🔴 WAJIB ADA
        Fortify::requestPasswordResetLinkView(fn () =>
            view('auth.forgot-password')
        );

        // Fortify::resetPasswordView(function ($request) {
        //     dd($request->all());
        // });

        Fortify::resetPasswordView(fn ($request) =>
            view('auth.reset-password', [
                'request' => $request
            ])
        );
        
        Fortify::authenticateUsing(function (Request $request) {

            // ======================
            // VALIDASI CAPTCHA
            // ======================
            if (! CaptchaController::validate($request->captcha)) {
                throw ValidationException::withMessages([
                    'captcha' => 'Captcha tidak sesuai.',
                ]);
            }

            // ======================
            // AMBIL USER
            // ======================
            $user = User::with('role')
                ->where('email', $request->email)
                ->first();

            // ======================
            // CEK ROLE (HARUS PARTICIPANT)
            // ======================
            if (! $user->role || $user->role->slug !== 'participant') {
                throw ValidationException::withMessages([
                    'email' => 'Akun ini bukan akun peserta.',
                ]);
            }

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return null;
            }

            

            // ======================
            // LOGIN BERHASIL
            // ======================
            return $user;
        });

        
        // ✅ REGISTER VIEW (FIXED)
        Fortify::registerView(function () {
            $participantCategories = ParticipantCategory::orderBy('name')->get();
            // dd($participantCategories);
            return view('auth.register', [
                'participantCategories' => $participantCategories,
            ]);
        });

        // ✅ LOGIN VIEW
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // ✅ REDIRECT SETELAH LOGIN
        Fortify::redirects('login', '/dashboard/my-schedule');

        // ✅ AUTHENTICATION
        // Fortify::authenticateUsing(function (Request $request) {
        //     $user = User::where('email', $request->email)->first();

        //     if ($user && Hash::check($request->password, $user->password)) {
        //         return $user;
        //     }

        //     return null;
        // });

        // ✅ FORTIFY ACTIONS
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(
            RedirectIfTwoFactorAuthenticatable::class
        );

        // ✅ RATE LIMITER
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())) . '|' . $request->ip()
            );

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->session()->get('login.id')
            );
        });
    }
}
