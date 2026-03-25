<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'program_type',
        'municipality',
        'created_by',
        'is_active'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'boolean'
    ];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}