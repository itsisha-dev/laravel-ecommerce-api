<?php

namespace App\Models;

//use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasSlug;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = ['vendor_id','category_id','name','slug','description','price','stock','is_active'];

    protected $casts = [   
        'is_active' => 'boolean',        
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
