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
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>
        <main>
            <header>
                <a  class="logo" href={{url('/home')}}><img class="logo" src="{{ url('icons/logo.png') }}" alt="Invents"></a>
                <form class="nothome" id="searchForm" action="{{ route('events.search') }}" method="GET">
                    <input name="search" value="" placeholder="Search event" class="search-event"/>
                    <button type="submit" id="searchButton"></button>
                </form> 

                @if (Auth::check())
                <a class="user" href="{{ url('/user/' . Auth::user()->id) }}"><span>{{ Auth::user()->name }}</span></a>
                @elseif (request()->path() !== 'login')
                    <a class="button user" href="{{ url('/login') }}"> Login </a> 
                @endif
            </header>
            <section id="content">
                @yield('content')
            </section>
            <footer>
                <p>© 2023 Invents</p>
            </footer>
        </main>
    </body>
</html>