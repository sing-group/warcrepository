<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    // Overriding the default attribute used for login.
    public function username()
    {
        return 'name';
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/user/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /*
     * Overriding credentials from AuthenticatesUsers trait.
     */
    public function credentials(Request $request)
    {

        $usernameInput = trim($request->{$this->username()});
        $usernameColumn = filter_var($usernameInput, FILTER_VALIDATE_EMAIL) ? 'email' : $this->username();

        return [$usernameColumn => $usernameInput, 'password' => $request->password, 'verified' => 1];


    }
}
