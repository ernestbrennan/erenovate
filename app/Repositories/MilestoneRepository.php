<?php

namespace App\Repositories;

use App\Travel\Batches\Batch;
use App\Travel\Materials\Material;
use App\Travel\Milestones\Milestone;
use App\Travel\Milestones\MilestoneContent;

class MilestoneRepository extends Milestone
{
    public static function createWithRelations($milestone, $contract_draft_id, $sequence)
    {
        $new_milestone = Milestone::query()->create([
            'contract_draft_id' => $contract_draft_id,
            'sequence' => $sequence + 1,
            'status' =>  Milestone::STATUS_PENDING,
        ]);
        $milestone_content = $milestone['milestone_content'];

        $milestone_content_create_attributes = collect($milestone_content)
            ->put('milestone_id', $new_milestone['id'])
            ->put('start_date', date("Y-m-d", strtotime($milestone_content['start_date'])))
            ->put('end_date', date("Y-m-d", strtotime($milestone_content['end_date'])))
            ->put('status', MilestoneContent::STATUS_ACTIVE)
            ->put('version', MilestoneContent::DEFAULT_VERSION)
            ->except(['id', 'created_at', 'updated_at'])
            ->toArray();

        $new_milestone_content = MilestoneContent::query()->create($milestone_content_create_attributes);

        foreach ($milestone_content['batches'] as $batch) {

            $new_batch = Batch::query()
                ->create([
                    'user_id' => $batch['user']['userId'],
                    'description' => $batch['description']
                ]);

            $new_batch->files()->attach(array_pluck($batch['files'], 'id'));
            $new_milestone_content->batches()->attach([$new_batch['id']]);
        }

        if ($milestone_content['materials_provide_on'] === MilestoneContent::MATERIAL_PROVIDE_ON_CONTRACT) {

            foreach ($milestone_content['materials'] as $material) {

                if (!empty($material['title'])){
                    $new_material = Material::query()->create($material);

                    $new_milestone_content->materials()->attach([$new_material['id']]);
                }
            }

            $new_milestone_content->material_files()->attach(array_pluck($milestone_content['material_files'], 'id'));
        }
    }

    public static function findById($id)
    {
        return self::query()->find($id)->first();
    }

    public function getGuaranteeProject()
    {
        return $this->contract_draft->contract->guarantee_project;
    }
}
