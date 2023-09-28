<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Manager\ImageManager;
use App\Manager\PriceManager;
use App\Models\ProductPhoto;
use App\Models\Product;
use Illuminate\Support\Carbon;
use App\Http\Resources\ProductAttributeListResource;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'name' =>$this->name,
            'slug' =>$this->slug,
            'cost' =>$this->cost . PriceManager::CURRENCY_SYMBOE,
            'price' =>$this->price . PriceManager::CURRENCY_SYMBOE,
            'sku' =>$this->sku,
            'stock' =>$this->stock,
            'discount_fixed' =>$this->discount_fixed . PriceManager::CURRENCY_SYMBOE,
            'discount_percent' =>$this->discount_percent. '%',
            'description' =>$this->description,
            'created_at' =>$this->created_at->toDayDateTimeString(),
            'updated_at' =>$this->updated_at != $this->created_at?$this->updated_at->toDayDateTimeString():'Not updated yet',
            'discount_start' =>$this->discount_start!= null ? Carbon::create($this->discount_start)->toDayDateTimeString(): null,
            'discount_end' =>$this->discount_end != null? Carbon::create($this->discount_end)->toDayDateTimeString():null,
            'status' =>$this->status==Product::STATUS_ACTIVE? 'Active':'Inactive',

            'brand' => $this->brand?->name,
            'category' => $this->category?->name,
            'sub_category' => $this->sub_category?->name,
            'supplier' => $this->supplier?$this->supplier->name.'-'.$this->supplier->phone:'',
            'country' => $this->country?->name,
            'created_by' => $this->created_by?->name,
            'updated_by' => $this->updated_by?->name,
            'primary_photo' => ImageManager::prepareImageUrl(ProductPhoto::THUMB_IMAGE_UPLOAD_PATH,$this->primary_photo->photo),
            'attributes' => ProductAttributeListResource::collection($this->product_attributes),
        ];
    }
}
