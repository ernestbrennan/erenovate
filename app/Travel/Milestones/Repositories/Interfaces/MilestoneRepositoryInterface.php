<?php

namespace App\Travel\Milestones\Repositories\Interfaces;

use App\Travel\Milestones\Exceptions\NotFoundMilestoneErrorException;
use App\Travel\Milestones\Milestone;
use phpDocumentor\Reflection\Types\Mixed_;
use Prettus\Repository\Contracts\RepositoryInterface;

interface MilestoneRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $contract_id
     * @param mixed $with
     * @return mixed
     * @throws NotFoundMilestoneErrorException
     */
    public function getCurrentMilestone(int $contract_id, $with = []);

    /**
     * @param int $id
     * @param mixed $with
     * @return Milestone
     * @throws NotFoundMilestoneErrorException
     */
    public function getMilestoneById(int $id, $with = []): Milestone;
}