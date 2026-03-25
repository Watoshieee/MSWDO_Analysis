<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramRequirement extends Model
{
    protected $table = 'program_requirements';
    
    public $timestamps = true;
    
    protected $fillable = [
        'program_type',
        'requirement_name'
    ];
    
    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class, 'requirement_name', 'requirement_name')
            ->where('requirement_name', $this->requirement_name);
    }
}