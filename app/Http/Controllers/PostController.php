<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $post = Post::create($request->all());

        return redirect()->route('posts.show', $post->slug);
    }

    public function search(Request $request)
    {
        $q = $request->input('q');

        $posts = Post::whereRaw("title LIKE '%{$q}%'")->get();

        return view('posts.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->first();

        return view('posts.show', [
            'post' => $post,
            'author_name' => $post->author->name,
        ]);
    }

    public function publish(string $slug)
    {
        $post = Post::where('slug', $slug)->first();

        if ($post->status = 'published') {
            $post->save();
        }

        return back();
    }
}
