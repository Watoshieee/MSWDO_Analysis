<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\Application;
use App\Models\SocialWelfareProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangayAnalysisController extends Controller
{
    /**
     * Display barangay comparison dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        
        // Get all barangays in this municipality
        $barangays = Barangay::where('municipality', $user->municipality)
            ->orderBy('name')
            ->get();
        
        // Get applications data per barangay
        $applications = Application::where('municipality', $user->municipality)->get();
        
        // Get program data per barangay
        $programs = SocialWelfareProgram::where('municipality', $user->municipality)->get();
        
        // Prepare comparative data
        $comparativeData = [];
        $totalApplications = 0;
        $totalApproved = 0;
        $totalPending = 0;
        $totalRejected = 0;
        
        foreach ($barangays as $barangay) {
            $barangayApps = $applications->where('barangay', $barangay->name);
            $barangayPrograms = $programs->where('barangay', $barangay->name);
            
            $appCount = $barangayApps->count();
            $approvedCount = $barangayApps->where('status', 'approved')->count();
            $pendingCount = $barangayApps->where('status', 'pending')->count();
            $rejectedCount = $barangayApps->where('status', 'rejected')->count();
            
            $totalApplications += $appCount;
            $totalApproved += $approvedCount;
            $totalPending += $pendingCount;
            $totalRejected += $rejectedCount;
            
            $comparativeData[$barangay->name] = [
                'barangay' => $barangay,
                'population' => $barangay->male_population + $barangay->female_population,
                'households' => $barangay->total_households,
                'applications' => [
                    'total' => $appCount,
                    'approved' => $approvedCount,
                    'pending' => $pendingCount,
                    'rejected' => $rejectedCount,
                    'rate' => $appCount > 0 ? round(($approvedCount / $appCount) * 100, 1) : 0
                ],
                'programs' => [
                    'total' => $barangayPrograms->count(),
                    'beneficiaries' => $barangayPrograms->sum('beneficiary_count'),
                    'by_type' => $barangayPrograms->groupBy('program_type')
                        ->map(function($items) {
                            return $items->sum('beneficiary_count');
                        })
                ]
            ];
        }
        
        // Get top performing barangays
        $topByApplications = collect($comparativeData)
            ->sortByDesc(function($item) {
                return $item['applications']['total'];
            })
            ->take(5)
            ->toArray();
            
        $topByApproved = collect($comparativeData)
            ->sortByDesc(function($item) {
                return $item['applications']['approved'];
            })
            ->take(5)
            ->toArray();
            
        $topByBeneficiaries = collect($comparativeData)
            ->sortByDesc(function($item) {
                return $item['programs']['beneficiaries'];
            })
            ->take(5)
            ->toArray();

        return view('admin.barangay-analysis.index', compact(
            'municipality',
            'barangays',
            'comparativeData',
            'totalApplications',
            'totalApproved',
            'totalPending',
            'totalRejected',
            'topByApplications',
            'topByApproved',
            'topByBeneficiaries'
        ));
    }

    /**
     * Show program analysis per barangay
     */
    public function programs()
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        
        // Get all barangays
        $barangays = Barangay::where('municipality', $user->municipality)
            ->orderBy('name')
            ->get();
        
        // Get program data
        $programs = SocialWelfareProgram::where('municipality', $user->municipality)->get();
        
        // Prepare program comparison data
        $programTypes = [
            '4Ps' => '4Ps',
            'Senior_Citizen_Pension' => 'Senior Citizen',
            'PWD_Assistance' => 'PWD',
            'AICS' => 'AICS',
            'SLP' => 'SLP',
            'ESA' => 'ESA',
            'Solo_Parent' => 'Solo Parent'
        ];
        
        $programData = [];
        $barangayProgramTotals = [];
        
        foreach ($barangays as $barangay) {
            $barangayPrograms = $programs->where('barangay', $barangay->name);
            $barangayProgramTotals[$barangay->name] = $barangayPrograms->sum('beneficiary_count');
            
            foreach ($programTypes as $key => $label) {
                if (!isset($programData[$key])) {
                    $programData[$key] = [];
                }
                $programData[$key][$barangay->name] = $barangayPrograms
                    ->where('program_type', $key)
                    ->sum('beneficiary_count');
            }
        }
        
        // Calculate totals
        $programTotals = [];
        foreach ($programTypes as $key => $label) {
            $programTotals[$key] = array_sum($programData[$key] ?? []);
        }

        return view('admin.barangay-analysis.programs', compact(
            'municipality',
            'barangays',
            'programTypes',
            'programData',
            'programTotals',
            'barangayProgramTotals'
        ));
    }

    /**
     * Show applicant analysis per barangay
     */
    public function applicants()
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        
        // Get all barangays
        $barangays = Barangay::where('municipality', $user->municipality)
            ->orderBy('name')
            ->get();
        
        // Get applications
        $applications = Application::where('municipality', $user->municipality)->get();
        
        // Prepare applicant data
        $applicantData = [];
        $totalApplicants = 0;
        $totalMale = 0;
        $totalFemale = 0;
        
        foreach ($barangays as $barangay) {
            $barangayApps = $applications->where('barangay', $barangay->name);
            
            $maleCount = $barangayApps->where('gender', 'Male')->count();
            $femaleCount = $barangayApps->where('gender', 'Female')->count();
            
            $totalApplicants += $barangayApps->count();
            $totalMale += $maleCount;
            $totalFemale += $femaleCount;
            
            $applicantData[$barangay->name] = [
                'total' => $barangayApps->count(),
                'male' => $maleCount,
                'female' => $femaleCount,
                'average_age' => round($barangayApps->avg('age'), 1),
                'by_status' => [
                    'approved' => $barangayApps->where('status', 'approved')->count(),
                    'pending' => $barangayApps->where('status', 'pending')->count(),
                    'rejected' => $barangayApps->where('status', 'rejected')->count()
                ],
                'by_program' => $barangayApps->groupBy('program_type')
                    ->map(function($items) {
                        return $items->count();
                    })
                    ->toArray()
            ];
        }
        
        // Get top barangays by applicants
        $topBarangays = collect($applicantData)
            ->sortByDesc('total')
            ->take(5)
            ->toArray();

        return view('admin.barangay-analysis.applicants', compact(
            'municipality',
            'barangays',
            'applicantData',
            'totalApplicants',
            'totalMale',
            'totalFemale',
            'topBarangays'
        ));
    }

    /**
     * View detailed analysis for a specific barangay
     */
    public function showBarangay($barangayName)
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        
        $barangay = Barangay::where('municipality', $user->municipality)
            ->where('name', $barangayName)
            ->firstOrFail();
        
        $applications = Application::where('municipality', $user->municipality)
            ->where('barangay', $barangayName)
            ->orderBy('application_date', 'desc')
            ->get();
        
        $programs = SocialWelfareProgram::where('municipality', $user->municipality)
            ->where('barangay', $barangayName)
            ->get();

        return view('admin.barangay-analysis.show', compact(
            'municipality',
            'barangay',
            'applications',
            'programs'
        ));
    }
}