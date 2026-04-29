<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangayDataController;

// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/', function () {
    return redirect('/analysis');
});

// ============================================
// PUBLIC ANALYSIS PAGES
// ============================================
Route::prefix('analysis')->name('analysis.')->group(function () {
    Route::get('/', [AnalysisController::class, 'index'])->name('index');
    Route::get('/municipality/{name}', [AnalysisController::class, 'municipality'])->name('municipality');
    Route::get('/demographic', [AnalysisController::class, 'demographic'])->name('demographic');
    Route::get('/programs', [AnalysisController::class, 'programs'])->name('programs');
});

// ============================================
// AI CHATBOT (public — guests + auth users)
// ============================================
Route::post('/chatbot/message', [App\Http\Controllers\ChatbotController::class, 'reply'])->name('chatbot.reply');

// ============================================
// GUEST ROUTES (login, register, OTP, password reset)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/verify-otp', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');

    // Password Reset
    Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.update');

    // Change Password (after email verification)
    Route::get('/change-password', [App\Http\Controllers\Auth\ChangePasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [App\Http\Controllers\Auth\ChangePasswordController::class, 'change'])->name('password.change.submit');
});

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// SESSION HEARTBEAT — keeps session alive while tab is open
Route::middleware(['auth'])->get('/session/ping', function () {
    return response()->json(['ok' => true, 'ts' => now()->timestamp]);
})->name('session.ping');

// ============================================
// USER ROUTES (authenticated users with role 'user')
// ============================================
Route::middleware(['auth', 'ensure_role:user'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/programs', [UserController::class, 'programs'])->name('user.programs');
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/user/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/user/announcements', [UserController::class, 'announcements'])->name('user.announcements');
    Route::get('/user/my-requirements', [UserController::class, 'myRequirements'])->name('user.my-requirements');
    Route::put('/user/resubmit-requirement/{fileUploadId}', [UserController::class, 'resubmitRequirement'])->name('user.resubmit-requirement');
    Route::post('/user/resubmit-requirement/{fileUploadId}', [UserController::class, 'resubmitRequirement']); // Fallback for browsers that don't support PUT
    Route::get('/user/apply/AICS/aics', [UserController::class, 'aicsCategory'])->name('user.aics-category');
    Route::get('/user/apply/AICS/medical', [UserController::class, 'aicsMedical'])->name('user.aics-medical');
    Route::get('/user/apply/AICS/burial', [UserController::class, 'aicsBurial'])->name('user.aics-burial');
    Route::get('/user/apply/{program}', [ApplicationController::class, 'create'])->name('user.apply');
    Route::get('/user/pwd-application', [UserController::class, 'pwdApplication'])->name('user.pwd-application');
    Route::get('/user/pwd-fillable-form', [UserController::class, 'pwdFillableForm'])->name('user.pwd-form');
    Route::post('/user/pwd-fillable-form', [UserController::class, 'pwdFormSubmit'])->name('user.pwd-form.submit');
    Route::get('/user/solo-parent-application', [UserController::class, 'soloParentApplication'])->name('user.solo-parent-application');
    Route::post('/user/pwd-upload-requirement', [UserController::class, 'uploadPwdRequirement'])->name('user.pwd-upload-requirement');
    Route::post('/user/aics-medical-upload', [UserController::class, 'uploadAicsMedical'])->name('user.aics-medical-upload');
    Route::post('/user/aics-burial-upload', [UserController::class, 'uploadAicsBurial'])->name('user.aics-burial-upload');
    Route::post('/user/aics-medical-upload-batch', [UserController::class, 'uploadAicsMedicalBatch'])->name('user.aics-medical-upload-batch');
    Route::post('/user/aics-burial-upload-batch', [UserController::class, 'uploadAicsBurialBatch'])->name('user.aics-burial-upload-batch');
    Route::post('/user/mark-notifications-viewed', [UserController::class, 'markNotificationsViewed'])->name('user.mark-notifications-viewed');

    // Appointment routes (Solo Parent)
    Route::get('/user/appointments/slots', [\App\Http\Controllers\AppointmentController::class, 'getAvailableSlots'])->name('user.appointments.slots');
    Route::post('/user/appointments', [\App\Http\Controllers\AppointmentController::class, 'store'])->name('user.appointments.store');
    Route::post('/user/appointments/{id}/cancel', [\App\Http\Controllers\AppointmentController::class, 'cancel'])->name('user.appointments.cancel');

    // Chat routes
    Route::get('/chat/admins', [App\Http\Controllers\ChatController::class, 'getAdmins'])->name('chat.admins');
    Route::get('/chat/messages/{adminId}', [App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/unread-count', [App\Http\Controllers\ChatController::class, 'getUnreadCount'])->name('chat.unread');
});

// ============================================
// SOLO PARENT APPLICATION ROUTES
// ============================================
Route::middleware(['auth', 'ensure_role:user'])->group(function () {
    Route::get('/apply/solo-parent', [ApplicationController::class, 'showSoloParentForm'])->name('solo-parent.apply');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show');
});

// ============================================
// REQUIREMENT UPLOAD ROUTES
// ============================================
Route::middleware(['auth', 'ensure_role:user'])->prefix('applications')->name('applications.')->group(function () {
    Route::get('/{applicationId}/requirements', [ApplicationController::class, 'showRequirements'])->name('requirements');
    Route::post('/{applicationId}/requirement/upload', [ApplicationController::class, 'uploadRequirement'])->name('requirement.upload');
    Route::delete('/{applicationId}/requirement/delete', [ApplicationController::class, 'deleteRequirement'])->name('requirement.delete');
});

// Batch upload for requirements
Route::middleware(['auth'])->post('/applications/upload-batch', [ApplicationController::class, 'uploadBatch'])->name('applications.upload-batch');

// ============================================
// BARANGAY ANALYSIS ROUTES (public/admin accessible)
// ============================================
Route::prefix('barangay-analysis')->name('barangay-analysis.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\BarangayAnalysisController::class, 'index'])->name('index');
    Route::get('/programs', [App\Http\Controllers\Admin\BarangayAnalysisController::class, 'programs'])->name('programs');
    Route::get('/applicants', [App\Http\Controllers\Admin\BarangayAnalysisController::class, 'applicants'])->name('applicants');
    Route::get('/{barangay}', [App\Http\Controllers\Admin\BarangayAnalysisController::class, 'showBarangay'])->name('show');
});

// ============================================
// API ROUTES FOR BARANGAY DATA
// ============================================
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('/barangays/{municipality}/{year}', [BarangayDataController::class, 'getByYear']);
});