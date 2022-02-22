<?php

namespace App\Travel\Chats\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Travel\Chats\Exceptions\{CreateChatErrorException, DeleteChatErrorException, NotFoundChatErrorException};
use App\Travel\Chats\Repositories\Interfaces\ChatRepositoryInterface;
use App\Travel\Chats\Chat;

class ChatRepositoryEloquent extends BaseRepository implements ChatRepositoryInterface
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Chat::class;
    }

    /**
     * @param array $data
     * @return Chat
     * @throws CreateChatErrorException
     */
    public function createChat(array $data = []): Chat
    {
        try {
            return $this->create($data);
        } catch (\Exception $e) {
            throw new CreateChatErrorException($e);
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws DeleteChatErrorException
     */
    public function deleteChatById(int $id): bool
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            throw new DeleteChatErrorException($e);
        }
    }
}