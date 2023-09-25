<?php

namespace App\Http\Controllers;

use App\Http\Requests\Image\StoreImageRequest;
use App\Http\Requests\Image\UpdateImageRequest;
use App\Http\Requests\Product\storeProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Discount;
use App\Models\Image;
use App\Models\Product;
use App\Repositories\ImageRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    /**
     * Deklarimi dy repozitorive qe do te perdoren.
     */
    protected ProductRepository $productRepository;
    protected ImageRepository $imageRepository;
    public function __construct(ProductRepository $repository,ImageRepository $imageRepository){
        $this->productRepository = $repository;
        $this->imageRepository = $imageRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('product.index');
    }

    public function getForDatatable()
    {
        //Shfaqim ne datatable produktin
        $products = $this->productRepository->query()->with('images');

        return DataTables::of($products)->addIndexColumn()
            //Shtojme butonat edit dhe delete
            ->addColumn('action', function ($products) {
                $button = '<button type="button" id="'.$products->id.'" name="edit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Edit</button>';

                $button .= '<button type="button" id="'.$products->id.'" name="delete"
                            class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" style="background-color: red ">
                            Delete</button>';
                return $button;
            })
//            ->addColumn('image', function ($products){
//                return $products->images->first()->name;
//            })
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
    public function store(StoreProductRequest $productRequest,StoreImageRequest $imageRequest)
    {
        //Te dhenat e produktit te validuara
        $dataProduct = $productRequest->validated();
        //Imazhi i validuar
        $dataImage = $imageRequest->validated();
        //Ruhen produkti dhe imazhi me ane te repository
        $this->productRepository->store($dataProduct,$dataImage);

        return response()->json(['message' => 'Product created successfully']);
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
    public function update(UpdateProductRequest $updateProductRequest, Product $product)
    {
        $dataProduct = $updateProductRequest->validated();
        $this->productRepository->update($product,$dataProduct);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->productRepository->delete($product);
    }
}
