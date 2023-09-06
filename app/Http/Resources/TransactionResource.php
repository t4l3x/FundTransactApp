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
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'sender_account_id' => $this->sender_account_id,
            'receiver_account_id' => $this->receiver_account_id,
            'created_at' => $this->created_at,
            // Add more fields as needed
        ];
    }
}
