<?php

namespace App\Travel\SystemNotifications;

use App\Travel\Contracts\Contract;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Invoices\Invoice;
use App\Travel\Issues\Issue;
use App\Travel\Materials\MaterialsContent;
use App\Travel\Messages\Message;
use App\Travel\Milestones\Milestone;
use App\Travel\Milestones\MilestoneContent;
use App\Travel\Users\User;
use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    protected $table = 'guarantee_system_notification';

    protected $fillable = [
        'message_id', 'contract_draft_id', 'contract_id', 'invoice_id', 'milestone_id', 'milestone_content_id', 'materials_content_id', 'issue_id', 'type',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function contract_draft()
    {
        return $this->belongsTo(ContractDraft::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }

    public function milestone_content()
    {
        return $this->belongsTo(MilestoneContent::class);
    }

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    public function getTitle($for = 'notification')
    {
        $type = $this->type;
        $content = '';

        switch ($type) {
            case AbstractSystemNotification::TYPE_CONTRACT_PROJECT_HIRE_READY:
            case AbstractSystemNotification::TYPE_CONTRACT_BOTH_ACCEPTED:
            case AbstractSystemNotification::TYPE_SUMMARY_READY:

                $content = trans("$for.$type");
                break;

            case AbstractSystemNotification::TYPE_MILESTONE_CONTENT_EDITED:
            case AbstractSystemNotification::TYPE_MILESTONE_CONTENT_ACCEPTED:
            case AbstractSystemNotification::TYPE_MILESTONE_CONTENT_REJECTED:

                $content = trans("$for.$type", [
                    'milestone_sequence' => $this->milestone_content->milestone['sequence'],
                    'version' => $this->milestone_content['version']
                ]);
                break;

            case AbstractSystemNotification::TYPE_CONTRACT_DRAFT_REJECTED:
            case AbstractSystemNotification::TYPE_CONTRACT_DRAFT_PROPOSED:

                $content = trans("$for.$type", ['version' => $this->contract_draft['version'] ]);
                break;

            case AbstractSystemNotification::TYPE_INVOICE_CREATED:
            case AbstractSystemNotification::TYPE_INVOICE_UNVERIFIED:
            case AbstractSystemNotification::TYPE_INVOICE_CONFIRMED:
            case AbstractSystemNotification::TYPE_INVOICE_COMPLETED:
            case AbstractSystemNotification::TYPE_INVOICE_REJECTED:
            case AbstractSystemNotification::TYPE_INVOICE_OVERDUE:

                $content = trans("$for.$type", ['sequence' =>  $this->invoice->milestone['sequence'] ]);
                break;


            case AbstractSystemNotification::TYPE_ISSUE_CLOSED:
            case AbstractSystemNotification::TYPE_ADMIN_JOINED:
            case AbstractSystemNotification::TYPE_ADMIN_CLOSED:
            case AbstractSystemNotification::TYPE_ISSUE_PRICE_MODIFICATION_CREATED:

                $content = trans("$for.$type", ['sequence' =>  $this->issue['sequence'] ]);
                break;

            case AbstractSystemNotification::TYPE_ISSUE_CREATED:

                $content = trans("$for.$type", ['sequence' =>  $this->issue['sequence'], 'title' => $this->issue['title'] ]);
                break;


            case AbstractSystemNotification::TYPE_CONTRACT_SIGNED:
            case AbstractSystemNotification::TYPE_CONTRACT_COMPLETED:
            case AbstractSystemNotification::TYPE_USER_INVITE:

                $content = trans("$for.$type", ['project_name' =>  $this->message->chat->guarantee_project->project['project_name'] ]);
                break;

            case AbstractSystemNotification::TYPE_CONTRACT_FIRST_ACCEPTED:
                $role_accepted = $this->message->sender['role'] === User::ROLE_HOMEOWNER ? 'Client' : 'Pro' ;
                $role_need_accept = $this->message->sender['role'] === User::ROLE_HOMEOWNER ? 'Pro' : 'Client' ;


                $content = trans("$for.$type", ['role_accepted' =>  $role_accepted, 'role_need_accept' => $role_need_accept]);
                break;


            case AbstractSystemNotification::TYPE_CONTRACT_DRAFT_OFFERED:

                $role = $this->message->sender['role'] === User::ROLE_HOMEOWNER ? 'Client' : 'Pro' ;
                $content = trans("$for.$type", ['role' =>  $role, 'version' => $this->contract_draft['version']]);
                break;



            case AbstractSystemNotification::TYPE_MATERIALS_CONTENT_EDITED:
            case AbstractSystemNotification::TYPE_MATERIALS_CONTENT_ACCEPTED:
            case AbstractSystemNotification::TYPE_MATERIALS_CONTENT_REJECTED:

                $content = trans("$for.$type", ['milestone_sequence' => $this->materials_content->milestone_content->milestone['sequence']]);
                break;
        }

        return $content;
    }

    public function loadTarget()
    {
        if (in_array($this['type'], AbstractSystemNotification::CONTRACT)) {
            $this->load('contract');
        }
        if (in_array($this['type'], AbstractSystemNotification::CONTRACT_DRAFT)) {
            $this->load('contract_draft');

        } else if (in_array($this['type'], AbstractSystemNotification::MILESTONE_CONTENT)) {
            $this->load('milestone_content.milestone');

        } else if (in_array($this['type'], AbstractSystemNotification::MATERIALS_CONTENT)) {
            $this->load('materials_content');

        } else if (in_array($this['type'], AbstractSystemNotification::INVOICE)) {
            $this->load('invoice.milestone');

        } else if (in_array($this['type'], AbstractSystemNotification::ISSUE)) {
            $this->load('issue');
        }
    }

}
