<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>WARC-Repository</title>
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/styles.css')}}">
        <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Slab" rel="stylesheet">
    </head>
    <body>
    <nav class="navbar bg-primary navbar-fixed-top">
        <div class="container-fluid index-nav-border">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed navmovil" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar navmovil-iconos"></span>
                    <span class="icon-bar navmovil-iconos"></span>
                    <span class="icon-bar navmovil-iconos"></span>
                </button>
                <a class="navbar-brand" href="{{route('/')}}">
                    <img src="{{asset('images/logo.png')}}" alt="logo empresa" class="marginTopNav14 img-responsive img-logo">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="{{route('/')}}" class=" li-menus txt-blanco "><i class="glyphicon glyphicon-home"></i>&nbsp; @lang('messages.home')</a></li>
                    <li><a href="http://sing.ei.uvigo.es/" class=" li-menus txt-blanco li-menus-borde-derecha"><span class="glyphicon glyphicon-info-sign"></span>&nbsp; @lang('messages.About us') </a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ route('registration') }}" class="txt-blanco li-menus"><span class="glyphicon glyphicon-user"> </span>&nbsp; @lang('messages.Join for free')</a></li>
                    <li><a href="{{ route('login') }}" class="txt-blanco li-menus "><span class="glyphicon glyphicon-log-in"> </span> @lang('messages.Log in')</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle menu-right li-menus txt-blanco" data-toggle="dropdown"
                           role="button" aria-haspopup="true" aria-expanded="false"> @lang('messages.Language')<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @if(App::getLocale() == "en")
                                <li><a href="{{ url('lang', ['es']) }}" class="text-center ">@lang('messages.Change to Spanish')</a></li>
                            @else
                                <li><a href=" {{ url('lang', ['en']) }}" class="text-center img-idioma li-menus">@lang('messages.Change to English')</a></li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
        <div class="container loginPrincipalContainer" >
            <div class="col-xs-12 col-sm-12 col-md-12">
                <img src="images/logo.png" alt="Logo empresa" class="img-responsive logo-empresa loginimgLogo" >
            </div>
            <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 loginBoxmargin5">
                <div class="alert alert-success loginFontSize13" >
                    @lang('messages.Try our web application without registering. Use User: Jane or John and Password: secret.')
                </div>
                @if (count($errors))
                    <div class="alert alert-danger errors" style="padding-left: 5% !important;">
                        <ul>
                            @foreach($errors->all() as $error)

                                <li><i class="glyphicon glyphicon-alert">&nbsp; </i>{!! $error !!}</li>

                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">@lang('messages.Sign In')</div>
                        <div class="logindivForgotPass"><a href="{{ route('password.reset') }}">@lang('messages.Forgot password?')</a></div>
                    </div>
                    <div class="panel-body loginContainerForm" >
                        <div id="loginform" class="form-horizontal" role="form">
                        {!! Form::open(['method' => 'POST', 'route' => 'login']) !!}
                            <div  class="input-group loginMarginBotton25">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <div class="{{ $errors->has('email') ? ' has-error' : '' }}" style="margin-right:0px; margin-left:0px">
                                        <input id="login-username" type="text" class="form-control" name="name" value="{{ old('name') }}"
                                               placeholder="user_name or email" required autofocus>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                            <div class="input-group loginMarginBotton25">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <div class="{{ $errors->has('password') ? ' has-error' : '' }}" style="margin-right:0px; margin-left:0px">
                                        <input id="login-password" type="password" class="form-control" name="password" required>
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                            <div class="input-group mb10px">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> @lang('messages.Remember me')
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mt10px">
                                <div class="col-sm-12 controls">
                                  <button type="submit" class="btn btn-primary mb10px mt10px" >
                                      @lang('messages.Log in')
                                  </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 control">
                                    <div class=loginDivDYHAA >
                                        @lang('messages.Dont have an account!')
                                        <a href="{{ route('registration') }}">
                                            @lang('messages.Sign Up Here')
                                        </a>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <section class="col-xs-12 col-xs-12 col-md-12 footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6 footerleft ">
                    <div class="logofooter"><img src="{{asset('images/logo.png')}}" width="64" alt=""> WARC-Repository</div>
                    <p>@lang('messages.WARC-Repository-title-description')</p>
                </div>
                <div class="col-md-8 col-sm-6 paddingtop-bottom">
                    <h6 class="heading7 loginCollaborators" >@lang('messages.COLLABORATORS')</h6>
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
</html>
