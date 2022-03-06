<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

  

    <title>Laravel Image Upload with Spatie Medialibrary Example - ItSolutionstuff.com </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

  

<body>

    <div class="container">

        <h1>Posts List</h1>

        <div class="d-flex p-2 bd-highlight mb-3">

            <a href="{{ route('posts.create') }}" class="btn btn-dark">Add</a>

        </div>
        @if(session('success'))
        <div class="alert alert-success">
            <button class="btn-close" type="button" data-bs-dismiss="alert"></button>
            <strong>{{ session('success') }}</strong>
        </div>
        @endif

        <table class="table">

            <thead>

                <tr>

                    <th>#</th>

                    <th>Title</th>

                    <th>Body</th>

                    <th width="30%">Image</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>

                @foreach($posts as $key=>$post)

                <tr>

                    <td>{{ ++$key }}</td>

                    <td>{{ $post->title }}</td>

                    <td>{{ $post->body }}</td>

                    <td><img src="{{$post->getFirstMediaUrl('images', 'thumb')}}" / width="120px"></td>
                    <td> <button class="btn btn-danger btn-sm"> <a href="{{ url('removepost/'.$post->id) }}" class="button delete-confirm" style="text-decoration: none; color:white;"> drop</a></button>
                        </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</body>

  

</html>