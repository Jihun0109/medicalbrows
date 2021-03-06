<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use App\TblLog;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
    * Get the login username to be used by the controller.
    *
    * @return string
    */
    public function username()
    {
        $login = request()->input('identity');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_id';
        request()->merge([$field => $login]);

        return $field;
    }
    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $messages = [
            'identity.required' => 'Email or username cannot be empty',
            'email.exists' => 'Email or username already registered',
            'username.exists' => 'Username is already registered',
            'password.required' => 'Password cannot be empty',
        ];

        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
            'email' => 'string|exists:users',
            'username' => 'string|exists:users',
        ], $messages);
    }

    protected function authenticated(Request $request, $user)
    {
        // if ( $user->isAdmin() ) {// do your magic here
        //     return redirect()->route('dashboard');
        // }

        // Log::info(Auth::user()->user_id);
        // TblLog::create(['log' => 'ログイン:  ユーザーID 「'.Auth::user()->user_id.'」 ユーザー名: 「'.Auth::user()->name ."」"]);
        return redirect('/admin/reservations');
    }

    // protected function login(Request $request)
    // {
    //     $identity = $request->identity;
    //     $password = $request->password;
    //     $remember = $request->remember; //on
    //     $_token = $request->_token;

    //     $field = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_id';
    //     //dd($request);
    //     if (Auth::attempt([$field=>$identity, 'password'=>$password], true)) {
    //         // Authentication passed...
    //         return redirect()->intended('test');
    //     }
    // }
}
