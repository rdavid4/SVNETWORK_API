<?php

namespace App\Http\Resources;

use App\Models\Image;
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
            'social_facebook' => $this->social_facebook,
            'social_youtube' => $this->social_youtube,
            'social_twitter' => $this->social_twitter,
            'zip_code' => $this->zip_code,
            'verified' => $this->verified,
            'logo_url' => $this->logo_url,
            'cover_url' => $this->cover_url,
            'state' => $this->state,
            'owners' => UserMinimalResource::collection($this->users),
            'public_url' => $this->publicUrl,
            'date' => $this->created_at->format('m/d/Y h:i A'),
            'services' => CompanyServiceListResource::collection($this->services),
            'states' => StateResource::collection($this->states),
            'reviews' => ReviewResource::collection($this->reviews),
            'images' => ImageCompanyResource::collection($this->images->where('type',Image::TYPE_IMAGE)),
            'review_rate' =>  $this->reviewRate,
            'categories' => CategoryResource::collection($this->categories)
        ];
    }
}
