<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'surname' => $this->surname,
            'avatar_text' => $this->avatarText,
            'image' => $this->image,
            'email' => $this->email,
            'is_pro' => $this->pro,
            'is_admin' => $this->is_admin,
            'phone' => $this->phone,
            'companies' => CompanyResource::collection($this->companies),
            'projects' => CompanyProjectsResource::collection($this->projects),
            'stripe_client_id' => $this->stripe_client_id,
            'date' => $this->created_at->format('m/d/Y h:i A'),
        ];
    }
}
