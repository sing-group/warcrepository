
<div class="container col-xs-12 col-sm-12 col-md-10">
    <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-2 col-lg-10 col-lg-offset-2 content-card myPadding0">
        <div class="row seccion1-card col-xs-12 col-sm-12 col-md-5 col-lg-5">
            @if(!empty($corpus->user->photo) && file_exists( 'images/uploads/' . $corpus->user->photo ))
                <img src="{{ asset('images/uploads/' .  $corpus->user->photo )}}" alt="foto perfil" class="col-xs-12 col-xs-12 col-md-5 img-perfil imgUser">
            @else
                <img src="{{asset('images/user.svg')}}" alt="foto perfil" class="col-xs-12 col-xs-12 col-md-5 img-perfil imgUser">
            @endif

            <p class="col-xs-12 col-sm-12 col-md-5 mt10px" ><b>{{ $corpus->user->name }}</b></p>
            <button type="button" name="view" class="pull-right col-xs-12 col-xs-12 col-md-3 follow pad5px">
                <strong><a class="tdecNone" href="{{ route('user.show', $corpus->user) }}">
                        <i class="glyphicon glyphicon-eye-open"></i>&nbsp; @lang('messages.View')</a></strong>
            </button>
        </div>
        <div class="row content-gray col-xs-12 col-sm-12 col-md-12 col-lg-12 limpio">
            <div class="col-xs-12 col-sm-12 col-md-5 medio-izquierdo">
                <p class="col-xs-12 col-sm-12 col-md-12 nombre-arti">{{ $corpus->name }}</p>
                <?php
                $description_fragmentes = str_split( $corpus->description , 40 );
                foreach($description_fragmentes as $fragment){
                    echo '<p class="col-xs-12 col-sm-12 col-md-12">'.$fragment.'</p>';
                }
                ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-7 medio-derecho">
                <div class="col-xs-12 col-sm-12 col-md-5 divBorderMD">
                    <p class="txt-m"><i class="fa fa-archive" aria-hidden="true"></i>&nbsp; @lang('messages.Size'):&nbsp; <strong>{{ $corpus->size}} (MB)</strong></p>
                    <p class="txt-m"><i class="fa fa-thermometer-three-quarters"></i>&nbsp;&nbsp;&nbsp;   Spam:&nbsp;<strong>{{ $corpus->spam_amount}}</strong><i class="fa fa-percent"></i></p>
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="dropdownMenuButton2">@lang('messages.Languages')
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="langs" aria-labelledby="dropdownMenuButton2">
                        <?php $languages = $corpus->getAllContentLanguagesArray($corpus);
                        foreach($languages as $lang){
                            echo '<li class="taliCenter">'.$lang.'</li>';
                        }?>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-7">
                    <p class="txt-m"><i class="fa fa-upload" aria-hidden="true"></i> @lang('messages.Upload date:') <strong>{{ $corpus->getDate()}}</strong></p>

                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="dropdownMenuButton">@lang('messages.Content-Types')
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="types" aria-labelledby="dropdownMenuButton">
                        <?php
                        $types = $corpus->getAllContentTypesArray($corpus);
                        foreach($types as $type){
                            echo '<li class="taliCenter">'.$type.'</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row col-xs-12 col-sm-12 col-md-12 content-secion3 card-footer">
            <div class="col-xs-12 col-sm-12 col-md-6">
                <label class="descarga"><i class="fa fa-download" aria-hidden="true"></i>&nbsp; <span>{{ $corpus->downloads}}</span></label>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-6">
                <a href="{{ route('download_corpus', $corpus->uuid) }}" class="btn btn-success col-xs-12 col-sm-12 col-md-6">
                    <strong><i class="fa fa-download" aria-hidden="true"></i>&nbsp;@lang('messages.Download corpus')</strong></a>
            </div>
        </div>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-2">
    <span class="button-checkbox">
        <button type="button" class="btn" data-color="primary">
        </button>
        <input type="checkbox" name="corpus[]" class="hidden corpusCheck" id="{!! $corpus->uuid !!}" value="{!! $corpus->uuid !!}"  />
    </span>
</div>
