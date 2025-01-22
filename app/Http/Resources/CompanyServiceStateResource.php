<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyServiceStateResource extends JsonResource
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
            "name_en" => $this->name_en,
            "iso_code" => $this->iso_code,
            "totalZipcodes" => $this->totalZipcodes,
            "totalSelected" => $this->totalSelected,
            "allSelected" => $this->allSelected,
            "regions" => $this->regions,
        ];
    }
}
