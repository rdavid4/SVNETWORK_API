<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProjectResource extends JsonResource
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
            'title' => $this->title,
            'desciption' => $this->description,
            'date' => $this->date,
            'images' => ImageResource::collection($this->images),
            'companies' => SearchCompanyResource::collection($this->companies)
        ];
    }
}
