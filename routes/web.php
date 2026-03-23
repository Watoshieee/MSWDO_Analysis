<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\SuperAdminController;

// PUBLIC ROUTES
Route::get('/', function () {
    return redirect('/analysis');
});

Route::prefix('analysis')->name('analysis.')->group(function () {
    Route::get('/', [AnalysisController::class, 'index'])->name('index');
    Route::get('/municipality/{name}', [AnalysisController::class, 'municipality'])->name('municipality');
    Route::get('/demographic', [AnalysisController::class, 'demographic'])->name('demographic');
    Route::get('/programs', [AnalysisController::class, 'programs'])->name('programs');
});

// GUEST ROUTES
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/verify-otp', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');
});

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// PROTECTED ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// SUPER ADMIN ROUTES
Route::middleware(['auth', 'role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // DASHBOARD - dapat ito ang una
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    
    // User Management
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::post('/users/create', [SuperAdminController::class, 'createUser'])->name('users.create');
    Route::put('/users/{id}', [SuperAdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [SuperAdminController::class, 'deleteUser'])->name('users.delete');
    
    // DATA MANAGEMENT ROUTES
    Route::prefix('data')->name('data.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'dashboard'])->name('dashboard');
        Route::get('/municipalities', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'municipalities'])->name('municipalities');
        Route::post('/municipalities/{id}', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'updateMunicipality'])->name('municipalities.update');
        Route::get('/barangays', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'barangays'])->name('barangays');
        Route::post('/barangays/{id}', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'updateBarangay'])->name('barangays.update');
        Route::get('/programs', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'programs'])->name('programs');
        Route::post('/programs/create', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'createProgram'])->name('programs.create');
        Route::post('/programs/{id}', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'updateProgram'])->name('programs.update');
        Route::delete('/programs/{id}', [App\Http\Controllers\SuperAdmin\DataManagementController::class, 'deleteProgram'])->name('programs.delete');
    });
    
    // MUNICIPALITY MANAGEMENT ROUTES (NEW)
    Route::prefix('municipalities')->name('municipalities.')->group(function () {
        Route::get('/', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SuperAdmin\MunicipalityManagementController::class, 'store'])->name('store');
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

// ADMIN ROUTES (for municipality admins)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Existing routes
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/detailed-analysis', [App\Http\Controllers\AdminController::class, 'detailedAnalysis'])->name('detailed-analysis');
    Route::get('/applications', [App\Http\Controllers\AdminController::class, 'applications'])->name('applications');
    Route::post('/applications/{id}/status', [App\Http\Controllers\AdminController::class, 'updateApplicationStatus'])->name('applications.status');
    Route::get('/barangay/{name}', [App\Http\Controllers\AdminController::class, 'barangay'])->name('barangay');
    
    // Admin Data Management Routes
    Route::prefix('data')->name('data.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DataManagementController::class, 'dashboard'])->name('dashboard');
        Route::get('/municipality', [App\Http\Controllers\Admin\DataManagementController::class, 'municipality'])->name('municipality');
        Route::post('/municipality/update', [App\Http\Controllers\Admin\DataManagementController::class, 'updateMunicipality'])->name('municipality.update');
        Route::get('/barangays', [App\Http\Controllers\Admin\DataManagementController::class, 'barangays'])->name('barangays');
        Route::post('/barangays/{id}/update', [App\Http\Controllers\Admin\DataManagementController::class, 'updateBarangay'])->name('barangays.update');
        Route::post('/barangays/find-or-create', [App\Http\Controllers\Admin\DataManagementController::class, 'findOrCreateBarangay'])->name('barangays.find-or-create');
        Route::get('/programs', [App\Http\Controllers\Admin\DataManagementController::class, 'programs'])->name('programs');
        Route::post('/programs/create', [App\Http\Controllers\Admin\DataManagementController::class, 'createProgram'])->name('programs.create');
        Route::post('/programs/{id}/update', [App\Http\Controllers\Admin\DataManagementController::class, 'updateProgram'])->name('programs.update');
        Route::delete('/programs/{id}/delete', [App\Http\Controllers\Admin\DataManagementController::class, 'deleteProgram'])->name('programs.delete');
    });
    
    // Yearly Comparison Routes
    Route::prefix('yearly')->name('yearly.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\YearlyComparisonController::class, 'index'])->name('index');
        Route::get('/view/{year}', [App\Http\Controllers\Admin\YearlyComparisonController::class, 'viewYear'])->name('view');
        Route::get('/compare', [App\Http\Controllers\Admin\YearlyComparisonController::class, 'compare'])->name('compare');
    });
});

// Application Management Routes
Route::middleware(['auth'])->prefix('applications')->name('applications.')->group(function () {
    Route::get('/', [App\Http\Controllers\ApplicationController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\ApplicationController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\ApplicationController::class, 'store'])->name('store');
    Route::get('/{id}', [App\Http\Controllers\ApplicationController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [App\Http\Controllers\ApplicationController::class, 'edit'])->name('edit');
    Route::put('/{id}', [App\Http\Controllers\ApplicationController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\ApplicationController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/status', [App\Http\Controllers\ApplicationController::class, 'updateStatus'])->name('status');
});

// Barangay Analysis Routes
Route::prefix('barangay-analysis')->name('barangay-analysis.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\BarangayAnalysisController::class, 'index'])->name('index');
    Route::get('/programs', [App\Http\Controllers\Admin\BarangayAnalysisController::class, 'programs'])->name('programs');
    Route::get('/applicants', [App\Http\Controllers\Admin\BarangayAnalysisController::class, 'applicants'])->name('applicants');
    Route::get('/{barangay}', [App\Http\Controllers\Admin\BarangayAnalysisController::class, 'showBarangay'])->name('show');
});

// API Routes for Barangay Data (PUT THIS AT THE VERY BOTTOM)
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    // Get barangay data for specific year
    Route::get('/barangays/{municipality}/{year}', function($municipality, $year) {
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
                } else {
                    // Get a template from any existing year to get the correct ID
                    $template = App\Models\Barangay::where('municipality', $municipality)
                        ->where('name', $barangayName)
                        ->first();
                    
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
            
        } catch (\Exception $e) {
            \Log::error('API Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });
});