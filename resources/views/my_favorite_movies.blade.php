@extends('app')

@section('content')
    <style>
        .heigth-100 {
            height: 100px;
        }

        #results h2 {
            font-size: 17px;
            margin: 0 15px;
        }

        #results p {
            font-size: 13px;
        }

        .list-group-item {
            cursor: pointer;
        }

        .delete {
            position: absolute;
            top: 3px;
            right: 3px;
            color: red;
            cursor: pointer;
        }
    </style>
    <div class="middle-main">
        <div class="row justify-content-center">
            <div class="col-md-8" style="position: relative">
                <input type="text" id="query" class="form-control">
                <div style="position: absolute;width: 98%">
                    <ul class="list-group" id="results">
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">My Favorites</div>
                    <div class="card-body">
                        <ul class="list-group" id="my_favorites">
                            @foreach($movies as $movie)
                                <li class='list-group-item' data-id='{{ $movie['id'] }}'>{{ $movie['original_title'] }}<span class='delete'>X</span></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            let body = $("body");

            body.on("keyup paste", "#query", (e) => {
                let thisQuery = $(e.target).val();
                if (thisQuery.length > 2) {
                    $.ajax({
                        url: 'https://api.themoviedb.org/3/search/movie',
                        data: {
                            query: thisQuery,
                            api_key: "4c4ff693ec98c7088fe547d782e01836"
                        },
                        method: 'GET',
                        success: (response) => {
                            if (response.results.length) {
                                let movies = response.results.slice(0, 10);
                                let html = ``;
                                movies.map(movie => {
                                    html += `<li class="list-group-item" data-id="${movie.id}"><div class="d-flex align-items-center"><img class="heigth-100" src="https://image.tmdb.org/t/p/w200${movie.poster_path}"><h2>${movie.original_title}</h2><p>${movie.overview}</p></div><li>`;
                                });
                                $("#results").html(html);
                            }
                        }
                    });
                }
            });
            body.on("click", "#results .list-group-item", (e) => {
                let myThis = $(e.target).closest('.list-group-item');
                let thisId = myThis.attr("data-id");
                myThis = $(e.target).parent().children('h2').eq(0);
                if (!$("#my_favorites li[data-id=" + thisId + "]").length) {
                    $("#my_favorites").append("<li class='list-group-item' data-id='" + thisId + "'>" + myThis.html() + "<span class='delete'>X</span></li>");
                }
                sync_movies();
            });

            body.on("click", "#my_favorites .delete", (e) => {
                let myThis = $(e.target).closest('.list-group-item');
                myThis.remove();
                sync_movies();
            });

            function sync_movies() {
                let ids = [];
                $("body #my_favorites li").each(function () {
                    let attr = $(this).attr("data-id");
                    if (attr) {
                        ids.push(attr);
                    }
                });
                ids = ids.filter(onlyUnique);

                $.ajax({
                    url: `{{ route('sync_movies') }}`,
                    method: "POST",
                    data: {
                        ids: ids,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        console.log(response);
                    }
                });
            }

            function onlyUnique(value, index, self) {
                return self.indexOf(value) === index;
            }
        })
    </script>
@endsection
