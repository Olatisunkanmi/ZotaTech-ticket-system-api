<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\{AuthController, UserController, EventController, PaymentController, RedirectController,TicketController}
;
use App\Models\User;
use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix('v1')->group(function () {

    /// Declare the heartbeat route for the API
    Route::any('/', function () {
        return response()->json(['message' => 'Welcome to Open Tickets Apis'], 200);
    })->name('welcome');

    

   


    // Declare unauthenticated routes
    Route::group(['middleware' => 'guest'], function () {

        // Place your unauthenticated routes here
        Route::post('register', [AuthController::class, 'register'])->name('register');

        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::post('users/{id}', [UserController::class, 'show'])->name('show');

        Route::get('events', [EventController::class, 'index'])->name('index');

        Route::get('events/{slug}', [EventController::class, 'slug'])->name('slug');

        Route::post('events/{id}', [EventController::class, 'show'])->name('showEvent');

        Route::get('e/{shortlink}', [EventController::class, 'redirect'])->name('redirect');

        Route::get('verifyTransaction', [PaymentController::class, 'verifyTransaction'])->name('verifyTransaction');

        Route::get('tickets/{id}', [TicketController::class, 'show']);

        Route::get('search/events/filter', [EventController::class, 'searchEvents'])->name('searchEvents');

        Route::get('search/tickets/filter', [TicketController::class, 'searchTickets'])->name('searchTickets');

    });


    //Declare Authenticated routes
    Route::group(['middleware' => 'auth:api'], static function () {

        //User routes
        Route::prefix('users')->middleware(['role:user'])->group(function () {
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('indexUser');
            Route::put('/{id}', [UserController::class, 'update'])->name(' update');
        });


        //Admin routes
        Route::prefix('admin')->middleware(['role:admin'])->group(function () {
            Route::apiResource('users', UserController::class)->except(['update', 'destroy']);

            Route::apiResource('events', EventController::class)->except(['show', 'slug', 'redirect']);

            Route::get('/tickets', [TicketController::class, 'index']);
        });


        //Events routes
        Route::prefix('events')->group(function (){
            Route::post('/', [EventController::class, 'store'])->name('store');

            
            Route::group(['middleware' => 'isOwner'], function () {
                Route::put('/{id}', [EventController::class, 'update'])->name('update');
                Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');
            });
        });

        //Tickets Route
        Route::prefix('tickets')->group(function (){
            Route::post('/', [TicketController::class, 'store'])->name('storeticket');
            Route::post('pay', [PaymentController::class, 'makePayment'])->name('pay');

            
            Route::group(['middleware' => 'ticketOwner'], function () {
                Route::put('/{id}', [TicketController::class, 'update'])->name('updateTicket');
                Route::delete('/{id}', [TicketController::class, 'destroy'])->name('destroyTicket');
            });
        });
        
        //Tickets Routes
    //Route to Create a new ticket for an event.
    Route::post('/events/{event}/tickets', [TicketController::class, 'store'])->name('store');
    
    //Route to show that a ticket belongs to a specific event or Retrieve details of a specific ticket of an event.
    Route::get('/events/{event}/tickets/{ticket}', [TicketController::class, 'validateEventTicket'])->name('validateEventTicket');
    // Route to Update details of a specific ticket of an event.
    Route::put('/events/{event}/tickets/{ticket}', [TicketController::class, 'updatespecificticket'])->name('updateSpecificTicket');
    //Route to Delete a specific ticket.
    Route::delete('/events/{event}/tickets/{ticket}', [TicketController::class, 'deleteSpecificTicket'])->name('deleteSpecificTicket');
    
    });
});
