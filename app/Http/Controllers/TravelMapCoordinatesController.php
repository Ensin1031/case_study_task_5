<?php

namespace App\Http\Controllers;

use App\Models\EventMapCoordinates;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TravelMapCoordinatesController extends Controller
{

    public function store(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'travels.user-travels';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        $request->validate([
            'travel_id' => ['required', 'integer'],
            'title' => ['string', 'min:3', 'max:70'],
            'address_latitude' => ['required'],
            'address_longitude' => ['required'],
        ]);

        EventMapCoordinates::create([
            'travel_id' => $request->travel_id,
            'travel_event_id' => !!$request->travel_event_id ? $request->travel_event_id : null,
            'title' => $request->title ?? '',
            'description' => $request->description ?? '',
            'address_latitude' => $request->address_latitude,
            'address_longitude' => $request->address_longitude,
        ]);

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

    public function update(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'travels.user-travels';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        $request->validate([
            'id' => ['required', 'integer'],
            'title' => ['string', 'min:3', 'max:70'],
            'address_latitude' => ['required'],
            'address_longitude' => ['required'],
        ]);
        $coord = EventMapCoordinates::findOrFail($request->id);

        $coord->update([
            'title' => $request->title ?? '',
            'description' => $request->description ?? '',
            'address_latitude' => $request->address_latitude,
            'address_longitude' => $request->address_longitude,
        ]);

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'travels.user-travels';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        $request->validate([
            'id' => ['required', 'integer'],
        ]);
        $coord = EventMapCoordinates::findOrFail($request->id);

        $coord->delete();

        return redirect(route($redirect_to, $parameters, absolute: false));
    }
}
