<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
// ============================================
// USER ROUTES (authenticated users with role 'user')
// ============================================
Route::middleware(['auth', 'ensure_role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class , 'dashboard'])->name('user.dashboard');
    Route::get('/user/programs', [UserController::class , 'programs'])->name('user.programs');
    Route::get('/user/announcements', [UserController::class , 'announcements'])->name('user.announcements');
    Route::get('/user/my-requirements', [UserController::class , 'myRequirements'])->name('user.my-requirements');
    Route::put('/user/resubmit-requirement/{fileUploadId}', [UserController::class , 'resubmitRequirement'])->name('user.resubmit-requirement');
    Route::get('/user/apply/AICS/aics', [UserController::class, 'aicsCategory'])->name('user.aics-category');
    Route::get('/user/apply/AICS/medical', [UserController::class, 'aicsMedical'])->name('user.aics-medical');
    Route::get('/user/apply/AICS/burial', [UserController::class, 'aicsBurial'])->name('user.aics-burial');
    Route::get('/user/apply/{program}', [ApplicationController::class , 'create'])->name('user.apply');
    Route::get('/user/pwd-application', [UserController::class , 'pwdApplication'])->name('user.pwd-application');
    Route::get('/user/pwd-fillable-form', [UserController::class , 'pwdFillableForm'])->name('user.pwd-form');
    Route::post('/user/pwd-fillable-form', [UserController::class , 'pwdFormSubmit'])->name('user.pwd-form.submit');
    Route::get('/user/solo-parent-application', [UserController::class , 'soloParentApplication'])->name('user.solo-parent-application');
    Route::post('/user/pwd-upload-requirement', [UserController::class, 'uploadPwdRequirement'])->name('user.pwd-upload-requirement');
    Route::post('/user/aics-medical-upload', [UserController::class, 'uploadAicsMedical'])->name('user.aics-medical-upload');
    Route::post('/user/aics-burial-upload', [UserController::class, 'uploadAicsBurial'])->name('user.aics-burial-upload');
});

// [Duplicate route block removed — user management is handled in the main superadmin group below]

// ============================================
// SOLO PARENT APPLICATION ROUTES
// ============================================
Route::middleware(['auth', 'ensure_role:user'])->group(function () {
    Route::get('/apply/solo-parent', [ApplicationController::class , 'showSoloParentForm'])->name('solo-parent.apply');
    Route::post('/applications', [ApplicationController::class , 'store'])->name('applications.store');
    Route::get('/applications/{id}', [ApplicationController::class , 'show'])->name('applications.show');
});

// ============================================
// REQUIREMENT UPLOAD ROUTES
// ============================================
Route::middleware(['auth', 'ensure_role:user'])->prefix('applications')->name('applications.')->group(function () {
    Route::get('/{applicationId}/requirements', [ApplicationController::class , 'showRequirements'])->name('requirements');
    Route::post('/{applicationId}/requirement/upload', [ApplicationController::class , 'uploadRequirement'])->name('requirement.upload');
    Route::delete('/{applicationId}/requirement/delete', [ApplicationController::class , 'deleteRequirement'])->name('requirement.delete');
});

// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/', function () {
    return redirect('/analysis');
});

Route::prefix('analysis')->name('analysis.')->group(function () {
    Route::get('/', [AnalysisController::class , 'index'])->name('index');
    Route::get('/municipality/{name}', [AnalysisController::class , 'municipality'])->name('municipality');
    Route::get('/demographic', [AnalysisController::class , 'demographic'])->name('demographic');
    Route::get('/programs', [AnalysisController::class , 'programs'])->name('programs');
});

// ============================================
// GUEST ROUTES
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class , 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class , 'login']);

    Route::get('/register', [RegisterController::class , 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class , 'register']);

    Route::get('/verify-otp', [OtpController::class , 'showVerifyForm'])->name('otp.verify.form');
    Route::post('/verify-otp', [OtpController::class , 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpController::class , 'resend'])->name('otp.resend');
});

// Password Reset Routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class , 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class , 'store'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class , 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\NewPasswordController::class , 'store'])
        ->name('password.update');
});
// LOGOUT
Route::post('/logout', [LoginController::class , 'logout'])->name('logout');

// SESSION HEARTBEAT — keeps session alive while tab is open
Route::middleware(['auth'])->get('/session/ping', function () {
    return response()->json(['ok' => true, 'ts' => now()->timestamp]);
})->name('session.ping');

// GENERAL DASHBOARD — users only; admins/superadmins redirected by EnsureUserRole
Route::middleware(['auth', 'ensure_role:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard');
});

