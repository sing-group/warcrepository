@extends('layouts.layout')
@section('extracss')
        <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/styles-userHome.css')}}">
        <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Slab" rel="stylesheet">

@endsection
@section('content')
        <div class="container-fluid myMarginTop3em" >
            <div class="container">
                 <div class="content-info-user coninuser" >
                     <div class="col-md-12 barra-gris"></div>
                         <div class="col-md-6">
                             @if(!empty(Auth::user()->photo) && file_exists( 'images/uploads/' . Auth::user()->photo ))

                                 <img class="img-responsive foto" src="{{asset('images/uploads/'. Auth::user()->photo)}}" alt="foto de perfil">
                             @else
                                 <img class="img-responsive foto" src="{{asset('images/user.svg')}}" alt="foto de perfil">
                             @endif
                         </div>
                         <div class="info col-md-6">
                             <div >
                                <a class="btn btn-primary pull-right btn-editar" href="{{route('user.edit')}}"> @lang('messages.Edit') &nbsp&nbsp;<i class="fa fa-pencil" aria-hidden="true"></i></a>
                                <h4 ><strong>{{ Auth::user()->name }}</strong></h4>
                             </div>
                             <hr>
                             <div class="">
                                 <p><i class="fa fa-university">&nbsp&nbsp</i><span> {{ Auth::user()->institution }}</span></p>
                                 <p><i class="glyphicon glyphicon-envelope">&nbsp</i>{{ Auth::user()->email }}</p>
                             </div>
                             <hr>
                             <div class="">
                                <p><strong>@lang('messages.Date of joining:')&nbsp</strong><span> {{ Auth::user()->created_at }}</span></p>
                             </div>
                         </div>
                 </div>
                    <a href="{{route('corpus.search')}}" class="btn btn-primary pull-right myMarginR10" >
                        <span class="glyphicon glyphicon-plus"></span> @lang('messages.Search and operate with corpus')</a>
            </div>
                <div>
                    <!-- Nav tabs -->
                    <div class="container divNavtabs" >
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#mycorpus" aria-controls="mycorpus" role="tab" data-toggle="tab">@lang('messages.My corpus')</a></li>
                            <li role="presentation"><a href="#privatecorpus" aria-controls="privatecorpus" role="tab" data-toggle="tab">
                                    <i class="fa fa-user-secret"></i>&nbsp; @lang('messages.My private corpus')</a></li>
                            <li role="presentation"><a href="#trash" aria-controls="trash" role="tab" data-toggle="tab">
                                    <i class="glyphicon glyphicon-trash"></i>&nbsp; @lang('messages.Trash')</a></li>
                        </ul>
                    </div>
                    <!-- Tab panels -->
                    <div class="tab-content col-xs-12 col-sm-12 col-md-12" style="min-height: 300px !important">
                        <!-- Actividad Reciente -->
                        <div role="tabpanel " class="tab-pane active" id="mycorpus" >
                            {{-- inicio --}}
                            <div class="container col-xs-12 col-sm-12 col-md-10">
                                {{-- Tabla --}}
                                <div class="col-xs-9 col-sm-9 col-md-10 col-lg-9 content-principal">
                                    <table id="example" class="table table-striped table-bordered">
                                         <thead>
                                         <tr class="trbold">
                                                 <th> @lang('messages.Name')</th>
                                                 <th> @lang('Size')&nbsp; (Mbytes)</th>
                                                 <th> @lang('messages.Upload date')</th>
                                                 <th> @lang('messages.% Spam')</th>
                                                 <th> @lang('messages.View')</th>
                                                 <th> @lang('messages.Edit')</th>
                                                 <th> @lang('messages.Delete')</th>
                                                 <th> @lang('messages.Share Link')</th>
                                                 <th><i class="glyphicon glyphicon-cloud-download"></i>&nbsp; @lang('messages.Download')</th>
                                             </tr>
                                         </thead>
                                         <tbody class="tacenter">

                                             @foreach($publicCorpus as $corpus)
                                                 <tr class="trbold">
                                                     <td>{{$corpus->name}}</td>
                                                     <td>{{$corpus->size}}</td>
                                                     <td>{{ date( 'Y-m-d', strtotime($corpus->created_at )) }}</td>
                                                     <td>{{$corpus->spam_amount}}<i class="fa fa-percent"></i></td>
                                                     <td><button class="col-md-6 col-md-offset-3 btn btn-secondary mybtn-inline" data-toggle="modal" data-target="{{'#viewCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-eye-open"></i></button></td>
                                                     <td><button class="col-md-6 col-md-offset-3 btn btn-primary mybtn-inline" data-toggle="modal" data-target="{{'#editCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-edit"></i></button></td>
                                                     <td><button class="col-md-6 col-md-offset-3 btn btn-warning mybtn-inline" data-toggle="modal" data-target="{{'#confirmDeleteCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-trash"></i></button></td>
                                                     <td><button class="col-md-6 col-md-offset-3 btn btn-success mybtn-inline" data-toggle="modal" data-target="{{'#shareLinkCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-share-alt"></i></button></td>
                                                     <td><a href="{{ route('download_corpus', $corpus->uuid) }}" class="col-md-6 col-md-offset-3 btn btn-success mybtn-inline"><i class="glyphicon glyphicon-download-alt"></i></a></td>
                                                  </tr>
                                                 <!-- Modal Edit -->
                                                 <div class="modal fade" id="{{'editCorpusModal'.$corpus->uuid}}" tabindex="-1" role="dialog" aria-labelledby="editCorpusModal" aria-hidden="true">
                                                     <div class="modal-dialog" role="document">
                                                         <div class="modal-content">
                                                             <div class="modal-header">
                                                                 <h5 class="modal-title" id="exampleModalLabel">@lang('messages.Form to edit corpus:')&nbsp; <strong>{{$corpus->name}}</strong></h5>
                                                                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                             <span aria-hidden="true"></span>
                                                                         </button>
                                                             </div>
                                                             <div class="modal-body">
                                                                 {!! Form::open(['route' => ['corpus.update', $corpus->uuid], 'method' => 'PUT']) !!}
                                                                     <div class="form-group">
                                                                         <label for="corpusName">@lang('messages.New corpus name')</label>
                                                                         <input type="text" class="form-control"
                                                                                id="corpusName" name="corpus_name" maxlength="20" value="{{$corpus->name}}"/>
                                                                     </div>
                                                                     <div class="form-group">
                                                                         <label for="description">@lang('messages.New description:')</label>
                                                                         <textarea class="form-control" rows="5" id="description" name="description" value="{{$corpus->description}}" placeholder="{{$corpus->description}}" maxlength="140"></textarea>
                                                                     </div>
                                                                     <div class="checkbox">
                                                                         @if($corpus->status === 'public')
                                                                             <label>
                                                                                 <input name="make_private" type="checkbox"/> @lang('messages.Make private')
                                                                             </label>
                                                                         @else
                                                                             <label>
                                                                                 <input name="make_public" type="checkbox"/> @lang('messages.Make public')
                                                                             </label>
                                                                         @endif
                                                                     </div>
                                                             </div>
                                                             <div class="modal-footer">
                                                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Cancel')</button>
                                                                 <button type="submit" name="save" class="btn btn-success">@lang('messages.Save changes')</button>
                                                                 {!! Form::close() !!}
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <!-- Modal View Corpus -->
                                                 <div class="modal fade" id="{{'viewCorpusModal'.$corpus->uuid}}" tabindex="-1" role="dialog" aria-labelledby="viewCorpusModal" aria-hidden="true">
                                                     <div class="modal-dialog" role="document">
                                                         <div class="modal-content">
                                                             <div class="modal-header">
                                                                 <h5 class="modal-title" id="exampleModalLabel"><strong>{{$corpus->name}}</strong></h5>
                                                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                     <span aria-hidden="true">&times;</span>
                                                                 </button>
                                                             </div>
                                                             <div class="modal-body">
                                                                 <div class="card col-md-6" >
                                                                     <ul class="list-group list-group-flush">
                                                                         <li class="list-group-item">@lang('messages.Name')</li>
                                                                         <li class="list-group-item">@lang('messages.Size in Megabytes')</li>
                                                                         <li class="list-group-item special-itemLeft">@lang('messages.All Content Languages')</li>
                                                                         <li class="list-group-item special-itemLeft">@lang('messages.All Content Types')</li>
                                                                         <li class="list-group-item special-itemLeft">@lang('messages.All Content Dates')</li>
                                                                         <li class="list-group-item">@lang('messages.Total sites')</li>
                                                                         <li class="list-group-item">@lang('messages.Spam amount')</li>
                                                                         <li class="list-group-item">@lang('messages.Downloads')</li>
                                                                         <li class="list-group-item">@lang('messages.Created at')</li>
                                                                     </ul>
                                                                 </div>
                                                                 <div class="card col-md-6 tacenter" >
                                                                     <ul class="list-group list-group-flush">
                                                                         <li class="list-group-item">{{$corpus->name}}</li>
                                                                         <li class="list-group-item">{{$corpus->size}}</li>
                                                                         <li class="list-group-item special-itemRight">
                                                                             <button class="btn btn-primary dropdown-toggle"
                                                                                     type="button" data-toggle="dropdown" id="dropdownMenuButton">@lang('messages.Content-Languages')
                                                                                 <span class="caret"></span></button>
                                                                             <ul class="dropdown-menu" role="types" aria-labelledby="dropdownMenuButton">
                                                                                 <?php
                                                                                 $langs = explode(',',$corpus->all_content_languages);
                                                                                 foreach($langs as $lang){
                                                                                     echo '<li class="list-group-item">'.$lang.'</li>';
                                                                                 }
                                                                                 ?>
                                                                             </ul>
                                                                         </li>
                                                                         <li class="list-group-item special-itemRight">
                                                                             <button class="btn btn-primary dropdown-toggle"
                                                                                     type="button" data-toggle="dropdown" id="dropdownMenuButton">@lang('messages.Content-Types')
                                                                                 <span class="caret"></span></button>
                                                                             <ul class="dropdown-menu" role="types" aria-labelledby="dropdownMenuButton">
                                                                                 <?php
                                                                                 $types = explode(',',$corpus->all_content_types);
                                                                                 foreach($types as $type){
                                                                                     echo '<li class="list-group-item">'.$type.'</li>';
                                                                                 }
                                                                                 ?>
                                                                             </ul>
                                                                         </li>
                                                                         <li class="list-group-item special-itemRight">
                                                                             <button class="btn btn-primary dropdown-toggle"
                                                                                     type="button" data-toggle="dropdown" id="dropdownMenuButton">@lang('messages.Content-Dates')
                                                                                 <span class="caret"></span></button>
                                                                             <ul class="dropdown-menu" role="types" aria-labelledby="dropdownMenuButton">
                                                                                 <?php
                                                                                 $dates = explode(',',$corpus->all_content_dates);
                                                                                 foreach($dates as $date){
                                                                                     echo '<li class="list-group-item">'.$date.'</li>';
                                                                                 }
                                                                                 ?>
                                                                             </ul>
                                                                         </li>
                                                                         <li class="list-group-item">{{$corpus->total_sites}}</li>
                                                                         <li class="list-group-item">{{$corpus->spam_amount}}&nbsp; %</li>
                                                                         <li class="list-group-item">{{$corpus->downloads}}</li>
                                                                         <li class="list-group-item">{{$corpus->created_at}}</li>
                                                                     </ul>
                                                                 </div>
                                                                 <div class="card-body">
                                                                     <h3 class="card-title">@lang('messages.Description')</h3>
                                                                     <?php
                                                                     $description_fragmentes = str_split( $corpus->description , 50 );
                                                                     foreach($description_fragmentes as $fragment){
                                                                         echo '<p class="card-text descriptionCard" >'.$fragment.'</p>';
                                                                     }
                                                                     ?>
                                                                 </div>
                                                             </div>
                                                             <div class="modal-footer">
                                                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Close')</button>
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <!-- Modal share link Corpus -->
                                                 <div class="modal fade" id="{{'shareLinkCorpusModal'.$corpus->uuid}}" tabindex="-1" role="dialog" aria-labelledby="shareLinkCorpusModal" aria-hidden="true">
                                                     <div class="modal-dialog" role="document">
                                                         <div class="modal-content">
                                                             <div class="modal-header">
                                                                 <h5 class="modal-title" id="exampleModalLabel">@lang('messages.Link to share corpus:')&nbsp; <strong>{{$corpus->name}}</strong></h5>
                                                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                     <span aria-hidden="true">&times;</span>
                                                                 </button>
                                                             </div>
                                                             <div class="modal-body">
                                                                 <a href="{{ route('download_corpus', $corpus->uuid) }}"><strong>{{ route('download_corpus', $corpus->uuid) }}</strong></a>
                                                             </div>
                                                             <div class="modal-footer">
                                                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Close')</button>
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <!-- Modal confirm delete Corpus -->
                                                 <div class="modal fade" id="{{'confirmDeleteCorpusModal'.$corpus->uuid}}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteCorpusModal" aria-hidden="true">
                                                     <div class="modal-dialog" role="document">
                                                         <div class="modal-content">
                                                             <div class="modal-header">
                                                                 <h5 class="modal-title" id="exampleModalLabel">@lang('messages.Are you sure to delete this corpus?')</h5>
                                                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                     <span aria-hidden="true">&times;</span>
                                                                 </button>
                                                             </div>
                                                             <div class="modal-body">
                                                                 <strong>{{$corpus->name}}</strong>
                                                             </div>
                                                             <div class="modal-footer">
                                                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Cancel')</button>
                                                                 <a href="{{route('corpus.delete',$corpus->uuid)}}" type="button" class="btn btn-primary">@lang('messages.Confirm')</a>
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                             @endforeach
                                         </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel " class="tab-pane " id="privatecorpus">
                            {{-- inicio --}}
                            <div class="container col-xs-12 col-sm-12 col-md-10">
                                <div class="col-xs-9 col-sm-9 col-md-10 col-lg-9 content-principal">
                                    <table id="example1" class="table table-striped table-bordered">
                                        <thead>
                                        <tr class="trbold" >
                                            <th> @lang('messages.Name')</th>
                                            <th> @lang('Size')&nbsp; (Mbytes)</th>
                                            <th> @lang('messages.Upload date')</th>
                                            <th> @lang('messages.% Spam')</th>
                                            <th> @lang('messages.View')</th>
                                            <th> @lang('messages.Edit')</th>
                                            <th> @lang('messages.Delete')</th>
                                            <th> @lang('messages.Share Link')</th>
                                            <th><i class="glyphicon glyphicon-cloud-download"></i>&nbsp; @lang('messages.Download')</th>
                                        </tr>
                                        </thead>
                                        <tbody class="tacenter">
                                        @foreach($privateCorpus as $corpus)
                                            <tr class="trbold">
                                                <td>{{$corpus->name}}</td>
                                                <td>{{$corpus->size}}</td>
                                                <td>{{ date( 'Y-m-d', strtotime($corpus->created_at )) }}</td>
                                                <td>{{$corpus->spam_amount}}<i class="fa fa-percent"></i></td>
                                                <td><button class="col-md-6 col-md-offset-3 btn btn-secondary mybtn-inline" data-toggle="modal" data-target="{{'#viewCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-eye-open"></i></button></td>
                                                <td><button class="col-md-6 col-md-offset-3 btn btn-primary mybtn-inline" data-toggle="modal" data-target="{{'#editCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-edit"></i></button></td>
                                                <td><button class="col-md-6 col-md-offset-3 btn btn-warning mybtn-inline" data-toggle="modal" data-target="{{'#confirmDeleteCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-trash"></i></button></td>
                                                <td><button class="col-md-6 col-md-offset-3 btn btn-success mybtn-inline" data-toggle="modal" data-target="{{'#shareLinkCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-share-alt"></i></button></td>
                                                <td><a href="{{ route('download_corpus', $corpus->uuid) }}" class="col-md-6 col-md-offset-3 btn btn-success mybtn-inline"><i class="glyphicon glyphicon-download-alt"></i></a></td>
                                            </tr>
                                            <!-- Modal Edit -->
                                            <div class="modal fade" id="{{'editCorpusModal'.$corpus->uuid}}" tabindex="-1" role="dialog" aria-labelledby="editCorpusModal" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">@lang('messages.Form to edit corpus:')&nbsp; <strong>{{$corpus->name}}</strong></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true"></span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {!! Form::open(['route' => ['corpus.update', $corpus->uuid], 'method' => 'PUT']) !!}
                                                            <div class="form-group">
                                                                <label for="corpusName">@lang('messages.New corpus name')</label>
                                                                <input type="text" maxlength="20" class="form-control"
                                                                       id="corpusName" name="corpus_name" value="{{$corpus->name}}"/>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="description">@lang('messages.New description:')</label>
                                                                <textarea class="form-control" rows="5" id="description" name="description" value="{{$corpus->description}}" placeholder="{{$corpus->description}}" maxlength="140"></textarea>
                                                            </div>
                                                            <div class="checkbox">
                                                                @if($corpus->status === 'public')
                                                                    <label>
                                                                        <input name="make_private" type="checkbox"/> @lang('messages.Make private')
                                                                    </label>
                                                                @else
                                                                    <label>
                                                                        <input name="make_public" type="checkbox"/> @lang('messages.Make public')
                                                                    </label>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Cancel')</button>
                                                            <button type="submit" name="save" class="btn btn-success">@lang('messages.Save changes')</button>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal View Corpus -->
                                            <div class="modal fade" id="{{'viewCorpusModal'.$corpus->uuid}}" tabindex="-1" role="dialog" aria-labelledby="viewCorpusModal" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"><strong>{{$corpus->name}}</strong></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card col-md-6" >
                                                                <ul class="list-group list-group-flush">
                                                                    <li class="list-group-item">@lang('messages.Name')</li>
                                                                    <li class="list-group-item">@lang('messages.Size in Megabytes')</li>
                                                                    <li class="list-group-item special-itemLeft">@lang('messages.All Content Languages')</li>
                                                                    <li class="list-group-item special-itemLeft">@lang('messages.All Content Types')</li>
                                                                    <li class="list-group-item special-itemLeft">@lang('messages.All Content Dates')</li>
                                                                    <li class="list-group-item">@lang('messages.Total sites')</li>
                                                                    <li class="list-group-item">@lang('messages.Spam amount')</li>
                                                                    <li class="list-group-item">@lang('messages.Downloads')</li>
                                                                    <li class="list-group-item">@lang('messages.Created at')</li>
                                                                </ul>
                                                            </div>
                                                            <div class="card col-md-6 tacenter" >
                                                                <ul class="list-group list-group-flush">
                                                                    <li class="list-group-item">{{$corpus->name}}</li>
                                                                    <li class="list-group-item">{{$corpus->size}}</li>
                                                                    <li class="list-group-item special-itemRight">
                                                                        <button class="btn btn-primary dropdown-toggle"
                                                                                type="button" data-toggle="dropdown" id="dropdownMenuButton">@lang('messages.Content-Languages')
                                                                            <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu" role="types" aria-labelledby="dropdownMenuButton">
                                                                            <?php
                                                                            $langs = explode(',',$corpus->all_content_languages);
                                                                            foreach($langs as $lang){
                                                                                echo '<li class="list-group-item">'.$lang.'</li>';
                                                                            }
                                                                            ?>
                                                                        </ul>
                                                                    </li>
                                                                    <li class="list-group-item special-itemRight">
                                                                        <button class="btn btn-primary dropdown-toggle"
                                                                                type="button" data-toggle="dropdown" id="dropdownMenuButton">@lang('messages.Content-Types')
                                                                            <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu" role="types" aria-labelledby="dropdownMenuButton">
                                                                            <?php
                                                                            $types = explode(',',$corpus->all_content_types);
                                                                            foreach($types as $type){
                                                                                echo '<li class="list-group-item">'.$type.'</li>';
                                                                            }
                                                                            ?>
                                                                        </ul>
                                                                    </li>
                                                                    <li class="list-group-item special-itemRight">
                                                                        <button class="btn btn-primary dropdown-toggle"
                                                                                type="button" data-toggle="dropdown" id="dropdownMenuButton">@lang('messages.Content-Dates')
                                                                            <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu" role="types" aria-labelledby="dropdownMenuButton">
                                                                            <?php
                                                                            $dates = explode(',',$corpus->all_content_dates);
                                                                            foreach($dates as $date){
                                                                                echo '<li class="list-group-item">'.$date.'</li>';
                                                                            }
                                                                            ?>
                                                                        </ul>
                                                                    </li>
                                                                    <li class="list-group-item">{{$corpus->total_sites}}</li>
                                                                    <li class="list-group-item">{{$corpus->spam_amount}}&nbsp; %</li>
                                                                    <li class="list-group-item">{{$corpus->downloads}}</li>
                                                                    <li class="list-group-item">{{$corpus->created_at}}</li>
                                                                </ul>
                                                            </div>
                                                            <div class="card-body">
                                                                <h3 class="card-title">@lang('messages.Description')</h3>
                                                            <?php
                                                                $description_fragmentes = str_split( $corpus->description , 50 );
                                                                foreach($description_fragmentes as $fragment){
                                                                    echo '<p class="card-text descriptionCard" >'.$fragment.'</p>';
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Close')</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal share link Corpus -->
                                            <div class="modal fade" id="{{'shareLinkCorpusModal'.$corpus->uuid}}" tabindex="-1" role="dialog" aria-labelledby="shareLinkCorpusModal" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">@lang('messages.Link to share corpus:')&nbsp; <strong>{{$corpus->name}}</strong></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <a href="{{ route('download_corpus', $corpus->uuid) }}"><strong>{{ route('download_corpus', $corpus->uuid) }}</strong></a>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Close')</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal confirm delete Corpus -->
                                            <div class="modal fade" id="{{'confirmDeleteCorpusModal'.$corpus->uuid}}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteCorpusModal" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">@lang('messages.Are you sure to delete this corpus?')</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <strong>{{$corpus->name}}</strong>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Cancel')</button>
                                                            <a href="{{route('corpus.delete',$corpus->uuid)}}" type="button" class="btn btn-primary">@lang('messages.Confirm')</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel " class="tab-pane " id="trash">
                            {{-- inicio --}}
                            <div class="col-md-2 col-md-offset-10 col-lg-offset-8 myMarginTop2rem" ><button class="btn btn-default " data-toggle="modal" data-target="#emptyTrashModal"><i class="glyphicon glyphicon-trash"></i>&nbsp; Empty trash</button></div>
                            <div class="container col-xs-12 col-sm-12 col-md-10">
                                <div class="col-xs-9 col-sm-9 col-md-10 col-lg-9 content-principal">
                                    {{--tabla--}}
                                    <table id="example2" class="table table-striped table-bordered">
                                        <thead>
                                        <tr class="trbold">
                                            <th> @lang('messages.Name')</th>
                                            <th> @lang('Size')&nbsp; (Mbytes)</th>
                                            <th> @lang('messages.Upload date')</th>
                                            <th> @lang('messages.% Spam')</th>
                                            <th> @lang('messages.View')</th>
                                            <th> @lang('messages.Edit')</th>
                                            <th> @lang('messages.Delete')</th>
                                            <th> @lang('messages.Share Link')</th>
                                            <th><i class="glyphicon glyphicon-refresh"></i>&nbsp @lang('messages.Restore')</th>
                                        </tr>
                                        </thead>
                                        <tbody class="tacenter">
                                        @foreach($trashCorpus as $corpus)
                                            <tr class="trbold">
                                                <td>{{$corpus->name}}</td>
                                                <td>{{$corpus->size}}</td>
                                                <td>{{ date( 'Y-m-d', strtotime($corpus->created_at )) }}</td>
                                                <td>{{$corpus->spam_amount}}<i class="fa fa-percent"></i></td>
                                                <td><button class="col-md-6 col-md-offset-3 btn btn-secondary mybtn-inline" data-toggle="modal" data-target="{{'#viewCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-eye-open"></i></button></td>
                                                <td><button disabled class="col-md-6 col-md-offset-3 btn btn-primary mybtn-inline" data-toggle="modal" data-target="{{'#editCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-edit"></i></button></td>
                                                <td><button class="col-md-6 col-md-offset-3 btn btn-warning mybtn-inline" data-toggle="modal" data-target="{{'#confirmDeleteCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-trash"></i></button></td>
                                                <td><button class="col-md-6 col-md-offset-3 btn btn-success mybtn-inline" data-toggle="modal" data-target="{{'#shareLinkCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-share-alt"></i></button></td>
                                                <td><button class="col-md-6 col-md-offset-3 btn btn-success mybtn-inline" data-toggle="modal" data-target="{{'#restoreCorpusModal'.$corpus->uuid}}"><i class="glyphicon glyphicon-refresh"></i></button></td>
                                            </tr>
                                            <!-- Modal View Corpus -->
                                            <div class="modal fade" id="{{'viewCorpusModal'.$corpus->uuid}}" tabindex="-1" role="dialog" aria-labelledby="viewCorpusModal" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"><strong>{{$corpus->name}}</strong></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card col-md-6" >
                                                                <ul class="list-group list-group-flush">
                                                                    <li class="list-group-item">@lang('messages.Name')</li>
                                                                    <li class="list-group-item">@lang('messages.Size in Megabytes')</li>
                                                                    <li class="list-group-item special-itemLeft">@lang('messages.All Content Languages')</li>
                                                                    <li class="list-group-item special-itemLeft">@lang('messages.All Content Types')</li>
                                                                    <li class="list-group-item special-itemLeft">@lang('messages.All Content Dates')</li>
                                                                    <li class="list-group-item">@lang('messages.Total sites')</li>
                                                                    <li class="list-group-item">@lang('messages.Spam amount')</li>
                                                                    <li class="list-group-item">@lang('messages.Downloads')</li>
                                                                    <li class="list-group-item">@lang('messages.Created at')</li>
                                                                </ul>
                                                            </div>
                                                            <div class="card col-md-6 tacenter" >
                                                                <ul class="list-group list-group-flush">
                                                                    <li class="list-group-item">{{$corpus->name}}</li>
                                                                    <li class="list-group-item">{{$corpus->size}}</li>
                                                                    <li class="list-group-item special-itemRight">
                                                                        <button class="btn btn-primary dropdown-toggle"
                                                                                type="button" data-toggle="dropdown" id="dropdownMenuButton">@lang('messages.Content-Languages')
                                                                            <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu" role="types" aria-labelledby="dropdownMenuButton">
                                                                            <?php
                                                                            $langs = explode(',',$corpus->all_content_languages);
                                                                            foreach($langs as $lang){
                                                                                echo '<li class="list-group-item">'.$lang.'</li>';
                                                                            }
                                                                            ?>
                                                                        </ul>
                                                                    </li>
                                                                    <li class="list-group-item special-itemRight">
                                                                        <button class="btn btn-primary dropdown-toggle"
                                                                                type="button" data-toggle="dropdown" id="dropdownMenuButton">@lang('messages.Content-Types')
                                                                            <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu" role="types" aria-labelledby="dropdownMenuButton">
                                                                            <?php
                                                                            $types = explode(',',$corpus->all_content_types);
                                                                            foreach($types as $type){
                                                                                echo '<li class="list-group-item">'.$type.'</li>';
                                                                            }
                                                                            ?>
                                                                        </ul>
                                                                    </li>
                                                                    <li class="list-group-item special-itemRight">
                                                                        <button class="btn btn-primary dropdown-toggle"
                                                                                type="button" data-toggle="dropdown" id="dropdownMenuButton">@lang('messages.Content-Dates')
                                                                            <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu" role="types" aria-labelledby="dropdownMenuButton">
                                                                            <?php
                                                                            $dates = explode(',',$corpus->all_content_dates);
                                                                            foreach($dates as $date){
                                                                                echo '<li class="list-group-item">'.$date.'</li>';
                                                                            }
                                                                            ?>
                                                                        </ul>
                                                                    </li>
                                                                    <li class="list-group-item">{{$corpus->total_sites}}</li>
                                                                    <li class="list-group-item">{{$corpus->spam_amount}}&nbsp; %</li>
                                                                    <li class="list-group-item">{{$corpus->downloads}}</li>
                                                                    <li class="list-group-item">{{$corpus->created_at}}</li>
                                                                </ul>
                                                            </div>
                                                            <div class="card-body">
                                                                <h3 class="card-title">@lang('messages.Description')</h3>
                                                                <?php
                                                                $description_fragmentes = str_split( $corpus->description , 50 );
                                                                foreach($description_fragmentes as $fragment){
                                                                    echo '<p class="card-text descriptionCard" >'.$fragment.'</p>';
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Close')</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- Modal share link Corpus -->
                                            <div class="modal fade" id="{{'shareLinkCorpusModal'.$corpus->uuid}}" tabindex="-1" role="dialog" aria-labelledby="shareLinkCorpusModal" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">@lang('messages.Link to share corpus:')&nbsp; <strong>{{$corpus->name}}</strong></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <a href="{{ route('download_corpus', $corpus->uuid) }}"><strong>{{ route('download_corpus', $corpus->uuid) }}</strong></a>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Close')</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- Modal confirm delete Corpus -->
                                        <div class="modal fade" id="{{'confirmDeleteCorpusModal'.$corpus->uuid}}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteCorpusModal" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">@lang('messages.Are you sure to delete this corpus permanently?')</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <strong>{{$corpus->name}}</strong>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Cancel')</button>
                                                        <a href="{{route('corpus.remove', $corpus->uuid)}}" type="button" class="btn btn-primary">@lang('messages.Confirm')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal confirm restore Corpus -->
                                        <div class="modal fade" id="{{'restoreCorpusModal'.$corpus->uuid}}" tabindex="-1" role="dialog" aria-labelledby="restorCorpusModal" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">@lang('messages.Do you want to restore this corpus?')</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <strong>{{$corpus->name}}</strong>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Cancel')</button>
                                                        <a href="{{route('corpus.restore',$corpus->uuid)}}" type="button" class="btn btn-primary">@lang('messages.Confirm')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="emptyTrashModal" tabindex="-1" role="dialog" aria-labelledby="emptyTrashModal" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">@lang('messages.Are you sure to empty trash permanently?')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Cancel')</button>
                                    <a href="{{route('corpus.emptyTrash')}}" type="button" class="btn btn-primary">@lang('messages.Confirm')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>


@endsection

@section ('extrajs')

    <script src="{{asset('js/dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable();
        } );

        $(document).ready(function() {
            $('#example1').DataTable();
        } );

        $(document).ready(function() {
            $('#example2').DataTable();
        } );
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            // other options
        });
    </script>
@endsection
