<?php

namespace App\Http\Controllers\api;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResources;
use App\Http\Resources\TicketResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController extends Controller
{
    public function searchEvents(string $events) : JsonResponse 
    {
        try {
            $events = Event::where('description', 'like', '%' .$events. '%')
            ->orWhere('type', 'like', '%' .$events. '%')
            ->orWhere('location', 'like', '%' .$events. '%')
            ->get();

            return response()->json([
                'message' => 'Searched events listed successfully',
                'events' => EventResources::collection($events)
            ], 200);
        } catch(\Throwable $th) {

            return response()->json([
                'message' => 'No events found'
            ], 404);
        }
    }

    public function searchTickets(Event $event, string $tickets) : JsonResponse
    {
        try {
            $tickets = DB::table('tickets')
            ->Where('ticket_type', 'like', '%' .$tickets. '%')
            ->orWhere('price', 'like', '%' .$tickets. '%')
            ->get();

            if ($tickets[0]->event_id !== $event->id ) {
                return response()->json([
                    'message' => 'ticket does not exist', 
                ], 404);
            }

            return response()->json([
                'message' => 'Searched ticket listed successfully',
                'event' => TicketResource::collection($tickets)
                
            ], 200);
        } catch(\Throwable $th) {
            throw $th;
        }
    }
}
