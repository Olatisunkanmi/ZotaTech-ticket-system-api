<?php

namespace App\Http\Controllers\api;

use App\Events\BookTicket;
use App\Http\Requests\PaymentRequest;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class PaymentController extends Controller
{
    //
    public PaymentService $paymentService;
    public function __construct()
    {

        $this->paymentService = new PaymentService();
    }


    public function makePayment(PaymentRequest $request): JsonResponse
    {
        $user = auth()->user();
        $ref = uniqid();
        $data = [
            'amount' => $request->amount * 100,
            'email' => $user->email,
            'user_id' => $user->id,
            'event_id' => $request->event_id,
            'ticket_type' => $request->ticket_type,
            'quantity' => $request->quantity,
            'reference' => $ref,
            'callback_url' => route('verifyTransaction')
        ];
        Payment::create(Arr::except($data, ['callback_url', 'email']));

        $result = $this->paymentService->initializeTransaction($data);

        return response()->json([
            "message" => "Payment initialized",
            "data" =>  $result,
            'status' => 200
        ]);
    }

    public function verifyTransaction(Request $request): JsonResponse
    {
        try {
            $response =  $this->paymentService->verifyTransaction($request->reference);

            if ($response['status'] == true) {
                $payment = Payment::where('reference', $request->reference)->first();
                if ($payment->status == 'successful') {
                    return response()->json([
                        "message" => "Payment already verified",
                        "data" =>  $payment,
                        'status' => 200
                    ]);
                }

                $payment->status = 'successful';
                $payment->save();

                $ticket_data = collect($payment)->except('id')->toArray();

                $ticket = Ticket::create($ticket_data);
                $event = $ticket->event;
                $event->available_seats -= $ticket->quantity;
                $event->save();

                $user = User::findorfail($ticket->user_id);

                event(new BookTicket($user, $ticket));

                return response()->json([
                    "message" => "Payment Successful. Ticket booked",
                    "data" =>  $ticket,
                    'status' => 200
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Reference code invalid",
            ], 302);
        }

        return response()->json([
            "message" => " Payament failed",
            'status' => 400
        ]);
    }
}
