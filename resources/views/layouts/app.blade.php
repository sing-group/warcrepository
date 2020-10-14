<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Slab" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar bg-primary navbar-fixed-top">
            <div class="container-fluid index-nav-border">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed navmovil" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar navmovil-iconos"></span>
                        <span class="icon-bar navmovil-iconos"></span>
                        <span class="icon-bar navmovil-iconos"></span>
                    </button>
                    <a class="navbar-brand" href="{{route('/')}}">
                        <img src="{{asset('images/logo.png')}}" alt="logo empresa" class="img-responsive img-logo imgNavLogo">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="{{route('/')}}" class=" li-menus txt-blanco "><i class="glyphicon glyphicon-home"></i>&nbsp; Home</a></li>
                        <li><a href="http://sing.ei.uvigo.es/" class=" li-menus txt-blanco li-menus-borde-derecha"><span class="glyphicon glyphicon-info-sign"></span>&nbsp; About us </a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="{{ route('registration') }}" class="txt-blanco li-menus"><span class="glyphicon glyphicon-user"> </span>&nbsp; Join for free</a></li>
                        <li><a href="{{ route('login') }}" class="txt-blanco li-menus "><span class="glyphicon glyphicon-log-in"> </span> Log in</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle menu-right li-menus txt-blanco" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false"> Language<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                @if(App::getLocale() == "en")
                                    <li><a href="{{ url('lang', ['es']) }}" class="text-center ">Change to Spanish</a></li>
                                @else
                                    <li><a href=" {{ url('lang', ['en']) }}" class="text-center img-idioma li-menus">Change to English</a></li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
