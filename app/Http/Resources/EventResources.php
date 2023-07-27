<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResources extends JsonResource
{
    /** 
     * @mixin Event
     * @extends JsonResource<Event>
     **/
    public function toArray($request)
    {


        return [
            'id' => $this->resource->id,
            'type' => 'events',
            'attributes' => [
                'title' => $this->resource->title,
                'description' => $this->resource->description,
                'category' => $this->category,
                'image' => [
                    'file_name' => $this->resource->image->file_name,
                    'mime_type' => $this->resource->image->mime_type,
                    'file_size' => $this->resource->image->size,
                    'image_url' => $this->resource->image->original_url,
                ],
                'date' => $this->resource->date,
                'time' => $this->resource->time,
                'type' => $this->resource->type,
                'price' => $this->resource->price,
                'capacity' => $this->resource->capacity,
                'available_seats' => $this->resource->available_seats,
                'location' => $this->resource->location,
                'start_date' => $this->resource->start_date,
                'end_date' => $this->resource->end_date,
                'user_id' => $this->resource->user_id,
            ],
            'url' => $this->resource->url,
        ];
    }
}
