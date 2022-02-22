<?php

namespace App\Travel\Projects\Repositories\Interfaces;

use App\Travel\Projects\ErenovateProject;
use App\Travel\Projects\Exceptions\{
    NotFoundErenovateProjectErrorException,
};
use Prettus\Repository\Contracts\RepositoryInterface;

interface ErenovateProjectRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $id
     * @return ErenovateProject
     * @throws NotFoundErenovateProjectErrorException
     */
    public function findErenovateProjectById(int $id): ErenovateProject;

    /**
     * @param array $data
     * @return ErenovateProject
     */
    public function createErenovateProject($data);


}