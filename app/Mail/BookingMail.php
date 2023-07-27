<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public Ticket $ticket 
    )
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have successfully booked your ticket',
            from: "zojatix@gmail.com"
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.PurchaseMail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $data =[
            'name'=> $this->user->name,
            'event' => $this->ticket->event?->title,
            'date' => $this->ticket->event?->start_date,
            'time' => $this->ticket->event?->time,
            'quantity' => $this->ticket->quantity,
            'id' => $this->ticket->id,
            'eventOwner'=>$this->ticket->event->user->name,
            'location'=>$this->ticket->event->location,
            'link' => config('app.url').'/api/v1/tickets/'.$this->ticket->id,
        ];
        $pdf = Pdf::loadView('ticket', $data);
        return [Attachment::fromData(fn () => $pdf->output(), 'ticket.pdf')];
    }
}
