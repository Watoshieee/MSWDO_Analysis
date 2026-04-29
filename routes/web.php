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
<<<<<<< HEAD
// BARANGAY ANALYSIS ROUTES (public/admin accessible)
=======
// AI CHATBOT (public — guests + auth users)
// ============================================
Route::post('/chatbot/message', [App\Http\Controllers\ChatbotController::class, 'reply'])->name('chatbot.reply');

Route::prefix('analysis')->name('analysis.')->group(function () {
    Route::get('/', [AnalysisController::class, 'index'])->name('index');
    Route::get('/municipality/{name}', [AnalysisController::class, 'municipality'])->name('municipality');
    Route::get('/demographic', [AnalysisController::class, 'demographic'])->name('demographic');
    Route::get('/programs', [AnalysisController::class, 'programs'])->name('programs');
});

// ============================================
// GUEST ROUTES
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/check-username', [RegisterController::class, 'checkUsername'])->name('check.username');

    Route::get('/verify-otp', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');
});

// Password Reset Routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
        ->name('password.update');
});

// Change Password Routes (after email verification)
Route::middleware('guest')->group(function () {
    Route::get('/change-password', [App\Http\Controllers\Auth\ChangePasswordController::class, 'showChangeForm'])
        ->name('password.change');
    Route::post('/change-password', [App\Http\Controllers\Auth\ChangePasswordController::class, 'change'])
        ->name('password.change.submit');
});
// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// SESSION HEARTBEAT — keeps session alive while tab is open
Route::middleware(['auth'])->get('/session/ping', function () {
    return response()->json(['ok' => true, 'ts' => now()->timestamp]);
})->name('session.ping');

