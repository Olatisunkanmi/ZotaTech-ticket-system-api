<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;


/**
 * @mixin \App\Models\Ticket
 **/


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
            'id' => strval($this->id),
            'event_id' => $this->event_id,
            'ticket_type' => $this->ticket_type,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'available' => $this->available,
        ];
        
    }
}
