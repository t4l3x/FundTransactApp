<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'currency' => $this->currency->isoCode(),
            'balance' => $this->balance->toDecimalAmount(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
