<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductSpecification;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductListResource;
use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = (new Product())->getProductList($request->all());
        return ProductListResource::collection($products);
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
    public function store(StoreProductRequest $request)
    {
        try{
            DB::BeginTransaction();
            $product = (new Product)->storeProduct($request->all(),auth()->id());
            if($request->has('attributes')){
                (new ProductAttribute)->storeAttributeData($request->input('attributes'),$product);
            }

            if($request->has('specifications')){
                (new ProductSpecification)->storeSpecificatioData($request->input('specifications'),$product);
            }
            DB::Commit();
            return response()->json(['msg'=>'Product added successfully','cls'=>'success','product_id'=>$product->id]);
        }catch(\Throwable $e){
            info('PRODUCT_STORE_FAILED',['data'=>$request->all(),'exception'=>$e,]);
            DB::rollBack();
            return response()->json(['msg'=>$e->getMessage(),'cls'=>'warning','flag'=>true]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
