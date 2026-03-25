<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\SocialWelfareProgram;
use App\Models\Application;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    /**
     * Main public page at /analysis — shows the MSWDO About / Programs info page.
     * Labelled "Programs" in the navbar (1st nav item).
     */
    public function index(Request $request)
    {
        return view('analysis.programs');
    }

    /**
     * Municipality detail page at /analysis/municipality/{name}
     */
    public function municipality($name)
    {
        $municipality = Municipality::where('name', $name)->firstOrFail();
        $barangays    = Barangay::where('municipality', $name)->get();
        $programs     = SocialWelfareProgram::where('municipality', $name)->get();

        $barangayData = [];
        foreach ($barangays as $barangay) {
            $barangayData[$barangay->name] = [
                'population'     => $barangay->male_population + $barangay->female_population,
                'male'           => $barangay->male_population,
                'female'         => $barangay->female_population,
                'single_parents' => $barangay->single_parent_count,
                'households'     => $barangay->total_households,
                'approved_apps'  => $barangay->total_approved_applications,
                'age_0_19'       => $barangay->population_0_19,
                'age_20_59'      => $barangay->population_20_59,
                'age_60_100'     => $barangay->population_60_100,
            ];
        }

        return view('analysis.municipality', compact('municipality', 'barangays', 'programs', 'barangayData'));
    }

    /**
     * Demographic page at /analysis/demographic
     * Labelled "Demographic" in the navbar (2nd nav item).
     */
    public function demographic()
    {
        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->get();

        $demographicData = [];
        foreach ($municipalities as $m) {
            $totalPop = $m->male_population + $m->female_population;
            $demographicData[$m->name] = [
                'total'           => $totalPop,
                'male'            => $m->male_population,
                'female'          => $m->female_population,
                'male_pct'        => $totalPop > 0 ? round(($m->male_population / $totalPop) * 100, 1) : 0,
                'female_pct'      => $totalPop > 0 ? round(($m->female_population / $totalPop) * 100, 1) : 0,
                'age_0_19'        => $m->population_0_19,
                'age_20_59'       => $m->population_20_59,
                'age_60_100'      => $m->population_60_100,
                'age_0_19_pct'    => $totalPop > 0 ? round(($m->population_0_19 / $totalPop) * 100, 1) : 0,
                'age_20_59_pct'   => $totalPop > 0 ? round(($m->population_20_59 / $totalPop) * 100, 1) : 0,
                'age_60_100_pct'  => $totalPop > 0 ? round(($m->population_60_100 / $totalPop) * 100, 1) : 0,
            ];
        }

        return view('analysis.demographic', compact('demographicData'));
    }

    /**
     * Comparative Analysis page at /analysis/programs — shows charts and program data.
     * Labelled "Analysis" in the navbar (3rd nav item).
     */
    public function programs(Request $request)
    {
        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->get();

        $comparisonData = [];
        $programTypes   = [];

        foreach ($municipalities as $municipality) {
            $programs        = SocialWelfareProgram::where('municipality', $municipality->name)->get();
            $totalPopulation = $municipality->male_population + $municipality->female_population;

            $comparisonData[$municipality->name] = [
                'total_population' => $totalPopulation,
                'male'             => $municipality->male_population,
                'female'           => $municipality->female_population,
                'population_0_19'  => $municipality->population_0_19,
                'population_20_59' => $municipality->population_20_59,
                'population_60_100'=> $municipality->population_60_100,
                'single_parents'   => $municipality->single_parent_count,
                'households'       => $municipality->total_households,
                'pending_apps'     => Application::where('municipality', $municipality->name)->where('status', 'pending')->count(),
                'approved_apps'    => Application::where('municipality', $municipality->name)->where('status', 'approved')->count(),
                'rejected_apps'    => Application::where('municipality', $municipality->name)->where('status', 'rejected')->count(),
                'programs'         => $programs->groupBy('program_type')->map->sum('beneficiary_count'),
                'age_groups'       => [
                    'Youth (0-19)'  => $municipality->population_0_19,
                    'Adult (20-59)' => $municipality->population_20_59,
                    'Senior (60-100)' => $municipality->population_60_100,
                ],
            ];

            foreach ($programs->pluck('program_type') as $type) {
                if (!in_array($type, $programTypes)) {
                    $programTypes[] = $type;
                }
            }
        }

        $programComparison = [];
        $allProgramTypes   = SocialWelfareProgram::distinct()->pluck('program_type');

        foreach ($allProgramTypes as $type) {
            $magdalena = SocialWelfareProgram::where('municipality', 'Magdalena')->where('program_type', $type)->sum('beneficiary_count');
            $liliw     = SocialWelfareProgram::where('municipality', 'Liliw')->where('program_type', $type)->sum('beneficiary_count');
            $majayjay  = SocialWelfareProgram::where('municipality', 'Majayjay')->where('program_type', $type)->sum('beneficiary_count');
            $total     = $magdalena + $liliw + $majayjay;

            $programComparison[$type] = [
                'program_type'         => $type,
                'magdalena'            => $magdalena,
                'liliw'                => $liliw,
                'majayjay'             => $majayjay,
                'total'                => $total,
                'highest'              => max($magdalena, $liliw, $majayjay),
                'highest_municipality' => $magdalena >= $liliw && $magdalena >= $majayjay
                    ? 'Magdalena'
                    : ($liliw >= $magdalena && $liliw >= $majayjay ? 'Liliw' : 'Majayjay'),
            ];
        }

        $municipalityProgramTotals = [
            'Magdalena' => SocialWelfareProgram::where('municipality', 'Magdalena')->sum('beneficiary_count'),
            'Liliw'     => SocialWelfareProgram::where('municipality', 'Liliw')->sum('beneficiary_count'),
            'Majayjay'  => SocialWelfareProgram::where('municipality', 'Majayjay')->sum('beneficiary_count'),
        ];

        $barangays = Barangay::whereIn('municipality', ['Magdalena', 'Liliw', 'Majayjay'])
            ->get()
            ->groupBy('municipality');

        return view('analysis.index', compact(
            'comparisonData',
            'programTypes',
            'barangays',
            'programComparison',
            'municipalityProgramTotals'
        ));
    }
}