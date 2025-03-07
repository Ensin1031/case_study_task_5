<?php

use Illuminate\Database\Eloquent\Collection;

function coordinates_view_data(Collection $coordinates): array {
    $result_coordinates = [];

    foreach ($coordinates as $coord) {
        $result_coordinates[] = [
            'id' => $coord->id,
            'position' => [
                'lat' => $coord->address_latitude,
                'lng' => $coord->address_longitude,
            ],
            'draggable' => false,
            'title' => $coord->title,
            'description' => $coord->description,
        ];
    }

    return $result_coordinates;
}
