<?php

namespace App\Travel\Projects\Services;

use App\Events\Mail\InviteHOEmailEvent;
use App\Events\Mail\WithdrawProjectEmailEvent;
use App\Repositories\ContractDraftRepository;
use App\Travel\Base\BaseFormRequest;
use App\Travel\Chats\Repositories\Interfaces\ChatRepositoryInterface;
use App\Travel\Contracts\Services\ContractService;
use App\Travel\Chats\Exceptions\CreateChatErrorException;
use App\Travel\ContractsDraft\ContractDraft;
use App\Travel\Projects\ErenovateProject;
use App\Travel\SystemNotifications\Contract\SystemNotificationContractProjectHireReady;
use App\Travel\SystemNotifications\User\SystemNotificationUserInvite;
use App\Travel\Users\Exceptions\CreateUserInfoErrorException;
use App\Travel\Contracts\Exceptions\CreateContractErrorException;
use App\Travel\Projects\Exceptions\{
    CreateGuaranteeProjectErrorException,
    DeleteGuaranteeProjectErrorException,
    NotFoundErenovateProjectErrorException,
    NotFoundGuaranteeProjectErrorException,
    UpdateGuaranteeProjectErrorException,
};
use App\Travel\Projects\Repositories\Interfaces\{
    ErenovateProjectRepositoryInterface,
    GuaranteeProjectRepositoryInterface
};
use App\Travel\Projects\GuaranteeProject;
use App\Travel\Users\User;
use App\Travel\Users\UserInfo;
use DB;
use Exception;
use Auth;

class ProjectService
{
    /**
     * @var GuaranteeProjectRepositoryInterface
     */
    private $guarantee_project_repository;

    /**
     * @var ErenovateProjectRepositoryInterface
     */
    private $erenovate_project_repository;

    /**
     * @var ChatRepositoryInterface
     */
    private $chat_repository;

    /**
     * @var ContractService
     */
    private $contract_service;

    /**
     * GuaranteeProjectService constructor.
     *
     * @param GuaranteeProjectRepositoryInterface $guarantee_project_repository
     * @param ErenovateProjectRepositoryInterface $erenovate_project_repository
     * @param ChatRepositoryInterface $chat_repository
     * @param ContractService $contract_service
     */
    public function __construct(GuaranteeProjectRepositoryInterface $guarantee_project_repository,
                                ErenovateProjectRepositoryInterface $erenovate_project_repository,
                                ChatRepositoryInterface $chat_repository,
                                ContractService $contract_service
    )
    {
        $this->guarantee_project_repository = $guarantee_project_repository;
        $this->erenovate_project_repository = $erenovate_project_repository;
        $this->chat_repository = $chat_repository;
        $this->contract_service = $contract_service;
    }

    /**d
     * @param GuaranteeProject $guarantee_project
     * @return array
     * @throws NotFoundGuaranteeProjectErrorException
     */
    public function getBaseData(GuaranteeProject $guarantee_project): array
    {
        try {
            return $this->guarantee_project_repository->getBaseData( $guarantee_project);
        } catch (Exception $e) {
            throw new NotFoundGuaranteeProjectErrorException($e);
        }
    }

    /**
     * @param BaseFormRequest $request
     * @return mixed
     * @throws CreateGuaranteeProjectErrorException
     */
    public function getOrCreate($request)
    {
        $params = $request->only('contractor_id', 'project_id');
        $guarantee_project = $this->guarantee_project_repository->findGuaranteeProjectByParams($params);

        if (empty($guarantee_project)) {

            DB::beginTransaction();
            try {
                $guarantee_project = $this->createWithRelations($params);
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                throw new CreateGuaranteeProjectErrorException($e);
            }
        }

        return compact('guarantee_project');
    }

