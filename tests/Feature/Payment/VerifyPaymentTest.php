<?php

use App\Http\Controllers\api\PaymentController;
use Illuminate\Http\JsonResponse;
use App\Models\Event;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Request;
use Mockery;

beforeEach(function () {
    $this->paymentService = mock(PaymentService::class);
    $this->paymentController = new PaymentController();
});

it('verifies a successful transaction', function () {
    $user = actingAs();
    $request = Request::create('/api/verify-transaction', 'POST', ['reference' => 'abc123']);
    $payment = Payment::factory()->create(['reference' => 'abc123', 'status' => 'pending']);

    $this->paymentService
        ->shouldReceive('verifyTransaction')
        ->with('abc123')
        ->andReturn(['status' => true]);

    $response = $this->paymentController->verifyTransaction($request);


    expect($response)->toBeInstanceOf(JsonResponse::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getData()->message)->toBe('Payment Successful. Ticket booked');



});

