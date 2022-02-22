<?php

namespace App\Http\Controllers\Front;

use App\Events\ContractCompletedEvent;
use App\Http\Controllers\Controller;
use App\Events\Mail\PayFeeEmailEvent;
use App\Http\Requests\Contract as R;
use App\Services\TimelineService;
use App\Travel\Contracts\Contract;
use App\Travel\Contracts\Requests\GetListRequest;
use App\Travel\Contracts\Services\ContractService;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Projects\GuaranteeProject;
use App\Travel\Issues\Issue;
use App\Travel\Messages\Message;
use App\Travel\Milestones\Milestone;
use App\Travel\Milestones\MilestoneContent;
use App\Travel\Summary\Services\SummaryService;
use App\Travel\SystemNotifications\Contract\SystemNotificationContractCompleted;
use App\Travel\SystemNotifications\Contract\SystemNotificationContractSigned;

class ContractController extends Controller
{
    public $service;

    public function __construct(ContractService $service)
    {
        $this->service = $service;
    }

    public function getList(GetListRequest $request)
    {
        return $this->service->listContract($request);
    }

    public function getByProject(R\CreateRequest $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');

        $guarantee_project = GuaranteeProject::query()->find($guarantee_project_id);

        $contract = Contract::with([
            'current_user_info',
            'interlocutor_info',
            'interlocutor_info.user:userId,user_photo,firstname,lastname',
        ])
            ->where('guarantee_project_id', $guarantee_project_id)
            ->first();

        return [
            'contract' => $contract,
            'project_name' => $guarantee_project->project['project_name'],
            'guarantee_project' => $guarantee_project->getData()
        ];
    }

    public function getDraftHistory(R\GetDraftHistoryRequest $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');

        $guarantee_project = GuaranteeProject::with([
            'contract',
            'contract.last_draft',
        ])
            ->find($guarantee_project_id);

        $last_draft = $guarantee_project['contract']['last_draft'];

        $messages = Message::getNotificationsByProject($guarantee_project_id, ['sender:userId,lastname,firstname']);

        $drafts_history = [];
        $draft_version = 1;

        foreach ($messages as $message) {
            if (!empty($message['notification']['contract_draft'])) {
                $draft_version = (int)$message['notification']['contract_draft']['version'];
            }

            $drafts_history[$draft_version][] = $message;
        }

        return [
            'drafts_history' => $drafts_history,
            'guarantee_project' => $guarantee_project->getData(),
        ];
    }

    public function getContractSigned(R\GetContractSignedRequest $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');

        $guarantee_project = GuaranteeProject::with('contract')->find($guarantee_project_id);

        $contract = Contract::with([
            'current_user_info',
            'interlocutor_info',
            'interlocutor_info.user:userId,user_photo,firstname,lastname',
            'signed_notification'
        ])
            ->where('id', $guarantee_project['contract']['id'])
            ->where('status', '!=', Contract::STATUS_PENDING)
            ->first();

        $contract_draft = ContractDraft::with([
            'summary',
            'batches',
            'batches.user',
            'batches.files',
            'contract_signed',
            'contract_signed.file',
            'milestones',
            'milestones.milestone_content' => function ($query) {
                $query->where('version', 1);
            },
            'milestones.milestone_content.batches',
            'milestones.milestone_content.batches.user',
            'milestones.milestone_content.batches.files',
            'milestones.milestone_content.materials',
            'milestones.milestone_content.material_files',
        ])
            ->withCount('invoices')
            ->where('contract_id', $contract['id'])
            ->where('status', '!=', ContractDraft::STATUS_REJECTED)
            ->orderBy('id', 'desc')
            ->first()
            ->toArray();

        $milestone_content = MilestoneContent::query()
            ->whereIn('milestone_id', array_pluck($contract_draft['milestones'], 'id'))
            ->where('status', MilestoneContent::STATUS_PENDING)
            ->first();

        $contract_draft['milestone_edited_id'] = $milestone_content ? $milestone_content->milestone->id : false;

        return [
            'guarantee_project' => $guarantee_project->getData(),
            'contract' => $contract,
            'contract_draft' => $contract_draft,
            'timeline' => TimelineService::getTimeLine($contract['id']),
        ];
    }

    public function confirmComplete(R\AcceptContractRequest $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');
        $comment = $request->input('comment');

        Issue::where('guarantee_project_id', $guarantee_project_id)
            ->where('type', Issue::TYPE_PRICE_MODIFICATION)
            ->where('status', '!=', Issue::STATUS_CLOSED)
            ->get()
            ->map(function ($item) {
                $item->update(['status' => Issue::STATUS_CLOSED]);
            });

        $guarantee_project = GuaranteeProject::with('contract')->find($guarantee_project_id);

        $guarantee_project->contract->update(['ho_confirm_complete' => true]);

        $summary_table = SummaryService::getSummaryTable($guarantee_project->contract['id']);

        $estimated_total = (int)(format_price($summary_table['payment_total']['estimated_total']));
        $final_total = (int)format_price($summary_table['payment_total']['final_total']);

        if ($estimated_total >= $final_total) {

            $guarantee_project->contract->update(['status' => Contract::STATUS_COMPLETED]);
        } else {
            event(new PayFeeEmailEvent($guarantee_project, true));
        }

        SystemNotificationContractCompleted::doNotification($guarantee_project->contract, $comment);
        event(new ContractCompletedEvent($guarantee_project));

        return [
            'status_code' => 200
        ];
    }


}
