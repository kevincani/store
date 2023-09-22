<?php

namespace App\Repositories;

use App\Models\Inventary;
use Illuminate\Database\Eloquent\Builder;

class InventaryRepository extends BaseRepository
{
    public const MODEL = Inventary::class;

    public function findInventary($id){
        return Inventary::find($id);
    }
    public function fullInventary($id){
        return Inventary::with('colors', 'categories', 'discounts', 'sizes', 'products')
            ->find($id);
    }
    public function inventaryDatatable(): Builder
    {
        return Inventary::with('colors', 'categories', 'discounts', 'sizes','products');
    }
}
