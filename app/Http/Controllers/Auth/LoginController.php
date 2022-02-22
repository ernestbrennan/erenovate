<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Cookie;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function autoLogin(Request $request)
    {
        $user_id = $request->get('user_id', 0);

        $user = User::find($user_id);

        if (!$user) {
            $user = User::all()->random(1)[0];
        }

        if ($user['api_token'] == '') {

            $api_token = str_random(64);

            $user->update([
                'api_token' => $api_token
            ]);
        }

        return [
            'success' => true,
            'data' => [
                'cookie_name' => env('AUTH_SESSION_NAME'),
                'cookie_value' => $user['api_token']
            ]
        ];
    }
}
