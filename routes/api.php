<?php

use Illuminate\Support\Facades\Route;

// ============================================
// CONTROLLERS
// ============================================

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\NotificationController;

// ============================================
// API ROUTES
// ============================================
// Note: All routes here need 'web' middleware for session-based auth

Route::middleware(['web'])->group(function () {
    // ============================================
    // M04: REVIEWS API (R401-R404)
    // ============================================
    Route::get('/space/{space_id}/reviews', [ReviewController::class, 'index']);           // R401
    Route::post('/space/{space_id}/reviews', [ReviewController::class, 'store']);          // R402

    Route::get('/review/{review_id}/response', [ResponseController::class, 'show']);       // R403
    Route::post('/review/{review_id}/response', [ResponseController::class, 'store']);     // R404

    // ============================================
    // M04: SCHEDULES API
    // ============================================
    Route::get('/space/{space_id}/schedule', [ScheduleController::class, 'index']);

    // Protected schedule management routes (business owner only)
    Route::middleware(['auth', 'business.owner'])->group(function () {
        Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
        Route::post('/space/{space_id}/schedule', [ScheduleController::class, 'store']);
        Route::patch('/space/{space_id}/schedule/{schedule_id}', [ScheduleController::class, 'update']);
        Route::put('/schedules/{id}', [ScheduleController::class, 'update']); // Alternative route for modal
        Route::delete('/space/{space_id}/schedule/{schedule_id}', [ScheduleController::class, 'destroy']);
        Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']); // Alternative route for modal
    });

    // ============================================
    // M04: BOOKINGS API (R406-R408)
    // ============================================
    Route::middleware(['auth'])->group(function () {
        Route::post('/space/{space_id}/schedule/{schedule_id}/bookings', [BookingController::class, 'store']); // R406
        Route::put('/space/{space_id}/schedule/{schedule_id}/bookings/{booking}', [BookingController::class, 'update']); // R407
        Route::patch('/space/{space_id}/schedule/{schedule_id}/bookings/{booking}/cancel', [BookingController::class, 'cancel']); // R408
    });

    // ============================================
    // PAYMENT
    // ============================================
    Route::middleware(['auth'])->group(function () {
        Route::post('/bookings/confirm-payment', [BookingController::class, 'confirmPayment']);
        Route::post('/bookings/confirm-update-payment', [BookingController::class, 'confirmUpdatePayment']);
    });

    // ============================================
    // NOTIFICATIONS
    // ============================================
    Route::middleware(['auth'])->group(function () {
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    });
});