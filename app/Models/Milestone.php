<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $table = 'guarantee_milestone';

    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'contract_draft_id', 'sequence', 'status'
    ];

    public function milestone_content()
    {
        return $this->hasOne(MilestoneContent::class)->orderBy('id', 'desc');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class)->where('status', '!=', Invoice::STATUS_REJECTED);
    }

    public function contract_draft()
    {
        return $this->belongsTo(ContractDraft::class);
    }
}
