<?php

use App\Models\User;

if (!function_exists('paginationOffset')) {

    function paginationOffset($page, $per_page)
    {
        return ($page - 1) * $per_page;
    }
}

if (!function_exists('allEntitiesLoaded')) {

    function allEntitiesLoaded($total_entities, $page, $per_page)
    {
        return ($page * $per_page) >= $total_entities;
    }
}

if (!function_exists('compareStrings')) {

    function compareStrings($search, $string)
    {
        if (empty($search) || empty($string)) {

            return false;
        }

        return strpos(strtolower($string), strtolower($search)) !== false;
    }
}

if (!function_exists('getUserPhoto')) {

    function getUserPhoto($user)
    {
        $media_id = preg_replace('/[^0-9]+/', '', $user->user_photo);

        if (empty($media_id) && $user->role === User::ROLE_CONTRACTOR) {

            $media_id = preg_replace('/[^0-9]+/', '', $user->user_business_map->business_profile['business_logo']);
        }

        if ($media = DB::table('media')->find($media_id)) {

            return env('MAIN_APP_URL') . '/contractors/login/mobile_app/' . $media->media_url;
        }

        return env('DEFAULT_PHOTO');
    }
}

if (!function_exists('getProfileLink')) {

    function getProfileLink($user_id)
    {
        $profile_link = env('MAIN_APP_URL');

        $user = User::with([
            'user_business_map.business_profile'
        ])
            ->find($user_id);

        if ($user['role'] === User::ROLE_CONTRACTOR) {


            $business_name = trim($user['user_business_map']['business_profile']['businessName']);
            $business_name = str_replace("%20", "-", $business_name);
            $business_name = str_replace(" ", "-", $business_name);

            $profile_link = env('MAIN_APP_URL') . '/contractors/profile/' . $business_name . '/aboutus';
        }

        return $profile_link;
    }
}

if (!function_exists('format_price')) {

    function format_price($price)
    {
        return (float)str_replace(',', '', $price);
    }
}

if (!function_exists('convert_price')) {

    function convert_price($price)
    {
        return number_format($price, 2, '.', ',');
    }
}

if (!function_exists('calculate_fee')) {

    function calculate_fee($amount)
    {
        $prices = [
            '500' => 8,
            '1,000.00' => 7,
            '2,500.00' => 5.5,
            '5,000.00' => 5,
            '150,000.00' => 3,
            '250,000.00' => 2.5,
        ];

        $fee = 0;

        foreach ($prices as $price => $percent) {
            if ($amount <= format_price($price)) {
                $fee = $amount * $percent / 100;
                break;
            }
        }

        if (!$fee) {
            $fee = $amount * 1.5 / 100;
        }

        return convert_price($fee);
    }
}
if (!function_exists('getPdfGenerateParams')) {

    function getPdfGenerateParams()
    {
        return array(
//        'cookie' => $this->getSessionCookieParams(),
            'header-spacing' => 0,
            'footer-spacing' => 25,
            'margin-top'    => 37,
            'margin-right'  => 0,
            'margin-bottom' => 38,
            'margin-left'   => 0,
            'orientation' => 'Portrait',
            'page-height' => 192 * 3,
            'page-width'  => 124 * 3,
            'enable-javascript' => true,
            'javascript-delay' => 1000,
            'disable-smart-shrinking' => true,
            'no-stop-slow-scripts' => true,
            'no-background' => false,
            'lowquality' => false,
            'encoding' => 'utf-8',
            'images' => true,
            'dpi' => 300,
            'enable-external-links' => true,
            'enable-internal-links' => true
        );
    }
}



