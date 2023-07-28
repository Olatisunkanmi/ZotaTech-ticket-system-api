<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User */
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
            'id' => strval($this->id),
            'type' => 'users',
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            // 'events' => collect($this->events)->map(function ($event) {
            //     return new EventResources($event);
            // }),
        ];
    }
}
