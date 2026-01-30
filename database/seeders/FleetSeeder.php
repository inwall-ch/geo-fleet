<?php

namespace Database\Seeders;

use App\Domains\Logistics\Enums\VehicleStatus;
use App\Domains\Logistics\Models\Vehicle;
use Illuminate\Database\Seeder;
use MatanYadaev\EloquentSpatial\Objects\Point;

class FleetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseLat = 45.0355;
        $baseLng = 38.9753;

        $names = [
            'Volvo FH16', 'Scania R500', 'Mercedes Actros', 'MAN TGX', 'DAF XF',
            'Renault T-Range', 'Iveco S-Way', 'Ford F-Max', 'Kamaz 54901', 'MAZ 5440',
            'Tesla Semi', 'Freightliner Cascadia', 'Peterbilt 579', 'Kenworth T680', 'Mack Anthem',
            'Isuzu Giga', 'Hino 700', 'UD Quon', 'Tata Prima', 'Ashok Leyland',
        ];

        foreach ($names as $index => $name) {
            $latOffset = (rand(-500, 500) / 10000);
            $lngOffset = (rand(-500, 500) / 10000);

            Vehicle::create([
                'name' => "{$name} - ".str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'status' => VehicleStatus::IDLE,
                'current_location' => new Point(
                    $baseLat + $latOffset,
                    $baseLng + $lngOffset,
                    4326
                ),
                'last_seen_at' => now(),
            ]);
        }
    }
}
