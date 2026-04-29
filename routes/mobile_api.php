<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MobileApiController;
use App\Http\Controllers\Api\SoloParentApiController;

// ============================================
// PUBLIC ENDPOINTS
// ============================================

// Municipalities list (no auth, no throttle — static data)
Route::get('/municipalities', [MobileApiController::class, 'municipalities']);

// Auth endpoints — rate-limited to prevent abuse
Route::middleware('throttle:3,1')->post('/register', [MobileApiController::class, 'register']);
Route::middleware('throttle:10,1')->post('/login', [MobileApiController::class, 'login']);

// OTP endpoints — strict rate limiting (5 per minute per IP)
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/verify-otp', [MobileApiController::class, 'verifyOtp']);
    Route::post('/resend-otp', [MobileApiController::class, 'resendOtp']);
    Route::post('/forgot-password', [MobileApiController::class, 'forgotPassword']);
    Route::post('/reset-password', [MobileApiController::class, 'resetPassword']);
});

// Public AI Chatbot (rate limited to 10 requests per minute)
Route::post('/chatbot/message', [\App\Http\Controllers\ChatbotController::class, 'reply'])
    ->middleware('throttle:10,1');

// ============================================
// PROTECTED ENDPOINTS (Sanctum)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/user', [MobileApiController::class, 'user']);

    // Dashboard & announcements
    Route::get('/dashboard', [MobileApiController::class, 'dashboard']);
    Route::get('/announcements', [MobileApiController::class, 'announcements']);

    // Applications CRUD
    Route::get('/applications', [MobileApiController::class, 'applications']);
    Route::get('/applications/{id}', [MobileApiController::class, 'applicationDetail']);
    Route::post('/applications', [MobileApiController::class, 'submitApplication']);
    Route::put('/applications/{id}', [MobileApiController::class, 'resubmitApplication']);

    // File re-upload (individual file)
    Route::post('/files/{fileId}/reupload', [MobileApiController::class, 'reuploadFile']);

    // Logout
    Route::post('/logout', [MobileApiController::class, 'logout']);

    // Solo Parent ID Application
    Route::prefix('solo-parent')->group(function () {
        Route::get('/slots', [SoloParentApiController::class, 'getAvailableSlots']);
        Route::post('/appointments', [SoloParentApiController::class, 'bookAppointment']);
        Route::get('/appointments', [SoloParentApiController::class, 'getAppointments']);
        Route::delete('/appointments/{id}', [SoloParentApiController::class, 'cancelAppointment']);
        Route::get('/application', [SoloParentApiController::class, 'getApplication']);
        Route::post('/requirements/upload', [SoloParentApiController::class, 'uploadRequirement']);
        Route::get('/notifications', [SoloParentApiController::class, 'getNotifications']);
        Route::put('/notifications/{id}/read', [SoloParentApiController::class, 'markNotificationRead']);
    });

    // Device Token Management
    Route::post('/device-token', [MobileApiController::class, 'registerDeviceToken']);
    Route::delete('/device-token', [MobileApiController::class, 'removeDeviceToken']);
});
