<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Manager\ImageManager;
use App\Http\Resources\BrandListResource;
use App\Http\Resources\BrandEditResource;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = (new Brand())->getBrand($request->all());
        return BrandListResource::collection($brands);
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
    public function store(StoreBrandRequest $request)
    {
        $brand = $request->except('logo');
        $brand['slug'] = Str::slug($request->input('slug'));
        $brand['user_id'] = auth()->id();

        if($request->has('logo')){
            $file = $request->input('logo');
             $brand['logo'] = $this->processImageUpload($file,$brand['slug']);
        }

        (new Brand())->storeBrand($brand);
        return response()->json(['msg'=>'Brand added successfully','cls'=>'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return new BrandEditResource($brand);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand_data = $request->except('logo');
        $brand_data['slug'] = Str::slug($request->input('slug'));
        if($request->has('logo')){
            $file = $request->input('logo');
            $brand_data['logo'] = $this->processImageUpload($file,$brand_data['slug'],$brand->logo);
        }
        $brand->update($brand_data);
        return response()->json(['msg'=>'Brand updated successfully','cls'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        if(!empty($brand->logo)){
            ImageManager::deletePhoto(Brand::IMAGE_UPLOAD_PATH, $brand->logo);
            ImageManager::deletePhoto(Brand::THUMB_IMAGE_UPLOAD_PATH, $brand->logo);
        }
        $brand->delete();
        return response()->json(['msg'=>'Drand Delete successfully','cls'=>'success']);
    }

    public function brandList()
    {
        $brandList = (new Brand())->getBrandIdAndName();
        return response()->json($brandList);
    }

    private function processImageUpload($file,$name,$existing_photo=null){
        $width = 800;
        $height = 800;
        $width_thumbnail = 150;
        $height_thumbnail = 150;
        $path = Brand::IMAGE_UPLOAD_PATH;
        $thumb_path = Brand::THUMB_IMAGE_UPLOAD_PATH;

        if(!empty($existing_photo)){
            ImageManager::deletePhoto(Brand::IMAGE_UPLOAD_PATH, $existing_photo);
            ImageManager::deletePhoto(Brand::THUMB_IMAGE_UPLOAD_PATH, $existing_photo);
        }

        $photo_name = ImageManager::uploadImage($name,$width, $height, $path, $file);
        ImageManager::uploadImage($name,$width_thumbnail, $height_thumbnail, $thumb_path, $file);
        return $photo_name;
    }
}
