<?php

use App\Domains\Logistics\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;

uses(RefreshDatabase::class);

test('fleet simulation command runs for limited cycles', function () {
    Redis::shouldReceive('geoAdd')->byDefault();

    Vehicle::factory()->count(3)->create();

    $this->artisan('fleet:simulate', ['--cycles' => 1])
        ->expectsOutput('Starting GeoFleet Simulation...')
        ->expectsOutput('Press Ctrl+C to stop.')
        ->expectsOutput('Simulation finished.')
        ->assertExitCode(0);
});

test('fleet simulation command fails without vehicles', function () {
    $this->artisan('fleet:simulate')
        ->expectsOutput('Starting GeoFleet Simulation...')
        ->expectsOutput('Press Ctrl+C to stop.')
        ->expectsOutput('No vehicles found. Run db:seed first!')
        ->assertExitCode(0);
});
