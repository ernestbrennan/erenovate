<?php

namespace App\Travel\Issues\Services;

use App\Events\Broadcast\SendMessageEvent;
use App\Events\Mail\Admin\IssueEmailEvent as AdminIssueEmailEvent;
use App\Mail\Admin\IssueEmail;
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
use App\Travel\SystemNotifications\Issue\SystemNotificationIssueClosed;
use App\Travel\SystemNotifications\Issue\SystemNotificationIssueCreated;
use Auth;

class IssueService
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
     * @throws NotFoundIssueErrorException
     * @throws NotFoundGuaranteeProjectErrorException
     */
    public function getById(BaseFormRequest $request)
    {
        $issue = $this->issue_repository->getIssueById($request->input('id'), ['chat']);
        $messages = $this->message_repository->getMessagesByChat($issue->chat->id);

        $guarantee_project = $this->guarantee_project_repository->getGuaranteeProjectById($issue->guarantee_project_id);
        $target_user = $this->guarantee_project_repository->getInterlocutorUser($guarantee_project);

        $chat = [
            'id' => $issue->chat->id,
            'total_message_count' => $issue->chat->messages->count(),
            'messages' => $messages,
            'target_user' => $target_user,
        ];

        $guarantee_project = $this->guarantee_project_repository->getBaseData($guarantee_project);

        return compact('issue', 'guarantee_project', 'chat');
    }

    /**
     * @param BaseFormRequest $request
     * @return mixed
     * @throws CreateIssueErrorException
     * @throws CreateChatErrorException
     */
    public function createIssue(BaseFormRequest $request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');
        $title = $request->input('title', '');
        $description = $request->input('description', '');
        $type = $request->input('type', Issue::TYPE_ISSUE);

        $chat = $this->chat_repository->createChat();

        $issue = $this->issue_repository->createIssue([
            'guarantee_project_id' => $guarantee_project_id,
            'chat_id' => $chat->id,
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'sequence' => $this->issue_repository->getCountByProject($guarantee_project_id) + 1
        ]);

        if ($type === Issue::TYPE_ISSUE) {
            SystemNotificationIssueCreated::doNotification($issue, $description);
        }

        event(new AdminIssueEmailEvent($issue));

        return compact('issue');
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

        if ($issue->type === Issue::TYPE_ISSUE) {
            SystemNotificationIssueClosed::doNotification($issue, $comment);
        }

        return compact('issue');
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
            'sender_id' => Auth::id(),
            'chat_id' => $issue->chat->id,
            'content' => $content,
            'type' => Message::TYPE_TEXT,
            'receiver_seen' => Message::RECEIVER_NOT_SEEN
        ];
        $message = $this->message_repository->createMessage($data)->load('files');

        $this->message_repository->attachFiles($message, $files);

        broadcast(new SendMessageEvent($message))->toOthers();

        return compact('message');
    }
}
