<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    public const MODEL = Product::class;

    protected ImageRepository $ImageRepository;
    public function __construct(ImageRepository $imageRepository){
        $this->ImageRepository = $imageRepository;
    }


    public function store(array $dataProduct,array $dataImage){

       $product = $this->create($dataProduct);

        /**
         * Ruajme secilin imazh ne folderin 'public' dhe i fusim ne databaze
         */
        foreach ($dataImage['images'] as $data){
            $imageName = $data->getClientOriginalName();
            $data->storeAs('public', $imageName);

            $image = [
                'name' => $imageName,
                'product_id' => $product->id
            ];
            $this->ImageRepository->create($image);
        }
    }



}
