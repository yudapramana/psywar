<?php

use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\BoardMemberController;
use App\Http\Controllers\MeetingAtGlanceController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\Dashboard\BuyPackageController;
use App\Http\Controllers\Dashboard\MyPackageController;
use App\Http\Controllers\Dashboard\MyScheduleController;
use App\Http\Controllers\Dashboard\PaymentController;
use App\Http\Controllers\Dashboard\SubmissionController;
use App\Http\Controllers\EScienceController;
use App\Http\Controllers\RegistrationPageController;
use App\Models\ServiceAccount;

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

Route::middleware('auth:sanctum')->get('/debug-auth', function () {
    $service = ServiceAccount::where('name', 'admin-app')->first();
    dd($service->plainTextToken);
});

Route::get('/dashboard', function () {
    return redirect()->route('dashboard.my-schedule');
})->name('dashboard.index');

Route::middleware(['auth'])->prefix('dashboard')->group(function () {

    Route::get(
        '/submissions/{paper}/download',
        [SubmissionController::class, 'download']
    )->name('submissions.download');

    Route::get(
        '/submissions/{paper}/preview',
        [SubmissionController::class, 'previewPdf']
    )->name('submissions.preview');

    Route::get('/my-package', [MyPackageController::class, 'index'])
        ->middleware('auth')
        ->name('dashboard.my-package');

    Route::get('/my-schedule', [MyScheduleController::class, 'index'])
        ->middleware('auth')
        ->name('dashboard.my-schedule');

    // Step 1: Form Buy Package
    Route::get('/buy-package', [BuyPackageController::class, 'create'])
        ->name('dashboard.buy-package');

    // Step 2: Submit Buy Package
    Route::post('/buy-package', [BuyPackageController::class, 'store'])
        ->name('dashboard.buy-package.store');

    // ⬇️ AJAX price lookup
    Route::get('/buy-package/price', [BuyPackageController::class, 'getPrice'])
        ->name('dashboard.buy-package.price');

    // Step 3: Payment Method
    // Route::get('/payment/{registration}', [PaymentController::class, 'create'])
    //     ->name('dashboard.payment');


     // STEP 1 — choose bank
    Route::get('/payment/choose-bank', [PaymentController::class, 'chooseBank'])
        ->name('dashboard.payment.choose-bank');

    Route::post('/payment/choose-bank', [PaymentController::class, 'storeBank'])
        ->name('dashboard.payment.store-bank');

    // STEP 2 — waiting transfer (show unique code & rekening)
    Route::get('/payment/transfer', [PaymentController::class, 'waitingTransfer'])
        ->name('dashboard.payment.transfer');

    // STEP 3 — upload proof
    Route::get('/payment/upload-proof', [PaymentController::class, 'uploadProof'])
        ->name('dashboard.payment.upload-proof');

    Route::post('/payment/upload-proof', [PaymentController::class, 'storeProof'])
        ->name('dashboard.payment.store-proof');

    Route::get('/completed', [PaymentController::class,'completed'])
        ->name('dashboard.payment.completed');


       

    // Route::get('/submission', [SubmissionController::class, 'index'])->name('dashboard.submission.index');
    // Route::get('/submission/create', [SubmissionController::class, 'create'])->name('dashboard.submission.create');
    // Route::post('/submission', [SubmissionController::class, 'store'])->name('dashboard.submission.store');
    // Route::get('/submission/{paper}', [SubmissionController::class, 'show'])->name('dashboard.submission.show');
    // Route::delete('/submission/{paper}', [SubmissionController::class, 'destroy'])->name('dashboard.submission.destroy');

    Route::prefix('submission')->name('dashboard.submission.')->group(function () {

        // LIST & CREATE
        Route::get('/', [SubmissionController::class, 'index'])->name('index');
        Route::get('/create', [SubmissionController::class, 'create'])->name('create');
        Route::post('/', [SubmissionController::class, 'store'])->name('store');

        // DETAIL
        Route::get('/{paper:uuid}', [SubmissionController::class, 'show'])->name('show');

        // ✏️ EDIT DRAFT
        Route::get('/{paper:uuid}/edit', [SubmissionController::class, 'edit'])->name('edit');
        Route::put('/{paper:uuid}', [SubmissionController::class, 'update'])->name('update');

        // 🚀 SUBMIT FINAL (LOCK AFTER)
        Route::post('/{paper:uuid}/submit', [SubmissionController::class, 'submit'])
            ->name('submit');

        // 🗑 DELETE (DRAFT ONLY)
        Route::delete('/{paper:uuid}', [SubmissionController::class, 'destroy'])
            ->name('destroy');
    });
});



Route::get('/registration', [RegistrationPageController::class, 'index'])
    ->name('registration');


Route::get('/captcha/refresh', function () {
    session()->forget('captcha_code');
})->name('captcha.refresh');


Route::get('/captcha', [CaptchaController::class, 'generate'])
    ->name('captcha.generate');


Route::get('/venue', fn () => view('pages.venue'))->name('venue');

// Route::get('/e-science/abstracts-cases-submission', function () {
//     return view('pages.abstract-case-submission');
// })->name('escience.abstracts-cases-submission');

Route::prefix('e-science')->name('escience.')->group(function () {

    Route::get('/abstracts-cases-submission',
        [EScienceController::class, 'submission']
    )->name('abstracts-cases-submission');

    Route::get('/accepted-research-case',
        [EScienceController::class, 'accepted']
    )->name('accepted-research-case');

});

Route::get('/', function () {
    return view('pages.landing');
})->name('landing');

Route::prefix('program')->group(function () {
    Route::get('/meeting-at-glance', [ProgramController::class, 'meetingAtGlance'])
        ->name('program.meeting-at-glance');

    Route::get('/full-program', [ProgramController::class, 'fullProgram'])
        ->name('program.full-program');

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




Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/email/send-otp', [EmailVerificationController::class, 'sendOtp'])
    ->name('email.sendOtp');
    // ->middleware('throttle:5,10')


Route::post('/email/verify-otp', [EmailVerificationController::class, 'verifyOtp'])
    ->name('email.verifyOtp');
// ->middleware('throttle:5,10')

Route::post('/api/check-participant-identity', [
    \App\Http\Controllers\Api\ParticipantCheckController::class,
    'check'
])->middleware('throttle:20,1')->name('api.participant.check');
