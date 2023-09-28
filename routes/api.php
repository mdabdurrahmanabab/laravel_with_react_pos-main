<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthCotroller;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPhotoController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SalesManagerController;
use App\Manager\ScriptManager;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Insert Division, Disctrict and Area
Route::get('insert-location',[ScriptManager::class,'getLocationData']);
//Insert Division
Route::get('insert-country',[ScriptManager::class,'getCountry']);


Route::get('division',[DivisionController::class,'index']);
Route::get('district/{division_id}',[DistrictController::class,'index']);
Route::get('area/{district_id}',[AreaController::class,'index']);

Route::post('login',[AuthCotroller::class,'login']);
Route::group(['middleware'=>'auth:sanctum'],static function(){

    Route::post('logout',[AuthCotroller::class,'logout']);
    Route::get('category-list',[CategoryController::class,'categoryList']);
    Route::get('sub-category-list/{id}',[SubCategoryController::class,'subCategoryList']);
    Route::get('brand-list',[BrandController::class,'brandList']);
    Route::get('country-list',[CountryController::class,'countryList']);
    Route::get('supplier-list',[SupplierController::class,'supplierList']);
    Route::get('attribute-list',[AttributeController::class,'attributeList']);
    Route::post('product_photo_upload/{id}',[ProductPhotoController::class,'store']);
    Route::get('shop-list',[ShopController::class,'shopList']);

    Route::apiResource('category',CategoryController::class);
    Route::apiResource('sub-category',SubCategoryController::class);
    Route::apiResource('brand',BrandController::class);
    Route::apiResource('supplier',SupplierController::class);
    Route::apiResource('attribute',AttributeController::class);
    Route::apiResource('value',AttributeValueController::class);
    Route::apiResource('product',ProductController::class);
    Route::apiResource('shop',ShopController::class);
    Route::apiResource('sales-manager',SalesManagerController::class);

});
