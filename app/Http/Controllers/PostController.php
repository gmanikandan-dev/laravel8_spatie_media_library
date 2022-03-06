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
    

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        $post->clearMediaCollection('images');
        return back()->with('success','Post is removed!');
    }
}