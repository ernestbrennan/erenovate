<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'guarantee_file';

    const TYPE_PDF = 'pdf';

    protected $fillable = [
        'extension', 'name', 'hash_name', 'path', 'size', 'url'
    ];
}
