<?php

namespace App\Travel\Contracts\Services;

use App\Travel\Base\BaseFormRequest;
use App\Travel\Contracts\Exceptions\{
    NotFoundContractErrorException
};
use App\Travel\Contracts\Repositories\Interfaces\ContractRepositoryInterface;
use App\Travel\Contracts\Requests\GetListRequest;
use App\Travel\Messages\Message;
use App\Travel\Projects\GuaranteeProject;
use App\Travel\Projects\Repositories\Interfaces\GuaranteeProjectRepositoryInterface;
use App\Travel\Users\Repositories\Interfaces\UserInfoRepositoryInterface;
use App\Travel\Users\Repositories\Interfaces\UserRepositoryInterface;
use App\Travel\Users\User;
use Auth;

class ContractService
{
    /**
     * @var ContractRepositoryInterface
     */
    private $contract_repository;

    /**
     * @var GuaranteeProjectRepositoryInterface
     */
    private $guarantee_project_repository;

    /**
     * @var UserRepositoryInterface
     */
    private $user_repository;
    /**
     * @var UserInfoRepositoryInterface
     */
    private $user_info_repository;

    /**
     * ChatService constructor.
     *
     * @param ContractRepositoryInterface $contract_repository
     * @param GuaranteeProjectRepositoryInterface $guarantee_project_repository
     * @param UserRepositoryInterface $user_repository
     * @param UserInfoRepositoryInterface $user_info_repository
     */
    public function __construct(ContractRepositoryInterface $contract_repository,
                                GuaranteeProjectRepositoryInterface $guarantee_project_repository,
                                UserRepositoryInterface $user_repository,
                                UserInfoRepositoryInterface $user_info_repository)
    {
        $this->contract_repository = $contract_repository;
        $this->guarantee_project_repository = $guarantee_project_repository;
        $this->user_repository = $user_repository;
        $this->user_info_repository = $user_info_repository;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function listContract(BaseFormRequest $request)
    {
        $with = ['contract', 'contractor', 'project', 'chat'];

        $guarantee_projects = $this->guarantee_project_repository->listGuaranteeProjectByUser(Auth::user(), $with);

        $query_search = $request->get('query_search', '');
        $projects = [];
        $guarantee_projects->map(function ($guarantee_project) use ($query_search, &$projects) {

            if (!$guarantee_project->contract || !$guarantee_project->contractor || !$guarantee_project->homeowner ) return;

            $project_name = $guarantee_project->project->project_name ?? '';
            $target_user = $this->guarantee_project_repository->getInterlocutorUser($guarantee_project);
            $contract = $guarantee_project->contract->getStatusInfo();

            $compare_strings = compareStrings($query_search, $project_name) ||
                compareStrings($query_search, $target_user->firstname) ||
                compareStrings($query_search, $target_user->lastname);

            if (($query_search && $compare_strings) || !$query_search) {

                $contractor_user_info = Auth::user()->role === User::ROLE_HOMEOWNER ? $guarantee_project->contract->interlocutor_info : $guarantee_project->contract->current_user_info;
                $guarantee_project_id = $guarantee_project->id;

                $projects[] = [
                    'guarantee_project_id' => $guarantee_project_id,
                    'project_name' => $project_name,
                    'target_user' => $target_user->only(['firstname', 'lastname', 'photo', 'role', 'user_photo']),
                    'contract' => $contract,
                    'title' => trans('contract.contract_name', [
                        'representative_name' => $contractor_user_info->representative_name,
                        'company_name' => $contractor_user_info->company_name
                    ]),
                    'chat' => [
                        'id' => $guarantee_project->chat->id,
                        'unread_messages_count' => $guarantee_project->chat->unread_messages->count(),
                    ],
                ];
            }

        });

        return compact('projects');
    }

    /**
     * @param GuaranteeProject
     * @throws \App\Travel\Contracts\Exceptions\CreateContractErrorException
     * @throws \App\Travel\Users\Exceptions\CreateUserInfoErrorException
     */
    public function createWithRelations($guarantee_project): void
    {
        $contract = $this->contract_repository->createContract([
            'guarantee_project_id' => $guarantee_project->id
        ]);

        $contractor_info = $this->user_repository->getForUserInfo($guarantee_project->contractor);
        $homeowner_info = $this->user_repository->getForUserInfo($guarantee_project->homeowner);

        $this->user_info_repository->createUserInfo(array_merge($contractor_info, ['contract_id' => $contract['id']]));
        $this->user_info_repository->createUserInfo(array_merge($homeowner_info, ['contract_id' => $contract['id']]));
    }

    /**
     * @param GuaranteeProject
     */
    public function deleteWithRelations($guarantee_project): void
    {
        $guarantee_project->contract->drafts->each->delete();
        $guarantee_project->contract->delete();
    }
}
