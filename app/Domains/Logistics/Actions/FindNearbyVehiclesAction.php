<?php

namespace App\Domains\Logistics\Actions;

use App\Domains\Logistics\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;

class FindNearbyVehiclesAction
{
    /**
     * @return Collection<int, Vehicle>
     */
    public function execute(float $latitude, float $longitude, float $radiusMeters): Collection
    {
        return Vehicle::query()
            ->select('vehicles.*')
            ->selectRaw(
                'ST_Distance(current_location, ST_Point(?, ?, 4326)::geography) as distance',
                [$longitude, $latitude]
            )
            ->whereRaw(
                'ST_DWithin(current_location, ST_Point(?, ?, 4326)::geography, ?)',
                [$longitude, $latitude, $radiusMeters]
            )
            ->orderBy('distance')
            ->get();
    }
}
