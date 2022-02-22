<?php

namespace App\Travel\Users;

use Illuminate\Database\Eloquent\Model;

class UserBusinessMap extends Model
{
    protected $table = 'user_business_map';
    
    protected $fillable = [
        'user_id', 'businessprofile_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'userId');
    }

    public function business_profile()
    {
        return $this->hasOne(BusinessProfile::class,  'businessId',  'businessprofile_id');
    }

}
