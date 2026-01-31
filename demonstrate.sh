#!/bin/bash

# GeoFleet Demonstration Script

echo "========================================"
echo "   GeoFleet Demonstration Started"
echo "========================================"


echo ""
echo "[2] Seeding Database (20 Vehicles)..."
./vendor/bin/sail artisan migrate:fresh --seed


echo ""
echo "[3] Testing API: Find Nearby Vehicles..."
echo "    Searching within 5km of Krasnodar center (45.0355, 38.9753)..."


RESPONSE=$(curl -s "http://localhost/api/vehicles/nearby?latitude=45.0355&longitude=38.9753&radius=5000")

COUNT=$(echo $RESPONSE | grep -o "\"id\":" | wc -l)
echo "    Found $COUNT vehicles nearby."

if [ $COUNT -gt 0 ]; then
    echo "    ✅ API Success"
else
    echo "    ❌ API Failed or No Vehicles found"
fi

echo ""
echo "[4] Starting Vehicle Simulation..."
echo "    This will run for 30 seconds to demonstrate movement."
echo "    Please open http://localhost/map in your browser to see the visualization."
echo "    Running..."

./vendor/bin/sail artisan fleet:simulate --cycles=30

echo ""
echo "========================================"
echo "   Demonstration Complete"
echo "========================================"
