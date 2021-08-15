<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $fillable = [
        'm_id',
        'original_title',
        'title',
        'overview',
        'adult',
        'backdrop_path',
        'genre_ids',
        'original_language',
        'popularity',
        'vote_average',
        'vote_count',
        'poster_path',
        'release_date',
        'video',
    ];
}
