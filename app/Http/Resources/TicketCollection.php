<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TicketCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
      return [
            'id' => strval($this->resource->id),
            'event_id' => $this->resource->event_id,
            'ticket_type' => $this->resource->ticket_type,
            'price' => $this->resource->price,
            'quantity' => $this->resource->quantity,
            'available' => $this->resource->available,
        ];
    }
}
