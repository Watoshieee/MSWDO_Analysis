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
}
