<?php

use App\Http\Controllers\Api\SecureFileController;
use App\Http\Controllers\Internal\ServiceTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::get('/anjir', function() {
    return 'anjaylah';
});

Route::post(
    '/internal/service-token',
    [ServiceTokenController::class, 'issue']
);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {

    // 1️⃣ Admin minta signed URL
    Route::get(
        '/secure/payments/proof/{payment}/signed',
        [SecureFileController::class, 'getSignedPaymentProof']
    );

});

// 2️⃣ Streaming endpoint (SIGNED)
// Route::get(
//     '/secure/payments/proof/{payment}',
//     [SecureFileController::class, 'streamPaymentProof']
// )->name('secure.payment.proof.stream')->middleware('signed');

Route::middleware('auth:sanctum')->get(
    '/secure/payments/proof/{payment}/stream',
    [SecureFileController::class, 'streamPaymentProof']
);
