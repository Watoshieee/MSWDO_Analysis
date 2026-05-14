<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * MunicipalityDataSeeder
 *
 * Replaces the 4 municipality-related tables with production data
 * exported from u997292278_mswdo_analysis (May 14, 2026).
 *
 * Safe to re-run — uses TRUNCATE before INSERT.
 */
class MunicipalityDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // ── 1. municipalities ─────────────────────────────────────────────────
        DB::table('municipalities')->truncate();
        DB::table('municipalities')->insert([
            [
                'id'                 => 2,
                'name'               => 'Liliw',
                'total_households'   => 555,
                'total_population'   => 39976,
                'male_population'    => 545,
                'female_population'  => 545,
                'population_0_19'    => 200,
                'population_20_59'   => 556,
                'population_60_100'  => 555,
                'single_parent_count'=> 312,
                'created_at'         => '2026-02-25 07:54:14',
                'year'               => '2026',
                'deleted_at'         => null,
            ],
            [
                'id'                 => 3,
                'name'               => 'Majayjay',
                'total_households'   => 5938,
                'total_population'   => 28503,
                'male_population'    => 14708,
                'female_population'  => 13796,
                'population_0_19'    => 8266,
                'population_20_59'   => 18301,
                'population_60_100'  => 1937,
                'single_parent_count'=> 285,
                'created_at'         => '2026-02-25 15:54:14',
                'year'               => '2024',
                'deleted_at'         => null,
            ],
            [
                'id'                 => 8,
                'name'               => 'Magdalena',
                'total_households'   => 6050,
                'total_population'   => 28200,
                'male_population'    => 14181,
                'female_population'  => 13950,
                'population_0_19'    => 8949,
                'population_20_59'   => 17778,
                'population_60_100'  => 1404,
                'single_parent_count'=> 0,
                'created_at'         => '2026-04-23 10:21:01',
                'year'               => '2024',
                'deleted_at'         => null,
            ],
        ]);

        // ── 2. municipality_monthly_summary ───────────────────────────────────
        DB::table('municipality_monthly_summary')->truncate();
        DB::table('municipality_monthly_summary')->insert([
            [
                'id'               => 1,
                'municipality'     => 'Liliw',
                'year'             => 2026,
                'month'            => 4,
                'total_pwd'        => 6,
                'total_aics'       => 6,
                'total_solo_parent'=> 0,
                'notes'            => null,
                'created_at'       => '2026-04-13 01:28:32',
                'updated_at'       => '2026-04-13 01:28:32',
                'deleted_at'       => null,
            ],
        ]);

        // ── 3. municipality_visions ───────────────────────────────────────────
        DB::table('municipality_visions')->truncate();
        DB::table('municipality_visions')->insert([
            [
                'id'               => 1,
                'municipality_name'=> 'Liliw',
                'vision'           => "A municipality where all families and communities are empowered for a sustainable, improved quality of life through accessible basic social services.\nA society where the poor, vulnerable, and disadvantaged are empowered, free from poverty, and living in a safe, productive community.",
                'mission'          => "Provide comprehensive, gender-responsive, and community-based social welfare services, programs, and policies to marginalized sectors.\nProtect and rehabilitate abused, exploited, and disadvantaged individuals, including children, youth, and elderly.\nPartner with government agencies (like DSWD), NGOs, and the private sector to deliver effective social protection.",
                'goals'            => "Poverty Alleviation: Implement programs that reduce the ill-effects of poverty and uplift the economic status of families.\nSocial Protection: Provide immediate relief and intervention (AICS - Assistance to Individuals in Crisis Situations) to victims of disasters, abuse, and displacement.\nEmpowerment: Develop capabilities for self-reliance among vulnerable groups.\nService Delivery: Identify the needs of the poor and deliver essential services such as livelihood support, counseling, and child development (e.g., day care programs).\nSectoral Focus: Ensure specialized care for PWDs, senior citizens, children in need of special protection, and victims of violence (VAWC).",
                'strategic_goals'  => '["Social Welfare Protection and Development","Immediate Relief and Disaster Response","Community and Social Empowerment","Policy and Compliance"]',
                'created_at'       => '2026-04-28 00:02:25',
                'updated_at'       => '2026-04-28 22:09:58',
            ],
            [
                'id'               => 2,
                'municipality_name'=> 'Majayjay',
                'vision'           => "A municipality where all families and communities are empowered for a sustainable, improved quality of life through accessible basic social services.\nA society where the poor, vulnerable, and disadvantaged are empowered, free from poverty, and living in a safe, productive community.",
                'mission'          => "Provide comprehensive, gender-responsive, and community-based social welfare services, programs, and policies to marginalized sectors.\nProtect and rehabilitate abused, exploited, and disadvantaged individuals, including children, youth, and elderly.\nPartner with government agencies (like DSWD), NGOs, and the private sector to deliver effective social protection.",
                'goals'            => "Poverty Alleviation: Implement programs that reduce the ill-effects of poverty and uplift the economic status of families.\nSocial Protection: Provide immediate relief and intervention (AICS - Assistance to Individuals in Crisis Situations) to victims of disasters, abuse, and displacement.\nEmpowerment: Develop capabilities for self-reliance among vulnerable groups.\nService Delivery: Identify the needs of the poor and deliver essential services such as livelihood support, counseling, and child development (e.g., day care programs).\nSectoral Focus: Ensure specialized care for PWDs, senior citizens, children in need of special protection, and victims of violence (VAWC).",
                'strategic_goals'  => '["Social Welfare Protection and Development","Immediate Relief and Disaster Response","Community and Social Empowerment","Policy and Compliance"]',
                'created_at'       => '2026-04-28 02:05:24',
                'updated_at'       => '2026-04-28 22:15:40',
            ],
            [
                'id'               => 3,
                'municipality_name'=> 'Magdalena',
                'vision'           => "A municipality where all families and communities are empowered for a sustainable, improved quality of life through accessible basic social services.\nA society where the poor, vulnerable, and disadvantaged are empowered, free from poverty, and living in a safe, productive community.",
                'mission'          => "Provide comprehensive, gender-responsive, and community-based social welfare services, programs, and policies to marginalized sectors.\nProtect and rehabilitate abused, exploited, and disadvantaged individuals, including children, youth, and elderly.\nPartner with government agencies (like DSWD), NGOs, and the private sector to deliver effective social protection.",
                'goals'            => "Poverty Alleviation: Implement programs that reduce the ill-effects of poverty and uplift the economic status of families.\nSocial Protection: Provide immediate relief and intervention (AICS - Assistance to Individuals in Crisis Situations) to victims of disasters, abuse, and displacement.\nEmpowerment: Develop capabilities for self-reliance among vulnerable groups.\nService Delivery: Identify the needs of the poor and deliver essential services such as livelihood support, counseling, and child development (e.g., day care programs).",
                'strategic_goals'  => '["Social Welfare Protection and Development","Immediate Relief and Disaster Response","Community and Social Empowerment","Policy and Compliance"]',
                'created_at'       => '2026-04-28 22:17:13',
                'updated_at'       => '2026-04-28 22:18:14',
            ],
        ]);

        // ── 4. municipality_yearly_summary ────────────────────────────────────
        DB::table('municipality_yearly_summary')->truncate();
        DB::table('municipality_yearly_summary')->insert([
            ['id'=>4,  'municipality'=>'Majayjay',   'year'=>'2024','total_population'=>28503,'population_0_19'=>8266, 'population_20_59'=>18301,'population_60_100'=>1937,'male_population'=>14708,'female_population'=>13796,'total_households'=>5938,'total_4ps'=>1180,'total_pwd'=>0,'total_senior'=>845,'total_aics'=>0,'total_esa'=>5, 'total_slp'=>5, 'total_solo_parent'=>0,'created_at'=>'2026-04-27 09:31:44','deleted_at'=>null,'income_level'=>2,'unemployment_rate'=>7.60,'education_level'=>2,'migration_rate'=>-1.22,'marriage_rate'=>3.62,'poverty_rate'=>17.10,'literacy_rate'=>95.20,'school_enrollment_rate'=>86.80,'labor_force_participation_rate'=>59.20],
            ['id'=>5,  'municipality'=>'Liliw',      'year'=>'2024','total_population'=>39976,'population_0_19'=>11420,'population_20_59'=>25120,'population_60_100'=>2836,'male_population'=>20180,'female_population'=>19796,'total_households'=>9750,'total_4ps'=>0,   'total_pwd'=>0,'total_senior'=>0,  'total_aics'=>0,'total_esa'=>0, 'total_slp'=>0, 'total_solo_parent'=>0,'created_at'=>'2026-04-28 15:05:54','deleted_at'=>null,'income_level'=>null,'unemployment_rate'=>null,'education_level'=>null,'migration_rate'=>null,'marriage_rate'=>null,'poverty_rate'=>null,'literacy_rate'=>null,'school_enrollment_rate'=>null,'labor_force_participation_rate'=>null],
            ['id'=>6,  'municipality'=>'Magdalena',  'year'=>'2024','total_population'=>28200,'population_0_19'=>8949, 'population_20_59'=>17778,'population_60_100'=>1404,'male_population'=>14181,'female_population'=>13950,'total_households'=>6050,'total_4ps'=>1500,'total_pwd'=>0,'total_senior'=>540,'total_aics'=>0,'total_esa'=>0, 'total_slp'=>0, 'total_solo_parent'=>0,'created_at'=>'2026-04-27 09:29:52','deleted_at'=>null,'income_level'=>1,'unemployment_rate'=>8.20,'education_level'=>2,'migration_rate'=>-1.21,'marriage_rate'=>4.92,'poverty_rate'=>19.80,'literacy_rate'=>94.60,'school_enrollment_rate'=>85.40,'labor_force_participation_rate'=>58.30],
            ['id'=>11, 'municipality'=>'Magdalena',  'year'=>'2015','total_population'=>25266,'population_0_19'=>8034, 'population_20_59'=>15966,'population_60_100'=>1266,'male_population'=>12730,'female_population'=>12536,'total_households'=>5433,'total_4ps'=>820, 'total_pwd'=>0,'total_senior'=>540,'total_aics'=>0,'total_esa'=>0, 'total_slp'=>0, 'total_solo_parent'=>0,'created_at'=>'2026-04-27 09:29:39','deleted_at'=>null,'income_level'=>1,'unemployment_rate'=>8.20,'education_level'=>2,'migration_rate'=>-1.21,'marriage_rate'=>4.92,'poverty_rate'=>19.80,'literacy_rate'=>94.60,'school_enrollment_rate'=>85.40,'labor_force_participation_rate'=>58.30],
            ['id'=>14, 'municipality'=>'Majayjay',   'year'=>'2015','total_population'=>27792,'population_0_19'=>8060, 'population_20_59'=>17843,'population_60_100'=>1889,'male_population'=>14341,'female_population'=>13451,'total_households'=>5790,'total_4ps'=>1050,'total_pwd'=>0,'total_senior'=>710,'total_aics'=>0,'total_esa'=>0, 'total_slp'=>0, 'total_solo_parent'=>0,'created_at'=>'2026-04-27 09:30:32','deleted_at'=>null,'income_level'=>1,'unemployment_rate'=>9.10,'education_level'=>2,'migration_rate'=>-1.82,'marriage_rate'=>4.18,'poverty_rate'=>22.30,'literacy_rate'=>93.80,'school_enrollment_rate'=>83.60,'labor_force_participation_rate'=>56.40],
            ['id'=>15, 'municipality'=>'Majayjay',   'year'=>'2020','total_population'=>27893,'population_0_19'=>8086, 'population_20_59'=>17890,'population_60_100'=>1890,'male_population'=>14367,'female_population'=>13499,'total_households'=>5811,'total_4ps'=>1120,'total_pwd'=>0,'total_senior'=>780,'total_aics'=>0,'total_esa'=>0, 'total_slp'=>0, 'total_solo_parent'=>0,'created_at'=>'2026-04-27 09:30:15','deleted_at'=>null,'income_level'=>2,'unemployment_rate'=>8.40,'education_level'=>2,'migration_rate'=>-1.51,'marriage_rate'=>3.92,'poverty_rate'=>19.70,'literacy_rate'=>94.50,'school_enrollment_rate'=>85.10,'labor_force_participation_rate'=>57.80],
            ['id'=>16, 'municipality'=>'Magdalena',  'year'=>'2020','total_population'=>27816,'population_0_19'=>8832, 'population_20_59'=>17538,'population_60_100'=>1394,'male_population'=>14001,'female_population'=>13763,'total_households'=>5980,'total_4ps'=>0,   'total_pwd'=>0,'total_senior'=>0,  'total_aics'=>0,'total_esa'=>0, 'total_slp'=>0, 'total_solo_parent'=>0,'created_at'=>'2026-04-27 09:29:22','deleted_at'=>null,'income_level'=>2,'unemployment_rate'=>7.50,'education_level'=>2,'migration_rate'=>-0.92,'marriage_rate'=>4.48,'poverty_rate'=>17.40,'literacy_rate'=>95.30,'school_enrollment_rate'=>86.80,'labor_force_participation_rate'=>59.60],
            ['id'=>23, 'municipality'=>'Liliw',      'year'=>'2015','total_population'=>36582,'population_0_19'=>11900,'population_20_59'=>22300,'population_60_100'=>2382,'male_population'=>18105,'female_population'=>18477,'total_households'=>9308,'total_4ps'=>1180,'total_pwd'=>0,'total_senior'=>780,'total_aics'=>0,'total_esa'=>0, 'total_slp'=>0, 'total_solo_parent'=>0,'created_at'=>'2026-04-28 15:05:29','deleted_at'=>null,'income_level'=>2,'unemployment_rate'=>6.80,'education_level'=>2,'migration_rate'=>0.52,'marriage_rate'=>5.82,'poverty_rate'=>14.20,'literacy_rate'=>95.40,'school_enrollment_rate'=>88.20,'labor_force_participation_rate'=>62.50],
            ['id'=>26, 'municipality'=>'Liliw',      'year'=>'2020','total_population'=>39491,'population_0_19'=>12240,'population_20_59'=>24580,'population_60_100'=>2671,'male_population'=>19905,'female_population'=>19542,'total_households'=>9621,'total_4ps'=>0,   'total_pwd'=>0,'total_senior'=>0,  'total_aics'=>0,'total_esa'=>0, 'total_slp'=>0, 'total_solo_parent'=>0,'created_at'=>'2026-04-27 03:26:26','deleted_at'=>null,'income_level'=>2,'unemployment_rate'=>5.90,'education_level'=>2,'migration_rate'=>0.31,'marriage_rate'=>5.21,'poverty_rate'=>12.10,'literacy_rate'=>96.20,'school_enrollment_rate'=>89.80,'labor_force_participation_rate'=>63.40],
            ['id'=>33, 'municipality'=>'Alaminos',   'year'=>'2024','total_population'=>2500, 'population_0_19'=>0,    'population_20_59'=>0,    'population_60_100'=>0,   'male_population'=>0,    'female_population'=>0,    'total_households'=>500, 'total_4ps'=>0,   'total_pwd'=>0,'total_senior'=>0,  'total_aics'=>0,'total_esa'=>0, 'total_slp'=>0, 'total_solo_parent'=>0,'created_at'=>'2026-04-25 04:33:04','deleted_at'=>null,'income_level'=>null,'unemployment_rate'=>null,'education_level'=>null,'migration_rate'=>null,'marriage_rate'=>null,'poverty_rate'=>null,'literacy_rate'=>null,'school_enrollment_rate'=>null,'labor_force_participation_rate'=>null],
            ['id'=>34, 'municipality'=>'Nagcarlan',  'year'=>'2024','total_population'=>4999, 'population_0_19'=>0,    'population_20_59'=>0,    'population_60_100'=>0,   'male_population'=>0,    'female_population'=>0,    'total_households'=>250, 'total_4ps'=>0,   'total_pwd'=>0,'total_senior'=>0,  'total_aics'=>0,'total_esa'=>0, 'total_slp'=>0, 'total_solo_parent'=>0,'created_at'=>'2026-04-25 04:42:18','deleted_at'=>null,'income_level'=>null,'unemployment_rate'=>null,'education_level'=>null,'migration_rate'=>null,'marriage_rate'=>null,'poverty_rate'=>null,'literacy_rate'=>null,'school_enrollment_rate'=>null,'labor_force_participation_rate'=>null],
            ['id'=>35, 'municipality'=>'Santa Rosa', 'year'=>'2024','total_population'=>35000,'population_0_19'=>0,    'population_20_59'=>0,    'population_60_100'=>0,   'male_population'=>0,    'female_population'=>0,    'total_households'=>2500,'total_4ps'=>0,   'total_pwd'=>0,'total_senior'=>0,  'total_aics'=>0,'total_esa'=>0, 'total_slp'=>0, 'total_solo_parent'=>0,'created_at'=>'2026-04-25 06:18:51','deleted_at'=>null,'income_level'=>null,'unemployment_rate'=>null,'education_level'=>null,'migration_rate'=>null,'marriage_rate'=>null,'poverty_rate'=>null,'literacy_rate'=>null,'school_enrollment_rate'=>null,'labor_force_participation_rate'=>null],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('✅ Municipality data seeded: municipalities, monthly_summary, visions, yearly_summary');
    }
}
