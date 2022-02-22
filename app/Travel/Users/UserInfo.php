<?php

namespace App\Travel\Users;

use App\Travel\Contracts\Contract;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'guarantee_user_info';

    const TYPE_INDIVIDUAL = 'individual';
    const TYPE_LEGAL = 'legal';

    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';

    protected $fillable = [
        'contract_id', 'user_id', 'type', 'first_name', 'last_name', 'company_name', 'representative_name', 'address_1', 'address_2',
        'city', 'province', 'postal_code', 'status'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'userId');
    }
}
