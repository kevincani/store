<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Discount;
use App\Models\HelpingInventary;
use App\Models\Image;
use App\Models\Inventary;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductsController extends Controller
{
    public function view(){
        $products = Product::all();
        $sizes = Size::all();
        $colors = Color::all();
        $categories = Category::all();
        $discounts = Discount::all();

        return view('products.index' , compact('products','sizes', 'colors', 'categories', 'discounts'));
    }

    public function viewAddProduct(){
        return view('products.add');
    }

    public function add(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);



        // Insert data into respective tables
        $product = Product::create([
            'name' => $validatedData['name'],
            'desc' => $validatedData['description'],
        ]);

        $img=$request->file('image');
        if (isset($img)) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $imagePath = $image->storeAs('public', $imageName);


            $imageModel = new Image();
            $imageModel->name = $imageName;
            $imageModel->product_id = $product->id;
            $imageModel->save();
        }

    }

    public function store(Request $request){

        $validatedData = $request->validate([
            'name' => 'required',
//            'description' => 'required',
            'color' => 'required',
            'size' => 'required',
            'discount' => 'required',
            'category' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);



//        // Insert data into respective tables
//        $product = Product::create([
//            'name' => $validatedData['name'],
//            'desc' => $validatedData['description'],
//        ]);

        $product = Product::firstOrCreate(['name' => $validatedData['name']]);
        $color = Color::firstOrCreate(['color' => $validatedData['color']]);
        $size = Size::firstOrCreate(['size' => $validatedData['size']]);
        $discount = Discount::firstOrCreate(['discount_percent' => $validatedData['discount']]);
        $category = Category::firstOrCreate(['category' => $validatedData['category']]);

        $inventary = Inventary::create([
            'product_id' => $product->id,
            'category_id' => $category->id,
            'size_id' => $size->id,
            'color_id' => $color->id,
            'discount_id' => $discount->id,
            'quantity' => $validatedData['quantity'],
            'price' => $validatedData['price'],
        ]);

        // Return a response
        return response()->json([
            'status' => 'success',
            'message' => 'Data inserted successfully',
        ])->header('Refresh', '3;url=' . route('products.view'));
    }

    public function viewDatatable(Request $request){

        if ($request->ajax()){

//            $products = Product::with('colors', 'categories', 'discounts', 'sizes','inventary')->get();

            $inventary = Inventary::with('colors', 'categories', 'discounts', 'sizes','products');



            return DataTables::of($inventary)
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" id="'.$data->id.'" name="edit" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Edit</button>';

                    $button .= '<button type="button" id="'.$data->id.'" name="delete"  class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" style="background-color: red ">
                    Delete</button>';

                    return $button;
                })
                ->make(true);
        }


        $sizes = Size::all();
        $colors = Color::all();
        $categories = Category::all();
        $discounts = Discount::all();
        return view('products.edit', compact('sizes', 'colors', 'categories', 'discounts'));
    }

    public function edit($id)
    {
        $product = Product::with('images','colors', 'categories', 'discounts', 'sizes','inventary')->findOrFail($id);


        return response()->json(['result' => compact('product')]);
    }
    public function update(Request $request){

        $validate = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'color' => 'required',
            'size' => 'required',
            'discount' => 'required',
            'category' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

//        if ($validate->fails()){
//            return response()->json(['errors' => $validate->errors()->all()]);
//        }

        $product = Product::findOrFail($request->hiddenId);
        $product->update([
            'name' => $validate['name'],
            'desc' => $validate['description'],
        ]);

        $color = Color::firstOrCreate(['color' => $validate['color']]);
        $size = Size::firstOrCreate(['size' => $validate['size']]);
        $discount = Discount::firstOrCreate(['discount_percent' => $validate['discount']]);
        $category = Category::firstOrCreate(['category' => $validate['category']]);


        $inventary = Inventary::where([
            'product_id' => $product->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
            'discount_id' => $discount->id,
            'category_id' => $category->id,
        ])->first();

        $inventary->update([
            'category_id' => $category->id,
            'size_id' => $size->id,
            'color_id' => $color->id,
            'discount_id' => $discount->id,
            'quantity' => $validate['quantity'],
            'price' => $validate['price'],
        ]);

        $img = $request->file('image');
        if (isset($img)) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $imagePath = $image->storeAs('public', $imageName);

            $imageModel = Image::where('product_id', $request->hiddenId)->first();
            $imageModel->name = $imageName;
            $imageModel->save();
        }

        return response()->json(['success' => 'Data is successfully updated']);

    }
    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $image = Image::where('product_id', $id)->first();
        $pivot = Inventary::where('product_id', $id)->first();

        $pivot->delete();
        $image->delete();
        $product->delete();
    }

}
