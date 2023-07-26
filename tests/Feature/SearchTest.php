<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


use Pest\Laravel\{assertDatabaseHas, assertDatabaseMissing, assertDatabaseCount};

it('Search event', function () {

    $data = [
        "description" => 'sd',
        "location" => 'avenue'
    ];

    $response = $this->getJson(route('/search/{events}', ['events' => $data]));
    $data = $response->json('events');

    //Assertions
    $this->assertDatabaseHas('events', $data);
    $response->assertStatus(200);
    
});
