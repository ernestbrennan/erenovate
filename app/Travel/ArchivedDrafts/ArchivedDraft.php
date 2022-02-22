<?php

namespace App\Travel\ArchivedDrafts;

use Illuminate\Database\Eloquent\Model;

class ArchivedDraft extends Model
{
    protected $table = 'guarantee_archived_drafts';

    protected $fillable = [
        'draft', 'title'
    ];

    public function getDraftAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setDraftAttribute($draft)
    {
        $this->attributes['draft'] = json_encode($draft);
    }

}
