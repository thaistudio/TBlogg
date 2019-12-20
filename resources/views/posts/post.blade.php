@extends('layouts.app')

@section('header')
    <header class="masthead" style="background-image: url({{$bg_img}})">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="page-heading">
                    <h1>
                        {{$header_str_1}}
                    </h1>
                    <span class="subheading">{{$header_str_2}}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('content')
    {{-- New Post button --}}
    <div class="container">
        <a href="/posts/create" class="btn btn-info">New Post</a>
    </div>
    <p></p>

    @if (count($contents) > 0)
        @foreach ($contents as $content)
            <div class="jumbotron bg-secondary text-light">
                <div class="row">
                    {{-- Post cover photo --}}
                    <div class="col-2">
                        <img style="width:100%" src="/storage/cover_images/{{$content->cover_image}}" alt="noimage">
                    </div>

                    {{-- Content --}}
                    <div class="col-8">
                        {{-- Tittle --}}
                        <h3><a href="/posts/{{$content->id}}">{{$content->title}}</a></h3>

                        {{-- Preview content --}}
                        <p class="card-text">
                            {{App\ControllerHelper\StringHelper::GetPostPreview($content->body)}}
                            <br>
                            <small>{{str_word_count($content->body)}} words</small>
                        </p>

                        <br>

                        {{-- Post info --}}
                        <small>Created at: {{$content->created_at}}</small>

                        @if ($content->shared_at != null)
                            <br>
                            <small>Shared at: {{$content->shared_at}}</small>
                        @endif

                        <br>
                        <small>by {{$content->user->name}}</small>
                        <br><br>
                    </div>
                </div>

                <br>

                @if (Auth::user())
                    @if (Auth::user()->id === $content->user_id)
                        <div class="row">
                            <div class="col-1">
                                {{-- Edit button --}}
                                {{-- <a href="{{url('/')}}/posts/{{$content->id}}/edit" class="btn btn-primary">Edit</a> --}}
                                {!! Form::open(['action' => ['PostController@edit', $content->id],
                                    'method' => 'GET']) !!}
                                    {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
                                {!! Form::close() !!}
                            </div>
                            <div class="col-1">

                                {{-- Share button --}}
                                @if ($content->shared_at != null)
                                    {!! Form::open(['route' => ['post.unshare', $content->id],
                                    'method' => 'GET']) !!}
                                        {!! Form::submit('Unshare', ['class' => 'btn btn-warning']) !!}
                                    {!! Form::close() !!}
                                @else
                                    {!! Form::open(['route' => ['post.share', $content->id],
                                    'method' => 'GET']) !!}
                                        {!! Form::submit('Share', ['class' => 'btn btn-primary']) !!}
                                    {!! Form::close() !!}
                                @endif
                            </div>

                            <div class="col-10">
                                {{-- Delete button --}}
                                {!! Form::open(['action' => ['PostController@destroy', $content->id],
                                'method' => 'POST', 'class' => 'float-right']) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    @else
                        {{-- Collection button --}}
                        @if (App\ControllerHelper\Cache\PostsCH::is_collected_by($content->id, Auth::user()->id))
                            <div class="col-1">
                                {!! Form::open(['action' => ['PostController@dis_collect', $content->id],
                                'method' => 'GET']) !!}
                                    {!! Form::submit('Dismiss', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                            </div>
                        @else
                            <div class="col-1">
                                {!! Form::open(['action' => ['PostController@collect', $content->id],
                                'method' => 'GET']) !!}
                                    {!! Form::submit('Collect', ['class' => 'btn btn-primary']) !!}
                                {!! Form::close() !!}
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        @endforeach
        {{-- {{$contents->links()}} --}}
    @else
        <div class="alert alert-dismissible alert-secondary">
            @if (Auth::check())
                <p>{{$nothing_str}}</p>
            @else
                <p>Please sign in and start blogging :) !</p>
            @endif
        </div>
    @endif
@endsection()
