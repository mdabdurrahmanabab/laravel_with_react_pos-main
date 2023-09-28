<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Manager\ImageManager;
use App\Http\Resources\CategoryListResource;
use App\Http\Resources\CategoryEditResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = (new Category())->getCategory($request->all());
        return CategoryListResource::collection($categories);
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
    public function store(StoreCategoryRequest $request)
    {
        $category = $request->except('photo');
        $category['slug'] = Str::slug($request->input('slug'));
        $category['user_id'] = auth()->id();

        if($request->has('photo')){
            $file = $request->input('photo');
             $category['photo'] = $this->processImageUpload($file,$category['slug']);
        }

        (new Category())->storeCategory($category);
        return response()->json(['msg'=>'Category added successfully','cls'=>'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryEditResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category_data = $request->except('photo');
        $category_data['slug'] = Str::slug($request->input('slug'));
        if($request->has('photo')){
            $file = $request->input('photo');
            $category_data['photo'] = $this->processImageUpload($file,$category_data['slug'],$category->photo);
        }
        $category->update($category_data);
        return response()->json(['msg'=>'Category updated successfully','cls'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Category $category)
    {
        if(!empty($category->photo)){
            ImageManager::deletePhoto(Category::IMAGE_UPLOAD_PATH, $category->photo);
            ImageManager::deletePhoto(Category::THUMB_IMAGE_UPLOAD_PATH, $category->photo);
        }
        $category->delete();
        return response()->json(['msg'=>'Category Delete successfully','cls'=>'success']);
    }

    final public function categoryList()
    {
        $categories = (new Category)->getCategoryIdAndName();
        return response()->json($categories);
    }

    private function processImageUpload($file,$name,$existing_photo=null){
        $width = 800;
        $height = 800;
        $width_thumbnail = 150;
        $height_thumbnail = 150;
        $path = Category::IMAGE_UPLOAD_PATH;
        $thumb_path = Category::THUMB_IMAGE_UPLOAD_PATH;

        if(!empty($existing_photo)){
            ImageManager::deletePhoto(Category::IMAGE_UPLOAD_PATH, $existing_photo);
            ImageManager::deletePhoto(Category::THUMB_IMAGE_UPLOAD_PATH, $existing_photo);
        }

        $photo_name = ImageManager::uploadImage($name,$width, $height, $path, $file);
        ImageManager::uploadImage($name,$width_thumbnail, $height_thumbnail, $thumb_path, $file);
        return $photo_name;
    }
}
