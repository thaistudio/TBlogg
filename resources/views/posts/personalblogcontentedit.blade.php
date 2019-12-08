@extends('layouts.app')

@section('header')
    <header class="masthead" style="background-image: url('img/home-bg.jpg')">
        <div class="overlay"></div>
        <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
            <div class="site-heading">

            </div>
            </div>
        </div>
        </div>
    </header>
@endsection

@section('content')
    <h1>Write your blog</h1>
    {!! Form::open(['action' => ['PostController@update', $content->id],
    'method' => 'POST',
    'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {!! Form::label('title', 'Title') !!}
            {!! Form::text('blogtitle', $content->title, ['class' => 'form-control' ]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('content', 'Blog Content') !!}
            {!! Form::textarea('blogcontent', $content->body, ['class' => 'form-control' ]) !!}
        </div>

        <div class="form-group">
            <p>Upload Post's Cover Image</p>
            {!! Form::file('cover_image') !!}
        </div>

        {!! Form::hidden('_method', 'PUT') !!}
        {!! Form::hidden('url', URL::previous()) !!}
        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection()
