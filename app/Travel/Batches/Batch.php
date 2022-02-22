<?php

namespace App\Travel\Batches;

use App\Travel\Files\File;
use App\Travel\Users\User;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $table = 'guarantee_batch';

    protected $fillable = [
        'description', 'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'userId');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'guarantee_batch_file', 'batch_id', 'file_id');
    }
}
