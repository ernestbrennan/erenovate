<?php

namespace App\Travel\Users\Services;

use App\Travel\Base\BaseFormRequest;
use App\Travel\Users\Exceptions\{CreateUserErrorException, NotFoundUserErrorException};
use App\Travel\Users\Repositories\Interfaces\UserRepositoryInterface;
use App\Travel\Users\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    private $user_repository;

    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $user_repository
     */
    public function __construct(UserRepositoryInterface $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * @param BaseFormRequest $request
     * @return User
     * @throws CreateUserErrorException
     */
    public function createUser(BaseFormRequest $request)
    {
        $role = $request->get('role', User::ROLE_HOMEOWNER);

        $email = $request->get('email', '');
        $first_name = $request->get('first_name', '');
        $last_name = $request->get('last_name', '');
        $postal_code = $request->get('postal_code', '');
        $city = $request->get('city', '');
        $address = $request->get('address', '');
        $password = $request->get('password', str_random());

        $now = Carbon::now()->toDateTimeString();
        DB::beginTransaction();
        try {
            $user = $this->user_repository->createUser([
                'email' => $email,
                'firstname' => $first_name,
                'lastname' => $last_name,
                'password' => $password,
                'province_id' => $province_id ?? 0,
                'postalcode' => $postal_code,
                'country_id' => 38,
                'city' => $city,
                'address' => $address,
                'created_date' => $now,

                'user_cover_photo' => '',
                'description' => '',
                'email_status_id' => 1,
                'user_status' => 0,
            ]);

            $user_id = $user['userId'];

            DB::table('user_activation')->insert([
                'userkey' => md5(uniqid($user_id, true)),
                'created_date' => $now,
                'user_id' => $user_id
            ]);

            DB::table('tbl_user_other_data')->insert([
                'user_id' => $user_id,
                'latitude' => 0,
                'longitude' => 0,
                'plan_id' => 0,
                'paid_status' => 0,
                'registered_source' => 'eRenovate',

                'facebook_id' => '',
                'ethnicity' => '',
                'bio' => '',
                'gmt_time' => '',
                'notification_status' => 'no',
                'delete_status' => 'no',
                'app_request_notification' => 'yes',
                'login_status' => 0,
                'reward_point' => 0,
                'your_left_lead' => '',
                'plan_expiry_date' => '',
            ]);

            if ($role === User::ROLE_CONTRACTOR) {

                $business_profile_id = DB::table('business_profile')->insertGetId([
                    'businesstype' => 101,
                    'businessName' => $first_name,
                    'address' => '',
                    'country_id' => 38,
                    'province_id' => 467,
                    'city' => '',
                    'area_postalcode' => '',
                    'serviceArea' => null,
                    'mobile_phone' => '',
                    'email' => $email,
                    'website_url' => '',
                    'industry' => 1,
                    'certifiedBy_id' => 10136,
                    'business_status_id' => 4,
                    'createDate' => null,
                    'business_version' => null,
                    'seo_title' => null,
                    'seo_description' => null,
                    'business_logo' => 'getImage?mediaid=0',
                    'professional_type' => 0,
                    'lead_status' => 'Off',
                    'established_year' => '',
                    'association_memberships' => '',
                    'newletter_optin' => '0',
                    'promo_code' => ''
                ]);

                DB::table('tbl_user_group_plan')->insertGetId([
                    'user_id' => $user_id,
                    'business_id' => $business_profile_id,
                    'group_id' => '1',
                    "plan_id" => 0,
                    "plan_cost" => 0
                ]);

                DB::table('business_activation')->insertGetId([
                    'businesskey' => str_random(),
                    'created_date' => $now,
                    'email_status_id' => 3,
                    'clientip' => '',
                    'businessId' => $business_profile_id
                ]);

                DB::table('user_business_map')->insertGetId([
                    'business_scope_type_id' => 1,
                    'user_id' => $user_id,
                    'businessprofile_id' => $business_profile_id,
                    'created_date' => $now
                ]);
            }
            DB::commit();

            return $user;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new CreateUserErrorException($e);
        }

    }

    public function updateFcm(Request $request)
    {
        $user = \Auth::user();

        return $user->fcm()->updateOrCreate([], ['fcm_token' => $request->get('fcm_token')]);
    }

}