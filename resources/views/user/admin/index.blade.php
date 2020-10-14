@extends('layouts.layout')
@section('extracss')


    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/stylesAdminDashboard.css')}}">

@endsection
@section('content')
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 content-principal dashboard-background" >
            <a href="{{route('user.index')}}" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn-seccion2-izquierda my-margin-top-20">Administrate users</a>
            <a class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn-seccion2-izquierda" data-toggle="modal" data-target="#restartDemoUsers">Restart default users</a>
        </div>
        <div class="col-xs-9 col-sm-9 col-md-10 col-lg-9 content-principal">
            <div class="col-md-offset-1 col-md-11  col-lg-offset-1 col-lg-11 ">
                <h2>Dashboard</h2>
                <hr>
            </div>
            <table id="example" class="table table-striped table-bordered dashboard-table" >
                <thead>
                    <tr>
                        <th>@lang('messages.Name')</th>
                        <th>@lang('messages.Last name')</th>
                        <th>@lang('messages.Publicated corpus')</th>
                        <th>@lang('messages.View')</th>
                        <th>@lang('messages.Ban/unban')</th>
                        <th>@lang('messages.Delete')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{!! $user->name !!}</td>
                        <td>{!! $user->last_name !!}</td>
                        <td>{!! $user->corpus()->count() !!}</td>
                        <td><a class="col-md-8 col-md-offset-2 btn btn-primary mybtn-inline" href="{{ route('user.show', $user) }}" >
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </a>
                        </td>
                        @if($user->verified == 1)
                            <td><button class="col-md-8 col-md-offset-2 btn btn-warning mybtn-inline" data-toggle="modal" data-target="{{'#confirmBanUserModal'.$user->name}}"><i class="glyphicon glyphicon-ban-circle"></i></button></td>

                        @else
                            <td><button class="col-md-8 col-md-offset-2 btn btn-success mybtn-inline" data-toggle="modal" data-target="{{'#confirmUNBanUserModal'.$user->name}}"><i class="glyphicon glyphicon-thumbs-up"></i></button></td>
                        @endif

                        <td><button class="col-md-8 col-md-offset-2 btn btn-danger mybtn-inline" data-toggle="modal" data-target="{{'#confirmDeleteUserModal'.$user->name}}"><i class="glyphicon glyphicon-trash"></i></button></td>
                    </tr>
                    <!-- Modal confirm ban user -->
                    <div class="modal fade" id="{{'confirmBanUserModal'.$user->name}}" tabindex="-1" role="dialog" aria-labelledby="confirmBanUserModal" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"> @lang('messages.Are you shure do you want to ban')&nbsp; {!!$user->name !!}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @lang('messages.You can restore the user again when you want')
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Cancel')</button>
                                    <a href="{{route('user.ban',$user)}}" type="button" class="btn btn-primary">@lang('messages.Confirm')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal confirm unban user -->
                    <div class="modal fade" id="{{'confirmUNBanUserModal'.$user->name}}" tabindex="-1" role="dialog" aria-labelledby="confirmUNBanUserModal" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"> @lang('messages.Are you shure do you want to unban')&nbsp; {!!$user->name !!}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Cancel')</button>
                                    <a href="{{route('user.unban',$user)}}" type="button" class="btn btn-primary">@lang('messages.Confirm')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal confirm delete user -->
                    <div class="modal fade" id="{{'confirmDeleteUserModal'.$user->name}}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteUserModal" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"> @lang('messages.Are you shure do you want to delete')&nbsp; {!!$user->name !!}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @lang('messages.This action will remove this user permanently')
                                </div>
                                <div class="modal-footer">
                                    <a type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Cancel')</a>
                                    <a href="{{route('user.delete',$user)}}" type="button" class="btn btn-primary">@lang('messages.Confirm')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </table>
        </div>
    <!-- Modal confirm restart demo users -->
        <div id="restartDemoUsers" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('messages.Restart data from demo users')</h4>
                    </div>
                    <div class="modal-body">
                        <p>@lang('messages.Are you sure to reset the data?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.Close')</button>
                        <a href="{{route('user.restartDemoUsers')}}" type="button" class="btn btn-primary">@lang('messages.Confirm')</a>
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
    </script>
@endsection
