<?php

namespace App\Http\Resources;
use App\Models\Event; // Make sure to import the Event model if not already imported
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Event
 **/

class EventResources extends JsonResource
{
   
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => 'events',
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'category' => $this->category,
                'image' => [
                    'file_name' => $this->image->file_name,
                    'mime_type' => $this->image->mime_type,
                    'file_size' => $this->image->size,
                    'image_url' => $this->image->original_url,
                ],
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
        ];
    }
}
