<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Inventary;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function view(){

        $products = Product::with('images', 'colors', 'categories', 'discounts', 'sizes', 'inventary')->paginate(3);
        $sizes = Size::all();
        $categories = Category::all();

        return view('display.welcome', compact('products','sizes','categories'));
    }

    public function viewDetails($id){
        $colors = Color::all();
        $products = Product::with('images', 'colors', 'categories', 'discounts', 'sizes', 'inventary')->findOrFail($id);
        return view('display.details', compact('products','colors'));
    }

    public function checkInventary(Request $request) {
        // Get the data sent through the AJAX request
        $selectedColor = $request->input('selectedColor');
        $colorId = Color::where('color', $selectedColor)->value('id');
        $quantity = $request->input('quantity');
        $size = $request->input('size');
        $sizeId = Size::where('size',$size)->value('id');
        $productId = $request->input('id');

        $pivot = Inventary::where([
            ['color_id', $colorId],
            ['size_id', $sizeId],
            ['product_id', $productId],
        ])->first();


        if (!$pivot) {
            return response()->json(['status' => 'error', 'message' => 'The required color is over']);
        }

        $pivotId = $pivot->id;

        return response()->json(['status' => 'success', 'message' => 'Product added to the cart!', 'pivot_id' => $pivotId]);
    }


    public function filterCategory(Request $request)
    {
        $sizes = Size::all();
        $categories = Category::all();

        $categoryId = $request->input('category');
        $sizeId = $request->input('size');
        $searchQuery = $request->input('search'); // Get the search query

        $products = Product::with('images', 'colors', 'categories', 'discounts', 'sizes', 'inventary')
            ->when($categoryId, function ($query, $categoryId) {
                return $query->whereHas('categories', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
            })
            ->when($sizeId, function ($query, $sizeId) {
                return $query->whereHas('sizes', function ($query) use ($sizeId) {
                    $query->where('size_id', $sizeId);
                });
            })
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('name', 'like', '%' . $searchQuery . '%');
            })
            ->paginate(3);

//        $products->appends($request->query()); // Preserve the filter parameters in the pagination links

        if ($request->ajax()) {
            return view('display.productList', compact('products', 'sizes', 'categories'));
        }

        return view('display.welcome', compact('products', 'sizes', 'categories'));
    }

    public function cart(){
        return view('display.cart');
    }

    public function cartDisplay(Request $request){

        $requestData = $request->input('requestData');
        $quantityData = $request->input('quantityData');
        $productData = $request->input('productData');


        $products = [];

        // Loop through the IDs in the requestData array and fetch the corresponding products
        foreach ($requestData as $index => $id) {
            $quantity = $quantityData[$index];

            $product = Product::with(['images', 'colors', 'categories', 'discounts', 'sizes', 'inventary' => function ($query) use ($id) {
                $query->where('inventary.id', '=', $id);
            }])->find($productData[$index]);
            // If the product is found, set the quantity value for the pivot
            if ($product) {
                $product->inventary[0]->quantity = $quantity;
                $products[] = $product;
            }
        }

        // Return the array of products as a JSON response
        return view('display.cartItems', compact('products'));
    }

}
