<?php

use App\Domains\Logistics\Actions\UpdateVehicleLocationAction;
use App\Domains\Logistics\DTOs\UpdateLocationData;
use App\Domains\Logistics\Enums\VehicleStatus;
use App\Domains\Logistics\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use MatanYadaev\EloquentSpatial\Objects\Point;

uses(RefreshDatabase::class);

it('updates vehicle location and logs tracking point', function () {
    // Arrange
    Redis::shouldReceive('geoAdd')
        ->once()
        ->with('geofleet:vehicles', 10.0, 20.0, \Mockery::type('int'));

    $vehicle = Vehicle::factory()->create([
        'status' => VehicleStatus::IDLE,
        'current_location' => new Point(0, 0, 4326),
    ]);

    $data = new UpdateLocationData(
        latitude: 20.0,
        longitude: 10.0,
        speed: 50.0,
        heading: 180.0
    );

    $action = new UpdateVehicleLocationAction();

    // Act
    $updatedVehicle = $action->execute($vehicle, $data);

    // Assert
    expect($updatedVehicle->current_location)->toBeInstanceOf(Point::class);
    expect($updatedVehicle->current_location->latitude)->toEqual(20.0);
    expect($updatedVehicle->current_location->longitude)->toEqual(10.0);
    expect($updatedVehicle->status)->toBe(VehicleStatus::MOVING);
    expect($updatedVehicle->last_seen_at)->not->toBeNull();

    $this->assertDatabaseHas('vehicles', [
        'id' => $vehicle->id,
        'status' => 'moving',
    ]);

    $this->assertDatabaseHas('tracking_points', [
        'vehicle_id' => $vehicle->id,
        'speed' => 50.0,
        'heading' => 180.0,
    ]);

    // Check spatial data in DB for tracking point (checking rough coordinates as exact float match can be tricky)
    $trackingPoint = $vehicle->trackingPoints()->first();
    expect($trackingPoint->location->latitude)->toEqual(20.0);
    expect($trackingPoint->location->longitude)->toEqual(10.0);
});
