<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Manager\ImageManager;
use App\Models\Brand;

class BrandEditResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'serial' => $this->serial,
            'status' => $this->status,
            'logo_preview' => ImageManager::prepareImageUrl(Brand::THUMB_IMAGE_UPLOAD_PATH,$this->logo),
        ];
    }
}
