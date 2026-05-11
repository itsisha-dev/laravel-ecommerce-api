<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','fullname','type','label','address',
        'city','state','country','postal_code','phone','is_default'
    ];

    protected $casts = [    
        'is_default' => 'boolean',      
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
