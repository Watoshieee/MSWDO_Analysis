<?php

namespace Tests\Unit;

use App\Models\SocialWelfareProgram;
use App\Services\AhpRankingService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AhpRankingServiceTest extends TestCase
{
    private AhpRankingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AhpRankingService();

        if (!Schema::hasTable('social_welfare_programs')) {
            Schema::create('social_welfare_programs', function (Blueprint $table) {
                $table->id();
                $table->string('municipality');
                $table->string('barangay')->nullable();
                $table->string('program_type');
                $table->integer('beneficiary_count')->nullable();
                $table->integer('year');
                $table->integer('month')->nullable();
                $table->softDeletes();
            });
        }
    }

    protected function tearDown(): void
    {
        if (Schema::hasTable('social_welfare_programs')) {
            SocialWelfareProgram::withTrashed()->forceDelete();
        }
        parent::tearDown();
    }

    public function test_effective_weights_sum_to_one(): void
    {
        $sum = array_sum($this->service->effectiveWeights());
        $this->assertEqualsWithDelta(1.0, $sum, 0.0000001);
    }

    public function test_complete_valid_data_produces_ranking(): void
    {
        $rows = $this->seedComplete(2024);
        $built = $this->service->buildAnnualLookup($rows);
        $result = $this->service->analyze(2024, $rows, $built['lookup'], $built['duplicates']);

        $this->assertTrue($result['ranking_available']);
        $this->assertSame('Computed', $result['status']);
        $this->assertCount(3, $result['ranking_rows']);
        $this->assertSame('Majayjay', $result['ranking_rows'][0]['municipality']);
        $this->assertGreaterThan($result['scores']['Liliw'], $result['scores']['Majayjay']);
        $this->assertArrayNotHasKey('AICS', $result['matrix']['Liliw']);
    }

    public function test_zero_count_is_na_and_blocks_ranking(): void
    {
        $rows = $this->seedComplete(2024, ['Liliw' => ['PWD_Assistance' => 0]]);
        $built = $this->service->buildAnnualLookup($rows);
        $result = $this->service->analyze(2024, $rows, $built['lookup'], $built['duplicates']);

        $this->assertNull($built['lookup']['Liliw'][2024]['PWD_Assistance']);
        $this->assertFalse($result['ranking_available']);
        $this->assertEmpty($result['ranking_rows']);
        $this->assertTrue(collect($result['missing'])->contains(
            fn ($item) => $item['municipality'] === 'Liliw' && $item['program_type'] === 'PWD_Assistance'
        ));
    }

    public function test_missing_annual_record_blocks_ranking(): void
    {
        $rows = $this->seedComplete(2024);
        $rows = $rows->reject(function ($row) {
            return $row->municipality === 'Magdalena' && $row->program_type === 'Solo_Parent';
        })->values();
        $built = $this->service->buildAnnualLookup($rows);
        $result = $this->service->analyze(2024, $rows, $built['lookup'], $built['duplicates']);

        $this->assertFalse($result['ranking_available']);
        $this->assertTrue(collect($result['missing'])->contains(
            fn ($item) => $item['municipality'] === 'Magdalena' && $item['program_type'] === 'Solo_Parent'
        ));
    }

    public function test_equal_criterion_values_share_normalized_one(): void
    {
        $overrides = [];
        foreach (['Liliw', 'Magdalena', 'Majayjay'] as $muni) {
            $overrides[$muni]['Senior_Citizen_Pension'] = 100;
        }
        $rows = $this->seedComplete(2024, $overrides);
        $built = $this->service->buildAnnualLookup($rows);
        $result = $this->service->analyze(2024, $rows, $built['lookup'], $built['duplicates']);

        $this->assertTrue($result['ranking_available']);
        $this->assertContains('Senior_Citizen_Pension', $result['equal_criteria']);
        foreach (AhpRankingService::MUNICIPALITIES as $muni) {
            $this->assertSame(1.0, $result['normalized'][$muni]['Senior_Citizen_Pension']);
        }
    }

    public function test_new_year_appears_automatically(): void
    {
        $this->persist($this->seedComplete(2024));
        $this->persist($this->seedComplete(2025));

        $years = $this->service->availableProgramYears();
        $this->assertContains(2024, $years);
        $this->assertContains(2025, $years);
    }

    public function test_updated_count_changes_score(): void
    {
        $low = $this->seedComplete(2024);
        $lowBuilt = $this->service->buildAnnualLookup($low);
        $lowResult = $this->service->analyze(2024, $low, $lowBuilt['lookup'], $lowBuilt['duplicates']);

        $high = $this->seedComplete(2024, ['Liliw' => [
            'Senior_Citizen_Pension' => 900,
            'PWD_Assistance' => 800,
            'Solo_Parent' => 700,
        ]]);
        $highBuilt = $this->service->buildAnnualLookup($high);
        $highResult = $this->service->analyze(2024, $high, $highBuilt['lookup'], $highBuilt['duplicates']);

        $this->assertGreaterThan($lowResult['scores']['Liliw'], $highResult['scores']['Liliw']);
        $this->assertSame(1, $highResult['ranks']['Liliw']);
    }

    public function test_duplicate_annual_rows_are_detected_without_double_counting(): void
    {
        $this->persist($this->seedComplete(2024));
        SocialWelfareProgram::create([
            'municipality' => 'Liliw',
            'program_type' => 'PWD_Assistance',
            'beneficiary_count' => 9999,
            'year' => 2024,
            'month' => null,
        ]);

        $rows = $this->service->loadAnnualRows();
        $built = $this->service->buildAnnualLookup($rows);

        $this->assertNotEmpty($built['duplicates']);
        $this->assertSame(9999, $built['lookup']['Liliw'][2024]['PWD_Assistance']);
        $this->assertSame(1, collect($built['duplicates'])->where('municipality', 'Liliw')->where('program_type', 'PWD_Assistance')->count());
    }

    public function test_monthly_rows_are_not_used_as_annual_totals(): void
    {
        $this->persist($this->seedComplete(2024, ['Liliw' => ['Solo_Parent' => null]], skipMissing: true));
        SocialWelfareProgram::create([
            'municipality' => 'Liliw',
            'program_type' => 'Solo_Parent',
            'beneficiary_count' => 500,
            'year' => 2024,
            'month' => 6,
        ]);

        $rows = $this->service->loadAnnualRows();
        $built = $this->service->buildAnnualLookup($rows);
        $this->assertArrayNotHasKey('Solo_Parent', $built['lookup']['Liliw'][2024] ?? []);

        $result = $this->service->analyze(2024, $rows, $built['lookup'], $built['duplicates']);
        $this->assertFalse($result['ranking_available']);
    }

    public function test_soft_deleted_rows_are_excluded(): void
    {
        $this->persist($this->seedComplete(2024));
        $row = SocialWelfareProgram::where('municipality', 'Majayjay')
            ->where('program_type', 'PWD_Assistance')
            ->whereNull('month')
            ->first();
        $row->delete();

        $rows = $this->service->loadAnnualRows();
        $built = $this->service->buildAnnualLookup($rows);
        $this->assertArrayNotHasKey('PWD_Assistance', $built['lookup']['Majayjay'][2024] ?? []);
    }

    public function test_aics_is_excluded_from_ahp_but_kept_in_lookup(): void
    {
        $rows = $this->seedComplete(2024);
        $rows->push($this->makeRow('Liliw', 'AICS', 2024, 80));
        $built = $this->service->buildAnnualLookup($rows);
        $result = $this->service->analyze(2024, $rows, $built['lookup'], $built['duplicates']);

        $this->assertSame(80, $built['lookup']['Liliw'][2024]['AICS']);
        $this->assertArrayNotHasKey('AICS', $result['matrix']['Liliw']);
        $this->assertTrue($result['ranking_available']);
    }

    public function test_eccd_and_kababaihan_are_excluded_from_programs_analysis(): void
    {
        $rows = $this->seedComplete(2024);
        $rows->push($this->makeRow('Magdalena', 'ECCD', 2024, 627));
        $rows->push($this->makeRow('Liliw', 'Kababaihan', 2024, 40));
        $built = $this->service->buildAnnualLookup($rows);
        $result = $this->service->analyze(2024, $rows, $built['lookup'], $built['duplicates']);

        $this->assertArrayNotHasKey('ECCD', $built['lookup']['Magdalena'][2024] ?? []);
        $this->assertArrayNotHasKey('Kababaihan', $built['lookup']['Liliw'][2024] ?? []);
        $this->assertFalse(collect($result['insights'])->contains(fn ($line) => str_contains($line, 'ECCD')));
        $this->assertFalse(collect($result['insights'])->contains(fn ($line) => str_contains($line, 'Kababaihan')));
    }

    public function test_other_municipalities_are_ignored(): void
    {
        $rows = $this->seedComplete(2024);
        $rows->push($this->makeRow('Nagcarlan', 'PWD_Assistance', 2024, 8000));
        $rows->push($this->makeRow('Santa Rosa', 'Solo_Parent', 2024, 8000));
        $built = $this->service->buildAnnualLookup($rows);

        $this->assertArrayNotHasKey('Nagcarlan', $built['lookup']);
        $this->assertArrayNotHasKey('Santa Rosa', $built['lookup']);
    }

    public function test_growth_does_not_treat_na_as_zero(): void
    {
        $rows = $this->seedComplete(2023, ['Liliw' => ['PWD_Assistance' => 0]]);
        $rows = $rows->concat($this->seedComplete(2024));
        $built = $this->service->buildAnnualLookup($rows);
        $result = $this->service->analyze(2024, $rows, $built['lookup'], $built['duplicates']);

        $this->assertTrue(collect($result['insights'])->contains(
            fn ($line) => str_contains($line, 'cannot be calculated') && str_contains($line, 'PWD Assistance')
        ));
        $this->assertFalse(collect($result['insights'])->contains(
            fn ($line) => str_contains($line, 'increased from 0')
        ));
    }

    /**
     * @param  array<string, array<string, int|null>>  $overrides
     */
    private function seedComplete(int $year, array $overrides = [], bool $skipMissing = false): Collection
    {
        $base = [
            'Liliw' => ['Senior_Citizen_Pension' => 120, 'PWD_Assistance' => 90, 'Solo_Parent' => 70],
            'Magdalena' => ['Senior_Citizen_Pension' => 150, 'PWD_Assistance' => 110, 'Solo_Parent' => 80],
            'Majayjay' => ['Senior_Citizen_Pension' => 200, 'PWD_Assistance' => 140, 'Solo_Parent' => 95],
        ];
        foreach ($overrides as $muni => $values) {
            foreach ($values as $type => $count) {
                $base[$muni][$type] = $count;
            }
        }

        $rows = collect();
        $id = (int) (microtime(true) * 1000) % 1000000;
        foreach ($base as $muni => $programs) {
            foreach ($programs as $type => $count) {
                if ($skipMissing && $count === null) {
                    continue;
                }
                $rows->push($this->makeRow($muni, $type, $year, $count, $id++));
            }
        }

        return $rows;
    }

    private function persist(Collection $rows): void
    {
        foreach ($rows as $row) {
            SocialWelfareProgram::create([
                'municipality' => $row->municipality,
                'program_type' => $row->program_type,
                'beneficiary_count' => $row->beneficiary_count,
                'year' => $row->year,
                'month' => null,
            ]);
        }
    }

    private function makeRow(string $muni, string $type, int $year, ?int $count, ?int $id = null): SocialWelfareProgram
    {
        $row = new SocialWelfareProgram();
        $row->forceFill([
            'municipality' => $muni,
            'program_type' => $type,
            'beneficiary_count' => $count,
            'year' => $year,
            'month' => null,
        ]);
        $row->id = $id ?? random_int(1, 999999);

        return $row;
    }
}
