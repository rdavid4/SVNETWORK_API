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
            'description' => $this->description,
            'email' => $this->email,
            'slug' => $this->slug,
            'phone' => $this->phone,
            'city' => $this->city,
            'video_url' => $this->video_url,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'zip_code' => $this->zip_code,
            'verified' => $this->verified,
            'logo_url' => $this->logo_url,
            'state' => $this->state,
            'owners' => $this->users,
            'date' => $this->created_at->format('m/d/Y h:i A'),
            'services' => ServiceResource::collection($this->services),
            'states' => StateResource::collection($this->states),
            'categories' => CategoryResource::collection($this->categories),
        ];
    }
}
