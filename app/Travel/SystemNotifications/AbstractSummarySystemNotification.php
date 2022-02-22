<?php

namespace App\Travel\SystemNotifications;

use App\Events\Broadcast\SendMessageEvent;
use App\Events\Mail\SystemNotificationEmailEvent;
use App\Travel\Messages\Message;

abstract class AbstractSummarySystemNotification extends AbstractSystemNotification
{
    public static function doNotification($contract_draft, $comment = '')
    {
        $chat = $contract_draft->contract->guarantee_project->chat;

        $message = Message::query()
            ->create([
                'sender_id' => \Auth::id(),
                'chat_id' => $chat->id,
                'content' => $comment,
                'type' => Message::TYPE_SYSTEM,
                'receiver_seen' => Message::RECEIVER_NOT_SEEN
            ]);

        $systemNotification = static::createNotification($contract_draft['id'], $message['id'], static::getType());

        $message->load(['files', 'notification']);

        broadcast(new SendMessageEvent($message))->toOthers();
        event(new SystemNotificationEmailEvent($systemNotification));

        return $systemNotification;
    }

    protected static function createNotification($contract_draft_id, $message_id, $type)
    {
        return self::saveSystemNotification([
            'message_id' => $message_id,
            'type' => $type,
        ]);
    }
}
