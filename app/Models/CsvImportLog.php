<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CsvImportLog extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'file_name',
        'file_type',
        'total_rows',
        'successful_rows',
        'failed_rows',
        'error_details',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
