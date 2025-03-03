<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TravelController extends Controller
{
    public function index() {
        return redirect(route('travels.travels', absolute: false));
    }
    public function show($id): View
    {
        $travel = Travel::findOrFail($id);
        return view('travels.travel-show', [
            'user' => $travel->user(),
            'travel' => $travel,
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

    public function user_index(Request $request): View
    {
        $user = $request->user();
        return view('travels.travels', [
            'user' => $user,
            'travels' => $user->travels,
            'redirect_to' => 'travels.user-travels',
            'title' => 'Путешествия пользователя '.$user->name,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'integer'],
            'travel_title' => ['required', 'string', 'min:3', 'max:70'],
            'start_at' => ['required'],
        ]);

        $file= $request->file('travel_main_photo');
        $file_name= date('YmdHi').$file->getClientOriginalName();
        $file-> move(public_path('Image/travels'), $file_name);

        $travel = Travel::create([
            'user_id' => $request->user_id,
            'travel_title' => $request->travel_title,
            'short_description' => $request->short_description || '',
            'start_at' => date_create($request->start_at)->format('Y-m-d'),
            'travel_main_photo' => $file_name,
        ]);

        return redirect(route('travels.travel-show', $travel->id, absolute: false));
    }

    public function update(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'admin-panel.authors';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

        $request->validate([
            'id' => ['required', 'integer'],
            'author_name' => ['required', 'string', 'min:3', 'max:70'],
            'about_author' => ['required', 'string'],
        ]);
//        $author = Author::find($request->id);
//
//        $file= $request->file('author_photo');
//        $filename= date('YmdHi').$file->getClientOriginalName();
//        $file-> move(public_path('Image/authors'), $filename);
//
//        if (!$author) {
//            $author = Author::create([
//                'author_name' => $request->author_name,
//                'about_author' => $request->about_author,
//                'author_photo' => $filename,
//            ]);
//        } else {
//            $author->update([
//                'author_name' => $request->author_name,
//                'about_author' => $request->about_author,
//                'author_photo' => $filename,
//            ]);
//        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $query = $request->query();
        $redirect_to = 'admin-panel.authors';
        $parameters = [];
        if (array_key_exists("redirect_to", $query)) {
            $redirect_to = $query["redirect_to"];
        }
        if (array_key_exists("query_parameters", $query)) {
            $parameters = $query["query_parameters"];
        }

//        $request->validate([
//            'id' => ['required', 'integer'],
//        ]);
//
//        $author = Author::find($request->id);
//        if ($author) {
//            $author->delete();
//        }

        return redirect(route($redirect_to, $parameters, absolute: false));
    }
}
