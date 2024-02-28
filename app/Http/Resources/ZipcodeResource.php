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
            'country_iso' => $this->ISO,
            'zipcode' => $this->ZIPCODE,
            'location' => $this->LOCATION,
            'state' => $this->STATE,
            'state_iso' => $this->STATE_ISO,
            'lat' => $this->LAT,
            'lon' => $this->LON,
        ];
    }
}
