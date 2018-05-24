<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($settings->app_name) ? $settings->app_name : config('app.name', 'Academy') }}</title>

    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png', $secure) }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png', $secure) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png', $secure) }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest', $secure) }}">
    <link rel="mask-icon" href="{{ asset('favicon/safari-pinned-tab.svg', $secure) }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="{{ asset('css/app.css', $secure) }}">
</head>
<body>
<div id="app">
    <landing></landing>
</div>
<script src="{{ asset('js/landing/index.js', $secure) }}"></script>
</body>
</html>