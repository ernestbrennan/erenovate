<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('email', '');
        $password = $request->input('password', '');

        $admin = DB::table('admin_user')->where('email', $email)->where('password', $password)->first();

        return [
            'token' => $admin ? env('API_TOKEN') : null,
        ];
    }

}
