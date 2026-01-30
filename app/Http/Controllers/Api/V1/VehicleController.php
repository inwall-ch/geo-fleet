<?php

namespace App\Http\Controllers\Api\V1;

use App\Domains\Logistics\Actions\FindNearbyVehiclesAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function nearby(Request $request, FindNearbyVehiclesAction $action)
    {
        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['required', 'numeric', 'min:0'],
        ]);

        $vehicles = $action->execute(
            (float) $validated['latitude'],
            (float) $validated['longitude'],
            (float) $validated['radius']
        );

        return response()->json($vehicles);
    }
}
