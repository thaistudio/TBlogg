<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Thai's blog</title>

        <!-- Style -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" rel="stylesheet">

        @include('inc.navbar')
    </head>
    <body>
        <div class="container">
            @include('inc.message')
            @yield('content')
        </div>
    </body>
</html>

