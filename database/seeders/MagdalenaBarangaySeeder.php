<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MagdalenaBarangaySeeder extends Seeder
{
    public function run(): void
    {
        $barangays = [
            'Alipit', 'Baanan', 'Balanac', 'Bucal', 'Buenavista',
            'Bungkol', 'Buo', 'Burlungan', 'Cigaras',
            'Ibabang Atingay', 'Ibabang Butnong',
            'Ilayang Atingay', 'Ilayang Butnong',
            'Ilog', 'Malaking Ambling', 'Malinao', 'Maravilla',
            'Munting Ambling', 'Poblacion', 'Sabang',
            'Salasad', 'Tanawan', 'Tipunan', 'Halayhayin',
        ];

        $currentYear = now()->year;

        foreach ($barangays as $name) {
            // Only insert if not already present
            $exists = DB::table('barangays')
                ->where('municipality', 'Magdalena')
                ->where('name', $name)
                ->exists();

            if (!$exists) {
                DB::table('barangays')->insert([
                    'municipality'              => 'Magdalena',
                    'name'                      => $name,
                    'total_population'          => 0,
                    'single_parent_count'       => 0,
                    'pwd_count'                 => 0,
                    'aics_count'                => 0,
                    'four_ps_count'             => 0,
                    'senior_count'              => 0,
                    'total_households'          => 0,
                    'total_approved_applications' => 0,
                    'year'                      => $currentYear,
                ]);
            }
        }

        $this->command->info('Magdalena barangays seeded: ' . count($barangays));
    }
}
