<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MunicipalityYearlySummary extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'municipality_yearly_summary';
    
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'municipality',
        'year',
        'total_population',
        'population_0_19',
        'population_20_59',
        'population_60_100',
        'total_households',
        'total_4ps',
        'total_pwd',
        'total_senior',
        'total_aics',
        'total_esa',
        'total_slp',
        'total_solo_parent',
        'created_at'
    ];

    protected $casts = [
        'year' => 'integer',
        'total_population' => 'integer',
        'population_0_19' => 'integer',
        'population_20_59' => 'integer',
        'population_60_100' => 'integer',
        'total_households' => 'integer',
        'total_4ps' => 'integer',
        'total_pwd' => 'integer',
        'total_senior' => 'integer',
        'total_aics' => 'integer',
        'total_esa' => 'integer',
        'total_slp' => 'integer',
        'total_solo_parent' => 'integer',
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality', 'name');
    }
}