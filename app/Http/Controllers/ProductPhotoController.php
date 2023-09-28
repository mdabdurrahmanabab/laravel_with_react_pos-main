<?php

namespace App\Http\Controllers;

use App\Models\ProductPhoto;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductPhotoRequest;
use App\Http\Requests\UpdateProductPhotoRequest;
use Illuminate\Support\Str;
use App\Manager\ImageManager;
use Illuminate\Support\Carbon;

class ProductPhotoController extends Controller
{
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
    public function store(StoreProductPhotoRequest $request, $id)
    {
        if($request->has('photos')){
            $product = (new Product())->getProductById($id);
            if($product){
                foreach ($request->photos as $photo) {
                    $name = Str::slug($product->slug.'-'.Carbon::now()->toDayDateTimeString().'-'.random_int(10000, 99999));
                    $photo_data['product_id'] = $id;
                    $photo_data['is_primary'] = $photo['is_primary'];
                    $photo_data['photo'] =ImageManager::processImageUpload(
                        $photo['photo'],
                        $name,
                        ProductPhoto::IMAGE_UPLOAD_PATH,
                        ProductPhoto::WIDTH,
                        ProductPhoto::HEIGHT,
                        ProductPhoto::THUMB_IMAGE_UPLOAD_PATH,
                        ProductPhoto::WIDTH_THUMBNAIL,
                        ProductPhoto::HEIGHT_THUMBNAIL,
                    );
                    (new ProductPhoto())->storeProductPhoto($photo_data);
                }
            }
        }
        return response()->json(['msg'=>'Product photo added successfully','cls'=>'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductPhoto $productPhoto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductPhoto $productPhoto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductPhotoRequest $request, ProductPhoto $productPhoto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductPhoto $productPhoto)
    {
        //
    }
}
