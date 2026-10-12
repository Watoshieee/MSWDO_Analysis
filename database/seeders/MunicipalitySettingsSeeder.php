<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MunicipalitySettings;

class MunicipalitySettingsSeeder extends Seeder
{
    public function run(): void
    {
        $municipalities = [
            [
                'municipality_name' => 'Liliw',
                'display_name'      => 'Municipality of Liliw',
                'logo_path'         => null,
                'contact_email'     => null,
                'contact_phone'     => null,
                'office_address'    => null,
                'office_hours'      => null,
                'is_active'         => true,
            ],
            [
                'municipality_name' => 'Majayjay',
                'display_name'      => 'Municipality of Majayjay',
                'logo_path'         => null,
                'contact_email'     => null,
                'contact_phone'     => null,
                'office_address'    => null,
                'office_hours'      => null,
                'is_active'         => true,
            ],
            [
                'municipality_name' => 'Magdalena',
                'display_name'      => 'Municipality of Magdalena',
                'logo_path'         => null,
                'contact_email'     => null,
                'contact_phone'     => null,
                'office_address'    => null,
                'office_hours'      => null,
                'is_active'         => true,
            ],
        ];

        foreach ($municipalities as $muni) {
            MunicipalitySettings::updateOrCreate(
                ['municipality_name' => $muni['municipality_name']],
                $muni
            );
        }
    }
}
