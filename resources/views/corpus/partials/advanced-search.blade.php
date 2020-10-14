<div class="container content-buscador">
{!! Form::open(['route' => ['corpus.search'], 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'search']) !!}
    <div class="row col-xs-12 col-sm-12 col-md-12 limpio">
        <div class="dropdown col-xs-12 col-sm-12 col-md-3">
            <select class="form-control btn btn-primary dropdown-toggle col-xs-12 col-sm-12 col-md-12" id="sel1" name="status">
                <option value="" disabled selected>@lang('messages.Select source')</option>
                <option value="general">@lang('messages.General')</option>
                <option value="myCorpus">@lang('My Corpus')</option>
            </select>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search" name="name" value="{{ old('name') }}">
                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3">
            <div class="input-group">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-number dec" data-type="minus" data-field="quant[1]">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>
                <input type="number" name="size" class="form-control input-number" placeholder="Max size MB" min="1" max="999999999999999" value="{{ old('size') }}">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-number inc" data-type="plus" data-field="quant[1]">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3">
            <div class="input-group">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-number decSpam" data-type="minus2" data-field="quant[2]">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>
                <input id="maxSpam" type="number" name="spam_amount" class="form-control input-number" placeholder="% max spam" min="0" max="100" value="{{ old('spam_amount') }}">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-number incSpam" data-type="plus2" data-field="quant[2]">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
            </div>
        </div>
    </div>
    <div class="content-btns-buscador col-xs-12 col-sm-12 col-md-12">
        <div class="col-xs-12 col-sm-12 col-md-6">
            <button class="btn btn-primary btn-reset col-xs-12 col-sm-12 col-md-2">@lang('messages.Reset')</button>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <button class="btn btn-success col-xs-12 col-sm-12 col-md-3">@lang('messages.Search Corpus')</button>
        </div>
    </div>
{!! Form::close() !!}
</div>