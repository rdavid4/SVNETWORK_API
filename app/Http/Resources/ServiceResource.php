<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'pause' => $this->pause,
            'image' => $this->image,
            'icon' => $this->icon,
            'icon_image' => $this->icon_image,
            'category' => new CategoryResource($this->category),
        ];
    }
}
