<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MunicipalityYearlySummary extends Model
{
    use HasFactory;

    protected $table = 'municipality_yearly_summary';
    
    public $timestamps = false;

    protected $fillable = [
        'municipality',
        'year',
        'total_population',
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