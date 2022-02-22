<?php

namespace App\Travel\Chats\Services;

use App\Travel\Base\BaseFormRequest;
use App\Travel\Chats\Exceptions\{
    NotFoundChatErrorException
};
use App\Travel\Chats\Repositories\Interfaces\ChatRepositoryInterface;
use App\Travel\Projects\Exceptions\NotFoundGuaranteeProjectErrorException;
use App\Travel\Projects\Repositories\Interfaces\GuaranteeProjectRepositoryInterface;
use App\Travel\Messages\Repositories\Interfaces\MessageRepositoryInterface;
use Auth;

class ChatService
{
    /**
     * @var ChatRepositoryInterface
     */
    private $chat_repository;

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
     * @param MessageRepositoryInterface $message_repository
     * @param GuaranteeProjectRepositoryInterface $guarantee_project_repository
     */
    public function __construct(ChatRepositoryInterface $chat_repository,
                                MessageRepositoryInterface $message_repository,
                                GuaranteeProjectRepositoryInterface $guarantee_project_repository)
    {
        $this->chat_repository = $chat_repository;
        $this->message_repository = $message_repository;
        $this->guarantee_project_repository = $guarantee_project_repository;
    }

    /**
     * @param BaseFormRequest $request
     * @return mixed
     * @throws NotFoundGuaranteeProjectErrorException
     */
    public function getByProject($request)
    {
        $guarantee_project_id = $request->input('guarantee_project_id');
        $with = ['chat', 'contractor', 'homeowner'];

        $guarantee_project = $this->guarantee_project_repository->getGuaranteeProjectById($guarantee_project_id, $with);
        $target_user = $this->guarantee_project_repository->getInterlocutorUser($guarantee_project);
        $messages = $this->message_repository->getMessagesByChat($guarantee_project->chat['id']);

        $chat = [
            'id' => $guarantee_project->chat['id'],
            'total_message_count' => $guarantee_project->chat->messages->count(),
            'unread_messages_count' => $guarantee_project->chat->unread_messages->count(),
            'messages' => $messages,
            'target_user' => $target_user->only(['firstname', 'lastname', 'photo', 'role', 'user_photo', 'userId']),
        ];

        $guarantee_project = $this->guarantee_project_repository->getBaseData($guarantee_project);

        return compact('guarantee_project', 'chat');
    }
}