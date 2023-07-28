<?php

use App\Models\Event;

it('updates an event', function() {
    // Create an event using factory
    $event = Event::factory()->create();

    // Define the updated event data
    $updatedEventData = [
        'title' => 'Updated Event',
        'description' => 'This is the updated event description',
        'date' => '2023-07-30',
        'time' => '2:00 PM',
        'type' => 'private',
        'price' => 25.99,
        'capacity' => 150,
        'location' => 'Updated Venue',
        // Add the image to the updated event data (if needed)
        // 'image' => UploadedFile::fake()->image('updated_image.jpg', 600, 400),
    ];

    // Send a PUT request to update the event
    $response = $this->put("/events/{$event->id}", $updatedEventData);

    // Assert the response
    $response->assertStatus(200); // Assuming you return 200 for successful event update
    $this->assertDatabaseHas('events', ['title' => 'Updated Event']); // Assuming you have an 'events' table and title field

    // If the image is updated, assert that the new image is stored in the storage system (assuming you are using the public disk)
    if (isset($updatedEventData['image'])) {
        Storage::disk('public')->assertExists('events/' . $updatedEventData['image']->hashName());
    }
});
