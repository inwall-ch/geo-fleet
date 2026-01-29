<?php

namespace App\Domains\Logistics\DTOs;

use Spatie\LaravelData\Data;

class UpdateLocationData extends Data
{
    public function __construct(
        public float $latitude,
        public float $longitude,
        public float $speed,
        public float $heading,
    ) {}
}
