<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'event_id' => $this->resource->event_id,
            'ticket_type' => $this->resource->ticket_type,
            'amount' => $this->resource->amount,
            'quantity' => $this->resource->quantity,
        ];
    }
}
