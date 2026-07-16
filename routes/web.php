<?php
use App\Http\Controllers\AreaSelectionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
//use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Api\ComplaintController;

// ⚠️ কন্ট্রোলারের সঠিক পাথ নিশ্চিত করা হলো (আপনার প্রজেক্টের স্ট্রাকচার অনুযায়ী)
//use App\Http\Controllers\Web\AreaSelectionController; 
// যদি এরর দেয়, তবে শুধু নিচের লাইনটির কমেন্ট (//) তুলে দিন এবং উপরের লাইনটি কমেন্ট করুন:
// use App\Http\Controllers\AreaSelectionController;

/*
|--------------------------------------------------------------------------
| পাবলিক সিটিজেন রাউটস (সাধারণ নাগরিকদের জন্য)
|--------------------------------------------------------------------------
*/

// হোমপেজ এবং অন্যান্য নাগরিক রাউটগুলো 'check.area' মিডলওয়্যারের আন্ডারে থাকবে
Route::middleware(['check.area'])->group(function () {
    // মেইন ল্যান্ডিং পেজ
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // ওয়েবসাইট থেকে অভিযোগ ট্র্যাক করার রাউট
    Route::post('/track', [HomeController::class, 'track'])->name('complaint.track');

    // ফর্মের পেজটি দেখার জন্য (GET)
    Route::get('/complaint/create', [HomeController::class, 'create'])->name('complaint.create');

    // ফর্ম সাবমিট করার জন্য (POST)
    Route::post('/complaint/submit', [HomeController::class, 'store'])->name('complaint.submit');
});

/*
|--------------------------------------------------------------------------
| এলাকা নির্বাচন ও AJAX এপিআই রাউটস (মিডলওয়্যারের বাইরে রাখা হলো)
|--------------------------------------------------------------------------
*/
// ১. এলাকা নির্বাচন পেজ ও ডাটা সেভ
Route::get('/select-area', [AreaSelectionController::class, 'showSelectionForm'])->name('area.select');
Route::post('/save-area', [AreaSelectionController::class, 'saveArea'])->name('area.save');

// ২. জিপিএস লোকেশন ডিটেক্ট করার রাউট
Route::get('/detect-location', [AreaSelectionController::class, 'detectLocation'])->name('detect.location');

// ৩. ড্রপডাউনের জন্য AJAX এপিআই রাউটস (সিঙ্গেল এন্ট্রি নিশ্চিত করা হলো)
Route::get('/get-districts/{division_id}', [AreaSelectionController::class, 'getDistricts']);
Route::get('/get-upazilas/{district_id}', [AreaSelectionController::class, 'getUpazilas']);
Route::get('/get-unions/{upazila_id}', [AreaSelectionController::class, 'getUnions']);



Route::post('/complaint/{id}/comment', [ComplaintController::class, 'storeComment'])->name('complaint.comment'); //comment

/*
|--------------------------------------------------------------------------
| অফিশিয়াল অ্যাডমিন অথেন্টিকেশন রাউটস
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