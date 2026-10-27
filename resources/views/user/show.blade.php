
@extends('layouts.layout')
@section('extracss')
    <link rel="stylesheet" href="{{asset('css/styles-userShow.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles-userHome.css')}}">
@endsection

@section('content')
    <div class="container-fluid containerFluidUserShow">
        <div class="container">
            <div class="content-info-user contInfoUser" >
                <div class="col-md-12 barra-gris"></div>
                <div class="col-md-6">
                    @if(!empty($user->photo) && file_exists( 'images/uploads/' . $user->photo ))
                        <img class="img-responsive foto" src="{{ asset('images/uploads/' .  $user->photo )}}" alt="foto de perfil">
                    @else
                        <img class="img-responsive foto" src="{{asset('images/user.svg')}}" alt="foto de perfil">
                    @endif
                </div>
                <div class="info col-md-6">
                    <div >
                        <h4><strong><i class="glyphicon glyphicon-user"></i>&nbsp; {{ $user->name }}</strong></h4>
                    </div>
                    <hr>
                    <div class="">
                        <p><i class="fa fa-university"></i>&nbsp; {{ $user->institution }} </p>
                        <p><i class="glyphicon glyphicon-envelope"></i>&nbsp; {{ $user->email }}</p>
                    </div>
                    <hr>
                    <div class="">
                        <p><strong><i class="glyphicon glyphicon-calendar"></i>&nbsp; @lang('messages.Date of joining:')</strong>
                            <span>&nbsp; {{ $user->created_at }}</span></p>
                    </div>
                </div>
            </div>
        </div>
            <div>
                <div class="container myPadding0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">
                                <i class="glyphicon glyphicon-object-align-right"></i>&nbsp; @lang('messages.Public corpus')</a></li>
                    </ul>
                </div>
                <div class="tab-content col-xs-12 col-sm-12 col-md-12">
                    <div role="tabpanel " class="tab-pane active" id="actividad-reciente" style="min-height: 300px">
                        @foreach ($corpus as $corpus)
                            @include('corpus.partials.corpus', ['corpus' => $corpus])
                        @endforeach
                    </div>
                </div>
            </div>
    </div>
    </div>
    </div>
    </div>
@endsection
@section ('extrajs')

@endsection
