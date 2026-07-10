<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ComplaintController;
use Illuminate\Support\Facades\Route;

// পাবলিক এপিআই (লগইন ছাড়া অ্যাক্সেস করা যাবে)
Route::prefix('v1')->group(function () {
    Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);
});

// প্রোটেক্টেড এপিআই (লগইন বা টোকেন বাধ্যতামূলক)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::post('/complaints', [ComplaintController::class, 'store']);
});