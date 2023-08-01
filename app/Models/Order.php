<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['product_id', 'quantity', 'unit_price', 'total_price', 'imei', 'status', 'customer_name', 'customer', 'finished_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('imei', 'unit_price')
                    ->withTimestamps();
    }
    
}