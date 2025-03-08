@props(['map_id' => 'get_update_coordinates_map_id', 'travel_id', 'height' => '20rem', 'width' => '100%', 'zoomLevel' => 13, ])

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
        let mapUpdateGetCoordinates;
        let mapUpdateGetCoordinatesID;
        let mapUpdateGetCoordinatesActiveMarker;

        function initUpdateGetCoordinatesMap() {
            const center = [getLatitude(), getLongitude()];
            const zoom = {!! json_encode($zoomLevel ?? 13) !!};
            mapUpdateGetCoordinatesID = {!! json_encode($map_id ?? 'get_update_coordinates_map_id') !!};
            mapUpdateGetCoordinates = L.map(mapUpdateGetCoordinatesID).setView(center, zoom);
            initMapSettings(mapUpdateGetCoordinates)
            mapUpdateGetCoordinates.on('click', onUpdateGetCoordinatesMapClick);
        }
        function mapUpdateInvalidateSize() {
            setTimeout(() => mapUpdateGetCoordinates.invalidateSize(), 10);
        }
        function initUpdateGetCoordinatesMarker(markerData) {
            if (!!mapUpdateGetCoordinatesActiveMarker) {
                mapUpdateGetCoordinates.removeLayer(mapUpdateGetCoordinatesActiveMarker);
            }
            const marker = generateMarker(markerData, markerData.id);
            marker.addTo(mapUpdateGetCoordinates).bindPopup(
                `<div><b>${markerData.position.lat},  ${markerData.position.lng}</b>`);
            mapUpdateGetCoordinates.panTo(markerData.position);
            mapUpdateGetCoordinatesActiveMarker = marker;
            const update_address_latitude_input = document.querySelector('#update_address_latitude_input')
            if (update_address_latitude_input) {
                update_address_latitude_input.value = markerData.position.lat
            }
            const update_address_longitude_input = document.querySelector('#update_address_longitude_input')
            if (update_address_longitude_input) {
                update_address_longitude_input.value = markerData.position.lng
            }
        }
        function onUpdateGetCoordinatesMapClick(e) {
            initUpdateGetCoordinatesMarker({
                position: {
                    lat: e.latlng.lat,
                    lng: e.latlng.lng,
                 },
                draggable: false,
            })
        }

        if (!getLongitude() || !getLatitude()) {
            setUserMapPosition(initUpdateGetCoordinatesMap, 1000)
        } else {
            setTimeout(function () {initUpdateGetCoordinatesMap()}, 100);
        }

    </script>
</div>
