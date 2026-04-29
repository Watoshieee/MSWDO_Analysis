<?php

use Illuminate\Support\Facades\Route;

// ============================================
// ADMIN ROUTES (for municipality admins)
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/detailed-analysis', [App\Http\Controllers\AdminController::class, 'detailedAnalysis'])->name('detailed-analysis');
    Route::get('/applications', [App\Http\Controllers\AdminController::class, 'applications'])->name('applications');
    Route::post('/applications/{id}/status', [App\Http\Controllers\AdminController::class, 'updateApplicationStatus'])->name('applications.status');
    Route::get('/barangay/{name}', [App\Http\Controllers\AdminController::class, 'barangay'])->name('barangay');

    // Requirements routes
    Route::get('/requirements', [App\Http\Controllers\AdminController::class, 'requirements'])->name('requirements');
    Route::get('/requirements/{id}', [App\Http\Controllers\AdminController::class, 'viewRequirement'])->name('view-requirement');
    Route::post('/requirements/{id}/status', [App\Http\Controllers\AdminController::class, 'updateFileStatus'])->name('update-file-status');

    // Archive / Restore / Force-delete
    Route::delete('/applications/{id}/archive', [App\Http\Controllers\AdminController::class, 'archiveApplication'])->name('applications.archive');
    Route::patch('/applications/{id}/restore', [App\Http\Controllers\AdminController::class, 'restoreApplication'])->name('applications.restore');
    Route::delete('/applications/{id}/force-delete', [App\Http\Controllers\AdminController::class, 'forceDeleteApplication'])->name('applications.force-delete');
    Route::delete('/applications/{id}/direct-delete', [App\Http\Controllers\AdminController::class, 'directDeleteApplication'])->name('applications.direct-delete');
    Route::post('/applications/{id}/mark-id-ready', [App\Http\Controllers\AdminController::class, 'markIdReady'])->name('applications.mark-id-ready');

    // Admin Appointment routes (Solo Parent)
    Route::get('/appointments', [App\Http\Controllers\Admin\AppointmentController::class, 'index'])->name('admin.appointments.index');
    Route::post('/appointments/{id}/confirm', [App\Http\Controllers\Admin\AppointmentController::class, 'confirm'])->name('admin.appointments.confirm');
    Route::post('/appointments/{id}/validate', [App\Http\Controllers\Admin\AppointmentController::class, 'validate'])->name('admin.appointments.validate');
    Route::post('/appointments/{id}/reject', [App\Http\Controllers\Admin\AppointmentController::class, 'reject'])->name('admin.appointments.reject');
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
    Route::prefix('data')->name('data.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DataManagementController::class, 'dashboard'])->name('dashboard');
        Route::get('/municipality', [App\Http\Controllers\Admin\DataManagementController::class, 'municipality'])->name('municipality');
        Route::post('/municipality/update', [App\Http\Controllers\Admin\DataManagementController::class, 'updateMunicipality'])->name('municipality.update');
        Route::get('/barangays', [App\Http\Controllers\Admin\DataManagementController::class, 'barangays'])->name('barangays');
        Route::post('/barangays/bulk-update', [App\Http\Controllers\Admin\DataManagementController::class, 'bulkUpdateBarangays'])->name('barangays.bulk-update');
        Route::post('/barangays/bulk-store', [App\Http\Controllers\Admin\DataManagementController::class, 'bulkStoreBarangays'])->name('barangays.bulk-store');
        Route::post('/barangays/find-or-create', [App\Http\Controllers\Admin\DataManagementController::class, 'findOrCreateBarangay'])->name('barangays.find-or-create');
        Route::post('/barangays/{id}/update', [App\Http\Controllers\Admin\DataManagementController::class, 'updateBarangay'])->name('barangays.update');
        Route::get('/programs', [App\Http\Controllers\Admin\DataManagementController::class, 'programs'])->name('programs');
        Route::post('/programs/create', [App\Http\Controllers\Admin\DataManagementController::class, 'createProgram'])->name('programs.create');
        Route::post('/programs/{id}/update', [App\Http\Controllers\Admin\DataManagementController::class, 'updateProgram'])->name('programs.update');
        Route::delete('/programs/{id}/delete', [App\Http\Controllers\Admin\DataManagementController::class, 'deleteProgram'])->name('programs.delete');
        Route::get('/yearly', [App\Http\Controllers\Admin\DataManagementController::class, 'yearlyData'])->name('yearly');
        Route::post('/yearly/save', [App\Http\Controllers\Admin\DataManagementController::class, 'saveYearlySummary'])->name('yearly.save');
        Route::delete('/yearly/{id}/delete', [App\Http\Controllers\Admin\DataManagementController::class, 'deleteYearlySummary'])->name('yearly.delete');
        Route::post('/yearly/{id}/archive', [App\Http\Controllers\Admin\DataManagementController::class, 'archiveYearlySummary'])->name('yearly.archive');
        Route::post('/yearly/{id}/restore', [App\Http\Controllers\Admin\DataManagementController::class, 'restoreYearlySummary'])->name('yearly.restore');
        Route::delete('/yearly/{id}/force-delete', [App\Http\Controllers\Admin\DataManagementController::class, 'forceDeleteYearlySummary'])->name('yearly.forceDelete');
    });

    // Announcement Management Routes
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('store');
        Route::patch('/{announcement}/deactivate', [App\Http\Controllers\Admin\AnnouncementController::class, 'deactivate'])->name('deactivate');
        Route::patch('/{announcement}/activate', [App\Http\Controllers\Admin\AnnouncementController::class, 'activate'])->name('activate');
        Route::delete('/{announcement}', [App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('destroy');
    });

    // Admin notification bell
    Route::post('/mark-notifications-viewed', [App\Http\Controllers\AdminController::class, 'markNotificationsViewed'])->name('mark-notifications-viewed');

    // Vision / Mission / Goals
    Route::post('/vision-mission/save', [App\Http\Controllers\AdminController::class, 'saveVisionMission'])->name('vision-mission.save');

    // Admin user management
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/users/search', [App\Http\Controllers\AdminController::class, 'searchUsers'])->name('users.search');

    // Yearly Comparison Routes
    Route::prefix('yearly')->name('yearly.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\YearlyComparisonController::class, 'index'])->name('index');
        Route::get('/view/{year}', [App\Http\Controllers\Admin\YearlyComparisonController::class, 'viewYear'])->name('view');
        Route::get('/compare', [App\Http\Controllers\Admin\YearlyComparisonController::class, 'compare'])->name('compare');
    });
});
