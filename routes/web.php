<?php

use Illuminate\Support\Facades\Route;


// ============================================
// HOME & STATIC PAGES (US200-US204)
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [StaticController::class, 'about'])->name('about');
Route::get('/faq', [StaticController::class, 'faq'])->name('faq');
Route::get('/terms-of-service', [StaticController::class, 'terms'])->name('terms');
Route::get('/contact-us', [StaticController::class, 'contact'])->name('contact');

// ============================================
// AUTHENTICATION (US100-US102)
// ============================================
// - Auth::routes();
// - GET /login (US100)
// - POST /login
// - POST /logout
// - GET /register (US101)
// - POST /register
// - GET /password/reset (US102)
// - POST /password/email

// ============================================
// USER PROFILES (US300-US303)
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});

// ============================================
// SPACES (US205-US208, US500-US502)
// ============================================
Route::get('/space', [SpaceController::class, 'index'])->name('spaces.index');
Route::get('/space/{id}', [SpaceController::class, 'show'])->name('spaces.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/add-space', [SpaceController::class, 'create'])->name('spaces.create');
    Route::post('/add-space', [SpaceController::class, 'store'])->name('spaces.store');
    Route::get('/space/{id}/edit', [SpaceController::class, 'edit'])->name('spaces.edit');
    Route::patch('/space/{id}', [SpaceController::class, 'update'])->name('spaces.update');
    Route::delete('/space/{id}', [SpaceController::class, 'destroy'])->name('spaces.destroy');
});

// ============================================
// SCHEDULES (US503)
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/space/{spaceId}/schedules', [ScheduleController::class, 'index']);
    Route::post('/space/{spaceId}/schedules', [ScheduleController::class, 'store']);
    Route::patch('/space/{spaceId}/schedules/{scheduleId}', [ScheduleController::class, 'update']);
    Route::delete('/space/{spaceId}/schedules/{scheduleId}', [ScheduleController::class, 'destroy']);
});

// ============================================
// BOOKINGS (US400-US403, US505)
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/user/{userId}/my_reservations', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/owner/{ownerId}/calendar', [BookingController::class, 'calendar'])->name('bookings.calendar');

    Route::post('/api/space/{spaceId}/schedule/{scheduleId}/bookings', [BookingController::class, 'store']);
    Route::get('/api/space/{spaceId}/schedule/{scheduleId}/bookings/{bookingId}', [BookingController::class, 'show']);
    Route::put('/api/space/{spaceId}/schedule/{scheduleId}/bookings/{bookingId}', [BookingController::class, 'update']);
    Route::patch('/api/space/{spaceId}/schedule/{scheduleId}/bookings/{bookingId}/cancel', [BookingController::class, 'cancel']);
});

// ============================================
// REVIEWS (US404)
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::post('/api/space/{spaceId}/reviews', [ReviewController::class, 'store']);
    Route::patch('/api/review/{reviewId}', [ReviewController::class, 'update']);
    Route::delete('/api/review/{reviewId}', [ReviewController::class, 'destroy']);

    Route::post('/api/review/{reviewId}/response', [ResponseController::class, 'store']);
});

// ============================================
// FAVORITES (US407) - AJAX
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::post('/space/{spaceId}/favorite', [SpaceController::class, 'favorite'])->name('spaces.favorite');
    Route::delete('/space/{spaceId}/favorite', [SpaceController::class, 'unfavorite'])->name('spaces.unfavorite');
    Route::get('/user/{userId}/my_favorites', [UserController::class, 'favorites'])->name('users.favorites');
});

// ============================================
// ADMIN (US600-US602)
// ============================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\AdminController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [Admin\UserManagementController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [Admin\UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/ban', [Admin\UserManagementController::class, 'ban'])->name('users.ban');

    // Spaces
    Route::get('/spaces', [Admin\SpaceManagementController::class, 'index'])->name('spaces.index');
    Route::delete('/spaces/{id}', [Admin\SpaceManagementController::class, 'destroy'])->name('spaces.destroy');
    Route::post('/spaces/{id}/close', [Admin\SpaceManagementController::class, 'close'])->name('spaces.close');

    // Reviews
    Route::get('/reviews', [Admin\ReviewManagementController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{id}', [Admin\ReviewManagementController::class, 'destroy'])->name('reviews.destroy');
});
