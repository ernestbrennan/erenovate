<?php

namespace App\Travel\Notes;

use App\Travel\Files\File;
use App\Travel\Projects\GuaranteeProject;
use App\Travel\Users\User;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'guarantee_note';

    protected $fillable = [
         'user_id', 'guarantee_project_id', 'content'
    ];

    public function guarantee_project()
    {
        return $this->belongsTo(GuaranteeProject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'guarantee_note_file', 'note_id', 'file_id');
    }
}
