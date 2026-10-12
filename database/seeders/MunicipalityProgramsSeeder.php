<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MunicipalityProgram;

class MunicipalityProgramsSeeder extends Seeder
{
    public function run(): void
    {
        // Programs confirmed from existing routes and controllers:
        //
        // All municipalities:
        //   PWD_Assistance  - /user/pwd-application, /user/pwd-fillable-form
        //   Solo_Parent     - /user/solo-parent-application, /apply/solo-parent
        //   AICS_Medical    - /user/apply/AICS/medical
        //   AICS_Burial     - /user/apply/AICS/burial
        //
        // Liliw only (confirmed by EnsureLiliwMunicipality + isLiliwResident()):
        //   MTA             - /user/mta-application
        //   Day_Care        - /user/day-care-registration

        $sharedPrograms = [
            [
                'program_key'   => 'PWD_Assistance',
                'program_label' => 'PWD Assistance',
                'description'   => 'Support services for persons with disability.',
                'display_order' => 1,
            ],
            [
                'program_key'   => 'Solo_Parent',
                'program_label' => 'Solo Parent Support',
                'description'   => 'Assistance for solo parents raising children alone.',
                'display_order' => 2,
            ],
            [
                'program_key'   => 'AICS_Medical',
                'program_label' => 'AICS - Medical Assistance',
                'description'   => 'Emergency medical financial aid for families in crisis situations.',
                'display_order' => 3,
            ],
            [
                'program_key'   => 'AICS_Burial',
                'program_label' => 'AICS - Burial Assistance',
                'description'   => 'Financial assistance for burial expenses of indigent families.',
                'display_order' => 4,
            ],
        ];

        $liliwOnlyPrograms = [
            [
                'program_key'   => 'MTA',
                'program_label' => 'Municipal Tulong Aral',
                'description'   => 'Educational assistance program for students.',
                'display_order' => 5,
            ],
            [
                'program_key'   => 'Day_Care',
                'program_label' => 'Day Care Registration',
                'description'   => 'Registration for day care services.',
                'display_order' => 6,
            ],
        ];

        $allMunicipalities = ['Liliw', 'Majayjay', 'Magdalena'];

        // Seed shared programs for all municipalities
        foreach ($allMunicipalities as $muni) {
            foreach ($sharedPrograms as $program) {
                MunicipalityProgram::updateOrCreate(
                    [
                        'municipality_name' => $muni,
                        'program_key'       => $program['program_key'],
                    ],
                    array_merge($program, [
                        'municipality_name' => $muni,
                        'is_active'         => true,
                    ])
                );
            }
        }

        // Seed Liliw-only programs
        foreach ($liliwOnlyPrograms as $program) {
            MunicipalityProgram::updateOrCreate(
                [
                    'municipality_name' => 'Liliw',
                    'program_key'       => $program['program_key'],
                ],
                array_merge($program, [
                    'municipality_name' => 'Liliw',
                    'is_active'         => true,
                ])
            );
        }
    }
}