// GENERAL DASHBOARD — users only; admins/superadmins redirected by EnsureUserRole
Route::middleware(['auth', 'ensure_role:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ============================================
// SUPER ADMIN ROUTES
// ============================================
Route::middleware(['auth', 'role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // DASHBOARD
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

    // User Management (SuperAdmin\UserController)
    Route::get('/users', [SuperAdminUserController::class, 'index'])->name('users');
    Route::post('/users', [SuperAdminUserController::class, 'store'])->name('users.create');
    Route::put('/users/{id}', [SuperAdminUserController::class, 'update'])->name('users.update');

    // Archive / Restore / Force-Delete (must come before wildcards)
    Route::get('/users/archived-json', [SuperAdminUserController::class, 'getArchivedUsers'])->name('users.archived-json');
    Route::post('/users/{id}/archive', [SuperAdminUserController::class, 'archive'])->name('users.archive');
    Route::post('/users/{id}/restore', [SuperAdminUserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}/force-delete', [SuperAdminUserController::class, 'forceDelete'])->name('users.forceDelete');

    // DATA MANAGEMENT ROUTES
    Route::prefix('data')->name('data.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'dashboard'])->name('dashboard');
        Route::get('/municipalities', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'municipalities'])->name('municipalities');
        Route::post('/municipalities/summary/save', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'saveMunicipalitySummary'])->name('municipalities.summary.save');
        // Archive routes for municipality summaries
        Route::get('/municipalities/summary/archived', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'getArchivedSummaries'])->name('municipalities.summary.archived');
        Route::post('/municipalities/summary/{id}/archive', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'archiveMunicipalitySummary'])->name('municipalities.summary.archive');
        Route::post('/municipalities/summary/{id}/restore', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'restoreMunicipalitySummary'])->name('municipalities.summary.restore');
        Route::delete('/municipalities/summary/{id}/force-delete', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'forceDeleteMunicipalitySummary'])->name('municipalities.summary.force-delete');
        Route::delete('/municipalities/summary/{id}', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'deleteMunicipalitySummary'])->name('municipalities.summary.delete');

        // ── Monthly summary routes ─────────────────────────────────────
        Route::post('/municipalities/monthly/save', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'saveMonthlySummary'])->name('municipalities.monthly.save');
        Route::post('/municipalities/monthly/{id}/edit', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'editMonthlySummary'])->name('municipalities.monthly.edit');
        Route::post('/municipalities/monthly/{id}/archive', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'archiveMonthlySummary'])->name('municipalities.monthly.archive');
        Route::post('/municipalities/monthly/{id}/restore', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'restoreMonthlySummary'])->name('municipalities.monthly.restore');
        Route::delete('/municipalities/monthly/{id}/force-delete', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'forceDeleteMonthlySummary'])->name('municipalities.monthly.force-delete');
        Route::get('/municipalities/monthly/archived', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'getArchivedMonthly'])->name('municipalities.monthly.archived');

        Route::post('/municipalities/{id}', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'updateMunicipality'])->name('municipalities.update');
        Route::get('/barangays', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'barangays'])->name('barangays');
        Route::post('/barangays', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'storeBarangay'])->name('barangays.store');
        // Specific routes BEFORE {id} wildcard to prevent conflicts
        Route::get('/barangays/{id}/edit', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'editBarangay'])->name('barangays.edit');
        Route::post('/barangays/bulk-delete', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'bulkArchiveBarangays'])->name('barangays.bulk-delete');
        Route::post('/barangays/bulk-store', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'bulkStoreBarangays'])->name('barangays.bulk-store');
        Route::get('/barangays/archived', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'getArchivedBarangays'])->name('barangays.archived');
        Route::delete('/barangays/archived/delete-all', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'forceDeleteAllArchivedBarangays'])->name('barangays.archived.delete-all');
        Route::patch('/barangays/{id}/archive', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'archiveBarangay'])->name('barangays.archive');
        Route::patch('/barangays/{id}/restore', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'restoreBarangay'])->name('barangays.restore');
        Route::delete('/barangays/{id}/force-delete', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'forceDeleteBarangay'])->name('barangays.force-delete');
        Route::post('/barangays/bulk-update', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'bulkUpdateBarangays'])->name('barangays.bulk-update');
        Route::post('/barangays/{id}', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'updateBarangay'])->name('barangays.update');
        Route::get('/programs', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'programs'])->name('programs');
        Route::post('/programs/sync-barangays', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'syncFromBarangays'])->name('programs.sync-barangays');
        Route::post('/programs/create', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'createProgram'])->name('programs.create');
        // Archive routes for programs
        Route::get('/programs/archived', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'getArchivedPrograms'])->name('programs.archived');
        Route::post('/programs/{id}/archive', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'archiveProgram'])->name('programs.archive');
        Route::post('/programs/{id}/restore', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'restoreProgram'])->name('programs.restore');
        Route::delete('/programs/{id}/force-delete', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'forceDeleteProgram'])->name('programs.force-delete');
        Route::post('/programs/{id}', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'updateProgram'])->name('programs.update');
        Route::delete('/programs/{id}', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'deleteProgram'])->name('programs.delete');
    });

    // MUNICIPALITY MANAGEMENT ROUTES
    Route::prefix('municipalities')->name('municipalities.')->group(function () {
        Route::get('/', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'store'])->name('store');
        // Archive routes (must be before /{id} wildcards)
        Route::get('/archived', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'getArchived'])->name('archived');
        Route::post('/{id}/restore', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'forceDestroy'])->name('force-delete');
        Route::get('/{id}/edit', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'destroy'])->name('delete');
        // Barangay management for municipality
        Route::get('/{id}/barangays', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'showBarangays'])->name('barangays');
        Route::post('/{id}/barangays', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'storeBarangays'])->name('barangays.store');
    });

    // API Routes for dropdowns
    Route::get('/api/laguna-municipalities', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'getLagunaMunicipalities']);
    Route::get('/api/barangays/{municipality}', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'getBarangays']);
});

