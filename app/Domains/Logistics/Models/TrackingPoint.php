<?php

namespace App\Domains\Logistics\Models;

use Database\Factories\TrackingPointFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class TrackingPoint extends Model
{
    use HasFactory;
    use HasSpatial;

    const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'location' => Point::class,
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    protected static function newFactory()
    {
        return TrackingPointFactory::new();
    }
}
