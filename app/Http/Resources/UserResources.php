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
            'profile_picture' => $this->getFirstMediaUrl('avatars')
            // 'profile_picture' => $this->profile_picture
            // 'profile_picture' => [
            //     'picture' => $this->profile_picture->file_name,
            //     'mime_type' => $this->profile_picture->mime_type,
            //     'size' => $this->profile_picture->size,
            //     'original_url' => $this->profile_picture->original_url
            // ]
            // 'events' => collect($this->events)->map(function ($event) {
            //     return new EventResources($event);
            // }),
        ];
    }
}