// ============================================
// SUPER ADMIN ROUTES
// ============================================
Route::middleware(['auth', 'role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // DASHBOARD
    Route::get('/dashboard', [SuperAdminController::class , 'dashboard'])->name('dashboard');

    // User Management (SuperAdmin\UserController)
    Route::get('/users', [App\Http\Controllers\SuperAdmin\UserController::class, 'index'])->name('users');
    Route::post('/users', [App\Http\Controllers\SuperAdmin\UserController::class, 'store'])->name('users.create');
    Route::put('/users/{id}', [App\Http\Controllers\SuperAdmin\UserController::class, 'update'])->name('users.update');

    // Archive / Restore / Force-Delete (must come before wildcards)
    Route::get('/users/archived-json', [\App\Http\Controllers\SuperAdmin\UserController::class, 'getArchivedUsers'])->name('users.archived-json');
    Route::post('/users/{id}/archive', [App\Http\Controllers\SuperAdmin\UserController::class, 'archive'])->name('users.archive');
    Route::post('/users/{id}/restore', [App\Http\Controllers\SuperAdmin\UserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}/force-delete', [App\Http\Controllers\SuperAdmin\UserController::class, 'forceDelete'])->name('users.forceDelete');

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
        Route::post('/municipalities/{id}', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'updateMunicipality'])->name('municipalities.update');
        Route::get('/barangays', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'barangays'])->name('barangays');
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
        Route::get('/api/laguna-municipalities', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class , 'getLagunaMunicipalities']);
        Route::get('/api/barangays/{municipality}', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class , 'getBarangays']);
    });

// ============================================
// ADMIN ROUTES (for municipality admins)
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Existing routes
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class , 'dashboard'])->name('dashboard');
    Route::get('/detailed-analysis', [App\Http\Controllers\AdminController::class , 'detailedAnalysis'])->name('detailed-analysis');
    Route::get('/applications', [App\Http\Controllers\AdminController::class , 'applications'])->name('applications');
    Route::post('/applications/{id}/status', [App\Http\Controllers\AdminController::class , 'updateApplicationStatus'])->name('applications.status');
    Route::get('/barangay/{name}', [App\Http\Controllers\AdminController::class , 'barangay'])->name('barangay');
    // Requirements routes - ADD THESE
    Route::get('/requirements', [App\Http\Controllers\AdminController::class , 'requirements'])->name('requirements');
    Route::get('/requirements/{id}', [App\Http\Controllers\AdminController::class , 'viewRequirement'])->name('view-requirement');
    Route::post('/requirements/{id}/status', [App\Http\Controllers\AdminController::class , 'updateFileStatus'])->name('update-file-status');

    // Admin Data Management Routes
    Route::prefix('data')->name('data.')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Admin\DataManagementController::class , 'dashboard'])->name('dashboard');
            Route::get('/municipality', [App\Http\Controllers\Admin\DataManagementController::class , 'municipality'])->name('municipality');
            Route::post('/municipality/update', [App\Http\Controllers\Admin\DataManagementController::class , 'updateMunicipality'])->name('municipality.update');
            Route::get('/barangays', [App\Http\Controllers\Admin\DataManagementController::class , 'barangays'])->name('barangays');
            Route::post('/barangays/{id}/update', [App\Http\Controllers\Admin\DataManagementController::class , 'updateBarangay'])->name('barangays.update');
            Route::post('/barangays/find-or-create', [App\Http\Controllers\Admin\DataManagementController::class , 'findOrCreateBarangay'])->name('barangays.find-or-create');
            Route::get('/programs', [App\Http\Controllers\Admin\DataManagementController::class , 'programs'])->name('programs');
            Route::post('/programs/create', [App\Http\Controllers\Admin\DataManagementController::class , 'createProgram'])->name('programs.create');
            Route::post('/programs/{id}/update', [App\Http\Controllers\Admin\DataManagementController::class , 'updateProgram'])->name('programs.update');
            Route::delete('/programs/{id}/delete', [App\Http\Controllers\Admin\DataManagementController::class , 'deleteProgram'])->name('programs.delete');
        }
        );

        // Yearly Comparison Routes
        Route::prefix('yearly')->name('yearly.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\YearlyComparisonController::class , 'index'])->name('index');
            Route::get('/view/{year}', [App\Http\Controllers\Admin\YearlyComparisonController::class , 'viewYear'])->name('view');
            Route::get('/compare', [App\Http\Controllers\Admin\YearlyComparisonController::class , 'compare'])->name('compare');
        }
        );
    });
// General dashboard — user-only; admins/superadmins will be redirected by EnsureUserRole
Route::middleware(['auth', 'ensure_role:user'])->group(function () {
    Route::get('/dashboard', [UserController::class , 'dashboard'])->name('dashboard');
});
// ============================================
// BARANGAY ANALYSIS ROUTES
// ============================================
Route::prefix('barangay-analysis')->name('barangay-analysis.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\BarangayAnalysisController::class , 'index'])->name('index');
    Route::get('/programs', [App\Http\Controllers\Admin\BarangayAnalysisController::class , 'programs'])->name('programs');
    Route::get('/applicants', [App\Http\Controllers\Admin\BarangayAnalysisController::class , 'applicants'])->name('applicants');
    Route::get('/{barangay}', [App\Http\Controllers\Admin\BarangayAnalysisController::class , 'showBarangay'])->name('show');
});

