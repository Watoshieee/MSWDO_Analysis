<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MunicipalityMonthlySummary extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'municipality_monthly_summary';

    protected $fillable = [
        'municipality',
        'year',
        'month',
        'total_pwd',
        'total_aics',
        'total_solo_parent',
        'notes',
    ];

    protected $casts = [
        'year'              => 'integer',
        'month'             => 'integer',
        'total_pwd'         => 'integer',
        'total_aics'        => 'integer',
        'total_solo_parent' => 'integer',
    ];

    // Helper: month name
    public function getMonthNameAttribute(): string
    {
        return \Carbon\Carbon::createFromDate(null, $this->month, 1)->format('F');
    }

    public function municipality(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Municipality::class, 'municipality', 'name');
    }
}
