@props(['map_id' => 'get_create_coordinates_map_id', 'travel_id', 'height' => '20rem', 'width' => '100%', 'zoomLevel' => 13, ])

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
        let mapCreateGetCoordinates;
        let mapCreateGetCoordinatesID;
        let mapCreateGetCoordinatesActiveMarker;

        function initCreateGetCoordinatesMap() {
            const center = [getLatitude(), getLongitude()];
            const zoom = {!! json_encode($zoomLevel ?? 13) !!};
            mapCreateGetCoordinatesID = {!! json_encode($map_id ?? 'get_create_coordinates_map_id') !!};
            mapCreateGetCoordinates = L.map(mapCreateGetCoordinatesID).setView(center, zoom);
            initMapSettings(mapCreateGetCoordinates)
            mapCreateGetCoordinates.on('click', onCreateGetCoordinatesMapClick);
        }
        function mapCreateInvalidateSize() {
            setTimeout(() => mapCreateGetCoordinates.invalidateSize(), 10);
        }
        function initCreateGetCoordinatesMarker(markerData) {
            if (!!mapCreateGetCoordinatesActiveMarker) {
                mapCreateGetCoordinates.removeLayer(mapCreateGetCoordinatesActiveMarker);
            }
            const marker = generateMarker(markerData, markerData.id);
            marker.addTo(mapCreateGetCoordinates).bindPopup(
                `<div><b>${markerData.position.lat},  ${markerData.position.lng}</b>`);
            mapCreateGetCoordinates.panTo(markerData.position);
            mapCreateGetCoordinatesActiveMarker = marker;
            const create_address_latitude_input = document.querySelector('#create_address_latitude_input')
            if (create_address_latitude_input) {
                create_address_latitude_input.value = markerData.position.lat
            }
            const create_address_longitude_input = document.querySelector('#create_address_longitude_input')
            if (create_address_longitude_input) {
                create_address_longitude_input.value = markerData.position.lng
            }
        }
        function onCreateGetCoordinatesMapClick(e) {
            initCreateGetCoordinatesMarker({
                position: {
                    lat: e.latlng.lat,
                    lng: e.latlng.lng,
                 },
                draggable: false,
            })
        }

        if (!getLongitude() || !getLatitude()) {
            setUserMapPosition(initCreateGetCoordinatesMap, 1000)
        } else {
            setTimeout(function () {initCreateGetCoordinatesMap()}, 100);
        }

    </script>
</div>
