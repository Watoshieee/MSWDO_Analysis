<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileStatusLog extends Model
{
    protected $table = 'file_status_logs';
    
    public $timestamps = false;
    
    protected $fillable = [
        'file_monitoring_id',
        'user_id',  // ADD THIS
        'changed_by',
        'old_status',
        'new_status',
        'remarks',
        'requirement_name',
        'municipality'
    ];
    
    public function fileMonitoring()
    {
        return $this->belongsTo(FileMonitoring::class);
    }
    
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}