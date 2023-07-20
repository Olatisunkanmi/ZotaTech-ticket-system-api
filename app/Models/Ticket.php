<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Ticket extends Model

{
    use HasFactory, HasUlids;
    protected $fillable = [
        'user_id',
        'ticket_id',
        'event_id',
        'user_id',
        'ticket_type',
        'amount',
        'quantity',
    ];
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /*public function bookings()
    {
        return $this->belongsToMany(Booking::class)->withPivot('quantity');
    }*/
}
