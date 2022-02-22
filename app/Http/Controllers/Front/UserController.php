<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Requests\SignInRequest;
use App\Travel\Requests\SignUpRequest;
use App\Travel\Users\Services\UserService;
use App\Travel\Users\User;
use App\Travel\Users\UserInfo;
use Auth;
use App\Http\Requests\User as R;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private $user_service;

    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    public function signIn(SignInRequest $request)
    {
        $user = User::whereEmail($request->get('email'))->first();

        Auth::login($user);

        Cookie::queue(
            env('AUTH_SESSION_NAME'),
            $user['userId'],
            time() + intval(60 * 60 * 24 * 365),
            '/',
            env('AUTH_SESSION_DOMAIN')
        );

        return response()->json([
            'response' => $user->only(['userId', 'role', 'firstname', 'lastname', 'photo', 'profile_link']),
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function logout()
    {
        Auth::logout();

        Cookie::queue(
            env('AUTH_SESSION_NAME'),
            '',
            time() + intval(60 * 60 * 24 * 365),
            '/',
            env('AUTH_SESSION_DOMAIN')
        );

        return response()->json([
            'status_code' => 200
        ], Response::HTTP_OK);
    }


    public function signUp(SignUpRequest $request)
    {
        $user = $this->user_service->createUser($request);

        return response()
            ->json([
                'response' => $user->only(['userId', 'role', 'firstname', 'lastname', 'photo', 'profile_link']),
                'status_code' => 200
            ], Response::HTTP_OK)
            ->cookie(
                env('AUTH_SESSION_NAME'),
                $user['userId'],
                time() + intval(60 * 60 * 24 * 365),
                '/',
                env('AUTH_SESSION_DOMAIN')
            );
    }


    public function getInfo()
    {
        return response()->json([
            'response' => Auth::user()->getInfo(),
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function getDraftInfo(R\GetUserInfoRequest $request)
    {
        $user_info_id = $request->input('id');

        return response()->json([
            'response' => UserInfo::query()->find($user_info_id),
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function updateDraftInfo(R\UpdateUserInfoRequest $request)
    {
        $request_user_info = collect($request->input('user_info'))
            ->put('status', UserInfo::STATUS_PENDING)
            ->except(['created_at', 'updated_at'])
            ->toArray();

        $user_info = UserInfo::query()->find($request_user_info['id']);

        $user_info->contract->last_draft->update([

            ContractDraft::getInterlocutorAcceptAttribute() => false,
            'status' => ContractDraft::STATUS_PENDING,
        ]);

        $user_info->update(
            $request_user_info
        );

        return response()->json([
            'response' => $request_user_info,
            'status_code' => 200
        ], Response::HTTP_OK);
    }


    public function confirmInterlocutorInfo(R\ConfirmInterlocutorInfoRequest $request)
    {
        $user_info_id = $request->input('user_info_id');

        UserInfo::find($user_info_id)
            ->update([
                'status' => UserInfo::STATUS_CONFIRMED
            ]);

        return response()->json([
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function unConfirmInterlocutorInfo(R\ConfirmInterlocutorInfoRequest $request)
    {
        $user_info_id = $request->input('user_info_id');

        UserInfo::find($user_info_id)
            ->update([
                'status' => UserInfo::STATUS_PENDING
            ]);

        return response()->json([
            'status_code' => 200
        ], Response::HTTP_OK);
    }

    public function fcm(Request $request)
    {
        $this->user_service->updateFcm($request);

        return response()->json([
            'status_code' => 200
        ], Response::HTTP_OK);
    }
}
