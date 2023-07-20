<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketCollection;
use App\Events\BookTicket;
use App\Http\Resources\TicketResource;
use App\Models\Event;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TicketController extends Controller
{
    public function index()
    {
        return TicketCollection::collection(Ticket::paginate(10));
       
    }

    public function store(TicketRequest $request, Event $event) : JsonResponse
    {

        $data = array_merge($request->validated(), ['user_id' => Auth::user()->id]);
       
        
        $ticket = Ticket::create($data);
        $event = $ticket->event;
        $event->available_seats -= $ticket->quantity;
        $event->save();

        $user = User::findorfail($ticket->user_id);

        event(new BookTicket($user, $ticket));

        return response()->json([
            'data' => new TicketResource($ticket)
            
        ], Response::HTTP_CREATED);
    }

    public function show(string $id):JsonResponse
    {
        try{
            $ticket = Ticket::findOrFail($id);
    
            return response()->json([
                'Message' => 'Ticket Found',
                'data' => new TicketResource($ticket),
            ],Response::HTTP_OK);

        }catch(\Throwable $th){
            return response()->json([
                'Message'=> 'Ticket not Found',
            ], Response::HTTP_NOT_FOUND);
        }
        
    }
    public function validateEventTicket(Event $event, Ticket $ticket):JsonResponse
    {
        if ($ticket->event_id !== $event->id){
            return response()->json(['error' => 'Ticket not found for the specified event'], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'Message'=>'Ticket found for the specified event',
            'data' => $ticket], Response:: HTTP_FOUND);
    }

    public function update(Request $request, Ticket $ticket):JsonResponse
    {
        try{
            $ticket=Ticket::findOrFail($ticket);

            $ticket->update($request->all());

            return response()->json([
                'data' =>new TicketResource($ticket)
            ], Response::HTTP_OK);

        }catch(\Throwable $th){
            return response()->json([
                'data'=>'Ticket not Found'
            ], Response::HTTP_NOT_FOUND);
        }
        

        
    }
    public function updateSpecificTicket(TicketRequest $request, Event $event, Ticket $ticket):JsonResponse
    {
        if ($ticket->event_id !== $event->id) {
            return response()->json(['error' => 'Ticket not found for the specified event'], Response::HTTP_NOT_FOUND);
        }
        $validatedData = $request->validate([
            'ticket_type' => 'required',
            'price' => 'numeric',
            'quantity' => 'integer|min:1',
        ]);
        $ticket->update($validatedData);

        return response()->json([
            'Message'=>'Ticket Updated Successfully',
            'data' => $ticket], Response::HTTP_OK);
    }

    public function destroy(Ticket $ticket):JsonResponse
    {
        try{
            $ticket = Ticket::findOrFail($ticket);

            $ticket->delete();
            return response()->json([
                'Message' => 'Ticket deleted successfully'
            ], Response::HTTP_OK);
        }catch(\Throwable $th){
            return response()->json([
                'Message' => 'Ticket not Found'
            ], Response::HTTP_NOT_FOUND);
        }
       
    }
    public function deleteSpecificTicket(Event $event, Ticket $ticket)
    {
        if ($ticket->event_id !== $event->id) {
            return response()->json(['error' => 'Ticket not found for the specified event'], Response::HTTP_NOT_FOUND);
        }
    
        $ticket->delete();
    
        return response()->json(['message' => 'Ticket deleted successfully'], Response::HTTP_OK);
    }
}
