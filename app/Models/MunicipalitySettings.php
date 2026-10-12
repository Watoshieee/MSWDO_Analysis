<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MunicipalitySettings extends Model
{
    protected $table = 'municipality_settings';

    protected $fillable = [
        'municipality_name',
        'display_name',
        'logo_path',
        'contact_email',
        'contact_phone',
        'office_address',
        'office_hours',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get settings for a specific municipality.
     */
    public static function forMunicipality(string $municipalityName): ?self
    {
        return static::where('municipality_name', $municipalityName)
            ->where('is_active', true)
            ->first();
    }
}
