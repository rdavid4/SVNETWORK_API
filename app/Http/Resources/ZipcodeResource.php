<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZipcodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'country_iso' => $this->iso,
            'zipcode' => $this->zipcode,
            'location' => $this->location,
            'state' => $this->state,
            'state_iso' => $this->state_iso,
            'lat' => $this->lat,
            'lon' => $this->lon,
        ];
    }
}
