<?php

namespace App\Services;

use App\Models\Application;
use App\Models\FileMonitoring;
use App\Models\SoloParentCategoryRequirement;
use App\Models\SoloParentApplicationRequirement;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class SoloParentCategoryService
{
    public const BENEFIT_CODE_1_2 = 'CODE_1_2';
    public const BENEFIT_TITLE_1_2 = 'Solo Parent Availing Subsidy & Discount';

    public static function getBenefitDefinitions(): array
    {
        return [
            self::BENEFIT_CODE_1_2 => [
                [
                    'group_key'        => 'benefit_affidavit_no_employment',
                    'group_title'      => 'Affidavit of No Employment',
                    'requirement_name' => 'Affidavit of no employment',
                    'is_or_group'      => false,
                    'order_num'        => 1,
                ],
                [
                    'group_key'        => 'benefit_itr',
                    'group_title'      => 'Income Tax Return (ITR)',
                    'requirement_name' => 'Income Tax Return (ITR)',
                    'is_or_group'      => false,
                    'order_num'        => 2,
                ],
                [
                    'group_key'        => 'benefit_social_case_study',
                    'group_title'      => 'Social Case Study',
                    'requirement_name' => 'Social Case Study issued by the DSWD',
                    'is_or_group'      => false,
                    'order_num'        => 3,
                ],
                [
                    'group_key'        => 'benefit_proof_of_income',
                    'group_title'      => 'Verifiable Proof of Income',
                    'requirement_name' => 'Any verifiable proof of income',
                    'is_or_group'      => false,
                    'order_num'        => 4,
                ],
            ],
        ];
    }
    public static function getCategories(): array
    {
        return [
            'A1' => 'A1 — Rape',
            'A2' => 'A2 — Death of Spouse',
            'A3' => 'A3 — Detention/Criminal Conviction of Spouse',
            'A4' => 'A4 — Physical/Mental Incapacity of Spouse',
            'A5' => 'A5 — Legal/De Facto Separation of Spouse',
            'A6' => 'A6 — Declaration of Nullity/Annulment of Marriage',
            'A7' => 'A7 — Abandonment by Spouse',
            'B'  => 'B — OFW Spouse/Family Member',
            'C'  => 'C — Unmarried Mother & Father',
            'D'  => 'D — Legal Guardian, Adoptive or Foster Parent',
            'E'  => 'E — Consanguinity or Affinity of Parent/Guardian',
            'F'  => 'F — Solo Parent Who Are Pregnant',
        ];
    }

    public static function getDefaultDefinitions(): array
    {
        return [
            'A1' => [
                [
                    'group_key' => 'a1_birth_cert',
                    'group_title' => 'Birth Certificate',
                    'requirement_name' => 'Xerox copy of Birth Certificate/s of the child or children',
                    'is_or_group' => false,
                    'order_num' => 1,
                ],
                [
                    'group_key' => 'a1_complaint_affidavit',
                    'group_title' => 'Complaint Affidavit',
                    'requirement_name' => 'Complaint Affidavit',
                    'is_or_group' => false,
                    'order_num' => 2,
                ],
                [
                    'group_key' => 'a1_sole_parental_care',
                    'group_title' => 'Sworn Affidavit of Sole Parental Care',
                    'requirement_name' => 'Sworn affidavit declaring that the solo parent has the sole parental care and support of the child or children at the time of the execution of affidavit',
                    'is_or_group' => false,
                    'order_num' => 3,
                ],
            ],
            'A2' => [
                [
                    'group_key' => 'a2_birth_cert',
                    'group_title' => 'Birth Certificate',
                    'requirement_name' => 'Xerox copy of Birth Certificate/s of the child or children',
                    'is_or_group' => false,
                    'order_num' => 1,
                ],
                [
                    'group_key' => 'a2_marriage_contract',
                    'group_title' => 'Marriage Contract',
                    'requirement_name' => 'Xerox copy of Marriage contract',
                    'is_or_group' => false,
                    'order_num' => 2,
                ],
                [
                    'group_key' => 'a2_death_cert',
                    'group_title' => 'Death Certificate of Spouse',
                    'requirement_name' => 'Xerox copy of Death Certificate of the spouse',
                    'is_or_group' => false,
                    'order_num' => 3,
                ],
                [
                    'group_key' => 'a2_affidavit_support',
                    'group_title' => 'Sworn Affidavit of Non-Cohabitation & Support',
                    'requirement_name' => 'Sworn affidavit declaring that the solo parent is not cohabiting with a partner or co-parent, and has the sole parental care and support of the child or children',
                    'is_or_group' => false,
                    'order_num' => 4,
                ],
            ],            'A3' => [
                [
                    'group_key' => 'a3_birth_cert',
                    'group_title' => 'Birth Certificate',
                    'requirement_name' => 'Xerox copy of Birth Certificate/s of the child or children',
                    'is_or_group' => false,
                    'order_num' => 1,
                ],
                [
                    'group_key' => 'a3_marriage_contract',
                    'group_title' => 'Marriage Contract',
                    'requirement_name' => 'Xerox copy of Marriage contract',
                    'is_or_group' => false,
                    'order_num' => 2,
                ],
                [
                    'group_key' => 'a3_detention_proof',
                    'group_title' => 'Proof of Detention or Criminal Conviction (Submit 1)',
                    'requirement_name' => 'Certificate of detention or certification that the spouse is serving sentence for at least 3 months issued by the law enforcement agency having actual custody of the detained spouse',
                    'is_or_group' => true,
                    'order_num' => 3,
                ],
                [
                    'group_key' => 'a3_detention_proof',
                    'group_title' => 'Proof of Detention or Criminal Conviction (Submit 1)',
                    'requirement_name' => 'Commitment order issued by the court pursuant to conviction of the spouse',
                    'is_or_group' => true,
                    'order_num' => 4,
                ],
                [
                    'group_key' => 'a3_affidavit_support',
                    'group_title' => 'Sworn Affidavit of Non-Cohabitation & Support',
                    'requirement_name' => 'Sworn affidavit declaring that the solo parent is not cohabiting with a partner or co-parent, and has sole parental care and support of the child or children',
                    'is_or_group' => false,
                    'order_num' => 5,
                ],
            ],
            'A4' => [
                [
                    'group_key' => 'a4_birth_cert',
                    'group_title' => 'Birth Certificate',
                    'requirement_name' => 'Xerox copy of Birth Certificate/s of the child or children',
                    'is_or_group' => false,
                    'order_num' => 1,
                ],
                [
                    'group_key' => 'a4_marriage_or_cohabitation',
                    'group_title' => 'Proof of Marriage or Cohabitation (Submit 1)',
                    'requirement_name' => 'Xerox copy of Marriage certificate',
                    'is_or_group' => true,
                    'order_num' => 2,
                ],
                [
                    'group_key' => 'a4_marriage_or_cohabitation',
                    'group_title' => 'Proof of Marriage or Cohabitation (Submit 1)',
                    'requirement_name' => 'Affidavit of cohabitation',
                    'is_or_group' => true,
                    'order_num' => 3,
                ],
                [
                    'group_key' => 'a4_medical_record',
                    'group_title' => 'Medical Record / Abstract',
                    'requirement_name' => 'Medical record or medical abstract evidencing the physical or mental state of the incapacitated spouse issued not more than 3 months before submission',
                    'is_or_group' => false,
                    'order_num' => 4,
                ],
                [
                    'group_key' => 'a4_affidavit_support',
                    'group_title' => 'Sworn Affidavit of Non-Cohabitation & Support',
                    'requirement_name' => 'Sworn affidavit that the solo parent is not cohabiting with a partner or co-parent and has sole parental care and support of the child or children',
                    'is_or_group' => false,
                    'order_num' => 5,
                ],
            ],
            'A5' => [
                [
                    'group_key' => 'a5_birth_cert',
                    'group_title' => 'Birth Certificate',
                    'requirement_name' => 'Xerox copy of Birth Certificate/s of the child or children',
                    'is_or_group' => false,
                    'order_num' => 1,
                ],
                [
                    'group_key' => 'a5_marriage_cert',
                    'group_title' => 'Marriage Certificate',
                    'requirement_name' => 'Xerox copy of Marriage certificate',
                    'is_or_group' => false,
                    'order_num' => 2,
                ],
                [
                    'group_key' => 'a5_separation_proof',
                    'group_title' => 'Proof of Separation (Submit 1)',
                    'requirement_name' => 'Judicial decree of legal separation',
                    'is_or_group' => true,
                    'order_num' => 3,
                ],
                [
                    'group_key' => 'a5_separation_proof',
                    'group_title' => 'Proof of Separation (Submit 1)',
                    'requirement_name' => 'Affidavit of 2 disinterested persons attesting to the fact of de facto separation',
                    'is_or_group' => true,
                    'order_num' => 4,
                ],
                [
                    'group_key' => 'a5_affidavit_support',
                    'group_title' => 'Sworn Affidavit of Non-Cohabitation & Support',
                    'requirement_name' => 'Sworn affidavit declaring that the solo parent is not cohabiting with a partner or co-parent, and has sole parental care and support of the child or children',
                    'is_or_group' => false,
                    'order_num' => 5,
                ],
            ],
            'A6' => [
                [
                    'group_key' => 'a6_birth_cert',
                    'group_title' => 'Birth Certificate',
                    'requirement_name' => 'Xerox copy of Birth Certificate/s of the child or children',
                    'is_or_group' => false,
                    'order_num' => 1,
                ],
                [
                    'group_key' => 'a6_marriage_cert',
                    'group_title' => 'Marriage Certificate',
                    'requirement_name' => 'Xerox copy of Marriage certificate',
                    'is_or_group' => false,
                    'order_num' => 2,
                ],
                [
                    'group_key' => 'a6_nullity_or_divorce',
                    'group_title' => 'Proof of Nullity, Annulment, or Foreign Divorce (Submit 1)',
                    'requirement_name' => 'Judicial decree of nullity or annulment of marriage',
                    'is_or_group' => true,
                    'order_num' => 3,
                ],
                [
                    'group_key' => 'a6_nullity_or_divorce',
                    'group_title' => 'Proof of Nullity, Annulment, or Foreign Divorce (Submit 1)',
                    'requirement_name' => 'Judicial recognition of foreign divorce',
                    'is_or_group' => true,
                    'order_num' => 4,
                ],
                [
                    'group_key' => 'a6_affidavit_support',
                    'group_title' => 'Sworn Affidavit of Non-Cohabitation & Support',
                    'requirement_name' => 'Sworn affidavit declaring that the solo parent is not cohabiting with a partner or co-parent, and has sole parenting care and support of the child or children',
                    'is_or_group' => false,
                    'order_num' => 5,
                ],
            ],            'A7' => [
                [
                    'group_key' => 'a7_birth_cert',
                    'group_title' => 'Birth Certificate',
                    'requirement_name' => 'Xerox copy of Birth Certificate/s of the child or children',
                    'is_or_group' => false,
                    'order_num' => 1,
                ],
                [
                    'group_key' => 'a7_marriage_or_affidavit',
                    'group_title' => 'Marriage Proof or Applicant Affidavit (Submit 1)',
                    'requirement_name' => 'Xerox copy of Marriage certificate',
                    'is_or_group' => true,
                    'order_num' => 2,
                ],
                [
                    'group_key' => 'a7_marriage_or_affidavit',
                    'group_title' => 'Marriage Proof or Applicant Affidavit (Submit 1)',
                    'requirement_name' => 'Applicant affidavit',
                    'is_or_group' => true,
                    'order_num' => 3,
                ],
                [
                    'group_key' => 'a7_disinterested_persons',
                    'group_title' => 'Affidavit of 2 Disinterested Persons',
                    'requirement_name' => 'Affidavit of 2 disinterested persons attesting to the abandonment of the spouse',
                    'is_or_group' => false,
                    'order_num' => 4,
                ],
                [
                    'group_key' => 'a7_police_or_barangay_abandonment',
                    'group_title' => 'Record of Abandonment',
                    'requirement_name' => 'Police or barangay record of the fact of abandonment',
                    'is_or_group' => false,
                    'order_num' => 5,
                ],
                [
                    'group_key' => 'a7_affidavit_support',
                    'group_title' => 'Sworn Affidavit of Non-Cohabitation & Support',
                    'requirement_name' => 'Sworn affidavit declaring that the solo parent is not cohabiting with a partner or co-parent, and has sole parenting care and support of the child or children',
                    'is_or_group' => false,
                    'order_num' => 6,
                ],
            ],
            'B' => [
                [
                    'group_key' => 'b_birth_cert',
                    'group_title' => 'Birth Certificate',
                    'requirement_name' => 'Xerox copy of Birth Certificate/s of the child or children',
                    'is_or_group' => false,
                    'order_num' => 1,
                ],
                [
                    'group_key' => 'b_marriage_cert',
                    'group_title' => 'Marriage Certificate',
                    'requirement_name' => 'Xerox copy of Marriage certificate of the applicant',
                    'is_or_group' => false,
                    'order_num' => 2,
                ],
                [
                    'group_key' => 'b_oec',
                    'group_title' => 'Overseas Employment Certificate (OEC)',
                    'requirement_name' => 'Overseas Employment Certificate (OEC) or equivalent document',
                    'is_or_group' => false,
                    'order_num' => 3,
                ],
                [
                    'group_key' => 'b_passport_stamps',
                    'group_title' => 'Passport Stamps (12 Months Overseas Work)',
                    'requirement_name' => 'Copy of passport stamps showing continuous 12 months of overseas work',
                    'is_or_group' => false,
                    'order_num' => 4,
                ],
                [
                    'group_key' => 'b_affidavit_support',
                    'group_title' => 'Sworn Affidavit of Non-Cohabitation & Support',
                    'requirement_name' => 'Sworn affidavit declaring that the solo parent is not cohabiting with a partner or co-parent, and has sole parenting care and support of the child or children',
                    'is_or_group' => false,
                    'order_num' => 5,
                ],
            ],
            'C' => [
                [
                    'group_key' => 'c_birth_cert',
                    'group_title' => 'Birth Certificate',
                    'requirement_name' => 'Xerox copy of Birth Certificate/s of the child or children',
                    'is_or_group' => false,
                    'order_num' => 1,
                ],
                [
                    'group_key' => 'c_cenomar',
                    'group_title' => 'CENOMAR',
                    'requirement_name' => 'Certificate of No Marriage (CENOMAR)',
                    'is_or_group' => false,
                    'order_num' => 2,
                ],
                [
                    'group_key' => 'c_barangay_affidavit',
                    'group_title' => 'Barangay Official Affidavit',
                    'requirement_name' => 'Affidavit of a Barangay official attesting that the solo parent is a resident of the barangay and that the children are under the parental care and support of the applicant solo parent',
                    'is_or_group' => false,
                    'order_num' => 3,
                ],
                [
                    'group_key' => 'c_affidavit_support',
                    'group_title' => 'Sworn Affidavit of Non-Cohabitation & Support',
                    'requirement_name' => 'Sworn affidavit declaring that the solo parent is not cohabiting with a partner or co-parent, and has sole parenting care and support of the child or children',
                    'is_or_group' => false,
                    'order_num' => 4,
                ],
            ],
            'D' => [
                [
                    'group_key' => 'd_birth_cert',
                    'group_title' => 'Birth Certificate',
                    'requirement_name' => 'Xerox copy of Birth Certificate/s of the child or children',
                    'is_or_group' => false,
                    'order_num' => 1,
                ],
                [
                    'group_key' => 'd_guardianship_proof',
                    'group_title' => 'Proof of Guardianship, Foster Care or Adoption',
                    'requirement_name' => 'Proof of guardianship, foster care or adoption',
                    'is_or_group' => false,
                    'order_num' => 2,
                ],
                [
                    'group_key' => 'd_barangay_affidavit',
                    'group_title' => 'Barangay Official Affidavit',
                    'requirement_name' => 'Affidavit of a barangay official attesting that the solo parent is a resident of the barangay and that the children are under the parental care and support of the applicant solo parent',
                    'is_or_group' => false,
                    'order_num' => 3,
                ],
                [
                    'group_key' => 'd_affidavit_support',
                    'group_title' => 'Sworn Affidavit of Non-Cohabitation & Support',
                    'requirement_name' => 'Sworn affidavit declaring that the solo parent is not cohabiting with a partner/co-parent, and has sole parenting care and support of the child or children',
                    'is_or_group' => false,
                    'order_num' => 4,
                ],
            ],
            'E' => [
                [
                    'group_key' => 'e_birth_cert',
                    'group_title' => 'Birth Certificate',
                    'requirement_name' => 'Xerox copy of Birth Certificate/s of the child or children',
                    'is_or_group' => false,
                    'order_num' => 1,
                ],
                [
                    'group_key' => 'e_death_or_absence_proof',
                    'group_title' => 'Proof of Death or Absence of Parents/Guardian (Submit 1)',
                    'requirement_name' => 'Death certificate of the parents or legal guardian',
                    'is_or_group' => true,
                    'order_num' => 2,
                ],
                [
                    'group_key' => 'e_death_or_absence_proof',
                    'group_title' => 'Proof of Death or Absence of Parents/Guardian (Submit 1)',
                    'requirement_name' => 'Police or barangay records evidencing disappearance or absence of parents/legal guardian for at least 6 months',
                    'is_or_group' => true,
                    'order_num' => 3,
                ],
                [
                    'group_key' => 'e_barangay_affidavit',
                    'group_title' => 'Barangay Official Affidavit',
                    'requirement_name' => 'Affidavit of a barangay official attesting that the solo parent is a resident of the barangay and that the children are under the parental care and support of the applicant solo parent',
                    'is_or_group' => false,
                    'order_num' => 4,
                ],
            ],
            'F' => [
                [
                    'group_key' => 'f_barangay_affidavit',
                    'group_title' => 'Barangay Official Affidavit',
                    'requirement_name' => 'Affidavit of a barangay official attesting that the solo parent is a resident of the barangay and that the children are under the parental care and support of the applicant solo parent',
                    'is_or_group' => false,
                    'order_num' => 1,
                ],
                [
                    'group_key' => 'f_affidavit_support',
                    'group_title' => 'Sworn Affidavit of Non-Cohabitation & Support',
                    'requirement_name' => 'Sworn affidavit declaring that the solo parent is not cohabiting with a partner or co-parent, and has sole parenting care and support of the child or children',
                    'is_or_group' => false,
                    'order_num' => 2,
                ],
            ],
        ];
    }
    public static function getLegacyRequirements(): array
    {
        return [
            'PSA Birth Certificate of Child/Children',
            'Barangay Certificate (stating you are a solo parent)',
            'Valid Government-Issued ID',
            'CENOMAR or PSA Marriage Certificate',
            'Death Certificate of Spouse (if widowed) / Police Report (if abandoned)',
            '2x2 ID Photo (recent, white background)',
        ];
    }

    public static function getMasterRequirements(string $categoryCode): array
    {
        $code = strtoupper(trim($categoryCode));

        try {
            if (Schema::hasTable('solo_parent_category_requirements')) {
                $dbItems = SoloParentCategoryRequirement::where('category_code', $code)
                    ->where('is_active', true)
                    ->orderBy('order_num')
                    ->get();

                if ($dbItems->isNotEmpty()) {
                    return $dbItems->map(fn($item) => [
                        'id'               => $item->id,
                        'category_code'    => $item->category_code,
                        'group_key'        => $item->group_key,
                        'group_title'      => $item->group_title,
                        'requirement_name' => $item->requirement_name,
                        'description'      => $item->description,
                        'is_or_group'      => (bool) $item->is_or_group,
                        'order_num'        => $item->order_num,
                    ])->toArray();
                }
            }
        } catch (\Exception $e) {
            Log::warning('SoloParentCategoryService: Unable to read master requirements: ' . $e->getMessage());
        }

                if ($code === self::BENEFIT_CODE_1_2) {
            $benefitDefs = self::getBenefitDefinitions();
            return $benefitDefs[self::BENEFIT_CODE_1_2] ?? [];
        }

        $defs = self::getDefaultDefinitions();
        return $defs[$code] ?? [];
    }

    public static function seedDefaultMasterRequirementsIfNeeded(): void
    {
        try {
            if (Schema::hasTable('solo_parent_category_requirements')) {
                $allDefs = self::getDefaultDefinitions();
                foreach ($allDefs as $catCode => $items) {
                    foreach ($items as $item) {
                        SoloParentCategoryRequirement::firstOrCreate(
                            [
                                'category_code'    => $catCode,
                                'group_key'        => $item['group_key'],
                                'requirement_name' => $item['requirement_name'],
                            ],
                            [
                                'group_title'      => $item['group_title'],
                                'description'      => $item['description'] ?? null,
                                'is_or_group'      => $item['is_or_group'] ?? false,
                                'is_active'        => true,
                                'order_num'        => $item['order_num'] ?? 0,
                            ]
                        );
                    }
                }

                $benefitDefs = self::getBenefitDefinitions();
                foreach ($benefitDefs as $bCode => $bItems) {
                    foreach ($bItems as $bItem) {
                        SoloParentCategoryRequirement::firstOrCreate(
                            [
                                'category_code'    => $bCode,
                                'group_key'        => $bItem['group_key'],
                                'requirement_name' => $bItem['requirement_name'],
                            ],
                            [
                                'group_title'      => $bItem['group_title'],
                                'description'      => $bItem['description'] ?? null,
                                'is_or_group'      => false,
                                'is_active'        => true,
                                'order_num'        => $bItem['order_num'] ?? 0,
                            ]
                        );
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('SoloParentCategoryService: Could not seed master definitions: ' . $e->getMessage());
        }
    }

    public static function snapshotRequirementsForApplication(int $applicationId, string $categoryCode, ?string $benefitCode = null): void
    {
        $code = strtoupper(trim($categoryCode));
        self::seedDefaultMasterRequirementsIfNeeded();
        $masterItems = self::getMasterRequirements($code);

        try {
            if (Schema::hasTable('solo_parent_application_requirements')) {
                $existingCount = SoloParentApplicationRequirement::where('application_id', $applicationId)->count();
                if ($existingCount > 0) {
                    return;
                }

                foreach ($masterItems as $item) {
                    SoloParentApplicationRequirement::create([
                        'application_id'   => $applicationId,
                        'category_code'    => $code,
                        'group_key'        => $item['group_key'],
                        'group_title'      => $item['group_title'],
                        'requirement_name' => $item['requirement_name'],
                        'is_or_group'      => $item['is_or_group'] ?? false,
                        'order_num'        => $item['order_num'] ?? 0,
                    ]);
                }

                if ($benefitCode && strtoupper(trim($benefitCode)) === self::BENEFIT_CODE_1_2) {
                    $benefitItems = self::getMasterRequirements(self::BENEFIT_CODE_1_2);
                    foreach ($benefitItems as $bItem) {
                        SoloParentApplicationRequirement::create([
                            'application_id'   => $applicationId,
                            'category_code'    => self::BENEFIT_CODE_1_2,
                            'group_key'        => $bItem['group_key'],
                            'group_title'      => $bItem['group_title'],
                            'requirement_name' => $bItem['requirement_name'],
                            'is_or_group'      => false,
                            'order_num'        => $bItem['order_num'] ?? 0,
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('SoloParentCategoryService: Failed to snapshot requirements: ' . $e->getMessage());
        }
    }

    public static function getRequirementsForApplication(?Application $application): array
    {
        if (!$application) {
            return [];
        }

        $catCode = $application->category_code ? strtoupper(trim($application->category_code)) : null;

        try {
            if (Schema::hasTable('solo_parent_application_requirements')) {
                $snapshots = SoloParentApplicationRequirement::where('application_id', $application->id)
                    ->orderBy('order_num')
                    ->get();

                if ($snapshots->isNotEmpty()) {
                    return self::formatRequirementsHierarchy($snapshots->toArray());
                }
            }
        } catch (\Exception $e) {
            Log::warning('SoloParentCategoryService: Failed reading snapshot: ' . $e->getMessage());
        }

        if ($catCode && array_key_exists($catCode, self::getCategories())) {
            self::snapshotRequirementsForApplication($application->id, $catCode);
            $master = self::getMasterRequirements($catCode);
            return self::formatRequirementsHierarchy($master);
        }

        $legacy = self::getLegacyRequirements();
        $items = [];
        foreach ($legacy as $idx => $name) {
            $items[] = [
                'group_key'        => 'legacy_' . $idx,
                'group_title'      => $name,
                'requirement_name' => $name,
                'is_or_group'      => false,
                'order_num'        => $idx + 1,
            ];
        }
        return self::formatRequirementsHierarchy($items);
    }

    public static function formatRequirementsHierarchy(array $items): array
    {
        $groups = [];
        foreach ($items as $item) {
            $gKey = $item['group_key'];
            if (!isset($groups[$gKey])) {
                $groups[$gKey] = [
                    'group_key'   => $gKey,
                    'group_title' => $item['group_title'],
                    'is_or_group' => (bool) ($item['is_or_group'] ?? false),
                    'options'     => [],
                ];
            }
            $groups[$gKey]['options'][] = [
                'requirement_name' => $item['requirement_name'],
                'description'      => $item['description'] ?? null,
            ];
        }
        return array_values($groups);
    }

    public static function evaluateCompletion(Application $application, ?FileMonitoring $fileMonitoring): array
    {
        if (!$fileMonitoring) {
            return [
                'is_complete'      => false,
                'has_rejected'     => false,
                'total_groups'     => 0,
                'satisfied_groups' => 0,
                'groups'           => [],
            ];
        }

        $uploads = $fileMonitoring->fileUploads ?? collect();
        $uploadedByName = $uploads->keyBy('requirement_name');

        $hasAnyRejected = $uploads->where('status', 'rejected')->count() > 0;

        $groups = self::getRequirementsForApplication($application);
        if (empty($groups)) {
            $total = $uploads->count();
            $approved = $uploads->where('status', 'approved')->count();
            return [
                'is_complete'      => ($total > 0 && $approved === $total && !$hasAnyRejected),
                'has_rejected'     => $hasAnyRejected,
                'total_groups'     => $total,
                'satisfied_groups' => $approved,
                'groups'           => [],
            ];
        }

        $satisfiedCount = 0;
        $evaluatedGroups = [];
        // Track whether any rejection is BLOCKING (i.e., not overridden by a satisfying OR alternative)
        $hasBlockingRejection = false;

        foreach ($groups as $group) {
            $isSatisfied = false;
            $satisfiedBy = null;
            $groupHasRejected = false;
            $optionsStatus = [];

            foreach ($group['options'] as $opt) {
                $reqName = $opt['requirement_name'];
                $upload = $uploadedByName->get($reqName);
                $st = $upload ? $upload->status : 'not_uploaded';

                if ($st === 'rejected') {
                    $groupHasRejected = true;
                }
                if ($st === 'approved' && !$isSatisfied) {
                    $isSatisfied = true;
                    $satisfiedBy = $upload;
                }

                $optionsStatus[] = [
                    'requirement_name' => $reqName,
                    'status'           => $st,
                    'file_upload'      => $upload,
                ];
            }

            if ($isSatisfied) {
                $satisfiedCount++;
                // This group is satisfied. Any rejections in this group are overridden
                // by the approved alternative — they do NOT block completion.
            } else {
                // Group is NOT satisfied.
                if ($groupHasRejected) {
                    // There is a rejected file and no approved alternative in this group.
                    // This is a blocking rejection.
                    $hasBlockingRejection = true;
                }
                // If group has no uploads at all, it is also unsatisfied (handled by satisfiedCount check).
            }

            $evaluatedGroups[] = [
                'group_key'     => $group['group_key'],
                'group_title'   => $group['group_title'],
                'is_or_group'   => $group['is_or_group'],
                'is_satisfied'  => $isSatisfied,
                'satisfied_by'  => $satisfiedBy,
                'has_rejected'  => $groupHasRejected,
                'options'       => $optionsStatus,
            ];
        }

        $totalGroups = count($groups);
        // Complete when: all slots are satisfied AND no blocking rejection exists.
        // A rejected file in a satisfied OR group does NOT block completion.
        $isComplete = ($totalGroups > 0 && $satisfiedCount === $totalGroups && !$hasBlockingRejection);

        return [
            'is_complete'      => $isComplete,
            'has_rejected'     => $hasAnyRejected,
            'total_groups'     => $totalGroups,
            'satisfied_groups' => $satisfiedCount,
            'groups'           => $evaluatedGroups,
        ];
    }

    public static function getCategoryPreviewMap(): array
    {
        $categories = self::getCategories();
        $map = [];
        foreach (array_keys($categories) as $code) {
            $items = self::getMasterRequirements($code);
            $grouped = self::formatRequirementsHierarchy($items);
            $displayItems = [];

            foreach ($grouped as $g) {
                if ($g['is_or_group']) {
                    $optNames = array_column($g['options'], 'requirement_name');
                    $displayItems[] = [
                        'title'       => $g['group_title'],
                        'is_or_group' => true,
                        'options'     => $optNames,
                    ];
                } else {
                    $displayItems[] = [
                        'title'       => $g['options'][0]['requirement_name'],
                        'is_or_group' => false,
                        'options'     => [],
                    ];
                }
            }
            $map[$code] = [
                'code'         => $code,
                'title'        => $categories[$code],
                'requirements' => $displayItems,
            ];
        }
        return $map;
    }

    public static function getCategorizedRequirementsForApplication(?Application $application): array
    {
        if (!$application) {
            return ['primary' => [], 'benefit' => []];
        }

        try {
            if (Schema::hasTable('solo_parent_application_requirements')) {
                $snapshots = SoloParentApplicationRequirement::where('application_id', $application->id)
                    ->orderBy('order_num')
                    ->get();

                if ($snapshots->isNotEmpty()) {
                    $primaryRows = $snapshots->where('category_code', '!=', self::BENEFIT_CODE_1_2)->values()->toArray();
                    $benefitRows = $snapshots->where('category_code', self::BENEFIT_CODE_1_2)->values()->toArray();

                    return [
                        'primary' => self::formatRequirementsHierarchy($primaryRows),
                        'benefit' => self::formatRequirementsHierarchy($benefitRows),
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('SoloParentCategoryService: Failed reading categorized snapshot: ' . $e->getMessage());
        }

        $all = self::getRequirementsForApplication($application);
        return [
            'primary' => $all,
            'benefit' => [],
        ];
    }

    public static function hasBenefitSnapshot(int $applicationId, string $benefitCode = self::BENEFIT_CODE_1_2): bool
    {
        try {
            if (Schema::hasTable('solo_parent_application_requirements')) {
                return SoloParentApplicationRequirement::where('application_id', $applicationId)
                    ->where('category_code', $benefitCode)
                    ->exists();
            }
        } catch (\Exception $e) {
            return false;
        }
        return false;
    }

    public static function getBenefitPreviewList(string $benefitCode = self::BENEFIT_CODE_1_2): array
    {
        $items = self::getMasterRequirements($benefitCode);
        $grouped = self::formatRequirementsHierarchy($items);
        $displayItems = [];
        foreach ($grouped as $g) {
            $displayItems[] = [
                'title'       => $g['options'][0]['requirement_name'] ?? $g['group_title'],
                'is_or_group' => false,
                'options'     => [],
            ];
        }
        return [
            'code'         => $benefitCode,
            'title'        => self::BENEFIT_TITLE_1_2,
            'requirements' => $displayItems,
        ];
    }
}