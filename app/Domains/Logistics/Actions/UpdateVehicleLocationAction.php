<?php

namespace App\Domains\Logistics\Actions;

use App\Domains\Logistics\DTOs\UpdateLocationData;
use App\Domains\Logistics\Enums\VehicleStatus;
use App\Domains\Logistics\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use MatanYadaev\EloquentSpatial\Objects\Point;

class UpdateVehicleLocationAction
{
    public function execute(Vehicle $vehicle, UpdateLocationData $data): Vehicle
    {
        $point = new Point($data->latitude, $data->longitude, 4326);

        return DB::transaction(function () use ($vehicle, $data, $point) {
            // Update Vehicle
            $vehicle->update([
                'current_location' => $point,
                'status' => $data->speed > 0 ? VehicleStatus::MOVING : VehicleStatus::IDLE,
                'last_seen_at' => now(),
            ]);

            // Log Tracking Point
            $vehicle->trackingPoints()->create([
                'location' => $point,
                'speed' => $data->speed,
                'heading' => $data->heading,
            ]);

            // Update Redis Geo
            Redis::geoAdd('geofleet:vehicles', $data->longitude, $data->latitude, strval($vehicle->id));

            return $vehicle;
        });
    }
}
