<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::group(['middleware' => ['web']], function(){

//Select language
    Route::get('lang/{lang}', function ($lang) {
        session(['lang' => $lang]);
        return \Redirect::back();
    })->where([
        'lang' => 'en|es'
    ]);

// All logged users
    Route::group(['middleware' => ['auth']], function () {

        Route::get('/user/home', array('as' => 'user.index', 'uses' => 'UserController@index'));

        Route::get('/user/edit', array('as' => 'user.edit', 'uses' => 'UserController@edit'));

        Route::get('/user/unsuscribe/{user}', array('as' => 'user.unsuscribe', 'uses' => 'UserController@unsuscribe'));

        Route::get('/user/list/', array('as' => 'user.list', 'uses' => 'UserController@listUsers'));

        Route::get('/user/show/{user?}', array('as' => 'user.show', 'uses' => 'UserController@show'));

        Route::put('/user/update/{user}', array('as' => 'user.update', 'uses' => 'UserController@update'));

        Route::any('/corpus/search-corpus', array('as' => 'corpus.search', 'uses' => 'CorpusController@search'));

        Route::any('/corpus/filter-setup', array('as' => 'corpus.filter', 'uses' => 'CorpusController@filter'));

        Route::get('/corpus/deleteCorpus/{corpus}', array('as' => 'corpus.delete', 'uses' => 'CorpusController@delete'));

        Route::get('/corpus/removeCorpus/{corpus}', array('as' => 'corpus.remove', 'uses' => 'CorpusController@remove'));

        Route::get('/corpus/restoreCorpus/{corpus}', array('as' => 'corpus.restore', 'uses' => 'CorpusController@restore'));

        Route::get('/corpus/emptyTrash', array('as' => 'corpus.emptyTrash', 'uses' => 'CorpusController@empty_trash'));

        Route::put('/corpus/update/{user_uuid}', array('as' => 'corpus.update', 'uses' => 'CorpusController@update'));

        Route::post('/corpus/joinCorpus', array('as' => 'corpus.joinCorpus', 'uses' => 'CorpusController@joinCorpus'));

        Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
    });

// Only admin users
    Route::group(['middleware' => ['auth', 'AdminAuth']], function () {
        Route::get('/user/restartDemoUsers', array('as' => 'user.restartDemoUsers', 'uses' => 'UserController@restartDemoUsers'));
        Route::get('/user/delete/{user}', array('as' => 'user.delete', 'uses' => 'UserController@delete'));
        Route::get('/user/ban/{user}', array('as' => 'user.ban', 'uses' => 'UserController@ban'));
        Route::get('/user/unban/{user}', array('as' => 'user.unban', 'uses' => 'UserController@unban'));
        Route::get('/user/admin', function () {
            return view('user.admin.index');
        });
    });

    Route::get('/',array('as' => '/', 'uses' => 'HomeController@index'));
    Route::get('/downloadwarcprocessor', 'HomeController@downloadwarcprocessor');
    Route::get('/downloadwacorpus/{corpus}', array('as' => 'download_corpus', 'uses' => 'HomeController@downloadcorpus'));
    Route::get('/register/info', array('as' => 'register.info', 'uses' => 'HomeController@info'));

    Route::get('/login', function () {
        if (Auth::check()) {
            return redirect('/user/home');
        }else{
            return view('auth.login');
        }
    });
    Route::get('/registration',['as' => 'registration', function () {
        if (Auth::check()) {
            return redirect('/user/home');
        }else{
            return view('registration.index');
        }
    }]);

// Verification mail link.
    Route::get('register/verify/{token}', 'Auth\RegisterController@verify');

// Auth
    Auth::routes();

// Rewriting password.email route, to asigns it an alias.
    Route::put('password/email', array('as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'));
    Route::post('password/reset', array('as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@reset'));
});


