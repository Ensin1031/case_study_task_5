<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TravelController extends Controller
{
    public function home() {
        return redirect(route('index', absolute: false));
    }
    public function index() {
        return view('layouts.index');
    }
    public function show($id): View
    {
        $travel = Travel::findOrFail($id);
        $can_edit = !!request()->user() && request()->user()->id == $travel->user_id;
        return view('travels.travel-show', [
            'user' => $travel->user(),
            'travel' => $travel,
            'can_edit' => $can_edit,
            'redirect_to' => 'travels.travel-show',
            'query_parameters' => $travel->id,
            'need_full_content' => true,
        ]);
    }

    public function common_index(Request $request): View
    {
        return view('travels.travels', [
            'user' => $request->user(),
            'travels' => Travel::all(),
            'redirect_to' => 'travels.travels',
            'title' => 'Путешествия',
        ]);
    }

    public function user_index(Request $request): View | RedirectResponse
    {
        $user = $request->user();
        if (!$user) {
            return redirect(route('travels.travels', absolute: false));
        }
        return view('travels.travels', [
            'user' => $user,
            'travels' => $user->travels,
            'redirect_to' => 'travels.user-travels',
            'title' => 'Путешествия пользователя '.$user->name,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => ['required', 'integer'],
            'travel_title' => ['required', 'string', 'min:10', 'max:70'],
            'start_at' => ['required'],
        ]);

        $file= $request->file('travel_main_photo');
        $file_name= str_replace(' ', '_', date('YmdHi').$file->getClientOriginalName());
        $file-> move(public_path('Image/travels'), $file_name);
        $main_photo_url = '/Image/travels/'.$file_name;

        $travel = Travel::create([
            'user_id' => $request->user_id,
            'travel_title' => $request->travel_title,
            'short_description' => $request->short_description ?? '',
            'start_at' => date_create($request->start_at)->format('Y-m-d'),
            'main_photo_url' => $main_photo_url,
        ]);

        return redirect(route('travels.travel-show', $travel->id, absolute: false));
    }

    public function change_main_photo(Request $request): RedirectResponse
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
        $travel = Travel::findOrFail($request->id);

        $file= $request->file('travel_main_photo');
        $file_name= str_replace(' ', '_', date('YmdHi').$file->getClientOriginalName());
        $file-> move(public_path('Image/travels'), $file_name);
        $main_photo_url = '/Image/travels/'.$file_name;

        $travel->update([
            'main_photo_url' => $main_photo_url,
        ]);

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

    public function update_travel_dates(Request $request): RedirectResponse
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

        $travel = Travel::findOrFail($request->id);

        if ($request->has('start_at')) {
            $travel->update([
                'start_at' => date_create($request->start_at)->format('Y-m-d'),
            ]);
        } elseif ($request->has('end_at')) {
            $travel->update([
                'end_at' => date_create($request->end_at)->format('Y-m-d'),
            ]);
        }

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
            'travel_title' => ['required', 'string', 'min:10', 'max:70'],
        ]);

        $request->validate([
            'id' => ['required', 'integer'],
        ]);
        $travel = Travel::findOrFail($request->id);

        $travel->update([
            'travel_title' => $request->travel_title,
            'short_description' => $request->short_description ?? '',
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
        $travel = Travel::findOrFail($request->id);

        if ($travel) {
            $travel->delete();
        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }
}
