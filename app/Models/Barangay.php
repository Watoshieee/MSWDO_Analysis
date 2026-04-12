<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barangay extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barangays';

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'municipality',
        'name',
        'male_population',
        'female_population',
        'population_0_19',
        'population_20_59',
        'population_60_100',
        'single_parent_count',
        'pwd_count',
        'aics_count',
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