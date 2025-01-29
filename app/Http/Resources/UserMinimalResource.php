<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMinimalResource extends JsonResource
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
            'avatar_text' => $this->avatarText,
            'name' => $this->name,
            'surname' => $this->surname,
            'image' => $this->image,
            'email' => $this->email
        ];
    }
}
