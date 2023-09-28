<?php

namespace App\Http\Controllers;

use App\Models\SalesManager;
use App\Models\Address;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalesManagerRequest;
use App\Http\Requests\UpdateSalesManagerRequest;
use Illuminate\Support\Str;
use App\Manager\ImageManager;
use DB;

class SalesManagerController extends Controller
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
    public function store(StoreSalesManagerRequest $request)
    {
        $salesManager =  (new SalesManager())->prepareData($request->except('logo'),auth());
        $address =  (new Address())->prepareData($request->except('logo'));

        if($request->has('photo')){
            $name = Str::slug($request->name.'-'.now().'photo');
            $salesManager['photo']=ImageManager::processImageUpload(
                $request->photo,
                $name,
                SalesManager::IMAGE_UPLOAD_PATH,
                SalesManager::WIDTH,
                SalesManager::HEIGHT,
                SalesManager::THUMB_IMAGE_UPLOAD_PATH,
                SalesManager::WIDTH_THUMBNAIL,
                SalesManager::HEIGHT_THUMBNAIL,
            );
        }

        if($request->has('nid_photo')){
            $name = Str::slug($request->name.'-'.now().'nid_photo');
            $salesManager['nid_photo']=ImageManager::processImageUpload(
                $request->nid_photo,
                $name,
                SalesManager::IMAGE_UPLOAD_PATH,
                SalesManager::WIDTH,
                SalesManager::HEIGHT,
                SalesManager::THUMB_IMAGE_UPLOAD_PATH,
                SalesManager::WIDTH_THUMBNAIL,
                SalesManager::HEIGHT_THUMBNAIL,
            );
        }

        try{
            DB::BeginTransaction();
            $salesManager = SalesManager::create($salesManager);
            $salesManager->address()->create($address);
            DB::Commit();
            return response()->json(['msg'=>'SalesManager added successfully','cls'=>'success']);
        }catch(\Throwable $e){
            if(isset($salesManager['photo'])){
                ImageManager::deletePhoto(SalesManager::IMAGE_UPLOAD_PATH,$salesManager['photo']);
                ImageManager::deletePhoto(SalesManager::THUMB_IMAGE_UPLOAD_PATH,$salesManager['photo']);
            }
            if(isset($salesManager['nid_photo'])){
                ImageManager::deletePhoto(SalesManager::IMAGE_UPLOAD_PATH,$salesManager['nid_photo']);
                ImageManager::deletePhoto(SalesManager::THUMB_IMAGE_UPLOAD_PATH,$salesManager['nid_photo']);
            }

            info('SALES_MANAGER_STORE_FAILED',['salesManager'=>$salesManager,'address'=>$address,'exception'=>$e,]);
            DB::rollBack();
            return response()->json(['msg'=>$e->getMessage(),'cls'=>'warning','flag'=>true]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesManager $salesManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesManager $salesManager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalesManagerRequest $request, SalesManager $salesManager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesManager $salesManager)
    {
        //
    }
}
