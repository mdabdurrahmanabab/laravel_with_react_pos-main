<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Address;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use Illuminate\Support\Str;
use App\Manager\ImageManager;
use Illuminate\Http\Request;
use App\Http\Resources\ShopListResource;
use App\Http\Resources\ShopEditResource;
use DB;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $shops = (new Shop())->getShopList($request->all());
        return ShopListResource::collection($shops);
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
    public function store(StoreShopRequest $request)
    {
        $shop =  (new Shop())->prepareData($request->except('logo'),auth());
        $address =  (new Address())->prepareData($request->except('logo'));

        if($request->has('logo')){
            $name = Str::slug($request->name);
            $shop['logo']=ImageManager::processImageUpload(
                $request->logo,
                $name,
                Shop::IMAGE_UPLOAD_PATH,
                Shop::WIDTH,
                Shop::HEIGHT,
                Shop::THUMB_IMAGE_UPLOAD_PATH,
                Shop::WIDTH_THUMBNAIL,
                Shop::HEIGHT_THUMBNAIL,
            );
        }
        try{
            DB::BeginTransaction();
            $shop = Shop::create($shop);
            $shop->address()->create($address);
            DB::Commit();
            return response()->json(['msg'=>'Shop added successfully','cls'=>'success']);
        }catch(\Throwable $e){
            if(isset($shop['logo'])){
                ImageManager::deletePhoto(Shop::IMAGE_UPLOAD_PATH,$shop['logo']);
                ImageManager::deletePhoto(Shop::THUMB_IMAGE_UPLOAD_PATH,$shop['logo']);
            }

            info('SHOP_STORE_FAILED',['shop'=>$shop,'address'=>$address,'exception'=>$e,]);
            DB::rollBack();
            return response()->json(['msg'=>$e->getMessage(),'cls'=>'warning','flag'=>true]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        $shop->load('address');
        return new ShopEditResource($shop);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShopRequest $request, Shop $shop)
    {
        $shop_data =  (new Shop())->prepareData($request->except('logo'),auth());
        $address_data =  (new Address())->prepareData($request->except('logo'));

        if($request->has('logo')){
            $name = Str::slug($request->name);
            $shop_data['logo']=ImageManager::processImageUpload(
                $request->logo,
                $name,
                Shop::IMAGE_UPLOAD_PATH,
                Shop::WIDTH,
                Shop::HEIGHT,
                Shop::THUMB_IMAGE_UPLOAD_PATH,
                Shop::WIDTH_THUMBNAIL,
                Shop::HEIGHT_THUMBNAIL,
                $shop->logo,
            );
        }
        try{
            DB::BeginTransaction();
            $shop_data = $shop->update($shop_data);
            $shop->address()->update($address_data);
            DB::Commit();
            return response()->json(['msg'=>'Shop Updated successfully','cls'=>'success']);
        }catch(\Throwable $e){
            info('SHOP_STORE_FAILED',['shop'=>$shop_data,'address'=>$address_data,'exception'=>$e,]);
            DB::rollBack();
            return response()->json(['msg'=>$e->getMessage(),'cls'=>'warning','flag'=>true]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        if(!empty($shop->logo)){
            ImageManager::deletePhoto(Shop::IMAGE_UPLOAD_PATH,$shop->logo);
            ImageManager::deletePhoto(Shop::THUMB_IMAGE_UPLOAD_PATH,$shop->logo);
        }
        $shop->delete();

        (new Address())->deleteAddressById($shop);
        return response()->json(['msg'=>'Shop deleted successfully','cls'=>'success']);
    }

    public function shopList()
    {
        $shops = (new Shop())->getShopIdAndName();
        return response()->json($shops);
    }
}
