<?php

namespace App\Travel\Users;

use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    protected $table = 'business_profile';

    protected $primaryKey = 'businessId';

    public $timestamps = null;

    protected $fillable = [
        'businessprofile_id',
        'city',
        'email',
        'mobile_phone',
        'address',
        'serviceArea',
        'area_postalcode',
        'facebook_url',
        'twitter_url',
        'youtube_url',
        'linkedIn_url',
        'pinterest_url',
        'bbb_url',
        'website_url'
    ];
}
