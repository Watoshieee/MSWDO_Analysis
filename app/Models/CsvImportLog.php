<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsvImportLog extends Model
{
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
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
