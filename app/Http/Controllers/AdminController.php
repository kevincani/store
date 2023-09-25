<?php

namespace App\Http\Controllers;

use App\Repositories\InventaryRepository;
use App\Repositories\OrderRepository;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    /** Deklarimi i repozitorive qe do te perdoren */
    protected OrderRepository $orderRepository;
    protected InventaryRepository $inventaryRepository;
    public function __construct(OrderRepository $repository,InventaryRepository $inventaryRepository){
        $this->orderRepository = $repository;
        $this->inventaryRepository = $inventaryRepository;
    }

    public function view(){
        return view('admin.index');
    }
    public function raport(){
        // Te gjithe porosite
        $orders = $this->orderRepository->query()->with('orderDetails')->get();

        // Array qe do te mbaje te dhenat e datatable
        $raport = [];

        foreach ($orders as $order){
            if (!$order->is_refunded){
                foreach ($order->orderDetails as $orderDetail){  // Loop te gjithe produktet e seciles porosi

                    $id = $orderDetail->pivot_id; // id e inventarit qe ndodhej produkti i shitur

                    $inventary = $this->inventaryRepository->fullInventary($id); //Te dhenat qe ndodhen ne inventar
//
                    // Nqs eshte eshte shitur nje produkt identik rrisim sasine e shitur perndryshe e krijojm ne raport
                    if (isset($raport[$id])){
                        $raport[$id]['quantity'] += $orderDetail->quantity;
                    }
                    else{
                        $raport[$id]['productName'] = $inventary->products->name;
                        $raport[$id]['productSize'] = $inventary->sizes->size;
                        $raport[$id]['productColor'] = $inventary->colors->color;
                        $raport[$id]['productCategory'] = $inventary->categories->category;
                        $raport[$id]['quantity'] = $orderDetail->quantity;
                    }
                }
            }
        }
        return DataTables::of($raport)->make();
    }
}