    public function hireFromErenovate($request)
    {
        $params = $request->only('contractor_id', 'project_id');

        DB::beginTransaction();
        try {
            $guarantee_project = $this->createWithRelations($params);

            $guarantee_project->contract->current_user_info->update([
                'status' => UserInfo::STATUS_CONFIRMED
            ]);
            $guarantee_project->contract->interlocutor_info->update([
                'status' => UserInfo::STATUS_CONFIRMED
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            throw new CreateGuaranteeProjectErrorException($e);
        }

        SystemNotificationContractProjectHireReady::doNotification($guarantee_project->contract);

        return compact('guarantee_project');
    }



    /**
     * @param GuaranteeProject
     * @return GuaranteeProject
     * @throws NotFoundErenovateProjectErrorException
     * @throws CreateChatErrorException
     * @throws CreateContractErrorException
     * @throws CreateGuaranteeProjectErrorException
     * @throws CreateUserInfoErrorException
     */
    public function createWithRelations($params): GuaranteeProject
    {
        $erenovate_project = $this->erenovate_project_repository->findErenovateProjectById($params['project_id']);
        $chat = $this->chat_repository->createChat();

        $guarantee_project = $this->guarantee_project_repository->createGuaranteeProject([
            'contractor_id' => $params['contractor_id'],
            'project_id' => $erenovate_project->id,
            'homeowner_id' => $erenovate_project->user_id,
            'chat_id' => $chat->id,
        ]);

        $this->contract_service->createWithRelations($guarantee_project);

        return $guarantee_project;
    }

    /**
     * @param BaseFormRequest $request
     * @return mixed
     * @throws UpdateGuaranteeProjectErrorException
     */
    public function userVisit($request)
    {
        $data = [Auth::user()->role . '_visited' => 1];
        return $this->guarantee_project_repository->updateGuaranteeProject($data, $request->get('id'));
    }

    /**
     * @param BaseFormRequest $request
     * @return mixed
     * @throws NotFoundGuaranteeProjectErrorException
     */
    public function checkVisited($request)
    {
        $field = Auth::user()->role . '_visited';
        return $this->guarantee_project_repository->findGuaranteeProjectById($request->get('id'))->$field;
    }

    /**
     * @param BaseFormRequest $request
     * @return \mixed
     * @throws DeleteGuaranteeProjectErrorException
     * @throws Exception
     */
    public function withdraw($request): void
    {
        DB::beginTransaction();
        try {

            $guarantee_project = $this->guarantee_project_repository->findGuaranteeProjectById($request->get('id'));

            $this->chat_repository->deleteChatById($guarantee_project->chat_id);
            $this->contract_service->deleteWithRelations($guarantee_project);

//            event(new WithdrawProjectEmailEvent(
//                $this->guarantee_project_repository->getInterlocutorUser($guarantee_project),
//                $guarantee_project->project->project_name)
//            );

            $this->guarantee_project_repository->deleteGuaranteeProjectById($guarantee_project->id);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw new DeleteGuaranteeProjectErrorException($e);
        }

    }

    public function inviteHo($request)
    {
        $contract = $request->input('contract');
        $contract_draft = $request->input('contract_draft');
        $comment = $request->input('comment', '');

        $invite_info = collect($contract['invite_info']);

        $user = User::getOrCreate($invite_info);

        $project_id = ErenovateProject::create(collect([
            'user_id' => $user['userId'],
            'project_name' => $contract_draft['type'] === ContractDraft::TYPE_SINGLE_MILESTONE ?
                $contract_draft['milestones'][0]['milestone_content']['title'] :
                $contract_draft['title']
        ]));

        $guarantee_project = GuaranteeProject::getOrCreate($project_id, Auth::id());
        $contract_id = $guarantee_project->contract['id'];

        UserInfo::where('contract_id', $contract_id)
            ->where('user_id', Auth::id())
            ->update(
                collect($contract['current_user_info'])
                    ->put('status', UserInfo::STATUS_PENDING)
                    ->except(['id', 'created_at', 'updated_at'])
                    ->toArray()
            );

        $contract_draft = collect($contract_draft)->put('contract_id', $contract_id);

        if ($archived_draft = Auth::user()->archived_drafts()->find($request->get('archived_draft_id'))) {
            $archived_draft->delete();
        };

        ContractDraftRepository::createWithRelations($contract_draft, 1);

        event(new InviteHOEmailEvent($guarantee_project, $user->wasRecentlyCreated));
        SystemNotificationUserInvite::doNotification($guarantee_project, $comment);

        return [
            'guarantee_project' => $guarantee_project->only('id'),
        ];
    }
}
