<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>WARC-Repository</title>
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/styles-grey-navbar.css')}}">
        <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Slab" rel="stylesheet">
        @yield('extracss')

    </head>
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <img src="{{asset('images/logo.png')}}" class="img-responsive img-logo">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="btn-nav"><a href="{{route('user.index') }}"><span class="glyphicon glyphicon-home"></span></span>&nbsp; @lang('messages.home')<span class="sr-only"></span></a></li>
                    <li class="btn-nav"><a href="{{ route('corpus.search') }}"><span class="glyphicon glyphicon-folder-open"></span>&nbsp; @lang('messages.Corpus')</a></li>
                    <li class="btn-nav"><a href="{{ route('user.list') }}"><span class="glyphicon glyphicon-user"></span>&nbsp; @lang('messages.Users')</a></li>
                </ul>
                {!! Form::open(['method'=>'GET','route' => ['user.list'],'class'=>'navbar-form navbar-left','role'=>'search'])!!}
                    <div class="form-group">
                        <input type="text" name=searchUsers class="form-control search-nav" placeholder="Search for researchers">
                    </div>
                {!! Form::close() !!}
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle menu-right" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user" aria-hidden="true"></i><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('user.edit') }}" class="text-center"><i class="glyphicon glyphicon-edit"></i>&nbsp; @lang('messages.Edit Profile')</a></li>

                            <li role="separator" class="divider"></li>
                            @if(App::getLocale() == "en")
                                <li><a href="{{ url('lang', ['es']) }}" class="text-center ">@lang('messages.Change to Spanish')</a></li>
                            @else
                                <li><a href=" {{ url('lang', ['en']) }}" class="text-center img-idioma li-menus">@lang('messages.Change to English')</a></li>
                            @endif
                            <li role="separator" class="divider"></li>
                            @if(!Auth::user())
                                <li><a href="{{ route('login') }}" class="text-center"><span class="glyphicon glyphicon-log-in"> </span> @lang('messages.Log in')</a></li>
                            @else
                                <li><a href="{{ route('logout') }}" class="text-center"><span class="glyphicon glyphicon-log-out"> </span> @lang('messages.Log out')</a></li>

                            @endif
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@yield('content')
    <section class="col-xs-12 col-xs-12 col-md-12 footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6 footerleft ">
                    <div class="logofooter"><img src="{{asset('images/logo.png')}}" width="64" alt=""> WARC-Repository</div>
                    <p>@lang('messages.WARC-Repository-title-description')</p>
                </div>
                <div class="col-md-8 col-sm-6 paddingtop-bottom">
                    <h6 class="heading7 h6Collaborators2" >@lang('messages.COLLABORATORS')</h6>
                    <a class="aSingLogo2" href="http://sing-group.org/"><img src="{{asset('images/singGroupLogo.png')}}"  height="128" width="128" ></a>
                    <a class="aESEILogo2" href="http://www.esei.uvigo.es/"><img src="{{asset('images/logo-esei-25-4.png')}} " ></a>
                    <a href="https://uvigo.gal/"><img src="{{asset('images/logo-vector-universidade-vigo.jpg')}}" width="256"></a>
                </div>
            </div>
        </div>
    </section>
</body>
    <script type="text/javascript" src="{{asset('js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
        $('#example').DataTable();
        });
    </script>
@yield('extrajs')
</html>
