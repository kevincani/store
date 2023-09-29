<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Repositories\ImageRepository;
use Illuminate\Http\Request;

class ImageController extends Controller
{

    protected ImageRepository $imageRepository;
    public function __construct(ImageRepository $imageRepository){
        $this->imageRepository = $imageRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        $this->imageRepository->delete($image);
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
