<?php

namespace App\Travel\Issues\Repositories\Interfaces;

use App\Travel\Issues\Exceptions\CreateIssueErrorException;
use App\Travel\Issues\Exceptions\NotFoundIssueErrorException;
use App\Travel\Issues\Exceptions\UpdateIssueErrorException;
use App\Travel\Issues\Issue;
use Prettus\Repository\Contracts\RepositoryInterface;

interface IssueRepositoryInterface extends RepositoryInterface
{

    /**
     * @param array $data
     * @return Issue
     * @throws CreateIssueErrorException
     */
    public function createIssue(array $data = []) :Issue;

    /**
     * @param int $guarantee_project_id
     * @return int
     */
    public function getCountByProject(int $guarantee_project_id) :int;

    /**
     * @param int $id
     * @return Issue
     * @throws NotFoundIssueErrorException
     */
    public function findIssueById(int $id): Issue;

    /**
     * @param int $id
     * @param mixed $with
     * @return Issue
     * @throws NotFoundIssueErrorException
     */
    public function getIssueById(int $id, $with = []): Issue;


    /**
     * @param array $data
     * @param int $id
     * @return Issue
     * @throws UpdateIssueErrorException
     */
    public function updateIssue(array $data, int $id): Issue;

}