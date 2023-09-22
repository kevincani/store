<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'payment_intent', 'total_price', 'session_id' ];

//    public function orderDetails(): HasMany
//    {
//        return $this->hasMany(OrderDetail::class);
//    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
