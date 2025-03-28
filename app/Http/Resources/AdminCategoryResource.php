<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCategoryResource extends JsonResource
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
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_title_2' => $this->meta_title_2,
            'meta_description2' => $this->meta_description2,
            'title' => $this->title,
            'description' => $this->description,
            'icon' => $this->icon,
            'image' => $this->image
        ];
    }
}
