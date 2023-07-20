<?php

namespace App\Listeners;

use App\Mail\BookingMail;
use App\Events\BookTicket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Ticket;

class BookingListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookTicket $event): void
    {
        //
        Mail::to($event->user->email)->send(new BookingMail($event->user, $event->ticket));

    }
}
