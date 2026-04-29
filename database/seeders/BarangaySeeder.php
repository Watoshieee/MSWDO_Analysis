<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangaySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Magdalena' => [
                'Alipit', 'Malaking Ambling', 'Munting Ambling', 'Baanan', 'Balanac',
                'Bucal', 'Buenavista', 'Bungkol', 'Buo', 'Burlungan', 'Cigaras',
                'Ibabang Atingay', 'Ibabang Butnong', 'Ilayang Atingay', 'Ilayang Butnong',
                'Ilog', 'Malinao', 'Maravilla', 'Poblacion', 'Sabang', 'Salasad',
                'Tanawan', 'Tipunan', 'Halayhayin',
            ],
            'Liliw' => [
                'Bagong Anyo (Poblacion)', 'Bayate', 'Bongkol', 'Bubukal', 'Cabuyew',
                'Calumpang', 'San Isidro Culoy', 'Dagatan', 'Daniw', 'Dita',
                'Ibabang Palina', 'Ibabang San Roque', 'Ibabang Sungi', 'Ibabang Taykin',
                'Ilayang Palina', 'Ilayang San Roque', 'Ilayang Sungi', 'Ilayang Taykin',
                'Kanlurang Bukal', 'Laguan', 'Luquin', 'Malabo-Kalantukan',
                'Masikap (Poblacion)', 'Maslun (Poblacion)', 'Mojon', 'Novaliches',
                'Oples', 'Pag-asa (Poblacion)', 'Palayan', 'Rizal (Poblacion)',
                'Silangang Bukal', 'Tuy-Baanan',
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
                'Taytay', 'Villa Nogales',
            ],
        ];

        foreach ($data as $municipality => $barangays) {
            foreach ($barangays as $name) {
                // Use updateOrCreate to avoid duplicates on repeated seeding
                DB::table('barangays')->updateOrInsert(
                    ['municipality' => $municipality, 'name' => $name],
                    [
                        'municipality'               => $municipality,
                        'name'                       => $name,
                        'year'                       => date('Y'),
                        'male_population'            => 0,
                        'female_population'          => 0,
                        'population_0_19'            => 0,
                        'population_20_59'           => 0,
                        'population_60_100'          => 0,
                        'single_parent_count'        => 0,
                        'total_households'           => 0,
                        'total_approved_applications'=> 0,
                    ]
                );
            }
        }

        $total = \App\Models\Barangay::count();
        $this->command->info("✅ Seeded $total barangays for Magdalena, Liliw, and Majayjay.");
    }
}
