<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // List of all municipalities in Laguna
        $lagunaMunicipalities = [
            'Alaminos', 'Bay', 'Biñan', 'Cabuyao', 'Calamba', 'Calauan', 'Cavinti',
            'Famy', 'Kalayaan', 'Liliw', 'Los Baños', 'Luisiana', 'Lumban', 'Mabitac',
            'Magdalena', 'Majayjay', 'Nagcarlan', 'Paete', 'Pagsanjan', 'Pakil',
            'Pangil', 'Pila', 'Rizal', 'San Pablo', 'San Pedro', 'Santa Cruz',
            'Santa Maria', 'Santa Rosa', 'Siniloan', 'Victoria'
        ];

        foreach ($lagunaMunicipalities as $municipality) {
            // Check if municipality already exists
            $exists = DB::table('municipalities')->where('name', $municipality)->exists();
            
            if (!$exists) {
                DB::table('municipalities')->insert([
                    'name' => $municipality,
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
        // Optional: Remove added municipalities
        $lagunaMunicipalities = [
            'Alaminos', 'Bay', 'Biñan', 'Cabuyao', 'Calamba', 'Calauan', 'Cavinti',
            'Famy', 'Kalayaan', 'Liliw', 'Los Baños', 'Luisiana', 'Lumban', 'Mabitac',
            'Magdalena', 'Majayjay', 'Nagcarlan', 'Paete', 'Pagsanjan', 'Pakil',
            'Pangil', 'Pila', 'Rizal', 'San Pablo', 'San Pedro', 'Santa Cruz',
            'Santa Maria', 'Santa Rosa', 'Siniloan', 'Victoria'
        ];
        
        DB::table('municipalities')->whereIn('name', $lagunaMunicipalities)->delete();
    }
};