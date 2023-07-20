<?php

namespace App\Http\Controllers;

use App\Events\BookTicket;
use App\Models\Booking;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();

        return response()->json(['data' => $bookings]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric',
            'booking_date' => 'required|date',
            'tickets' => 'required|array',
            'tickets.*.id' => 'required|exists:tickets,id',
            'tickets.*.quantity' => 'required|integer|min:1',
        ]);

        $booking = Booking::create($validatedData);
        $booking->tickets()->attach($validatedData['tickets']);
        

        return response()->json(['data' => $booking], 201);
    }

    public function show(Booking $booking)
    {
        return response()->json(['data' => $booking]);
    }

    public function destroy(Booking $booking)
    {
        $booking->tickets()->detach();
        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully']);
    }
}
