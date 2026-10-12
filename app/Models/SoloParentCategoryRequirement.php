<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoloParentCategoryRequirement extends Model
{
    protected $table = 'solo_parent_category_requirements';

    protected $fillable = [
        'category_code',
        'group_key',
        'group_title',
        'requirement_name',
        'description',
        'is_or_group',
        'is_active',
        'order_num',
    ];

    protected $casts = [
        'is_or_group' => 'boolean',
        'is_active'   => 'boolean',
        'order_num'   => 'integer',
    ];

    protected $appends = [
        'sort_order',
    ];

    public function getSortOrderAttribute()
    {
        return $this->order_num;
    }

    public function setSortOrderAttribute($value)
    {
        $this->attributes['order_num'] = $value;
    }
}