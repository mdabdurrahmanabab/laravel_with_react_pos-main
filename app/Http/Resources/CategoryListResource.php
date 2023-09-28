<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;
use App\Manager\ImageManager;

class CategoryListResource extends JsonResource
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
            'status' => $this->status == 1 ? 'Active':'Inactive',
            'photo' => ImageManager::prepareImageUrl(Category::THUMB_IMAGE_UPLOAD_PATH,$this->photo),
            'photo_full' => ImageManager::prepareImageUrl(Category::IMAGE_UPLOAD_PATH,$this->photo),
            'created_by' => $this->user?->name,
            'created_at' => $this->created_at->toDayDateTimeString(),
            'updated_at' => $this->updated_at != $this->created_at ? $this->updated_at->toDayDateTimeString() : 'Not updated yet',
        ];
    }
}
