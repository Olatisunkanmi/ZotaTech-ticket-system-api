<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => strval($this->id),
            'type' => 'categories',
            'attributes' => [
                'name' => $this->name,
            ],
            'events' => collect($this->events)->map(function ($event) {
                return [
                    'id' => strval($event->id),
                    'description' => $event->description,
                    'title' => $event->title,
                    'images' => [
                        'file_name' => $event->image->file_name,
                        'mime_type' => $event->image->mime_type,
                        'file_size' => $event->image->size,
                        'image_url' => $event->image->original_url,
                    ],
                    'location' => $event->location,
                    'type' => $event->type,
                    'start_date' => $event->start_date,
                    'url' => [
                        'long_url' => $event->url->long_url,
                        'short_url' => $event->url->short_url,
                    ],
                ];
            }),
        ];
    }
}
