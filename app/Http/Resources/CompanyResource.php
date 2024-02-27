<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'city' => $this->city,
            'date' => $this->created_at->format('m/d/Y h:i A'),
            'services' => ServiceResource::collection($this->services),
            'states' => StateResource::collection($this->states),
            'categories' => CategoryResource::collection($this->categories),
        ];
    }
}
