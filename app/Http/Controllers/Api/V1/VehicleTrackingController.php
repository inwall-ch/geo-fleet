<?php

namespace App\Http\Controllers\Api\V1;

use App\Domains\Logistics\Actions\UpdateVehicleLocationAction;
use App\Domains\Logistics\DTOs\UpdateLocationData;
use App\Domains\Logistics\Models\Vehicle;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class VehicleTrackingController extends Controller
{
    public function update(Request $request, Vehicle $vehicle, UpdateVehicleLocationAction $action)
    {
        $data = UpdateLocationData::from($request->all());

        $action->execute($vehicle, $data);

        return response()->json(['message' => 'Location updated successfully.']);
    }
}
