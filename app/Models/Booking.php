<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, BelongsTo};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'booking_date',
    ];
    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class)->withPivot('quantity');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
