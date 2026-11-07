<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peta Cabang - Google Maps</title>
    <style>
        body { margin: 0; font-family: sans-serif; }
        #map { height: 100vh; width: 100%; }
    </style>
</head>
<body>
    <button id="btn-near" style="position:absolute; z-index: 1000; top:10px; right:10px; padding: 8px 12px; cursor:pointer; background: white; border: 1px solid #ccc; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
        Cabang Terdekat
    </button>
    
    <div id="map"></div>

    <script>
        const branches = @json($branches);
        let map;
        let markers = [];
        let infoWindow;

        function initMap() {
            // Inisiasi Google Maps
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: -6.2, lng: 106.82 },
                zoom: 12,
            });
            infoWindow = new google.maps.InfoWindow();

            // Tampilkan semua cabang dari database
            branches.forEach((b, i) => {
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(b.latitude), lng: parseFloat(b.longitude) },
                    map: map,
                    title: b.nama
                });
                markers.push(marker);

                marker.addListener("click", () => {
                    infoWindow.setContent(`<b>${b.nama}</b><br>${b.alamat}`);
                    infoWindow.open(map, marker);
                });
            });
        }

        // Rumus Haversine
        function jarakKm(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const rad = d => d * Math.PI / 180;
            const dLat = rad(lat2 - lat1), dLon = rad(lon2 - lon1);
            const a = Math.sin(dLat / 2) ** 2 + Math.cos(rad(lat1)) * Math.cos(rad(lat2)) * Math.sin(dLon / 2) ** 2;
            return 2 * R * Math.asin(Math.sqrt(a));
        }

        // Fitur Cabang Terdekat
        document.getElementById('btn-near').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };

                        let best = null;
                        branches.forEach((b, i) => {
                            const d = jarakKm(pos.lat, pos.lng, b.latitude, b.longitude);
                            if (!best || d < best.d) best = { d, i, b };
                        });

                        // Marker titik pengguna (warna biru)
                        new google.maps.Marker({
                            position: pos,
                            map: map,
                            icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                            title: "Lokasi Anda"
                        });

                        // Buka popup cabang terdekat
                        const bestMarker = markers[best.i];
                        infoWindow.setContent(`<b>${best.b.nama}</b><br>Jarak: ${best.d.toFixed(2)} km`);
                        infoWindow.open(map, bestMarker);

                        // Zoom menyesuaikan lokasi pengguna dan cabang
                        const bounds = new google.maps.LatLngBounds();
                        bounds.extend(pos);
                        bounds.extend({ lat: parseFloat(best.b.latitude), lng: parseFloat(best.b.longitude) });
                        map.fitBounds(bounds);
                    },
                    () => { alert("Lokasi tidak dapat diakses. Periksa izin browser."); }
                );
            } else {
                alert("Browser Anda tidak mendukung Geolocation.");
            }
        });
    </script>
    
    <!-- Script Google Maps API milikmu -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBz4fScPshi6uFe9-mF5_X8vLiOsQ2tBKg&callback=initMap" async defer></script>
</body>
</html>