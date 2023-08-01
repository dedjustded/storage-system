<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'unit_price', 'availability'];
    
    public function getTotalPriceAttribute()
    {
        return $this->unit_price * $this->availability;
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}