<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardUserResource extends JsonResource
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
            'email' => $this->email,
            'image' => $this->image,
            'date' => $this->created_at->format('m/d/Y h:i A'),
        ];
    }
}
