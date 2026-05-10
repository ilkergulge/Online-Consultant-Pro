<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConsultantListController;

use App\Http\Controllers\CheckoutController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/consultants', [ConsultantListController::class, 'index'])->name('consultants.index');
Route::get('/consultants/{consultant}', [ConsultantListController::class, 'show'])->name('consultants.show');

use App\Http\Controllers\MeetingController;

Route::middleware(['auth', 'throttle:checkout'])->group(function () {
    Route::post('/checkout/{consultant}', [CheckoutController::class, 'process'])->name('checkout.process');
});

Route::middleware('auth')->group(function () {
    Route::get('/meeting/{appointment}', [MeetingController::class, 'show'])->name('meeting.show');
});

// Move callbacks outside of auth middleware because payment gateways return POSTs without session cookies/CSRF tokens.
// The callbacks will use the generated cache token to re-identify the session and user.
Route::match(['get', 'post'], '/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::match(['get', 'post'], '/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');

// Local route to render the shopier HTML form redirect
Route::get('/checkout/shopier-redirect', function (Illuminate\Http\Request $request) {
    $token = $request->query('token');
    $args = Illuminate\Support\Facades\Cache::get('shopier_args_' . $token);
    if (!$args) abort(404);
    return view('frontend.checkout.shopier', compact('args'));
})->name('checkout.shopier');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\SocialAuthController;

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SystemSettingController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class)->except(['create', 'store', 'show']);
    Route::resource('settings', SystemSettingController::class)->only(['index', 'store']);
});

use App\Http\Controllers\Consultant\DashboardController as ConsultantDashboardController;
use App\Http\Controllers\Consultant\ProfileController as ConsultantProfileController;
use App\Http\Controllers\Consultant\AvailabilityController;

Route::middleware(['auth', 'role:consultant'])->prefix('consultant')->name('consultant.')->group(function () {
    Route::get('/dashboard', [ConsultantDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ConsultantProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ConsultantProfileController::class, 'update'])->name('profile.update');
    Route::resource('availability', AvailabilityController::class)->except(['create', 'show', 'edit', 'update']);
});

require __DIR__.'/auth.php';
