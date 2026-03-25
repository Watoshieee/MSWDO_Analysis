<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramRequirement;

class AllProgramRequirementsSeeder extends Seeder
{
    public function run()
    {
        $requirements = [
            // AICS Medical Assistance
            ['program_type' => 'AICS_Medical', 'requirement_name' => 'Certificate of Indigency (Original)'],
            ['program_type' => 'AICS_Medical', 'requirement_name' => 'Medical Certificate'],
            ['program_type' => 'AICS_Medical', 'requirement_name' => 'Marriage Contract (if spouse)'],
            ['program_type' => 'AICS_Medical', 'requirement_name' => 'Birth Certificate (if parent/children)'],
            ['program_type' => 'AICS_Medical', 'requirement_name' => 'Photocopy of ID (patient & claimant)'],
            ['program_type' => 'AICS_Medical', 'requirement_name' => 'Authorization Letter (if applicable)'],
            
            // AICS Burial Assistance
            ['program_type' => 'AICS_Burial', 'requirement_name' => 'Certificate of Indigency'],
            ['program_type' => 'AICS_Burial', 'requirement_name' => 'Death Certificate'],
            ['program_type' => 'AICS_Burial', 'requirement_name' => 'Marriage Contract'],
            ['program_type' => 'AICS_Burial', 'requirement_name' => 'Birth Certificate'],
            ['program_type' => 'AICS_Burial', 'requirement_name' => 'Valid IDs'],
            ['program_type' => 'AICS_Burial', 'requirement_name' => 'Authorization Letter'],
            
            // Solo Parent ID
            ['program_type' => 'Solo_Parent', 'requirement_name' => 'Application Form'],
            ['program_type' => 'Solo_Parent', 'requirement_name' => 'Cedula'],
            ['program_type' => 'Solo_Parent', 'requirement_name' => "Voter's ID"],
            ['program_type' => 'Solo_Parent', 'requirement_name' => 'Birth Certificate (minor)'],
            ['program_type' => 'Solo_Parent', 'requirement_name' => 'Barangay Certification'],
            
            // PWD ID New
            ['program_type' => 'PWD_New', 'requirement_name' => "Voter's ID"],
            ['program_type' => 'PWD_New', 'requirement_name' => 'Medical Certificate'],
            ['program_type' => 'PWD_New', 'requirement_name' => 'Registration Form with Cedula'],
            ['program_type' => 'PWD_New', 'requirement_name' => 'Barangay & President Certification'],
            ['program_type' => 'PWD_New', 'requirement_name' => 'Birth Certificate'],
            
            // PWD ID Renewal
            ['program_type' => 'PWD_Renewal', 'requirement_name' => "Voter's ID"],
            ['program_type' => 'PWD_Renewal', 'requirement_name' => 'Medical Certificate'],
            ['program_type' => 'PWD_Renewal', 'requirement_name' => 'Affidavit (Sinumpaang Salaysay)'],
            ['program_type' => 'PWD_Renewal', 'requirement_name' => 'Birth Certificate'],
            ['program_type' => 'PWD_Renewal', 'requirement_name' => 'Payment (PHP 100)'],
            
            // 4Ps Program
            ['program_type' => '4Ps', 'requirement_name' => 'Birth Certificates (all family members)'],
            ['program_type' => '4Ps', 'requirement_name' => 'School IDs / Report Cards'],
            ['program_type' => '4Ps', 'requirement_name' => 'Barangay Certificate'],
            ['program_type' => '4Ps', 'requirement_name' => 'Valid ID'],
            ['program_type' => '4Ps', 'requirement_name' => '1x1 Pictures'],
            ['program_type' => '4Ps', 'requirement_name' => 'Health Records (0-5 yrs old)'],
            
            // Senior Citizen ID
            ['program_type' => 'Senior_Citizen', 'requirement_name' => 'OSCA Application Form'],
            ['program_type' => 'Senior_Citizen', 'requirement_name' => 'ID Photos'],
            ['program_type' => 'Senior_Citizen', 'requirement_name' => 'Birth Certificate / Valid ID'],
            ['program_type' => 'Senior_Citizen', 'requirement_name' => 'Barangay Certificate (if needed)'],
            ['program_type' => 'Senior_Citizen', 'requirement_name' => "Voter's Certification (if needed)"],
            ['program_type' => 'Senior_Citizen', 'requirement_name' => 'Authorization Letter (if applicable)'],
        ];
        
        foreach ($requirements as $req) {
            ProgramRequirement::updateOrCreate(
                [
                    'program_type' => $req['program_type'],
                    'requirement_name' => $req['requirement_name']
                ],
                $req
            );
        }
        
        $this->command->info('All program requirements seeded successfully!');
    }
}