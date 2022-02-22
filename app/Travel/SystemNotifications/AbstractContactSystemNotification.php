<?php

namespace App\Travel\SystemNotifications;

use App\Events\Broadcast\SendMessageEvent;
use App\Events\Mail\SystemNotificationEmailEvent;
use App\Travel\Messages\Message;

abstract class AbstractContactSystemNotification extends AbstractSystemNotification
{
    public static function doNotification($contract, $comment = '')
    {
        $chat = $contract->guarantee_project->chat;

        $message = Message::query()
            ->create([
                'sender_id' => \Auth::id(),
                'chat_id' => $chat['id'],
                'content' => $comment,
                'type' => Message::TYPE_SYSTEM,
                'receiver_seen' => Message::RECEIVER_NOT_SEEN
            ]);

        $systemNotification = static::createNotification($contract['id'], $message['id'], static::getType());

        $message->load(['files', 'notification']);

        broadcast(new SendMessageEvent($message))->toOthers();
        event(new SystemNotificationEmailEvent($systemNotification));

        return $systemNotification;
    }

    protected static function createNotification($contract_id, $message_id, $type)
    {
        return self::saveSystemNotification([
            'message_id' => $message_id,
            'type' => $type,
            'contract_id' => $contract_id,
            'user_id' => $type === self::TYPE_CONTRACT_FIRST_ACCEPTED ? \Auth::id() : null
        ]);
    }
}
