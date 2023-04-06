<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100 scroll-behavior-smooth {{ (config('settings.dark_mode') == 1 ? 'dark' : '') }}" dir="{{ (__('lang_dir') == 'rtl' ? 'rtl' : 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('site_title')</title>

    @yield('head_content')
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <link href="{{ url('/') }}/uploads/brand/{{ config('settings.favicon') ?? 'favicon.png' }}" rel="icon">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app'. (__('lang_dir') == 'rtl' ? '.rtl' : '') . (config('settings.dark_mode') == 1 ? '.dark' : '').'.css') }}" rel="stylesheet" data-theme-light="{{ asset('css/app'. (__('lang_dir') == 'rtl' ? '.rtl' : '') . '.css') }}" data-theme-dark="{{ asset('css/app'. (__('lang_dir') == 'rtl' ? '.rtl' : '') . '.dark.css') }}" data-theme-target="href">

    {!! config('settings.custom_js') !!}

    @if(config('settings.custom_css'))
        <style>
            {!! config('settings.custom_css') !!}
        </style>
    @endif
</head>
@yield('body')
</html>
