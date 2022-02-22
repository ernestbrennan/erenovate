<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Travel\Contracts\Contract;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Projects\GuaranteeProject;
use App\Travel\Users\UserInfo;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function getSigned(Request $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');

        $guarantee_project = GuaranteeProject::with('contract')->find($guarantee_project_id);

        $contract = Contract::with([
            'signed_notification'
        ])
            ->where('id', $guarantee_project['contract']['id'])
            //->where('status', '!=', Contract::STATUS_PENDING)
            ->first();

        $contract['homeowner_info'] = UserInfo::with('user')
            ->where('contract_id', $contract['id'])
            ->whereHas('user', function ($query) use ($guarantee_project) {
                $query->where('user_id', $guarantee_project->project->homeowner['userId']);
            })
            ->first();

        $contract['contractor_info'] = UserInfo::with('user')
            ->where('contract_id', $contract['id'])
            ->whereHas('user', function ($query) use ($guarantee_project) {
                $query->where('user_id', $guarantee_project->contractor['userId']);
            })
            ->first();

        $contract_draft = ContractDraft::with([
            'summary',
            'batches',
            'batches.user',
            'batches.files',
            'contract_signed',
            'contract_signed.file',
            'milestones',
            'milestones.milestone_content'  => function($query){
                $query->where('version', 1);
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
            'contract' => $contract,
            'contract_draft' => $contract_draft->setHidden(['current_accepted', 'interlocutor_accepted'])->toArray(),
            'guarantee_project' => GuaranteeProject::with('issues')->find($guarantee_project_id)
        ];
    }
}
