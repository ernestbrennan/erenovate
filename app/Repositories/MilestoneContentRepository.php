<?php

namespace App\Repositories;

use App\Travel\Batches\Batch;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Materials\Material;
use App\Travel\Milestones\MilestoneContent;

class MilestoneContentRepository extends MilestoneContent
{
    public static function createWithRelations($milestone, ContractDraft $contract_draft, $version, $status)
    {
        $milestone_content = $milestone['milestone_content'];

        dd($contract_draft);

        $createAttributes = collect($milestone_content)
            ->put('start_date', date("Y-m-d", strtotime($milestone_content['start_date'])))
            ->put('end_date', date("Y-m-d", strtotime($milestone_content['end_date'])))
            ->put('milestone_id', $milestone['id'])
            ->put('status', MilestoneContent::STATUS_ACTIVE)
            ->put('version', $version)
            ->except(['id', 'created_at', 'updated_at'])
            ->toArray();

        $new_milestone_content = MilestoneContent::query()->create($createAttributes);

        foreach ($milestone_content['batches'] as $batch) {

            $new_batch = Batch::query()
                ->create([
                    'user_id' => $batch['user']['userId'],
                    'description' => $batch['description']
                ]);

            $new_batch->files()->attach(array_pluck($batch['files'], 'id'));
            $new_milestone_content->batches()->attach([$new_batch['id']]);
        }

        if ($contract_draft['materials_provide_on'] === ContractDraft::MATERIAL_PROVIDE_ON_CONTRACT) {

            foreach ($milestone_content['materials'] as $material) {

                $new_material = Material::query()->create($material);

                $new_milestone_content->materials()->attach([$new_material['id']]);
            }

            $new_milestone_content->material_files()->attach(array_pluck($milestone_content['material_files'], 'id'));
        }

        return $milestone_content;
    }
}
