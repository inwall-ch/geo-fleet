#!/bin/bash

# GeoFleet Demonstration Script

echo "========================================"
echo "   GeoFleet Demonstration Started"
echo "========================================"

# 1. Check status
# echo ""
# echo "[1] Checking Environment..."
# ./vendor/bin/sail ps

# 2. Seed Database
echo ""
echo "[2] Seeding Database (20 Vehicles)..."
./vendor/bin/sail artisan migrate:fresh --seed

# 3. Start Reverb (in background if not running, but user usually runs it separately)
# We assume the user has run 'reverb:start' in another terminal as per instructions

# 4. Demonstrate API: Nearby Vehicles
echo ""
echo "[3] Testing API: Find Nearby Vehicles..."
echo "    Searching within 5km of Krasnodar center (45.0355, 38.9753)..."

# Pick a vehicle created by seeder to verify
# We know seeder creates vehicles around base coordinates.
RESPONSE=$(curl -s "http://localhost/api/vehicles/nearby?latitude=45.0355&longitude=38.9753&radius=5000")

# Count items in JSON array (simple grep count)
COUNT=$(echo $RESPONSE | grep -o "\"id\":" | wc -l)
echo "    Found $COUNT vehicles nearby."

if [ $COUNT -gt 0 ]; then
    echo "    ✅ API Success"
else
    echo "    ❌ API Failed or No Vehicles found"
fi

# 5. Start Simulation
echo ""
echo "[4] Starting Vehicle Simulation..."
echo "    This will run for 30 seconds to demonstrate movement."
echo "    Please open http://localhost/map in your browser to see the visualization."
echo "    Running..."

# Run simulation for a limited time using --cycles
./vendor/bin/sail artisan fleet:simulate --cycles=30

echo ""
echo "========================================"
echo "   Demonstration Complete"
echo "========================================"
