<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => strval($this->resource->id),
            'type' => 'users',
            'attributes' => [
                'name' => $this->resource->name,
                'email' => $this->resource->email,
                'phone_number' => $this->resource->phone_number,
                'created_at' => $this->resource->created_at,
                'updated_at' => $this->resource->updated_at,
            ],
            'events' => collect($this->resource->events)->map(function ($event) {
                return new EventResources($event);
            }),
        ];
    }
}
