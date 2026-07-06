<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationDevice extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'device_fingerprint',
        'user_id',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
