<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceNewAddedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'service_name' =>$this->service->name,
            'service_slug' =>$this->service->slug,
            'state_name'=> $this->state->name_en,
            'state_slug'=> $this->state->slug
        ];
    }
}
