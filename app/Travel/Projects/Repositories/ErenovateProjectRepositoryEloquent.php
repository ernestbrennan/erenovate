<?php

namespace App\Travel\Projects\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Travel\Projects\Repositories\Interfaces\ErenovateProjectRepositoryInterface;
use App\Travel\Projects\ErenovateProject;
use App\Travel\Projects\Exceptions\{
    NotFoundErenovateProjectErrorException,
    NotFoundGuaranteeProjectErrorException,
    UpdateGuaranteeProjectErrorException
};
use Exception;

class ErenovateProjectRepositoryEloquent extends BaseRepository implements ErenovateProjectRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return ErenovateProject::class;
    }


    /**
     * @param int $id
     * @return ErenovateProject
     * @throws NotFoundErenovateProjectErrorException
     */
    public function findErenovateProjectById(int $id): ErenovateProject
    {
        try {
            return $this->find($id);
        } catch (Exception $e) {
            throw new NotFoundErenovateProjectErrorException($e);
        }
    }


    /**
     * @param array $data
     * @return ErenovateProject
     */
    public function createErenovateProject($data)
    {
        // TODO: Implement createErenovateProject() method.
    }
}