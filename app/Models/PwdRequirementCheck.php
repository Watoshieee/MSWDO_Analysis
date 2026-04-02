<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PwdRequirementCheck extends Model
{
    protected $table = 'pwd_requirement_checks';

    protected $fillable = [
        'application_id',
        'requirement_key',
        'requirement_label',
        'status',
        'file_path',
        'admin_notes',
        'checked_by',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
