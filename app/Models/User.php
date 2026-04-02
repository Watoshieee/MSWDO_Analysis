<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

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
        'birthdate',
        'age',
        'role',
        'municipality',
        'barangay',
        'mobile_number',
        'status',
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

    public function generateOtp()
    {
        $otp = rand(100000, 999999);
        $this->otp_code = $otp;
        $this->otp_expires_at = now()->addMinutes(10);
        $this->save();
        return $otp;
    }

    public function verifyOtp($otp)
    {
        if ($this->otp_code == $otp && $this->otp_expires_at > now()) {
            $this->email_verified_at = now();
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
        $this->reset_token_expires_at = now()->addMinutes(30);
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
}