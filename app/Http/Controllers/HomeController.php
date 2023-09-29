<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Repositories\CategoryRepository;
use App\Repositories\ColorRepository;
use App\Repositories\InventaryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SizeRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** Deklarimi i repozitorit qe do te perdoret */
    protected CategoryRepository $categoryRepository;
    protected SizeRepository $sizeRepository;
    protected ColorRepository $colorRepository;
    protected InventaryRepository $inventaryRepository;
    protected ProductRepository $productRepository;
    public function __construct(CategoryRepository $categoryRepository,InventaryRepository $inventaryRepository,SizeRepository $sizeRepository
                                ,ColorRepository $colorRepository,ProductRepository $productRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->inventaryRepository = $inventaryRepository;
        $this->sizeRepository = $sizeRepository;
        $this->colorRepository = $colorRepository;
        $this->productRepository = $productRepository;
    }
    public function view(){

        // Produktet se bashku me imazhet
        $products = $this->productRepository->query()->with('images')->paginate(3);
        // Te gjitha masat per filtra
        $sizes = $this->sizeRepository->getAll();
        //Te gjitha kategorite per filtra
        $categories = $this->categoryRepository->getAll();

        return view('home.index', compact('products','sizes','categories'));
    }
    public function filter(Request $request){
        // Te gjitha masat behen compact per filtra
        $sizes = $this->sizeRepository->getAll();
        //Te gjitha kategorite behen compact per filtra
        $categories = $this->categoryRepository->getAll();

        // Kategoria qe do shfaqet
        $categoryId = $request->input('category');
        // Masa qe do te shfaqet
        $sizeId = $request->input('size');
        // Search-i qe do te shfaqet
        $searchQuery = $request->input('search'); // Get the search query

        // Ne array perfshijme ato produkte qe plotesojne kushtet e filtrave
        $products = $this->productRepository->query()->with('inventary')
            ->when($categoryId, function ($query, $categoryId) {
                return $query->whereHas('inventary', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
            })
            ->when($sizeId, function ($query, $sizeId) {
                return $query->whereHas('inventary', function ($query) use ($sizeId) {
                    $query->where('size_id', $sizeId);
                });
            })
            ->when($searchQuery, function ($query, $searchQuery) {
                $query->where('name', 'like', '%' . $searchQuery . '%');
            })
            ->paginate(3);

        // Kthejme response ne rast filtrash
        if ($request->ajax()) {
            return view('home.productList', compact('products', 'sizes', 'categories'));
        }

        //Kthejme response ne rast kalimi ne faqen tjeter prej paginimit
        return view('home.index', compact('products', 'sizes', 'categories'));
    }
    public function viewDetails($id){
        // Qe qe te zgjedhe klienti ngjyren per produktin
        $colors = $this->colorRepository->getAll();
        // Inventari i produktit qe do shfaqim detajet
        $inventary = $this->inventaryRepository->query()->with('products.images', 'colors', 'categories', 'discounts', 'sizes', 'products')
            ->where('product_id',$id)->get();

        return view('home.details', compact('inventary','colors'));
    }
    public function checkInventary(Request $request) {
        // Ngjyra e zgjedhur dhe me pas Id e saj
        $selectedColor = $request->input('selectedColor');
        $colorId = $this->colorRepository->query()->where('color', $selectedColor)->value('id');

        $quantity = $request->input('quantity');

        // Masa e zgjedhur dhe me pas Id e saj
        $size = $request->input('size');
        $sizeId = Size::where('size',$size)->value('id');
        // Id e produktit
        $productId = $request->input('id');
        // Kerkojme ne inventar produktin me karakteristikat e dhena
        $inventary = $this->inventaryRepository->query()->where([
            ['color_id', $colorId],
            ['size_id', $sizeId],
            ['product_id', $productId],
        ])->first();

        // Nqs nuk ndodhet kthejme pergjigje qe ka mbaruar
        if (!$inventary) {
            return response()->json(['status' => 'error', 'message' => 'The required color is over']);
        }

        $inventaryId = $inventary->id;
        // Kthejme pergjigje se bashku me Id e inventarit qe gjetem sepse ate e fusim ne shporte
        return response()->json(['status' => 'success', 'message' => 'Product added to the cart!', 'inventary_id' => $inventaryId]);
    }
}
