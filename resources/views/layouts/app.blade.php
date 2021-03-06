<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user" content="{{ Auth::user() }}">
    <script src="https://kit.fontawesome.com/141643de39.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="shortcut icon" class="rounded" href="{{asset('img/154059274.png')}}">
    <title>FortyApp</title>
</head>
<body>
    <div id="app">
        @include('partials.nav')

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @include('layouts.footer')
<script src="{{ mix('js/app.js')}}"></script>
</body>
</html>
