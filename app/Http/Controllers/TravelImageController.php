<?php

namespace App\Http\Controllers;

use App\Models\TravelImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TravelImageController extends Controller
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
            'image_title' => ['required', 'string'],
        ]);

        $file= $request->file('travel_photo');
        $file_name= str_replace(' ', '_', date('YmdHi').$file->getClientOriginalName());
        $file-> move(public_path('Image/travel_images'), $file_name);
        $photo_url = '/Image/travel_images/'.$file_name;

        TravelImage::create([
            'travel_id' => $request->travel_id,
            'travel_event_id' => $request->travel_event_id ?? null,
            'image_title' => $request->image_title,
            'file_name' => $file_name,
            'url' => $photo_url,
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
            'image_title' => ['required', 'string'],
        ]);

        $image = TravelImage::findOrFail($request->id);

        $image->update([
            'image_title' => $request->image_title,
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
        $image = TravelImage::findOrFail($request->id);

        if ($image) {
            $image->delete();
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }
}
