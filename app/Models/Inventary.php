<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Inventary extends Model
{
    use HasFactory;
    protected $table = 'inventary';
    protected $fillable = ['id','quantity','price','product_id','category_id','size_id','color_id','discount_id'];

    public function categories()
    {
        return $this->hasOne(Category::class, 'id','category_id');
    }

    public function sizes()
    {
        return $this->hasOne(Size::class, 'id','size_id');
    }

    public function colors()
    {
        return $this->hasOne(Color::class, 'id','color_id');
    }
    public function discounts()
    {
        return $this->hasOne(Discount::class, 'id','discount_id');
    }
    public function products()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }

}
