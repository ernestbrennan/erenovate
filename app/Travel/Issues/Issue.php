<?php

namespace App\Travel\Issues;

use App\Travel\Chats\Chat;
use App\Travel\Projects\GuaranteeProject;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $table = 'guarantee_issue';

    const STATUS_OPENED = 'opened';
    const STATUS_CLOSED = 'closed';
    const STATUS_UNDER_REVIEW = 'under_review';

    const TYPE_ISSUE = 'issue';
    const TYPE_PRICE_MODIFICATION = 'price_modification';

    protected $fillable = [
       'guarantee_project_id', 'chat_id', 'title', 'description', 'sequence', 'status', 'type'
    ];

    public function chat()
    {
        return $this->belongsTo( Chat::class);
    }

    public function guarantee_project()
    {
        return $this->belongsTo(GuaranteeProject::class);
    }
}
