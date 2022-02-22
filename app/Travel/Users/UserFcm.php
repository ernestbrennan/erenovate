<?php

namespace App\Travel\Users;

use Illuminate\Database\Eloquent\Model;

class UserFcm extends Model
{
    protected $table = 'guarantee_user_fcm';

    protected $fillable = [
        'user_id', 'fcm_token'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
