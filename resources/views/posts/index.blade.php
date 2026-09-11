@extends('layouts.app')

@section('content')
<h1>Posts</h1>
<p><a href="/posts/new">Crate New Post</a></p>

@foreach ($posts as $post)
<article>
    <h2>{{ $post->title }}</h2>
    <p>By {{ $post->author->name }}</p>
    <div>{!! $post->body !!}</div>
</article>
@endforeach
@endsection
