<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
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
            'description' => $this->description,
            'client' => new UserBasicResource($this->client),
            'location' => $this->location,
            'service' => new ServiceBasicResource($this->service),
            'date' => $this->getDateHumansAttribute(),
            'images' => ImageQuoteResource::collection($this->images)
        ];
    }
}
