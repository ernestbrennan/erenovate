<?php

namespace App\Travel\Issues\Repositories;

use App\Travel\Issues\Exceptions\CreateIssueErrorException;
use App\Travel\Issues\Exceptions\NotFoundIssueErrorException;
use App\Travel\Issues\Exceptions\UpdateIssueErrorException;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Travel\Issues\Repositories\Interfaces\IssueRepositoryInterface;
use App\Travel\Issues\Issue;
use Exception;

class IssueRepositoryEloquent extends BaseRepository implements IssueRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Issue::class;
    }

    /**
     * @param array $data
     * @return Issue
     * @throws CreateIssueErrorException
     */
    public function createIssue(array $data = []): Issue
    {
        try {
            return $this->create(array_merge($data, [
                'status' => Issue::STATUS_OPENED
            ]));

        } catch (\Exception $e) {
            throw new CreateIssueErrorException($e);
        }
    }

    /**
     * @param int $guarantee_project_id
     * @return int
     */
    public function getCountByProject(int $guarantee_project_id): int
    {
        return Issue::query()->where('guarantee_project_id', $guarantee_project_id)->count();
    }

    /**
     * @param int $id
     * @return Issue
     * @throws NotFoundIssueErrorException
     */
    public function findIssueById(int $id): Issue
    {
        try {
            return $this->find($id);
        } catch (Exception $e) {
            throw new NotFoundIssueErrorException($e);
        }
    }

    /**
     * @param array $data
     * @param int $id
     * @return Issue
     * @throws UpdateIssueErrorException
     */
    public function updateIssue(array $data, int $id): Issue
    {
        try {
            return $this->update($data, $id);
        } catch (Exception $e) {
            throw new UpdateIssueErrorException($e);
        }
    }

    /**
     * @param int $id
     * @param mixed $with
     * @return Issue
     * @throws NotFoundIssueErrorException
     */
    public function getIssueById(int $id, $with = []): Issue
    {
        try {
            return $this->with($with)->find($id);
        } catch (Exception $e) {
            throw new NotFoundIssueErrorException($e);
        }
    }
}