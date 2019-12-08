@extends('layouts.app')

@section('header')
    <!-- Page Header -->
    <header class="masthead" style="background-image: url('img/about-bg.jpg')">
        <div class="overlay"></div>
        <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
            <div class="page-heading">
                <h1>About Me</h1>
                <span class="subheading">This is what I do.</span>
            </div>
            </div>
        </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="container">
        <h1>About {{$name}}</h1>
        <p>My name is {{$name}}. I am {{$age}} years old. <br> I love:</p>
        <ul class="list-group">
            @if (count($hobbies) > 2)
                @foreach ($hobbies as $hobbie)
                    <li class="list-group-item">{{$hobbie}}</li>
                @endforeach
            @endif
        </ul>
    </div>
@endsection
