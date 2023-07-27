<?php

namespace App\Models;

use App\Helper\Helper;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasOne};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class  Event extends Model implements HasMedia
{
    use HasFactory, HasUlids, InteractsWithMedia;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'image',
        'date',
        'time',
        'type',
        'price',
        'capacity',
        'available_seats',
        'location',
        'start_date',
        'end_date',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];


    /**
     * Get the image for the event.
     */
    public function image(): Attribute
    {
        return Attribute::make(get: fn () => $this->getFirstMedia('image') ?: null);
    }


    /**
     * Get the user that owns the event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get the url details for the event.
     */

    public function url(): HasOne
    {
        return $this->hasOne(Url::class);
    }

    public function category(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_event', 'event_id', 'category_id');
    }

 

    public static function booted()
    {
        parent::booted();
        static::created(function (Event $event) {
            $helper = app(Helper::class);
            $url_data = $helper->generateLink($event->title);
            $event->url()->create([
                'long_url' => $url_data['long_url'],
                'short_id' => $url_data['short_id'],
                'short_url' => $url_data['short_url'],
                'clicks' => 0,
            ]);
        });
    }
}
