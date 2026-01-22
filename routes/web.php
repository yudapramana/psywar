<?php

use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\BoardMemberController;
use App\Http\Controllers\MeetingAtGlanceController;
use App\Http\Controllers\ProgramController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/venue', fn () => view('pages.venue'))->name('venue');

Route::get('/e-science/abstracts-cases-submission', function () {
    return view('pages.abstract-case-submission');
})->name('escience.abstracts-cases-submission');


Route::get('/', function () {
    return view('pages.landing');
})->name('landing');

Route::prefix('program')->group(function () {
    Route::get('/meeting-at-glance', [ProgramController::class, 'meetingAtGlance'])
        ->name('program.meeting-at-glance');

    Route::get('/resources', [ProgramController::class, 'resources'])
        ->name('program.resources');
});

Route::prefix('about')->group(function () {
    Route::get('/overview', [AboutController::class, 'overview'])
        ->name('about.overview');

    Route::get('/board-members', [AboutController::class, 'boardMembers'])
        ->name('about.board-members');

    Route::get('/galleries', [AboutController::class, 'galleries'])
        ->name('about.galleries');
});

Route::get('/schedule', function () {
    return view('pages.schedule');
})->name('schedule');

Route::get('/speakers', function () {
    return view('pages.speakers');
})->name('speakers');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/email/send-otp', [EmailVerificationController::class, 'sendOtp'])
    ->name('email.sendOtp');

Route::post('/email/verify-otp', [EmailVerificationController::class, 'verifyOtp'])
    ->name('email.verifyOtp');
