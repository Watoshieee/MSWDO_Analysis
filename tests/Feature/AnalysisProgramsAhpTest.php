<?php

namespace Tests\Feature;

use App\Models\SocialWelfareProgram;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AnalysisProgramsAhpTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

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
        if (!Schema::hasTable('municipality_yearly_summary')) {
            Schema::create('municipality_yearly_summary', function (Blueprint $table) {
                $table->id();
                $table->string('municipality');
                $table->integer('year');
                $table->integer('total_population')->nullable();
                $table->integer('total_households')->nullable();
                $table->integer('male_population')->nullable();
                $table->integer('female_population')->nullable();
                $table->integer('population_0_19')->nullable();
                $table->integer('population_20_59')->nullable();
                $table->integer('population_60_100')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->softDeletes();
            });
        }
        if (!Schema::hasTable('municipality_visions')) {
            Schema::create('municipality_visions', function (Blueprint $table) {
                $table->id();
                $table->string('municipality_name');
                $table->text('vision')->nullable();
                $table->text('mission')->nullable();
                $table->text('goals')->nullable();
                $table->json('strategic_goals')->nullable();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('municipalities')) {
            Schema::create('municipalities', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamp('created_at')->nullable();
                $table->softDeletes();
            });
        }
    }

    protected function tearDown(): void
    {
        if (Schema::hasTable('social_welfare_programs')) {
            SocialWelfareProgram::query()->withTrashed()->forceDelete();
        }
        parent::tearDown();
    }

    public function test_programs_page_uses_ahp_and_omits_wsm(): void
    {
        $this->seedYear(2024);

        $response = $this->get('/analysis/programs?category=programs&year=2024');
        $response->assertOk();
        $html = $response->getContent();

        $this->assertStringContainsString('AHP-Weighted Comparative Ranking', $html);
        $this->assertStringContainsString('AHP-weighted comparative score based on beneficiary counts', $html);
        $this->assertStringNotContainsString('WSM:', $html);
        $this->assertStringNotContainsString('Weighted Scoring Model', $html);
        $this->assertStringContainsString('year-pills-wrap', $html);
        $this->assertStringContainsString('Descriptive Analysis', $html);
        $this->assertStringContainsString('Key Insights', $html);
        $this->assertStringContainsString('id="recommendations-analysis"', $html);
        $this->assertStringContainsString('Recommendation 1', $html);
        $this->assertLessThanOrEqual(10, preg_match_all('/Finding \\d+/', $html));
        $this->assertStringNotContainsString('Finding 11', $html);
        $this->assertStringNotContainsString('ECCD', $html);
        $this->assertStringNotContainsString('Kababaihan', $html);
    }

    public function test_html_csv_and_docx_exports_match_ahp_and_omit_wsm(): void
    {
        $this->seedYear(2024);
        $base = '/analysis/programs?category=programs&year=2024&download=report';

        $html = $this->get($base . '&format=html');
        $html->assertOk();
        $this->assertStringContainsString('AHP-Weighted Comparative Ranking', $html->getContent());
        $this->assertStringNotContainsString('How WSM will be calculated', $html->getContent());

        $csv = $this->get($base . '&format=csv');
        $csv->assertOk();
        $this->assertStringContainsString('AHP-weighted comparative ranking', $csv->getContent());
        $this->assertStringNotContainsString('WSM status', $csv->getContent());
        $this->assertStringContainsString('stored 0', $csv->getContent());

        $docx = $this->get($base . '&format=docx');
        if (class_exists('ZipArchive')) {
            $docx->assertOk();
            $this->assertStringContainsString(
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                $docx->headers->get('Content-Type')
            );
        }
    }

    public function test_incomplete_year_explains_unavailable_ranking(): void
    {
        $this->seedYear(2024, skipSoloParentLiliw: true);

        $response = $this->get('/analysis/programs?category=programs&year=2024');
        $response->assertOk();
        $this->assertStringContainsString('cannot be calculated', $response->getContent());
        $this->assertStringContainsString('Liliw / Solo Parent', $response->getContent());
    }

    private function seedYear(int $year, bool $skipSoloParentLiliw = false): void
    {
        $data = [
            'Liliw' => ['Senior_Citizen_Pension' => 120, 'PWD_Assistance' => 90, 'Solo_Parent' => 70, 'AICS' => 40, 'Kababaihan' => 40],
            'Magdalena' => ['Senior_Citizen_Pension' => 150, 'PWD_Assistance' => 110, 'Solo_Parent' => 80, 'AICS' => 55, 'ECCD' => 627],
            'Majayjay' => ['Senior_Citizen_Pension' => 200, 'PWD_Assistance' => 140, 'Solo_Parent' => 95, 'AICS' => 60],
        ];
        foreach ($data as $muni => $programs) {
            foreach ($programs as $type => $count) {
                if ($skipSoloParentLiliw && $muni === 'Liliw' && $type === 'Solo_Parent') {
                    continue;
                }
                SocialWelfareProgram::create([
                    'municipality' => $muni,
                    'program_type' => $type,
                    'beneficiary_count' => $count,
                    'year' => $year,
                    'month' => null,
                ]);
            }
        }
    }
}
