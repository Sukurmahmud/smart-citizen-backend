<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;

/*
|--------------------------------------------------------------------------
| পাবলিক সিটিজেন রাউটস (সাধারণ নাগরিকদের জন্য)
|--------------------------------------------------------------------------
*/
// মেইন ল্যান্ডিং পেজ
Route::get('/', [HomeController::class, 'index'])->name('home');

// ওয়েবসাইট থেকে অভিযোগ ট্র্যাক করার রাউট (যা মিসিং ছিল ভাই)
Route::post('/track', [HomeController::class, 'track'])->name('complaint.track');

// ওয়েবসাইট থেকে সরাসরি অভিযোগ জমা দেওয়ার রাউট
Route::post('/complaint/submit', [HomeController::class, 'storeComplaint'])->name('complaint.submit');


/*
|--------------------------------------------------------------------------
| অফিশিয়াল অ্যাডমিন অথেন্টিকেশন রাউটস
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| প্রোটেক্টেড ড্যাশবোর্ড রাউটস (শুধুমাত্র লগইন করা কর্মকর্তারা ঢুকতে পারবেন)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/complaints/{id}', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::post('/complaints/{id}/status', [DashboardController::class, 'updateStatus'])->name('dashboard.status.update');
});