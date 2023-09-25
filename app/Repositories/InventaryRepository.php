<?php

namespace App\Repositories;

use App\Models\Inventary;
use Illuminate\Database\Eloquent\Builder;

class InventaryRepository extends BaseRepository
{
    public const MODEL = Inventary::class;


    public function fullInventary($id){
        return Inventary::with('colors', 'categories', 'discounts', 'sizes', 'products')
            ->find($id);
    }


}
