<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaypalLog extends Model
{
    protected $table = 'guarantee_paypal_log';

    const TYPE_SIGNED = 'signed';
    const TYPE_COMPLETED = 'completed';

    const STATUS_COMPLETED = 'COMPLETED';

    protected $fillable = [
        'contract_id', 'order_id', 'type', 'log'
    ];

    public function getLogAttribute($value)
    {
        return json_decode($value, true);
    }
    public function setLogAttribute($value)
    {
        $this->attributes['log'] = json_encode($value);
    }

    public function contract() {
        return $this->belongsTo(Contract::class);
    }

}
