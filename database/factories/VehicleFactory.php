<?php

namespace Database\Factories;

use App\Domains\Logistics\Enums\VehicleStatus;
use App\Domains\Logistics\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domains\Logistics\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => Str::random(10),
            'status' => VehicleStatus::IDLE,
            'current_location' => new Point(0, 0, 4326),
            'last_seen_at' => now(),
        ];
    }
}
