<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialWelfareProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'social_welfare_programs';
    
    public $timestamps = true;
    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'municipality',
        'barangay',
        'program_type',
        'beneficiary_count',
        'year'
    ];

    protected $casts = [
        'year' => 'integer',
        'beneficiary_count' => 'integer',
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality', 'name');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay', 'name');
    }
}