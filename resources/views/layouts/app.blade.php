<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{config('config.title')}} | @yield('title')</title>
    <meta content="{{config('config.description')}}"
        name="description" />
        <meta name="url" content="{{config('config.url')}}">
        <meta property="og:url" content="{{config('config.url')}}"/>
        <meta content="{{config('config.keywords')}}"
        name="keywords" />
        <link rel="canonical" href="{{config('config.canonical')}}"/>
    <link rel="icon" href="{{ asset('public/assets/images/header/favicon.svg') }}"
        type="image/gif" sizes="16x16">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="{{ asset('public/css/materialize.min.css') }}">
    <!-- iPhuKien css -->
    <link rel="stylesheet" href="{{ asset('public/iphukien/user/common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/iphukien/user/header.css') }}">
    <link rel="stylesheet" href="{{ asset('public/iphukien/user/footer.css') }}">
    @yield('fb-meta-tags')
    @yield('meta-tags')
    @section('styles')
    @show
</head>

<body>
    @yield('fb-sdk')
    @yield('header')
    @yield('content')
    <footer>
        @yield('footer')
        @include('layouts.footer', ['status' => 'complete'])
        <!-- jQuery -->
        <script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>
        <!-- Materialize -->
        <script src="{{ asset('public/js/materialize.min.js') }}"></script>
        <!-- header js -->
        <script src="{{ asset('public/assets/scripts/iphukien/user/header.js') }}"></script>
        @section('scripts')
        @show
        @include('toast::messages-jquery')
        <script>
            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        </script>
    </footer>
</body>
</html>
