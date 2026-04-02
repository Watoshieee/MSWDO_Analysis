<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipality extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'municipalities';
    
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'total_households',
        'male_population',
        'female_population',
        'population_0_19',
        'population_20_59',
        'population_60_100',
        'single_parent_count',
        'year',
        'created_at'
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    public function barangays()
    {
        return $this->hasMany(Barangay::class, 'municipality', 'name');
    }

    public function socialWelfarePrograms()
    {
        return $this->hasMany(SocialWelfareProgram::class, 'municipality', 'name');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'municipality', 'name');
    }

    public function getTotalPopulationAttribute()
    {
        return $this->male_population + $this->female_population;
    }
}