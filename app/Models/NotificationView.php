<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationView extends Model
{
    protected $fillable = ['user_id', 'last_viewed_at'];
    
    protected $casts = [
        'last_viewed_at' => 'datetime'
    ];
}
