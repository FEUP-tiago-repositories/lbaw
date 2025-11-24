<?php

use Illuminate\Support\Facades\Route;

// ============================================
// CONTROLLERS
// ============================================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\BookingController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\SpaceManagementController;
use App\Http\Controllers\Admin\ReviewManagementController;

// Auth Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

// ============================================
// M01: HOME & STATIC PAGES (R101-R105)
// ============================================

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
Route::get('/space', [SpaceController::class, 'index'])->name('spaces.index');             // R303
Route::get('/space/{space_id}', [SpaceController::class, 'show'])->name('spaces.show');    // R304

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/add-space', [SpaceController::class, 'create'])->name('spaces.create');   // R301 (form)
    Route::post('/add-space', [SpaceController::class, 'store'])->name('spaces.store');    // R302
    Route::get('/space/{space_id}/edit', [SpaceController::class, 'edit'])->name('spaces.edit');
    Route::patch('/space/{space_id}', [SpaceController::class, 'update'])->name('spaces.update');   // R305
    Route::delete('/space/{space_id}', [SpaceController::class, 'destroy'])->name('spaces.destroy'); // R306

    // Favorites (R307-R308)
    Route::post('/space/{space_id}/favorite', [SpaceController::class, 'favorite'])->name('spaces.favorite');     // R307
    Route::patch('/space/{space_id}/favorite', [SpaceController::class, 'unfavorite'])->name('spaces.unfavorite'); // R308
});

// ============================================
// M04: BOOKINGS (R405)
// ============================================

// Route::middleware(['auth'])->group(function () {
    Route::get('/user/{user_id}/my_reservations', [BookingController::class, 'index'])->name('bookings.index'); // R405

    // Calendar para owner (se existir)
    Route::get('/owner/{ownerId}/calendar', [BookingController::class, 'calendar'])->name('bookings.calendar');
// });

// ============================================
// NOTIFICATIONS (extensão)
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

// ============================================
// M05: ADMIN ROUTES (R501-R518)
// ============================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
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
