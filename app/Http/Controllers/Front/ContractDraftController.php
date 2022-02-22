<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Events\Mail\PayFeeEmailEvent;
use App\Http\Requests\ContractDraft as R;
use App\Repositories\ContractDraftRepository;
use App\Services\Snappy\Pdf;
use App\Travel\Contracts\Contract;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Projects\GuaranteeProject;
use App\Travel\Milestones\MilestoneContent;
use App\Travel\SystemNotifications\Contract\SystemNotificationContractBothAccepted;
use App\Travel\SystemNotifications\Contract\SystemNotificationContractFirstAccepted;
use App\Travel\SystemNotifications\ContractDraft\SystemNotificationContractDraftOffered;
use App\Travel\SystemNotifications\ContractDraft\SystemNotificationContractDraftProposed;
use App\Travel\SystemNotifications\ContractDraft\SystemNotificationContractDraftRejected;
use App\Travel\Users\UserInfo;
use Auth;

class ContractDraftController extends Controller
{
    public function getCurrentDraft(R\CreateRequest $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');

        $guarantee_project = GuaranteeProject::with('contract')->find($guarantee_project_id);

        $contract = Contract::with([
            'current_user_info',
            'interlocutor_info',
            'interlocutor_info.user:userId,user_photo,firstname,lastname',
        ])
            ->where('id', $guarantee_project['contract']['id'])
            ->first();

        $contract_draft = ContractDraft::with([
            'summary',
            'batches',
            'batches.user',
            'batches.files',
            'contract_signed',
            'contract_signed.file',
            'milestones',
            'milestones.milestone_content' => function($query){
                $query->where('status', '!=', MilestoneContent::STATUS_REJECTED);
            },
            'milestones.milestone_content.batches',
            'milestones.milestone_content.batches.user',
            'milestones.milestone_content.batches.files',
            'milestones.milestone_content.materials',
            'milestones.milestone_content.material_files',
        ])
            ->where('contract_id', $contract['id'])
            ->where('status', '!=', ContractDraft::STATUS_REJECTED)
            ->orderBy('id', 'desc')
            ->first();

        return [
            'contract_draft' => $contract_draft,
            'contract' => $contract,
            'guarantee_project' => $guarantee_project->getData()
        ];
    }

    public function getHistoryDraft(R\GetHistoryDraftRequest $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');
        $draft_version = $request->input('draft_version');

        $guarantee_project = GuaranteeProject::with([
            'drafts' => function ($query) use ($draft_version) {

                $query->where('version', $draft_version);
            },
            'drafts.summary',
            'drafts.batches',
            'drafts.batches.user',
            'drafts.batches.files',
            'drafts.contract_signed',
            'drafts.contract_signed.file',
            'drafts.milestones',
            'drafts.milestones.milestone_content.batches',
            'drafts.milestones.milestone_content.batches.user',
            'drafts.milestones.milestone_content.batches.files',
            'drafts.milestones.milestone_content.materials',
            'drafts.milestones.milestone_content.material_files',
        ])
            ->find($guarantee_project_id);

        $contract_draft = $guarantee_project['drafts'][0];

        return [
            'contract_draft' => $contract_draft,
            'guarantee_project' => $guarantee_project->getData()
        ];
    }

    public function newDraft(R\UpdateRequest $request)
    {
        $contract = collect($request->input('contract'));
        $comment = $request->input('comment');

        $contract_draft = collect($request->input('contract_draft'))
            ->put('contract_id', $contract['id'])
            ->put('homeowner_accepted', false)
            ->put('contractor_accepted', false);

        $current = $contract['current_user_info'];

        UserInfo::query()
            ->find($current['id'])
            ->update(
                collect($current)
                    ->put('status', UserInfo::STATUS_PENDING)
                    ->except(['id', 'created_at', 'updated_at'])
                    ->toArray()
            );

        $last_version = Contract::query()->find($contract['id'])->drafts->last()['version'] ?? 0;
        $version = intval($last_version) + 1;

        $new_contract_draft = ContractDraftRepository::createWithRelations($contract_draft, $version);

        SystemNotificationContractDraftOffered::doNotification($new_contract_draft, $comment);

        return [
            'success' => true
        ];
    }

    public function proposeDraft(R\UpdateRequest $request)
    {
        $contract = $request->input('contract');
        $comment = $request->input('comment');

        $contract_draft = collect($request->input('contract_draft'));
        $user_info = $contract['current_user_info'];

        $contract = Contract::with('last_draft')->find($contract['id']);

        if ($user_info['status'] === 'new') {

            UserInfo::query()->find($user_info['id'])->update(
                collect($user_info)
                    ->put('status', UserInfo::STATUS_PENDING)
                    ->except(['id', 'created_at', 'updated_at'])
                    ->toArray()
            );
        }

        $last_version = strval(floatval($contract['last_draft']['version']) + 0.1);

        $contract_draft
            ->put(ContractDraft::getCurrentAcceptAttribute(), true)
            ->put(ContractDraft::getInterlocutorAcceptAttribute(), false);

        $new_contract_draft = ContractDraftRepository::createWithRelations($contract_draft, $last_version);

        SystemNotificationContractDraftProposed::doNotification($new_contract_draft, $comment);

        return [
            'success' => true
        ];
    }

    public function reject(R\RejectRequest $request)
    {
        $contract_draft_id = $request->input('contract_draft_id');
        $comment = $request->input('comment');

        $contract_draft = ContractDraft::query()->find($contract_draft_id);

        $contract_draft->update([
            'status' => ContractDraft::STATUS_REJECTED
        ]);

        SystemNotificationContractDraftRejected::doNotification($contract_draft, $comment);

        return [
            'success' => true
        ];
    }

    public function accept(R\AcceptRequest $request)
    {
        $contract_draft_id = $request->input('contract_draft_id');
        $comment = $request->input('comment');
        $user_info = $request->input('user_info');

        $current_accept_attr = Auth::user()['role'] . "_accepted";

        $contract_draft = ContractDraft::query()->find($contract_draft_id);

        if ($user_info['status'] === 'new') {

            UserInfo::query()->find($user_info['id'])->update(
                collect($user_info)
                    ->put('status', UserInfo::STATUS_PENDING)
                    ->except(['id', 'created_at', 'updated_at'])
                    ->toArray()
            );
        }

        $update = [
            $current_accept_attr => true
        ];

        if ($contract_draft['interlocutor_accepted']) {

            $update['status'] = ContractDraft::STATUS_ACCEPTED;

            SystemNotificationContractBothAccepted::doNotification($contract_draft->contract, $comment);

            event(new PayFeeEmailEvent(GuaranteeProject::find($contract_draft->contract['guarantee_project_id'])));

        } else{

            SystemNotificationContractFirstAccepted::doNotification($contract_draft->contract, $comment);
        }

        $contract_draft->update($update);

        return [
            'success' => true
        ];
    }

    public function exportPdf(R\ExportPdfRequest $request){

        $contract_draft = $request->input('contract_draft');
        $contract = $request->input('contract');

        $view = view('pdf.contract.index', [
            'contract_draft' => $contract_draft,
            'contract' => $contract,
        ])
            ->render();

        $pdf_generator = new Pdf(env('WKHTMLTOPDF_COMMAND'));
        $pdf = $pdf_generator->getOutputFromHtml($view, getPdfGenerateParams());

        $filename = 'contract_draft_'. $contract_draft['id'] . '.pdf';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'File-Name' => urlencode($filename),

            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
        ]);
    }

}
