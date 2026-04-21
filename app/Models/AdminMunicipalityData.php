<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminMunicipalityData extends Model
{
    protected $table = 'admin_municipality_data';
    protected $fillable = [
        'municipality',
        'year',
        'total_population',
        'total_households',
    ];
}
