<?php

namespace App\Travel\Messages\Repositories;

use App\Travel\Projects\Exceptions\{
    NotFoundGuaranteeProjectErrorException,
    UpdateGuaranteeProjectErrorException
};
use App\Travel\Projects\GuaranteeProject;
use App\Travel\Messages\Exceptions\CreateMessageErrorException;
use App\Travel\Messages\Exceptions\UpdateMessageErrorException;
use App\Travel\Messages\Message;
use App\Travel\Messages\Repositories\Interfaces\MessageRepositoryInterface;

use App\Travel\SystemNotifications\AbstractSystemNotification;
use DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Exception;

class MessageRepositoryEloquent extends BaseRepository implements MessageRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Message::class;
    }

    /**
     * @param int $chat_id
     * @param int $limit
     * @param int $offset
     * @return Message []
     */
    public function getMessagesByChat($chat_id, $limit = 15, $offset = 0)
    {
        return Message::with([
            'files',
            'notification',
        ])
            ->where('chat_id', $chat_id)
            ->offset($offset)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get()
            ->each(function (Message $message) {
                $notification = $message->notification;
                if (!$notification) return;

                if (in_array($notification['type'], AbstractSystemNotification::CONTRACT)) {
                    $notification->load('contract');
                }
                if (in_array($notification['type'], AbstractSystemNotification::CONTRACT_DRAFT)) {
                    $notification->load('contract_draft');

                } else if (in_array($notification['type'], AbstractSystemNotification::MILESTONE_CONTENT)) {
                    $notification->load('milestone_content.milestone');

                } else if (in_array($notification['type'], AbstractSystemNotification::MATERIALS_CONTENT)) {
                    $notification->load('materials_content');

                } else if (in_array($notification['type'], AbstractSystemNotification::INVOICE)) {
                    $notification->load('invoice.milestone');

                } else if (in_array($notification['type'], AbstractSystemNotification::ISSUE)) {
                    $notification->load('issue');
                }

                $notification['title'] = $notification->getTitle();
                $message->unsetRelation('sender');
            });
    }

    /**
     * @param array $data
     * @return Message
     * @throws CreateMessageErrorException
     */
    public function createMessage(array $data): Message
    {
        try {
            return $this->create($data);
        } catch (\Exception $e) {
            throw new CreateMessageErrorException($e);
        }
    }

    /**
     * @param array $files
     * @param Message $model
     */
    public function attachFiles(Message $model, $files): void
    {
        foreach ($files as $file) {
            $model->files()->attach($file['id']);
        }
    }

    /**
     * @param array $data
     * @param int $id
     * @return Message
     * @throws UpdateMessageErrorException
     */
    public function updateMessage(array $data, int $id) : Message
    {
        DB::beginTransaction();

        try {
            $message = $this->update($data, $id);

            DB::commit();
            return $message;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new UpdateMessageErrorException($e);
        }

    }
}