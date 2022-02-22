<?php

namespace App\Travel\SystemNotifications;

use App\Events\Broadcast\SendMessageEvent;
use App\Events\Mail\SystemNotificationEmailEvent;
use App\Models\GuaranteeProject;
use App\Models\Message;

abstract class AbstractMaterialsContentSystemNotification extends AbstractSystemNotification
{
    public static function doNotification($materials_content, $guarantee_project_id,  $comment = '')
    {
        $guarantee_project = GuaranteeProject::with('chat')->find($guarantee_project_id);
        $chat = $guarantee_project->chat;

        $message = Message::query()
            ->create([
                'sender_id' => \Auth::id(),
                'chat_id' => $chat->id,
                'content' => $comment,
                'type' => Message::TYPE_SYSTEM,
                'receiver_seen' => Message::RECEIVER_NOT_SEEN
            ]);

        $systemNotification = static::createInvoiceSystemNotification($materials_content['id'], $message['id'], static::getType());

        $message->load(['files', 'notification']);

        broadcast(new SendMessageEvent($message))->toOthers();
        event(new SystemNotificationEmailEvent( $systemNotification));

        return $systemNotification;
    }

    protected static function createInvoiceSystemNotification($milestone_id, $message_id, $type)
    {
        return self::saveSystemNotification([
            'message_id' => $message_id,
            'type' => $type,
            'materials_content_id' => $milestone_id
        ]);
    }
}
