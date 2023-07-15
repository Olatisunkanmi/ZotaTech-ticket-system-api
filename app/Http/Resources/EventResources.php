<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => 'events',
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'image' => [
                    'file_name' => $this->image->file_name,
                    'mine_type' => $this->image->mime_type,
                    'file_size' => $this->image->size,
                    'image_url' => $this->image->original_url,
                ],
                'date' => $this->date,
                'time' => $this->time,
                'type' => $this->type,
                'price' => $this->price,
                'capacity' => $this->capacity,
                'available_seats' => $this->available_seats,
                'location' => $this->location,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'user_id' => $this->user_id,
            ],  
                'url' => $this->url,
                "relationships" => [
                'tickets' => $this->ticket
                ]
        ];
    }
}
