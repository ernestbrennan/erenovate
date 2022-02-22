<?php

namespace App\Travel\Projects\Repositories;

use App\Travel\Contracts\Contract;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Projects\Exceptions\{CreateGuaranteeProjectErrorException,
    DeleteGuaranteeProjectErrorException,
    NotFoundGuaranteeProjectErrorException,
    UpdateGuaranteeProjectErrorException};
use App\Travel\Projects\GuaranteeProject;
use App\Travel\Projects\Repositories\Interfaces\GuaranteeProjectRepositoryInterface;

use App\Travel\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository;
use Exception;

class GuaranteeProjectRepositoryEloquent extends BaseRepository implements GuaranteeProjectRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return GuaranteeProject::class;
    }


    /**
     * @param int $id
     * @return GuaranteeProject
     * @throws NotFoundGuaranteeProjectErrorException
     */
    public function findGuaranteeProjectById(int $id): GuaranteeProject
    {
        try {
            return $this->find($id);
        } catch (Exception $e) {
            throw new NotFoundGuaranteeProjectErrorException($e);
        }
    }


    /**
     * @param int $id
     * @param mixed $with
     * @return GuaranteeProject
     * @throws NotFoundGuaranteeProjectErrorException
     */
    public function getGuaranteeProjectById(int $id, $with = []): GuaranteeProject
    {
        try {
            return $this->with($with)->find($id);
        } catch (Exception $e) {
            throw new NotFoundGuaranteeProjectErrorException($e);
        }
    }

    /**
     * @param array $data
     * @param int $id
     * @return GuaranteeProject
     * @throws UpdateGuaranteeProjectErrorException
     */
    public function updateGuaranteeProject(array $data, int $id): GuaranteeProject
    {
        try {
            return $this->update($data, $id);
        } catch (Exception $e) {
            throw new UpdateGuaranteeProjectErrorException($e);
        }
    }
    /**
     * @param User $user
     * @param array $with
     * @return GuaranteeProject [] | Collection
     */
    public function listGuaranteeProjectByUser($user, $with = [])
    {
        return $query = GuaranteeProject::with($with)
            ->where($user->role . '_id', $user->userId)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * @param GuaranteeProject $model
     * @return array
     */
    public function getBaseData(GuaranteeProject $model): array
    {
        $contract_status = $model->contract->status;
        $has_contract_draft = !empty($model->contract->last_draft) && $model->contract->last_draft['status'] != ContractDraft::STATUS_REJECTED;
        $pay_fee = $contract_status  === Contract::STATUS_PENDING && $model->contract->last_draft['current_accepted'] && $model->contract->last_draft['interlocutor_accepted'];

        return [
            'id' => $model->id,
            'project_link' => $model->project_link,
            'has_contract_draft' => $has_contract_draft,
            'contract_status' => $contract_status,
            'price_discrepancy' => \Auth::user()->role === User::ROLE_HOMEOWNER ? $model->price_discrepancy : null,
            'project_name' => $model->project['project_name'],
            'pay_fee' => $pay_fee
        ];
    }

    /**
     * @param GuaranteeProject $model
     * @return array
     */
    public function getInterlocutorUser(GuaranteeProject $model): User
    {
        return \Auth::id() === $model['homeowner_id'] ? $model['contractor'] : $model['homeowner'];
    }

    /**
     * @param array $params
     * @return GuaranteeProject | Model
     */
    public function findGuaranteeProjectByParams($params)
    {
        return GuaranteeProject::query()
            ->where('contractor_id', $params['contractor_id'])
            ->where('project_id',  $params['project_id'])
            ->where('status', GuaranteeProject::STATUS_ACTIVE)
            ->first();
    }

    /**
     * @param array $data
     * @return GuaranteeProject
     * @throws CreateGuaranteeProjectErrorException
     */
    public function createGuaranteeProject(array $data = []): GuaranteeProject
    {
        try {
            return $this->create(array_merge($data, [
                'status' => GuaranteeProject::STATUS_ACTIVE,
                'contractor_visited' => GuaranteeProject::NOT_VISITED,
                'homeowner_visited' => GuaranteeProject::NOT_VISITED
            ]));

        } catch (\Exception $e) {
            throw new CreateGuaranteeProjectErrorException($e);
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws DeleteGuaranteeProjectErrorException
     */
    public function deleteGuaranteeProjectById(int $id): bool
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            throw new DeleteGuaranteeProjectErrorException($e);
        }
    }
}