// ============================================
// ADMIN ROUTES (for municipality admins)
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Existing routes
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/detailed-analysis', [App\Http\Controllers\AdminController::class, 'detailedAnalysis'])->name('detailed-analysis');
    Route::get('/applications', [App\Http\Controllers\AdminController::class, 'applications'])->name('applications');
    Route::post('/applications/{id}/status', [App\Http\Controllers\AdminController::class, 'updateApplicationStatus'])->name('applications.status');
    Route::get('/barangay/{name}', [App\Http\Controllers\AdminController::class, 'barangay'])->name('barangay');
    // Requirements routes - ADD THESE
    Route::get('/requirements', [App\Http\Controllers\AdminController::class, 'requirements'])->name('requirements');
    Route::get('/requirements/{id}', [App\Http\Controllers\AdminController::class, 'viewRequirement'])->name('view-requirement');
    Route::post('/requirements/{id}/status', [App\Http\Controllers\AdminController::class, 'updateFileStatus'])->name('update-file-status');
    Route::get('/users/search', [App\Http\Controllers\AdminController::class, 'searchUsers'])->name('users.search');

    // Archive / Restore / Force-delete
    Route::delete('/applications/{id}/archive', [App\Http\Controllers\AdminController::class, 'archiveApplication'])->name('applications.archive');
    Route::patch('/applications/{id}/restore', [App\Http\Controllers\AdminController::class, 'restoreApplication'])->name('applications.restore');
    Route::delete('/applications/{id}/force-delete', [App\Http\Controllers\AdminController::class, 'forceDeleteApplication'])->name('applications.force-delete');
    Route::delete('/applications/{id}/direct-delete', [App\Http\Controllers\AdminController::class, 'directDeleteApplication'])->name('applications.direct-delete');
    Route::post('/applications/{id}/validate-pwd', [App\Http\Controllers\AdminController::class, 'validatePwdApplication'])->name('applications.validate-pwd');
    Route::post('/applications/{id}/validate-aics', [App\Http\Controllers\AdminController::class, 'validateAicsApplication'])->name('applications.validate-aics');
    Route::post('/applications/{id}/mark-id-ready', [App\Http\Controllers\AdminController::class, 'markIdReady'])->name('applications.mark-id-ready');

    // Admin Appointment routes (Solo Parent)
    Route::get('/appointments', [App\Http\Controllers\Admin\AppointmentController::class, 'index'])->name('admin.appointments.index');
    Route::post('/appointments/{id}/confirm', [App\Http\Controllers\Admin\AppointmentController::class, 'confirm'])->name('admin.appointments.confirm');
    Route::post('/appointments/{id}/validate', [App\Http\Controllers\Admin\AppointmentController::class, 'validate'])->name('admin.appointments.validate');
    Route::post('/appointments/{id}/reject', [App\Http\Controllers\Admin\AppointmentController::class, 'reject'])->name('admin.appointments.reject');
    // Appointment archive
    Route::delete('/appointments/{id}/archive', [App\Http\Controllers\Admin\AppointmentController::class, 'archive'])->name('admin.appointments.archive');
    Route::patch('/appointments/{id}/restore', [App\Http\Controllers\Admin\AppointmentController::class, 'restore'])->name('admin.appointments.restore');
    Route::delete('/appointments/{id}/force-delete', [App\Http\Controllers\Admin\AppointmentController::class, 'forceDelete'])->name('admin.appointments.force-delete');
    Route::get('/appointments/archived', [App\Http\Controllers\Admin\AppointmentController::class, 'archived'])->name('admin.appointments.archived');

    // Chat routes for admin
    Route::get('/chat/users', [App\Http\Controllers\ChatController::class, 'getUsers'])->name('chat.users');
    Route::get('/chat/messages/{userId}', [App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/unread-count', [App\Http\Controllers\ChatController::class, 'getUnreadCount'])->name('chat.unread');

    // Admin Settings Routes
    Route::get('/settings', [App\Http\Controllers\AdminSettingsController::class, 'index'])->name('settings');
    Route::get('/settings/get', [App\Http\Controllers\AdminSettingsController::class, 'get'])->name('settings.get');
    Route::post('/settings/update', [App\Http\Controllers\AdminSettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset', [App\Http\Controllers\AdminSettingsController::class, 'reset'])->name('settings.reset');

    // Admin Data Management Routes
    Route::prefix('data')->name('data.')->group(
        function () {
            Route::get('/dashboard', [App\Http\Controllers\Admin\DataManagementController::class, 'dashboard'])->name('dashboard');
            Route::get('/municipality', [App\Http\Controllers\Admin\DataManagementController::class, 'municipality'])->name('municipality');
            Route::post('/municipality/update', [App\Http\Controllers\Admin\DataManagementController::class, 'updateMunicipality'])->name('municipality.update');
            Route::get('/barangays', [App\Http\Controllers\Admin\DataManagementController::class, 'barangays'])->name('barangays');
            // Specific routes BEFORE {id} wildcard
            Route::post('/barangays/bulk-update', [App\Http\Controllers\Admin\DataManagementController::class, 'bulkUpdateBarangays'])->name('barangays.bulk-update');
            Route::post('/barangays/bulk-store', [App\Http\Controllers\Admin\DataManagementController::class, 'bulkStoreBarangays'])->name('barangays.bulk-store');
            Route::post('/barangays/find-or-create', [App\Http\Controllers\Admin\DataManagementController::class, 'findOrCreateBarangay'])->name('barangays.find-or-create');
            Route::post('/barangays/{id}/update', [App\Http\Controllers\Admin\DataManagementController::class, 'updateBarangay'])->name('barangays.update');
            Route::get('/programs', [App\Http\Controllers\Admin\DataManagementController::class, 'programs'])->name('programs');
            Route::post('/programs/create', [App\Http\Controllers\Admin\DataManagementController::class, 'createProgram'])->name('programs.create');
            Route::post('/programs/{id}/update', [App\Http\Controllers\Admin\DataManagementController::class, 'updateProgram'])->name('programs.update');
            Route::delete('/programs/{id}/delete', [App\Http\Controllers\Admin\DataManagementController::class, 'deleteProgram'])->name('programs.delete');
            // Municipality Yearly Data
            Route::get('/yearly', [App\Http\Controllers\Admin\DataManagementController::class, 'yearlyData'])->name('yearly');
            Route::post('/yearly/save', [App\Http\Controllers\Admin\DataManagementController::class, 'saveYearlySummary'])->name('yearly.save');
            Route::delete('/yearly/{id}/delete', [App\Http\Controllers\Admin\DataManagementController::class, 'deleteYearlySummary'])->name('yearly.delete');
            Route::post('/yearly/{id}/archive', [App\Http\Controllers\Admin\DataManagementController::class, 'archiveYearlySummary'])->name('yearly.archive');
            Route::post('/yearly/{id}/restore', [App\Http\Controllers\Admin\DataManagementController::class, 'restoreYearlySummary'])->name('yearly.restore');
            Route::delete('/yearly/{id}/force-delete', [App\Http\Controllers\Admin\DataManagementController::class, 'forceDeleteYearlySummary'])->name('yearly.forceDelete');
        }
    );

    // Announcement Management Routes
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('store');
        Route::patch('/{announcement}/deactivate', [App\Http\Controllers\Admin\AnnouncementController::class, 'deactivate'])->name('deactivate');
        Route::patch('/{announcement}/activate', [App\Http\Controllers\Admin\AnnouncementController::class, 'activate'])->name('activate');
        Route::delete('/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('destroy');
    });

    // Admin notification bell - mark viewed
    Route::post('/mark-notifications-viewed', [App\Http\Controllers\AdminController::class, 'markNotificationsViewed'])->name('mark-notifications-viewed');

    // Vision / Mission / Goals & Strategic Goals
    Route::post('/vision-mission/save', [App\Http\Controllers\AdminController::class, 'saveVisionMission'])->name('vision-mission.save');

    // Admin User Management (view users in same municipality)
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');

    // Yearly Comparison Routes
    Route::prefix('yearly')->name('yearly.')->group(
        function () {
            Route::get('/', [App\Http\Controllers\Admin\YearlyComparisonController::class, 'index'])->name('index');
            Route::get('/view/{year}', [App\Http\Controllers\Admin\YearlyComparisonController::class, 'viewYear'])->name('view');
            Route::get('/compare', [App\Http\Controllers\Admin\YearlyComparisonController::class, 'compare'])->name('compare');
        }
    );
});
// General dashboard — user-only; admins/superadmins will be redirected by EnsureUserRole
Route::middleware(['auth', 'ensure_role:user'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
});
// ============================================
// BARANGAY ANALYSIS ROUTES
>>>>>>> origin/main
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