<div class="container col-xs-12 col-sm-12 col-md-10">
    <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-2 col-lg-10 col-lg-offset-2 content-card myPadding0">
        <div class="row seccion1-card col-xs-12 col-sm-12 col-md-5 col-lg-5">
            @if(!empty($corpus->user->photo) && file_exists( 'images/uploads/' . $corpus->user->photo ))
                <img src="{{ asset('images/uploads/' .  $corpus->user->photo )}}" alt="foto perfil" class="col-xs-12 col-xs-12 col-md-5 img-perfil imgUser">
            @else
                <img src="{{asset('images/user.svg')}}" alt="foto perfil" class="col-xs-12 col-xs-12 col-md-5 img-perfil imgUser">
            @endif
            <p class="col-xs-12 col-sm-12 col-md-5 mt10px"><b>{{ $corpus->user->name }}</b></p>
            <button type="button" name="view" class="pull-right col-xs-12 col-xs-12 col-md-3 follow pad5px">
                <strong><a class="tdecNone" href="{{ route('user.show', $corpus->user) }}">
                        <i class="glyphicon glyphicon-eye-open"></i>&nbsp; @lang('messages.View')</a></strong>
            </button>
        </div>
        <div class="row content-gray col-xs-12 col-sm-12 col-md-12 col-lg-12 limpio">
            <div class="row content-gray col-xs-12 col-sm-12 col-md-12 col-lg-12 limpio">
                <div class="col-xs-12 col-sm-12 col-md-5 medio-izquierdo">
                    <p class="col-xs-12 col-sm-12 col-md-12 nombre-arti">{!! $corpus->name !!}</p>
                    <input type="hidden" name="uuids[{!! $corpus->uuid !!}][]" value="{!! $corpus->uuid !!}">
                    <p class="col-xs-12 col-sm-12 col-md-12">{{ $corpus->description }}</p>
                </div>
            <div class="col-xs-12 col-sm-12 col-md-7 medio-derecho-toShow">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-inline">
                        <div class="input-group">
                            <span class="input-group-addon">@lang('messages.Content dates to include')</span>
                            <div class="form-group">
                                <select name="dates[{!! $corpus->uuid !!}][]" class="selectpicker" id="sel1" multiple required>
                                    <?php $dates = $corpus->getAllContentDatesArray($corpus);
                                    foreach($dates as $date){
                                        echo '<option class="taliCenter" selected >'.$date.'</option>';
                                    }?>
                                </select>
                            </div>
                        </div>
                            <div class="input-group">
                                <span class="input-group-addon">@lang('messages.Content-types to incude')</span>
                                <div class="form-group">
                                    <select name="contents[{!! $corpus->uuid !!}][]" class=" selectpicker" id="sel1" multiple required>
                                        <?php
                                        $types = $corpus->getAllContentTypesArray($corpus);
                                        foreach($types as $type){
                                            echo '<option selected class="taliCenter">'.$type.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="input-group">
                                <span class="input-group-addon">@lang('messages.Languages ​​to include')</span>
                                <div class="form-group">
                                    <select name="langs[{!! $corpus->uuid !!}][]" class=" selectpicker" id="sel1" multiple required>
                                        <?php $languages = $corpus->getAllContentLanguagesArray($corpus);
                                        foreach($languages as $lang){
                                            echo '<option selected class="taliCenter">'.$lang.'</option>';
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 check-selector">
                                <div class="checkbox options" >
                                    <label id="{{$corpus->uuid}}">
                                        <input required name="spam[{!! $corpus->uuid !!}][]" type="checkbox" value="spam" style="margin-left: 1rem"> Spam
                                        <input required name="ham[{!! $corpus->uuid !!}][]" type="checkbox" value="ham"style="margin-left: 1rem"> Ham
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-xs-12 col-sm-12 col-md-12 content-secion3 card-footer">
            <div class="col-xs-12 col-sm-12 col-md-6">
                <label class="descarga"><i class="fa fa-download" aria-hidden="true"></i>&nbsp; <span>{{ $corpus->downloads}}</span></label>
            </div>
            <a href="{{ route('download_corpus', $corpus->uuid) }}" class="btn btn-success pull-right myMarginR10">
                <span class="glyphicon glyphicon-download"></span> @lang('messages.Download corpus')</a>
        </div>
    </div>
</div>



