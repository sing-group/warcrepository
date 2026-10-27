@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 20rem !important">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>@lang('messages.Success')</h4></div>
                    <div class="panel-body" style="    padding-bottom: 12rem;">
                        <div class="form-horizontal" >
                            <div class="jumbotron">
                                <h3 style="text-align: center">@lang('messages.Check your e-mail to complet your registration')</h3>
                            </div>
                            <div class="col-md-6 col-md-offset-4" style="margin-top: 1rem">
                                <a href="{{route('login')}}" class="btn btn-primary">
                                    @lang('messages.Back to login page')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
