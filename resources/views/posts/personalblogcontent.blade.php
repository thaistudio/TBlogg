@extends('layouts.app')

@section('header')
    <!-- Page Header -->
    <header class="masthead" style="background-image: url(/storage/cover_images/{{$content->cover_image}})">
        <div class="overlay"></div>
        <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
            <div class="post-heading">
                <h1>{{$content->title}}</h1>
                <h2 class="subheading">{{App\ControllerHelper\StringHelper::GetPostPreview($content->body)}}</h2>
                <span class="meta">Posted by
                <a href="\about">{{$content->user->name}}</a>
                on {{$content->created_at}}</span>
            </div>
            </div>
        </div>
        </div>
    </header>
@endsection

@section('content')
    <!-- Page Content -->
    <div class="row">
        <!-- Post Content Column -->
        <div class="card col-md-7 mx-auto">
            <!-- Blog content -->
            <div class="row">
                <!-- Post Content -->
                <div class="card-text">
                    <p class="modal-body">{{$content->body}}</p>
                </div>
            </div>

            <!-- Comment panel and comment display -->
            <div class="row">
                <div class="comment-wrapper">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong>Comment Section</strong>
                        </div>

                        <!-- Comment panel -->
                        @if (Auth::user())
                            <div class="panel-body">
                                {!! Form::open(['route' => ['comment.create', $content->id],
                                'method' => 'GET']) !!}
                                    <div class="form-group">
                                        {!! Form::textarea('comment', '', ['class' => 'form-control', 'rows' => 3,
                                        'placeholder' => 'Write a comment ...']) !!}
                                    </div>
                                    {!! Form::submit('Comment', ['class' => 'btn btn-primary']) !!}
                                {!! Form::close() !!}
                                <div class="clearfix"></div>
                                <hr>
                            </div>
                        @endif

                        <!-- Comment display -->
                        <div class="panel-body">
                            @isset($comments)
                                @foreach ($comments as $comment)
                                    <ul class="media-list">
                                        <li class="media">
                                            <a href="#" class="pull-left">
                                                <img src="https://bootdey.com/img/Content/user_1.jpg" alt="" class="img-circle">
                                            </a>
                                            <div class="media-body">
                                                <span class="text-muted pull-right">
                                                    <small class="text-muted">30 min ago</small>
                                                </span>
                                                <strong class="text-success">@<a href="/about">{{$comment->user->name}}</a> </strong>
                                                <p>{{$comment->comment}}</p>
                                            </div>
                                        </li>
                                    </ul>
                                @endforeach
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar Widgets Column -->
        <div class="card col-md-3 mx-auto">
            <!-- Search Widget -->
            <div class="card">
                <h5 class="card-header">Search</h5>
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for...">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button">Go!</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
@endsection
