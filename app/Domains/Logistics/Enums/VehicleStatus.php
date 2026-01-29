<?php

namespace App\Domains\Logistics\Enums;

enum VehicleStatus: string
{
    case IDLE = 'idle';
    case MOVING = 'moving';
    case OFFLINE = 'offline';
}
