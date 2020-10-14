<?php

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function (Dingo\Api\Routing\Router $api) {
    $api->post('login', 'App\Http\Controllers\AuthenticateController@authenticate');
    $api->post('user', 'App\Http\Controllers\UserController@create');
    $api->get('token', 'App\Http\Controllers\AuthenticateController@getToken');
});

$api->version('v1', ['middleware' => 'api.auth'], function($api) {
    $api->get('users', 'App\Http\Controllers\UserController@users');
    $api->get('me', 'App\Http\Controllers\UserController@me');
    $api->post('corpus', 'App\Http\Controllers\CorpusController@store' );
});

