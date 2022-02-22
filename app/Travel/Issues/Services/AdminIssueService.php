<?php

namespace App\Travel\Issues\Services;

use App\Events\Broadcast\SendMessageEvent;
use App\Travel\Base\BaseFormRequest;
use App\Travel\Chats\Exceptions\CreateChatErrorException;
use App\Travel\Chats\Repositories\Interfaces\ChatRepositoryInterface;
use App\Travel\Issues\Exceptions\CreateIssueErrorException;
use App\Travel\Issues\Exceptions\NotFoundIssueErrorException;
use App\Travel\Issues\Exceptions\UpdateIssueErrorException;
use App\Travel\Issues\Issue;
use App\Travel\Issues\Repositories\Interfaces\IssueRepositoryInterface;
use App\Travel\Messages\Exceptions\CreateMessageErrorException;
use App\Travel\Messages\Message;
use App\Travel\Messages\Repositories\Interfaces\MessageRepositoryInterface;
use App\Travel\Projects\Exceptions\NotFoundGuaranteeProjectErrorException;
use App\Travel\Projects\Repositories\Interfaces\GuaranteeProjectRepositoryInterface;
use App\Travel\SystemNotifications\Issue\SystemNotificationAdminClosed;
use App\Travel\SystemNotifications\Issue\SystemNotificationAdminJoined;
use App\Travel\SystemNotifications\Issue\SystemNotificationIssueClosed;
use App\Travel\SystemNotifications\Issue\SystemNotificationIssueCreated;
use Auth;

class AdminIssueService
{
    /**
     * @var ChatRepositoryInterface
     */
    private $chat_repository;

    /**
     * @var IssueRepositoryInterface
     */
    private $issue_repository;

    /**
     * @var MessageRepositoryInterface
     */
    private $message_repository;

    /**
     * @var GuaranteeProjectRepositoryInterface
     */
    private $guarantee_project_repository;

    /**
     * ChatService constructor.
     *
     * @param ChatRepositoryInterface $chat_repository
     * @param IssueRepositoryInterface $issue_repository
     * @param MessageRepositoryInterface $message_repository
     * @param GuaranteeProjectRepositoryInterface $guarantee_project_repository
     */
    public function __construct(ChatRepositoryInterface $chat_repository,
                                IssueRepositoryInterface $issue_repository,
                                MessageRepositoryInterface $message_repository,
                                GuaranteeProjectRepositoryInterface $guarantee_project_repository)
    {
        $this->chat_repository = $chat_repository;
        $this->issue_repository = $issue_repository;
        $this->message_repository = $message_repository;
        $this->guarantee_project_repository = $guarantee_project_repository;
    }

    /**
     * @param BaseFormRequest $request
     * @return mixed
     * @throws NotFoundGuaranteeProjectErrorException
     */
    public function getByProject(BaseFormRequest $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id', 0);
        $guarantee_project = $this->guarantee_project_repository->getGuaranteeProjectById($guarantee_project_id, ['issues']);
        $issues = $guarantee_project->issues;

        return compact('issues');
    }

    /**
     * @param BaseFormRequest $request
     * @return mixed
     * @throws NotFoundIssueErrorException
     * @throws NotFoundGuaranteeProjectErrorException
     */
    public function getById(BaseFormRequest $request)
    {
        $issue = $this->issue_repository->getIssueById($request->input('id'), [
            'chat',
            'guarantee_project',
            'guarantee_project.homeowner',
            'guarantee_project.contractor',
        ]);

        if ($issue['status'] === Issue::STATUS_OPENED && $issue['type'] === Issue::TYPE_ISSUE) {

            $issue->update(['status' => Issue::STATUS_UNDER_REVIEW,]);
            SystemNotificationAdminJoined::doNotification($issue);
        }

        $chat = [
            'id' => $issue->chat->id,
            'total_message_count' => $issue->chat->messages->count(),
            'messages' => $this->message_repository->getMessagesByChat($issue->chat->id),
            'homeowner' => $issue->guarantee_project->homeowner,
            'contractor' => $issue->guarantee_project->contractor,
        ];

        $guarantee_project = $issue->guarantee_project;

        return compact('issue', 'guarantee_project', 'chat');
    }

    /**
     * @param BaseFormRequest $request
     * @return mixed
     * @throws CreateMessageErrorException
     * @throws NotFoundIssueErrorException
     */
    public function sendMessage(BaseFormRequest $request)
    {
        $issue_id = $request->input('issue_id');
        $files = $request->input('files', []);
        $content = $request->input('content');

        $issue = $this->issue_repository->getIssueById($issue_id, ['chat']);

        $data = [
            'sender_id' => 0,
            'chat_id' => $issue->chat->id,
            'content' => $content,
            'type' => Message::TYPE_ADMIN,
            'receiver_seen' => Message::RECEIVER_NOT_SEEN
        ];
        $message = $this->message_repository->createMessage($data)->load('files');

        $this->message_repository->attachFiles($message, $files);

        broadcast(new SendMessageEvent($message))->toOthers();

        return compact('message');
    }

    /**
     * @param BaseFormRequest $request
     * @return mixed
     * @throws UpdateIssueErrorException
     * @throws NotFoundIssueErrorException
     */
    public function closeIssue(BaseFormRequest $request)
    {
        $issue_id = $request->input('id');
        $comment = $request->input('comment', '');

        $issue = $this->issue_repository->findIssueById($issue_id);

        $this->issue_repository->updateIssue(['status' => Issue::STATUS_CLOSED], $issue_id);

        if ($issue['type'] === Issue::TYPE_ISSUE) {
            SystemNotificationAdminClosed::doNotification($issue, $comment);
        }

        return compact('issue');
    }
}