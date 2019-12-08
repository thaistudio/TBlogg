@extends('layouts.app')

@section('header')
    @if (pathinfo(Request::url(), PATHINFO_FILENAME) === 'social')
        <header class="masthead" style="background-image: url('img/post-bg.jpg')">
            <div class="overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-10 mx-auto">
                        <div class="page-heading">
                        <h1>
                            Share your thoughts with others
                        </h1>
                        <span class="subheading">Write whatever you want.</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    @else
        <header class="masthead" style="background-image: url('img/personal-bg.jpg')">
            <div class="overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-10 mx-auto">
                        <div class="page-heading">
                        <h1>
                            Your personal area
                        </h1>
                        <span class="subheading">Write whatever you want.</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    @endif

@endsection

@section('content')
    <br><br>
    <div class="container">
        <a href="/{{Request::path()}}/create" class="btn btn-info">New Post</a>
    </div>
    <p></p>

    @if (count($contents) > 0)
        @foreach ($contents as $content)
            <div class="jumbotron bg-secondary text-light">
                <div class="row">
                    <div class="col-2">
                        <img style="width:100%" src="/storage/cover_images/{{$content->cover_image}}" alt="">
                    </div>
                    <div class="col-8">
                        <h3><a href="{{url('/')}}/posts/{{$content->id}}">{{$content->title}}</a></h3>
                        <p class="card-text">{{App\ControllerHelper\StringHelper::GetPostPreview($content->body)}}</p>
                        <br>
                        <small>Created at: {{$content->created_at}}</small>
                        <br>
                        <small>Shared at: {{$content->shared_at}}</small>
                        <br>
                        <small>by {{$content->user->name}}</small>
                        <br><br>
                    </div>
                </div>

                <br>

                @if (Auth::user())
                    @if (Auth::user()->id === $content->user->id)
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
                                @if (pathinfo(Request::url(), PATHINFO_FILENAME) !== 'social')
                                    {!! Form::open(['route' => ['post.share', $content->id],
                                    'method' => 'GET']) !!}
                                        {!! Form::submit('Share', ['class' => 'btn btn-primary']) !!}
                                    {!! Form::close() !!}
                                @else
                                    {!! Form::open(['route' => ['post.unshare', $content->id],
                                    'method' => 'GET']) !!}
                                        {!! Form::submit('Unshare', ['class' => 'btn btn-primary']) !!}
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
                    @endif
                @endif
            </div>
        @endforeach
        {{-- {{$contents->links()}} --}}
    @else
        <div class="alert alert-dismissible alert-secondary">
            @if (Auth::check())
                <p>You haven't posted anything yet. Do you feel like writing anything now? :) !</p>
            @else
                <p>Please sign in and start blogging :) !</p>
            @endif
        </div>
    @endif
@endsection()
