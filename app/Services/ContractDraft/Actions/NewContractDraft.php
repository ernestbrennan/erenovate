<?php

namespace App\Services;

use App\Repositories\ContractDraftRepository;
use App\Travel\Contracts\Contract;
use App\Travel\Users\UserInfo;

class NewContractDraft extends ContractDraftService
{

    public function make($contract, $contract_draft)
    {
        $contract_draft = collect($contract_draft)
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

        return ContractDraftRepository::createWithRelations(
            $contract_draft,
            $version
        );
    }

}