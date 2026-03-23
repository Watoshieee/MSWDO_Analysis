<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialWelfareProgram extends Model
{
    use HasFactory;

    protected $table = 'social_welfare_programs';
    
    public $timestamps = false;

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