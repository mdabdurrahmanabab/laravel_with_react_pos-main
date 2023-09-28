<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Address;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Manager\ImageManager;
use App\Http\Resources\SupplierListResource;
use App\Http\Resources\SupplierEditResource;
use DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $suppliers = (new Supplier())->getSupplierList($request->all());
        return SupplierListResource::collection($suppliers);
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
    final public function store(StoreSupplierRequest $request)
    {
        $supplier =  (new Supplier())->prepareData($request->except('logo'),auth());
        $address =  (new Address())->prepareData($request->except('logo'));

        if($request->has('logo')){
            $name = Str::slug($request->name);
            $supplier['logo']=ImageManager::processImageUpload(
                $request->logo,
                $name,
                Supplier::IMAGE_UPLOAD_PATH,
                Supplier::WIDTH,
                Supplier::HEIGHT,
                Supplier::THUMB_IMAGE_UPLOAD_PATH,
                Supplier::WIDTH_THUMBNAIL,
                Supplier::HEIGHT_THUMBNAIL,
            );
        }
        try{
            DB::BeginTransaction();
            $supplier = Supplier::create($supplier);
            $supplier->address()->create($address);
            DB::Commit();
            return response()->json(['msg'=>'Supplier added successfully','cls'=>'success']);
        }catch(\Throwable $e){
            if(isset($supplier['logo'])){
                ImageManager::deletePhoto(Supplier::IMAGE_UPLOAD_PATH,$supplier['logo']);
                ImageManager::deletePhoto(Supplier::THUMB_IMAGE_UPLOAD_PATH,$supplier['logo']);
            }

            info('SUPPLIER_STORE_FAILED',['supplier'=>$supplier,'address'=>$address,'exception'=>$e,]);
            DB::rollBack();
            return response()->json(['msg'=>'Something went wrong','cls'=>'warning','flag'=>true]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('address');
        return new SupplierEditResource($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier_data =  (new Supplier())->prepareData($request->except('logo'),auth());
        $address_data =  (new Address())->prepareData($request->except('logo'));

        if($request->has('logo')){
            $name = Str::slug($request->name);
            $supplier_data['logo']=ImageManager::processImageUpload(
                $request->logo,
                $name,
                Supplier::IMAGE_UPLOAD_PATH,
                Supplier::WIDTH,
                Supplier::HEIGHT,
                Supplier::THUMB_IMAGE_UPLOAD_PATH,
                Supplier::WIDTH_THUMBNAIL,
                Supplier::HEIGHT_THUMBNAIL,
                $supplier->logo,
            );
        }
        try{
            DB::BeginTransaction();
            $supplier_data = $supplier->update($supplier_data);
            $supplier->address()->update($address_data);
            DB::Commit();
            return response()->json(['msg'=>'Supplier Updated successfully','cls'=>'success']);
        }catch(\Throwable $e){
            info('SUPPLIER_STORE_FAILED',['supplier'=>$supplier_data,'address'=>$address_data,'exception'=>$e,]);
            DB::rollBack();
            return response()->json(['msg'=>'Something went wrong','cls'=>'warning','flag'=>true]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        if(!empty($supplier->logo)){
            ImageManager::deletePhoto(Supplier::IMAGE_UPLOAD_PATH,$supplier->logo);
            ImageManager::deletePhoto(Supplier::THUMB_IMAGE_UPLOAD_PATH,$supplier->logo);
        }
        $supplier->delete();

        (new Address())->deleteAddressById($supplier);
        return response()->json(['msg'=>'Supplier deleted successfully','cls'=>'success']);
    }

    public function supplierList()
    {
        $supplierList = (new Supplier())->getSupplierIdNameAndPhone();
        return response()->json($supplierList);
    }


}
