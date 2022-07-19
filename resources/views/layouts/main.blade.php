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

<body class="p-2 fs-6">
    <main>
        @include('inc.header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-3">
                    @include('inc.aside')
                </div>
                <div class="col-lg-10 col-md-10 col-sm-9">
                    @yield('content')
                </div>

            </div>

        </div>

    </main>

</body>

</html>
