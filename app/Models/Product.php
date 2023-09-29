<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\In;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'desc' ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'inventary');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'inventary');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'inventary');
    }
    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'inventary');
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function inventary()
    {
        return $this->belongsTo(Inventary::class , 'id','product_id');
    }

}
