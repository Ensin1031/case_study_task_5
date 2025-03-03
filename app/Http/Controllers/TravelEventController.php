<?php

namespace App\Http\Controllers;

use App\Models\TravelEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TravelEventController extends Controller
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
            'event_at' => ['required'],
        ]);

        TravelEvent::create([
            'travel_id' => $request->travel_id,
            'event_title' => $request->event_title ?? '',
            'event_description' => $request->event_description ?? '',
            'event_at' => date_create($request->event_at)->format('Y-m-d H:i:s'),
            'event_price' => $request->event_price ?? 0,
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
        $event = TravelEvent::findOrFail($request->id);

        if ($event) {
            $event->delete();
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }
}
