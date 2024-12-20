<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageCompanyResource extends JsonResource
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
            'url' => $this->urlCompany,
            'width' => $this->width,
            'height' => $this->height,
            'extension' => $this->extension,
            'isImage'=>$this->isImage
        ];
    }
}
