<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('m_id')->unique()->index();
            $table->string('original_title')->index();
            $table->string('title');
            $table->text('overview');
            $table->boolean('adult');
            $table->string('backdrop_path')->nullable();
            $table->string('genre_ids')->nullable();
            $table->string('genres')->nullable();
            $table->string('original_language',2);
            $table->float('popularity')->nullable();
            $table->float('vote_average')->nullable();
            $table->float('vote_count')->nullable();
            $table->string('poster_path')->nullable();
            $table->date('release_date')->nullable();
            $table->string('video')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
