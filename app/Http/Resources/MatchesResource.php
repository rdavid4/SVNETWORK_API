<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "user" => $this->client?->email,
            "service" => $this->service?->name,
            "company" => $this->company?->name,
            "project" => $this->project?->title,
            'date' => $this->created_at->format('m/d/Y h:i A')
        ];
    }
}
