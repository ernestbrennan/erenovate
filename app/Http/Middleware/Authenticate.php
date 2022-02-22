<?php

namespace App\Http\Middleware;

use App\Travel\Users\User;
use Closure;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        $user_id = Cookie::get(env('AUTH_SESSION_NAME'));

        $user = User::query()->find($user_id);

        if (($user_id = hex2bin($request->input('token'))) && $user = User::query()->find($user_id)) {

            Cookie::queue(
                env('AUTH_SESSION_NAME'),
                $user_id,
                time() + intval(60 * 60 * 24 * 365),
                '/',
                env('AUTH_SESSION_DOMAIN')
            );

            return redirect()->to(
                url()->current().'?'.http_build_query($request->except("token"))
            );
        }

        if (!$user) {

            if ($request->ajax()) {

                return response(
                    ['redirect_url' => route('sign_in')],
                    401
                );
            }

            return redirect(route('sign_in'));
        }

        Auth::login($user);

        return $next($request);
    }
}
