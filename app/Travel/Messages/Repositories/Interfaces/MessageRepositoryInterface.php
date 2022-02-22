<?php

namespace App\Travel\Messages\Repositories\Interfaces;

use App\Travel\Messages\Exceptions\{CreateMessageErrorException,
    UpdateMessageErrorException,
    NotFoundMessageErrorException};
use App\Travel\Messages\Message;
use Prettus\Repository\Contracts\RepositoryInterface;

interface MessageRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $chat_id
     * @param int $limit
     * @param int $offset
     * @return Message []
     */
    public function getMessagesByChat($chat_id, $limit = 15, $offset = 0);

    /**
     * @param array $data
     * @return Message
     * @throws CreateMessageErrorException
     */
    public function createMessage(array $data): Message;

    /**
     * @param array $files
     * @param Message $model
     */
    public function attachFiles(Message $model, $files): void;

    /**
     * @param array $data
     * @param int $id
     * @return Message
     * @throws UpdateMessageErrorException
     */
    public function updateMessage(array $data, int $id): Message;
}