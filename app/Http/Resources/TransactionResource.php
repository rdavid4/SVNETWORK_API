<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        "id"=> $this->id,
        "user"=> $this->user,
        "project"=> $this->project,
        "service"=> $this->service,
        "price"=> number_format($this->price, 2, '.', '').' '. strtoupper('USD'),
        "paid"=> $this->paid,
        "stripe_payment_method"=> $this->stripe_payment_method,
        "message"=> $this->message,
        "payment_code"=> $this->payment_code,
        "date" => date_format($this->created_at, 'm/d/Y h:i A')
        ];
    }
}
