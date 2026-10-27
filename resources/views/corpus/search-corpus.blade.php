@extends('layouts.layout')
@section('extracss')
    <link rel="stylesheet" href="{{asset('css/styles-searchCorpus.css')}}">
@endsection

@section('content')
    <div class="container-fluid principalContainerSearch" >
        <div class="row">
            <div>
                <div class="container myPadding0" >
                    <a href="#" class="btn btn-primary pull-right myMarginR10" id="filterApply">@lang('messages.Continue')</a>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active" >
                            <a href="#advanced-search" aria-controls="advanced-search"
                            role="tab" data-toggle="tab"><i class="glyphicon glyphicon-search"></i>&nbsp; @lang('messages.Advanced search')</a></li>
                    </ul>
                </div>
                <div class="tab-content col-xs-12 col-sm-12 col-md-12">
                    <div role="tabpanel" class="tab" id="advanced-search">
                    @include('corpus/partials/advanced-search')
                        <div role="tabpanel " class="tab-pane " id="home">
                        {!! Form::open(['route' => ['corpus.filter'], 'method' => 'POST', 'class' => 'form-horizontal', 'id ' => 'selectedCorpusForm', 'role' => 'filter']) !!}
                            @foreach ($corpuses as $corpus)
                                @include('corpus.partials.corpusToSearch', ['corpus' => $corpus])
                            @endforeach
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section ('extrajs')
    <script src="{{asset('js/checkbox.js')}}"></script>
    <script src="{{asset('js/incAndDecButtons.js')}}"></script>
@endsection