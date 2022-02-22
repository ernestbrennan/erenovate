<?php

namespace App\Travel\Chats\Repositories\Interfaces;

use App\Travel\Chats\Chat;
use App\Travel\Chats\Exceptions\{CreateChatErrorException, DeleteChatErrorException, NotFoundChatErrorException};
use Prettus\Repository\Contracts\RepositoryInterface;

interface ChatRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $data
     * @return Chat
     * @throws CreateChatErrorException
     */
    public function createChat(array $data = []) :Chat;

    /**
     * @param int $id
     * @return bool
     * @throws DeleteChatErrorException
     */
    public function deleteChatById(int $id) :bool;

}