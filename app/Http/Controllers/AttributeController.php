<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Http\Resources\AttributeListResource;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = (new Attribute())->getAttributeList();
        return AttributeListResource::collection($attributes);
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
    public function store(StoreAttributeRequest $request)
    {
        $attribute_data = $request->all();
        $attribute_data['user_id']= auth()->id();
        Attribute::create($attribute_data);
        return response()->json(['msg'=>'Attribute added successfully','cls'=>'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attribute $attribute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {
        $attribute_data = $request->all();
        $attribute->update($attribute_data);
        return response()->json(['msg'=>'Attribute updated successfully','cls'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return response()->json(['msg'=>'Attribute deleted successfully','cls'=>'success']);
    }

    public function attributeList()
    {
        $attribute_lists = (new Attribute())->getAttributeIdAndNameWithValue();
        return response()->json($attribute_lists);
    }
}
