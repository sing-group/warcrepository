@extends('layouts.layout')
@section('extracss')
    <link rel="stylesheet" href="{{asset('css/styles-searchCorpus.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
@endsection
@section('content')
    <div class="container-fluid pincipalContainerFiltersetup" >
        <div class="">
            <div>
            {!! Form::open(['route' => ['corpus.joinCorpus'], 'method' => 'POST', 'class' => 'form-inline']) !!}
                <div class="container myPadding0">
                    {{ Form::submit( 'Join', array('class' => 'btn btn-primary pull-right')) }}
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">@lang('messages.Select join parameters')</a></li>
                    </ul>
                </div>
                <div class="tab-content col-xs-12 col-sm-12 col-md-12">
                    <div role="tabpanel " class="tab-pane active" id="home">
                        @foreach ($corpuses as $corpus)
                            @include('corpus/partials/corpus_parameters', ['corpus' => $corpus])
                        @endforeach
                    </div>
                </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section ('extrajs')
    <script type="text/javascript" src="{{asset('js/bootstrap-select.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('input[type=checkbox]').on('click', function(){
                var parent = $(this).parent().attr('id');
                $('#'+parent+' input[type=checkbox]').removeAttr('required');
                $(this).attr('required', 'required');
            });
        });
    </script>
@endsection
