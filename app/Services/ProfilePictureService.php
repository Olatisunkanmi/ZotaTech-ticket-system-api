<?php 

namespace App\Services;

use App\Models\User;

class ProfilePictureService
{
    public function assignRandomProfilePicture(User $user)
    {
        $randomKeyword = ['nature', 'city', 'people', 'food', 'travel'];
        $randomKeyword = $randomKeyword[array_rand($randomKeyword)];
        $randomPictureURL = "https://source.unsplash.com/400x400/?{$randomKeyword}";
    
        $user->addMediaFromUrl($randomPictureURL)
             ->toMediaCollection('avatars');
    }
}