@extends('layouts.layout')

@section('extracss')
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/styles-editProfile.css')}}">
        <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Slab" rel="stylesheet">
@endsection
@section('content')
       <div class="container myMarginTop3em" >
           <h3 class="text-left">@lang('messages.Edit your profile')</h3>
           <hr>
           @if (count($errors))
               <div class="alert alert-danger errors" style="padding-left: 5% !important;">
                   <ul>
                       @foreach($errors->all() as $error)

                           <li><i class="glyphicon glyphicon-alert">&nbsp; </i>{!! $error !!}</li>

                       @endforeach
                   </ul>
               </div>
           @endif
           <div class="flex">
               <div class="col-xs-12 col-sm-12 col-md-5 limpio flex-img">
                   @if(!empty(Auth::user()->photo) && file_exists( 'images/uploads/' . Auth::user()->photo ))
                       <img src="{{asset('images/uploads/' . Auth::user()->photo) }}" alt="Imagen de Perfil" class="img-perfil col-md-offset-5">
                   @else
                       <img src="{{asset('images/defaultEditProfileImg.png')}}" alt="Imagen de Perfil" class="img-perfil col-md-offset-5">
                   @endif
               </div>
               <div class="container col-xs-12 col-sm-12 col-md-5 flex-forms limpio">
                   {!! Form::open(['route' => ['user.update', Auth::user()], 'method' => 'PUT', 'files' => true, 'class' => 'form-horizontal']) !!}
                       <input name="_method" type="hidden" value="PUT">

                       <div class="form-group">
                           <label class="col-xs-4 col-sm-4 col-md-4" >Name</label>
                           <div class="input-group">
                               <div class="input-group-addon anchoMinimo39"><i class="fa fa-user" aria-hidden="true"></i></div>
                               <input type="text" class="form-control col-xs-8 col-sm-8 col-md-8" id="username" name="name" placeholder="" value="{!!  Auth::user()->name !!}">
                           </div>
                       </div>

                       <div class="form-group">
                           <label class="col-xs-4 col-sm-4 col-md-4" for="img">@lang('messages.Upload your photo')</label>
                           <div class="input-group col-xs-8 col-sm-8 col-md-offset-4">
                               <input type="file" name="photoProfile" id="photoProfile" class="form-control-file btn-examinar" id="img" aria-describedby="fileHelp">
                           </div>
                       </div>

                       <div class="form-group">
                           <label class="col-xs-4 col-sm-4 col-md-4" for="name">@lang('messages.First name')</label>
                           <div class="input-group">
                               <div class="input-group-addon anchoMinimo39"><i class="fa fa-user" aria-hidden="true"></i></div>
                               <input type="text" class="form-control col-xs-8 col-sm-8 col-md-8" name="first_name" placeholder="" value="{!!  Auth::user()->first_name !!}">
                           </div>
                       </div>

                       <div class="form-group">
                           <label class="col-xs-4 col-sm-4 col-md-4" for="lastname">@lang('messages.Last name')</label>
                           <div class="input-group">
                               <div class="input-group-addon anchoMinimo39"><i class="fa fa-user" aria-hidden="true"></i></div>
                               <input type="text" class="form-control col-xs-8 col-sm-8 col-md-8" name="last_name" placeholder="" value="{!!  Auth::user()->last_name !!}">
                           </div>
                       </div>

                       <div class="form-group">
                           <label class="col-xs-4 col-sm-4 col-md-4" >@lang('messages.Email') </label>
                           <div class="input-group">
                               <div class="input-group-addon anchoMinimo39"><i class="fa fa-envelope-o" aria-hidden="true"></i></div>
                               <input type="email" class="form-control col-xs-8 col-sm-8 col-md-8" id="email" name="email" placeholder="Email Address" value="{!!  Auth::user()->email !!}">
                           </div>
                       </div>

                       <div class="form-group">
                           <label class="col-xs-4 col-sm-4 col-md-4" >@lang('messages.Institution')</label>
                           <div class="input-group">
                               <div class="input-group-addon"><i class="fa fa-briefcase" aria-hidden="true"></i></div>
                               <input type="text" class="form-control col-xs-8 col-sm-8 col-md-8"  name="institution" placeholder="" value="{!!  Auth::user()->institution !!}">
                           </div>
                       </div>

                       <div class="container form-2">
                           <div class="col-xs-12 col-sm-12 col-md-5">
                               <h4 class="text-center">@lang('messages.Change password')</h4>
                               <div class="col-xs-12 col-sm-12 col-md-12">
                                   @if(session('password_warning'))
                                       <div class="alert alert-success" id="id">
                                           {{session('password_warning')}}
                                       </div>
                                   @endif
                                   @if(session('user_photo'))
                                       <div class="alert alert-success" id="id">
                                           {{session('user_photo')}}
                                       </div>
                                   @endif
                                   @if(session('unique_user'))
                                       <div class="alert alert-success" id="id">
                                           {{session('unique_user')}}
                                       </div>
                                   @endif
                               </div>

                               <hr>
                               <div class="col-xs-12 col-sm-12 col-md-12 txtform2">
                                   <label class="col-xs-12 col-sm-12 col-md-12" for="oldpass">@lang('messages.Old password')</label>
                                   <div class="input-group">
                                       <input type="password" class="form-control pwd" value="" name="old_password">
                                       <span class="input-group-btn">
                                           <button class="btn btn-default reveal" type="button">
                                               <i class="glyphicon glyphicon-eye-open"></i>
                                           </button>
                                       </span>
                                   </div>
                               </div>

                               <div class="col-xs-12 col-sm-12 col-md-12 txtform2">
                                   <label class="col-xs-12 col-sm-12 col-md-12" for="newpass">@lang('messages.New password')</label>
                                   <div class="input-group">
                                       <input type="password" class="form-control pwd" value="" name="password">
                                       <span class="input-group-btn">
                                           <button class="btn btn-default reveal" type="button">
                                               <i class="glyphicon glyphicon-eye-open"></i>
                                           </button>
                                       </span>
                                   </div>
                               </div>

                               <div class="col-xs-12 col-sm-12 col-md-12 txtform2">
                                   <label class="col-xs-12 col-sm-12 col-md-12" for="confirmpass">@lang('messages.Confirm new password')</label>
                                   <div class="input-group">
                                       <input type="password" class="form-control pwd" value="" name="password_confirmation">
                                       <span class="input-group-btn">
                                           <button class="btn btn-default reveal" type="button">
                                               <i class="glyphicon glyphicon-eye-open"></i>
                                           </button>
                                       </span>
                                   </div>
                               </div>
                           </div>
                           
                           @if(Auth::user()->role != 'demo')
                               <div class="col-xs-12 col-sm-12 col-md-5 content-btn">
                                   <button type="submit" name="button" class="btn btn-success col-xs-3 col-xs-offset-4 col-sm-4 col-sm-offset-5 col-md-3 col-md-offset-5" >@lang('messages.Apply')</button>
                               </div>
                               {!! Form::close() !!}
                           @else
                               <div class="col-xs-12 col-sm-12 col-md-5 content-btn">
                                   <button disabled name="button" class="btn btn-success col-xs-3 col-xs-offset-4 col-sm-4 col-sm-offset-5 col-md-3 col-md-offset-5" >@lang('messages.Apply')</button>
                               </div>
                           @endif

                       </div>
                       <hr>
                       <div class="col-xs-12 col-sm-12 col-md-12">
                           @if(Auth::user()->role === 'demo' || Auth::user()->role === 'admin' )
                            <button disabled type="submit" name="button" class="btn btn-warning col-xs-3 col-xs-offset-4 col-sm-4 col-sm-offset-5 col-md-3 col-md-offset-5 unsuscribeButton" >@lang('messages.Unsubscribe')</button>
                           @else
                            <button name="button" class="btn btn-warning col-xs-3 col-xs-offset-4 col-sm-4 col-sm-offset-5 col-md-3 col-md-offset-5 unsuscribeButton" data-toggle="modal" data-target="#unsuscribeUserModal" >@lang('messages.Unsubscribe')</button>
                           @endif
                       </div>

                       <div class="modal fade" id="unsuscribeUserModal" tabindex="-1" role="dialog" aria-labelledby="unsuscribeUserModal" aria-hidden="true">
                           <div class="modal-dialog" role="document">
                               <div class="modal-content">
                                   <div class="modal-header">
                                       <h5 class="modal-title" id="exampleModalLabel">@lang('messages.Are you sure to unsuscribe?')</h5>
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                       </button>
                                   </div>
                                   <div class="modal-body">
                                       <strong>@lang('messages.This action is permanent')</strong>
                                   </div>
                                   <div class="modal-footer">
                                       <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Cancel')</button>
                                       <a href="{{route('user.unsuscribe', Auth::user())}}" type="button" class="btn btn-primary">@lang('messages.Confirm')</a>
                                   </div>
                               </div>
                           </div>
                       </div>
               </div>
               <div class="container col-xs-12 col-sm-12 col-md-2 content1 flex-espacio"></div>
           </div>
       </div>
  <script type="text/javascript" src="{{asset('//code.jquery.com/jquery-1.11.1.min.js')}}"></script>
  <script>
      $(".reveal").on('click',function() {
          var $pwd = $(".pwd");
          if ($pwd.attr('type') === 'password') {
              $pwd.attr('type', 'text');
          } else {
              $pwd.attr('type', 'password');
          }
      });
  </script>
@endsection
