<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'guarantee_note';

    const TYPE_TEXT = 'text';
    const TYPE_FILE = 'file';

    protected $fillable = [
         'user_id', 'guarantee_project_id', 'content', 'type'
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
