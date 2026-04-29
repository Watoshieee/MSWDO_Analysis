<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MunicipalityVision extends Model
{
    use HasFactory;

    protected $table = 'municipality_visions';

    protected $fillable = [
        'municipality_name',
        'vision',
        'mission',
        'goals',
        'strategic_goals',
    ];

    protected $casts = [
        'strategic_goals' => 'array',
    ];
}
