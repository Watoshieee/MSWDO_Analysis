<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BarangayDataController extends Controller
{
    /**
     * Get barangay data for a specific municipality and year.
     * Creates default records (population 0) for barangays that don't have a year entry yet.
     */
    public function getByYear(string $municipality, int $year): JsonResponse
    {
        try {
            if ($year < 2000 || $year > date('Y') + 1) {
                return response()->json(['error' => 'Invalid year'], 400);
            }

            // Get all barangay names for this municipality from the DB
            $barangayNames = Barangay::where('municipality', $municipality)
                ->select('name')
                ->distinct('name')
                ->get()
                ->pluck('name')
                ->toArray();

            // Fall back to config defaults if nothing in DB
            if (empty($barangayNames)) {
                $barangayNames = config("locations.barangays.{$municipality}", []);
            }

            $result = [];
            foreach ($barangayNames as $barangayName) {
                $result[] = Barangay::updateOrCreate(
                    [
                        'municipality' => $municipality,
                        'name'         => $barangayName,
                        'year'         => $year,
                    ],
                    [
                        'male_population'           => 0,
                        'female_population'         => 0,
                        'population_0_19'           => 0,
                        'population_20_59'          => 0,
                        'population_60_100'         => 0,
                        'single_parent_count'       => 0,
                        'total_households'          => 0,
                        'total_approved_applications' => 0,
                    ]
                );
            }

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Barangay data API error', [
                'municipality' => $municipality,
                'year'         => $year,
                'error'        => $e->getMessage(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
