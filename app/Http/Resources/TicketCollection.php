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
        return[
            'id'=>strval($this->id),
            'ticket_name'=>$this->ticket_name,
            'ticket_event_id'=>$this->ticket_event_id,
            'ticket_price'=>$this->ticket_price,
            'ticket_quantity'=>$this->ticket_price,
            'ticket_available'=>$this->ticket_available,
        ];
    }
}