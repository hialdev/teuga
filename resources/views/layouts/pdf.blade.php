<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-bs-theme="light"
    data-color-theme="Blue_Theme">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="/assets/images/logos/favicon.png" />

    <!-- Core Css -->
    <link rel="stylesheet" href="{{public_path('assets/css/styles.css')}}" />
    <link rel="stylesheet" href="{{public_path('css/app.css')}}" />
    @yield('css')
</head>

<body>
    @yield('content')

    @yield('scripts')
</body>

</html>
