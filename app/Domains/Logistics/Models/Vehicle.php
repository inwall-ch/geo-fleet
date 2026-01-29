<?php

namespace App\Domains\Logistics\Models;

use App\Domains\Logistics\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
