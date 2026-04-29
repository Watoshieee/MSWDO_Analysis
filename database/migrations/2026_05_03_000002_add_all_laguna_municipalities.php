<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // List of all municipalities in Laguna with their codes
        $lagunaMunicipalities = [
            ['name' => 'Alaminos', 'code' => 'ALAMINOS'],
            ['name' => 'Bay', 'code' => 'BAY'],
            ['name' => 'Biñan', 'code' => 'BINAN'],
            ['name' => 'Cabuyao', 'code' => 'CABUYAO'],
            ['name' => 'Calamba', 'code' => 'CALAMBA'],
            ['name' => 'Calauan', 'code' => 'CALAUAN'],
            ['name' => 'Cavinti', 'code' => 'CAVINTI'],
            ['name' => 'Famy', 'code' => 'FAMY'],
            ['name' => 'Kalayaan', 'code' => 'KALAYAAN'],
            ['name' => 'Liliw', 'code' => 'LILIW'],
            ['name' => 'Los Baños', 'code' => 'LOS_BANOS'],
            ['name' => 'Luisiana', 'code' => 'LUISIANA'],
            ['name' => 'Lumban', 'code' => 'LUMBAN'],
            ['name' => 'Mabitac', 'code' => 'MABITAC'],
            ['name' => 'Magdalena', 'code' => 'MAGDALENA'],
            ['name' => 'Majayjay', 'code' => 'MAJAYJAY'],
            ['name' => 'Nagcarlan', 'code' => 'NAGCARLAN'],
            ['name' => 'Paete', 'code' => 'PAETE'],
            ['name' => 'Pagsanjan', 'code' => 'PAGSANJAN'],
            ['name' => 'Pakil', 'code' => 'PAKIL'],
            ['name' => 'Pangil', 'code' => 'PANGIL'],
            ['name' => 'Pila', 'code' => 'PILA'],
            ['name' => 'Rizal', 'code' => 'RIZAL'],
            ['name' => 'San Pablo', 'code' => 'SAN_PABLO'],
            ['name' => 'San Pedro', 'code' => 'SAN_PEDRO'],
            ['name' => 'Santa Cruz', 'code' => 'SANTA_CRUZ'],
            ['name' => 'Santa Maria', 'code' => 'SANTA_MARIA'],
            ['name' => 'Santa Rosa', 'code' => 'SANTA_ROSA'],
            ['name' => 'Siniloan', 'code' => 'SINILOAN'],
            ['name' => 'Victoria', 'code' => 'VICTORIA'],
        ];

        foreach ($lagunaMunicipalities as $municipality) {
            // Check if municipality already exists
            $exists = DB::table('municipalities')->where('name', $municipality['name'])->exists();
            
            if (!$exists) {
                DB::table('municipalities')->insert([
                    'name' => $municipality['name'],
                    'code' => $municipality['code'],  // ← idinagdag ito
                    'total_households' => 0,
                    'male_population' => 0,
                    'female_population' => 0,
                    'population_0_19' => 0,
                    'population_20_59' => 0,
                    'population_51_100' => 0,
                    'single_parent_count' => 0,
                    'created_at' => now(),
                    'year' => date('Y')
                ]);
            }
        }
    }

    public function down()
    {
        // List of municipality names to remove
        $lagunaMunicipalityNames = [
            'Alaminos', 'Bay', 'Biñan', 'Cabuyao', 'Calamba', 'Calauan', 'Cavinti',
            'Famy', 'Kalayaan', 'Liliw', 'Los Baños', 'Luisiana', 'Lumban', 'Mabitac',
            'Magdalena', 'Majayjay', 'Nagcarlan', 'Paete', 'Pagsanjan', 'Pakil',
            'Pangil', 'Pila', 'Rizal', 'San Pablo', 'San Pedro', 'Santa Cruz',
            'Santa Maria', 'Santa Rosa', 'Siniloan', 'Victoria'
        ];
        
        DB::table('municipalities')->whereIn('name', $lagunaMunicipalityNames)->delete();
    }
};