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
            'current_page' => $this->current_page,
            'data' => $this->collection,
            'first_page_url' => $this->first_page_url,
            'from' => $this->from,
            'last_page' => $this->last_page,
            'last_page_url' => $this->last_page_url,
            'links' => $this->links,
            'next_page_url' => $this->next_page_url,
            'path' => $this->path,
            'per_page' => $this->per_page,
            'prev_page_url' => $this->prev_page_url,
            'to' => $this->to,
            'total' => $this->total,
        ];
    }
}
