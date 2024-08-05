<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            "object" => $this->object,
            "has_more" => $this->has_more,
            "url" => $this->url,
            "data" => ChargeResource::collection($this->data)
        ];
    }
}
