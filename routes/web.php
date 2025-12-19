<?php

use App\Http\Controllers\Admin\AdminController;
// ============================================
// CONTROLLERS
// ============================================
use App\Http\Controllers\Admin\ReviewManagementController;
use App\Http\Controllers\Admin\SpaceManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\RecoverController;
use App\Http\Controllers\ResponseController;

// Admin Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SpaceController;
// Auth Controllers
use App\Http\Controllers\StaticController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ============================================
// M01: HOME & STATIC PAGES (R101-R105)
// ============================================

$middleware = [];
if (! app()->environment('local')) {
    $middleware = ['auth', 'admin']; // login + admin só em produção
}

Route::get('/', [HomeController::class, 'index'])->name('home');                           // R101
Route::get('/about-us', [StaticController::class, 'about'])->name('about');                // R102
Route::get('/faq', [StaticController::class, 'faq'])->name('faq');                        // R103
Route::get('/terms-of-service', [StaticController::class, 'terms'])->name('terms');        // R104
Route::get('/contact-us', [StaticController::class, 'contact'])->name('contact');          // R105

// ============================================
// M02: AUTHENTICATION (R201-R207)
// ============================================

Route::controller(LoginController::class)->group(function () {
    Route::get('/sign-in', 'showLoginForm')->name('login');                                // R201
    Route::post('/sign-in', 'authenticate');                                               // R202
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/sign-up', 'showRegistrationForm')->name('register');                      // R203
    Route::post('/sign-up', 'register');                                                   // R204
});

Route::controller(RecoverController::class)->group(function () {
    Route::get('/password/reset/{token}', 'showResetForm')->name('password.reset');
    Route::post('/password/reset', 'resetPassword')->name('password.update');
    Route::post('/sign-in/recover', 'sendRecoveryEmail');
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth'); // R207

// ============================================
// M02: USER PROFILES (R205-R206)
// ============================================

Route::middleware(['auth'])->group(function () {
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');       // R205
    Route::patch('/users/{id}', [UserController::class, 'update'])->name('users.update'); // R206
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');  // Form for R206
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

// ============================================
// M03: SPACES (R301-R308)
// ============================================

// Public routes
Route::get('/spaces', [SpaceController::class, 'index'])->name('spaces.index');             // R303
Route::get('/spaces/search', [SearchController::class, 'search'])->name('spaces.search');

// Authenticated routes - /spaces/create MUST come BEFORE /spaces/{space}
Route::middleware(['auth', 'business.owner'])->group(function () {
    Route::get('/spaces/create', [SpaceController::class, 'create'])->name('spaces.create');   // R301 (form)
    Route::post('/spaces', [SpaceController::class, 'store'])->name('spaces.store');          // R302 (action)
});

// Public parameterized route - comes after specific routes
Route::get('/spaces/{space}', [SpaceController::class, 'show'])->name('spaces.show');        // R304

// More authenticated routes with {space} parameter
Route::middleware(['auth'])->group(function () {
    Route::get('/spaces/{space}/edit', [SpaceController::class, 'edit'])->name('spaces.edit');
    Route::patch('/spaces/{space}', [SpaceController::class, 'update'])->name('spaces.update');   // R305
    Route::delete('/spaces/{space}', [SpaceController::class, 'destroy'])->name('spaces.destroy'); // R306

    // Favorites (R307-R308)
    Route::post('/spaces/{space_id}/favorite', [SpaceController::class, 'favorite'])->name('spaces.favorite');     // R307
    Route::patch('/spaces/{space_id}/favorite', [SpaceController::class, 'unfavorite'])->name('spaces.unfavorite'); // R308
});

// ============================================
// M04: BOOKINGS (R405)
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/users/{user_id}/my_reservations', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::get('/bookings/payment-success', fn () => view('bookings.modals.payment-success'))->name('bookings.payment.success');
    // Business Owner - Manage Reservations
    Route::get('/manage-reservations', [BookingController::class, 'selectSpace'])->name('spaces.bookings.select');
    Route::get('/spaces/{space}/bookings', [BookingController::class, 'spaceBookings'])->name('spaces.bookings');
});

// ============================================
// M04: REVIEWS and RESPONSES
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/responses',[App\Http\Controllers\ResponseController::class, 'store'])->name('responses.store');
});

// ============================================
// NOTIFICATIONS (extensão)
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// ============================================
// M05: ADMIN ROUTES (R501-R518)
// ============================================
Route::middleware($middleware)->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');                 // R501

    // Users Management (R502-R509)
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');           // R502
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');  // R506
    Route::post('/users/create', [UserManagementController::class, 'store'])->name('users.store');   // R507
    Route::get('/users/{id}', [UserManagementController::class, 'show'])->name('users.show');        // R503
    Route::patch('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');  // R504
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy'); // R505
    Route::post('/users/{id}/ban', [UserManagementController::class, 'ban'])->name('users.ban');     // R508
    Route::post('/users/{id}/unban', [UserManagementController::class, 'unban'])->name('users.unban'); // R509
    Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');

    // Spaces Management (R511-R515)
    Route::get('/spaces', [SpaceManagementController::class, 'index'])->name('spaces.index');        // R511
    Route::get('/spaces/{id}', [SpaceManagementController::class, 'show'])->name('spaces.show');     // R512
    Route::delete('/spaces/{id}', [SpaceManagementController::class, 'destroy'])->name('spaces.destroy'); // R513
    Route::post('/spaces/{id}/close', [SpaceManagementController::class, 'close'])->name('spaces.close');    // R514
    Route::post('/spaces/{id}/reopen', [SpaceManagementController::class, 'reopen'])->name('spaces.reopen'); // R515

    // Reviews Management (R516-R518)
    Route::get('/reviews', [ReviewManagementController::class, 'index'])->name('reviews.index');     // R516
    Route::get('/reviews/{id}', [ReviewManagementController::class, 'show'])->name('reviews.show');  // R517
    Route::delete('/reviews/{id}', [ReviewManagementController::class, 'destroy'])->name('reviews.destroy'); // R518
});
