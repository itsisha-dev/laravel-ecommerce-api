<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $fillable = ['session_id','user_id'];

    protected $casts = [        
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotal()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }
}
