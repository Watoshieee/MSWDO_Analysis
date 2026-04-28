<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    // timestamps disabled — table has no created_at/updated_at (but does have deleted_at)
    public $timestamps = false;

    // Define the table name
    protected $table = 'applications';

    // Define the primary key
    protected $primaryKey = 'id';

    // Define which attributes are mass assignable
    protected $fillable = [
        'user_id',
        'program_type',
        'municipality',
        'barangay',
        'full_name',
        'age',
        'gender',
        'contact_number',
        'status',
        'application_date',
        'year',
        'form_data',
        'stage',
        'completed_at',
        'proof_photo_path',
        'id_status',
        'id_ready_at'
    ];

    protected $casts = [
        'form_data'        => 'array',
        'application_date' => 'datetime',
        'completed_at'     => 'datetime',
        'deleted_at'       => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class , 'municipality', 'name');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class , 'barangay', 'name');
    }

    public function fileMonitoring()
    {
        return $this->hasOne(FileMonitoring::class);
    }

    public function pwdRequirementChecks()
    {
        return $this->hasMany(PwdRequirementCheck::class, 'application_id');
    }
}