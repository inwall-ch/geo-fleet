<?php

namespace App\Domains\Logistics\Models;

use App\Domains\Logistics\Enums\VehicleStatus;
use Database\Factories\VehicleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Vehicle extends Model
{
    use HasFactory;
    use HasSpatial;

    protected $guarded = [];

    protected $casts = [
        'status' => VehicleStatus::class,
        'current_location' => Point::class,
        'last_seen_at' => 'datetime',
    ];

    public function trackingPoints(): HasMany
    {
        return $this->hasMany(TrackingPoint::class);
    }

    protected static function newFactory()
    {
        return VehicleFactory::new();
    }
}
