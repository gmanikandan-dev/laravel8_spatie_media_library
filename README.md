# Laravel 8 Multiple Image Upload with Spatie's Media Library

### _Requirements_  
```
Laravel 8 Media Library requires `PHP 7.4+` and Laravel 8.

This package uses json columns. MySQL 5.7 or higher is required.
```
>  _Step 1 : Create a New Laravel  project_
````
composer create-project laravel/laravel spatie --prefer-dist
````
> Application key set successfully.

#####  _run_ 
````sh
cd spatie
````

>  _Step 2 : Install Laravel UI Inside your project_
````
 composer require laravel/ui --prefer-dist
````
>  _Step 3 : Install Bootstrap scaffolding_
````
 php artisan ui bootstrap --auth
````
> Bootstrap scaffolding installed successfully.
> Please run "npm install && npm run dev" to compile your fresh scaffolding.
> Authentication scaffolding generated successfully.

>  _Step 4 : Add following code in (app/Providers/AppServiceProvider.php)_
````
        use Schema;
        public function boot()
        {
            Schema::defaultStringLength(191);
        }
````
 >  _Step 5 :Install spatie/laravel-medialibrary Package
 ````
 composer require "spatie/laravel-medialibrary:^9.0.0" --prefer-dist
 ````
 > Publishing complete
 
 >  _Step 6 : Create new db in your phpmyadmin and Configure the database in .env file
 ````
APP_NAME=Spatie
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spatie
DB_USERNAME=root
DB_PASSWORD=ZZZZZZ
 ````
  >  _Step 7 :You need to publish the migration to create the media table:
  ````
 php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
  ````
  > Publishing complete.
  
   >  _Step 8 : Migrate tables in db
   ````
   php artisan migrate
   ````
> Migration table created successfully.
> Step 9 : Publishing the config file is optional
````
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="config"
````
 > Publishing complete.
 
> Step 10 : Adding a media disk
> By default, the media library will store its files on Laravel's public disk. If you want a dedicated disk you should add a disk to config/filesystems.php
````
 ...
    'disks' => [
        ...

        'media' => [
            'driver' => 'local',
            'root'   => public_path('media'),
            'url'    => env('APP_URL').'/media',
        ],
    ...
````
 > To store all media on that disk by default, you should set the disk_name config value in the media-library config file to the name of the disk you added. config/media-library.php
 ````
return [
    'disk_name' => 'media',

    // ...
];
 ````
> Step 11 : Create a Post Table,Model and Controller
````
php artisan make:model Post -mc
````
> Model created successfully.
Created Migration: 2022_03_05_182553_create_posts_table
Controller created successfully

> Step 12 : Check and add code in database/migrations/2021_07_13_140744_create_posts_table.php
````
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('body');
            $table->timestamps();
        });
    }
````
> Step 12 : Check and add code in app/Models/Post.php
````
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Post extends Model implements HasMedia
{
use HasFactory, InteractsWithMedia;
    protected $fillable = [
       'title',
        'body',
    ];
}

````
> Step 13 : Check and add code in routes/web.php
````
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

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

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('posts',[PostController::class,'index'])->name('posts.index');
Route::get('posts/create',[PostController::class,'create'])->name('posts.create');
Route::post('posts/store',[PostController::class,'store'])->name('posts.store');
````
> Step 14 : Check and add code in app/Http/Controllers/PostController.php
````
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
/**
* Write code on Method
     *
     * @return response()
*/
    public function index()
    {    
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
     }
/*
     * Write code on Method
     *   * @return response()
*/
    public function create()
    {
        return view('posts.create');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $valiator = $request->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'required',
        ]);
        $post = Post::create($request->all());
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $post->addMediaFromRequest('image')->toMediaCollection('images');
        }
        return redirect()->route('posts.index');
    }
}
````


>Step 15 : Create Blade Files
resources/views/posts/index.blade.php
````
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
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th width="30%">Image</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $key=>$post)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->body }}</td>
                    <td><img src="{{$post->getFirstMediaUrl('images', 'thumb')}}" / width="120px"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
````
> resources/views/posts/create.blade.php
````
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
````
> Add bootstrap 5 css and js link in resources/views/layouts/app.blade.php
````
//At top of inside head tag
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

````
````
//At bottom of body tag
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

````
> Add code in  resources/views/layouts/app.blade.php
````
  <!-- Left Side Of Navbar -->
                    @if(Auth::user())
                    <ul class="navbar-nav me-auto">
                    <a class="nav-link" href="{{ url('posts') }}">{{ __('Post') }}</a>
                    </ul>
                      @endif
````

> run 
````
php artisan migrate
````
````
php artisan serve
````
> Starting Laravel development server: http://127.0.0.1:8000
Open in browser http://127.0.0.1:8000
Register
Login
Click post
Add post
enter title and body
choose image. Image should be less than 1MB and resolution less 1024 and submit it.


### If any error occur,download or clone source code in the branch of master
Inside your project folder run 
````
composer install
````
>create db and config db in .env file 
````
php aritisan migrate
````
````
php artisan serve
````

## _Follow my git profile and Thank you for visiting_ 
