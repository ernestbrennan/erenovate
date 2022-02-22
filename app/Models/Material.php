<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'guarantee_material';

    protected $fillable = [
        'title', 'quantity', 'link', 'price'
    ];


    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = str_replace(',', '', $value);
    }

    public function getPriceAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }
}
