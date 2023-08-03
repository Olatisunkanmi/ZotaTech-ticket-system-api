<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;



class User extends Authenticatable implements HasMedia
{
    use HasApiTokens,  HasFactory, Notifiable, HasUlids, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'confirm_password',
        'subaccount_code',
        'profile_picture'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'confirm_password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'confirm_password' => 'hashed'
    ];

    /**
     * Get the events for the user.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }


    /**
     * Generate a role based token for the user
     * 
     */
    public function generateUserRole(): string
    {
        $role = [];

        if (Str::contains($this->email, 'zojatech.com')) {
            $role = ['admin'];
        } else {
            $role = ['user'];
        }

        return $this->createToken($this->email, $role)->accessToken;
    }

    // public function registerMediaCollection(): void
    // {
    //     $this->addMediaCollection('profile_picture');
    // }

    public function profile_picture(): Attribute
    {
        return Attribute::make(get: fn () => $this->getFirstMedia('profile_picture') ?: null);
    }
}
