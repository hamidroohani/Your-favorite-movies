<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/my-favorite-movies',[\App\Http\Controllers\MoviesController::class,'my_favorite_movies'])->name('my-favorite-movies');
Route::post('/sync_movies',[\App\Http\Controllers\MoviesController::class,'sync_movies'])->name('sync_movies');
