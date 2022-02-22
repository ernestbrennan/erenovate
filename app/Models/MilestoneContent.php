<?php

namespace App\Models;

use App\Travel\SystemNotifications\AbstractSystemNotification;
use App\Travel\SystemNotifications\SystemNotification;
use Illuminate\Database\Eloquent\Model;

class MilestoneContent extends Model
{
    protected $table = 'guarantee_milestone_content';

    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_REJECTED = 'rejected';

    const MATERIAL_PROVIDE_ON_CONTRACT = 'contract';
    const MATERIAL_PROVIDE_ON_MILESTONE = 'milestone';

    const MATERIAL_SUPPLY_SIDE_HOMEOWNER = 'homeowner';
    const MATERIAL_SUPPLY_SIDE_CONTRACTOR = 'contractor';

    const DEFAULT_VERSION = 1;

    protected $fillable = [
        'milestone_id', 'title', 'description',  'price', 'status', 'version', 'start_date', 'end_date',
        'materials_provide_on', 'material_supply_side',
    ];

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = format_price($value);
    }
    
    public function getPriceAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }

    public function batches()
    {
        return $this->belongsToMany(
            Batch::class,
            'guarantee_milestone_content_batch',
            'milestone_content_id',
            'batch_id'
        );
    }

    public function materials_content()
    {
        return $this->hasOne(MaterialsContent::class)->orderBy('id', 'desc');
    }


    public function edited_notification()
    {
        return $this->hasOne(SystemNotification::class)
            ->where('type', AbstractSystemNotification::TYPE_MILESTONE_CONTENT_EDITED)
            ->orderBy('id', 'desc');
    }
}
