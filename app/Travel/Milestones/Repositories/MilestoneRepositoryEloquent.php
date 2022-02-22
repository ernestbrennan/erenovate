<?php

namespace App\Travel\Milestones\Repositories;

use App\Travel\Milestones\Exceptions\NotFoundMilestoneErrorException;
use App\Travel\Milestones\Milestone;
use App\Travel\Milestones\MilestoneContent;
use App\Travel\Milestones\Repositories\Interfaces\MilestoneRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Travel\Contracts\Exceptions\{
    NotFoundContractErrorException
};
use Exception;

class MilestoneRepositoryEloquent extends BaseRepository implements MilestoneRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Milestone::class;
    }

    /**
     * @param int $contract_draft_id
     * @param mixed $with
     * @return mixed
     * @throws NotFoundMilestoneErrorException
     */
    public function getCurrentMilestone(int $contract_draft_id, $with = [])
    {
        try {
            return Milestone::query()
                ->withRelations()
                ->with(['invoice'])
                ->whereHas('milestone_content', function($query) {
                    $query->where('status', MilestoneContent::STATUS_ACTIVE);
                })
                ->where('contract_draft_id', $contract_draft_id)
                ->first();

        } catch (Exception $e) {
            throw new NotFoundMilestoneErrorException($e);
        }
    }

    /**
     * @param int $id
     * @param mixed $with
     * @return \App\Models\Milestone
     * @throws NotFoundMilestoneErrorException
     */
    public function getMilestoneById(int $id, $with = []): Milestone
    {
        try {
            return $this->with($with)->find($id);
        } catch (Exception $e) {
            throw new NotFoundMilestoneErrorException($e);
        }
    }
}