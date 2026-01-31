<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoFleet Real-time Map</title>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <!-- Tailwind CSS (for basic styling) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        #map { height: 100vh; width: 100%; }
    </style>
</head>
<body class="bg-gray-100">

    <div id="map"></div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <!-- Laravel Echo & Pusher -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>

    <script>
        // Use default Pusher log for debugging
        Pusher.logToConsole = true;
    </script>

    <script>
        // 1. Initialize Map
        const map = L.map('map').setView([45.0355, 38.9753], 13); // Krasnodar

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // 2. Setup Echo
        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ env('VITE_REVERB_APP_KEY') }}',
            wsHost: '{{ env('VITE_REVERB_HOST') }}',
            wsPort: {{ env('VITE_REVERB_PORT') ?? 80 }},
            wssPort: {{ env('VITE_REVERB_PORT') ?? 443 }},
            forceTLS: ('{{ env('VITE_REVERB_SCHEME') }}' === 'https'),
            enabledTransports: ['ws', 'wss'],
        });

        // 3. Vehicle Markers Store
        const markers = {};

        // 4. Custom Icon
        const truckIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/741/741407.png', // Simple truck icon
            iconSize: [32, 32],
            iconAnchor: [16, 16],
            popupAnchor: [0, -16]
        });

        console.log('Connecting to global-map...');

        // 5. Listen to Channel
        window.Echo.channel('global-map')
            .listen('VehicleMoved', (e) => {
                console.log('VehicleMoved:', e);
                updateMarker(e);
            })
            .error((error) => {
                console.error('Echo Error:', error);
            });

        function updateMarker(data) {
            const { id, name, coordinates, speed, heading, status } = data;
            const lat = coordinates.lat;
            const lng = coordinates.lng;

            if (markers[id]) {
                // Update existing marker
                const marker = markers[id];
                marker.setLatLng([lat, lng]);
                marker.getPopup().setContent(buildPopupContent(name, speed, status));
                
                // Optional: Rotate icon based on heading (requires plugin or CSS transform, skipping for simplicity)
            } else {
                // Create new marker
                const marker = L.marker([lat, lng], { icon: truckIcon })
                    .addTo(map)
                    .bindPopup(buildPopupContent(name, speed, status));
                
                markers[id] = marker;
            }
        }

        function buildPopupContent(name, speed, status) {
            let statusColor = status === 'moving' ? 'text-green-600' : 'text-gray-600';
            return `
                <div class="text-center">
                    <strong class="text-lg block mb-1">${name}</strong>
                    <div class="text-sm">Speed: <strong>${Math.round(speed)} km/h</strong></div>
                    <div class="text-xs uppercase font-bold ${statusColor}">${status}</div>
                </div>
            `;
        }
    </script>
</body>
</html>
