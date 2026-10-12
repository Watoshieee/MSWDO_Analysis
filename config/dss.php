<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Decision Support System (DSS) & Weighted Scoring Model (WSM) Configuration
    |--------------------------------------------------------------------------
    |
    | Transparent criteria and weights used by the MSWDO Decision Support System
    | to assess vulnerability, program prioritization, and resource allocation.
    | All weights must sum to 1.00 (100%).
    |
    */

    'wsm_weights' => [
        'pwd' => [
            'key' => 'pwd',
            'label' => 'PWD (Persons with Disability)',
            'weight' => 0.15,
            'source_field' => 'pwd_count',
            'description' => 'Prevalence of persons with disability requiring specialized welfare assistance.',
        ],
        'aics' => [
            'key' => 'aics',
            'label' => 'AICS (Assistance to Individuals in Crisis Situations)',
            'weight' => 0.20,
            'source_field' => 'aics_count',
            'description' => 'Incidence of urgent crisis assistance beneficiaries.',
        ],
        'solo_parent' => [
            'key' => 'solo_parent',
            'label' => 'Solo Parent',
            'weight' => 0.15,
            'source_field' => 'single_parent_count',
            'description' => 'Count of registered solo parent heads of households.',
        ],
        'four_ps' => [
            'key' => 'four_ps',
            'label' => '4Ps Beneficiaries',
            'weight' => 0.15,
            'source_field' => 'four_ps_count',
            'description' => 'Households enrolled in Pantawid Pamilyang Pilipino Program.',
        ],
        'senior' => [
            'key' => 'senior',
            'label' => 'Senior Citizens',
            'weight' => 0.15,
            'source_field' => 'senior_count',
            'description' => 'Elderly residents requiring social pension and health support.',
        ],
        'population_concentration' => [
            'key' => 'population_concentration',
            'label' => 'Barangay Population Concentration',
            'weight' => 0.10,
            'source_field' => 'total_population',
            'description' => 'Proportion of municipality population residing within the barangay.',
        ],
        'beneficiary_concentration' => [
            'key' => 'beneficiary_concentration',
            'label' => 'Overall Beneficiary Concentration',
            'weight' => 0.10,
            'source_field' => 'total_beneficiaries',
            'description' => 'Proportion of all social welfare beneficiaries concentrated in the barangay.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Analytical Terminology & Disclaimer
    |--------------------------------------------------------------------------
    | Neutral analytical terms ensuring DSS outputs remain supportive rather
    | than prescriptive or replacing professional MSWDO assessment.
    */
    'terminology' => [
        'high_priority' => 'Higher calculated priority score',
        'moderate_priority' => 'Moderate calculated priority score',
        'low_priority' => 'Lower calculated priority score',
        'indicator' => 'Priority indicator',
        'score_label' => 'Calculated WSM score',
    ],

    'disclaimer' => 'The DSS/WSM result is a decision-support indicator calculated from the available MSWDO data and configured criteria. It does not replace professional assessment, field validation, or official MSWDO decision-making.',

    'methodology_formula' => "Weighted Score = Normalized Score × Criterion Weight\nFinal WSM Score = Σ (Weighted Scores)\nNormalization Method: Linear Max Normalization [Normalized Score = Raw Value / Max(Raw Value in Municipality)] (Range: 0.0000 - 1.0000)",
];
