<?php

namespace App\Travel\Projects\Repositories\Interfaces;

use App\Travel\Projects\Exceptions\{CreateGuaranteeProjectErrorException,
    DeleteGuaranteeProjectErrorException,
    NotFoundGuaranteeProjectErrorException,
    UpdateGuaranteeProjectErrorException};
use App\Travel\Projects\GuaranteeProject;
use App\Travel\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

interface GuaranteeProjectRepositoryInterface extends RepositoryInterface
{
    /**
     * @param GuaranteeProject $model
     * @return array
     */
    public function getBaseData(GuaranteeProject $model): array;

    /**
     * @param GuaranteeProject $model
     * @return array
     */
    public function getInterlocutorUser(GuaranteeProject $model): User;

    /**
     * @param array $data
     * @param int $id
     * @return GuaranteeProject
     * @throws UpdateGuaranteeProjectErrorException
     */
    public function updateGuaranteeProject(array $data, int $id): GuaranteeProject;

    /**
     * @param int $id
     * @return GuaranteeProject
     * @throws NotFoundGuaranteeProjectErrorException
     */
    public function findGuaranteeProjectById(int $id): GuaranteeProject;

    /**
     * @param array $params
     * @return GuaranteeProject | Model
     */
    public function findGuaranteeProjectByParams($params);

    /**
     * @param int $id
     * @param mixed $with
     * @return GuaranteeProject
     * @throws NotFoundGuaranteeProjectErrorException
     */
    public function getGuaranteeProjectById(int $id, $with = []): GuaranteeProject;

    /**
     * @param array $with
     * @param User $user
     * @return GuaranteeProject [] | Collection
     */
    public function listGuaranteeProjectByUser( $user, $with = []);

    /**
     * @param array $data
     * @return GuaranteeProject
     * @throws CreateGuaranteeProjectErrorException
     */
    public function createGuaranteeProject(array $data = []) :GuaranteeProject;

    /**
     * @param int $id
     * @return bool
     * @throws DeleteGuaranteeProjectErrorException
     */
    public function deleteGuaranteeProjectById(int $id) :bool;

}