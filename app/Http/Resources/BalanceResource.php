<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'available' => $this->available,
            'total_available' =>number_format($this->available[0]->amount / 100, 2, '.', '').' '. strtoupper($this->available[0]->currency),
            'total_pending' =>number_format($this->pending[0]->amount / 100, 2, '.', '').' '. strtoupper($this->pending[0]->currency),
            'pending' => $this->pending,

        ];
    }
}
