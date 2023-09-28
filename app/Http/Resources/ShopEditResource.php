<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Shop;
use App\Manager\ImageManager;

class ShopEditResource extends JsonResource
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
            'status' => $this->status,
            'details' => $this->details,
            'address' => $this->address?->address,
            'division_id' => $this->address?->division_id,
            'district_id' => $this->address?->district_id,
            'area_id' => $this->address?->area_id,
            'landmark' => $this->address?->landmark,
            'logo_preview' => ImageManager::prepareImageUrl(Shop::THUMB_IMAGE_UPLOAD_PATH,$this->logo),
        ];
    }
}
