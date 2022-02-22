<?php

namespace App\Travel\Milestones\Services;

use App\Travel\Batches\Batch;
use App\Travel\Materials\Material;
use App\Travel\Milestones\Milestone;
use App\Travel\SystemNotifications\MilestoneContent\SystemNotificationMilestoneContentAccepted;
use App\Travel\SystemNotifications\MilestoneContent\SystemNotificationMilestoneContentEdited;
use App\Travel\SystemNotifications\MilestoneContent\SystemNotificationMilestoneContentRejected;
use Illuminate\Http\Request;
use App\Services\TimelineService;
use App\Travel\Milestones\MilestoneContent;
use App\Travel\Projects\GuaranteeProject;

class MilestoneService
{
    public function getCurrent(Request $request)
    {
        $guarantee_project = GuaranteeProject::with([
            'contract',
            'contract.last_draft',
            'contract.last_draft.current_milestone',
            'contract.last_draft.current_milestone.invoice',
            'contract.last_draft.current_milestone.milestone_content' => function ($query) {
                $query->where('status', MilestoneContent::STATUS_ACTIVE);
            },
            'contract.last_draft.current_milestone.milestone_content.batches',
            'contract.last_draft.current_milestone.milestone_content.batches.user',
            'contract.last_draft.current_milestone.milestone_content.batches.files',
            'contract.last_draft.current_milestone.milestone_content.materials',
            'contract.last_draft.current_milestone.milestone_content.material_files',
        ])
            ->find($request->input('project_id'));

        $milestone = $guarantee_project['contract']['last_draft']['current_milestone'];

        $edited_milestone_content = MilestoneContent::where('milestone_id', $milestone['id'])->where('status', MilestoneContent::STATUS_PENDING)->first();

        $milestone['edited'] = [
            'user_id' => $edited_milestone_content->edited_notification->message['sender_id'] ?? null,
            'is_edited' => !empty($edited_milestone_content)
        ];

        return [
            'milestone' => $milestone,
            'timeline' => TimelineService::getTimeLine($guarantee_project['contract']['id']),
            'guarantee_project' => $guarantee_project->getData(),
        ];
    }

    public function getById(Request $request)
    {
        $milestone_id = $request->input('milestone_id');
        $version = $request->input('version', null);

        $milestone = Milestone::with([
            'contract_draft',
            'contract_draft.contract',
            'contract_draft.contract.guarantee_project',
            'invoice',
            'milestone_content' => function ($query) use ($version) {
                if ($version) {
                    $query->where('version', $version);
                } else {
                    $query->where('status', MilestoneContent::STATUS_ACTIVE);
                }
            },
            'milestone_content.batches',
            'milestone_content.batches.user',
            'milestone_content.batches.files',
            'milestone_content.materials',
            'milestone_content.material_files',
        ])
            ->find($milestone_id);

        $contract_draft = $milestone->contract_draft;
        $guarantee_project = $milestone->contract_draft->contract->guarantee_project;

        $edited_milestone_content = MilestoneContent::where('milestone_id', $milestone['id'])->where('status', MilestoneContent::STATUS_PENDING)->first();

        $milestone['edited'] = [
            'user_id' => $edited_milestone_content->edited_notification->message['sender_id'] ?? null,
            'is_edited' => !empty($edited_milestone_content)
        ];

        return [
            'milestone' => $milestone,
            'timeline' => TimelineService::getTimeLine($contract_draft['contract_id']),
            'guarantee_project' => $guarantee_project->getData(),
        ];
    }

    public function getEdited(Request $request)
    {
        $milestone_id = $request->input('milestone_id');

        $milestone = Milestone::with([
            'contract_draft',
            'contract_draft.contract',
            'contract_draft.contract.guarantee_project',
            'invoice',
            'milestone_content' => function ($query) {
                $query->where('status', MilestoneContent::STATUS_PENDING);
            },
            'milestone_content.batches',
            'milestone_content.batches.user',
            'milestone_content.batches.files',
            'milestone_content.materials',
            'milestone_content.material_files',
        ])
            ->find($milestone_id);

        $milestone['edited'] = [
            'user_id' => $milestone->milestone_content->edited_notification->message['sender_id'] ?? null,
            'has_milestone_edited' => true
        ];

        return [
            'milestone' => $milestone,
        ];
    }

    public function edit(Request $request)
    {
        $milestone = $request->input('milestone');
        $comment = $request->input('comment', '');

        $milestone_content = collect($milestone['milestone_content']);

        $new_milestone_content = MilestoneContent::query()->create(
            $milestone_content
                ->put('status', MilestoneContent::STATUS_PENDING)
                ->put('version', MilestoneContent::query()->where('milestone_id', $milestone['id'])->orderBy('id', 'desc')->first()->version + 1)
                ->toArray()
        );

        foreach ($milestone_content['batches'] as $batch) {

            $new_batch = Batch::query()
                ->create([
                    'user_id' => $batch['user']['userId'],
                    'description' => $batch['description']
                ]);

            $new_batch->files()->attach(array_pluck($batch['files'], 'id'));
            $new_milestone_content->batches()->attach([$new_batch['id']]);
        }


        foreach ($milestone_content['materials'] as $material) {
            if (!empty($material['title'])) {

                $new_material = Material::query()->create($material);
                $new_milestone_content->materials()->attach([$new_material['id']]);
            }
        }

        if ($ids = array_pluck($milestone_content['material_files'], 'id')) {

            $new_milestone_content->material_files()->attach($ids);
        }

        SystemNotificationMilestoneContentEdited::doNotification($new_milestone_content, $comment);
    }

    public function accept(Request $request)
    {
        $milestone = $request->input('milestone');
        $comment = $request->input('comment', '');

        MilestoneContent::query()
            ->where('milestone_id', $milestone['id'])
            ->where('status', MilestoneContent::STATUS_ACTIVE)
            ->update([
                'status' => MilestoneContent::STATUS_REJECTED
            ]);

        $milestone_content = MilestoneContent::query()->find($milestone['milestone_content']['id']);

        $milestone_content->update([
            'status' => MilestoneContent::STATUS_ACTIVE
        ]);

        SystemNotificationMilestoneContentAccepted::doNotification($milestone_content, $comment);
    }

    public function reject(Request $request)
    {
        $milestone = $request->input('milestone');
        $comment = $request->input('comment', '');

        $milestone_content = MilestoneContent::query()->find($milestone['milestone_content']['id']);

        $milestone_content->update([
            'status' => MilestoneContent::STATUS_REJECTED
        ]);

        SystemNotificationMilestoneContentRejected::doNotification($milestone_content, $comment);
    }
}
