<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

  

    <title>Laravel Image Upload with Spatie Medialibrary Example</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

  

<body>

    <div class="container">

        <h1>Create Post</h1>

        <div class="d-flex p-2 bd-highlight mb-3">

            <a href="{{ route('posts.index') }}" class="btn btn-outline-danger btn-sm">Go Back</a>

        </div>

        <div>

            <form action="{{ route('posts.store') }}" enctype="multipart/form-data" method="post">

                @csrf

                <div class="mb-3">

                    <label>Title</label>

                    <input type="text" name="title" class="form-control">

                </div>

                <div class="mb-3">

                    <label>Body</label>

                    <textarea class="form-control" name="body"></textarea>

                </div>

                <div class="mb-3">

                    <label>Image:</label>

                    <input type="file" name="image" class="form-control">

                </div>

                <div class="d-grid">

                    <button class="btn btn-success">Submit</button>

                </div>

            </form>

        </div>

    </div>

</body>

  

</html>