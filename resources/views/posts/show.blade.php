@extends('layouts.app')

@section('content')
<article>
    <h1>{{ $post->title }}</h1>
    <p>By {{ $author_name }}</p>
    <div>{!! $post->body !!}</div>
</article>
@endsection
