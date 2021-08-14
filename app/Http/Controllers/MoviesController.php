<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;

class MoviesController extends Controller
{
    public function my_favorite_movies()
    {
        $user = User::with('Metas')->find(auth()->id());
        $metas = $user->Metas;
        return view("my_favorite_movies", compact('metas'));
    }

    public function sync_movies(Request $request)
    {
        // return if was not auth

        $user_id = auth()->id();

        $web_favorites = $request->input('ids');
        $db_favorites = Favorite::where("user_id", $user_id)->get()->toArray();

        foreach ($db_favorites as $db_favorite) {
            if (!in_array($db_favorite->movie_id, $web_favorites)) {
                $db_favorite->delete();
            }
        }

        foreach ($web_favorites as $web_favorite) {
            if (!in_array($web_favorite, $db_favorites)) {
                $record = new Favorite();
                $record->user_id = $user_id;
                $record->movie_id = $web_favorite;
                $record->save();
            }
        }

        $meta = UserMeta::where("user_id", $user_id)->where("key", "favorite_movies")->first();
        if ($meta) {
            $meta->value = serialize($web_favorites);
            $meta->save();
        } else {
            $meta = new UserMeta();
            $meta->user_id = $user_id;
            $meta->key = 'favorite_movies';
            $meta->value = serialize($web_favorites);
            $meta->save();
        }
        return $request->input('ids');
    }
}
