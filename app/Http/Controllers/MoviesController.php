<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Movie;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;

class MoviesController extends Controller
{
    public function my_favorite_movies()
    {
        $user = User::with('Metas')->find(auth()->id());
        $metas = $user->Metas;
        if (count($metas)){
            $metas = $metas->where('key','favorite_movies')->first();
            $metas = unserialize($metas->value);
        }
        $movies = [];
        foreach ($metas as $meta)
        {
            $movie = file_get_contents("https://api.themoviedb.org/3/movie/" . $meta . "?api_key=4c4ff693ec98c7088fe547d782e01836&language=en-US");
            $movie = json_decode($movie,true);
            $movies[] = $movie;
            $movie['m_id'] = $movie['id'];
            $movie = array_map(function ($m){
                return (is_array($m)) ? serialize($m) : $m;
            },$movie);
            Movie::create($movie);
        }
        return view("my_favorite_movies", compact('movies'));
    }

    public function sync_movies(Request $request)
    {
        // return if was not auth

        $user_id = auth()->id();

        $web_favorites = $request->input('ids');
        $db_favorites = Favorite::where("user_id", $user_id)->pluck('movie_id','id')->toArray();

        foreach ($db_favorites as $key=>$db_favorite) {
            if (!in_array($db_favorite, $web_favorites)) {
                Favorite::where('id',$key)->delete();
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

    public function search_movies(Request $request)
    {
        $query = $request->input('query');
        $query = str_replace(" ","+",$query);
        return file_get_contents("https://api.themoviedb.org/3/search/movie?api_key=4c4ff693ec98c7088fe547d782e01836&query=".$query);
    }
}
