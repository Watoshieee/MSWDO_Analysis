<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    protected $fillable = ['user_id', 'setting_key', 'setting_value'];

    public static function get($key, $default = null, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        $setting = self::where('user_id', $userId)->where('setting_key', $key)->first();
        return $setting ? $setting->setting_value : $default;
    }

    public static function set($key, $value, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        return self::updateOrCreate(
            ['user_id' => $userId, 'setting_key' => $key],
            ['setting_value' => $value]
        );
    }

    public static function getByMunicipality($key, $municipality, $default = null)
    {
        // Get the latest color setting from any admin in the same municipality
        $setting = self::whereHas('user', function($query) use ($municipality) {
            $query->where('municipality', $municipality);
        })->where('setting_key', $key)
          ->orderBy('updated_at', 'desc')
          ->first();
        
        return $setting ? $setting->setting_value : $default;
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
