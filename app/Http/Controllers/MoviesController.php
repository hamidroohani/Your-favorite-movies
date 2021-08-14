<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MoviesController extends Controller
{
    public function my_favorite_movies()
    {
        return view("my_favorite_movies");
    }

    public function sync_movies(Request $request)
    {
        return $request->input('ids');
    }
}
