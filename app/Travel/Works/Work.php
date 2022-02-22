<?php

namespace App\Travel\Works;

use App\Travel\Invoices\Invoice;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    protected $table = 'guarantee_work';

    protected $fillable = [
        'invoice_id', 'title', 'price', 'quantity'
    ];

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = format_price($value);
    }

    public function getPriceAttribute($value)
    {
        return convert_price($value);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
