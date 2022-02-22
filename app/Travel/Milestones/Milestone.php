<?php

namespace App\Travel\Milestones;

use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Invoices\Invoice;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Travel\Milestones\Milestone
 *
 * @method static Builder|Milestone query()
 * @method Builder withRelations()
 * @mixin Eloquent

 */
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

    public function scopeWithRelations(Builder  $query)
    {
        return $query->with([
            'milestone_content',
            'milestone_content.batches',
            'milestone_content.batches.user',
            'milestone_content.batches.files',
            'milestone_content.materials',
            'milestone_content.material_files',
        ]);
    }
}
