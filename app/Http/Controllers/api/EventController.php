<?php

namespace App\Http\Controllers\api;

use App\Helper\Helper;
use App\Models\{Event, Url, Category};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\EventResources;
use App\Http\Requests\EventRequest;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{Auth, Cache};
use Illuminate\Support\Js;

class EventController extends Controller
{
    
    public function index(): JsonResponse
    {
        try {
            $events = Helper::saveToCache('events', Event::latest()->paginate(), now()->addHour());



            return response()->json([
                'message' => 'Events retrieved successfully',
                'data' => EventResources::collection($events)
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Events not found'
            ], 404);
        }
    }

    public function slug(string $slug): JsonResponse
    {
        try {
            //code...
            $url_id = explode('-', $slug)[6];
            $data = Url::where('short_id', $url_id)->first();

            if (!$data) {
                return response()->json([
                    'message' => 'Event not found'
                ], 404);
            }

            $id = (int)$data->event_id;
            $cachedEvent = Helper::getFromCache('events', $id);

            if ($cachedEvent) {
                $event = $cachedEvent;
            } else {
                $event = Event::findOrFail($id);
            }

           
            Helper::updateCache('events', $event->id, $event, now()->addHour());

            return response()->json([
                'message' => 'Event retrieved successfully',
                'data' => new EventResources($event)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }
    }


    public function show(string $id): JsonResponse
    {
        try {
            $cachedEvent = Helper::getFromCache('events', $id);

            if ($cachedEvent) {
                $event = $cachedEvent;
            } else {
                $event = Event::findOrFail($id);
                $event = Helper::saveToCache('events' . $event->id, $event, now()->addHour());
            }

            Helper::updateEventClicks($event);
            Helper::updateCache('events', $event->id, $event, now()->addHour());

            return response()->json([
                'message' => 'Event retrieved successfully',
                'data' => new EventResources($event)
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }
    }
   
    public function store(EventRequest $request): JsonResponse
    {

        $data = Event::create(array_merge(
            $request->validated(),
            [
                'user_id' => Auth::user()->id,
            ]
        ));


        $category = Category::where('name', $request->category)->first();


        $category->attachEvents($data->id);


        if ($request->hasFile('image') && !empty($request->image)) {
            $data->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return response()->json([
            'message' => 'Event created successfully',
            'event' => new EventResources($data),
        ], 201);
    }

   

    public function redirect($short_id): JsonResponse
    {
        $url = Url::where('short_id', $short_id)->first();

        if (!$url) {
            return response()->json([
                'message' => 'Url not found'
            ], 404);
        }

        return redirect($url->long_url);
    }

    
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            //code...
            $cachedEvent = Helper::getFromCache('events', $id);

            if ($cachedEvent) {
                $event = $cachedEvent;
            } else {
                $event = Event::findOrFail($id);
            }

            $event->update($request->all());
            Helper::updateCache('events', $id, $event, now()->addHour());


            return response()->json([
                'message' => 'Event created successfully',
                'event' => new EventResources($event),
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }
    }


  
    public function destroy(string $id): JsonResponse
    {
        try {
            //code...
            $event =  Cache::get('event:' . $id);

            if ($event) {
                Cache::forget('event:' . $id);
            }

            if (!$event) {
                $event = Event::find($id);

                if ($event) {
                    $event->delete();
                }
            }

            return response()->json([
                'message' => 'Event deleted successfully'
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }
    }
}
