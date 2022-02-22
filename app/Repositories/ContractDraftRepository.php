<?php

namespace App\Repositories;

use App\Travel\Batches\Batch;
use App\Travel\Contracts\ContractSigned;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\ContractsDraft\ContractDraftSummary;

class ContractDraftRepository extends ContractDraft
{
    public static function createWithRelations($contract_draft, $version)
    {
        $batches = $contract_draft['batches'];
        $milestones = $contract_draft['milestones'];

        $new_contract_draft = ContractDraft::query()
            ->create([
                'user_id' => \Auth::id(),
                'contract_id' => $contract_draft['contract_id'],
                'version' => $version,
                'type' => $contract_draft['type'],
                'title' => $contract_draft['title'],
                'description' => $contract_draft['description'],
                'status' => ContractDraft::STATUS_PENDING,
                'homeowner_accepted' => $contract_draft['homeowner_accepted'],
                'contractor_accepted' => $contract_draft['contractor_accepted'],
            ]);

        ContractSigned::query()->create([
            'contract_draft_id' => $new_contract_draft['id'],
            'file_id' => $contract_draft['contract_signed']['file']['id'],
            'description' => $contract_draft['contract_signed']['description']
        ]);

        foreach ($batches as $batch) {

            $new_batch = Batch::query()
                ->create([
                    'user_id' => $batch['user']['userId'],
                    'description' => $batch['description']
                ]);

            $new_batch->files()->attach(array_pluck($batch['files'], 'id'));
            $new_contract_draft->batches()->attach([$new_batch['id']]);
        }

        if ($new_contract_draft['type'] === ContractDraft::TYPE_SINGLE_MILESTONE) {

            $milestones = [
                $milestones[0]
            ];
        }

        foreach ($milestones as $sequence => $milestone) {
            MilestoneRepository::createWithRelations($milestone, $new_contract_draft['id'], $sequence);
        }

        ContractDraftSummary::query()->create([
            'contract_draft_id' => $new_contract_draft['id'],
            'start_date' => $contract_draft['summary']['start_date'],
            'end_date' => $contract_draft['summary']['end_date'],
            'service_cost' => $contract_draft['summary']['service_cost'],
            'material_cost' => $contract_draft['summary']['material_cost'],
        ]);

        return $new_contract_draft;
    }
}
