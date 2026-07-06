<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'username',
        'password',
        'email',
        'full_name',
        'first_name',
        'last_name',
        'middle_name',
        'birthdate',
        'date_of_birth',
        'age',
        'gender',
        'role',
        'municipality',
        'barangay',
        'mobile_number',
        'phone_number',
        'address',
        'status',
        'valid_id_path',
        'valid_id_filename',
        'id_verification_status',
        'id_verified_at',
        'id_verified_by',
        'id_rejection_reason',
        'must_change_password',
        'email_verified_at',
        'otp_code',
        'otp_expires_at',
        'reset_token',
        'reset_token_expires_at',
        'archived_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
        'reset_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'reset_token_expires_at' => 'datetime',
        'password' => 'hashed',
        'must_change_password' => 'boolean',
        'id_verified_at' => 'datetime',
    ];

    const ID_STATUS_PENDING = 'pending';
    const ID_STATUS_APPROVED = 'approved';
    const ID_STATUS_REJECTED = 'rejected';

    const ID_DECLINE_REASONS = [
        'blurry_id' => 'Malabo ang picture ng valid ID.',
        'unreadable_id' => 'Hindi mabasa ang valid ID.',
        'wrong_municipality' => 'Hindi taga sa municipality na ito ang user.',
    ];

    // Role constants
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    // Role checks
    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    // Get all available roles
    public static function getRoles()
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_USER => 'User',
        ];
    }

    // Get municipalities for admin
    public static function getMunicipalities()
    {
        return ['Magdalena', 'Liliw', 'Majayjay'];
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'user_id');
    }

    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    public function isIdVerificationPending(): bool
    {
        return $this->id_verification_status === self::ID_STATUS_PENDING;
    }

    public function isIdVerificationApproved(): bool
    {
        return $this->id_verification_status === null
            || $this->id_verification_status === self::ID_STATUS_APPROVED;
    }

    public function isIdVerificationRejected(): bool
    {
        return $this->id_verification_status === self::ID_STATUS_REJECTED;
    }

    public function canLoginAsUser(): bool
    {
        return $this->isIdVerificationApproved() && $this->status === 'active';
    }

    public function generateOtp()
    {
        $otp = rand(100000, 999999);
        $this->otp_code = $otp;
        $this->otp_expires_at = Carbon::now()->addMinutes(10);
        $this->save();
        return $otp;
    }

    public function verifyOtp($otp)
    {
        if ($this->otp_code == $otp && $this->otp_expires_at > now()) {
            $this->email_verified_at = Carbon::now();
            $this->otp_code = null;
            $this->otp_expires_at = null;
            $this->save();
            return true;
        }
        return false;
    }

    // PASSWORD RESET METHODS
    public function generateResetToken()
    {
        $token = Str::random(60);
        $this->reset_token = $token;
        $this->reset_token_expires_at = Carbon::now()->addMinutes(30);
        $this->save();
        return $token;
    }

    public function verifyResetToken($token)
    {
        return $this->reset_token === $token && $this->reset_token_expires_at > now();
    }

    public function clearResetToken()
    {
        $this->reset_token = null;
        $this->reset_token_expires_at = null;
        $this->save();
    }

    // Accessor for full_name (handles both old and new structure)
    public function getFullNameAttribute($value)
    {
        // If full_name exists in database, return it
        if ($value) {
            return $value;
        }
        
        // Otherwise, construct from first_name, middle_name, last_name
        $parts = array_filter([
            $this->attributes['first_name'] ?? null,
            $this->attributes['middle_name'] ?? null,
            $this->attributes['last_name'] ?? null,
        ]);
        
        return implode(' ', $parts);
    }

    // Accessor for phone_number (handles both mobile_number and phone_number)
    public function getPhoneNumberAttribute($value)
    {
        return $value ?? ($this->attributes['mobile_number'] ?? null);
    }

    // Accessor for date_of_birth (handles both birthdate and date_of_birth)
    public function getDateOfBirthAttribute($value)
    {
        return $value ?? ($this->attributes['birthdate'] ?? null);
    }
}