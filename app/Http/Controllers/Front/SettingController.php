<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting as R;
use App\Travel\Users\User;

class SettingController extends Controller
{
    public function get()
    {
        $user = \Auth::user();

        $settings = [
            'first_name' => $user['firstname'],
            'last_name' => $user['lastname'],
        ];

        if ($user['role'] === User::ROLE_CONTRACTOR) {

            $business_profile = $user->user_business_map->business_profile;

            $additional = [
                'city' => $business_profile['city'],
                'email' => $business_profile['email'],
                'phone_number' => $business_profile['mobile_phone'],
                'address' => $business_profile['address'],
                'lead_radius' => $business_profile['serviceArea'],
                'postal_code' => $business_profile['area_postalcode'],

                'facebook_url' => $business_profile['facebook_url'],
                'twitter_url' => $business_profile['twitter_url'],
                'youtube_url' => $business_profile['youtube_url'],
                'linkedin_url' => $business_profile['linkedIn_url'],
                'pinterest_url' => $business_profile['pinterest_url'],
                'bbb_url' => $business_profile['bbb_url'],
                'website_url' => $business_profile['website_url'],
            ];

        } else {

            $additional = [
                'city' => $user['city'],
                'email' => $user['email'],
                'phone_number' => $user['mobile_phone'],
                'address' => $user['address'],
                'postal_code' => $user['postalcode'],
            ];
        }

        return [
            'setting' => array_merge($settings, $additional)
        ];
    }

    public function changePassword(R\ChangePasswordRequest $request)
    {
        \Auth::user()->update([
            'password' => $request->input('new_password'),
        ]);

        return [
            'status_code' => 200
        ];
    }

    public function save(R\SaveRequest $request)
    {
        $user = \Auth::user();

        \Auth::user()->update([
            'firstname' => $request->input('first_name'),
            'lastname' => $request->input('last_name'),
            'email' => $request->input('email'),
        ]);

        if ($user['role'] === User::ROLE_CONTRACTOR) {

            $user->user_business_map->business_profile->update([
                'city' => $request->input('city'),
                'mobile_phone' => $request->input('phone_number'),
                'serviceArea' => $request->input('lead_radius'),
                'address' => $request->input('address'),
                'area_postalcode' => $request->input('postal_code'),

                'website_url' => $request->input('website_url'),
                'facebook_url' => $request->input('facebook_url'),
                'twitter_url' => $request->input('twitter_url'),
                'youtube_url' => $request->input('youtube_url'),
                'linkedIn_url' => $request->input('linkedin_url'),
                'pinterest_url' => $request->input('pinterest_url'),
                'bbb_url' => $request->input('bbb_url'),
            ]);

        } else {

            \Auth::user()->update([
                'city' => $request->input('city'),
                'mobile_phone' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'postalcode' => $request->input('postal_code'),
            ]);
        }

        return [
            'status_code' => 200
        ];
    }
}