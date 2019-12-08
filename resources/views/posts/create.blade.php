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
    {!! Form::open(['action' => 'PostController@store',
    'method' => 'POST',
    'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {!! Form::label('title', 'Title') !!}
            {!! Form::text('blogtitle', '', ['class' => 'form-control',
            'placeholder' => 'Your blog title ...' ]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('content', 'Blog Content') !!}
            {!! Form::textarea('blogcontent', '', ['class' => 'form-control',
            'placeholder' => 'Start blogging ...' ]) !!}
        </div>

        <div class="form-group">
            <p>Upload Cover Image</p>
            {!! Form::file('cover_image') !!}
        </div>

        {!! Form::submit('Post', ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection()
