<?php

use App\Models\Event;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = actingAs(factory(App\Models\User::class)->create());
});

it('creates an event with an image', function() {
    $eventData = [
        'title' => 'Test Event',
        'description' => 'This is a test event',
        'date' => '2023-07-28',
        'time' => '12:00 PM',
        'type' => 'public',
        'price' => 20.99,
        'capacity' => 100,
        'location' => 'Test Venue',
        'image' => UploadedFile::fake()->image('test_image.jpg', 600, 400),
    ];

    $response = $this->post('/events', $eventData);

    $response->assertStatus(201); 
    $this->assertDatabaseHas('events', ['title' => 'Test Event']);

    // Storage::disk('public')->assertExists('events/' . $eventData['image']->hashName());
});
