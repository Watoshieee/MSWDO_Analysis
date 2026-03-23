<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;

    protected $table = 'barangays';
    
    public $timestamps = false;

    protected $fillable = [
        'municipality',
        'name',
        'male_population',
        'female_population',
        'population_0_19',
        'population_20_59',
        'population_60_100',
        'single_parent_count',
        'total_households',
        'total_approved_applications',
        'year'
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality', 'name');
    }
}