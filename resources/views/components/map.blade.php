@props(['map_id' => 'travel_map_id', 'height' => '20rem', 'width' => '100%', 'center_point', 'zoomLevel' => 13, 'markers' => []])

<div class="w-full h-full">
    <style>
        .leaflet-attribution-flag {
            display: none !important;
        }
    </style>
    <div style="width: {{ $width }}; height: {{ $height }};position: relative;">
        <div id="{{ $map_id }}" style="width: {{ $width }}; height: {{ $height }};z-index: 5;"></div>
    </div>

    <script defer>
        let mapPopup = L.popup()
        const markers = {};
        let mapCenter = [];
        let mapObjContainer;
        let mapZOOM = 13;

        function markerClicked($event, index) {
            console.log(mapObjContainer);
            console.log($event.latlng.lat, $event.latlng.lng);
        }
        function markerDragEnd($event, index) {
            console.log(mapObjContainer);
            console.log($event.target.getLatLng());
        }

        function initMap() {
            const lat = getLatitude()
            const lng = getLongitude()
            mapCenter = [lat, lng]
            mapZOOM = {!! json_encode($zoomLevel ?? 13) !!};
            const initialMarkers = {!! json_encode($markers ?? []) !!};

            mapObjContainer = L.map({{ $map_id }}).setView(mapCenter, mapZOOM);
            initMapSettings(mapObjContainer)
            mapObjContainer.on('click', onMapClick);
            if (initialMarkers) { initMarkers(initialMarkers); }
            mapObjContainer.panTo(new L.LatLng(lat, lng));

        }
        function initMarkers(initialMarkers) {
            for (let index = 0; index < initialMarkers.length; index++) {
                if (index !== 0) {
                    initMarker(initialMarkers[index])
                }
            }
        }
        function initMarker(markerData) {
            const marker = generateMarker(markerData, markerData.id);
            marker.addTo(mapObjContainer).bindPopup(
                `<div><b>${markerData.position.lat},  ${markerData.position.lng}</b></div>
                <div style="font-size: .825rem;font-weight: 600;">${markerData.title}</div>
                <div style="font-size: .725rem;font-weight: 400;">${markerData.description}</div>`);
            mapObjContainer.panTo(markerData.position);
            markers[markerData.id] = marker
        }
        function goToMapObjPoint(markerData) {
            if (!markers[markerData.id]) {
                initMarker(markerData)
            } else {
                mapObjContainer.panTo(new L.LatLng(markerData.position.lat, markerData.position.lng));
            }
        }
        function onMapClick(e) {
            mapPopup
                .setLatLng(e.latlng)
                .setContent("You clicked the map at " + e.latlng.toString())
                .openOn(mapObjContainer);
        }
        function getLatitude() {
            const latitude = localStorage.getItem('userLatitude');
            return latitude ? parseFloat(latitude) : '';
        }
        function getLongitude() {
            const longitude = localStorage.getItem('userlongitude');
            return longitude ? parseFloat(longitude) : '';
        }
        function setLatitude(latitude) {localStorage.setItem('userLatitude', latitude.toString());}
        function setLongitude(longitude) {localStorage.setItem('userlongitude', longitude.toString());}
        function setUserMapPosition(initFunc, timeOut = 100) {
            if (typeof navigator !== 'undefined' && navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const { latitude, longitude } = position.coords
                    setTimeout(function ()
                    {
                        setLatitude(latitude)
                        setLongitude(longitude)
                        if (!!initFunc) {
                            initFunc()
                        }
                    }, timeOut);
                })
            }
        }
        function initMapSettings(mapObj) {

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(mapObj);
            const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            });
            const imagens = L.tileLayer('http://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                attribution: '© Google Maps'
            });
            const menuBase = {
                "OpenStreetMap": osm,
                "Google Maps": imagens,
            };
            mapObj.addLayer(osm);
            L.control.layers(menuBase).addTo(mapObj);

        }
        function generateMarker(data, index) {
            return L.marker(data.position, {
                draggable: data.draggable
            })
                .on('click', (event) => markerClicked(event, index))
                .on('dragend', (event) => markerDragEnd(event, index));
        }

        if (!getLongitude() || !getLatitude()) {
            setUserMapPosition(initMap, 1000)
        } else {
            setTimeout(function () {initMap()}, 100);
        }

    </script>
</div>
