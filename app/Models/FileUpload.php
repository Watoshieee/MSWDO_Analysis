<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $table = 'file_uploads';
    
    public $timestamps = false;
    
    protected $fillable = [
        'file_monitoring_id',
        'user_id',  // ADD THIS
        'file_name',
        'file_path',
        'requirement_name',
        'status',
        'remarks',
        'admin_remarks',  // ADD THIS
        'verified_at',  // ADD THIS
        'verified_by',  // ADD THIS
        'uploaded_at',
        'municipality'
    ];
    
    protected $casts = [
        'uploaded_at' => 'datetime'
    ];
    
    public function fileMonitoring()
    {
        return $this->belongsTo(FileMonitoring::class);
    }
    
    public function statusLogs()
    {
        return $this->hasMany(FileStatusLog::class, 'file_monitoring_id', 'file_monitoring_id')
            ->where('requirement_name', $this->requirement_name);
    }
}