<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AddressListResource;
use App\Models\Shop;
use App\Manager\ImageManager;

class ShopListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'details' => $this->details,
            'created_by' => $this->user->name,
            'logo' => ImageManager::prepareImageUrl(Shop::THUMB_IMAGE_UPLOAD_PATH,$this->logo),
            'logo_full' => ImageManager::prepareImageUrl(Shop::IMAGE_UPLOAD_PATH,$this->logo),
            'status' => $this->status == Shop::STATUS_ACTIVE?Shop::STATUS_ACTIVE_TEXT:Shop::STATUS_INACTIVE_TEXT,
            'created_at' => $this->created_at->toDayDateTimeString(),
            'updated_at' => $this->updated_at != $this->created_at ? $this->updated_at->toDayDateTimeString() : 'Not updated yet',
            'address' => (new AddressListResource($this->address)),
        ];
    }
}
