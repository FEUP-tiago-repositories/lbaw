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

Route::middleware(['auth'])->group(function () {

    // ============================================
    // M04: REVIEWS API (R401-R404)
    // ============================================
    Route::get('/space/{space_id}/reviews', [ReviewController::class, 'index']);           // R401
    Route::post('/space/{space_id}/reviews', [ReviewController::class, 'store']);          // R402

    Route::get('/review/{review_id}/response', [ResponseController::class, 'show']);       // R403
    Route::post('/review/{review_id}/response', [ResponseController::class, 'store']);     // R404

    // ============================================
    // M04: BOOKINGS API (R406-R408)
    // ============================================
    Route::post('/space/{space_id}/schedule/{schedule_id}/bookings', [BookingController::class, 'store']);        // R406
    Route::put('/space/{space_id}/schedule/{schedule_id}/bookings/{booking_id}', [BookingController::class, 'update']); // R407 (PUT!)
    Route::patch('/space/{space_id}/schedule/{schedule_id}/bookings/{booking_id}/cancel', [BookingController::class, 'cancel']); // R408

// Analisar estas rotas que se seguem q n estão no openapi!

    // ============================================
    // SCHEDULES
    // ============================================

    Route::get('/space/{space_id}/schedules', [ScheduleController::class, 'index']);
    Route::post('/space/{space_id}/schedules', [ScheduleController::class, 'store']);
    Route::patch('/space/{space_id}/schedules/{schedule_id}', [ScheduleController::class, 'update']);
    Route::delete('/space/{space_id}/schedules/{schedule_id}', [ScheduleController::class, 'destroy']);
    Route::get('/space/{space_id}/schedules/available', [BookingController::class, 'getAvailableSchedules']);

    // ============================================
    // PAYMENT
    // ============================================
    Route::post('/bookings/confirm-payment', [BookingController::class, 'confirmPayment'])->middleware('auth');
    Route::post('/bookings/confirm-update-payment', [BookingController::class, 'confirmUpdatePayment'])->middleware('auth');
    // ============================================
    // NOTIFICATIONS
    // ============================================
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});
