<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    public const MODEL = Order::class;
    public function allOrdersWithDetails(){
        return Order::with('orderDetails')->get();
    }
}
