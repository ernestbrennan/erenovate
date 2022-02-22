<?php

namespace App\Models;

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

    public function chat_room()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function guarantee_project()
    {
        return $this->belongsTo(GuaranteeProject::class);
    }
}
