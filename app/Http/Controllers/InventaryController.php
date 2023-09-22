<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventary\StoreInventaryRequest;
use App\Http\Requests\inventary\UpdateInventaryRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Discount;
use App\Models\Inventary;
use App\Models\Product;
use App\Models\Size;
use App\Repositories\InventaryRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InventaryController extends Controller
{
    /**
     * Deklarimi repozitorit qe do te perdoret.
     */
    protected InventaryRepository $inventaryRepository;
    public function __construct(InventaryRepository $inventaryRepository){
        $this->inventaryRepository = $inventaryRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $sizes = Size::all();
        $colors = Color::all();
        $categories = Category::all();
        $discounts = Discount::all();

        return view('inventary.index' , compact('products','sizes', 'colors', 'categories', 'discounts'));
    }

    public function getForDatatable(){

        $inventary = $this->inventaryRepository->inventaryDatatable()->get();

        return DataTables::of($inventary)
            ->addColumn('action', function ($inventary) {
                $button = '<button type="button" id="'.$inventary->id.'" name="edit"
                           class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                           Edit</button>';

                $button .= '<button type="button" id="'.$inventary->id.'" name="delete"
                            class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" style="background-color: red ">
                            Delete</button>';

                return $button;
            })
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventaryRequest $request)
    {
        $data = $request->validated();
        $this->inventaryRepository->create($data);
        return response()->json(['message' => 'Added to inventary successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventaryRequest $request, Inventary $inventary)
    {
        $data = $request->validated();
        $this->inventaryRepository->update($inventary,$data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventary $inventary)
    {
        $this->inventaryRepository->delete($inventary);
    }
}
