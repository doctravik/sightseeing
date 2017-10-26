<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="site">
        @include('layouts.partials.nav')
        @include('layouts.partials.hero')

        <section class="section flex-1">
            <div class="container">
                @flash
                    <article class="message is-{{ flash()->type() }}">
                        <div class="message-body">{{ flash()->message() }}</div>
                    </article>
                @endflash

                @yield('content')
            </div>
        </section>

        @include('layouts.partials.footer')
        @include('layouts.partials.flash')
    </div>

    <!-- Scripts -->
    @include('layouts.partials.script')
</body>
</html>
