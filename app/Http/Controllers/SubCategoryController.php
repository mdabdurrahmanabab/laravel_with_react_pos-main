<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Http\Resources\SubCategoryListResource;
use App\Http\Resources\SubCategoryEditResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Manager\ImageManager;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request)
    {
        $subCategories = (new SubCategory())->getSubCategory($request->all());
        return SubCategoryListResource::collection($subCategories);
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
    final public function store(StoreSubCategoryRequest $request)
    {
        $sub_category = $request->except('photo');
        $sub_category['slug'] = Str::slug($request->input('slug'));
        $sub_category['user_id'] = auth()->id();

        if($request->has('photo')){
            $file = $request->input('photo');
             $sub_category['photo'] = $this->processImageUpload($file,$sub_category['slug']);
        }

        (new SubCategory())->storeSubCategory($sub_category);
        return response()->json(['msg'=>'Sub category added successfully','cls'=>'success']);
    }

    /**
     * Display the specified resource.
     */
    final public function show(SubCategory $subCategory)
    {
        return new SubCategoryEditResource($subCategory);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory)
    {
        $sub_category_data = $request->except('photo');
        $sub_category_data['slug'] = Str::slug($request->input('slug'));
        if($request->has('photo')){
            $file = $request->input('photo');
            $sub_category_data['photo'] = $this->processImageUpload($file,$sub_category_data['slug'],$subCategory->photo);
        }
        $subCategory->update($sub_category_data);
        return response()->json(['msg'=>' Sub Category updated successfully','cls'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(SubCategory $subCategory)
    {
        if(!empty($subCategory->photo)){
            ImageManager::deletePhoto(SubCategory::IMAGE_UPLOAD_PATH, $subCategory->photo);
            ImageManager::deletePhoto(SubCategory::THUMB_IMAGE_UPLOAD_PATH, $subCategory->photo);
        }
        $subCategory->delete();
        return response()->json(['msg'=>'Sub Category Delete successfully','cls'=>'success']);
    }

    public function subCategoryList($category_id)
    {
        $subCategoryList = (new SubCategory())->getSubCategoryIdAndName($category_id);
        return response()->json($subCategoryList);
    }

    private function processImageUpload($file,$name,$existing_photo=null){
        $width = 800;
        $height = 800;
        $width_thumbnail = 150;
        $height_thumbnail = 150;
        $path = SubCategory::IMAGE_UPLOAD_PATH;
        $thumb_path = SubCategory::THUMB_IMAGE_UPLOAD_PATH;

        if(!empty($existing_photo)){
            ImageManager::deletePhoto(SubCategory::IMAGE_UPLOAD_PATH, $existing_photo);
            ImageManager::deletePhoto(SubCategory::THUMB_IMAGE_UPLOAD_PATH, $existing_photo);
        }

        $photo_name = ImageManager::uploadImage($name,$width, $height, $path, $file);
        ImageManager::uploadImage($name,$width_thumbnail, $height_thumbnail, $thumb_path, $file);
        return $photo_name;
    }
}
