<?php

namespace App\Travel\Messages\Services;

use App\Events\Broadcast\ReadMessagesEvent;
use App\Events\Broadcast\SendMessageEvent;
use App\Events\Mail\NewMessageEmailEvent;
use App\Travel\Base\BaseFormRequest;
use App\Travel\Projects\Exceptions\{
    NotFoundGuaranteeProjectErrorException
};
use App\Travel\Projects\Repositories\Interfaces\GuaranteeProjectRepositoryInterface;
use App\Travel\Messages\Exceptions\CreateMessageErrorException;
use App\Travel\Messages\Exceptions\UpdateMessageErrorException;
use App\Travel\Messages\Message;
use App\Travel\Messages\Repositories\Interfaces\MessageRepositoryInterface;
use Auth;

class MessageService
{
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
     * @param MessageRepositoryInterface $message_repository
     * @param GuaranteeProjectRepositoryInterface $guarantee_project_repository
     */
    public function __construct(MessageRepositoryInterface $message_repository,
                                GuaranteeProjectRepositoryInterface $guarantee_project_repository)
    {
        $this->message_repository = $message_repository;
        $this->guarantee_project_repository = $guarantee_project_repository;
    }

    public function getMessagesByChat(BaseFormRequest $request)
    {
        $chat_id = $request->input('chat_id');
        $per_page = $request->input('per_page', 15);
        $offset = $request->input('offset', 0);

        return $this->message_repository->getMessagesByChat($chat_id, $per_page, $offset);
    }

    /**
     * @param $request
     * @return mixed
     * @throws NotFoundGuaranteeProjectErrorException
     * @throws CreateMessageErrorException
     */
    public function sendMessage(BaseFormRequest $request)
    {
        $files = $request->input('files', []);
        $guarantee_project_id = $request->input('guarantee_project_id');
        $content = $request->input('content');

        $guarantee_project = $this->guarantee_project_repository->getGuaranteeProjectById($guarantee_project_id, ['chat']);

        $data = [
            'sender_id' => Auth::id(),
            'chat_id' => $guarantee_project->chat->id,
            'content' => $content,
            'type' => Message::TYPE_TEXT,
            'receiver_seen' => Message::RECEIVER_NOT_SEEN
        ];

        $message = $this->message_repository->createMessage($data)->load('files');

        $this->message_repository->attachFiles($message, $files);

        event(new NewMessageEmailEvent($message));
        broadcast(new SendMessageEvent($message))->toOthers();

        return compact('message');
    }

    /**
     * @param $request
     * @return mixed
     * @throws UpdateMessageErrorException
     */
    public function readMessage(BaseFormRequest $request)
    {
        $id = $request->get('id');
        $message = $this->message_repository->updateMessage(['receiver_seen' => Message::RECEIVER_SEEN], $id);
        broadcast(new ReadMessagesEvent($message));

        return $message;
    }
}
