<?php

namespace App\Events;

use App\Domains\Logistics\Models\TrackingPoint;
use App\Domains\Logistics\Models\Vehicle;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VehicleMoved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Vehicle $vehicle)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('presence-global-map'),
        ];
    }

    public function broadcastWith(): array
    {
        /** @var TrackingPoint|null $latestPoint */
        $latestPoint = $this->vehicle->trackingPoints()->latest()->first();

        return [
            'id' => $this->vehicle->id,
            'name' => $this->vehicle->name,
            'coordinates' => [
                'lat' => $this->vehicle->current_location->latitude,
                'lng' => $this->vehicle->current_location->longitude,
            ],
            'speed' => $latestPoint ? $latestPoint->speed : 0,
            'heading' => $latestPoint ? $latestPoint->heading : 0,
            'status' => $this->vehicle->status->value,
        ];
    }
}
