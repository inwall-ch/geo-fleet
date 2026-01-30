<?php

use App\Domains\Logistics\Models\Vehicle;
use App\Events\VehicleMoved;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redis;

uses(RefreshDatabase::class);

test('it updates vehicle location and broadcasts event', function () {
    Event::fake();
    Redis::shouldReceive('geoAdd')->once();

    $vehicle = Vehicle::factory()->create();

    $payload = [
        'latitude' => 40.7128,
        'longitude' => -74.0060,
        'speed' => 60.5,
        'heading' => 90.0,
    ];

    $response = $this->postJson("/api/vehicles/{$vehicle->id}/location", $payload);

    $response->assertOk();

    $this->assertDatabaseHas('tracking_points', [
        'vehicle_id' => $vehicle->id,
        'speed' => 60.5,
        'heading' => 90.0,
    ]);

    Event::assertDispatched(VehicleMoved::class, function ($event) use ($vehicle) {
        return $event->vehicle->id === $vehicle->id;
    });
});
