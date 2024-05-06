<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
class ChargeResource extends JsonResource
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
            "object" => $this->object,
            "amount" => number_format($this->amount / 100, 2, '.', '').' '. strtoupper($this->currency),
            "amount_captured" => $this->amount_captured,
            "amount_refunded" => $this->amount_refunded,
            "application" => $this->application,
            "application_fee" => $this->application_fee,
            "application_fee_amount" => $this->application_fee_amount,
            "balance_transaction" => $this->balance_transaction,
            "billing_details" => $this->billing_details,
            "calculated_statement_descriptor" => $this->calculated_statement_descriptor,
            "captured" => $this->captured,
            "created" => $this->created,
            "currency" => $this->currency,
            "customer" => $this->customer,
            "user" => User::where('stripe_client_id', $this->customer)->first(),
            "description" => $this->description,
            "destination" => $this->destination,
            "dispute" => $this->dispute,
            "disputed" => $this->disputed,
            "failure_balance_transaction" => $this->failure_balance_transaction,
            "failure_code" => $this->failure_code,
            "failure_message" => $this->failure_message,
            "fraud_details" => $this->fraud_details,
            "invoice" => $this->invoice,
            "livemode" => $this->livemode,
            "metadata" => $this->metadata,
            "on_behalf_of" => $this->on_behalf_of,
            "order" => $this->order,
            "outcome" => $this->outcome,
            "paid" => $this->paid,
            "payment_intent" => $this->payment_intent,
            "payment_method" => $this->payment_method,
            "payment_method_details" => $this->payment_method_details,
            "radar_options" => $this->radar_options,
            "receipt_email" => $this->receipt_email,
            "receipt_number" => $this->receipt_number,
            "receipt_url" => $this->receipt_url,
            "refunded" => $this->refunded,
            "review" => $this->review,
            "shipping" => $this->shipping,
            "source" => $this->source,
            "source_transfer" => $this->source_transfer,
            "statement_descriptor" => $this->statement_descriptor,
            "statement_descriptor_suffix" => $this->statement_descriptor_suffix,
            "status" => $this->status,
            "transfer_data" => $this->transfer_data,
            "transfer_group" => $this->transfer_group,
            "date" => date("m-d-Y H:i:s", $this->create)
        ];
    }
}
