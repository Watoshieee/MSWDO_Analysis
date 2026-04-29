<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;

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

        // Monthly summary routes
        Route::post('/municipalities/monthly/save', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'saveMonthlySummary'])->name('municipalities.monthly.save');
        Route::post('/municipalities/monthly/{id}/edit', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'editMonthlySummary'])->name('municipalities.monthly.edit');
        Route::post('/municipalities/monthly/{id}/archive', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'archiveMonthlySummary'])->name('municipalities.monthly.archive');
        Route::post('/municipalities/monthly/{id}/restore', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'restoreMonthlySummary'])->name('municipalities.monthly.restore');
        Route::delete('/municipalities/monthly/{id}/force-delete', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'forceDeleteMonthlySummary'])->name('municipalities.monthly.force-delete');
        Route::get('/municipalities/monthly/archived', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'getArchivedMonthly'])->name('municipalities.monthly.archived');

        Route::post('/municipalities/{id}', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'updateMunicipality'])->name('municipalities.update');
        Route::get('/barangays', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'barangays'])->name('barangays');
        Route::post('/barangays', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'storeBarangay'])->name('barangays.store');
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
        Route::get('/archived', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'getArchived'])->name('archived');
        Route::post('/{id}/restore', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'forceDestroy'])->name('force-delete');
        Route::get('/{id}/edit', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'destroy'])->name('delete');
        Route::get('/{id}/barangays', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'showBarangays'])->name('barangays');
        Route::post('/{id}/barangays', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'storeBarangays'])->name('barangays.store');
    });

    // API Routes for dropdowns
    Route::get('/api/laguna-municipalities', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'getLagunaMunicipalities']);
    Route::get('/api/barangays/{municipality}', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'getBarangays']);
});