// Batch upload for requirements
Route::middleware(['auth'])->post('/applications/upload-batch', [ApplicationController::class , 'uploadBatch'])->name('applications.upload-batch');

// ============================================
// API ROUTES FOR BARANGAY DATA
// ============================================
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    // Get barangay data for specific year
    Route::get('/barangays/{municipality}/{year}', function ($municipality, $year) {
            try {
                // Log the request
                \Log::info('API Request: ' . $municipality . ' - Year: ' . $year);

                // Validate year
                $year = intval($year);
                if ($year < 2000 || $year > date('Y') + 1) {
                    return response()->json(['error' => 'Invalid year'], 400);
                }

                // Get all barangay names for this municipality
                $barangayNames = App\Models\Barangay::where('municipality', $municipality)
                    ->select('name')
                    ->distinct('name')
                    ->get()
                    ->pluck('name')
                    ->toArray();

                // If no barangays exist, use defaults
                if (empty($barangayNames)) {
                    $defaultLists = [
                        'Magdalena' => [
                            'Alipit', 'Malaking Ambling', 'Munting Ambling', 'Baanan', 'Balanac',
                            'Bucal', 'Buenavista', 'Bungkol', 'Buo', 'Burlungan', 'Cigaras',
                            'Ibabang Atingay', 'Ibabang Butnong', 'Ilayang Atingay', 'Ilayang Butnong',
                            'Ilog', 'Malinao', 'Maravilla', 'Poblacion', 'Sabang', 'Salasad',
                            'Tanawan', 'Tipunan', 'Halayhayin'
                        ],
                        'Liliw' => [
                            'Bagong Anyo (Poblacion)', 'Bayate', 'Bongkol', 'Bubukal', 'Cabuyew',
                            'Calumpang', 'San Isidro Culoy', 'Dagatan', 'Daniw', 'Dita',
                            'Ibabang Palina', 'Ibabang San Roque', 'Ibabang Sungi', 'Ibabang Taykin',
                            'Ilayang Palina', 'Ilayang San Roque', 'Ilayang Sungi', 'Ilayang Taykin',
                            'Kanlurang Bukal', 'Laguan', 'Luquin', 'Malabo-Kalantukan',
                            'Masikap (Poblacion)', 'Maslun (Poblacion)', 'Mojon', 'Novaliches',
                            'Oples', 'Pag-asa (Poblacion)', 'Palayan', 'Rizal (Poblacion)',
                            'Silangang Bukal', 'Tuy-Baanan'
                        ],
                        'Majayjay' => [
                            'Amonoy', 'Bakia', 'Balanac', 'Balayong', 'Banilad', 'Banti',
                            'Bitaoy', 'Botocan', 'Bukal', 'Burgos', 'Burol', 'Coralao',
                            'Gagalot', 'Ibabang Banga', 'Ibabang Bayucain', 'Ilayang Banga',
                            'Ilayang Bayucain', 'Isabang', 'Malinao', 'May-It', 'Munting Kawayan',
                            'Olla', 'Oobi', 'Origuel (Poblacion)', 'Panalaban', 'Pangil',
                            'Panglan', 'Piit', 'Pook', 'Rizal', 'San Francisco (Poblacion)',
                            'San Isidro', 'San Miguel (Poblacion)', 'San Roque',
                            'Santa Catalina (Poblacion)', 'Suba', 'Talortor', 'Tanawan',
                            'Taytay', 'Villa Nogales'
                        ]
                    ];
                    $barangayNames = $defaultLists[$municipality] ?? [];
                }

                $result = [];

                foreach ($barangayNames as $barangayName) {
                    // Check if record exists for this year
                    $record = App\Models\Barangay::where('municipality', $municipality)
                        ->where('name', $barangayName)
                        ->where('year', $year)
                        ->first();

                    if ($record) {
                        $result[] = $record;
                    }
                    else {
                        // Use updateOrCreate to prevent duplicates
                        $newRecord = App\Models\Barangay::updateOrCreate(
                        [
                            'municipality' => $municipality,
                            'name' => $barangayName,
                            'year' => $year
                        ],
                        [
                            'male_population' => 0,
                            'female_population' => 0,
                            'population_0_19' => 0,
                            'population_20_59' => 0,
                            'population_60_100' => 0,
                            'single_parent_count' => 0,
                            'total_households' => 0,
                            'total_approved_applications' => 0,
                        ]
                        );

                        $result[] = $newRecord;
                    }
                }

                \Log::info('Returning ' . count($result) . ' barangays');
                return response()->json($result);

            }
            catch (\Exception $e) {
                \Log::error('API Error: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
        );
    });