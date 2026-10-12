<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoloParentApplicationRequirement extends Model
{
    protected $table = 'solo_parent_application_requirements';

    protected $fillable = [
        'application_id',
        'category_code',
        'group_key',
        'group_title',
        'requirement_name',
        'is_or_group',
        'order_num',
    ];

    protected $casts = [
        'is_or_group' => 'boolean',
        'order_num'   => 'integer',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}