<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
// use Illuminate\Database\Eloquent\Attributes\Fillable;
// use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    // Add role constants
    const ROLE_ADMIN    = 'admin';
    const ROLE_VENDOR   = 'vendor';
    const ROLE_CUSTOMER = 'customer';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',    // if need to change value
    ];

    // Hidden attributes
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cast attributes
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',           
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Required method 1 - defines what goes into the subject claim (usually the user ID)
    public function getJWTIdentifier()
    {
        return $this->getKey(); // usually id
    }

    // Required method 2 - add extra payload data into JWT
    public function getJWTCustomClaims()
    {
        return [];
    }

    // Helper functions
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isVendor()
    {
        return $this->role === self::ROLE_VENDOR;
    }

    public function isCustomer()
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    // Relationships
    public function addresses() {
        return $this->hasMany(UserAddress::class);
    }

    public function defaultShippingAddress() {
        return $this->hasOne(UserAddress::class)->where('type','shipping')->where('is_default',true);
    }

    public function defaultBillingAddress() {
        return $this->hasOne(UserAddress::class)->where('type','billing')->where('is_default',true);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function vendorProfile()
    {
        return $this->hasOne(Vendor::class, 'user_id');
    }
}
