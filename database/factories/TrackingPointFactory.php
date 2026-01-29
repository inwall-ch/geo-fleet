<?php

namespace Database\Factories;

use App\Domains\Logistics\Models\TrackingPoint;
use App\Domains\Logistics\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domains\Logistics\Models\TrackingPoint>
 */
class TrackingPointFactory extends Factory
{
    protected $model = TrackingPoint::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_id' => Vehicle::factory(),
            'location' => new Point(0, 0, 4326),
            'speed' => rand(0, 120),
            'heading' => rand(0, 360),
        ];
    }
}
