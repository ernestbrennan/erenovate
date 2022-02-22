<?php

namespace App\Travel\Contracts;

use Illuminate\Database\Eloquent\Model;
use App\Travel\Invoices\Invoice;
use App\Travel\Issues\Issue;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Projects\GuaranteeProject;
use App\Travel\SystemNotifications\AbstractSystemNotification;
use App\Travel\SystemNotifications\SystemNotification;
use App\Travel\Users\UserInfo;

class Contract extends Model
{
    protected $table = 'guarantee_contract';

    const STATUS_PENDING = 'pending';
    const STATUS_SIGNED = 'signed';
    const STATUS_FINISHED = 'finished';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'guarantee_project_id', 'status', 'ho_confirm_complete'
    ];

    public function guarantee_project()
    {
        return $this->belongsTo(GuaranteeProject::class);
    }

    public function current_user_info()
    {
        return $this->hasOne(UserInfo::class, 'contract_id', 'id')
            ->where('user_id', \Auth::id());
    }

    public function interlocutor_info()
    {
        return $this->hasOne(UserInfo::class, 'contract_id', 'id')
            ->where('user_id', '!=', \Auth::id());
    }

    public function drafts()
    {
        return $this->hasMany(ContractDraft::class);
    }

    public function last_draft()
    {
        return $this->hasOne(ContractDraft::class)->orderBy('id', 'desc');
    }

    public function notifications()
    {
        return $this->hasManyThrough(SystemNotification::class, ContractDraft::class);
    }

    public function signed_notification()
    {
        return $this->hasOne(SystemNotification::class)
            ->where('type', AbstractSystemNotification::TYPE_CONTRACT_SIGNED);
    }


    public function getStatusInfo()
    {
        $status = $this['status'];

        if ($status === self::STATUS_SIGNED) {

            $milestone_ids = $this->last_draft->milestones->pluck('id');

            $has_invoice = Invoice::query()
                ->whereIn('milestone_id', $milestone_ids)
                ->where('status', '!=', Invoice::STATUS_REJECTED)
                ->where('status', '!=', Invoice::STATUS_COMPLETED)
                ->exists();
        }

        if ($status === self::STATUS_FINISHED || $status === self::STATUS_COMPLETED) {

            $has_issue = Issue::query()
                ->where('guarantee_project_id', $this['guarantee_project_id'])
                ->where('status', '!=', Issue::STATUS_CLOSED)
                ->exists();
        }

        return [
            'status' => $this['status'],
            'current_milestone_sequence' => $this['status'] === self::STATUS_SIGNED ?
                $this->last_draft->current_milestone['sequence'] : null,
            'has_invoice' => $has_invoice ?? null,
            'has_issue' => $has_issue ?? null,
        ];
    }
}
