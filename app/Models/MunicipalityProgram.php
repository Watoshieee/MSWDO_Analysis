<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MunicipalityProgram extends Model
{
    protected $table = 'municipality_programs';

    protected $fillable = [
        'municipality_name',
        'program_key',
        'program_label',
        'description',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get active programs for a specific municipality, ordered by display_order.
     */
    public static function forMunicipality(string $municipalityName): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('municipality_name', $municipalityName)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();
    }

    /**
     * Check if a specific program is available for a municipality.
     */
    public static function isAvailable(string $municipalityName, string $programKey): bool
    {
        return static::where('municipality_name', $municipalityName)
            ->where('program_key', $programKey)
            ->where('is_active', true)
            ->exists();
    }
}
