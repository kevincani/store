<?php

namespace App\Http\Controllers;

use App\Repositories\InventaryRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Deklarimi repozitorit qe do te perdoret.
     */
    protected InventaryRepository $inventaryRepository;
    public function __construct(InventaryRepository $inventaryRepository){
        $this->inventaryRepository = $inventaryRepository;
    }
    public function view(){
        return view('cart.index');
    }

    public function show(Request $request){

        $requestData = $request->input('requestData');
        $quantityData = $request->input('quantityData');


        $inventaries = [];

        // Marrim me rradhe te gjitha id e inventareve qe jane ne shporte
        foreach ($requestData as $index => $id) {
            $quantity = $quantityData[$index];
            $inventary = $this->inventaryRepository->query()->with('products.images', 'colors', 'categories', 'discounts', 'sizes', 'products')
                ->findOrFail($id);

            // Nqs inventary ekziston i japim quantityn qe klienti do te bleje
            if ($inventary) {
                $inventary->quantity = $quantity;
                $inventaries[] = $inventary;
            }
        }

        // Return the array of products as a JSON response
        return view('cart.items', compact('inventaries'));
    }
}
