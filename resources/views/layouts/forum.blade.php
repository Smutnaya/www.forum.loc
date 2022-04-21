<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>

<body class="p-3 mb-2 bg-teal">

    <main class="py-4">
        @include('inc.header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    @include('inc.aside')
                </div>
                <div class="col content">
                    @yield("content")
                </div>
                <div class="col-md-2 info-post">
                    здесь мб последние апнутые темы или популярные
                </div>
            </div>
        </div>
    </main>
    {{-- @include('inc.footer') --}}
</body>

</html>
