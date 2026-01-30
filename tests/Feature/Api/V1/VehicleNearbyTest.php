<?php

use App\Domains\Logistics\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MatanYadaev\EloquentSpatial\Objects\Point;

uses(RefreshDatabase::class);

test('it finds nearby vehicles within radius', function () {
    // Reference point: New York City (40.7128, -74.0060)
    $lat = 40.7128;
    $lng = -74.0060;

    // Vehicle A: Very close (approx 100m away)
    // 0.001 degrees lat is approx 111m. So 0.0009 is roughly 100m.
    $vehicleNear = Vehicle::factory()->create([
        'name' => 'Near Vehicle',
        'current_location' => new Point($lat + 0.0009, $lng, 4326),
    ]);

    // Vehicle B: Far away (approx 10km away)
    // 0.1 degrees is approx 11km.
    $vehicleFar = Vehicle::factory()->create([
        'name' => 'Far Vehicle',
        'current_location' => new Point($lat + 0.1, $lng, 4326),
    ]);

    // Search with 5000m radius (5km)
    $response = $this->getJson("/api/vehicles/nearby?latitude={$lat}&longitude={$lng}&radius=5000");

    $response->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(['name' => 'Near Vehicle'])
        ->assertJsonMissing(['name' => 'Far Vehicle']);

    // Check if distance is calculated
    $data = $response->json();
    expect($data[0]['distance'])->toBeLessThan(200); // Should be around 100m
});

test('it validates input parameters', function () {
    $response = $this->getJson('/api/vehicles/nearby');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['latitude', 'longitude', 'radius']);
});
