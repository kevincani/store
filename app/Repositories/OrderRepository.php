<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    public const MODEL = Order::class;
    public function allOrdersWithDetails(){
        return Order::with('orderDetails')->get();
    }
    public function orderWithDetails($id){
        return Order::with('orderDetails')->find($id);
    }
    public function allUsersOrdersWithDetails(){
        $user = auth()->user();
        return Order::where('user_id',$user->id)->with('orderDetails')->get();
    }
}
