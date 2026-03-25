<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileMonitoring extends Model
{
    protected $table = 'file_monitoring';
    
    protected $fillable = [
        'application_id',
        'user_id',  // ADD THIS
        'assigned_admin_id',
        'priority',
        'overall_status',
        'notes',
        'municipality'  // MAKE SURE THIS IS HERE
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
    
    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }
    
    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class);
    }
    
    public function statusLogs()
    {
        return $this->hasMany(FileStatusLog::class);
    }
    
    public function updateOverallStatus()
    {
        $totalFiles = $this->fileUploads()->count();
        $approvedFiles = $this->fileUploads()->where('status', 'approved')->count();
        $rejectedFiles = $this->fileUploads()->where('status', 'rejected')->count();
        
        if ($totalFiles == 0) {
            $this->overall_status = 'pending';
        } elseif ($rejectedFiles > 0) {
            $this->overall_status = 'rejected';
        } elseif ($approvedFiles == $totalFiles) {
            $this->overall_status = 'approved';
        } else {
            $this->overall_status = 'in_review';
        }
        
        $this->save();
        
        // Also update application stage if all files approved
        if ($this->overall_status == 'approved') {
            $this->application->update(['stage' => 'completed', 'completed_at' => now()]);
        }
    }
}