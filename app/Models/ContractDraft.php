<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Sabberworm\CSS\CSSList\CSSBlockList;

class ContractDraft extends Model
{
    protected $table = 'guarantee_contract_draft';

    const INIT_VERSION = '1';

    const TYPE_SINGLE_MILESTONE = 'single';
    const TYPE_SEVERAL_MILESTONE = 'several';

    const STATUS_PENDING = 'pending';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ACCEPTED = 'accepted';

    protected $fillable = [
        'user_id', 'contract_id', 'type', 'status', 'version', 'title', 'description', 'homeowner_accepted', 'contractor_accepted'
    ];

    protected $hidden = [
        'homeowner_accepted', 'contractor_accepted'
    ];

    protected $appends = [
        'current_accepted', 'interlocutor_accepted'
    ];

    public function getCurrentAcceptedAttribute()
    {
        $attr = self::getCurrentAcceptAttribute();
        return $this->$attr;
    }

    public function getInterlocutorAcceptedAttribute()
    {
        $attr = self::getInterlocutorAcceptAttribute();
        return $this->$attr;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }
    public function current_milestone()
    {
        return $this->hasOne(Milestone::class)->where('status', Milestone::STATUS_IN_PROGRESS);
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'guarantee_batch_contract_draft', 'contract_draft_id', 'batch_id');
    }

    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class, Milestone::class);
    }

    public function current_invoice()
    {
        return $this->hasManyThrough(Invoice::class, Milestone::class)
            ->where('guarantee_invoice.status', '!=',Invoice::STATUS_COMPLETED);
    }

    public function summary()
    {
        return $this->hasOne(ContractDraftSummary::class);
    }


    public function contract_signed()
    {
        return $this->hasOne(ContractSigned::class);
    }



    public static function getCurrentAcceptAttribute()
    {
        return Auth::user()['role'] . "_accepted";
    }

    public static function getInterlocutorAcceptAttribute()
    {
        return \Auth::user()->getInterlocutorRole() . '_accepted';
    }
}
