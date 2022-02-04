<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'carts')->withPivot('quantity', 'price')->withTimestamps();;
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')->withPivot(['quantity', 'price'])->withTimestamps();
    }
}
