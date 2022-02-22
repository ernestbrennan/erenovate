<?php

namespace App\Repositories;

use App\Travel\Contracts\Contract;
use App\Travel\Users\UserInfo;

class ContractRepository extends Contract
{
    public static function createWithRelations($project_id)
    {
        $contract = self::query()
            ->create([
                'guarantee_project_id' => $project_id,
                'status' => self::STATUS_PENDING
            ]);

        $contractor = $contract->guarantee_project->contractor;
        $homeowner = $contract->guarantee_project->homeowner;

        UserInfo::query()
            ->create(
                array_merge($contractor->getInfo(), ['contract_id' => $contract['id']])
            );

        UserInfo::query()
            ->create(
                array_merge($homeowner->getInfo(), ['contract_id' => $contract['id']])
            );
    }
}
