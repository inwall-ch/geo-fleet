<?php

namespace App\Console\Commands;

use App\Domains\Logistics\Actions\UpdateVehicleLocationAction;
use App\Domains\Logistics\DTOs\UpdateLocationData;
use App\Domains\Logistics\Models\Vehicle;
use Illuminate\Console\Command;

class FleetSimulateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fleet:simulate {--cycles= : Number of cycles to run (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate vehicle movement for demonstration purposes';

    /**
     * Execute the console command.
     */
    public function handle(UpdateVehicleLocationAction $action)
    {
        $this->info('Starting GeoFleet Simulation...');
        $this->info('Press Ctrl+C to stop.');

        $vehicles = Vehicle::all();

        if ($vehicles->isEmpty()) {
            $this->error('No vehicles found. Run db:seed first!');

            return;
        }

        $cycles = $this->option('cycles') ? (int) $this->option('cycles') : null;
        $currentCycle = 0;

        while (true) {
            if ($cycles !== null && $currentCycle >= $cycles) {
                $this->info('Simulation finished.');
                break;
            }
            foreach ($vehicles as $vehicle) {
                $currentLat = $vehicle->current_location->latitude;
                $currentLng = $vehicle->current_location->longitude;

                $latChange = (rand(-10, 10) / 100000);
                $lngChange = (rand(-10, 10) / 100000);

                $newLat = $currentLat + $latChange;
                $newLng = $currentLng + $lngChange;

                $speed = rand(10, 90);

                $heading = rand(0, 360);

                $data = new UpdateLocationData(
                    latitude: $newLat,
                    longitude: $newLng,
                    speed: $speed,
                    heading: $heading
                );

                $action->execute($vehicle, $data);

                $vehicle->refresh();

                $this->line("<comment>[{$vehicle->id} {$vehicle->name}]</comment> Moved to {$newLat}, {$newLng} (Speed: {$speed} km/h)");
            }

            $this->info('--- Cycle Complete. Sleeping for 1 second ---');
            sleep(1);
            $currentCycle++;
        }
    }
}
