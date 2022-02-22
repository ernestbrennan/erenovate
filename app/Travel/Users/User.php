<?php

namespace App\Travel\Users;

use App\Travel\ArchivedDrafts\ArchivedDraft;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Auth;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    const UPDATED_AT = null;

    const ROLE_HOMEOWNER = 'homeowner';
    const ROLE_CONTRACTOR = 'contractor';

    protected $table = 'user';
    protected $primaryKey = 'userId';

    protected $rememberTokenName = null;

    public $timestamps = false;

    protected $fillable = [
        'email', 'password', 'user_photo', 'mobile_phone', 'address', 'city', 'postalcode', 'firstname', 'lastname',
        'screen_name', 'province_id', 'created_date', 'country_id', 'user_cover_photo', 'description', 'email_status_id',
        'user_status', 'userId'
    ];

    protected $appends = ['role', 'photo', 'profile_link'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getRoleAttribute()
    {
        $map = UserBusinessMap::where('user_id', $this->userId)->first();

        if (!$map || empty($map['businessprofile_id'])) {

            return self::ROLE_HOMEOWNER;
        }

        return self::ROLE_CONTRACTOR;
    }

    public function getPhotoAttribute()
    {
        return getUserPhoto($this);
    }

    public function getProfileLinkAttribute()
    {
        return getProfileLink($this->userId);
    }

    public function user_business_map()
    {
        return $this->hasOne(UserBusinessMap::class, 'user_id');
    }

    public function archived_drafts()
    {
        return $this->hasMany(ArchivedDraft::class, 'user_id');
    }

    public function fcm()
    {
        return $this->hasOne(UserFcm::class, 'user_id');
    }

    public function getRole()
    {
        $map = Auth::user()->user_business_map()->first();

        if (!$map || empty($map['businessprofile_id'])) {

            return self::ROLE_HOMEOWNER;
        }

        return self::ROLE_CONTRACTOR;
    }

    public function getRoleAttr()
    {
        return $this->getRole() . '_id';
    }

    public static function getInterlocutorAttr()
    {
        return Auth::user()->getRole() === self::ROLE_HOMEOWNER ?
            self::ROLE_HOMEOWNER . '_id' :
            self::ROLE_CONTRACTOR . '_id';
    }

    public function getInterlocutorRole()
    {
        return $this->role !== self::ROLE_HOMEOWNER ? self::ROLE_HOMEOWNER : self::ROLE_CONTRACTOR;
    }

    public function getInfo()
    {
        if ($this->role === self::ROLE_HOMEOWNER) {

            $data = [
                'type' => UserInfo::TYPE_INDIVIDUAL,
                'postal_code' => $this->postalcode,
            ];
        }

        if ($this->role === self::ROLE_CONTRACTOR) {

            $business_profile = $this->user_business_map->business_profile;

            $data = [
                'type' => UserInfo::TYPE_LEGAL,
                'postal_code' => $business_profile['area_postalcode'],
                'company_name' => $business_profile['businessName'],
                'representative_name' => $this->firstname . ' ' . $this->lastname
            ];
        }

        $province = \DB::table('province')->find($this->province_id);


        return array_merge($data, [
            'user_id' => $this->userId,
            'status' => UserInfo::STATUS_NEW,
            'province' => empty($province) ? null : $province->name,
            'first_name' => $this->firstname,
            'last_name' => $this->lastname,
            'city' => $this->city,
            'address_1' => $this->address,

        ]);
    }

    public static function getOrCreate(Collection $user_info)
    {
        $email = $user_info->get('email', '');
        $first_name = $user_info->get('first_name', '');
        $last_name = $user_info->get('last_name', '');
        $postal_code = $user_info->get('postal_code', '');
        $city = $user_info->get('city', '');
        $address = $user_info->get('address', '');

        if (!$user = self::whereEmail($email)->first()) {

            $user = self::query()->create([
                'email' => $email,
                'firstname' => $first_name,
                'lastname' => $last_name,
                'password' => $user_info->get('password', str_random()),
                'province_id' => $province_id ?? 0,
                'postalcode' => $postal_code,
                'country_id' => 38,
                'city' => $city,
                'address' => $address,
                'created_date' => date("Y-m-d H:i:s"),

                'user_cover_photo' => '',
                'description' => '',
                'email_status_id' => 1,
                'user_status' => 0,

            ]);

            $user_id = $user['userId'];

            DB::table('user_activation')->insert([
                'userkey' => md5(uniqid($user_id, true)),
                'created_date' => date('Y-m-d H:i:s', time()),
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
        }

        return $user;
    }
}
