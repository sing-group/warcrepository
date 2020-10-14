@extends('layouts.layout')
@section('extracss')
    <link rel="stylesheet" href="{{asset('css/styles-userList.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-combined.min.css')}}" id="bootstrap-css">
@endsection
@section('content')
    <div class="container principalcontainerList">
        <div>
            <div>
                <div class="myPadding0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#alphabetical_order" aria-controls="alphabetical_order" role="tab" data-toggle="tab">
                                <i class="glyphicon glyphicon-sort-by-alphabet"></i>&nbsp; @lang('messages.users')</a></li>
                    </ul>
                </div>
                <div class="tab-content col-xs-12 col-sm-12 col-md-12">
                    <div role="tabpanel " class="tab-pane active" id="alphabetical_order">
                        @foreach ($users as $user)
                        <div class="col-lg-6">
                            <div >
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="container well span5 minHW" >
                                    	<div class="row-fluid">
                                            <div class="span2" >
                                                @if(!empty($user->photo) && file_exists( 'images/uploads/' . $user->photo ))
                                                    <img class="img-circle" src="{{ asset('images/uploads/' .  $user->photo )}}" height="58" width="58" alt="foto de perfil">
                                                @else
                                                    <img class="img-circle" src="{{ asset('images/user.svg/' .  $user->photo )}}" alt="foto de perfil">
                                                @endif
                                            </div>

                                            <div class="span8">
                                                <h3><i class="glyphicon glyphicon-user"></i>&nbsp; {!! $user->name !!}</h3>
                                                <div class="myMarginTop2rem">
                                                    <h5><i class="glyphicon glyphicon-envelope"></i>&nbsp; {!! $user->email !!}</h5>
                                                    <div class="overFlowTextOutput">
                                                        <h5><i class="fa fa-university" ></i>&nbsp; {!! $user->institution !!}</h5>
                                                    </div>
                                                    <h5><i class="glyphicon glyphicon-pushpin"></i>&nbsp; {{ $user->corpus()->count()}}&nbsp; @lang('messages.corpus published')</h5>
                                                </div>
                                            </div>

                                            <div class="span2">
                                                <div class="btn-group row followButtonmargin">
                                                    <a class="btn btn-info"  href="{{ route('user.show', $user) }}">
                                                        <span><i class="glyphicon glyphicon-eye-open" ></i> &nbsp; @lang('messages.View')</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section ('extrajs')
    <script type="text/javascript">
        $("#div-1").limitChar(1, true);

    </script>
@endsection
