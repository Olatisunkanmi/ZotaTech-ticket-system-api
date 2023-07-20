<?php

use App\Models\Event;
use App\Services\PaymentService;

beforeEach(function () {
    $this->paymentService = mock(PaymentService::class);
});

it('initiates a transaction', function () {
    $user = actingAs();


    $event = Event::factory()->create();
    $reference = uniqid();
    $data = [
        'amount' => fake()->numberBetween(100, 500),
        'event_id' => $event->id,
        'user_id'=> $user->id,
        'ticket_type' => 'general',
        'quantity' => 2,
        'reference' => $reference
    ];

    $response = $this->postJson(route('pay'), $data);
    $response->assertStatus(200);

    $this->assertDatabaseCount('payments',1);
    $this->assertDatabaseCount('events',1);

    $this->assertDatabaseHas('payments', [
        'amount' => $data['amount'] * 100,
        'event_id' => $data['event_id'],
        'user_id' => $data['user_id'],
        'ticket_type' => $data['ticket_type'],
        'quantity' => $data['quantity'],
        'status'=> 'pending'
    ]);

    expect($response['status'])->toBeTruthy();
    expect($response['message'])->toBe("Payment initialized");


});
